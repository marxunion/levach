<?php
namespace Core;

use Dotenv\Dotenv;

class Settings
{
    private static $settings = [];

    public static function Init()
    {
        self::$settings = 
        [
            'default_changeable_article_edit_timeout_minutes' => $_ENV['default_changeable_article_edit_timeout_minutes'],
            'default_changeable_max_upload_filesize_mb' => $_ENV['default_changeable_max_upload_filesize_mb'],
            'default_changeable_article_need_rating_to_approve_editorially' => $_ENV['default_changeable_article_need_rating_to_approve_editorially'],
            
            'HOSTNAME' => $_ENV['HOSTNAME'],

            'S3_HOST' => $_ENV['S3_HOST'],
            'S3_IMAGES_BUCKET_NAME' => $_ENV['S3_IMAGES_BUCKET_NAME'],
            'S3_REGION' => $_ENV['S3_REGION'],
            'S3_ACCESS_KEY' => $_ENV['S3_ACCESS_KEY'],
            'S3_SECRET_KEY' => $_ENV['S3_SECRET_KEY'],

            'DB_TYPE' => $_ENV['DB_TYPE'],
            'DB_NAME' => $_ENV['DB_NAME'],
            'DB_HOST' => $_ENV['DB_HOST'],
            'DB_USER' => $_ENV['DB_USER'],
            'DB_PASSWORD' => $_ENV['DB_PASSWORD'],

            'RECAPTCHA_SECRETKEY' => $_ENV['RECAPTCHA_SECRETKEY']
        ];

        if($_ENV['S3_PATH_STYLE_ENDPOINT'] == "true")
        {
            self::$settings['S3_PATH_STYLE_ENDPOINT'] = true;
        }
        else
        {
            self::$settings['S3_PATH_STYLE_ENDPOINT'] = false;
        }

        if($_ENV['DEBUG_MODE'] == "true")
        {
            self::$settings['DEBUG_MODE'] = true;
        }
        else
        {
            self::$settings['DEBUG_MODE'] = false;
        }

        if($_ENV['IS_DEBUG_SERVER'] == "true")
        {
            self::$settings['IS_DEBUG_SERVER'] = true;
        }
        else
        {
            self::$settings['IS_DEBUG_SERVER'] = false;
        }
    }
    public static function getSetting($key)
    {
        if(isset(self::$settings[$key]))
        {
            return self::$settings[$key];
        }
        else
        {
            return null;
        }
    }
    public static function setSetting($key, $value)
    {
        self::$settings[$key] = $value;
    }
}
