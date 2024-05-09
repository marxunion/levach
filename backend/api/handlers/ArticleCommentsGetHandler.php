<?php
namespace Api\Handlers;

use Core\Error;
use Core\Warning;
use Core\Critical;

use Base\BaseHandlerRoute;

use Api\Models\ArticleCommentsGetModel;

class ArticleCommentsGetHandler extends BaseHandlerRoute
{
    public function Init()
    {
        if(isset($this->args['viewCode']))
        {
            if($this->request->getQueryParams())
            {
                $parsedBody = $this->request->getQueryParams();
                if(isset($this->parsedBody['count']))
                {
                    if(isset($this->parsedBody['lastLoaded']))
                    {
                        if(!isset($this->parsedBody['sortType']))
                        {
                            $this->parsedBody['sortType'] = 'rate';
                        }
                        $this->model = new ArticleCommentsGetModel();
                    }
                    else
                    {
                        throw new Error(400, "Invalid comments lastLoaded", "Invalid comments lastLoaded");
                    }
                }
                else
                {
                    throw new Error(400, "Invalid comments count", "Invalid comments count");
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
            if($this->parsedBody['sortType'] == 'rate')
            {
                $this->model->getCommentsByRate($articleId, $this->parsedBody['count'], $this->parsedBody['lastLoaded']);
            }
            else if($this->parsedBody['sortType'] == 'created_date')
            {
                $this->model->getCommentsByCreatedDate($articleId, $this->parsedBody['count'], $this->parsedBody['lastLoaded']);
            }
            else
            {
                throw new Error(400, "Invalid sortType", "Invalid sortType");
            }
        }
        else
        {
            throw new Error(400, "Invalid article viewCode", "Invalid article viewCode");
        }
    }
}