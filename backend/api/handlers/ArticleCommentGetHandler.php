<?php
namespace Api\Handlers;

use Core\Error;

use Base\BaseHandlerRouteWithArgs;

use Api\Models\ArticleCommentGetModel;

class ArticleCommentGetHandler extends BaseHandlerRouteWithArgs
{
    public function Init()
    {
        if(!empty($this->args['viewCode']))
        {
            if($this->request->getQueryParams())
            {
                $this->parsedBody = $this->request->getQueryParams();
                if(isset($this->parsedBody['commentId']))
                {
                    if(is_numeric($this->parsedBody['commentId']))
                    {
                        if($this->parsedBody['commentId'] > 2147483646)
                        {
                            $this->parsedBody['commentId'] = 2147483647;
                        }
                        $this->model = new ArticleCommentGetModel();
                    }
                    else
                    {
                        throw new Error(400, "Invalid comment id", "Invalid comment id");
                    }
                }
                else
                {
                    throw new Error(400, "Invalid comment id", "Invalid comment id");
                }
            }
            else
            {
                throw new Error(400, "Invalid query params", "Invalid query params");
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
            $this->response = $this->response->withJson($this->model->getComment($articleId, $this->parsedBody['commentId']));
        }
        else
        {
            throw new Error(403, "Invalid article viewCode", "Invalid article viewCode");
        }
    }
}