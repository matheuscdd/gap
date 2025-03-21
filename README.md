# SSL
## Instalar certificado SSL no HOST e configura certbot
```
./ssl.sh
```

## Atualizações de versão do certbot
* Ao invés de subir com o arquivo o `docker-compose.prod.https.yml`, suba `docker-compose.prod.http.yml`
* Instale o certbot e o certificado na mão 
```
docker exec -it gap-prod-http-nginx-1 apt update 
docker exec -it gap-prod-http-nginx-1 apt install certbot python3-certbot-nginx -y
docker exec -it gap-prod-http-nginx-1 certbot certonly --nginx -n --agree-tos -d "${DOMAIN}" --email "${EMAIL_SUPPORT}"
```
* Copie as configurações realizadas automaticamente pelo certbot
```
docker exec -it gap-prod-http-nginx-1 cat /etc/nginx/nginx.conf
```

* Faça os ajustes para funcionar com as variáveis de ambiente modificadas no docker compose
```
- DOMAIN
- fullchain.pem
- privkey.pem
- options-ssl-nginx.conf
- ssl-dhparams.pem
```

* Agora é só subir o arquivo normalmente `docker-compose.prod.https.yml`

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
docker compose -f docker-compose.dev.http.yml up --build
```

### Builda o container de produção
```
docker compose -f docker-compose.prod.https.yml up --build
```

### Cria o usuário admin inicial
```
gapsh admin:create
```
### Gera a chave JWT
```
gapsh jwt:secret
```

### Gera as chaves rsa
```
gapsh rsa:gen
```


### Outros comandos
```
alias gdevup="docker compose -f docker-compose.dev.http.yml up" 
alias gprodup="docker compose -f docker-compose.prod.https.yml up" 
alias gdevstop="docker compose -f docker-compose.dev.http.yml stop" 
alias gprodstop="docker compose -f docker-compose.prod.https.yml stop"
alias gdevdown="docker compose -f docker-compose.dev.http.yml down"
alias gproddown="docker compose -f docker-compose.prod.https.yml down"
alias gdevres="docker compose -f docker-compose.dev.http.yml stop && docker compose -f docker-compose.dev.http.yml up"
alias gprodres="docker compose -f docker-compose.prod.https.yml stop && docker compose -f docker-compose.prod.https.yml up"
```
