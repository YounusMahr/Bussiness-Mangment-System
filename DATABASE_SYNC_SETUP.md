# Database Sync Setup Guide

## Overview
This application supports offline/online database synchronization. When offline, data is stored in the local database. When online, you can sync data with the remote database using the sync button in the navbar.

## Configuration

### 1. Remote Database Credentials
Add the following to your `.env` file:

```env
# Remote Database Configuration (for online sync)
DB_REMOTE_HOST=127.0.0.1
DB_REMOTE_PORT=3306
DB_REMOTE_DATABASE=u201313409_bussManage_db
DB_REMOTE_USERNAME=u201313409_bussManage_db
DB_REMOTE_PASSWORD=bussManage114
```

### 2. Database Connections
The application uses two database connections:
- **Local (`mysql`)**: Your local database for offline use
- **Remote (`mysql_remote`)**: Online database for synchronization

Both connections are configured in `config/database.php`.

## How It Works

### Offline Mode
- When there's no internet connection, all operations use the local database
- Data is stored locally and can be accessed normally
- The sync button will be disabled (gray) when offline

### Online Mode
- When internet is available, the sync button becomes active (green)
- Click the sync button to synchronize data between local and remote databases
- The sync process performs a **bidirectional sync**:
  - Uploads new/updated local data to remote
  - Downloads new/updated remote data to local
  - Merges data intelligently (updates existing, inserts new)

### Sync Process
1. **Connectivity Check**: Verifies internet connection and remote database accessibility
2. **Table Discovery**: Automatically discovers all tables (excluding system tables)
3. **Bidirectional Sync**: 
   - Syncs local → remote (uploads your local changes)
   - Syncs remote → local (downloads remote changes)
4. **Conflict Resolution**: Uses ID-based matching - if a record exists in both, the source data overwrites the destination

## Sync Button Features

### Status Indicators
- **Green with pulse**: Online and connected to remote database (ready to sync)
- **Gray**: Offline or cannot connect to remote database
- **Spinning icon**: Sync in progress

### Sync Results
After syncing, you'll see a notification showing:
- Number of records synced
- Number of tables processed
- Any errors that occurred

## Tables Synced
All application tables are automatically synced, excluding:
- `migrations`
- `password_reset_tokens`
- `sessions`
- `cache` tables
- `failed_jobs`
- `job_batches`
- `jobs`

## Troubleshooting

### Sync Button is Gray/Disabled
1. Check your internet connection
2. Verify remote database credentials in `.env`
3. Ensure remote database server is accessible
4. Check firewall settings if using a remote host

### Sync Fails
1. Check application logs: `storage/logs/laravel.log`
2. Verify database credentials are correct
3. Ensure remote database has the same table structure
4. Check for network timeouts

### Data Conflicts
- The sync uses ID-based matching
- If a record with the same ID exists in both databases, the source data will overwrite the destination
- For critical data, review sync results carefully

## Security Notes
- Remote database credentials are stored in `.env` file (never commit this to version control)
- Ensure your remote database has proper security measures
- Use SSL connections if available (configure in `config/database.php`)

## Best Practices
1. **Regular Syncs**: Sync frequently when online to keep data up-to-date
2. **Backup**: Always backup your database before major syncs
3. **Test Connection**: Use the sync button to test connectivity before working offline
4. **Monitor Logs**: Check logs if sync fails to identify issues

## Technical Details

### Service Class
The sync functionality is handled by `App\Services\DatabaseSyncService`:
- `isOnline()`: Checks internet connectivity
- `isRemoteConnected()`: Tests remote database connection
- `syncBidirectional()`: Performs two-way sync
- `syncToRemote()`: One-way sync (local → remote)
- `syncFromRemote()`: One-way sync (remote → local)

### Livewire Component
The sync UI is managed by `App\Livewire\Components\DatabaseSync`:
- Handles user interaction
- Displays sync status
- Manages connectivity checks
- Shows sync results

## Support
For issues or questions, check:
1. Application logs: `storage/logs/laravel.log`
2. Browser console for JavaScript errors
3. Network tab for connection issues

