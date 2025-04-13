<?php
namespace Routes\Api\Article\Comments\Subcomment\Get\BeforeId;

use Core\Error;

use Base\BaseControllerRouteWithArgs;

use Api\Handlers\AdminStatusHandler;

use Api\Models\ArticleCommentsGetBeforeIdModel;

class ArticleCommentsGetBeforeIdHandler extends BaseControllerRouteWithArgs
{
    public function Init()
    {
        if(!empty($this->args['viewCode']))
        {
            if(!empty($this->args['commentId']))
            {
                if(is_numeric($this->args['commentId']))
                {
                    $this->model = new ArticleCommentsGetBeforeIdModel();
                }
                else
                {
                    throw new Error(400, "Invalid article commentId", "Invalid article commentId");
                }
            }
            else
            {
                throw new Error(400, "Invalid article commentId", "Invalid article commentId");
            }
        }
        else
        {
            throw new Error(400, "Invalid article viewCode", "Invalid article viewCode");
        }
    }
    
    public function Process()
    {
        if(strpos($this->args['viewCode'], '#') === 0)
        {
            $articleId = $this->model->getArticleByViewId((int)substr($this->args['viewCode'], 1));
        }
        else
        {
            $articleId = $this->model->getArticleByViewCode($this->args['viewCode']);
        }

        if(isset($articleId))
        {
            $this->response = $this->response->withJson($this->model->getComments($articleId, $this->args['commentId']));
        }
        else
        {
            throw new Error(400, "Invalid article viewCode", "Invalid article viewCode");
        }
    }
}