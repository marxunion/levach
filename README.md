# Levach

![](https://github.com/marxunion/levach/blob/main/frontend/src/assets/img/logo/logo.png?raw=true)

A hybrid media project focused on designing a better future through scientific discussion 

## Requirements

1. [**Docker**](https://www.docker.com/)

## How to install and configure

### Configure php
Rename php-example to php and ether your auth information

### Configure .env
Rename .env-example to .env and ether your auth information

### Run in DevMode
```bash
docker-compose -f docker-compose.yml -f docker/docker-compose.dev.yml up --build -d
```
After build image:
```bash
docker-compose -f docker-compose.yml -f docker/docker-compose.dev.yml up -d
```

### Run in ProductionMode
```bash
docker-compose -f docker-compose.yml -f docker/docker-compose.prod.yml up --build -d
```
After build image:
```bash
docker-compose -f docker-compose.yml -f docker/docker-compose.prod.yml up -d
```