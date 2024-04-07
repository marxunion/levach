<?php
namespace Core;

use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Handlers\ErrorHandler;
use Throwable;
use Core\Logger;
use Core\Warning;
use Core\Error;
use Core\ErrorCritical;

class CustomExceptionHandler extends ErrorHandler
{
    private $exception = null;
    private $exceptionType;
    private $exceptionDetails = [];

    public function __construct(bool $displayErrorDetails)
    {
        $this->exception = null;
        $this->exceptionDetails = [];
        parent::__construct($displayErrorDetails);
    }

    public function __invoke(Throwable $exception, bool $displayErrorDetails): ResponseInterface
    {
        $this->exception = $exception;
        $this->exceptionType = get_class($exception);
        
        if ($this->exceptionType == Error::class || $this->exceptionType == Warning::class || $this->exceptionType == ErrorCritical::class) 
        {
            return $this->handleCustomException();
        }

        return $this->handleDefaultException();
    }

    private function handleCustomException() : ResponseInterface
    {
        switch ($this->exceptionType) 
        {
            if($this->displayErrorDetails)
            {
                $this->exceptionDetails['code'] = $this->exception->getCode();
                $this->exceptionDetails['message'] = $this->exception->getExtendedMessage();
                $this->exceptionDetails['file'] = $this->exception->getFile();
                $this->exceptionDetails['line'] = $this->exception->getLine();
                $this->exceptionDetails['trace'] = $this->exception->getTrace();
                $this->exceptionDetails['date'] = date('Y-m-d H:i:s');
                if($this->exceptionDetails['message'] == "")
                {
                    $message = $this->exception->getMessage();
                    if($message != "")
                    {
                        $this->exceptionDetails['message'] = $message;
                    }
                    else
                    {
                        $this->exceptionDetails['message'] = "Unknown error";
                        
                    }
                }
            }
            else
            {
                $this->exceptionDetails['code'] = $this->exception->getCode();
                $this->exceptionDetails['message'] = $this->exception->getClientMessage();
                $this->exceptionDetails['date'] = date('Y-m-d H:i:s');
            }
            $logMessage = 'Code: '.$this->exceptionDetails['code'].' | Message: '.$this->exceptionDetails['message'].' | File: '.$this->exceptionDetails['file'].' | Line: '.$this->exceptionDetails['line'].' | Trace: '.$this->exception->getTraceAsString();
            case Error::class:
                $responsePayload = json_encode(['Error' => $exceptionDetails]);
                $response = $this->responseFactory->createResponse(500);

                Logger::WriteError($logMessage);
                break;
            case Warning::class:
                $responsePayload = json_encode(['Warning' => $exceptionDetails]);
                $response = $this->responseFactory->createResponse(500);

                Logger::WriteWarning($logMessage);
                break;
            case ErrorCritical::class:
                $responsePayload = json_encode(['ErrorCritical' => $exceptionDetails]);
                $response = $this->responseFactory->createResponse(500);

                Logger::WriteCriticalError($logMessage);
                break;
            default:
                $responsePayload = json_encode(['Error' => $exceptionDetails]);
                $response = $this->responseFactory->createResponse(500);
                
                Logger::WriteError($logMessage);
                break;
        }
        $response->getBody()->write($responsePayload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    private function handleDefaultException(): ResponseInterface
    {
        if($this->displayErrorDetails)
        {
            $this->exceptionDetails['code'] = $this->exception->getCode();
            $this->exceptionDetails['message'] = $this->exception->getMessage();
            $this->exceptionDetails['file'] = $this->exception->getFile();
            $this->exceptionDetails['line'] = $this->exception->getLine();
            $this->exceptionDetails['trace'] = $this->exception->getTrace();
            $this->exceptionDetails['date'] = date('Y-m-d H:i:s');
        }
        else
        {
            $this->exceptionDetails['code'] = $this->exception->getCode();
            $this->exceptionDetails['message'] = $this->exception->getMessage();
            $this->exceptionDetails['date'] = date('Y-m-d H:i:s');
        }

        Logger::WriteErrorCritical('Code: '.$this->exceptionDetails['code'].' | Message: '.$this->exceptionDetails['message'].' | File: '.$this->exceptionDetails['file'].' | Line: '.$this->exceptionDetails['line'].' | Trace: '.$this->exception->getTraceAsString());

        $responsePayload = json_encode(['ErrorCritical' => $exceptionDetails]);

        $response = $this->responseFactory->createResponse(500);
        $response->getBody()->write($responsePayload);
        
        return $response->withHeader('Content-Type', 'application/json');
    }
}