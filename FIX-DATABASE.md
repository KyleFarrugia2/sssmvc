# Fix Database Connection Error

You're getting: `Access denied for user 'root'@'localhost'`

## Quick Fix Option 1: Use SQLite (Fastest - No MySQL Setup Needed)

1. Update your `.env` file:
```env
DB_CONNECTION=sqlite
DB_DATABASE=/home/sss/sssmvc/database/database.sqlite
# Comment out or remove MySQL settings:
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_USERNAME=root
# DB_PASSWORD=
```

2. Create the SQLite database file:
```bash
touch database/database.sqlite
chmod 664 database/database.sqlite
```

3. Run migrations:
```bash
php artisan migrate
php artisan tinker < tinker-seed.php
```

4. Clear config cache:
```bash
php artisan config:clear
```

## Option 2: Fix MySQL Authentication

### Step 1: Access MySQL
```bash
sudo mysql
```

### Step 2: In MySQL, run these commands:

**Option A: Create a new user (Recommended)**
```sql
CREATE USER IF NOT EXISTS 'sssmvc'@'localhost' IDENTIFIED BY 'sssmvc123';
CREATE DATABASE IF NOT EXISTS sssmvc;
GRANT ALL PRIVILEGES ON sssmvc.* TO 'sssmvc'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

**Option B: Enable password for root**
```sql
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'your_password';
FLUSH PRIVILEGES;
EXIT;
```

### Step 3: Update `.env` file

**For Option A (new user):**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sssmvc
DB_USERNAME=sssmvc
DB_PASSWORD=sssmvc123
```

**For Option B (root user):**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sssmvc
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Step 4: Run migrations
```bash
php artisan config:clear
php artisan migrate
php artisan tinker < tinker-seed.php
```

## Test Connection

After fixing, test with:
```bash
php artisan tinker
```
Then in tinker:
```php
DB::connection()->getPdo();
```
If it works, you'll see the connection info. If not, you'll see the error.

