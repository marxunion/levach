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
        $parsedBody = $this->request->getParsedBody();
        if(is_array($parsedBody))
        {
            $this->data = $parsedBody;
        }
        else
        {
            throw new Warning(400, "Please add a title for the article", "Empty article title");
        }
    }
    public function Process()
    {
        
    }
}