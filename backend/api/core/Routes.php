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

use Api\Handlers\ArticleApproveWithChangesHandler;

use Api\Handlers\AdminRejectAllApproveHandler;
use Api\Handlers\AdminRejectAllPremoderateHandler;

use Api\Handlers\AdminArticlePremoderateHandler;

use Api\Handlers\ArticleApproveRequestHandler;

use Api\Handlers\AdminArticleApproveHandler;
use Api\Handlers\AdminArticleDeleteHandler;
use Api\Handlers\AdminArticleApprovePreloadHandler;

use Api\Handlers\AdminArticleCommentsDeleteHandler;
use Api\Handlers\ArticleCommentsGetHandler;
use Api\Handlers\ArticleCommentsSetHandler;
;
use Api\Handlers\csrfTokenHandler;

use Api\Handlers\AdminSettingsGetHandler;
use Api\Handlers\AdminSettingsSetHandler;

use Api\Handlers\AdminStatusHandler;
use Api\Handlers\AdminQuitHandler;
use Api\Handlers\AdminLoginHandler;
use Api\Handlers\ArticleNewHandler;
use Api\Handlers\ArticlesHandler;
use Api\Handlers\ArticleViewHandler;
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
            $apiGroup->get('/', function (Request $request, Response $response) 
            {
                self::$handler = new StatusHandler($request, $response);
                return self::$handler->Handle();
            });
            $apiGroup->group('/comments', function (RouteCollectorProxy $commentsGroup)
            {
                $commentsGroup->post('/get', function (Request $request, Response $response) 
                {
                    self::$handler = new ArticleCommentsGetHandler($request, $response);
                    return self::$handler->Handle();
                });

                $commentsGroup->post('/set', function (Request $request, Response $response) 
                {
                    self::$handler = new ArticleCommentCreateHandler($request, $response);
                    return self::$handler->Handle();
                });
            
                $commentsGroup->post('/delete', function (Request $request, Response $response) 
                {
                    self::$handler = new AdminArticleCommentsDeleteHandler($request, $response);
                    return self::$handler->Handle();
                });
            });
            $apiGroup->group('/admin', function (RouteCollectorProxy $adminGroup) 
            {
                $adminGroup->group('/settings', function (RouteCollectorProxy $adminSettingsGroup) 
                {
                    $adminSettingsGroup->post('/get', function (Request $request, Response $response) 
                    {
                        self::$handler = new AdminSettingsGetHandler($request, $response);
                        return self::$handler->Handle();
                    });

                    $adminSettingsGroup->post('/set', function (Request $request, Response $response) 
                    {
                        self::$handler = new AdminSettingsSetHandler($request, $response);
                        return self::$handler->Handle();
                    });
                });
                $adminGroup->group('/article', function (RouteCollectorProxy $adminArticleGroup) 
                {
                    $adminArticleGroup->post('/premoderate/{viewCode}', function (Request $request, Response $response, array $args) 
                    {
                        self::$handler = new AdminArticlePremoderateHandler($request, $response, $args);
                        return self::$handler->Handle();
                    });

                    $adminArticleGroup->group('/approve', function (RouteCollectorProxy $adminApproveArticleGroup) 
                    {
                        $adminApproveArticleGroup->post('/{viewCode}', function (Request $request, Response $response, array $args) 
                        {
                            self::$handler = new AdminArticleApproveHandler($request, $response, $args);
                            return self::$handler->Handle();
                        });

                        $adminApproveArticleGroup->post('/preload/{viewCode}', function (Request $request, Response $response, array $args) 
                        {
                            self::$handler = new AdminArticleApprovePreloadHandler($request, $response, $args);
                            return self::$handler->Handle();
                        });
                    });
                    
                    $adminArticleGroup->post('/delete/{viewCode}', function (Request $request, Response $response, array $args) 
                    {
                        self::$handler = new AdminArticleDeleteHandler($request, $response, $args);
                        return self::$handler->Handle();
                    });
                });
                
                $adminGroup->group('/articles', function (RouteCollectorProxy $adminArticlesGroup) 
                {
                    $adminArticlesGroup->post('/rejectAllApprove', function (Request $request, Response $response) 
                    {
                        self::$handler = new AdminRejectAllApproveHandler($request, $response);
                        return self::$handler->Handle();
                    });

                    $adminArticlesGroup->post('/rejectAllPremoderate', function (Request $request, Response $response) 
                    {
                        self::$handler = new AdminRejectAllPremoderateHandler($request, $response);
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

            $apiGroup->get('/csrfToken', function (Request $request, Response $response) 
            {
                self::$handler = new csrfTokenHandler($request, $response);
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
    
            $apiGroup->group('/article', function (RouteCollectorProxy $articleGroup) 
            {
                $articleGroup->post('/new', function (Request $request, Response $response) 
                {
                    self::$handler = new ArticleNewHandler($request, $response);
                    return self::$handler->Handle();
                });

                $articleGroup->group('/edit', function (RouteCollectorProxy $articleEditGroup) 
                {
                    $articleEditGroup->post('/requestApprove/{editCode}', function (Request $request, Response $response, array $args) 
                    {
                        self::$handler = new ArticleApproveRequestHandler($request, $response, $args);
                        return self::$handler->Handle();
                    });
    
                    $articleEditGroup->post('/{editCode}', function (Request $request, Response $response, array $args) 
                    {
                        self::$handler = new ArticleEditHandler($request, $response, $args);
                        return self::$handler->Handle();
                    });
        
                    $articleEditGroup->post('/preload/{editCode}', function (Request $request, Response $response, array $args) 
                    {
                        self::$handler = new ArticleEditPreloadHandler($request, $response, $args);
                        return self::$handler->Handle();
                    });
                });
                
                $articleGroup->post('/view/{viewCode}', function (Request $request, Response $response, array $args) 
                {
                    self::$handler = new ArticleViewHandler($request, $response, $args);
                    return self::$handler->Handle();
                });
        
                $articleGroup->get('/approveWithChanges/{editCode}', function (Request $request, Response $response, array $args) 
                {
                    self::$handler = new ArticleApproveWithChangesHandler($request, $response, $args);
                    return self::$handler->Handle();
                });
            });
            
    
            $apiGroup->get('/articles', function (Request $request, Response $response) 
            {
                self::$handler = new ArticlesHandler($request, $response);
                return self::$handler->Handle();
            });
            
            $apiGroup->group('/media', function (RouteCollectorProxy $mediaGroup) 
            {
                $mediaGroup->get('/img/{file}', function (Request $request, Response $response, array $args) 
                {
                    self::$handler = new MediaLoadImageHandler($request, $response, $args);
                    return self::$handler->Handle();
                });
                $mediaGroup->post('/img/upload', function (Request $request, Response $response) 
                {
                    self::$handler = new MediaUploadImageHandler($request, $response);
                    return self::$handler->Handle();
                });
            });
            
            $apiGroup->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function (Request $request, Response $response) 
            {
                return self::$handler->Handle();
            });
        });
    }
}