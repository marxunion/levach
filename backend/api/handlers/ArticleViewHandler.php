<?php
namespace Api\Handlers;

use Core\Error;

use Base\BaseHandlerRouteWithArgs;

use Api\Models\ArticleViewModel;

use Api\Handlers\AdminStatusHandler;

class ArticleViewHandler extends BaseHandlerRouteWithArgs
{
    public function Init()
    {
        if(!empty($this->args['viewCode']))
        {
            $this->cookiesBody = $this->request->getCookieParams();
            
            $this->model = new ArticleViewModel();
        }
        else
        {
            throw new Error(404, "Article not found", "Article not found");
        }
        
    }
    public function Process()
    {
        $articleId = $this->model->getArticleByViewCode($this->args['viewCode']);

        if($articleId)
        {
            if(AdminStatusHandler::isAdmin($this->cookiesBody))
            {
                $this->response = $this->response->withJson($this->model->viewArticleAdmin($articleId));
            }
            else
            {
                $this->response = $this->response->withJson($this->model->viewArticle($articleId));
            }
        }
        else
        {
            throw new Error(404, "Article not found", "Article not found");
        }
    }
}