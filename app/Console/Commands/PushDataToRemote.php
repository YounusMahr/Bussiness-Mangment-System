<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DatabaseSyncService;

class PushDataToRemote extends Command
{
    protected $signature = 'db:push-remote';
    protected $description = 'Push local database data to remote database';

    public function handle()
    {
        $this->info('Pushing data to remote database...');
        $this->newLine();

        $syncService = new DatabaseSyncService();

        // Check connectivity
        if (!$syncService->isOnline()) {
            $this->error('✗ No internet connection available.');
            return 1;
        }

        if (!$syncService->isRemoteConnected()) {
            $this->error('✗ Cannot connect to remote database.');
            $this->line('Error: ' . $syncService->getRemoteConnectionError());
            return 1;
        }

        $this->info('✓ Internet connection: OK');
        $this->info('✓ Remote database connection: OK');
        $this->newLine();

        // Push data
        $this->info('Starting data push...');
        $results = $syncService->syncToRemote();

        if ($results['success']) {
            $this->newLine();
            $this->info('✓ Push completed successfully!');
            $this->newLine();
            
            $this->info('Summary:');
            $this->line('  Total new records pushed: ' . $results['total']);
            if (isset($results['totalSkipped']) && $results['totalSkipped'] > 0) {
                $this->line('  Existing records skipped: ' . $results['totalSkipped'] . ' (no duplicates)');
            }
            $this->line('  Tables processed: ' . count($results['synced']));
            
            if (!empty($results['errors'])) {
                $this->newLine();
                $this->warn('Tables with errors: ' . count($results['errors']));
                foreach ($results['errors'] as $table => $error) {
                    $this->line("  - {$table}: {$error}");
                }
            }

            if (!empty($results['synced'])) {
                $this->newLine();
                $this->info('Tables pushed:');
                foreach ($results['synced'] as $table => $count) {
                    $skipped = $results['skipped'][$table] ?? 0;
                    $this->line("  - {$table}: {$count} new records" . ($skipped > 0 ? " ({$skipped} skipped)" : ""));
                }
            }

            return 0;
        } else {
            $this->newLine();
            $this->error('✗ Push failed!');
            foreach ($results['errors'] as $error) {
                $this->error('  ' . $error);
            }
            return 1;
        }
    }
}
