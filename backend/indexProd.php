<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();

$app->addErrorMiddleware(true, true, true);

// Маршрут для обслуживания статических файлов из frontend/dist
$app->get('/frontend/assets/{file:.*}', function ($request, $response, $args) {
    $file = __DIR__ . '/../frontend/dist/' . $args['file'];

    if (file_exists($file)) {
        return $response->withFile($file);
    } else {
        return $response->withStatus(404)->write('File not found');
    }
});

// Маршрут для отображения дефолтной страницы
$app->get('/', function ($request, $response) {
    $file = __DIR__ . '/../frontend/index.html';
    
    if (file_exists($file)) {
        return $response->withFile($file);
    } else {
        return $response->withStatus(404)->write($file);
    }
});

// Маршрут для обработки API запросов, например, из папки backend
$app->group('/api', function (RouteCollectorProxy $group) {
    // Добавьте здесь ваши маршруты для бэкенда
});

$app->run();


/*require 'vendor/autoload.php';

require 'backend/core/ErrorHandler.php';

require 'backend/settings/settings.php';
require 'backend/settings/configs/config.php';

use backend\core\Router;
use backend\core\ErrorHandler;

if ($settings['DEBUG_MODE'] and $settings['DEV_TOLLS']) {
  require 'backend/core/FastDebug.php';
}
$apps = require 'backend/apps/apps.php';

spl_autoload_register(function($class)
{
  $path = str_replace('\\','/',$class.'.php');
  if (file_exists($path)) 
  {
    require $path;
  }
});
session_start();


if (empty($_SESSION['authorize']['is_admin'])) 
{
  $_SESSION['authorize']['is_admin'] = 0;
}

$router = new Router($apps, $config);

new ErrorHandler($settings,$config);*/