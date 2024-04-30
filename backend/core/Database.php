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
            'database_type' => Settings::getSetting("DB_TYPE"),
            'database_name' => Settings::getSetting("DB_NAME"),
            'server' => Settings::getSetting("DB_HOST"),
            'username' => Settings::getSetting("DB_USER"),
            'password' => Settings::getSetting("DB_PASSWORD")
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