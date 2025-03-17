# SSL
## Instalar certificado SSL no HOST e configura certbot
```
./build.sh
```

## Atualizações de versão do certbot
* No arquivo `docker-compose.prod.yml`, no serviço `nginx`, na parte de selecionar o Dockerfile, escolha `Dockerfile.dev`, isso deve ser feito para resetar as configurações certbot
* Instale o certbot e o certificado na mão 
```
docker exec -it gap-prod-nginx-1 apt update 
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

* No arquivo `docker-compose.prod.yml`, volte a apontar para o `Dockerfile.prod` no serviço do `nginx`, isso deve ser feito para inserir as novas configurações do certbot

# Configurações para o acesso ao S3 de desenvolvimento
Você precisa redirecionar o nome do serviço docker pro localhost, adicionando as informações abaixo no seu `/etc/hosts`. Caso esteja rodando no Windows, independente de ser via WSL, você precisa inserir a mesma linha em `C:\Windows\System32\drivers\etc\hosts.ics`, algumas limitações do sistema exigem que copie o arquivo pra algum lugar e depois substitua pelo original
```
127.0.0.1	s3
```

# Comandos úteis
### Acessa o artisan do container
```
gapsh() {
    id=$(docker ps --no-trunc | grep laravel | cut -d ' ' -f 1)
    docker exec -it $id php artisan $*
    sudo chown -R $USER:$USER ~/gap
}
```

### Builda o container de desenvolvimento
```
docker compose -f docker-compose.dev.yml up --build
```

### Builda o container de produção
```
docker compose -f docker-compose.prod.yml up --build
```

### Cria o usuário admin inicial
```
gapsh admin:create
```
### Gera a chave JWT
```
gapsh jwt:secret
```

### Outros comandos
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
