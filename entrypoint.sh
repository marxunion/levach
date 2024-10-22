printenv > /etc/environment
cron -f &
docker-php-entrypoint php-fpm