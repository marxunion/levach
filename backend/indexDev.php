<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;
use Slim\Factory\AppFactory;
use Slim\Psr7\Stream;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();

$app->addErrorMiddleware(true, true, true);

$app->get('/', function ($request, $response) 
{
    header('Location: http://localhost:8000');
    exit;
});

$app->group('/api', function (RouteCollectorProxy $group) 
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
    $group->get('/hello/{name}', function (Request $request, Response $response, array $args) 
    {
        $name = $args['name'];
        $data = [
            'message' => "Hello, $name",
            'timestamp' => time(),
        ];
        return $response->withJson($data);
    });
    $group->get('/media/img/{file}', function (Request $request, Response $response, $args) 
    {
        $file = $args['file'];
    
        $filePath = __DIR__ . '/../media/img/' . $args['file'];

        if (!file_exists($filePath)) 
        {
            return $response->withStatus(404)->withJson(['error' => 'File not found']);
        }

        $fileType = mime_content_type($filePath);

        $response = $response->withHeader('Content-Type', $fileType);

        $stream = new Stream(fopen($filePath, 'r'));

        $response = $response->withBody($stream);

        return $response;
    });
});

$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function (Request $request, Response $response) 
{
    $data = [
        'apistatus' => "ok"
    ];
    return $response->withJson($data);
});

$app->run();