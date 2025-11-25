# Cache Cleanup Guide

## Changes Made

### 1. Cache Driver Changed
- **Before**: `CACHE_STORE=database` (stored cache in database, causing database bloat)
- **After**: `CACHE_STORE=file` (stores cache in files, reducing database size)

### 2. Cache Location
Cache files are now stored in: `storage/framework/cache/data/`

## Optional: Clean Up Old Cache Table

If you want to free up space in your database by removing old cache entries, you can:

### Option 1: Truncate Cache Table (Recommended)
Run this SQL command in your database:
```sql
TRUNCATE TABLE cache;
TRUNCATE TABLE cache_locks;
```

### Option 2: Drop Cache Tables (If not needed)
If you're sure you won't need database cache anymore:
```sql
DROP TABLE IF EXISTS cache;
DROP TABLE IF EXISTS cache_locks;
```

### Option 3: Using Laravel Tinker
```bash
php artisan tinker
```
Then run:
```php
DB::table('cache')->truncate();
DB::table('cache_locks')->truncate();
```

## Benefits of File Cache

1. **Reduced Database Size**: Cache no longer stored in database
2. **Better Performance**: File cache is faster for small applications
3. **Easier Cleanup**: Can delete cache files directly from filesystem
4. **No Database Overhead**: Reduces database queries and storage

## Cache Management

Cache files are automatically managed by Laravel. To manually clear cache:

```bash
php artisan cache:clear
```

Cache files will be automatically cleaned up by Laravel's garbage collection.

## Note

- Old cache entries in the database will remain but won't be used
- New cache entries will be stored in files
- You can safely ignore the old database cache entries or clean them up using the methods above

