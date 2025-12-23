# NutriTrack - Setup & Run Instructions

## Prerequisites
- PHP 8.1 or higher
- Composer
- MySQL/MariaDB
- Node.js and npm (for frontend assets)

## Step-by-Step Setup

### 1. Install PHP Dependencies
```bash
composer install
```

### 2. Configure Environment
```bash
# Copy the example environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 3. Configure Database (MySQL)
Edit `.env` file and set your MySQL credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sssmvc
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 4. Create Database
```bash
# Login to MySQL
mysql -u root -p

# Create database
CREATE DATABASE sssmvc;
EXIT;
```

### 5. Run Migrations
```bash
php artisan migrate
```

### 6. Seed Database (Optional - with dummy data)
```bash
# Option A: Using seeder
php artisan db:seed

# Option B: Using Tinker (more data)
php artisan tinker < tinker-seed.php
```

### 7. Install Frontend Dependencies (if needed)
```bash
npm install
```

### 8. Build Frontend Assets (if using Vite)
```bash
npm run build
# OR for development with hot reload:
npm run dev
```

### 9. Start Development Server
```bash
php artisan serve
```

The application will be available at: **http://localhost:8000**

## Quick Start (All-in-One)
```bash
composer install
cp .env.example .env
php artisan key:generate
# Edit .env with your MySQL credentials
php artisan migrate
php artisan tinker < tinker-seed.php
php artisan serve
```

## Access the Application
- **Home**: http://localhost:8000
- **Meal Plans**: http://localhost:8000/meal-plans
- **Foods**: http://localhost:8000/foods

## Troubleshooting

### Database Connection Error
- Check MySQL is running: `sudo systemctl status mysql`
- Verify credentials in `.env`
- Ensure database exists: `mysql -u root -p -e "SHOW DATABASES;"`

### Permission Issues
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

