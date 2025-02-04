#!/usr/bin/env bash

set -e

cd /var/www/html || exit
source .env.cron
PGPASSWORD="$PGPASSWORD" POSTGRES_USER="$POSTGRES_USER" FORWARD_DB_PORT="$FORWARD_DB_PORT" POSTGRES_DB="$POSTGRES_DB" php artisan schedule:run >> logs/cron.log
