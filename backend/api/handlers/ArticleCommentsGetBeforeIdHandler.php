<?php
namespace Api\Handlers;

use Core\Error;

use Base\BaseHandlerRouteWithArgs;

use Api\Handlers\AdminStatusHandler;

use Api\Models\ArticleCommentsGetBeforeIdModel;

class ArticleCommentsGetBeforeIdHandler extends BaseHandlerRouteWithArgs
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
        $articleId = $this->model->getArticleByViewCode($this->args['viewCode']);
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