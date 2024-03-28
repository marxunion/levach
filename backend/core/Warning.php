<?php
namespace Core;

class Warning extends _Exception
{
    protected function InvokeClient(): array
    {
        return [
            'warningStatus' => true, 
            'warningMessage' => $this->clientMessage, 
            'warningCode' => $this->code
        ];
    }
    protected function InvokeServer(): array
    {
        return [
            'warningStatus' => true, 
            'warningMessage' => $this->serverMessage, 
            'warningCode' => $this->code
        ];
    }
    public function InvokeLog()
    {
        Logger::writeWarning($this->InvokeServerMessage());
    }
}