#!/usr/bin/env bash

set -e

readonly path='/app'
readonly logsDir="$path/logs"
cd "$path" || exit
[ ! -d "$logsDir" ] && mkdir "$logsDir"

source .env.cron
PGPASSWORD="$PGPASSWORD" POSTGRES_USER="$POSTGRES_USER" FORWARD_DB_PORT="$FORWARD_DB_PORT" POSTGRES_DB="$POSTGRES_DB" php artisan schedule:run >> "$logsDir/cron.log"
