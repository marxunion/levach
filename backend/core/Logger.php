<?php
namespace Core;

use Psr\Log\LoggerInterface;
use Monolog\Level;
use Monolog\Logger as MonologLogger;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\StreamHandler;

use Stringable;

class Logger extends MonologLogger
{
    private static Logger $instance;

    public function __construct(string $name)
    {
        parent::__construct($name);
    }

    public static function initInstance($name) : self
    {
        if(!isset(self::$instance)) 
        {
            self::$instance = new self($name);
        }
        return self::$instance;
    }

    public static function getInstance() : self|null
    {
        if(isset(self::$instance)) 
        {
            return self::$instance;
        }
        else
        {
            return null;
        }
    }

    public function error(Stringable|string $message, array $context = []) : void
    {
        $currentDate = date('Y-m-d H:i:s');
        parent::pushHandler(new StreamHandler(__DIR__.'/../logs/errors/'.$currentDate.'.log', Level::Debug));
        parent::error($message);
    }

    public function critical(Stringable|string $message, array $context = []) : void
    {
        $currentDate = date('Y-m-d H:i:s');
        parent::pushHandler(new StreamHandler(__DIR__.'/../logs/errors/critical/'.$currentDate.'.log', Level::Debug));
        parent::critical($message);
    }
}