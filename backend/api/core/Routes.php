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

use Base\BaseHandler;
use Base\BaseHandlerRoute;
use Base\EmptyHandlerRoute;

use Api\Handlers\ArticleNewHandler;
use Api\Handlers\ArticlesHandler;
use Api\Handlers\ArticleViewHandler;
use Api\Handlers\ArticleEditHandler;
use Api\Handlers\MediaLoadImageHandler;
use Api\Handlers\MediaUploadImageHandler;
use Api\Handlers\StatusHandler;
use Api\Handlers\UnknownHandler;

class Routes
{
    private static App $app;
    private static BaseHandler $handler;

    public static function Init(App $app)
    {
        self::$handler = new UnknownHandler();
        self::$app = $app;
        
        self::$app->group('/api', function (RouteCollectorProxy $group) 
        {
            $group->get('/', function (Request $request, Response $response) 
            {
                self::$handler = new StatusHandler($request, $response);
                return self::$handler->Handle();
            });
    
            $group->get('/status', function (Request $request, Response $response) 
            {
                self::$handler = new StatusHandler($request, $response);
                return self::$handler->Handle();
            });
    
            $group->post('/article/new', function (Request $request, Response $response) 
            {
                self::$handler = new ArticleNewHandler($request, $response);
                return self::$handler->Handle();
            });

            $group->post('/article/edit/{tokenEdit}', function (Request $request, Response $response, array $args) 
            {
                self::$handler = new ArticleEditHandler($request, $response, $args);
                return self::$handler->Handle();
            });
    
            $group->get('/article/view/{tokenView}', function (Request $request, Response $response, array $args) 
            {
                self::$handler = new ArticleViewHandler($request, $response, $args);
                return self::$handler->Handle();
            });
    
            $group->get('/articles', function (Request $request, Response $response) 
            {
                self::$handler = new ArticlesHandler($request, $response);
                return self::$handler->Handle();
            });
    
            $group->get('/media/img/{file}', function (Request $request, Response $response, array $args) 
            {
                self::$handler = new MediaLoadImageHandler($request, $response, $args);
                return self::$handler->Handle();
            });
            $group->post('/media/img/upload', function (Request $request, Response $response) 
            {
                self::$handler = new MediaUploadImageHandler($request, $response);
                return self::$handler->Handle();
                
            });
            $group->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function (Request $request, Response $response) 
            {
                return self::$handler->Handle();
            });
        });
    }
}