<?php
namespace Base;

use Core\Error;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class BaseHandlerRoute extends BaseHandler
{
    protected array $data;
    
    protected array $cookiesBody;
    protected array $parsedBody;

    protected $request;
    protected $response;

    public function __construct(?Request $request = null, ?Response $response = null)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function Init()
    {
        
    }

    public function Process()
    {

    }

    public function Finish()
    {
        if($this->response != null)
        {
            return $this->response;
        }
        else
        {
            throw new Error(500, "Api Unknown Error", "Api Failed finish route without handler");
        }
    }

    public function Handle()
    {
        $this->Init();
        $this->Process();
        return $this->Finish();
    }
}