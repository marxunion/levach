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

### Configure frontend config
Open frontend/configs/main.json and ether your domain name and recaptcha site key 

### Run server in DevMode
```bash
docker-compose -f docker-compose.server.dev.yml up --build
```
OR
```bash
docker-compose -f docker-compose.server.dev.yml up --build -d
```

After builded PHP image:
```bash
docker-compose -f docker-compose.server.dev.yml up
```
OR
```bash
docker-compose -f docker-compose.server.dev.yml up -d
```

### Run server in ProductionMode
```bash
docker-compose -f docker-compose.server.prod.yml up --build
```
OR
```bash
docker-compose -f docker-compose.server.prod.yml up --build -d
```

After builded PHP image:
```bash
docker-compose -f docker-compose.server.prod.yml up
```
OR
```bash
docker-compose -f docker-compose.server.prod.yml up -d
```
After starting the server, the port for access to http nginx 8086(you can change this port in nginx/prod.conf if it is already used), for deployment to the Internet, you can use nginx + letsencrypt, the example of the config is shown with letsencrypt in nginx-example.conf. 

### Database Migration Import
```bash
docker exec POSTGRES sh /var/www/levach/scripts/postgres.migration.import.sh
```

### Database Migration Export
```bash
docker exec POSTGRES sh /var/www/levach/scripts/postgres.migration.export.sh
```

### Create admin account
```bash
docker exec POSTGRES sh /var/www/levach/scripts/admin.create.account.sh
```

### Set permissions for edit
```bash
sudo chmod 777 backend/logs
sudo chmod 777 backend/logs/errors
sudo chmod 777 backend/logs/errors/critical
sudo chmod 777 backend/vendor/ezyang/htmlpurifier/library/HTMLPurifier/DefinitionCache/Serializer
sudo chmod 777 media
sudo chmod 777 media/img
```