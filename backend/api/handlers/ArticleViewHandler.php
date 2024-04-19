<?php
namespace Api\Handlers;

use Core\Warning;

use Base\BaseHandlerRouteWithArgs;

use Api\Models\ArticleViewModel;

class ArticleViewHandler extends BaseHandlerRouteWithArgs
{
    public function Init()
    {
        $this->model = new ArticleViewModel();
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
            throw new Warning(404, "Requested article not found", "Article not found");
        }
    }
}