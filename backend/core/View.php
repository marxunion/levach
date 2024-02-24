<?php 

namespace backend\core;

use backend\lib\Settings;
use backend\lib\UserAgent;

class View {
  
  public $path;
  

  public function __construct($params,$config) 
  {
    $this->params = $params; 
    $this->config = $config;
    $this->path = 'templates/'.$params['app'].'/'.$params['controller'].'/'.$params['action'];    
  }

  public function render($title , $vars = [], $layout = 'default')
  {
    extract($vars);
    $user_agent = UserAgent::parseUserAgent();
    if ($user_agent['name'] == "Internet Explorer") 
    {
      $style = '/frontend/css/old_theme.min.css';
      ob_start();
      require "backend/views/templates/".$params['app'].'/main/old_browser.php';
      $view = ob_get_clean();

      require 'backend/views/layouts/old_browsers.php';
      exit;
    }
    if ($settings['PROJECT_UPDATED']) 
    {
      $style = '/frontend/css/update_theme.min.css';
      ob_start();
      require "backend/views/templates/".$params['app'].'/main/update.php';
      $view = ob_get_clean();
      
      require 'backend/views/layouts/default.php';
      exit;
    }
    if (file_exists('backend/views/'.$this->path.'.php')) 
    {
      ob_start();
      require 'backend/views/'.$this->path.'.php';
      $view = ob_get_clean();
      $style_general = '/frontend/css/'.$this->params['app'].'/'.$this->params['controller'].'/'.'gl_'.$this->params['controller'].'_style.min.css';
      $style = '/frontend/css/'.$this->params['app'].'/'.$this->params['controller'].'/'.$this->params['action'].'.min.css';
      $js = '/frontend/js/'.$this->params['app'].'/'.$this->params['controller'].'/'.$this->params['action'].'.min.js';
      require 'backend/views/layouts/'.$layout.'.php';
    } 
    else 
    {
      View::renderErrors(404,'Not found view '.$this->params['action'].', maybe you need to create in '.'backend/views/'.$this->path.'.php', $this->config['PROJECT_NAME']);
    }
  }


  public function redirect($url)
  {
    header("location: $url");
    exit;
  }

  public static function renderErrors($status_error, $messageDev, $title)
  {
    $page = 'prod';
    $style = '/frontend/css/prod_errors.css';
    if (Settings::Loader('DEBUG_MODE')) 
    {
      $message = $messageDev;
      $page = 'dev';
      $style = '/frontend/css/dev_errors.css';
    }
    http_response_code($status_error);
    ob_start();
    
    if(Router::$params['app'] == '')
    {
      require "backend/views/templates/ru/errors/engineerrors$page.php";
    }
    else
    {
      require 'backend/views/templates/'.Router::$params['app']."/errors/engineerrors$page.php";
    }
    
    $view = ob_get_clean();
    require 'backend/views/layouts/error.php';
  }

  public function message($status, $message) 
  {
		exit(json_encode(['status' => $status, 'message' => $message]));
  }
  public function jsonDecode($array)
  {
    exit(json_encode($array));
  }
  public static function messageStatic ($status, $message) 
  {
    exit(json_encode(['status' => $status, 'message' => $message]));
  }
	public function location($url) 
  {
		exit(json_encode(['url' => $url]));
	}
}
?>