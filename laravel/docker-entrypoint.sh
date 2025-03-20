#!/usr/bin/env bash

set -e

envsRawest="$(printenv | grep -Ev "^SUPERVISOR_PHP_COMMAND|^_|^LESSCLOSE|^LS_COLORS|^LESSOPEN")"

readarray -t envsRaw < <(printf '%s\n' "$envsRawest")
res=''
readonly filename=".env.cron"
[ -e "$filename" ] && rm "$filename"

for var in "${envsRaw[@]}"; do
  delimiter='='
  key=${var%%"$delimiter"*}
  val=${var#*"$delimiter"}
  echo "$key='$val'" >> "$filename"
done

rm bootstrap/cache/*.php &
service cron start
php artisan serve --host=0.0.0.0 --port=80 
