<?php
namespace Core;

use Slim\Routing\RouteCollectorProxy;
use Slim\Factory\AppFactory;
use Slim\Psr7\Response;
use Slim\Psr7\Stream;
use Slim\App;

use Core\Logger;

class Error
{
    private int $status;
    private string $clientMessage;
    private string $serverMessage;
    private string $code;
    private $clientResponse;

    public function __construct(int $status = 500, string $clientMessage = '', string $serverMessage = null, string $code = "000000")
    {
        $this->status = $status;
        $this->clientMessage = $clientMessage;

        if($serverMessage == null) $this->serverMessage = $message;
        else $this->serverMessage = $serverMessage;

        $this->code = $code;
    }

    //Client
    private function InvokeClient()
    {
        return [
            'errorStatus' => true, 
            'errorMessage' => $this->clientMessage, 
            'errorCode' => $this->code
        ];
    }
    public function InvokeClientMessage()
    {
        return 'Status: '.$this->status.' | Message: '.$this->clientMessage.' | Code: '.$this->code;

    }
    public function InvokeClientResponse()
    {
        $response = new Response();
        $response->getBody()->write(json_encode($this->InvokeClient()));
        return $response->withStatus($this->status)->withHeader('Content-type', 'application/json');
    }

    //Server
    private function InvokeServer()
    {
        return [
            'errorStatus' => true, 
            'errorMessage' => $this->serverMessage, 
            'errorCode' => $this->code
        ];
    }
    public function InvokeServerMessage()
    {
        return 'Status: '.$this->status.' | Message: '.$this->serverMessage.' | Code: '.$this->code;
    }

    private function InvokeServerResponse()
    {
        return $this->InvokeServer();
    }
    
    //Log
    public function InvokeLog()
    {
        Logger::writeError($this->InvokeServerMessage());
    }
}

class ErrorCritical extends Error
{
    public function InvokeLog()
    {
        Logger::writeCtitical($this->InvokeServerMessage());
    }
}