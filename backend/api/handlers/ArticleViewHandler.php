<?php
namespace Api\Handlers;

use Core\Error;

use Base\BaseHandlerRouteWithArgs;

use Base\ArticleViewModel;

class ArticleViewHandler extends BaseHandlerRouteWithArgs
{
    public function Init()
    {
        $this->model = new ArticlesModel();
    }
    public function Process()
    {
        $articleId = $this->model->getArticleByViewCode($this->args['viewCode']);

        if($articleId)
        {
            $this->response = $this->model->viewArticle($articleId);
        }
        else
        {
            throw new Warning(404, "Requested article not found", "Article not found");
        }
    }
}