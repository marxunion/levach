<?php
namespace Api\Handlers;

use Core\Error;

use Base\BaseHandlerRouteWithArgs;

use Api\Models\ArticleViewModel;

class ArticleViewHandler extends BaseHandlerRouteWithArgs
{
    public function Init()
    {
        if(isset($this->args['viewCode']))
        {
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
            $this->response = $this->response->withJson($this->model->viewArticle($articleId));
        }
        else
        {
            throw new Error(404, "Article not found", "Article not found");
        }
    }
}