<?php 

namespace backend\controllers\en;
use backend\core\Controller;


class MainController extends Controller 
{
  public function indexAction()
  {
    $title = $this->config['PROJECT_NAME'];
    $this->view->render($title);
  }
  public function rulesAction()
  {
    $title = $this->config['PROJECT_NAME'];
    $this->view->render($title);
  }
  public function helpAction()
  {
    $title = $this->config['PROJECT_NAME'];
    $this->view->render($title);
  }
  public function aboutUsAction()
  {
    $title = $this->config['PROJECT_NAME'];
    $this->view->render($title);
  }
}
