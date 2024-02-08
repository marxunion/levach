<?php

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

new ErrorHandler($settings,$config);