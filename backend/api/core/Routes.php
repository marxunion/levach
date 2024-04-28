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

use Api\Handlers\AdminSettingsGetHandler;
use Api\Handlers\AdminSettingsSetHandler;
use Api\Handlers\AdminStatusHandler;
use Api\Handlers\AdminQuitHandler;
use Api\Handlers\AdminLoginHandler;
use Api\Handlers\ArticleNewHandler;
use Api\Handlers\ArticlesHandler;
use Api\Handlers\ArticleViewHandler;
use Api\Handlers\ArticleSearchHandler;
use Api\Handlers\ArticleEditHandler;
use Api\Handlers\ArticleEditPreloadHandler;
use Api\Handlers\MediaLoadImageHandler;
use Api\Handlers\MediaUploadImageHandler;
use Api\Handlers\SponsoringHandler;
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
    
        self::$app->group('/api', function (RouteCollectorProxy $apiGroup) 
        {
            $apiGroup->group('/admin', function (RouteCollectorProxy $adminGroup) 
            {
                $adminGroup->group('/settings', function (RouteCollectorProxy $adminSettingsGroup) 
                {
                    $adminSettingsGroup->post('/get', function (Request $request, Response $response) 
                    {
                        self::$handler = new AdminSettingsHandler($request, $response);
                        return self::$handler->Handle();
                    });

                    $adminSettingsGroup->post('/set', function (Request $request, Response $response) 
                    {
                        self::$handler = new AdminSettingsHandler($request, $response);
                        return self::$handler->Handle();
                    });
                });

                $adminGroup->get('/status', function (Request $request, Response $response) 
                {
                    self::$handler = new AdminStatusHandler($request, $response);
                    return self::$handler->Handle();
                });

                $adminGroup->post('/login', function (Request $request, Response $response) 
                {
                    self::$handler = new AdminLoginHandler($request, $response);
                    return self::$handler->Handle();
                });

                $adminGroup->post('/quit', function (Request $request, Response $response) 
                {
                    self::$handler = new AdminQuitHandler($request, $response);
                    return self::$handler->Handle();
                });
            });

            $apiGroup->get('/', function (Request $request, Response $response) 
            {
                self::$handler = new StatusHandler($request, $response);
                return self::$handler->Handle();
            });

            $apiGroup->get('/sponsoring', function (Request $request, Response $response) 
            {
                self::$handler = new SponsoringHandler($request, $response);
                return self::$handler->Handle();
            });

            $apiGroup->get('/status', function (Request $request, Response $response) 
            {
                self::$handler = new StatusHandler($request, $response);
                return self::$handler->Handle();
            });
    
            $apiGroup->post('/article/new', function (Request $request, Response $response) 
            {
                self::$handler = new ArticleNewHandler($request, $response);
                return self::$handler->Handle();
            });

            $apiGroup->post('/article/edit/{editCode}', function (Request $request, Response $response, array $args) 
            {
                self::$handler = new ArticleEditHandler($request, $response, $args);
                return self::$handler->Handle();
            });

            $apiGroup->get('/article/edit/preload/{editCode}', function (Request $request, Response $response, array $args) 
            {
                self::$handler = new ArticleEditPreloadHandler($request, $response, $args);
                return self::$handler->Handle();
            });
    
            $apiGroup->get('/article/view/{viewCode}', function (Request $request, Response $response, array $args) 
            {
                self::$handler = new ArticleViewHandler($request, $response, $args);
                return self::$handler->Handle();
            });

            $apiGroup->get('/article/search/{queryStr}', function (Request $request, Response $response, array $args) 
            {
                self::$handler = new ArticleSearchHandler($request, $response, $args);
                return self::$handler->Handle();
            });
    
            $apiGroup->get('/articles', function (Request $request, Response $response) 
            {
                self::$handler = new ArticlesHandler($request, $response);
                return self::$handler->Handle();
            });
    
            $apiGroup->get('/media/img/{file}', function (Request $request, Response $response, array $args) 
            {
                self::$handler = new MediaLoadImageHandler($request, $response, $args);
                return self::$handler->Handle();
            });
            $apiGroup->post('/media/img/upload', function (Request $request, Response $response) 
            {
                self::$handler = new MediaUploadImageHandler($request, $response);
                return self::$handler->Handle();
                
            });
            $apiGroup->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function (Request $request, Response $response) 
            {
                return self::$handler->Handle();
            });
        });
    }
}