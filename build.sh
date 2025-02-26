#!/bin/bash

set -e

# SSL requirements
source .env
readonly curPath=$(pwd)
sudo apt install certbot -y
certbot certonly --standalone -n --agree-tos -d "${DOMAIN}" --email "${EMAIL_SUPPORT}"
readonly startNginx="docker compose -f $curPath/docker-compose.prod.yml start nginx"
readonly stopNginx="docker compose -f $curPath/docker-compose.prod.yml stop nginx"
readonly certbotRenew="certbot renew --quiet"
crontab -l | { cat; echo "0 0 1 */3 * $stopNginx && $certbotRenew && $stopNginx"; } | crontab -
