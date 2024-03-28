<?php
namespace Api\Handlers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;
use Slim\Factory\AppFactory;
use Slim\Psr7\Stream;
use Slim\App;

use Core\Settings;
use Core\Logger;
use Core\Error;
use Core\Database;

use Base\BaseHandlerRouteWithArgs;

class MediaLoadImageHandler extends BaseHandlerRouteWithArgs
{
    private $file;
    private $filePath;

    public function Init()
    {
        $this->file = $this->args['file'];
            
        $this->filePath = __DIR__.'/../../../media/img/'.$this->args['file'];
    }
    public function Process()
    {
        try 
        {
            if (file_exists($this->filePath)) 
            {
                try 
                {
                    $this->response = $this->response->withFile($this->filePath);
                } 
                catch (\Throwable $th) 
                {
                    $error = new Error(404, "LoadImage File not found", "LoadImage Unable to open file, filePath=".$this->filePath, "001002");
                    $error->InvokeLog();
                    $this->response = $error->InvokeClientResponse();
                }
            }
            else
            {
                $error = new Error(404, "LoadImage File not found", "LoadImage File not found, filePath=".$this->filePath, "001001");
                $error->InvokeLog();
                $this->response = $error->InvokeClientResponse();
            }
        }
        catch (\Throwable $th)
        {
            $error = new Error(404, "LoadImage File not found", "LoadImage File not found, filePath=".$this->filePath, "001001");
            $error->InvokeLog();
            $this->response = $error->InvokeClientResponse();
        }
    }
}