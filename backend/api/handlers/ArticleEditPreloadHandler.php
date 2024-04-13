<?php
namespace Api\Handlers;

use Core\Error;
use Core\Warning;

use Base\BaseHandlerRouteWithArgs;

use Base\ArticleEditPreloadModel;

class ArticleEditPreloadHandler extends BaseHandlerRouteWithArgs
{
   
    public function Init()
    {
        $this->model = new ArticleNewModel();
        $parsedBody = $this->request->getParsedBody();

        if(is_array($parsedBody))
        {
            $this->data = $parsedBody;
        }
        else
        {
            throw new Error(400, "Please add editCode to edit article", "Empty request content cannot find editCode");
        }
    }

    public function Process()
    {
        $articleId = $this->model->getArticleIdByEditCode($this->args['editCode']);

        if($articleId)
        {
            $this->response = $this->model->viewArticle($articleId);
        }
        else
        {
            throw new Error(404, "Article for edit not found", "Failed edit article with edit code, editCode not found");
        }
    }
}
