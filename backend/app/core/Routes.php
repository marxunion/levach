<?php
namespace App\Core;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;
use Slim\Factory\AppFactory;
use Slim\Psr7\Stream;
use Core\Settings;
use Slim\App;

class Routes
{
    private static App $app;

    public static function Init(App $app)
    {
        self::$app = $app;
        
        if(Settings::Get("IS_DEBUG_SERVER"))
        {
            self::$app->get('/', function (Request $request,Response $response) 
            {
                header('Location: http://localhost:8000');
                exit;
            });
        }
        else
        {
            self::$app->get('/frontend/assets/{file:.*}', function (Request $request, Response $response, array $args) 
            {
                $file = __DIR__ . '/../frontend/dist/' . $args['file'];

                if (file_exists($file)) 
                {
                    return $response->withFile($file);
                } 
                else
                {
                    return $response->withStatus(404)->withJson(['error' => 'File not found']);
                }
            });

            $app->get('/', function (Request $request, Response $response) 
            {
                $file = __DIR__ . '/../frontend/index.html';
                
                if (file_exists($file)) 
                {
                    return $response->withFile($file);
                } 
                else 
                {
                    return $response->withStatus(404)->withJson(['error' => 'File not found']);
                }
            });
        }
    }
}