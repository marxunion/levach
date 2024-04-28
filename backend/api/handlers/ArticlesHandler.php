<?php
namespace Api\Handlers;

use Core\Error;

use Base\BaseHandlerRoute;

use Api\Models\ArticlesModel;

class ArticlesHandler extends BaseHandlerRoute
{
    public function Init()
    {
        $this->model = new ArticlesModel();
        $parsedBody = $this->request->getQueryParams();
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
        if(isset($this->data['count']))
        {
            $count = $this->data['count'];
            if($count > 8)
            {
                $count = 8;
            }
        }

        $category = 'editoriallyArticles';
        if(isset($this->data['category']))
        {
        }

        $lastLoadedArticleId = 2147483645;
        if(isset($this->data['lastLoadedArticleId']))
        {
            $lastLoadedArticleId = $this->data['lastLoadedArticleId'];
        }

        if(isset($this->data['sortType']))
        {
            $sortType = $this->data['sortType'];
            $articleIds = null;
            if($sortType == 'timestamp')
            {
                $lastLoadedArticleTimestamp = 2147483645;
                if(isset($this->data['lastLoadedArticleTimestamp']))
                {
                    $lastLoadedArticleTimestamp = $this->data['lastLoadedArticleTimestamp'];
                }
                $articleIds = $this->model->loadArticlesIdsByTimestamp($count, $lastLoadedArticleId, $lastLoadedArticleTimestamp);
            }
            else if($sortType == 'rate')
            {
                $lastLoadedArticleRate = 2147483645;
                if(isset($this->data['lastLoadedArticleRate']))
                {
                    $lastLoadedArticleRate = $this->data['lastLoadedArticleRate'];
                }
                $articleIds = $this->model->loadArticlesIdsByRate($count, $lastLoadedArticleId, $lastLoadedArticleRate);
            }
            else
            {
                throw new Error(400, "Invalid sortType", "Invalid sortType");
                return;
            }
            
            $this->response = $this->response->withJson($this->model->loadArticles($articleIds, $category));
        }
        else
        {
            throw new Error(400, "Please select sortType", "Please select sortType");
        }
    }
}