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
use Core\Database;

use Base\BaseHandler;
use Base\BaseHandlerRouterWithArgs;

class MediaLoadImageHandler extends BaseHandlerRouterWithArgs
{
    public function __construct(Request $request, Response $response, array $args)
    {
        parent::__construct($request, $response, $args);
    }
    public function process()
    {
        $file = $this->args['file'];
            
        $filePath = __DIR__.'/../../../media/img/'.$this->args['file'];
                
        try 
        {
            if (file_exists($filePath)) 
            {
                try 
                {
                    return $this->response->withFile($filePath);
                } 
                catch (\Throwable $th) 
                {
                    Logger::WriteError("LoadFile Unable to open file, filePath=".$filePath);
                    return $this->response->withStatus(500)->withHeader('Content-type', 'application/json')->withJson(
                    [
                        'errorStatus' => true, 
                        'errorMessage' => 'Unable to open file', 
                        'errorCode' => "001002"
                    ]);
                }
            }
            else
            {
                Logger::WriteWarning("LoadFile File not found, filePath=".$filePath);
                return $this->response->withStatus(404)->withHeader('Content-type', 'application/json')->withJson(
                [
                    'errorStatus' => true, 
                    'errorMessage' => 'File not found', 
                    'errorCode' => "001001"
                ]);
            }
        }
        catch (\Throwable $th)
        {
            Logger::WriteError("LoadFile File not found, filePath=".$filePath);
            return $this->response->withStatus(500)->withHeader('Content-type', 'application/json')->withJson(
            [
                'errorStatus' => true, 
                'errorMessage' => 'File not found', 
                'errorCode' => "001001"
            ]);
        }
    }
}