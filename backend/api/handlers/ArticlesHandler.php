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
        
        if(isset($this->parsedBody['category']))
        {
            $category = $this->parsedBody['category'];
        }
        else
        {
            $category = 'editoriallyArticles';
        }
        
        if(isset($this->parsedBody['lastLoaded']))
        {
            $lastLoaded = $this->parsedBody['lastLoaded'];
        }
        else
        {
            $lastLoaded = 0;
        }

        
        if(isset($this->parsedBody['sortType']))
        {
            $sortType = $this->parsedBody['sortType'];
            $articleIds = null;
        
            if($sortType == 'created_date')
            {
                if(AdminStatusHandler::isAdmin($this->cookiesBody))
                {
                    if($category == 'EditoriallyArticles')
                    {
                        $articleIds = $this->model->loadEditoriallyArticlesIdsByCreatedDate($count, $lastLoaded);
                        $this->response = $this->response->withJson($this->model->loadEditoriallyArticles($articleIds));
                    }
                    else if($category == 'EditoriallyApprovedArticles')
                    {
                        $articleIds = $this->model->loadEditoriallyApprovedArticlesIdsByCreatedDate($count, $lastLoaded);
                        $this->response = $this->response->withJson($this->model->loadEditoriallyApprovedArticles($articleIds));
                    }
                    else if($category == 'AbyssArticles')
                    {
                        $articleIds = $this->model->loadAbyssArticlesIdsByCreatedDate($count, $lastLoaded);
                        $this->response = $this->response->withJson($this->model->loadAbyssArticles($articleIds));
                    }
                    else if($category == 'ArticlesSearch')
                    {
                        if(isset($this->parsedBody['searchTitle']))
                        {
                            if(strlen($this->parsedBody['searchTitle']) > 0)
                            {
                                $searchTitle = $this->parsedBody['searchTitle'];
                                if(isset($this->parsedBody['searchTags']))
                                {
                                    $searchTagsString = '{'.implode(',', $this->parsedBody['searchTags']).'}';
    
                                    $articleIds = $this->model->loadArticlesSearchIdsByCreatedDateWithTags($count, $lastLoaded, $searchTitle, $searchTagsString);
                                    $this->response = $this->response->withJson($this->model->loadArticlesSearch($articleIds));
                                }
                                else
                                {
                                    $articleIds = $this->model->loadArticlesSearchIdsByCreatedDate($count, $lastLoaded, $searchTitle);
                                    $this->response = $this->response->withJson($this->model->loadArticlesSearch($articleIds));
                                }
                            }
                            else
                            {
                                throw new Error(400, "Not found search title", "Not found search title");
                            }
                        }
                        else
                        {
                            throw new Error(400, "Not found search title", "Not found search title");
                        }
                    }
                    else if($category == 'ArticlesWaitingApproval')
                    {
                        $articleIds = $this->model->loadArticlesWaitingApprovalIdsByCreatedDate($count, $lastLoaded);
                        $this->response = $this->response->withJson($this->model->loadArticlesWaitingApproval($articleIds));
                    }
                    else if($category == 'ArticlesWaitingPremoderate')
                    {
                        $articleIds = $this->model->loadArticlesWaitingPremoderateIdsByCreatedDate($count, $lastLoaded);
                        $this->response = $this->response->withJson($this->model->loadArticlesWaitingPremoderate($articleIds));
                    }
                    else
                    {
                        throw new Error(400, "Invalid category", "Invalid category");
                    }
                }
                else
                {
                    if($category == 'EditoriallyArticles')
                    {
                        $articleIds = $this->model->loadEditoriallyArticlesIdsByCreatedDate($count, $lastLoaded);
                        $this->response = $this->response->withJson($this->model->loadEditoriallyArticles($articleIds));
                    }
                    else if($category == 'EditoriallyApprovedArticles')
                    {
                        $articleIds = $this->model->loadEditoriallyApprovedArticlesIdsByCreatedDate($count, $lastLoaded);
                        $this->response = $this->response->withJson($this->model->loadEditoriallyApprovedArticles($articleIds));
                    }
                    else if($category == 'AbyssArticles')
                    {
                        $articleIds = $this->model->loadAbyssArticlesIdsByCreatedDate($count, $lastLoaded);
                        $this->response = $this->response->withJson($this->model->loadAbyssArticles($articleIds));
                    }
                    else if($category == 'ArticlesSearch')
                    {
                        if(isset($this->parsedBody['searchTitle']))
                        {
                            if(strlen($this->parsedBody['searchTitle']) > 0)
                            {
                                $searchTitle = $this->parsedBody['searchTitle'];
                                $searchTags = $this->parsedBody['searchTags'];
                                
                                if(isset($searchTags))
                                {
                                    $searchTagsString = '{'.implode(',', $searchTags).'}';
    
                                    $articleIds = $this->model->loadArticlesSearchIdsByCreatedDateWithTags($count, $lastLoaded, $searchTitle, $searchTagsString);
                                    $this->response = $this->response->withJson($this->model->loadArticlesSearch($articleIds));
                                }
                                else
                                {
                                    $articleIds = $this->model->loadArticlesSearchIdsByCreatedDate($count, $lastLoaded, $searchTitle);
                                    $this->response = $this->response->withJson($this->model->loadArticlesSearch($articleIds));
                                }
                            }
                            else
                            {
                                throw new Error(400, "Not found search title", "Not found search title");
                            }
                        }
                        else
                        {
                            throw new Error(400, "Not found search title", "Not found search title");
                        }
                    }
                    else
                    {
                        throw new Error(400, "Invalid category", "Invalid category");
                    }
                }
            }
            else if($sortType == 'rate')
            {
                if(AdminStatusHandler::isAdmin($this->cookiesBody))
                {
                    if($category == 'EditoriallyArticles')
                    {
                        $articleIds = $this->model->loadEditoriallyArticlesIdsByRate($count, $lastLoaded);
                        $this->response = $this->response->withJson($this->model->loadEditoriallyArticles($articleIds));
                    }
                    else if($category == 'EditoriallyApprovedArticles')
                    {
                        $articleIds = $this->model->loadEditoriallyApprovedArticlesIdsByRate($count, $lastLoaded);
                        $this->response = $this->response->withJson($this->model->loadEditoriallyApprovedArticles($articleIds));
                    }
                    else if($category == 'AbyssArticles')
                    {
                        $articleIds = $this->model->loadAbyssArticlesIdsByRate($count, $lastLoaded);
                        $this->response = $this->response->withJson($this->model->loadAbyssArticles($articleIds));
                    }
                    else if($category == 'ArticlesSearch')
                    {
                        if(isset($this->parsedBody['searchTitle']))
                        {
                            if(strlen($this->parsedBody['searchTitle']) > 0)
                            {
                                $searchTitle = $this->parsedBody['searchTitle'];
                                
                                if(isset($this->parsedBody['searchTags']))
                                {
                                    $searchTagsString = '{'.implode(',', $this->parsedBody['searchTags']).'}';
    
                                    $articleIds = $this->model->loadArticlesSearchIdsByRateWithTags($count, $lastLoaded, $searchTitle, $searchTagsString);
                                    $this->response = $this->response->withJson($this->model->loadArticlesSearch($articleIds));
                                }
                                else
                                {
                                    $articleIds = $this->model->loadArticlesSearchIdsByRate($count, $lastLoaded, $searchTitle);
                                    $this->response = $this->response->withJson($this->model->loadArticlesSearch($articleIds));
                                }
                            }
                            else
                            {
                                throw new Error(400, "Not found search title", "Not found search title");
                            }
                        }
                        else
                        {
                            throw new Error(400, "Not found search title", "Not found search title");
                        }
                    }
                    else if($category == 'ArticlesWaitingApproval')
                    {
                        $articleIds = $this->model->loadArticlesWaitingApprovalIdsByRate($count, $lastLoaded);
                        $this->response = $this->response->withJson($this->model->loadArticlesWaitingApproval($articleIds));
                    }
                    else if($category == 'ArticlesWaitingPremoderate')
                    {
                        $articleIds = $this->model->loadArticlesWaitingPremoderateIdsByRate($count, $lastLoaded);
                        $this->response = $this->response->withJson($this->model->loadArticlesWaitingPremoderate($articleIds));
                    }
                    else
                    {
                        throw new Error(400, "Invalid category", "Invalid category");
                    }
                }
                else
                {
                    if($category == 'EditoriallyArticles')
                    {
                        $articleIds = $this->model->loadEditoriallyArticlesIdsByRate($count, $lastLoaded);
                        $this->response = $this->response->withJson($this->model->loadEditoriallyArticles($articleIds));
                    }
                    else if($category == 'EditoriallyApprovedArticles')
                    {
                        $articleIds = $this->model->loadEditoriallyApprovedArticlesIdsByRate($count, $lastLoaded);
                        $this->response = $this->response->withJson($this->model->loadEditoriallyApprovedArticles($articleIds));
                    }
                    else if($category == 'AbyssArticles')
                    {
                        $articleIds = $this->model->loadAbyssArticlesIdsByRate($count, $lastLoaded);
                        $this->response = $this->response->withJson($this->model->loadAbyssArticles($articleIds));
                    }
                    else if($category == 'ArticlesSearch')
                    {
                        if(isset($this->parsedBody['searchTitle']))
                        {
                            if(strlen($this->parsedBody['searchTitle']) > 0)
                            {
                                $searchTitle = $this->parsedBody['searchTitle'];
                                $searchTags = $this->parsedBody['searchTags'];
                                
                                if(isset($searchTags))
                                {
                                    $searchTagsString = '{'.implode(',', $searchTags).'}';
    
                                    $articleIds = $this->model->loadArticlesSearchIdsByRateWithTags($count, $lastLoaded, $searchTitle, $searchTagsString);
                                    $this->response = $this->response->withJson($this->model->loadArticlesSearch($articleIds));
                                }
                                else
                                {
                                    $articleIds = $this->model->loadArticlesSearchIdsByRate($count, $lastLoaded, $searchTitle);
                                    $this->response = $this->response->withJson($this->model->loadArticlesSearch($articleIds));
                                }
                            }
                            else
                            {
                                throw new Error(400, "Not found search title", "Not found search title");
                            }
                        }
                        else
                        {
                            throw new Error(400, "Not found search title", "Not found search title");
                        }
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