<?php
namespace Core;

use Medoo\Medoo;

use Core\Settings;

class Database 
{
    private static $connection;

    private static function establishConnection() 
    {
        self::$connection = new Medoo([
            'database_type' => Settings::Get("DB_TYPE"),
            'database_name' => Settings::Get("DB_NAME"),
            'server' => Settings::Get("DB_HOST"),
            'username' => Settings::Get("DB_USER"),
            'password' => Settings::Get("DB_PASSWORD")
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
        self::establishConnection();
    }
}