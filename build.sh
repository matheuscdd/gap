#!/bin/bash

# Docker requirements
for pkg in docker.io docker-doc docker-compose docker-compose-v2 podman-docker containerd runc; do sudo apt-get remove $pkg; done

sudo apt-get update
sudo apt-get install ca-certificates curl
sudo install -m 0755 -d /etc/apt/keyrings
sudo curl -fsSL https://download.docker.com/linux/ubuntu/gpg -o /etc/apt/keyrings/docker.asc
sudo chmod a+r /etc/apt/keyrings/docker.asc

echo \
  "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.asc] https://download.docker.com/linux/ubuntu \
  $(. /etc/os-release && echo "$VERSION_CODENAME") stable" | \
  sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
sudo apt-get update
sudo apt-get install docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin -y


# PHP requirements
sudo echo 'export WWWUSER=${WWWUSER:-$UID}' | cat >> ~/.bashrc
sudo echo 'export WWWGROUP=${WWWGROUP:-$(id -g)}' | cat >> ~/.bashrc
source ~/.bashrc
sudo apt update && sudo apt upgrade -y
sudo apt install unzip php8.3 php8.3-curl php8.3-xml php8.3-zip -y
sudo systemctl disable apache2
curDir=$(pwd)
cd ~
curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php
HASH=$(curl -sS https://composer.github.io/installer.sig)
echo $HASH
php -r "if (hash_file('SHA384', '/tmp/composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
sudo php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer
cd $curDir
composer update
sudo apt update && sudo apt upgrade -y
cp docker-compose.prod.yml docker-compose.yml
./vendor/bin/sail up --build
rm docker-compose.yml