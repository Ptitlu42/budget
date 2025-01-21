#!/bin/bash

GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
PURPLE='\033[0;35m'
CYAN='\033[0;36m'
NC='\033[0m'
BOLD='\033[1m'

TOTAL_STEPS=8
current_step=0

show_progress() {
    current_step=$((current_step + 1))
    percentage=$((current_step * 100 / TOTAL_STEPS))
    position=$((current_step * 50 / TOTAL_STEPS))

    echo -en "\e[K${BOLD}${BLUE}$percentage%${NC} - ${CYAN}$1${NC}\n"

    echo -en "\e[K${YELLOW}[${NC}"
    for ((i=1; i<=50; i++)); do
        if [ $i -lt $position ]; then
            echo -en "${GREEN}=${NC}"
        elif [ $i -eq $position ]; then
            echo -en "${PURPLE}>${NC}"
        else
            echo -n " "
        fi
    done
    echo -en "${YELLOW}]${NC}"

    echo -en "\n\n"
}

echo -e "${BOLD}${GREEN}🚀 Starting deployment...${NC}"
echo
echo
echo

show_progress "Pulling latest changes..."
git pull origin main

show_progress "Installing dependencies..."
composer install --optimize-autoloader --no-dev

show_progress "Backing up database..."
BACKUP_FILE="database/database.sqlite.backup.$(date +%Y%m%d_%H%M%S)"
cp database/database.sqlite "$BACKUP_FILE"

show_progress "Migrating database..."
php artisan migrate:fresh --force

show_progress "Restoring data..."
grep -v -E "^CREATE |^INSERT INTO migrations|^INSERT INTO custom_types|^CREATE INDEX|^CREATE UNIQUE" save.sql | sqlite3 database/database.sqlite

show_progress "Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

show_progress "Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

show_progress "Deployment completed!"
echo -e "${BOLD}${GREEN}✅ Deployment completed successfully!${NC}"
echo -e "${BOLD}${BLUE}📁 Database backup saved as: ${YELLOW}$BACKUP_FILE${NC}"
