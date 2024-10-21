<?php
namespace Core;

use Aws\Exception\AwsException;
use Aws\S3\S3Client as AwsS3Client;

use Core\Settings;

use Helpers\StringFormatter;

class S3Client
{
    private static $connection;

    private static function establishConnection() 
    {
        try
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
            
            if(Settings::getSetting("DEBUG_MODE"))
            {
                Logger::getInstance()->info("S3 connection successfully established");
            }
        }
        catch(AwsException $e)
        {
            Logger::getInstance()->critical(StringFormatter::awsExceptionToString($e));
            throw new Critical(500, "Failed to establish s3 connenction", "Failed to establish s3 connenction");
        }
    }

    public static function createBucket($bucketName)
    {
        try 
        {
            self::$connection->createBucket([
                'Bucket' => $bucketName,
            ]);
        } 
        catch(AwsException $e) 
        {
            Logger::getInstance()->critical(StringFormatter::awsExceptionToString($e));
            throw new Critical(500, "Failed to create bucket $bucketName", "Failed to create bucket $bucketName");
        }
    }

    public static function ensureBucketExists() 
    {
        $bucketName = Settings::getSetting("S3_IMAGES_BUCKET_NAME");
        if(!self::$connection->doesBucketExist($bucketName)) 
        {
            self::createBucket($bucketName);
        }
    }

    public static function getConnection() 
    {
        if(!isset(self::$connection)) 
        {
            self::establishConnection();
            self::ensureBucketExists();
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
        self::ensureBucketExists();
    }
}