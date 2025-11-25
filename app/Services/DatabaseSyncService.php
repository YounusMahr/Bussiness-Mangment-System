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
     * Check if remote database is accessible
     */
    public function isRemoteConnected(): bool
    {
        try {
            // Set a longer timeout for remote connections
            config(['database.connections.' . $this->remoteConnection . '.options' => [
                PDO::ATTR_TIMEOUT => 5,
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
        // Try multiple methods for better reliability
        $hosts = ['www.google.com', '8.8.8.8', '1.1.1.1'];
        $ports = [80, 53, 443];
        
        foreach ($hosts as $host) {
            foreach ($ports as $port) {
                $connected = @fsockopen($host, $port, $errno, $errstr, 3);
                if ($connected) {
                    fclose($connected);
                    return true;
                }
            }
        }
        
        // Fallback: Try using curl if available
        if (function_exists('curl_init')) {
            $ch = curl_init('https://www.google.com');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            $result = @curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($result !== false && $httpCode > 0) {
                return true;
            }
        }
        
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
            'totalSkipped' => 0
        ];

        if (!$this->isOnline() || !$this->isRemoteConnected()) {
            $results['success'] = false;
            $results['errors'][] = 'No internet connection or remote database unavailable';
            return $results;
        }

        $tables = $this->getTablesToSync();

        foreach ($tables as $table) {
            try {
                $syncResult = $this->pushTable($table, $this->localConnection, $this->remoteConnection);
                $results['synced'][$table] = $syncResult['inserted'];
                $results['skipped'][$table] = $syncResult['skipped'];
                $results['total'] += $syncResult['inserted'];
                $results['totalSkipped'] += $syncResult['skipped'];
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
                    
                    // Check if record exists (fast lookup using array)
                    if (isset($existingKeysSet[$primaryKeyValue])) {
                        // Record already exists, skip it (no duplicate)
                        $skipped++;
                        continue;
                    }
                    
                    // Record doesn't exist, insert it
                    // Remove timestamps if they exist (let database handle them)
                    unset($recordArray['created_at'], $recordArray['updated_at']);
                    
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
     * Get list of tables to sync (excluding system tables)
     */
    protected function getTablesToSync(): array
    {
        $excludedTables = ['migrations', 'password_reset_tokens', 'sessions', 'cache', 'cache_locks', 'failed_jobs', 'job_batches', 'jobs'];
        
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
}

