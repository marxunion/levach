<?php
namespace Core;

use Monolog\Level;
use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;

class Logger
{
    private static $log;
    public static function Init()
    {
        self::$log = new MonologLogger('main');
        self::$log->pushHandler(new StreamHandler(__DIR__.'/../logs/main.log', Level::Debug));
        self::$log->pushHandler(new StreamHandler(__DIR__.'/../logs/warnings.log', Level::Warning));
        self::$log->pushHandler(new StreamHandler(__DIR__.'/../logs/errors.log', Level::Error));
    }

    public static function WriteDebug($message)
    {
        self::$log->debug($message);
    }
    public static function WriteInfo($message)
    {
        self::$log->info($message);
    }
    public static function WriteWarning($message)
    {
        self::$log->warning($message);
    }
    public static function WriteError($message)
    {
        $currentDate = date('Y-m-d H:i:s');
        $logerror = new MonologLogger('error-'.$currentDate);
        $logerror->pushHandler(new StreamHandler(__DIR__.'/../logs/errors/'.$currentDate.'.log', Level::Debug));
        self::$log->error($message);
        $logerror->error($message);
    }
    public static function WriteCriticalError($message)
    {
        $currentDate = date('Y-m-d H:i:s');
        $logerror = new MonologLogger('errorCritical-'.$currentDate);
        $logerror->pushHandler(new StreamHandler(__DIR__.'/../logs/errors/critical/'.$currentDate.'.log', Level::Debug));
        self::$log->critical($message);
        $logerror->critical($message);
    }
}