<?php
namespace App;

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Slim\Routing\RouteCollectorProxy;
use Slim\Factory\AppFactory;
use Slim\Psr7\Stream;

use Core\Settings;
use Core\Warning;
use Core\Error;
use Core\Logger;
use Core\Database;

use Core\CustomExceptionHandler;
use App\Core\Routes as AppRoutes;
use Api\Core\Routes as ApiRoutes;

header('Server: NGINX');
header('x-powered-by: PHP');

require __DIR__ . '/vendor/autoload.php';

Settings::Init();
$logger = Logger::initInstance('main');
$logger->initExceptionHandlers();

Database::Init();

$app = AppFactory::create();

$exceptionHandler = new CustomExceptionHandler($app->getCallableResolver(), $app->getResponseFactory(), $logger);

$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler($exceptionHandler);

ApiRoutes::Init($app);
AppRoutes::Init($app);

$app->run();