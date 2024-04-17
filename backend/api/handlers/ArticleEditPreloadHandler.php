<?php
namespace Api\Handlers;

use Core\Error;
use Core\Warning;

use Base\BaseHandlerRouteWithArgs;

use Api\Models\ArticleEditPreloadModel;

class ArticleEditPreloadHandler extends BaseHandlerRouteWithArgs
{
   
    public function Init()
    {
        $this->model = new ArticleEditPreloadModel();
    }

    public function Process()
    {
        $articleId = $this->model->getArticleIdByEditCode($this->args['editCode']);

        if($articleId)
        {
            $this->response = $this->response->withJson($this->model->viewArticle($articleId));
        }
        else
        {
            throw new Error(404, "Article for edit not found", "Failed edit article with edit code, editCode not found");
        }
    }
}
