<?php
namespace Api\Handlers;

use Core\Error;

use Base\BaseHandlerRoute;

use Base\ArticlesModel;

class ArticlesHandler extends BaseHandlerRoute
{
    public function Init()
    {
        $this->model = new ArticlesModel();
        $parsedBody = $this->request->getParsedBody();
        if(is_array($parsedBody))
        {
            $this->data = $parsedBody;
        }
        else
        {
            throw new Error(400, "Please select sortType", "Please select sortType");
        }
    }
    public function Process()
    {
        $count = 4;
        if($this->data['count'])
        {
            $count = $this->data['count'];
        }
        $lastLoadedArticleId = 0;
        if($this->data['count'])
        {
            $lastLoadedArticleId = $this->data['lastLoadedArticleId'];
        }

        if($this->data['sortType'])
        {
            $sortType = $this->data['sortType'];
            if($sortType == 'time')
            {
                $lastLoadedArticleTime = 0;
                if($this->data['lastLoadedArticleTime'])
                {
                    $lastLoadedArticleTime = $this->data['lastLoadedArticleTime'];
                }
                $this->response = $this->response->withJson($this->model->loadArticlesByTime($count, $lastLoadedArticleId, $lastLoadedArticleTime));
            }
            else if($sortType == 'rate')
            {
                $lastLoadedArticleRate = 0;
                if($this->data['lastLoadedArticleRate'])
                {
                    $lastLoadedArticleRate = $this->data['lastLoadedArticleRate'];
                }
                $this->response = $this->response->withJson($this->model->loadArticlesByRate($count, $lastLoadedArticleId, $lastLoadedArticleRate));
            }
            else
            {
                throw new Error(400, "Invalid sortType", "Invalid sortType");
            }
        }
        else
        {
            throw new Error(400, "Please select sortType", "Please select sortType");
        }
    }
}