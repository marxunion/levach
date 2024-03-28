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
use App\Core\Routes as AppRoutes;
use Api\Core\Routes as ApiRoutes;

header('Server: NGINX');
header('x-powered-by: PHP');

require __DIR__ . '/vendor/autoload.php';

Settings::Init();
Logger::Init();
Database::Init();

Logger::WriteInfo("Database inited");

$app = AppFactory::create();

Logger::WriteInfo("App created");

$app->addErrorMiddleware(Settings::Get("DEBUG_MODE"), Settings::Get("DEBUG_MODE"), Settings::Get("DEBUG_MODE"));

Logger::WriteInfo("Error middleware added");

ApiRoutes::Init($app);
AppRoutes::Init($app);

Logger::WriteInfo("Routes inited");

$app->run();