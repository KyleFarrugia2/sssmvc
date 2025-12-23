#!/bin/bash

# Script to update .env file for MySQL
# Run this AFTER you've set up MySQL user and database

if [ ! -f .env ]; then
    echo "Error: .env file not found!"
    exit 1
fi

echo "Updating .env file for MySQL..."

# Backup .env
cp .env .env.backup

# Update database settings
sed -i 's/^DB_CONNECTION=.*/DB_CONNECTION=mysql/' .env
sed -i 's/^DB_HOST=.*/DB_HOST=127.0.0.1/' .env
sed -i 's/^DB_PORT=.*/DB_PORT=3306/' .env
sed -i 's/^DB_DATABASE=.*/DB_DATABASE=sssmvc/' .env
sed -i 's/^DB_USERNAME=.*/DB_USERNAME=sssmvc/' .env
sed -i 's/^DB_PASSWORD=.*/DB_PASSWORD=sssmvc123/' .env

echo "✅ .env file updated!"
echo "Backup saved as .env.backup"
echo ""
echo "Now run:"
echo "  php artisan config:clear"
echo "  php artisan migrate"
echo "  php artisan tinker < tinker-seed.php"

