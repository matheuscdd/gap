```
gapsh() {
    id=$(docker ps --no-trunc | grep laravel | cut -d ' ' -f 1)
    docker exec -it $id php artisan $*
    sudo chown -R $USER:$USER ~/gap
}
```
```
docker compose -f docker-compose.dev.yml up 
```
```
docker compose -f docker-compose.prod.yml up 
```
```
gapsh admin:create
```
# JWT_SECRET precisa ser inserido .env do back 

```
alias gdevup="docker compose -f docker-compose.dev.yml up" 
alias gprodup="docker compose -f docker-compose.prod.yml up" 
alias gdevstop="docker compose -f docker-compose.dev.yml stop" 
alias gprodstop="docker compose -f docker-compose.prod.yml stop"
alias gdevdown="docker compose -f docker-compose.dev.yml down"
alias gproddown="docker compose -f docker-compose.prod.yml down"
alias gdevres="docker compose -f docker-compose.dev.yml stop && docker compose -f docker-compose.dev.yml up"
alias gprodres="docker compose -f docker-compose.prod.yml stop && docker compose -f docker-compose.prod.yml up"
```

# Atualizações no certbot
* No arquivo `nginx/Dockerfile.prod` , troque `nginx.https` por `nginx.http`, isso deve ser feito para resetar as configurações certbot
* Instale o certbot e o certificado na mão 
```
docker exec -it gap-prod-nginx-1 apt install certbot python3-certbot-nginx -y
docker exec -it gap-prod-nginx-1 certbot certonly --nginx -n --agree-tos -d "${DOMAIN}" --email "${EMAIL_SUPPORT}"
```
* Copie as configurações realizadas automaticamente pelo certbot
```
docker exec -it gap-prod-nginx-1 cat /etc/nginx/nginx.conf
```

* Faça os ajustes para funcionar com as variáveis de ambiente modificadas no docker compose
```
- DOMAIN
- fullchain.pem
- privkey.pem
- options-ssl-nginx.conf
- ssl-dhparams.pem
```

* No arquivo `nginx/Dockerfile.prod` , agora troque `nginx.http` por `nginx.https`, isso deve ser feito para inserir as novas configurações do certbot

# Crontab

```
apt update && apt-get install cron -y && echo '* * * * * cd /var/www/html && source .env && php artisan schedule:run >> /dev/null 2>&1' | crontab - && service cron start
```