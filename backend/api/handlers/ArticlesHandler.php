<?php
namespace Api\Handlers;

use Core\Error;

use Base\BaseHandlerRoute;

use Api\Models\ArticlesModel;
use Api\Handlers\AdminStatusHandler;

class ArticlesHandler extends BaseHandlerRoute
{
    public function Init()
    {
        $this->model = new ArticlesModel();
        $parsedBody = $this->request->getQueryParams();
        if(is_array($parsedBody))
        {
            $this->parsedBody = $parsedBody;
            $this->cookiesBody = $this->request->getCookieParams();
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
    
                if(AdminStatusHandler::isAdmin($this->cookiesBody))
                {
                    if($cateroty == 'editoriallyArticles')
                    {
                        $articleIds = $this->model->loadEditoriallyArticlesIdsByTimestamp($count, $lastLoadedArticleId, $lastLoadedArticleTimestamp);
                        $this->response = $this->response->withJson($this->model->loadEditoriallyArticles($articleIds));
                    }
                    else if($cateroty == 'editoriallyApprovedArticles')
                    {
                        $articleIds = $this->model->loadEditoriallyApprovedArticlesIdsByTimestamp($count, $lastLoadedArticleId, $lastLoadedArticleTimestamp);
                        $this->response = $this->response->withJson($this->model->loadEditoriallyApprovedArticles($articleIds));
                    }
                    else if($cateroty == 'abyssArticles')
                    {
                        $articleIds = $this->model->loadAbyssArticlesIdsByTimestamp($count, $lastLoadedArticleId, $lastLoadedArticleTimestamp);
                        $this->response = $this->response->withJson($this->model->loadAbyssArticles($articleIds));
                    }
                    else if($cateroty == 'articlesWaitingApproval')
                    {
                        $articleIds = $this->model->loadArticlesWaitingApprovalIdsByTimestamp($count, $lastLoadedArticleId, $lastLoadedArticleTimestamp);
                        $this->response = $this->response->withJson($this->model->loadArticlesWaitingApproval($articleIds));
                    }
                    else if($cateroty == 'articlesWaitingPremoderate')
                    {
                        $articleIds = $this->model->loadArticlesWaitingPremoderateIdsByTimestamp($count, $lastLoadedArticleId, $lastLoadedArticleTimestamp);
                        $this->response = $this->response->withJson($this->model->loadArticlesWaitingPremoderate($articleIds));
                    }
                    else
                    {
                        throw new Error(400, "Invalid category", "Invalid category");
                    }
                }
                else
                {
                    if($cateroty == 'editoriallyArticles')
                    {
                        $articleIds = $this->model->loadEditoriallyArticlesIdsByTimestamp($count, $lastLoadedArticleId, $lastLoadedArticleTimestamp);
                        $this->response = $this->response->withJson($this->model->loadEditoriallyArticles($articleIds));
                    }
                    else if($cateroty == 'editoriallyApprovedArticles')
                    {
                        $articleIds = $this->model->loadEditoriallyApprovedArticlesIdsByTimestamp($count, $lastLoadedArticleId, $lastLoadedArticleTimestamp);
                        $this->response = $this->response->withJson($this->model->loadEditoriallyApprovedArticles($articleIds));
                    }
                    else if($cateroty == 'abyssArticles')
                    {
                        $articleIds = $this->model->loadAbyssArticlesIdsByTimestamp($count, $lastLoadedArticleId, $lastLoadedArticleTimestamp);
                        $this->response = $this->response->withJson($this->model->loadAbyssArticles($articleIds));
                    }
                    else
                    {
                        throw new Error(400, "Invalid category", "Invalid category");
                    }
                }
            }
            else if($sortType == 'rate')
            {
                $lastLoadedArticleRate = 2147483645;
                if(isset($this->parsedBody['lastLoadedArticleRate']))
                {
                    $lastLoadedArticleRate = $this->parsedBody['lastLoadedArticleRate'];
                }
            
                if(AdminStatusHandler::isAdmin($this->cookiesBody))
                {
                    if($cateroty == 'editoriallyArticles')
                    {
                        $articleIds = $this->model->loadEditoriallyArticlesIdsByRate($count, $lastLoadedArticleId, $lastLoadedArticleRate);
                        $this->response = $this->response->withJson($this->model->loadEditoriallyArticles($articleIds));
                    }
                    else if($cateroty == 'editoriallyApprovedArticles')
                    {
                        $articleIds = $this->model->loadEditoriallyApprovedArticlesIdsByRate($count, $lastLoadedArticleId, $lastLoadedArticleRate);
                        $this->response = $this->response->withJson($this->model->loadEditoriallyApprovedArticles($articleIds));
                    }
                    else if($cateroty == 'abyssArticles')
                    {
                        $articleIds = $this->model->loadAbyssArticlesIdsByRate($count, $lastLoadedArticleId, $lastLoadedArticleRate);
                        $this->response = $this->response->withJson($this->model->loadAbyssArticles($articleIds));
                    }
                    else if($cateroty == 'articlesWaitingApproval')
                    {
                        $articleIds = $this->model->loadArticlesWaitingApprovalIdsByRate($count, $lastLoadedArticleId, $lastLoadedArticleRate);
                        $this->response = $this->response->withJson($this->model->loadArticlesWaitingApproval($articleIds));
                    }
                    else if($cateroty == 'articlesWaitingPremoderate')
                    {
                        $articleIds = $this->model->loadArticlesWaitingPremoderateIdsByRate($count, $lastLoadedArticleId, $lastLoadedArticleRate);
                        $this->response = $this->response->withJson($this->model->loadArticlesWaitingPremoderate($articleIds));
                    }
                    else
                    {
                        throw new Error(400, "Invalid category", "Invalid category");
                    }
                }
                else
                {
                    if($cateroty == 'editoriallyArticles')
                    {
                        $articleIds = $this->model->loadEditoriallyArticlesIdsByRate($count, $lastLoadedArticleId, $lastLoadedArticleRate);
                        $this->response = $this->response->withJson($this->model->loadEditoriallyArticles($articleIds));
                    }
                    else if($cateroty == 'editoriallyApprovedArticles')
                    {
                        $articleIds = $this->model->loadEditoriallyApprovedArticlesIdsByRate($count, $lastLoadedArticleId, $lastLoadedArticleRate);
                        $this->response = $this->response->withJson($this->model->loadEditoriallyApprovedArticles($articleIds));
                    }
                    else if($cateroty == 'abyssArticles')
                    {
                        $articleIds = $this->model->loadAbyssArticlesIdsByRate($count, $lastLoadedArticleId, $lastLoadedArticleRate);
                        $this->response = $this->response->withJson($this->model->loadAbyssArticles($articleIds));
                    }
                    else
                    {
                        throw new Error(400, "Invalid category", "Invalid category");
                    }
                }
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