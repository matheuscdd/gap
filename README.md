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
# JWT_SECRET precisa ser inserido tantos .env do back como do front

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