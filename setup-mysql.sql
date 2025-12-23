-- MySQL Setup Script for NutriTrack
-- Run this after: sudo mysql

-- Option 1: Create dedicated user (RECOMMENDED)
CREATE USER IF NOT EXISTS 'sssmvc'@'localhost' IDENTIFIED BY 'sssmvc123';
CREATE DATABASE IF NOT EXISTS sssmvc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
GRANT ALL PRIVILEGES ON sssmvc.* TO 'sssmvc'@'localhost';
FLUSH PRIVILEGES;

-- Option 2: If you prefer to use root (uncomment below and comment Option 1)
-- ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'your_password_here';
-- CREATE DATABASE IF NOT EXISTS sssmvc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- FLUSH PRIVILEGES;

-- Verify
SHOW DATABASES;
SELECT user, host FROM mysql.user WHERE user IN ('root', 'sssmvc');

