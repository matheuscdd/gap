#!/usr/bin/env bash

cd /var/www/html 
source .env.cron
php artisan schedule:run >> logs/outputs