<?php
namespace Api\Handlers;

use Core\Error;
use Core\Warning;

use Base\BaseHandlerRoute;

use Base\ArticleSearchModel;

class ArticleSearchHandler extends BaseHandlerRoute
{
    public function Init()
    {
        $this->model = new ArticleSearchModel();
    }

    public function Process()
    {
        
    }
}