<?php

namespace Core;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;
use Slim\Psr7\Stream;

class _Exception extends Exception
{
    protected int $status;
    protected string $clientMessage;
    protected string $serverMessage;
    protected $code;
    protected ?ResponseInterface $clientResponse = null;

    public function __construct(int $status = 500, string $clientMessage = '', string $serverMessage = null, $code = "000000")
    {
        parent::__construct($clientMessage); // Вызываем конструктор базового класса Exception
        $this->status = $status;
        $this->clientMessage = $clientMessage;

        if ($serverMessage === null) {
            $this->serverMessage = $clientMessage; // Если нет явно указанного сообщения для сервера, используем клиентское сообщение
        } else {
            $this->serverMessage = $serverMessage;
        }

        $this->code = $code;
    }

    public function getClientResponse(): ResponseInterface
    {
        if ($this->clientResponse === null) {
            $this->clientResponse = new Response();
            $this->clientResponse->getBody()->write(json_encode($this->InvokeClient()));
            $this->clientResponse = $this->clientResponse->withStatus($this->status)->withHeader('Content-type', 'application/json');
        }
        return $this->clientResponse;
    }

    protected function InvokeClient(): array
    {
        return [
            'exceptionStatus' => true,
            'exceptionMessage' => $this->clientMessage,
            'exceptionCode' => $this->code
        ];
    }

    public function InvokeServerMessage(): string
    {
        return 'Status: ' . $this->status . ' | Message: ' . $this->serverMessage . ' | Code: ' . $this->code;
    }

    public function InvokeLog()
    {
        Logger::writeDebug($this->InvokeServerMessage());
    }
}