<?php
namespace Api\Handlers;

use Core\Error;
use Core\Warning;
use Core\Critical;

use Base\BaseHandlerRoute;

use Api\Models\ArticleCommentsGetModel;

class ArticleCommentsSubcommentsGetHandler extends BaseHandlerRoute
{
    public function Init()
    {
        if(isset($this->args['viewCode']))
        {
            if($this->request->getQueryParams())
            {
                $parsedBody = $this->request->getQueryParams();
                if(!isset($this->parsedBody['comment_id']))
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

        }
        else
        {
            throw new Error(403, "Invalid article viewCode", "Invalid article viewCode");
        }
    }
}