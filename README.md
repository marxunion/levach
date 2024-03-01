# Levach

![](https://github.com/marxunion/levach/blob/main/frontend/src/assets/img/logo.png?raw=true)

A hybrid media project focused on designing a better future through scientific discussion 

## Requirements

1. [**Docker**](https://www.docker.com/)
2. [**Docker PHP Image**](https://hub.docker.com/_/php)
3. [**Docker Composer Image**](https://hub.docker.com/_/composer)
4. [**Docker Nginx Image**](https://hub.docker.com/_/nginx)
5. [**Docker NodeJS Image**](https://hub.docker.com/_/node)


## How to install and configure


### Start in DevMode
```bash
docker-compose -f docker-compose.yml -f docker/docker-compose.dev.yml up -d
```

### Start in ProductionMode
```bash
docker-compose -f docker-compose.yml -f docker/docker-compose.prod.yml up -d
```