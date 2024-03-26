# Levach

![](https://github.com/marxunion/levach/blob/main/frontend/src/assets/img/logo/logo.png?raw=true)

A hybrid media project focused on designing a better future through scientific discussion 

## Requirements

1. [**Docker**](https://www.docker.com/)
2. [**Docker PHP Image**](https://hub.docker.com/_/php)
3. [**Docker Postgres Image**](https://hub.docker.com/_/postgres)
4. [**Docker PGAdmin Image**](https://hub.docker.com/r/elestio/pgadmin)
5. [**Docker Composer Image**](https://hub.docker.com/_/composer)
6. [**Docker Nginx Image**](https://hub.docker.com/_/nginx)
7. [**Docker NodeJS Image**](https://hub.docker.com/_/node)

## How to install and configure

### Configure php
Rename php-example to php and ether your auth information

### Configure .env
Rename .env-example to .env and ether your auth information

### Start in DevMode
```bash
docker-compose -f docker-compose.yml -f docker/docker-compose.dev.yml up -d
```

### Start in ProductionMode
```bash
docker-compose -f docker-compose.yml -f docker/docker-compose.prod.yml up -d
```
