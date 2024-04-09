<?php
namespace Core;

use Dotenv\Dotenv;

class Settings
{
    private static $settings = [];

    public static function Init()
    {
        self::$settings = [
            'MAX_UPLOAD_FILESIZE_MB' => $_ENV['MAX_UPLOAD_FILESIZE_MB'],
            
            'DB_TYPE' => $_ENV['DB_TYPE'],
            'DB_NAME' => $_ENV['DB_NAME'],
            'DB_HOST' => $_ENV['DB_HOST'],
            'DB_USER' => $_ENV['DB_USER'],
            'DB_PASSWORD' => $_ENV['DB_PASSWORD']
        ];

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
    public static function getProperty($key)
    {
        return self::$settings[$key];
    }
    public static function setProperty($key, $value)
    {
        self::$settings[$key] = $value;
    }
}
