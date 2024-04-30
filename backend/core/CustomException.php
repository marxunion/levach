<?php
namespace Core;

use \Exception;

class CustomException extends Exception
{
    private $extendedMessage;
    private $params;

    public function getExtendedMessage()
    {
        return $this->extendedMessage;
    }

    public function getParams()
    {
        return $this->params;
    }


    public function __construct(int $code = 0, string $message = "", string $extendedMessage = "", array $params = [], ?Throwable $previous = null)
    {
        $this->extendedMessage = $extendedMessage;
        $this->params = $params;

        parent::__construct($message, $code, $previous);
    }
}