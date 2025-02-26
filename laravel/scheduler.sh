#!/usr/bin/env bash

set -e

readonly path='/app'
readonly logsDir="$path/logs"
cd "$path" || exit
[ ! -d "$logsDir" ] && mkdir "$logsDir"

source .env.cron
envs=$(cat .env.cron | tr '\n' ' ')
eval "$envs php artisan schedule:run >> $logsDir/cron.log"