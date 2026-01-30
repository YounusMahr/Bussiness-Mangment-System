<?php

namespace App\Livewire\Components;

use Livewire\Component;
use App\Services\DatabaseSyncService;
use Illuminate\Support\Facades\Log;

class DatabaseSync extends Component
{
    public $isOnline = false;
    public $isRemoteConnected = false;
    public $syncing = false;
    public $syncStatus = null;
    public $syncMessage = '';
    public $connectionError = '';

    public function mount()
    {
        $this->checkConnectivity();
    }

    public function checkConnectivity()
    {
        try {
            $syncService = new DatabaseSyncService();
            $this->isOnline = $syncService->isOnline();
            $this->isRemoteConnected = $syncService->isRemoteConnected();
            
            // Get detailed error message if connection failed
            if (!$this->isRemoteConnected) {
                $this->connectionError = $syncService->getRemoteConnectionError();
            } else {
                $this->connectionError = '';
            }
        } catch (\Exception $e) {
            Log::error('Connectivity check error: ' . $e->getMessage());
            $this->isOnline = false;
            $this->isRemoteConnected = false;
            $this->connectionError = 'Error checking connectivity: ' . $e->getMessage();
        }
    }

    public function refreshConnectivity()
    {
        $this->checkConnectivity();
        $this->dispatch('connectivity-updated');
    }

    public function sync()
    {
        // Refresh connectivity first
        $this->checkConnectivity();
        
        $this->syncing = true;
        $this->syncStatus = null;
        $this->syncMessage = '';

        try {
            $syncService = new DatabaseSyncService();
            
            // Check connectivity first
            if (!$this->isOnline) {
                $this->syncStatus = 'error';
                $this->syncMessage = 'No internet connection available. Please check your internet connection.';
                $this->syncing = false;
                return;
            }

            // Re-check remote connection
            if (!$this->isRemoteConnected) {
                $this->checkConnectivity(); // Refresh one more time
                if (!$this->isRemoteConnected) {
                    $this->syncStatus = 'error';
                    $errorMsg = $this->connectionError ?: 'Cannot connect to remote database.';
                    $this->syncMessage = $errorMsg . ' Please check your database credentials in .env file (DB_REMOTE_HOST, DB_REMOTE_DATABASE, DB_REMOTE_USERNAME, DB_REMOTE_PASSWORD).';
                    $this->syncing = false;
                    return;
                }
            }

            // Push data to remote (one-way: local to remote only)
            $results = $syncService->syncToRemote();

            if ($results['success']) {
                $this->syncStatus = 'success';
                $totalPushed = $results['total'];
                $totalSkipped = $results['totalSkipped'] ?? 0;
                $syncedTables = count($results['synced']);
                $migrationsSynced = $results['migrations_synced'] ?? false;
                $migrationsRun = $results['migrations_run'] ?? false;
                
                // Count transaction/history tables synced
                $transactionTables = [];
                $transactionCount = 0;
                foreach ($results['synced'] ?? [] as $table => $count) {
                    if (str_contains($table, '_transactions') || str_contains($table, '_history')) {
                        $transactionTables[] = $table . ' (' . $count . ')';
                        $transactionCount += $count;
                    }
                }
                
                $this->syncMessage = "Successfully pushed {$totalPushed} new records from {$syncedTables} tables to remote database.";
                
                if ($migrationsSynced) {
                    $this->syncMessage .= " Migrations table synced successfully.";
                }
                
                if ($migrationsRun) {
                    $this->syncMessage .= " Remote migrations verified.";
                }
                
                if ($transactionCount > 0) {
                    $this->syncMessage .= " History/Transaction data: {$transactionCount} records pushed (" . implode(', ', $transactionTables) . ").";
                }
                
                if ($totalSkipped > 0) {
                    $this->syncMessage .= " {$totalSkipped} existing records skipped (no duplicates).";
                }
                
                if (!empty($results['errors'])) {
                    $errorCount = count($results['errors']);
                    $errorTables = array_keys($results['errors']);
                    $this->syncMessage .= " {$errorCount} table(s) had errors: " . implode(', ', $errorTables) . ".";
                }
                
                $this->dispatch('sync-complete');
            } else {
                $this->syncStatus = 'error';
                $this->syncMessage = 'Push failed: ' . implode(', ', $results['errors']);
                $this->dispatch('sync-complete');
            }

            // Refresh connectivity status
            $this->checkConnectivity();

        } catch (\Exception $e) {
            $this->syncStatus = 'error';
            $this->syncMessage = 'Push failed: ' . $e->getMessage();
            Log::error('Database push error: ' . $e->getMessage());
            $this->dispatch('sync-complete');
        } finally {
            $this->syncing = false;
        }
    }

    public function render()
    {
        return view('livewire.components.database-sync');
    }
}
