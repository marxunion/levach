<?php
namespace Core;

use Aws\S3\S3Client as AwsS3Client;

use Core\Settings;

class S3Client 
{
    private static $initStatus = false;
    private static $connection;

    private static function establishConnection() 
    {
        self::$connection = new AwsS3Client([
            'version' => 'latest',
            'endpoint' => 'http://'.Settings::getSetting("S3_HOST"),
            'region'  => Settings::getSetting("S3_REGION"),
            'use_path_style_endpoint'  => Settings::getSetting("S3_PATH_STYLE_ENDPOINT"),
            'credentials' => [
                'key' => Settings::getSetting("S3_ACCESS_KEY"),
                'secret' => Settings::getSetting("S3_SECRET_KEY"),
            ],
        ]);
        
    }

    public static function getConnection() 
    {
        if(!isset(self::$connection)) 
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