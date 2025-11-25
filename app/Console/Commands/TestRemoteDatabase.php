<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class TestRemoteDatabase extends Command
{
    protected $signature = 'db:test-remote';
    protected $description = 'Test remote database connection';

    public function handle()
    {
        $this->info('Testing remote database connection...');
        $this->newLine();

        // Display configuration
        $this->info('Configuration:');
        $this->line('  Host: ' . config('database.connections.mysql_remote.host'));
        $this->line('  Port: ' . config('database.connections.mysql_remote.port'));
        $this->line('  Database: ' . config('database.connections.mysql_remote.database'));
        $this->line('  Username: ' . config('database.connections.mysql_remote.username'));
        $this->line('  Password: ' . (config('database.connections.mysql_remote.password') ? '***' : '(empty)'));
        $this->newLine();

        try {
            $this->info('Attempting to connect...');
            $pdo = DB::connection('mysql_remote')->getPdo();
            $this->info('✓ Connection successful!');
            $this->newLine();

            // Test a simple query
            $this->info('Testing query...');
            $result = DB::connection('mysql_remote')->select('SELECT VERSION() as version, DATABASE() as database_name');
            $this->info('✓ Query successful!');
            $this->newLine();

            $this->info('Database Information:');
            $this->line('  MySQL Version: ' . $result[0]->version);
            $this->line('  Database Name: ' . $result[0]->database_name);
            $this->newLine();

            // List tables
            $tables = DB::connection('mysql_remote')->select('SHOW TABLES');
            $tableKey = 'Tables_in_' . config('database.connections.mysql_remote.database');
            $this->info('Tables found: ' . count($tables));
            if (count($tables) > 0 && count($tables) <= 10) {
                foreach ($tables as $table) {
                    $this->line('  - ' . $table->$tableKey);
                }
            } elseif (count($tables) > 10) {
                $this->line('  (showing first 10)');
                for ($i = 0; $i < 10; $i++) {
                    $this->line('  - ' . $tables[$i]->$tableKey);
                }
            }

            $this->newLine();
            $this->info('✓ Remote database connection is working correctly!');
            return 0;

        } catch (\Exception $e) {
            $this->error('✗ Connection failed!');
            $this->newLine();
            $this->error('Error: ' . $e->getMessage());
            $this->newLine();

            // Provide helpful suggestions
            $this->warn('Troubleshooting suggestions:');
            if (str_contains($e->getMessage(), 'Access denied')) {
                $this->line('  1. Check your username and password in .env');
                $this->line('  2. Verify the user has access to the database');
            } elseif (str_contains($e->getMessage(), 'Unknown database')) {
                $this->line('  1. Check DB_REMOTE_DATABASE in .env');
                $this->line('  2. Verify the database exists on the remote server');
            } elseif (str_contains($e->getMessage(), 'Connection refused') || str_contains($e->getMessage(), 'Connection timed out')) {
                $this->line('  1. Check DB_REMOTE_HOST in .env (should be the remote server IP/hostname, not 127.0.0.1)');
                $this->line('  2. Verify the remote server allows remote connections');
                $this->line('  3. Check firewall settings');
                $this->line('  4. Verify the port is correct (default: 3306)');
            } elseif (str_contains($e->getMessage(), 'getaddrinfo failed')) {
                $this->line('  1. Check DB_REMOTE_HOST in .env');
                $this->line('  2. Verify the hostname/IP is correct');
            } else {
                $this->line('  1. Check all database credentials in .env');
                $this->line('  2. Verify the remote database server is running');
                $this->line('  3. Check network connectivity to the remote server');
            }

            return 1;
        }
    }
}
