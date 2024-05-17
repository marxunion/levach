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

### Make database backup
```bash
docker-compose -f docker-compose.yml -f docker/docker-compose.postgres.backup.yml up -d
docker-compose -f docker-compose.yml -f docker/docker-compose.postgres.backup.yml down
```

### Make database migration (Import)
```bash
docker-compose -f docker-compose.yml -f docker/docker-compose.postgres.migration.import.yml up -d
docker-compose -f docker-compose.yml -f docker/docker-compose.postgres.migration.import.yml down
```

### Make database migration (Export)
```bash
docker-compose -f docker-compose.yml -f docker/docker-compose.postgres.migration.export.yml up -d
docker-compose -f docker-compose.yml -f docker/docker-compose.postgres.migration.export.yml down
```

### Run in DevMode
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
``
OR
```bash
docker-compose -f docker-compose.yml -f docker/docker-compose.server.dev.yml up -d
```

### Run in ProductionMode
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