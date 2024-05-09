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
        if(is_array($this->request->getQueryParams()))
        {
            $this->parsedBody = $this->request->getQueryParams();

            $this->model = new ArticlesModel();

            if(isset($this->parsedBody['count']))
            {
                if($this->parsedBody['count'] > 8)
                {
                    $this->parsedBody['count'] = 8;
                }
            }
            else
            {
                $this->parsedBody['count'] = 4;
            }
            
            if(!isset($this->parsedBody['category']))
            {
                $this->parsedBody['category'] = 'editoriallyArticles';
            }
            
            if(!isset($this->parsedBody['lastLoaded']))
            {
                $this->parsedBody['lastLoaded'] = 0;
            }
            
            if(!isset($this->parsedBody['sortType']))
            {
                $this->parsedBody['sortType'] = 'rate';
            }

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

            $searchTitle = '%'.$this->parsedBody['searchTitle'].'%';
            $searchTagsString = '{'.implode(',', $this->parsedBody['searchTags']).'}';

            $articleIds = $this->model->loadEditoriallyArticlesSearchTitleTagsIdsByRate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle, $searchTagsString);
        }
        else
        {
            if(isset($this->parsedBody['searchTitle']))
            {
                if(strlen($this->parsedBody['searchTitle']) > 0)
                {
                    $searchTitle = '%'.$this->parsedBody['searchTitle'].'%';
                    
                    $articleIds = $this->model->loadEditoriallyArticlesSearchTitleIdsByRate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle);
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
                    $articleIds = $this->model->loadEditoriallyArticlesSearchTagsIdsByRate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTagsString);
                }
                else
                {
                    throw new Error(400, "Invalid search tags", "Invalid search tags");
                }
            }
            else
            {
                $articleIds = $this->model->loadEditoriallyArticlesIdsByRate($this->parsedBody['count'], $this->parsedBody['lastLoaded']);
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

            $searchTitle = '%'.$this->parsedBody['searchTitle'].'%';
            $searchTagsString = '{'.implode(',', $this->parsedBody['searchTags']).'}';

            $articleIds = $this->model->loadEditoriallyArticlesSearchTitleTagsIdsByCreatedDate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle, $searchTagsString);
        }
        else
        {
            if(isset($this->parsedBody['searchTitle']))
            {
                if(strlen($this->parsedBody['searchTitle']) > 0)
                {
                    $searchTitle = '%'.$this->parsedBody['searchTitle'].'%';
                    
                    $articleIds = $this->model->loadEditoriallyArticlesSearchTitleIdsByCreatedDate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle);
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

                    $articleIds = $this->model->loadEditoriallyArticlesSearchTagsIdsByCreatedDate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTagsString);
                }
                else
                {
                    throw new Error(400, "Invalid search tags", "Invalid search tags");
                }
            }
            else
            {
                $articleIds = $this->model->loadEditoriallyArticlesIdsByCreatedDate($this->parsedBody['count'], $this->parsedBody['lastLoaded']);
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

            $searchTitle = '%'.$this->parsedBody['searchTitle'].'%';
            $searchTagsString = '{'.implode(',', $this->parsedBody['searchTags']).'}';

            $articleIds = $this->model->loadEditoriallyApprovedArticlesSearchTitleTagsIdsByRate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle, $searchTagsString);
        }
        else
        {
            if(isset($this->parsedBody['searchTitle']))
            {
                if(strlen($this->parsedBody['searchTitle']) > 0)
                {
                    $searchTitle = '%'.$this->parsedBody['searchTitle'].'%';
                    
                    $articleIds = $this->model->loadEditoriallyApprovedArticlesSearchTitleIdsByRate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle);
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

                    $articleIds = $this->model->loadEditoriallyApprovedArticlesSearchTagsIdsByRate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTagsString);
                }
                else
                {
                    throw new Error(400, "Invalid search tags", "Invalid search tags");
                }
            }
            else
            {
                $articleIds = $this->model->loadEditoriallyApprovedArticlesIdsByRate($this->parsedBody['count'], $this->parsedBody['lastLoaded']);
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

            $searchTitle = '%'.$this->parsedBody['searchTitle'].'%';
            $searchTagsString = '{'.implode(',', $this->parsedBody['searchTags']).'}';

            $articleIds = $this->model->loadEditoriallyApprovedArticlesSearchTitleTagsIdsByCreatedDate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle, $searchTagsString);
        }
        else
        {
            if(isset($this->parsedBody['searchTitle']))
            {
                if(strlen($this->parsedBody['searchTitle']) > 0)
                {
                    $searchTitle = '%'.$this->parsedBody['searchTitle'].'%';
                    
                    $articleIds = $this->model->loadEditoriallyApprovedArticlesSearchTitleIdsByCreatedDate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle);
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

                    $articleIds = $this->model->loadEditoriallyApprovedArticlesSearchTagsIdsByCreatedDate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTagsString);
                }
                else
                {
                    throw new Error(400, "Invalid search tags", "Invalid search tags");
                }
            }
            else
            {
                $articleIds = $this->model->loadEditoriallyApprovedArticlesIdsByCreatedDate($this->parsedBody['count'], $this->parsedBody['lastLoaded']);
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

            $searchTitle = '%'.$this->parsedBody['searchTitle'].'%';
            $searchTagsString = '{'.implode(',', $this->parsedBody['searchTags']).'}';

            $articleIds = $this->model->loadAbyssArticlesSearchTitleTagsIdsByRate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle, $searchTagsString);
        }
        else
        {
            if(isset($this->parsedBody['searchTitle']))
            {
                if(strlen($this->parsedBody['searchTitle']) > 0)
                {
                    $searchTitle = '%'.$this->parsedBody['searchTitle'].'%';
                    
                    $articleIds = $this->model->loadAbyssArticlesSearchTitleIdsByRate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle);
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

                    $articleIds = $this->model->loadAbyssArticlesSearchTagsIdsByRate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTagsString);
                }
                else
                {
                    throw new Error(400, "Invalid search tags", "Invalid search tags");
                }
            }
            else
            {
                $articleIds = $this->model->loadAbyssArticlesIdsByRate($this->parsedBody['count'], $this->parsedBody['lastLoaded']);
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

            $searchTitle = '%'.$this->parsedBody['searchTitle'].'%';
            $searchTagsString = '{'.implode(',', $this->parsedBody['searchTags']).'}';

            $articleIds = $this->model->loadAbyssArticlesSearchTitleTagsIdsByCreatedDate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle, $searchTagsString);
        }
        else
        {
            if(isset($this->parsedBody['searchTitle']))
            {
                if(strlen($this->parsedBody['searchTitle']) > 0)
                {
                    $searchTitle = '%'.$this->parsedBody['searchTitle'].'%';
                    
                    $articleIds = $this->model->loadAbyssArticlesSearchTitleIdsByCreatedDate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle);
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

                    $articleIds = $this->model->loadAbyssArticlesSearchTagsIdsByCreatedDate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTagsString);
                }
                else
                {
                    throw new Error(400, "Invalid search tags", "Invalid search tags");
                }
            }
            else
            {
                $articleIds = $this->model->loadAbyssArticlesIdsByCreatedDate($this->parsedBody['count'], $this->parsedBody['lastLoaded']);
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

            $searchTitle = '%'.$this->parsedBody['searchTitle'].'%';
            $searchTagsString = '{'.implode(',', $this->parsedBody['searchTags']).'}';

            $articleIds = $this->model->loadArticlesWaitingApproveSearchTitleTagsIdsByRate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle, $searchTagsString);
        }
        else
        {
            if(isset($this->parsedBody['searchTitle']))
            {
                if(strlen($this->parsedBody['searchTitle']) > 0)
                {
                    $searchTitle = '%'.$this->parsedBody['searchTitle'].'%';
                    
                    $articleIds = $this->model->loadArticlesWaitingApproveSearchTitleIdsByRate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle);
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

                    $articleIds = $this->model->loadArticlesWaitingApproveSearchTagsIdsByRate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTagsString);
                }
                else
                {
                    throw new Error(400, "Invalid search tags", "Invalid search tags");
                }
            }
            else
            {
                $articleIds = $this->model->loadArticlesWaitingApproveIdsByRate($this->parsedBody['count'], $this->parsedBody['lastLoaded']);
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

            $searchTitle = '%'.$this->parsedBody['searchTitle'].'%';
            $searchTagsString = '{'.implode(',', $this->parsedBody['searchTags']).'}';

            $articleIds = $this->model->loadArticlesWaitingApproveSearchTitleTagsIdsByCreatedDate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle, $searchTagsString);
        }
        else
        {
            if(isset($this->parsedBody['searchTitle']))
            {
                if(strlen($this->parsedBody['searchTitle']) > 0)
                {
                    $searchTitle = '%'.$this->parsedBody['searchTitle'].'%';
                    
                    $articleIds = $this->model->loadArticlesWaitingApproveSearchTitleIdsByCreatedDate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle);
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

                    $articleIds = $this->model->loadArticlesWaitingApproveSearchTagsIdsByCreatedDate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTagsString);
                }
                else
                {
                    throw new Error(400, "Invalid search tags", "Invalid search tags");
                }
            }
            else
            {
                $articleIds = $this->model->loadArticlesWaitingApproveIdsByCreatedDate($this->parsedBody['count'], $this->parsedBody['lastLoaded']);
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

            $searchTitle = '%'.$this->parsedBody['searchTitle'].'%';
            $searchTagsString = '{'.implode(',', $this->parsedBody['searchTags']).'}';

            $articleIds = $this->model->loadArticlesWaitingPremoderateSearchTitleTagsIdsByRate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle, $searchTagsString);
        }
        else
        {
            if(isset($this->parsedBody['searchTitle']))
            {
                if(strlen($this->parsedBody['searchTitle']) > 0)
                {
                    $searchTitle = '%'.$this->parsedBody['searchTitle'].'%';
                    
                    $articleIds = $this->model->loadArticlesWaitingPremoderateSearchTitleIdsByRate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle);
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

                    $articleIds = $this->model->loadArticlesWaitingPremoderateSearchTagsIdsByRate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTagsString);
                }
                else
                {
                    throw new Error(400, "Invalid search tags", "Invalid search tags");
                }
            }
            else
            {
                $articleIds = $this->model->loadArticlesWaitingPremoderateIdsByRate($this->parsedBody['count'], $this->parsedBody['lastLoaded']);
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

            $searchTitle = '%'.$this->parsedBody['searchTitle'].'%';
            $searchTagsString = '{'.implode(',', $this->parsedBody['searchTags']).'}';

            $articleIds = $this->model->loadArticlesWaitingPremoderateSearchTitleTagsIdsByCreatedDate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle, $searchTagsString);
        }
        else
        {
            if(isset($this->parsedBody['searchTitle']))
            {
                if(strlen($this->parsedBody['searchTitle']) > 0)
                {
                    $searchTitle = '%'.$this->parsedBody['searchTitle'].'%';
                    
                    $articleIds = $this->model->loadArticlesWaitingPremoderateSearchTitleIdsByCreatedDate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle);
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

                    $articleIds = $this->model->loadArticlesWaitingPremoderateSearchTagsIdsByCreatedDate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTagsString);
                }
                else
                {
                    throw new Error(400, "Invalid search tags", "Invalid search tags");
                }
            }
            else
            {
                $articleIds = $this->model->loadArticlesWaitingPremoderateIdsByCreatedDate($this->parsedBody['count'], $this->parsedBody['lastLoaded']);
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

            $searchTitle = '%'.$this->parsedBody['searchTitle'].'%';
            $searchTagsString = '{'.implode(',', $this->parsedBody['searchTags']).'}';
            
            $articleIds = $this->model->loadArticlesSearchTitleTagsIdsByRate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle, $searchTagsString);
        }
        else
        {
            if(isset($this->parsedBody['searchTitle']))
            {
                if(strlen($this->parsedBody['searchTitle']) > 0)
                {
                    $searchTitle = '%'.$this->parsedBody['searchTitle'].'%';
                    
                    $articleIds = $this->model->loadArticlesSearchTitleIdsByRate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle);
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

                    $articleIds = $this->model->loadArticlesSearchTagsIdsByRate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTagsString);
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

            $searchTitle = '%'.$this->parsedBody['searchTitle'].'%';
            $searchTagsString = '{'.implode(',', $this->parsedBody['searchTags']).'}';
            
            $articleIds = $this->model->loadArticlesSearchTitleTagsIdsByCreatedDate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle, $searchTagsString);
        }
        else
        {
            if(isset($this->parsedBody['searchTitle']))
            {
                if(strlen($this->parsedBody['searchTitle']) > 0)
                {
                    $searchTitle = '%'.$this->parsedBody['searchTitle'].'%';
                    
                    $articleIds = $this->model->loadArticlesSearchTitleIdsByCreatedDate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle);
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

                    $articleIds = $this->model->loadArticlesSearchTagsIdsByCreatedDate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTagsString);
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
        if(isset($this->parsedBody['sortType']))
        {
            if($this->parsedBody['sortType'] == 'rate')
            {
                if(AdminStatusHandler::isAdmin($this->cookiesBody))
                {
                    if($this->parsedBody['category'] == 'EditoriallyArticles')
                    {
                        $this->loadEditoriallyArticlesByRate();
                    }
                    else if($this->parsedBody['category'] == 'EditoriallyApprovedArticles')
                    {
                        $this->loadEditoriallyApprovedArticlesByRate();
                    }
                    else if($this->parsedBody['category'] == 'AbyssArticles')
                    {
                        $this->loadAbyssArticlesByRate();
                    }
                    else if($this->parsedBody['category'] == 'ArticlesWaitingApprove')
                    {
                        $this->loadArticlesWaitingApproveByRate();
                    }
                    else if($this->parsedBody['category'] == 'ArticlesWaitingPremoderate')
                    {
                        $this->loadArticlesWaitingPremoderateByRate();
                    }
                    else if($this->parsedBody['category'] == 'ArticlesSearch')
                    {
                        $this->loadArticlesSearchByRate();
                    }
                    else
                    {
                        throw new Error(400, "Invalid category", "Invalid category");
                    }
                }
                else
                {
                    if($this->parsedBody['category'] == 'EditoriallyArticles')
                    {
                        $this->loadEditoriallyArticlesByRate();
                    }
                    else if($this->parsedBody['category'] == 'EditoriallyApprovedArticles')
                    {
                        $this->loadEditoriallyApprovedArticlesByRate();
                    }
                    else if($this->parsedBody['category'] == 'AbyssArticles')
                    {
                        $this->loadAbyssArticlesByRate();
                    }
                    else if($this->parsedBody['category'] == 'ArticlesSearch')
                    {
                        $this->loadArticlesSearchByRate();
                    }
                    else
                    {
                        throw new Error(400, "Invalid category", "Invalid category");
                    }
                }
            }
            else if($this->parsedBody['sortType'] == 'created_date')
            {
                if(AdminStatusHandler::isAdmin($this->cookiesBody))
                {
                    if($this->parsedBody['category'] == 'EditoriallyArticles')
                    {
                        $this->loadEditoriallyArticlesByCreatedDate();
                    }
                    else if($this->parsedBody['category'] == 'EditoriallyApprovedArticles')
                    {
                        $this->loadEditoriallyApprovedArticlesByCreatedDate();
                    }
                    else if($this->parsedBody['category'] == 'AbyssArticles')
                    {
                        $this->loadAbyssArticlesByCreatedDate();
                    }
                    else if($this->parsedBody['category'] == 'ArticlesWaitingApprove')
                    {
                        $this->loadArticlesWaitingApproveByCreatedDate();
                    }
                    else if($this->parsedBody['category'] == 'ArticlesWaitingPremoderate')
                    {
                        $this->loadArticlesWaitingPremoderateByCreatedDate();
                    }
                    else if($this->parsedBody['category'] == 'ArticlesSearch')
                    {
                        $this->loadArticlesSearchByCreatedDate();
                    }
                    else
                    {
                        throw new Error(400, "Invalid category", "Invalid category");
                    }
                }
                else
                {
                    if($this->parsedBody['category'] == 'EditoriallyArticles')
                    {
                        $this->loadEditoriallyArticlesByCreatedDate();
                    }
                    else if($this->parsedBody['category'] == 'EditoriallyApprovedArticles')
                    {
                        $this->loadEditoriallyApprovedArticlesByCreatedDate();
                    }
                    else if($this->parsedBody['category'] == 'AbyssArticles')
                    {
                        $this->loadAbyssArticlesByCreatedDate();
                    }
                    else if($this->parsedBody['category'] == 'ArticlesSearch')
                    {
                        $this->loadArticlesSearchByCreatedDate();
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