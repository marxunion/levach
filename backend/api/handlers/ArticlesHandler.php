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

    private function loadEditoriallyArticlesByRate()
    {
        if(isset($this->parsedBody['searchTitle']) && isset($this->parsedBody['searchTags']))
        {
            if(!strlen($this->parsedBody['searchTitle']))
            {
                throw new Error(400, "Invalid search title", "Invalid search title");
            }
            if(!is_array($this->parsedBody['searchTags']))
            {
                throw new Error(400, "Invalid search tags", "Invalid search tags");
            }

            $searchTitle = $this->parsedBody['searchTitle'];
            $searchTagsString = '{'.implode(',', $this->parsedBody['searchTags']).'}';

            $articleIds = $this->model->loadEditoriallyArticlesSearchTitleTagsIdsByRate($count, $lastLoaded, $searchTitle, $searchTagsString);
        }
        else
        {
            if(isset($this->parsedBody['searchTitle']))
            {
                if(strlen($this->parsedBody['searchTitle']) > 0)
                {
                    $searchTitle = $this->parsedBody['searchTitle'];
                    
                    $articleIds = $this->model->loadEditoriallyArticlesSearchTitleIdsByRate($count, $lastLoaded, $searchTitle);
                }
                else
                {
                    throw new Error(400, "Invalid search title", "Invalid search title");
                }
            }
            else if(isset($this->parsedBody['searchTags']))
            {
                if(is_array($this->parsedBody['searchTags']))
                {
                    $searchTagsString = '{'.implode(',', $this->parsedBody['searchTags']).'}';

                    $articleIds = $this->model->loadEditoriallyArticlesSearchTagsIdsByRate($count, $lastLoaded, $searchTagsString);
                }
                else
                {
                    throw new Error(400, "Invalid search tags", "Invalid search tags");
                }
            }
            else
            {
                $articleIds = $this->model->loadEditoriallyArticlesIdsByRate($count, $lastLoaded);
            }
        }
        $this->response = $this->response->withJson($this->model->loadEditoriallyArticles($articleIds));
    }

    private function loadEditoriallyArticlesByCreatedDate()
    {
        if(isset($this->parsedBody['searchTitle']) && isset($this->parsedBody['searchTags']))
        {
            if(!strlen($this->parsedBody['searchTitle']))
            {
                throw new Error(400, "Invalid search title", "Invalid search title");
            }
            if(!is_array($this->parsedBody['searchTags']))
            {
                throw new Error(400, "Invalid search tags", "Invalid search tags");
            }

            $searchTitle = $this->parsedBody['searchTitle'];
            $searchTagsString = '{'.implode(',', $this->parsedBody['searchTags']).'}';

            $articleIds = $this->model->loadEditoriallyArticlesSearchTitleTagsIdsByCreatedDate($count, $lastLoaded, $searchTitle, $searchTagsString);
        }
        else
        {
            if(isset($this->parsedBody['searchTitle']))
            {
                if(strlen($this->parsedBody['searchTitle']) > 0)
                {
                    $searchTitle = $this->parsedBody['searchTitle'];
                    
                    $articleIds = $this->model->loadEditoriallyArticlesSearchTitleIdsByCreatedDate($count, $lastLoaded, $searchTitle);
                }
                else
                {
                    throw new Error(400, "Invalid search title", "Invalid search title");
                }
            }
            else if(isset($this->parsedBody['searchTags']))
            {
                if(is_array($this->parsedBody['searchTags']))
                {
                    $searchTagsString = '{'.implode(',', $this->parsedBody['searchTags']).'}';

                    $articleIds = $this->model->loadEditoriallyArticlesSearchTagsIdsByCreatedDate($count, $lastLoaded, $searchTagsString);
                }
                else
                {
                    throw new Error(400, "Invalid search tags", "Invalid search tags");
                }
            }
            else
            {
                $articleIds = $this->model->loadEditoriallyArticlesIdsByCreatedDate($count, $lastLoaded);
            }
        }
        $this->response = $this->response->withJson($this->model->loadEditoriallyArticles($articleIds));
    }

    private function loadEditoriallyApprovedArticlesByRate()
    {
        if(isset($this->parsedBody['searchTitle']) && isset($this->parsedBody['searchTags']))
        {
            if(!strlen($this->parsedBody['searchTitle']))
            {
                throw new Error(400, "Invalid search title", "Invalid search title");
            }
            if(!is_array($this->parsedBody['searchTags']))
            {
                throw new Error(400, "Invalid search tags", "Invalid search tags");
            }

            $searchTitle = $this->parsedBody['searchTitle'];
            $searchTagsString = '{'.implode(',', $this->parsedBody['searchTags']).'}';

            $articleIds = $this->model->loadEditoriallyApprovedArticlesSearchTitleTagsIdsByRate($count, $lastLoaded, $searchTitle, $searchTagsString);
        }
        else
        {
            if(isset($this->parsedBody['searchTitle']))
            {
                if(strlen($this->parsedBody['searchTitle']) > 0)
                {
                    $searchTitle = $this->parsedBody['searchTitle'];
                    
                    $articleIds = $this->model->loadEditoriallyApprovedArticlesSearchTitleIdsByRate($count, $lastLoaded, $searchTitle);
                }
                else
                {
                    throw new Error(400, "Invalid search title", "Invalid search title");
                }
            }
            else if(isset($this->parsedBody['searchTags']))
            {
                if(is_array($this->parsedBody['searchTags']))
                {
                    $searchTagsString = '{'.implode(',', $this->parsedBody['searchTags']).'}';

                    $articleIds = $this->model->loadEditoriallyApprovedArticlesSearchTagsIdsByRate($count, $lastLoaded, $searchTagsString);
                }
                else
                {
                    throw new Error(400, "Invalid search tags", "Invalid search tags");
                }
            }
            else
            {
                $articleIds = $this->model->loadEditoriallyApprovedArticlesIdsByRate($count, $lastLoaded);
            }
        }
        $this->response = $this->response->withJson($this->model->loadEditoriallyApprovedArticles($articleIds));
    }

    private function loadEditoriallyApprovedArticlesByCreatedDate()
    {
        if(isset($this->parsedBody['searchTitle']) && isset($this->parsedBody['searchTags']))
        {
            if(!strlen($this->parsedBody['searchTitle']))
            {
                throw new Error(400, "Invalid search title", "Invalid search title");
            }
            if(!is_array($this->parsedBody['searchTags']))
            {
                throw new Error(400, "Invalid search tags", "Invalid search tags");
            }

            $searchTitle = $this->parsedBody['searchTitle'];
            $searchTagsString = '{'.implode(',', $this->parsedBody['searchTags']).'}';

            $articleIds = $this->model->loadEditoriallyApprovedArticlesSearchTitleTagsIdsByCreatedDate($count, $lastLoaded, $searchTitle, $searchTagsString);
        }
        else
        {
            if(isset($this->parsedBody['searchTitle']))
            {
                if(strlen($this->parsedBody['searchTitle']) > 0)
                {
                    $searchTitle = $this->parsedBody['searchTitle'];
                    
                    $articleIds = $this->model->loadEditoriallyApprovedArticlesSearchTitleIdsByCreatedDate($count, $lastLoaded, $searchTitle);
                }
                else
                {
                    throw new Error(400, "Invalid search title", "Invalid search title");
                }
            }
            else if(isset($this->parsedBody['searchTags']))
            {
                if(is_array($this->parsedBody['searchTags']))
                {
                    $searchTagsString = '{'.implode(',', $this->parsedBody['searchTags']).'}';

                    $articleIds = $this->model->loadEditoriallyApprovedArticlesSearchTagsIdsByCreatedDate($count, $lastLoaded, $searchTagsString);
                }
                else
                {
                    throw new Error(400, "Invalid search tags", "Invalid search tags");
                }
            }
            else
            {
                $articleIds = $this->model->loadEditoriallyApprovedArticlesIdsByCreatedDate($count, $lastLoaded);
            }
        }
        $this->response = $this->response->withJson($this->model->loadEditoriallyApprovedArticles($articleIds));
    }

    private function loadAbyssArticlesByRate()
    {
        if(isset($this->parsedBody['searchTitle']) && isset($this->parsedBody['searchTags']))
        {
            if(!strlen($this->parsedBody['searchTitle']))
            {
                throw new Error(400, "Invalid search title", "Invalid search title");
            }
            if(!is_array($this->parsedBody['searchTags']))
            {
                throw new Error(400, "Invalid search tags", "Invalid search tags");
            }

            $searchTitle = $this->parsedBody['searchTitle'];
            $searchTagsString = '{'.implode(',', $this->parsedBody['searchTags']).'}';

            $articleIds = $this->model->loadAbyssArticlesSearchTitleTagsIdsByRate($count, $lastLoaded, $searchTitle, $searchTagsString);
        }
        else
        {
            if(isset($this->parsedBody['searchTitle']))
            {
                if(strlen($this->parsedBody['searchTitle']) > 0)
                {
                    $searchTitle = $this->parsedBody['searchTitle'];
                    
                    $articleIds = $this->model->loadAbyssArticlesSearchTitleIdsByRate($count, $lastLoaded, $searchTitle);
                }
                else
                {
                    throw new Error(400, "Invalid search title", "Invalid search title");
                }
            }
            else if(isset($this->parsedBody['searchTags']))
            {
                if(is_array($this->parsedBody['searchTags']))
                {
                    $searchTagsString = '{'.implode(',', $this->parsedBody['searchTags']).'}';

                    $articleIds = $this->model->loadAbyssArticlesSearchTagsIdsByRate($count, $lastLoaded, $searchTagsString);
                }
                else
                {
                    throw new Error(400, "Invalid search tags", "Invalid search tags");
                }
            }
            else
            {
                $articleIds = $this->model->loadAbyssArticlesIdsByRate($count, $lastLoaded);
            }
        }
        $this->response = $this->response->withJson($this->model->loadAbyssArticles($articleIds));
    }

    private function loadAbyssArticlesByCreatedDate()
    {
        if(isset($this->parsedBody['searchTitle']) && isset($this->parsedBody['searchTags']))
        {
            if(!strlen($this->parsedBody['searchTitle']))
            {
                throw new Error(400, "Invalid search title", "Invalid search title");
            }
            if(!is_array($this->parsedBody['searchTags']))
            {
                throw new Error(400, "Invalid search tags", "Invalid search tags");
            }

            $searchTitle = $this->parsedBody['searchTitle'];
            $searchTagsString = '{'.implode(',', $this->parsedBody['searchTags']).'}';

            $articleIds = $this->model->loadAbyssArticlesSearchTitleTagsIdsByCreatedDate($count, $lastLoaded, $searchTitle, $searchTagsString);
        }
        else
        {
            if(isset($this->parsedBody['searchTitle']))
            {
                if(strlen($this->parsedBody['searchTitle']) > 0)
                {
                    $searchTitle = $this->parsedBody['searchTitle'];
                    
                    $articleIds = $this->model->loadAbyssArticlesSearchTitleIdsByCreatedDate($count, $lastLoaded, $searchTitle);
                }
                else
                {
                    throw new Error(400, "Invalid search title", "Invalid search title");
                }
            }
            else if(isset($this->parsedBody['searchTags']))
            {
                if(is_array($this->parsedBody['searchTags']))
                {
                    $searchTagsString = '{'.implode(',', $this->parsedBody['searchTags']).'}';

                    $articleIds = $this->model->loadAbyssArticlesSearchTagsIdsByCreatedDate($count, $lastLoaded, $searchTagsString);
                }
                else
                {
                    throw new Error(400, "Invalid search tags", "Invalid search tags");
                }
            }
            else
            {
                $articleIds = $this->model->loadAbyssArticlesIdsByCreatedDate($count, $lastLoaded);
            }
        }
        $this->response = $this->response->withJson($this->model->loadAbyssArticles($articleIds));
    }

    private function loadArticlesWaitingApproveByRate()
    {
        if(isset($this->parsedBody['searchTitle']) && isset($this->parsedBody['searchTags']))
        {
            if(!strlen($this->parsedBody['searchTitle']))
            {
                throw new Error(400, "Invalid search title", "Invalid search title");
            }
            if(!is_array($this->parsedBody['searchTags']))
            {
                throw new Error(400, "Invalid search tags", "Invalid search tags");
            }

            $searchTitle = $this->parsedBody['searchTitle'];
            $searchTagsString = '{'.implode(',', $this->parsedBody['searchTags']).'}';

            $articleIds = $this->model->loadArticlesWaitingApproveSearchTitleTagsIdsByRate($count, $lastLoaded, $searchTitle, $searchTagsString);
        }
        else
        {
            if(isset($this->parsedBody['searchTitle']))
            {
                if(strlen($this->parsedBody['searchTitle']) > 0)
                {
                    $searchTitle = $this->parsedBody['searchTitle'];
                    
                    $articleIds = $this->model->loadArticlesWaitingApproveSearchTitleIdsByRate($count, $lastLoaded, $searchTitle);
                }
                else
                {
                    throw new Error(400, "Invalid search title", "Invalid search title");
                }
            }
            else if(isset($this->parsedBody['searchTags']))
            {
                if(is_array($this->parsedBody['searchTags']))
                {
                    $searchTagsString = '{'.implode(',', $this->parsedBody['searchTags']).'}';

                    $articleIds = $this->model->loadArticlesWaitingApproveSearchTagsIdsByRate($count, $lastLoaded, $searchTagsString);
                }
                else
                {
                    throw new Error(400, "Invalid search tags", "Invalid search tags");
                }
            }
            else
            {
                $articleIds = $this->model->loadArticlesWaitingApproveIdsByRate($count, $lastLoaded);
            }
        }
        $this->response = $this->response->withJson($this->model->loadArticlesWaitingApprove($articleIds));
    }

    private function loadArticlesWaitingApproveByCreatedDate()
    {
        if(isset($this->parsedBody['searchTitle']) && isset($this->parsedBody['searchTags']))
        {
            if(!strlen($this->parsedBody['searchTitle']))
            {
                throw new Error(400, "Invalid search title", "Invalid search title");
            }
            if(!is_array($this->parsedBody['searchTags']))
            {
                throw new Error(400, "Invalid search tags", "Invalid search tags");
            }

            $searchTitle = $this->parsedBody['searchTitle'];
            $searchTagsString = '{'.implode(',', $this->parsedBody['searchTags']).'}';

            $articleIds = $this->model->loadArticlesWaitingApproveSearchTitleTagsIdsByCreatedDate($count, $lastLoaded, $searchTitle, $searchTagsString);
        }
        else
        {
            if(isset($this->parsedBody['searchTitle']))
            {
                if(strlen($this->parsedBody['searchTitle']) > 0)
                {
                    $searchTitle = $this->parsedBody['searchTitle'];
                    
                    $articleIds = $this->model->loadArticlesWaitingApproveSearchTitleIdsByCreatedDate($count, $lastLoaded, $searchTitle);
                }
                else
                {
                    throw new Error(400, "Invalid search title", "Invalid search title");
                }
            }
            else if(isset($this->parsedBody['searchTags']))
            {
                if(is_array($this->parsedBody['searchTags']))
                {
                    $searchTagsString = '{'.implode(',', $this->parsedBody['searchTags']).'}';

                    $articleIds = $this->model->loadArticlesWaitingApproveSearchTagsIdsByCreatedDate($count, $lastLoaded, $searchTagsString);
                }
                else
                {
                    throw new Error(400, "Invalid search tags", "Invalid search tags");
                }
            }
            else
            {
                $articleIds = $this->model->loadArticlesWaitingApproveIdsByCreatedDate($count, $lastLoaded);
            }
        }
        $this->response = $this->response->withJson($this->model->loadArticlesWaitingApprove($articleIds));
    }

    private function loadArticlesWaitingPremoderateByRate()
    {
        if(isset($this->parsedBody['searchTitle']) && isset($this->parsedBody['searchTags']))
        {
            if(!strlen($this->parsedBody['searchTitle']))
            {
                throw new Error(400, "Invalid search title", "Invalid search title");
            }
            if(!is_array($this->parsedBody['searchTags']))
            {
                throw new Error(400, "Invalid search tags", "Invalid search tags");
            }

            $searchTitle = $this->parsedBody['searchTitle'];
            $searchTagsString = '{'.implode(',', $this->parsedBody['searchTags']).'}';

            $articleIds = $this->model->loadArticlesWaitingPremoderateSearchTitleTagsIdsByRate($count, $lastLoaded, $searchTitle, $searchTagsString);
        }
        else
        {
            if(isset($this->parsedBody['searchTitle']))
            {
                if(strlen($this->parsedBody['searchTitle']) > 0)
                {
                    $searchTitle = $this->parsedBody['searchTitle'];
                    
                    $articleIds = $this->model->loadArticlesWaitingPremoderateSearchTitleIdsByRate($count, $lastLoaded, $searchTitle);
                }
                else
                {
                    throw new Error(400, "Invalid search title", "Invalid search title");
                }
            }
            else if(isset($this->parsedBody['searchTags']))
            {
                if(is_array($this->parsedBody['searchTags']))
                {
                    $searchTagsString = '{'.implode(',', $this->parsedBody['searchTags']).'}';

                    $articleIds = $this->model->loadArticlesWaitingPremoderateSearchTagsIdsByRate($count, $lastLoaded, $searchTagsString);
                }
                else
                {
                    throw new Error(400, "Invalid search tags", "Invalid search tags");
                }
            }
            else
            {
                $articleIds = $this->model->loadArticlesWaitingPremoderateIdsByRate($count, $lastLoaded);
            }
        }
        $this->response = $this->response->withJson($this->model->loadArticlesWaitingPremoderate($articleIds));
    }

    private function loadArticlesWaitingPremoderateByCreatedDate()
    {
        if(isset($this->parsedBody['searchTitle']) && isset($this->parsedBody['searchTags']))
        {
            if(!strlen($this->parsedBody['searchTitle']))
            {
                throw new Error(400, "Invalid search title", "Invalid search title");
            }
            if(!is_array($this->parsedBody['searchTags']))
            {
                throw new Error(400, "Invalid search tags", "Invalid search tags");
            }

            $searchTitle = $this->parsedBody['searchTitle'];
            $searchTagsString = '{'.implode(',', $this->parsedBody['searchTags']).'}';

            $articleIds = $this->model->loadArticlesWaitingPremoderateSearchTitleTagsIdsByCreatedDate($count, $lastLoaded, $searchTitle, $searchTagsString);
        }
        else
        {
            if(isset($this->parsedBody['searchTitle']))
            {
                if(strlen($this->parsedBody['searchTitle']) > 0)
                {
                    $searchTitle = $this->parsedBody['searchTitle'];
                    
                    $articleIds = $this->model->loadArticlesWaitingPremoderateSearchTitleIdsByCreatedDate($count, $lastLoaded, $searchTitle);
                }
                else
                {
                    throw new Error(400, "Invalid search title", "Invalid search title");
                }
            }
            else if(isset($this->parsedBody['searchTags']))
            {
                if(is_array($this->parsedBody['searchTags']))
                {
                    $searchTagsString = '{'.implode(',', $this->parsedBody['searchTags']).'}';

                    $articleIds = $this->model->loadArticlesWaitingPremoderateSearchTagsIdsByCreatedDate($count, $lastLoaded, $searchTagsString);
                }
                else
                {
                    throw new Error(400, "Invalid search tags", "Invalid search tags");
                }
            }
            else
            {
                $articleIds = $this->model->loadArticlesWaitingPremoderateIdsByCreatedDate($count, $lastLoaded);
            }
        }
        $this->response = $this->response->withJson($this->model->loadArticlesWaitingPremoderate($articleIds));
    }

    private function loadArticlesSearchByRate()
    {
        if(isset($this->parsedBody['searchTitle']) && isset($this->parsedBody['searchTags']))
        {
            if(!strlen($this->parsedBody['searchTitle']))
            {
                throw new Error(400, "Invalid search title", "Invalid search title");
            }
            if(!is_array($this->parsedBody['searchTags']))
            {
                throw new Error(400, "Invalid search tags", "Invalid search tags");
            }

            $searchTitle = $this->parsedBody['searchTitle'];
            $searchTagsString = '{'.implode(',', $this->parsedBody['searchTags']).'}';
            
            $articleIds = $this->model->loadArticlesSearchTitleTagsIdsByRate($count, $lastLoaded, $searchTitle, $searchTagsString);
        }
        else
        {
            if(isset($this->parsedBody['searchTitle']))
            {
                if(strlen($this->parsedBody['searchTitle']) > 0)
                {
                    $searchTitle = $this->parsedBody['searchTitle'];
                    
                    $articleIds = $this->model->loadArticlesSearchTitleIdsByRate($count, $lastLoaded, $searchTitle);
                }
                else
                {
                    throw new Error(400, "Invalid search title", "Invalid search title");
                }
            }
            else if(isset($this->parsedBody['searchTags']))
            {
                if(is_array($this->parsedBody['searchTags']))
                {
                    $searchTagsString = '{'.implode(',', $this->parsedBody['searchTags']).'}';

                    $articleIds = $this->model->loadArticlesSearchTagsIdsByRate($count, $lastLoaded, $searchTagsString);
                }
                else
                {
                    throw new Error(400, "Invalid search tags", "Invalid search tags");
                }
            }
            else
            {
                throw new Error(400, "Invalid search request", "Invalid search request");
            }
        }
        $this->response = $this->response->withJson($this->model->loadArticlesSearch($articleIds));
    }

    private function loadArticlesSearchByCreatedDate()
    {
        if(isset($this->parsedBody['searchTitle']) && isset($this->parsedBody['searchTags']))
        {
            if(!strlen($this->parsedBody['searchTitle']))
            {
                throw new Error(400, "Invalid search title", "Invalid search title");
            }
            if(!is_array($this->parsedBody['searchTags']))
            {
                throw new Error(400, "Invalid search tags", "Invalid search tags");
            }

            $searchTitle = $this->parsedBody['searchTitle'];
            $searchTagsString = '{'.implode(',', $this->parsedBody['searchTags']).'}';
            
            $articleIds = $this->model->loadArticlesSearchTitleTagsIdsByCreatedDate($count, $lastLoaded, $searchTitle, $searchTagsString);
        }
        else
        {
            if(isset($this->parsedBody['searchTitle']))
            {
                if(strlen($this->parsedBody['searchTitle']) > 0)
                {
                    $searchTitle = $this->parsedBody['searchTitle'];
                    
                    $articleIds = $this->model->loadArticlesSearchTitleIdsByCreatedDate($count, $lastLoaded, $searchTitle);
                }
                else
                {
                    throw new Error(400, "Invalid search title", "Invalid search title");
                }
            }
            else if(isset($this->parsedBody['searchTags']))
            {
                if(is_array($this->parsedBody['searchTags']))
                {
                    $searchTagsString = '{'.implode(',', $this->parsedBody['searchTags']).'}';

                    $articleIds = $this->model->loadArticlesSearchTagsIdsByCreatedDate($count, $lastLoaded, $searchTagsString);
                }
                else
                {
                    throw new Error(400, "Invalid search tags", "Invalid search tags");
                }
            }
            else
            {
                throw new Error(400, "Invalid search request", "Invalid search request");
            }
        }
        $this->response = $this->response->withJson($this->model->loadArticlesSearch($articleIds));
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
                        loadEditoriallyArticlesByCreatedDate();
                    }
                    else if($category == 'EditoriallyApprovedArticles')
                    {
                        loadEditoriallyApprovedArticlesByCreatedDate();
                    }
                    else if($category == 'AbyssArticles')
                    {
                        loadAbyssArticlesByCreatedDate();
                    }
                    else if($category == 'ArticlesWaitingApprove')
                    {
                        loadArticlesWaitingApproveByCreatedDate();
                    }
                    else if($category == 'ArticlesWaitingPremoderate')
                    {
                        loadArticlesWaitingPremoderateByCreatedDate();
                    }
                    else if($category == 'ArticlesSearch')
                    {
                        loadArticlesSearchByCreatedDate();
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
                        loadEditoriallyArticlesByCreatedDate();
                    }
                    else if($category == 'EditoriallyApprovedArticles')
                    {
                        loadEditoriallyApprovedArticlesByCreatedDate();
                    }
                    else if($category == 'AbyssArticles')
                    {
                        loadAbyssArticlesByCreatedDate();
                    }
                    else if($category == 'ArticlesSearch')
                    {
                        loadArticlesSearchByCreatedDate()
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
                        loadEditoriallyArticlesByRate();
                    }
                    else if($category == 'EditoriallyApprovedArticles')
                    {
                        loadEditoriallyApprovedArticlesByRate();
                    }
                    else if($category == 'AbyssArticles')
                    {
                        loadAbyssArticlesByRate();
                    }
                    else if($category == 'ArticlesWaitingApprove')
                    {
                        loadArticlesWaitingApproveByRate();
                    }
                    else if($category == 'ArticlesWaitingPremoderate')
                    {
                        loadArticlesWaitingPremoderateByRate();
                    }
                    else if($category == 'ArticlesSearch')
                    {
                        loadArticlesSearchByRate();
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
                        loadEditoriallyArticlesByRate();
                    }
                    else if($category == 'EditoriallyApprovedArticles')
                    {
                        loadEditoriallyApprovedArticlesByRate();
                    }
                    else if($category == 'AbyssArticles')
                    {
                        loadAbyssArticlesByRate();
                    }
                    else if($category == 'ArticlesSearch')
                    {
                        loadArticlesSearchByRate();
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