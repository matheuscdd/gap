#!/usr/bin/env sh
set -eu

envsubst '${DOMAIN}' < "/etc/nginx/conf.d/default.conf.template" > /etc/nginx/nginx.conf

exec "$@"