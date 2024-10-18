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
$logger->pushHandler(new StreamHandler(__DIR__.'/../logs/main.log', Level::Debug));
$logger->pushHandler(new StreamHandler(__DIR__.'/../logs/warnings.log', Level::Warning));
$logger->pushHandler(new StreamHandler(__DIR__.'/../logs/errors.log', Level::Error));
Database::Init();

AdminArticlesUpdatePopularitySortHandler::_updatePopularityValues();