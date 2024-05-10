<?php
namespace Api\Handlers;

use Core\Error;
use Core\Warning;
use Core\Critical;

use Base\BaseHandlerRouteWithArgs;

use Api\Models\ArticleCommentsGetModel;

class ArticleCommentsGetHandler extends BaseHandlerRouteWithArgs
{
    public function Init()
    {
        if(isset($this->args['viewCode']))
        {
            if(empty($this->request->getQueryParams()))
            {
                $this->parsedBody = [];
            }
            else
            {
                $this->parsedBody = $this->request->getQueryParams();
            }
            if(empty($this->parsedBody['count']))
            {
                $this->parsedBody['count'] = 8;
            }
            if(empty($this->parsedBody['lastLoaded']))
            {
                $this->parsedBody['lastLoaded'] = 0;
            }
            if(empty($this->parsedBody['sortType']))
            {
                $this->parsedBody['sortType'] = 'rate';
            }
            $this->model = new ArticleCommentsGetModel();
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
                $this->response = $this->response->withJson($this->model->getCommentsByRate($articleId, $this->parsedBody['count'], $this->parsedBody['lastLoaded']));
            }
            else if($this->parsedBody['sortType'] == 'created_date')
            {
                $this->response = $this->response->withJson($this->model->getCommentsByCreatedDate($articleId, $this->parsedBody['count'], $this->parsedBody['lastLoaded']));
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