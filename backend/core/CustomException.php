<?php
namespace Core;

use \Exception;

class CustomException extends Exception
{
    private $extendedMessage;

    public function getExtendedMessage()
    {
        return $this->extendedMessage;
    }

    public function __construct(int $code = 0, string $message = "", string $extendedMessage = "", ?Throwable $previous = null)
    {
        $this->extendedMessage = $extendedMessage;
        parent::__construct($message, $code, $previous);
    }
}