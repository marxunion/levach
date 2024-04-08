<?php
namespace Core;

use Psr\Log\LoggerInterface;
use Monolog\Level;
use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;

class Logger extends MonologLogger
{
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->pushHandler(new StreamHandler(__DIR__.'/../logs/main.log', Level::Debug));
        $this->pushHandler(new StreamHandler(__DIR__.'/../logs/warnings.log', Level::Warning));
        $this->pushHandler(new StreamHandler(__DIR__.'/../logs/errors.log', Level::Error));
    }

    public function error($message)
    {
        $currentDate = date('Y-m-d H:i:s');
        $this->pushHandler(new StreamHandler(__DIR__.'/../logs/errors/'.$currentDate.'.log', Level::Debug));
        $this->error($message);
    }

    public function critical($message)
    {
        $currentDate = date('Y-m-d H:i:s');
        $this->pushHandler(new StreamHandler(__DIR__.'/../logs/errors/critical/'.$currentDate.'.log', Level::Debug));
        parent::critical($message);
    }
}