<?php
namespace App;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;
use Slim\Factory\AppFactory;
use Slim\Psr7\Stream;

use Core\Settings;
use Core\Warning;
use Core\Error;
use Core\ErrorCritical;
use Core\Logger;
use Core\Database;

use Core\CustomExceptionHandler;
use App\Core\Routes as AppRoutes;
use Api\Core\Routes as ApiRoutes;

header('Server: NGINX');
header('x-powered-by: PHP');

require __DIR__ . '/vendor/autoload.php';

Settings::Init();
Logger::Init();
Database::Init();

$app = AppFactory::create();

$logger = new Logger('main');
$exceptionHandler = new CustomExceptionHandler($app->getCallableResolver(), $app->getResponseFactory(), $logger);

$errorMiddleware = $app->addErrorMiddleware(Settings::Get("DEBUG_MODE"), Settings::Get("DEBUG_MODE"), Settings::Get("DEBUG_MODE"));
$errorMiddleware->setDefaultErrorHandler($exceptionHandler);

ApiRoutes::Init($app);
AppRoutes::Init($app);

$app->run();