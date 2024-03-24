<?php
namespace Api\Core;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;
use Slim\Factory\AppFactory;
use Slim\Psr7\Stream;
use Slim\App;

use Core\Settings;
use Core\Logger;
use Core\Database;

class Routes
{
    private static App $app;

    public static function Init(App $app)
    {
        self::$app = $app;
        
        self::$app->group('/api', function (RouteCollectorProxy $group) 
        {
            $group->get('/', function (Request $request, Response $response) 
            {
                $data = [
                    'apistatus' => "ok"
                ];
                return $response->withJson($data);
            });
    
            $group->get('/status', function (Request $request, Response $response) 
            {
                $data = [
                    'apistatus' => "ok"
                ];
                return $response->withJson($data);
            });
    
            $group->post('/article/new', function (Request $request, Response $response) 
            {
                $data = $request->getParsedBody();
                return $response->withJson($data);
            });
    
            $group->get('/article/view', function (Request $request, Response $response, array $args) 
            {
                $data = $request->getParsedBody();
                return $response->withJson($data);
            });
    
            $group->get('/articles', function (Request $request, Response $response, array $args) 
            {
                $data = $request->getParsedBody();
                return $response->withJson($data);
            });
    
            $group->get('/media/img/{file}', function (Request $request, Response $response, array $args) 
            {
                $file = $args['file'];
            
                $filePath = __DIR__ . '/../media/img/' . $args['file'];
    
                if (file_exists($filePath)) 
                {
                    return $response->withFile($file);
                }
                else
                {
                    return $response->withStatus(404)->withJson(['error' => 'File not found']);
                }
    
                /*$fileType = mime_content_type($filePath);
    
                $response = $response->withHeader('Content-Type', $fileType);
    
                $stream = new Stream(fopen($filePath, 'r'));
    
                $response = $response->withBody($stream);
    
                return $response;*/
                
            });
        });
    
        self::$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function (Request $request, Response $response) 
        {
            $data = [
                'apistatus' => "ok"
            ];
            return $response->withJson($data);
        });
    }
}