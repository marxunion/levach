<?php
namespace Core;

use Monolog\Level;
use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;
use Stringable;

class Logger extends MonologLogger
{
    private static ?Logger $instance = null;
    private string $currentDate;
    private bool $exceptionHandlersInitialized = false;

    private function __construct(string $name)
    {
        parent::__construct($name);
        $this->currentDate = date('Y-m-d');
        $this->initHandlers();
    }

    public static function initInstance(string $name) : self
    {
        if (self::$instance === null) 
        {
            self::$instance = new self($name);
        }
        return self::$instance;
    }

    public static function getInstance() : ?self
    {
        return self::$instance;
    }

    private function initHandlers()
    {
        $this->pushHandler(new StreamHandler(__DIR__.'/../logs/main.log', Level::Debug));
    }

    public function initExceptionHandlers()
    {
        $this->pushHandler(new StreamHandler(__DIR__.'/../logs/warnings.log', Level::Warning));
        $this->pushHandler(new StreamHandler(__DIR__.'/../logs/errors.log', Level::Error));

        $errorLogFile = __DIR__ . '/../logs/errors/' . $this->currentDate . '.log';
        $this->pushHandler(new StreamHandler($errorLogFile, Level::Error));

        $criticalLogFile = __DIR__ . '/../logs/errors/critical/' . $this->currentDate . '.log';
        $this->pushHandler(new StreamHandler($criticalLogFile, Level::Critical));

        $this->exceptionHandlersInitialized = true;
    }

    private function checkForNewDay()
    {
        $newDate = date('Y-m-d');
        if ($newDate !== $this->currentDate) 
        {
            $this->currentDate = $newDate;

            $this->resetHandlers();
            $this->initHandlers(); 

            if ($this->exceptionHandlersInitialized) 
            {
                $this->initExceptionHandlers(); 
            }
        }
    }

    private function resetHandlers()
    {
        $this->handlers = [];
    }

    public function error(Stringable|string $message, array $context = []) : void
    {
        $this->checkForNewDay();
        parent::error($message, $context);
    }

    public function critical(Stringable|string $message, array $context = []) : void
    {
        $this->checkForNewDay();
        parent::critical($message, $context);
    }
}