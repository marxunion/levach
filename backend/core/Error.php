<?php
namespace Core;

class Error extends _Exception
{
    protected function InvokeClient(): array
    {
        return [
            'errorStatus' => true, 
            'errorMessage' => $this->clientMessage, 
            'errorCode' => $this->code
        ];
    }
    protected function InvokeServer(): array
    {
        return [
            'errorStatus' => true, 
            'errorMessage' => $this->serverMessage, 
            'errorCode' => $this->code
        ];
    }
    public function InvokeLog()
    {
        Logger::writeError($this->InvokeServerMessage());
    }
}