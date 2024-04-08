<?php
namespace Core;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Interfaces\CallableResolverInterface;
use Slim\Handlers\ErrorHandler;
use Throwable;
use Core\Settings;
use Core\Logger;
use Core\Warning;
use Core\Error;
use Core\ErrorCritical;

class CustomExceptionHandler extends ErrorHandler
{
    private $exceptionType;
    private $exceptionDetails = [];

    public function __construct(CallableResolverInterface $callableResolver, ResponseFactoryInterface $responseFactory)
    {
        $this->exceptionDetails = [];
        $this->responseFactory = $responseFactory;
        parent::__construct($callableResolver, $responseFactory);
    }

    public function __invoke(ServerRequestInterface $request, Throwable $exception, bool $displayErrorDetails, bool $logErrors, bool $logErrorDetails): ResponseInterface
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
        if(Settings::Get("DEBUG_MODE"))
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
                    switch ($this->exceptionType) 
                    {
                        case Error::class:
                            $this->exceptionDetails['message'] = "Unknown error";
                            break;
                        case Warning::class:
                            $this->exceptionDetails['message'] = "Unknown warning";
                            break;
                        case ErrorCritical::class:
                            $this->exceptionDetails['message'] = "Unknown error critical";
                            break;
                        default:
                            $this->exceptionDetails['message'] = "Unknown exception";
                            break;
                    }
                }
            }
        }
        else
        {
            $this->exceptionDetails['code'] = $this->exception->getCode();
            $message = $this->exception->getClientMessage();
            if($message != "")
            {
                $this->exceptionDetails['message'] = $message;
            }
            else
            {
                switch ($this->exceptionType) 
                {
                    case Error::class:
                        $this->exceptionDetails['message'] = "Unknown error";
                        break;
                    case Warning::class:
                        $this->exceptionDetails['message'] = "Unknown warning";
                        break;
                    case ErrorCritical::class:
                        $this->exceptionDetails['message'] = "Unknown error critical";
                        break;
                    default:
                        $this->exceptionDetails['message'] = "Unknown exception";
                        break;
                }
            }
            
            $this->exceptionDetails['date'] = date('Y-m-d H:i:s');
    
        }
        $logMessage = 'Code: '.$this->exceptionDetails['code'].' | Message: '.$this->exceptionDetails['message'].' | File: '.$this->exceptionDetails['file'].' | Line: '.$this->exceptionDetails['line'].' | Trace: '.$this->exception->getTraceAsString();
        
        $response = $this->responseFactory->createResponse(500);
        switch ($this->exceptionType) 
        {
            case Error::class:
                $responsePayload = json_encode(['Error' => $this->exceptionDetails]);
                Logger::WriteError($logMessage);
                break;
            case Warning::class:
                $responsePayload = json_encode(['Warning' => $this->exceptionDetails]);
                Logger::WriteWarning($logMessage);
                break;
            case ErrorCritical::class:
                $responsePayload = json_encode(['ErrorCritical' => $this->exceptionDetails]);
                Logger::WriteCriticalError($logMessage);
                break;
            default:
                $responsePayload = json_encode(['Error' => $this->exceptionDetails]);
                Logger::WriteError($logMessage);
                break;
        }
        $response->getBody()->write($responsePayload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    private function handleDefaultException(): ResponseInterface
    {
        if(Settings::Get("DEBUG_MODE"))
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

        Logger::WriteCriticalError('Code: '.$this->exceptionDetails['code'].' | Message: '.$this->exceptionDetails['message'].' | File: '.$this->exceptionDetails['file'].' | Line: '.$this->exceptionDetails['line'].' | Trace: '.$this->exception->getTraceAsString());

        $responsePayload = json_encode(['ErrorCritical' => $this->exceptionDetails]);

        $response = $this->responseFactory->createResponse(500);
        $response->getBody()->write($responsePayload);
        
        return $response->withHeader('Content-Type', 'application/json');
    }
}