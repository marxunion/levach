<?php
namespace Core;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Interfaces\CallableResolverInterface;
use Slim\Handlers\ErrorHandler;

use Core\Settings;
use Core\Logger;
use Core\Warning;
use Core\Error;
use Core\Critical;

use Throwable;

class CustomExceptionHandler extends ErrorHandler
{
    private $exceptionType;
    private $exceptionDetails = [];

    public function __construct(CallableResolverInterface $callableResolver, ResponseFactoryInterface $responseFactory, ?LoggerInterface $logger)
    {
        $this->exceptionDetails = [];
        $this->responseFactory = $responseFactory;
        parent::__construct($callableResolver, $responseFactory, $logger);
    }

    public function __invoke(ServerRequestInterface $request, Throwable $exception, bool $displayErrorDetails, bool $logErrors, bool $logErrorDetails): ResponseInterface
    {
        
        $this->exception = $exception;
        $this->exceptionType = get_class($exception);
        
        if($this->exceptionType == Error::class || $this->exceptionType == Warning::class || $this->exceptionType == Critical::class) 
        {
            return $this->handleCustomException();
        }

        return $this->handleDefaultException();
    }

    private function handleCustomException() : ResponseInterface
    {
        $this->exceptionDetails['code'] = $this->exception->getCode();
        
        if(Settings::getSetting("DEBUG_MODE"))
        {
            $this->exceptionDetails['message'] = $this->exception->getExtendedMessage();
            $this->exceptionDetails['file'] = $this->exception->getFile();
            $this->exceptionDetails['line'] = $this->exception->getLine();
            $this->exceptionDetails['trace'] = $this->exception->getTrace();
            $this->exceptionDetails['params'] = $this->exception->getParams();
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
                    switch($this->exceptionType) 
                    {
                        case Error::class:
                            $this->exceptionDetails['message'] = "Unknown error";
                            break;
                        case Warning::class:
                            $this->exceptionDetails['message'] = "Unknown warning";
                            break;
                        case Critical::class:
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
            $this->exceptionDetails['params'] = $this->exception->getParams();
            
            $message = $this->exception->getMessage();
            
            if($message != "")
            {
                $this->exceptionDetails['message'] = $message;
            }
            else
            {
                switch($this->exceptionType) 
                {
                    case Error::class:
                        $this->exceptionDetails['message'] = "Unknown error";
                        break;
                    case Warning::class:
                        $this->exceptionDetails['message'] = "Unknown warning";
                        break;
                    case Critical::class:
                        $this->exceptionDetails['message'] = "Unknown error critical";
                        break;
                    default:
                        $this->exceptionDetails['message'] = "Unknown exception";
                        break;
                }
            }
            
            $this->exceptionDetails['date'] = date('Y-m-d H:i:s');
        }

        $message = $this->exception->getExtendedMessage();
        if(empty($message))
        {
            $message = $this->exception->getMessage();
            if(empty($message))
            {
                switch($this->exceptionType) 
                {
                    case Error::class:
                        $this->exceptionDetails['message'] = "Unknown error";
                        break;
                    case Warning::class:
                        $this->exceptionDetails['message'] = "Unknown warning";
                        break;
                    case Critical::class:
                        $this->exceptionDetails['message'] = "Unknown error critical";
                        break;
                    default:
                        $this->exceptionDetails['message'] = "Unknown exception";
                        break;
                }
            }
        }
        $logMessage = 'Code: '.$this->exceptionDetails['code'].' | Message: '.$message.' | File: '.$this->exception->getFile().' | Line: '.$this->exception->getLine().' | Trace: '.$this->exception->getTraceAsString();
        $response = $this->responseFactory->createResponse($this->exceptionDetails['code']);

        switch($this->exceptionType) 
        {
            case Error::class:
                $responsePayload = json_encode(['Error' => $this->exceptionDetails]);
                $this->logger->error($logMessage);
                break;
            case Warning::class:
                $responsePayload = json_encode(['Warning' => $this->exceptionDetails]);
                $this->logger->warning($logMessage);
                break;
            case Critical::class:
                $responsePayload = json_encode(['Critical' => $this->exceptionDetails]);
                $this->logger->critical($logMessage);
                break;
            default:
                $responsePayload = json_encode(['Error' => $this->exceptionDetails]);
                $this->logger->error($logMessage);
                break;
        }
        
        $response->getBody()->write($responsePayload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    private function handleDefaultException(): ResponseInterface
    {
        if(Settings::getSetting("DEBUG_MODE"))
        {
            if(!empty($this->exception->getCode()))
            {
                $this->exceptionDetails['code'] = $this->exception->getCode();
            }
            else
            {
                $this->exceptionDetails['code'] = 500;
            }
            if(!empty($this->exception->getMessage()))
            {
                $this->exceptionDetails['message'] = $this->exception->getMessage();
            }
            else
            {
                $this->exceptionDetails['message'] = "Unknown Error";
            }
            if(!empty($this->exception->getFile()))
            {
                $this->exceptionDetails['file'] = $this->exception->getFile();
            }
            else
            {
                $this->exceptionDetails['file'] = "Unknown File";
            }
            if(!empty($this->exception->getLine()))
            {
                $this->exceptionDetails['line'] = $this->exception->getLine();
            }
            else
            {
                $this->exceptionDetails['line'] = 0;
            }

            if(!empty($this->exception->getTrace()))
            {
                $trace = $this->exception->getTrace();

                if(is_array($trace))
                {
                    if(count($trace) < 20)
                    {
                        $this->exceptionDetails['trace'] = 'Too many big trace';
                    }
                    else
                    {
                        $this->exceptionDetails['trace'] = 'Too many big trace';
                    }
                }
                else
                {
                    $this->exceptionDetails['trace'] = 'Error Trace';
                }
            }
            else
            {
                $this->exceptionDetails['trace'] = 'Unknown Trace';
            }

            $this->exceptionDetails['date'] = date('Y-m-d H:i:s');

            $this->logger->critical('Code: '.$this->exceptionDetails['code'].' | Message: '.$this->exceptionDetails['message'].' | File: '.$this->exceptionDetails['file'].' | Line: '.$this->exceptionDetails['line'].' | Trace: '.$this->exception->getTraceAsString());
        }
        else
        {
            $this->exceptionDetails['code'] = 500;
            $this->exceptionDetails['message'] = "Unknown Error";
            $this->exceptionDetails['date'] = date('Y-m-d H:i:s');

            if(!empty($this->exception->getFile()))
            {
                $file = $this->exception->getFile();
            }
            else
            {
                $file = 'Unknown file';
            }

            if(!empty($this->exception->getLine()))
            {
                $line = $this->exception->getLine();
            }
            else
            {
                $line = 0;
            }

            $this->logger->critical('Code: '.$this->exceptionDetails['code'].' | Message: '.$this->exceptionDetails['message'].' | File: '.$file.' | Line: '.$line.' | Trace: '.$this->exception->getTraceAsString());
        }

        $this->logger->critical('Code: '.$this->exceptionDetails['code'].' | Message: '.$this->exceptionDetails['message'].' | File: '.$this->exception->getFile().' | Line: '.$this->exception->getLine().' | Trace: '.$this->exception->getTraceAsString());

        $responsePayload = json_encode(['Critical' => $this->exceptionDetails]);

        $response = $this->responseFactory->createResponse(500);
        $response->getBody()->write($responsePayload);
        
        return $response->withHeader('Content-Type', 'application/json');
    }
}