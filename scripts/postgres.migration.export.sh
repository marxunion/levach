DB_NAME=$DB_NAME
POSTGRES_USER=$POSTGRES_USER
POSTGRES_PASSWORD=$POSTGRES_PASSWORD
DB_HOST=${DB_HOST}

export PGPASSWORD=$POSTGRES_PASSWORD

pg_dump -h $DB_HOST -U $POSTGRES_USER -d $DB_NAME --schema-only > /var/www/levach/database/migration.sql