<?php
namespace Core;

use PDO;
use PDOException;

use Medoo\Medoo;

use Helpers\StringFormatter;
use Core\Logger;
use Core\Settings;

class Database 
{
    private static $connection;

    private static function establishConnection() 
    {
        try 
        {
            self::$connection = new Medoo([
                'database_type' => Settings::getSetting("DB_TYPE"),
                'database_name' => Settings::getSetting("DB_NAME"),
                'server' => Settings::getSetting("DB_HOST"),
                'username' => Settings::getSetting("DB_USER"),
                'password' => Settings::getSetting("DB_PASSWORD"),
                'option' => [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
            ]);
            
            if(Settings::getSetting("DEBUG_MODE"))
            {
                Logger::getInstance()->info("Database connection successfully established");
            }
        } 
        catch (PDOException $e) 
        {
            Logger::getInstance()->critical(StringFormatter::pdoExceptionToString($e));
            throw new Critical(500, "Failed to establish database connenction", "Failed to establish database connenction");
        }
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
        self::getConnection();
    }
}