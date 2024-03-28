<?php
namespace Base;

use Core\Error;
use Core\Warning;
use Core\ErrorCritical;
use Core\Router;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;
use Slim\Factory\AppFactory;
use Slim\Psr7\Stream;
use Slim\App;

class BaseHandlerRoute extends BaseHandler
{
    protected $request;
    protected $response;

    public function __construct(Request $request = null, Response $response = null)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function _Init()
    {

    }

    public function Init()
    {
        try
        {
            $this->_Init();
        }
        catch (Warning $error)
        {
            $error->InvokeLog();
            $this->response = $error->InvokeClientResponse();
            return;
        }
        catch (Error $error)
        {
            $error->InvokeLog();
            $this->response = $error->InvokeClientResponse();
            return;
        }
        catch (ErrorCritical $error)
        {
            $error->InvokeLog();
            $this->response = $error->InvokeClientResponse();
            return;
        }
    }

    public function _Process()
    {

    }

    public function Process()
    {
        try
        {
            $this->_Process();
        }
        catch (Warning $error)
        {
            $error->InvokeLog();
            $this->response = $error->InvokeClientResponse();
            return;
        }
        catch (Error $error)
        {
            $error->InvokeLog();
            $this->response = $error->InvokeClientResponse();
            return;
        }
        catch (ErrorCritical $error)
        {
            $error->InvokeLog();
            $this->response = $error->InvokeClientResponse();
            return;
        }
    }

    public function _Finish()
    {
        if($this->response != null)
        {
            return $this->response;
        }
        else
        {
            $error = new Error(500, "Api Unknown Error", "Api Failed finish route without handler", "000000");
            $error->InvokeLog();
            $this->response = $error->InvokeClientResponse();
        }
    }

    public function Finish()
    {
        try
        {
            $this->_Finish();
        }
        catch (Warning $error)
        {
            $error->InvokeLog();
            $this->response = $error->InvokeClientResponse();
            return;
        }
        catch (Error $error)
        {
            $error->InvokeLog();
            $this->response = $error->InvokeClientResponse();
            return;
        }
        catch (ErrorCritical $error)
        {
            $error->InvokeLog();
            $this->response = $error->InvokeClientResponse();
            return;
        }
    }

    public function Handle()
    {
        $this->Init();
        $this->Process();
        return $this->Finish();
    }
}