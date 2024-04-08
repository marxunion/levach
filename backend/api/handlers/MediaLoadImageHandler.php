<?php
namespace Api\Handlers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;
use Slim\Factory\AppFactory;
use Slim\Psr7\Stream;
use Slim\App;

use Core\Settings;
use Core\Warning;
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
        if (file_exists($this->filePath)) 
        {
            $this->response = $this->response->withFile($this->filePath);
        }
        else
        {
            throw new Warning(404, "LoadImage File not found", "LoadImage File not found, filePath=".$this->filePath);
            return;
        }
    }
}