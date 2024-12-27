FROM php:8.2.27-fpm

RUN apt-get update 

RUN apt-get install -y \
    libpq-dev \
    cron

RUN docker-php-ext-install pdo pdo_pgsql 

COPY cron/tasks /etc/cron.d/tasks

RUN chmod 0644 /etc/cron.d/tasks

RUN crontab /etc/cron.d/tasks

RUN touch /var/log/cron.log

COPY ./entrypoint.sh /
ENTRYPOINT ["sh", "/entrypoint.sh"]