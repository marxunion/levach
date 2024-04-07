FROM php:fpm

RUN apt-get update 

RUN apt-get install -y \
    libpq-dev \
    cron

RUN docker-php-ext-install pdo pdo_pgsql 

COPY cron/systemInfoLog /etc/cron.d/systemInfoLog

RUN chmod 0644 /etc/cron.d/systemInfoLog

RUN crontab /etc/cron.d/systemInfoLog

RUN touch /var/log/cron.log

COPY ./entrypoint.sh /
ENTRYPOINT ["sh", "/entrypoint.sh"]