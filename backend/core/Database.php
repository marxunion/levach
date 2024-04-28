<?php
namespace Core;

use Medoo\Medoo;

use Core\Settings;

class Database 
{
    private static $initStatus = false;
    private static $connection;

    private static function establishConnection() 
    {
        self::$connection = new Medoo([
            'database_type' => Settings::getProperty("DB_TYPE"),
            'database_name' => Settings::getProperty("DB_NAME"),
            'server' => Settings::getProperty("DB_HOST"),
            'username' => Settings::getProperty("DB_USER"),
            'password' => Settings::getProperty("DB_PASSWORD")
        ]);
    }

    public static function getConnection() 
    {
        if (!isset(self::$connection)) 
        {
            self::establishConnection();
        }
        return self::$connection;
    }

    public static function closeConnection()
    {
        self::$connection = null;
    }

    public static function Init()
    {
        $initStatus = true;
        self::establishConnection();
    }

    public static function isInited()
    {
        return $initStatus;
    }
}