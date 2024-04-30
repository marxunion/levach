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
            $this->parsedBody = $parsedBody;
        }
        else
        {
            throw new Error(400, "Please select sortType", "Please select sortType");
        }
    }
    public function Process()
    {
        $count = 4;
        if(isset($this->parsedBody['count']))
        {
            $count = $this->parsedBody['count'];
            if($count > 8)
            {
                $count = 8;
            }
        }

        $category = 'editoriallyArticles';
        if(isset($this->parsedBody['category']))
        {
            $category = $this->parsedBody['category'];
        }

        $lastLoadedArticleId = 2147483645;
        if(isset($this->parsedBody['lastLoadedArticleId']))
        {
            $lastLoadedArticleId = $this->parsedBody['lastLoadedArticleId'];
        }

        if(isset($this->parsedBody['sortType']))
        {
            $sortType = $this->parsedBody['sortType'];
            $articleIds = null;
            if($sortType == 'timestamp')
            {
                $lastLoadedArticleTimestamp = 2147483645;
                if(isset($this->parsedBody['lastLoadedArticleTimestamp']))
                {
                    $lastLoadedArticleTimestamp = $this->parsedBody['lastLoadedArticleTimestamp'];
                }
                $articleIds = $this->model->loadArticlesIdsByTimestamp($count, $lastLoadedArticleId, $lastLoadedArticleTimestamp);
            }
            else if($sortType == 'rate')
            {
                $lastLoadedArticleRate = 2147483645;
                if(isset($this->parsedBody['lastLoadedArticleRate']))
                {
                    $lastLoadedArticleRate = $this->parsedBody['lastLoadedArticleRate'];
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