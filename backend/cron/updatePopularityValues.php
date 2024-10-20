<?php
namespace App;

use Monolog\Handler\StreamHandler;
use Monolog\Level;

use Core\Settings;
use Core\Logger;
use Core\Database;

use Api\Handlers\AdminArticlesUpdatePopularitySortHandler;

require __DIR__ . '/../vendor/autoload.php';

Settings::Init();
$logger = Logger::initInstance('main');
$logger->initExceptionHandlers();

Database::Init();

AdminArticlesUpdatePopularitySortHandler::_updatePopularityValues();