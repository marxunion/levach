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
        if(array_key_exists('count', $this->data))
        {
            $count = $this->data['count'];
        }
        $lastLoadedArticleId = 0;
        if(array_key_exists('lastLoadedArticleId', $this->data))
        {
            $lastLoadedArticleId = $this->data['lastLoadedArticleId'];
        }

        if(array_key_exists('sortType', $this->data))
        {
            $sortType = $this->data['sortType'];
            $articleIds = null;
            if($sortType == 'timestamp')
            {
                $lastLoadedArticleTimestamp = 2147483645;
                if(array_key_exists('lastLoadedArticleTimestamp', $this->data))
                {
                    $lastLoadedArticleTimestamp = $this->data['lastLoadedArticleTimestamp'];
                }
                $articleIds = $this->model->loadArticlesIdsByTimestamp($count, $lastLoadedArticleId, $lastLoadedArticleTimestamp);
            }
            else if($sortType == 'rate')
            {
                $lastLoadedArticleRate = 2147483645;
                if(array_key_exists('lastLoadedArticleRate', $this->data))
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
            
            $this->response = $this->response->withJson($this->model->loadArticles($articleIds));
        }
        else
        {
            throw new Error(400, "Please select sortType", "Please select sortType");
        }
    }
}