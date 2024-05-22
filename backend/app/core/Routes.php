<?php
namespace App\Core;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Core\Settings;

use Core\Error;

class Routes
{
    private static App $app;

    public static function Init(App $app)
    {
        self::$app = $app;
        
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
            self::$app->get('/assets/{file:.*}', function (Request $request, Response $response, array $args) 
            {
                $file = __DIR__ . '/../../../frontend/dist/' . $args['file'];

                if(file_exists($file)) 
                {
                    return $response->withFile($file);
                } 
                else
                {
                    throw new Error(404, "File not found", "File not found");
                }
            });

            $app->get('/', function (Request $request, Response $response) 
            {
                $file = __DIR__ . '/../../../frontend/dist/index.html';

                if(file_exists($file)) 
                {
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