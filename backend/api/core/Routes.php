<?php
namespace Api\Core;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;
use Slim\Factory\AppFactory;
use Slim\Psr7\Stream;
use Slim\App;

use Api\Handlers\MediaLoadImageHandler;
use Api\Handlers\MediaUploadImageHandler;

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
                    'status' => "ok"
                ];
                return $response->withJson($data);
            });
    
            $group->get('/status', function (Request $request, Response $response) 
            {
                $data = [
                    'status' => "ok"
                ];
                return $response->withJson($data);
            });
    
            $group->post('/article/new', function (Request $request, Response $response) 
            {
                $data = $request->getParsedBody();
                return $response->withJson($data);
            });

            $group->post('/article/edit/{article}', function (Request $request, Response $response, array $args) 
            {
                $data = $request->getParsedBody();
                return $response->withJson($data);
            });
    
            $group->get('/article/view/{article}', function (Request $request, Response $response, array $args) 
            {
                $data = $request->getParsedBody();
                return $response->withJson($data);
            });
    
            $group->get('/articles', function (Request $request, Response $response) 
            {
                $data = $request->getParsedBody();
                return $response->withJson($data);
            });
    
            $group->get('/media/img/{file}', function (Request $request, Response $response, array $args) 
            {
                $handler = new MediaLoadImageHandler($request, $response, $args);
                return $handler->process();
            });
            $group->post('/media/img/upload', function (Request $request, Response $response) 
            {
                $handler = new MediaUploadImageHandler($request, $response);
                return $handler->process();
            });
            $group->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function (Request $request, Response $response) 
            {
                $data = [
                    'errorStatus' => true, 
                    'errorMessage' => 'Api action not found', 
                    'errorCode' => "000001"
                ];
                return $response->withStatus(404)->withJson($data);
            });
        });
        self::$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function (Request $request, Response $response) 
        {
            return $response->withRedirect('/#'.$request->getUri()->getPath(), 301);
        });
    }
}