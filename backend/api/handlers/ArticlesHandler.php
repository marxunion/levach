<?php
namespace Api\Handlers;

use Core\Error;

use Base\BaseHandlerRoute;

use Base\ArticlesModel;

class ArticlesHandler extends BaseHandlerRoute
{
    public function Init()
    {
        $this->model = new ArticlesModel();
    }
    public function Process()
    {
        
    }
}