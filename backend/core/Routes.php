<?php
namespace Core;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;
use Slim\App;

use Base\BaseMainController;
use Base\BaseMainControllerRoute;


class Router
{
    private static App $app;
    private static BaseMainController $handler;

    public static function Init(App $app)
    {
        self::$handler = new UnknownMainController();
        self::$app = $app;
    
        self::$app->group('/api', function (RouteCollectorProxy $apiGroup) 
        {
            $apiGroup->get('/', function (Request $request, Response $response) 
            {
                self::$handler = new \Routes\Api\Status\MainController($request, $response);
                return self::$handler->Handle();
            });
            $apiGroup->group('/admin', function (RouteCollectorProxy $adminGroup) 
            {
                $adminGroup->group('/settings', function (RouteCollectorProxy $adminSettingsGroup) 
                {
                    $adminSettingsGroup->post('/get', function (Request $request, Response $response) 
                    {
                        self::$handler = new \Routes\Api\Admin\Settings\Get\MainController($request, $response);
                        return self::$handler->Handle();
                    });

                    $adminSettingsGroup->post('/set', function (Request $request, Response $response) 
                    {
                        self::$handler = new \Routes\Api\Admin\Settings\Set\MainController($request, $response);
                        return self::$handler->Handle();
                    });
                });
                $adminGroup->group('/article', function (RouteCollectorProxy $adminArticleGroup) 
                {
                    $adminArticleGroup->post('/premoderate/{viewCode}', function (Request $request, Response $response, array $args) 
                    {
                        self::$handler = new \Routes\Api\Admin\Article\Premoderate\MainController($request, $response, $args);
                        return self::$handler->Handle();
                    });

                    $adminArticleGroup->group('/comments', function (RouteCollectorProxy $adminArticleCommentsGroup) 
                    {
                        $adminArticleCommentsGroup->post('/get/{viewCode}', function (Request $request, Response $response, array $args) 
                        {
                            self::$handler = new \Routes\Api\Admin\Article\Comments\Get\MainController($request, $response, $args);
                            return self::$handler->Handle();
                        });
                        $adminArticleCommentsGroup->post('/delete/{viewCode}', function (Request $request, Response $response, array $args) 
                        {
                            self::$handler = new \Router\Api\Admin\Article\Comments\Delete\MainController($request, $response, $args);
                            return self::$handler->Handle();
                        });
                    });

                    $adminArticleGroup->group('/comment', function (RouteCollectorProxy $adminArticleCommentGroup) 
                    {
                        $adminArticleCommentGroup->post('/delete/{viewCode}', function (Request $request, Response $response, array $args) 
                        {
                            self::$handler = new \Routes\Api\Admin\Article\Comment\Delete\MainController($request, $response, $args);
                            return self::$handler->Handle();
                        });
                    });

                    $adminArticleGroup->group('/approve', function (RouteCollectorProxy $adminApproveArticleGroup) 
                    {
                        $adminApproveArticleGroup->post('/{viewCode}', function (Request $request, Response $response, array $args) 
                        {
                            self::$handler = new \Routes\Api\Admin\Article\Approve\MainController($request, $response, $args);
                            return self::$handler->Handle();
                        });

                        $adminApproveArticleGroup->post('/preload/{viewCode}', function (Request $request, Response $response, array $args) 
                        {
                            self::$handler = new \Routes\Api\Admin\Article\Approve\Preload\MainController($request, $response, $args);
                            return self::$handler->Handle();
                        });
                    });
                    
                    $adminArticleGroup->post('/delete/{viewCode}', function (Request $request, Response $response, array $args) 
                    {
                        self::$handler = new \Routes\Api\Admin\Article\Delete\MainController($request, $response, $args);
                        return self::$handler->Handle();
                    });
                });
                
                $adminGroup->group('/articles', function (RouteCollectorProxy $adminArticlesGroup) 
                {
                    $adminArticlesGroup->group('/comments', function (RouteCollectorProxy $adminArticlesCommentsGroup) 
                    {
                        $adminArticlesCommentsGroup->post('/get', function (Request $request, Response $response) 
                        {
                            self::$handler = new \Routes\Api\Admin\Articles\Comments\Get\MainController($request, $response);
                            return self::$handler->Handle();
                        });

                        $adminArticlesCommentsGroup->post('/delete', function (Request $request, Response $response) 
                        {
                            self::$handler = new \Routes\Api\Admin\Articles\Comments\Delete\MainController($request, $response);
                            return self::$handler->Handle();
                        });
                    });

                    $adminArticlesGroup->post('/rejectAllApprove', function (Request $request, Response $response) 
                    {
                        self::$handler = new \Routes\Api\Admin\Articles\RejectAllApprove\MainController($request, $response);
                        return self::$handler->Handle();
                    });

                    $adminArticlesGroup->post('/rejectAllPremoderate', function (Request $request, Response $response) 
                    {
                        self::$handler = new \Routes\Api\Admin\Articles\RejectAllPremoderate\MainController($request, $response);
                        return self::$handler->Handle();
                    });

                    $adminArticlesGroup->post('/updatePopularitySort', function (Request $request, Response $response) 
                    {
                        self::$handler = new \Routes\Api\Admin\Articles\UpdatePopularitySort\MainController($request, $response);
                        return self::$handler->Handle();
                    });
                });

                $adminGroup->get('/status', function (Request $request, Response $response) 
                {
                    self::$handler = new \Routes\Api\Admin\Status\MainController($request, $response);
                    return self::$handler->Handle();
                });

                $adminGroup->post('/login', function (Request $request, Response $response) 
                {
                    self::$handler = new \Routes\Api\Admin\Login\MainController($request, $response);
                    return self::$handler->Handle();
                });

                $adminGroup->post('/quit', function (Request $request, Response $response) 
                {
                    self::$handler = new \Routes\Api\Admin\Quit\MainController($request, $response);
                    return self::$handler->Handle();
                });
            });


            $apiGroup->get('/captchaToken', function (Request $request, Response $response) 
            {
                self::$handler = new \Routes\Api\CaptchaToken\MainController($request, $response);
                return self::$handler->Handle();
            });

            $apiGroup->get('/csrfToken', function (Request $request, Response $response) 
            {
                self::$handler = new \Routes\Api\CSRFToken\MainController($request, $response);
                return self::$handler->Handle();
            });

            $apiGroup->get('/sponsoring', function (Request $request, Response $response) 
            {
                self::$handler = new \Routes\Api\Sponsoring\MainController($request, $response);
                return self::$handler->Handle();
            });

            $apiGroup->get('/status', function (Request $request, Response $response) 
            {
                self::$handler = new \Routes\Api\Status\MainController($request, $response);
                return self::$handler->Handle();
            });
    
            $apiGroup->group('/article', function (RouteCollectorProxy $articleGroup) 
            {
                $articleGroup->post('/new', function (Request $request, Response $response) 
                {
                    self::$handler = new \Routes\Api\Article\New\MainController($request, $response);
                    return self::$handler->Handle();
                });

                $articleGroup->group('/comment', function (RouteCollectorProxy $articleCommentGroup)
                {
                    $articleCommentGroup->get('/get/{viewCode}', function (Request $request, Response $response, array $args) 
                    {
                        self::$handler = new \Routes\Api\Article\Comment\Get\MainController($request, $response, $args);
                        return self::$handler->Handle();
                    });
                    $articleCommentGroup->post('/new/{viewCode}', function (Request $request, Response $response, array $args) 
                    {
                        self::$handler = new \Routes\Api\Article\Comment\New\MainController($request, $response, $args);
                        return self::$handler->Handle();
                    });
                    $articleCommentGroup->group('/subcomment', function (RouteCollectorProxy $articleCommentSubcommentGroup)
                    {
                        $articleCommentSubcommentGroup->post('/new/{viewCode}', function (Request $request, Response $response, array $args) 
                        {
                            self::$handler = new \Routes\Api\Article\Comment\Subcomment\New\MainController($request, $response, $args);
                            return self::$handler->Handle();
                        });

                    });
                });

                $articleGroup->group('/comments', function (RouteCollectorProxy $articleCommentsGroup)
                {
                    $articleCommentsGroup->get('/get/{viewCode}/{commentId}', function (Request $request, Response $response, array $args) 
                    {
                        self::$handler = new \Routes\Api\Article\CommentsGetBeforeId\MainController($request, $response, $args);
                        return self::$handler->Handle();
                    });

                    $articleCommentsGroup->get('/get/{viewCode}', function (Request $request, Response $response, array $args) 
                    {
                        self::$handler = new \Routes\Api\Article\Comments\Get\MainController($request, $response, $args);
                        return self::$handler->Handle();
                    });
                
                    $articleCommentsGroup->post('/delete', function (Request $request, Response $response) 
                    {
                        self::$handler = new \Routes\Api\Admin\Article\Comments\Delete\MainController($request, $response);
                        return self::$handler->Handle();
                    });
                });

                $articleGroup->group('/edit', function (RouteCollectorProxy $articleEditGroup) 
                {
                    $articleEditGroup->post('/requestApprove/{editCode}', function (Request $request, Response $response, array $args) 
                    {
                        self::$handler = new \Routes\Api\Article\Approve\Request\MainController($request, $response, $args);
                        return self::$handler->Handle();
                    });
    
                    $articleEditGroup->post('/{editCode}', function (Request $request, Response $response, array $args) 
                    {
                        self::$handler = new \Routes\Api\Article\Edit\MainController($request, $response, $args);
                        return self::$handler->Handle();
                    });
        
                    $articleEditGroup->post('/preload/{editCode}', function (Request $request, Response $response, array $args) 
                    {
                        self::$handler = new \Routes\Api\Article\Edit\Preload\MainController($request, $response, $args);
                        return self::$handler->Handle();
                    });

                    $articleEditGroup->post('/approveWithChanges/{editCode}', function (Request $request, Response $response, array $args) 
                    {
                        self::$handler = new \Routes\Api\Article\Edit\ApproveWithChanges\MainController($request, $response, $args);
                        return self::$handler->Handle();
                    });
                });
                
                $articleGroup->get('/view/{viewCode}', function (Request $request, Response $response, array $args) 
                {
                    self::$handler = new \Routes\Api\Article\View\MainController($request, $response, $args);
                    return self::$handler->Handle();
                });
            });
            
    
            $apiGroup->get('/articles', function (Request $request, Response $response) 
            {
                self::$handler = new \Routes\Api\Articles\MainController($request, $response);
                return self::$handler->Handle();
            });
            
            $apiGroup->group('/media', function (RouteCollectorProxy $mediaGroup) 
            {
                $mediaGroup->get('/img/{file}', function (Request $request, Response $response, array $args) 
                {
                    self::$handler = new \Routes\Api\Media\Img\MainController($request, $response, $args);
                    return self::$handler->Handle();
                });
                $mediaGroup->post('/img/upload', function (Request $request, Response $response) 
                {
                    self::$handler = new \Routes\Api\Media\Img\Upload\MainController($request, $response);
                    return self::$handler->Handle();
                });
            });
            
            $apiGroup->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function (Request $request, Response $response) 
            {
                return self::$handler->Handle();
            });
        });


        if(Settings::getSetting("IS_DEBUG_SERVER"))
        {
            self::$app->get('/', function (Request $request, Response $response) 
            {
                header('Location: http://localhost:8000');
                exit;
            });
        }
        else
        {
            $app->get('/', function (Request $request, Response $response) 
            {
                $file = __DIR__ . '/../../../frontend/dist/index.html';

                if(file_exists($file)) 
                {
                    $response = $response->withHeader('Content-Type', 'text/html');
                    return $response->withFile($file);
                } 
                else 
                {
                    throw new Error(404, "File not found", "File not found");
                }
            });
        }
        self::$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function (Request $request, Response $response) 
        {
            return $response->withRedirect('/#'.$request->getUri()->getPath(), 301);
        });
    }
}