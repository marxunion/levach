<?php 
namespace backend\core;

class Router 
{
  public static $params = [];
  public static $apps = [];
  public static $config = [];

  public static function cutGetParamUrl($url)
  {
    return urldecode(preg_replace('/\?.*/iu','',$url));
  }

  public function __construct($apps,$config) 
  {
    Router::$apps = $apps;
    Router::$config = $config;
    $this->init();
  }

  private function urlsConnected() 
  {
    $total_urls = [];
    foreach (Router::$apps as $app) 
    {
      $urls = require 'backend/routes/'.$app.'/urls.php';
      foreach ($urls as $url) 
      {
        array_push($url, $app);
        array_push($total_urls,$url);
      }
    }
    return $total_urls;
  }  

  private function init()
  {
    $total_urls = $this->urlsConnected();
    $urls_and_apps = [];

    if ($this->matchUrl($total_urls)) 
    {
      $controller = 'backend\controllers\\'.Router::$params['app'].'\\'.ucfirst(Router::$params['controller']).'Controller';

      if (class_exists($controller)) 
      {
        $action = Router::$params['action'].'Action';
        
        if (method_exists($controller,$action)) 
        {
          $params = Router::$params;
          
          $controller = new $controller($params, Router::$config);
        
          $controller->$action();
        } 
        else 
        {
          View::renderErrors(404,'Not found action '.Router::$params['action'].', in controller '.Router::$params['controller'], Router::$config['PROJECT_NAME']);
        }
      } 
      else 
      {
        View::renderErrors(404,'Not found controller '.Router::$params['controller'].', maybe you need to create in app '.Router::$params['app'], Router::$config['PROJECT_NAME']);
      }      
    } 
    else 
    {
      View::renderErrors(404,'Not found url', Router::$config['PROJECT_NAME']);
    }
  }

  protected function matchUrl($routes) 
  {
    $url = trim($_SERVER['REQUEST_URI'],'/');

    foreach ($routes as $route) 
    {
      $url = self::cutGetParamUrl($url);
      $route[0] = preg_replace('/{([a-z]+):([^\}]+)}/', '(?P<\1>\2)', $route[0]);
      $route[0] = '#^'.$route[0].'$#';
      
      if (preg_match($route[0] ,$url)) 
      {
        Router::$params = ['controller' => $route[1],'action' => $route[2],'app' => $route[3]];
        return true;
      }
    }
    return false;
  }

}















