<?php
namespace App;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;
use Slim\Factory\AppFactory;
use Slim\Psr7\Stream;
use Monolog\Handler\StreamHandler;
use Monolog\Level;

use Helpers\StringFormatter;

use Core\Settings;
use Core\Logger;
use Core\SystemInfo;

require __DIR__ . '/../vendor/autoload.php';

Settings::Init();
$logger = Logger::initInstance('main');
$logger->pushHandler(new StreamHandler(__DIR__.'/../logs/main.log', Level::Debug));

$memUsage = SystemInfo::getServerMemoryUsage(false);
$cpuLoad = SystemInfo::getServerLoad();
$diskInfo = SystemInfo::getDiskInfo();

$logger->info("Disk usage: ".StringFormatter::formatSizeEnding(floatval($diskInfo['used']))." / ".StringFormatter::formatSizeEnding(floatval($diskInfo['size']))." (".round(($diskInfo['used']/$diskInfo['size'])*100, 1)."%)");
$logger->info("Memory usage: ".StringFormatter::formatSizeEnding($memUsage["total"] - $memUsage["free"])." / ".StringFormatter::formatSizeEnding($memUsage["total"])." (".round(SystemInfo::getServerMemoryUsage(true), 1)."%)");
$logger->info("CPU Load: ".round($cpuLoad, 1)."%");