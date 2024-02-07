<?php 

namespace backend\core;

abstract class Controller 
{
  public $params;
  public $view;
  public $config;
  public $app;

  public function __construct($params,$config) 
  {
    $this->params = $params;
    $this->app = $params['app'];
    $this->config = $config;
    if (!$this->checkAcl()) 
    {
      View::renderErrors(403,'No access', $this->config['PROJECT_NAME']);
      die;
    }
    $this->view = new View($params, $config);
    $this->model = $this->loadModel($params['controller']);
  
  }

  public function loadModel($name)
  {
    $path = 'backend\models\\'.$this->app.'\\'.ucfirst($name);
    if (class_exists($path)) 
    {
      return new $path;
    }
  }
  
  public function checkAcl() 
  {
		$this->acl = require 'backend/acl/'.$this->app.'/'.$this->params['controller'].'.php';
		if ($this->isAcl('all')) 
    {
			return true;
		}
		elseif ($this->isAcl('admin')) 
    {
      if (isset($_SESSION['authorize']['is_admin'])) 
      {
        if ($_SESSION['authorize']['is_admin'] == 1) 
        {
          return true;
        }
      }
    }
		return false;
	}

	public function isAcl($key) {
		return in_array($this->params['action'], $this->acl[$key]);
	}
}

?>