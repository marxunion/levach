# Levach

![](https://github.com/marxunion/levach/blob/main/frontend/src/assets/img/logo/logo.png?raw=true)

A hybrid media project focused on designing a better future through scientific discussion 

## Requirements

1. [**Docker**](https://www.docker.com/)

## How to install and configure

### Configure php
Rename php-example to php and ether your information

### Configure .env
Rename .env-example to .env and ether your auth information

### Database Migration Import
```bash
docker exec POSTGRES sh /var/www/levach/scripts/postgres.migration.import.sh
```

### Database Migration Export
```bash
docker exec POSTGRES sh /var/www/levach/scripts/postgres.migration.export.sh
```

### Run server in DevMode
```bash
docker-compose -f docker-compose.yml -f docker/docker-compose.server.dev.yml up --build
```
OR
```bash
docker-compose -f docker-compose.yml -f docker/docker-compose.server.dev.yml up --build -d
```

After builded PHP image:
```bash
docker-compose -f docker-compose.yml -f docker/docker-compose.server.dev.yml up
```
OR
```bash
docker-compose -f docker-compose.yml -f docker/docker-compose.server.dev.yml up -d
```

### Run server in ProductionMode
```bash
docker-compose -f docker-compose.yml -f docker/docker-compose.server.prod.yml up --build
```
OR
```bash
docker-compose -f docker-compose.yml -f docker/docker-compose.server.prod.yml up --build -d
```

After builded PHP image:
```bash
docker-compose -f docker-compose.yml -f docker/docker-compose.server.prod.yml up
```
OR
```bash
docker-compose -f docker-compose.yml -f docker/docker-compose.server.prod.yml up -d
```