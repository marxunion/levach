<?php
namespace Core;

class ErrorCritical extends _Exception
{
    protected function InvokeClient(): array
    {
        return [
            'errorCriticalStatus' => true, 
            'errorCriticalMessage' => $this->clientMessage, 
            'errorCriticalCode' => $this->code
        ];
    }
    protected function InvokeServer(): array
    {
        return [
            'errorCriticalStatus' => true, 
            'errorCriticalMessage' => $this->serverMessage, 
            'errorCriticalCode' => $this->code
        ];
    }
    public function InvokeLog()
    {
        Logger::writeError($this->InvokeServerMessage());
    }
}