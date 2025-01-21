#!/bin/bash

echo "🚀 Starting deployment..."

# Update code from git
echo "📥 Pulling latest changes..."
git pull origin main

# Install dependencies
echo "📦 Installing dependencies..."
composer install --optimize-autoloader --no-dev

# Backup current database
echo "💾 Backing up database..."
cp database/database.sqlite database/database.sqlite.backup

# Database migration and data restoration
echo "🔄 Migrating database..."
php artisan migrate:fresh
echo "📥 Restoring data..."
# Filter out CREATE TABLE, CREATE INDEX and similar statements before importing
grep -v -E "^CREATE |^INSERT INTO migrations|^INSERT INTO custom_types|^CREATE INDEX|^CREATE UNIQUE" save.sql | sqlite3 database/database.sqlite

# Clear caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Production optimizations
echo "⚡ Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Deployment completed successfully!"
