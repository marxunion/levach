<?php
namespace Api\Core;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;
use Slim\App;

use Base\BaseHandler;
use Base\BaseHandlerRoute;

use Api\Handlers\ArticleApproveWithChangesHandler;

use Api\Handlers\AdminRejectAllApproveHandler;
use Api\Handlers\AdminRejectAllPremoderateHandler;

use Api\Handlers\AdminArticlePremoderateHandler;

use Api\Handlers\ArticleApproveRequestHandler;

use Api\Handlers\AdminArticleApproveHandler;
use Api\Handlers\AdminArticleDeleteHandler;
use Api\Handlers\AdminArticleApprovePreloadHandler;

use Api\Handlers\AdminArticleCommentsGetHandler;
use Api\Handlers\ArticleCommentsGetBeforeIdHandler;
use Api\Handlers\AdminArticleCommentsDeleteHandler;
use Api\Handlers\AdminArticlesCommentsGetHandler;
use Api\Handlers\AdminArticlesCommentsDeleteHandler;

use Api\Handlers\AdminArticleCommentDeleteHandler;
use Api\Handlers\ArticleCommentsGetHandler;
use Api\Handlers\ArticleCommentNewHandler;
use Api\Handlers\ArticleCommentGetHandler;
use Api\Handlers\ArticleCommentSubcommentNewHandler;

use Api\Handlers\CSRFTokenHandler;

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

                    $adminArticleGroup->group('/comments', function (RouteCollectorProxy $adminArticleCommentsGroup) 
                    {
                        $adminArticleCommentsGroup->post('/get/{viewCode}', function (Request $request, Response $response, array $args) 
                        {
                            self::$handler = new AdminArticleCommentsGetHandler($request, $response, $args);
                            return self::$handler->Handle();
                        });

                        $adminArticleCommentsGroup->post('/get/{viewCode}/{commentId}', function (Request $request, Response $response, array $args) 
                        {
                            self::$handler = new ArticleCommentsGetBeforeIdHandler($request, $response, $args);
                            return self::$handler->Handle();
                        });

                        $adminArticleCommentsGroup->post('/delete/{viewCode}', function (Request $request, Response $response, array $args) 
                        {
                            self::$handler = new AdminArticleCommentsDeleteHandler($request, $response, $args);
                            return self::$handler->Handle();
                        });
                    });

                    $adminArticleGroup->group('/comment', function (RouteCollectorProxy $adminArticleCommentGroup) 
                    {
                        $adminArticleCommentGroup->post('/delete/{viewCode}', function (Request $request, Response $response, array $args) 
                        {
                            self::$handler = new AdminArticleCommentDeleteHandler($request, $response, $args);
                            return self::$handler->Handle();
                        });
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
                    $adminArticlesGroup->group('/comments', function (RouteCollectorProxy $adminArticlesCommentsGroup) 
                    {
                        $adminArticlesCommentsGroup->post('/get', function (Request $request, Response $response) 
                        {
                            self::$handler = new AdminArticlesCommentsGetHandler($request, $response);
                            return self::$handler->Handle();
                        });

                        $adminArticlesCommentsGroup->post('/delete', function (Request $request, Response $response) 
                        {
                            self::$handler = new AdminArticlesCommentsDeleteHandler($request, $response);
                            return self::$handler->Handle();
                        });
                    });

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


            $apiGroup->get('/captchaToken', function (Request $request, Response $response) 
            {
                self::$handler = new CaptchaTokenHandler($request, $response);
                return self::$handler->Handle();
            });

            $apiGroup->get('/csrfToken', function (Request $request, Response $response) 
            {
                self::$handler = new CSRFTokenHandler($request, $response);
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

                $articleGroup->group('/comment', function (RouteCollectorProxy $articleCommentGroup)
                {
                    $articleCommentGroup->get('/get/{viewCode}', function (Request $request, Response $response, array $args) 
                    {
                        self::$handler = new ArticleCommentGetHandler($request, $response, $args);
                        return self::$handler->Handle();
                    });
                    $articleCommentGroup->post('/new/{viewCode}', function (Request $request, Response $response, array $args) 
                    {
                        self::$handler = new ArticleCommentNewHandler($request, $response, $args);
                        return self::$handler->Handle();
                    });
                    $articleCommentGroup->group('/subcomment', function (RouteCollectorProxy $articleCommentSubcommentGroup)
                    {
                        $articleCommentSubcommentGroup->post('/new/{viewCode}', function (Request $request, Response $response, array $args) 
                        {
                            self::$handler = new ArticleCommentSubcommentNewHandler($request, $response, $args);
                            return self::$handler->Handle();
                        });

                    });
                });

                $articleGroup->group('/comments', function (RouteCollectorProxy $articleCommentsGroup)
                {
                    $articleCommentsGroup->get('/get/{viewCode}', function (Request $request, Response $response, array $args) 
                    {
                        self::$handler = new ArticleCommentsGetHandler($request, $response, $args);
                        return self::$handler->Handle();
                    });
                    
                
                    $articleCommentsGroup->post('/delete', function (Request $request, Response $response) 
                    {
                        self::$handler = new AdminArticleCommentsDeleteHandler($request, $response);
                        return self::$handler->Handle();
                    });
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

                    $articleEditGroup->post('/approveWithChanges/{editCode}', function (Request $request, Response $response, array $args) 
                    {
                        self::$handler = new ArticleApproveWithChangesHandler($request, $response, $args);
                        return self::$handler->Handle();
                    });
                });
                
                $articleGroup->get('/view/{viewCode}', function (Request $request, Response $response, array $args) 
                {
                    self::$handler = new ArticleViewHandler($request, $response, $args);
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