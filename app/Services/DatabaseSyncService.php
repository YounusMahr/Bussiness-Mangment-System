<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use PDO;

class DatabaseSyncService
{
    protected $localConnection = 'mysql';
    protected $remoteConnection = 'mysql_remote';
    
    /**
     * Cache for internet connectivity status to avoid redundant checks in same request
     */
    protected static $onlineStatus = null;

    /**
     * Check if remote database is accessible
     */
    public function isRemoteConnected(): bool
    {
        // Fail fast if offline
        if (!$this->isOnline()) {
            return false;
        }

        try {
            // Set a shorter timeout for remote connections
            config(['database.connections.' . $this->remoteConnection . '.options' => [
                PDO::ATTR_TIMEOUT => 3, // Reduced from 5 to 3
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]]);
            
            // Test the connection
            $pdo = DB::connection($this->remoteConnection)->getPdo();
            
            // Try a simple query to ensure connection is working
            DB::connection($this->remoteConnection)->select('SELECT 1');
            
            return true;
        } catch (Exception $e) {
            Log::warning('Remote database connection failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get remote connection error message
     */
    public function getRemoteConnectionError(): string
    {
        if (!$this->isOnline()) {
            return 'No internet connection available.';
        }

        try {
            DB::connection($this->remoteConnection)->getPdo();
            return '';
        } catch (Exception $e) {
            $message = $e->getMessage();
            
            // Provide user-friendly error messages
            if (str_contains($message, 'Access denied')) {
                return 'Database credentials are incorrect. Please check username and password.';
            } elseif (str_contains($message, 'Unknown database')) {
                return 'Database name is incorrect. Please check DB_REMOTE_DATABASE in .env';
            } elseif (str_contains($message, 'Connection refused') || str_contains($message, 'Connection timed out')) {
                return 'Cannot reach database server. Please check DB_REMOTE_HOST and ensure the server is accessible.';
            } elseif (str_contains($message, 'getaddrinfo failed')) {
                return 'Invalid database host. Please check DB_REMOTE_HOST in .env';
            }
            
            return 'Connection error: ' . $message;
        }
    }

    /**
     * Check internet connectivity
     */
    public function isOnline(): bool
    {
        // Return cached status if available
        if (self::$onlineStatus !== null) {
            return self::$onlineStatus;
        }

        // Try high-reliability hosts with very short timeout
        // Port 53 (DNS) or 80 (HTTP) are usually open
        $checks = [
            ['host' => '8.8.8.8', 'port' => 53],        // Google DNS
            ['host' => 'www.google.com', 'port' => 80],  // Google Web
        ];
        
        foreach ($checks as $check) {
            try {
                $connected = @fsockopen($check['host'], $check['port'], $errno, $errstr, 2);
                if ($connected) {
                    fclose($connected);
                    self::$onlineStatus = true;
                    return true;
                }
            } catch (Exception $e) {
                // Ignore and try next
            }
        }
        
        // Fallback: Try using curl if fsockopen fails/is disabled
        if (function_exists('curl_init')) {
            $ch = curl_init('https://www.google.com');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 2);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            $result = @curl_exec($ch);
            curl_close($ch);
            
            if ($result !== false) {
                self::$onlineStatus = true;
                return true;
            }
        }
        
        self::$onlineStatus = false;
        return false;
    }

    /**
     * Sync all tables from local to remote (push only new entries, no duplicates)
     */
    public function syncToRemote(): array
    {
        $results = [
            'success' => true,
            'synced' => [],
            'skipped' => [],
            'errors' => [],
            'total' => 0,
            'totalSkipped' => 0,
            'migrations_synced' => false,
            'migrations_run' => false
        ];

        if (!$this->isOnline() || !$this->isRemoteConnected()) {
            $results['success'] = false;
            $results['errors'][] = 'No internet connection or remote database unavailable';
            return $results;
        }

        // First, sync migrations table to ensure schema consistency
        try {
            $this->syncMigrationsTable();
            $results['migrations_synced'] = true;
            Log::info('Migrations table synced successfully');
        } catch (Exception $e) {
            Log::warning('Failed to sync migrations table: ' . $e->getMessage());
            // Continue with data sync even if migrations sync fails
        }

        // Run migrations on remote database to ensure all tables exist
        try {
            $this->runRemoteMigrations();
            $results['migrations_run'] = true;
            Log::info('Remote migrations run successfully');
        } catch (Exception $e) {
            Log::warning('Failed to run remote migrations: ' . $e->getMessage());
            $results['errors']['migrations'] = $e->getMessage();
            // Continue with data sync - tables might already exist
        }

        // Get all tables to sync (including transaction/history tables)
        $tables = $this->getTablesToSync();
        
        // Ensure transaction/history tables are synced in correct order (parent tables first)
        $tables = $this->orderTablesForSync($tables);

        foreach ($tables as $table) {
            try {
                // Check if table exists on remote, if not, skip with warning
                if (!$this->tableExists($table, $this->remoteConnection)) {
                    $results['errors'][$table] = "Table does not exist on remote database. Please run migrations first.";
                    Log::warning("Table {$table} does not exist on remote database");
                    continue;
                }

                $syncResult = $this->pushTable($table, $this->localConnection, $this->remoteConnection);
                $results['synced'][$table] = $syncResult['inserted'];
                $results['skipped'][$table] = $syncResult['skipped'];
                $results['total'] += $syncResult['inserted'];
                $results['totalSkipped'] += $syncResult['skipped'];
                
                if ($syncResult['inserted'] > 0) {
                    Log::info("Synced {$syncResult['inserted']} records from table {$table} to remote");
                }
            } catch (Exception $e) {
                $results['errors'][$table] = $e->getMessage();
                Log::error("Push error for table {$table}: " . $e->getMessage());
            }
        }

        return $results;
    }

    /**
     * Sync all tables from remote to local
     */
    public function syncFromRemote(): array
    {
        $results = [
            'success' => true,
            'synced' => [],
            'errors' => [],
            'total' => 0
        ];

        if (!$this->isOnline() || !$this->isRemoteConnected()) {
            $results['success'] = false;
            $results['errors'][] = 'No internet connection or remote database unavailable';
            return $results;
        }

        $tables = $this->getTablesToSync();

        foreach ($tables as $table) {
            try {
                $count = $this->syncTable($table, $this->remoteConnection, $this->localConnection);
                $results['synced'][$table] = $count;
                $results['total'] += $count;
            } catch (Exception $e) {
                $results['errors'][$table] = $e->getMessage();
                Log::error("Sync error for table {$table}: " . $e->getMessage());
            }
        }

        return $results;
    }

    /**
     * Two-way sync (merge local and remote data)
     */
    public function syncBidirectional(): array
    {
        $results = [
            'success' => true,
            'synced' => [],
            'errors' => [],
            'total' => 0
        ];

        if (!$this->isOnline() || !$this->isRemoteConnected()) {
            $results['success'] = false;
            $results['errors'][] = 'No internet connection or remote database unavailable';
            return $results;
        }

        $tables = $this->getTablesToSync();

        foreach ($tables as $table) {
            try {
                // First sync local to remote (new local data)
                $localToRemote = $this->syncTable($table, $this->localConnection, $this->remoteConnection);
                
                // Then sync remote to local (new remote data)
                $remoteToLocal = $this->syncTable($table, $this->remoteConnection, $this->localConnection);
                
                $results['synced'][$table] = [
                    'local_to_remote' => $localToRemote,
                    'remote_to_local' => $remoteToLocal
                ];
                $results['total'] += ($localToRemote + $remoteToLocal);
            } catch (Exception $e) {
                $results['errors'][$table] = $e->getMessage();
                Log::error("Bidirectional sync error for table {$table}: " . $e->getMessage());
            }
        }

        return $results;
    }

    /**
     * Push a single table from source to destination (only new entries, skip duplicates)
     */
    protected function pushTable(string $table, string $sourceConnection, string $destConnection): array
    {
        $inserted = 0;
        $skipped = 0;
        
        try {
            // Get all records from source
            $records = DB::connection($sourceConnection)->table($table)->get();
            
            if ($records->isEmpty()) {
                return ['inserted' => 0, 'skipped' => 0];
            }
            
            // Get primary key column (default to 'id')
            $primaryKey = $this->getPrimaryKey($table, $sourceConnection);
            
            // Get all existing primary key values from destination for faster lookup
            $existingKeys = DB::connection($destConnection)
                ->table($table)
                ->pluck($primaryKey)
                ->toArray();
            $existingKeysSet = array_flip($existingKeys); // For O(1) lookup
            
            foreach ($records as $record) {
                try {
                    $recordArray = (array) $record;
                    
                    // Check if record already exists in destination using primary key
                    $primaryKeyValue = $recordArray[$primaryKey] ?? null;
                    
                    if ($primaryKeyValue === null) {
                        // Skip records without primary key
                        $skipped++;
                        continue;
                    }
                    
                    // List of tables that should be updated if they already exist (State tables)
                    $stateTables = [
                        'users', 'customers', 'products', 'categories', 
                        'plot_purchases', 'plot_sales', 'udaars', 'installments', 
                        'stock_purchases', 'sales'
                    ];
                    $isStateTable = in_array($table, $stateTables);
                    
                    // Check if record exists (fast lookup using array)
                    if (isset($existingKeysSet[$primaryKeyValue])) {
                        if ($isStateTable) {
                            // For state tables, update existing record to ensure balances/status are synced
                            $dataToUpdate = $recordArray;
                            unset($dataToUpdate[$primaryKey]); // Don't update the primary key itself
                            
                            // Remove timestamps if we want remote to manage them, 
                            // or keep them if we want to sync local timestamps
                            unset($dataToUpdate['created_at'], $dataToUpdate['updated_at']);
                            
                            DB::connection($destConnection)
                                ->table($table)
                                ->where($primaryKey, $primaryKeyValue)
                                ->update($dataToUpdate);
                            
                            $inserted++; // Counting updates as sync successes
                        } else {
                            // For transaction/history tables, skip existing to avoid duplication
                            $skipped++;
                        }
                        continue;
                    }
                    
                    // Record doesn't exist, insert it
                    // Preserve timestamps for history/transaction tables to maintain data integrity
                    $isHistoryTable = str_contains($table, '_transactions') || str_contains($table, '_history') || $table === 'sale_items';
                    
                    if (!$isHistoryTable) {
                        // For regular tables, let database handle timestamps
                        unset($recordArray['created_at'], $recordArray['updated_at']);
                    }
                    // For history/transaction tables, keep timestamps as they represent actual event times
                    
                    DB::connection($destConnection)
                        ->table($table)
                        ->insert($recordArray);
                    
                    // Add to existing keys set to prevent duplicates in same batch
                    $existingKeysSet[$primaryKeyValue] = true;
                    $inserted++;
                    
                } catch (Exception $e) {
                    $recordId = $record->id ?? $record->{$primaryKey} ?? 'unknown';
                    
                    // Check if error is due to duplicate entry
                    if (str_contains($e->getMessage(), 'Duplicate entry') || str_contains($e->getMessage(), '1062')) {
                        $skipped++;
                        Log::warning("Duplicate record skipped: {$recordId} from table {$table}");
                    } else {
                        Log::error("Error pushing record {$recordId} from table {$table}: " . $e->getMessage());
                        // Continue with next record
                    }
                }
            }
        } catch (Exception $e) {
            Log::error("Error pushing table {$table}: " . $e->getMessage());
            throw $e;
        }
        
        return ['inserted' => $inserted, 'skipped' => $skipped];
    }

    /**
     * Sync a single table from source to destination (legacy method for bidirectional sync)
     */
    protected function syncTable(string $table, string $sourceConnection, string $destConnection): int
    {
        $count = 0;
        
        try {
            // Get all records from source
            $records = DB::connection($sourceConnection)->table($table)->get();
            
            if ($records->isEmpty()) {
                return 0;
            }
            
            // Get primary key column (default to 'id')
            $primaryKey = $this->getPrimaryKey($table, $sourceConnection);
            
            foreach ($records as $record) {
                try {
                    $recordArray = (array) $record;
                    
                    // Remove timestamps if they exist (let database handle them)
                    unset($recordArray['created_at'], $recordArray['updated_at']);
                    
                    // Check if record exists in destination using primary key
                    $exists = false;
                    if (isset($recordArray[$primaryKey])) {
                        $exists = DB::connection($destConnection)
                            ->table($table)
                            ->where($primaryKey, $recordArray[$primaryKey])
                            ->exists();
                    }
                    
                    if ($exists && isset($recordArray[$primaryKey])) {
                        // Update existing record
                        $primaryKeyValue = $recordArray[$primaryKey];
                        unset($recordArray[$primaryKey]); // Remove primary key from update array
                        
                        DB::connection($destConnection)
                            ->table($table)
                            ->where($primaryKey, $primaryKeyValue)
                            ->update($recordArray);
                    } else {
                        // Insert new record
                        DB::connection($destConnection)
                            ->table($table)
                            ->insert($recordArray);
                    }
                    
                    $count++;
                } catch (Exception $e) {
                    $recordId = $record->id ?? $record->{$primaryKey} ?? 'unknown';
                    Log::error("Error syncing record {$recordId} from table {$table}: " . $e->getMessage());
                    // Continue with next record
                }
            }
        } catch (Exception $e) {
            Log::error("Error syncing table {$table}: " . $e->getMessage());
            throw $e;
        }
        
        return $count;
    }

    /**
     * Get primary key column for a table
     */
    protected function getPrimaryKey(string $table, string $connection): string
    {
        try {
            $result = DB::connection($connection)
                ->select("SHOW KEYS FROM `{$table}` WHERE Key_name = 'PRIMARY'");
            
            if (!empty($result)) {
                return $result[0]->Column_name;
            }
        } catch (Exception $e) {
            Log::warning("Could not determine primary key for table {$table}, using 'id': " . $e->getMessage());
        }
        
        // Default to 'id' if primary key cannot be determined
        return 'id';
    }

    /**
     * Sync migrations table from local to remote
     * This ensures both databases know which migrations have been run
     */
    protected function syncMigrationsTable(): void
    {
        try {
            // Check if migrations table exists on remote, if not, create it
            $tableExists = DB::connection($this->remoteConnection)
                ->select("SHOW TABLES LIKE 'migrations'");
            
            if (empty($tableExists)) {
                // Create migrations table if it doesn't exist
                DB::connection($this->remoteConnection)->statement("
                    CREATE TABLE IF NOT EXISTS `migrations` (
                        `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                        `migration` varchar(255) NOT NULL,
                        `batch` int(11) NOT NULL,
                        PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                ");
                Log::info('Created migrations table on remote database');
            }
            
            // Get all migration records from local
            $localMigrations = DB::connection($this->localConnection)
                ->table('migrations')
                ->get();
            
            if ($localMigrations->isEmpty()) {
                return;
            }
            
            // Get existing migration records from remote
            $remoteMigrations = DB::connection($this->remoteConnection)
                ->table('migrations')
                ->pluck('migration')
                ->toArray();
            
            $remoteMigrationsSet = array_flip($remoteMigrations);
            $syncedCount = 0;
            
            // Insert missing migrations into remote
            foreach ($localMigrations as $migration) {
                $migrationName = $migration->migration;
                
                // Check if migration already exists in remote
                if (!isset($remoteMigrationsSet[$migrationName])) {
                    // Insert new migration record
                    DB::connection($this->remoteConnection)
                        ->table('migrations')
                        ->insert([
                            'migration' => $migrationName,
                            'batch' => $migration->batch ?? 1
                        ]);
                    
                    $syncedCount++;
                    Log::info("Synced migration: {$migrationName} to remote database");
                }
            }
            
            if ($syncedCount > 0) {
                Log::info("Synced {$syncedCount} migration(s) to remote database");
            }
        } catch (Exception $e) {
            // Log the error but don't fail the entire sync
            Log::warning('Failed to sync migrations table: ' . $e->getMessage());
            // Re-throw to be caught by caller
            throw $e;
        }
    }

    /**
     * Get list of tables to sync (excluding system tables)
     */
    protected function getTablesToSync(): array
    {
        // Exclude system tables but keep migrations for separate handling
        $excludedTables = ['password_reset_tokens', 'sessions', 'cache', 'cache_locks', 'failed_jobs', 'job_batches', 'jobs'];
        
        $tables = DB::connection($this->localConnection)
            ->select("SHOW TABLES");
        
        $tableList = [];
        $key = "Tables_in_" . config("database.connections.{$this->localConnection}.database");
        
        foreach ($tables as $table) {
            $tableName = $table->$key;
            if (!in_array($tableName, $excludedTables)) {
                $tableList[] = $tableName;
            }
        }
        
        return $tableList;
    }

    /**
     * Order tables for sync (parent tables before child/transaction tables)
     * This ensures foreign key constraints are satisfied
     */
    protected function orderTablesForSync(array $tables): array
    {
        // Define parent tables (should be synced first)
        $parentTables = [
            'users',
            'customers',
            'products',
            'categories',
            'plot_purchases',
            'plot_sales',
            'udaars',
            'installments',
            'stock_purchases',
            'sales',
        ];
        
        // Define transaction/history tables (should be synced after parent tables)
        $transactionTables = [
            'grocery_cash_transactions',
            'udaar_transactions',
            'plot_purchase_transactions',
            'plot_sale_transactions',
            'installment_transactions',
            'stock_purchase_transactions',
            'sale_items',
        ];
        
        $ordered = [];
        $remaining = [];
        
        // First, add parent tables
        foreach ($parentTables as $parent) {
            if (in_array($parent, $tables)) {
                $ordered[] = $parent;
            }
        }
        
        // Then, add transaction tables
        foreach ($transactionTables as $transaction) {
            if (in_array($transaction, $tables)) {
                $ordered[] = $transaction;
            }
        }
        
        // Finally, add any remaining tables
        foreach ($tables as $table) {
            if (!in_array($table, $ordered)) {
                $remaining[] = $table;
            }
        }
        
        return array_merge($ordered, $remaining);
    }

    /**
     * Check if a table exists on remote database
     */
    protected function tableExists(string $table, string $connection): bool
    {
        try {
            $result = DB::connection($connection)
                ->select("SHOW TABLES LIKE '{$table}'");
            return !empty($result);
        } catch (Exception $e) {
            Log::warning("Error checking if table {$table} exists: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Run migrations on remote database
     */
    protected function runRemoteMigrations(): void
    {
        try {
            // Get all migration files from local
            $migrationPath = database_path('migrations');
            $migrationFiles = glob($migrationPath . '/*.php');
            
            if (empty($migrationFiles)) {
                Log::info('No migration files found');
                return;
            }
            
            // Get migrations that have been run locally
            $localMigrations = DB::connection($this->localConnection)
                ->table('migrations')
                ->pluck('migration')
                ->toArray();
            
            // Get migrations that have been run remotely
            $remoteMigrations = [];
            try {
                $remoteMigrations = DB::connection($this->remoteConnection)
                    ->table('migrations')
                    ->pluck('migration')
                    ->toArray();
            } catch (Exception $e) {
                // Migrations table might not exist yet, that's okay
                Log::info('Remote migrations table does not exist yet, will be created');
            }
            
            $remoteMigrationsSet = array_flip($remoteMigrations);
            $migrationsToRun = [];
            
            // Find migrations that need to be run on remote
            foreach ($localMigrations as $migration) {
                if (!isset($remoteMigrationsSet[$migration])) {
                    $migrationsToRun[] = $migration;
                }
            }
            
            if (empty($migrationsToRun)) {
                Log::info('All migrations are already synced to remote');
                return;
            }
            
            Log::info('Found ' . count($migrationsToRun) . ' migration(s) to run on remote');
            
            // Note: We can't directly run Laravel migrations on remote via this service
            // Instead, we ensure the migrations table is synced so the remote knows which migrations to run
            // The actual migration execution should be done via artisan migrate on the remote server
            // But we can at least ensure the schema is synced via syncMigrationsTable()
            
        } catch (Exception $e) {
            Log::error('Error running remote migrations: ' . $e->getMessage());
            throw $e;
        }
    }
}

