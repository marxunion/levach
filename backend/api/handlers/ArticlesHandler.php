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
        $parsedBody = $this->request->getQueryParams();
        if(is_array($parsedBody))
        {
            $this->parsedBody = $parsedBody;

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

            $this->model = new ArticlesModel();
        }
        else
        {
            throw new Error(400, "Invalid request body", "Invalid request body");
        }
    }

    private function loadEditoriallyArticlesByPopularity()
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

            $articleIds = $this->model->loadEditoriallyArticlesSearchTitleTagsIdsByPopularity($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle, $searchTagsString);
        }
        else
        {
            if(isset($this->parsedBody['searchTitle']))
            {
                if(strlen($this->parsedBody['searchTitle']) > 0)
                {
                    $searchTitle = '%'.$this->parsedBody['searchTitle'].'%';
                    
                    $articleIds = $this->model->loadEditoriallyArticlesSearchTitleIdsByPopularity($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle);
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
                    $articleIds = $this->model->loadEditoriallyArticlesSearchTagsIdsByPopularity($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTagsString);
                }
                else
                {
                    throw new Error(400, "Invalid search tags", "Invalid search tags");
                }
            }
            else
            {
                $articleIds = $this->model->loadEditoriallyArticlesIdsByPopularity($this->parsedBody['count'], $this->parsedBody['lastLoaded']);
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

    private function loadEditoriallyApprovedArticlesByPopularity()
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

            $articleIds = $this->model->loadEditoriallyApprovedArticlesSearchTitleTagsIdsByPopularity($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle, $searchTagsString);
        }
        else
        {
            if(isset($this->parsedBody['searchTitle']))
            {
                if(strlen($this->parsedBody['searchTitle']) > 0)
                {
                    $searchTitle = '%'.$this->parsedBody['searchTitle'].'%';
                    
                    $articleIds = $this->model->loadEditoriallyApprovedArticlesSearchTitleIdsByPopularity($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle);
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

                    $articleIds = $this->model->loadEditoriallyApprovedArticlesSearchTagsIdsByPopularity($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTagsString);
                }
                else
                {
                    throw new Error(400, "Invalid search tags", "Invalid search tags");
                }
            }
            else
            {
                $articleIds = $this->model->loadEditoriallyApprovedArticlesIdsByPopularity($this->parsedBody['count'], $this->parsedBody['lastLoaded']);
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

    private function loadAbyssArticlesByPopularity()
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

            $articleIds = $this->model->loadAbyssArticlesSearchTitleTagsIdsByPopularity($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle, $searchTagsString);
        }
        else
        {
            if(isset($this->parsedBody['searchTitle']))
            {
                if(strlen($this->parsedBody['searchTitle']) > 0)
                {
                    $searchTitle = '%'.$this->parsedBody['searchTitle'].'%';
                    
                    $articleIds = $this->model->loadAbyssArticlesSearchTitleIdsByPopularity($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle);
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

                    $articleIds = $this->model->loadAbyssArticlesSearchTagsIdsByPopularity($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTagsString);
                }
                else
                {
                    throw new Error(400, "Invalid search tags", "Invalid search tags");
                }
            }
            else
            {
                $articleIds = $this->model->loadAbyssArticlesIdsByPopularity($this->parsedBody['count'], $this->parsedBody['lastLoaded']);
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

    private function loadArticlesWaitingApproveByPopularity()
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

            $articleIds = $this->model->loadArticlesWaitingApproveSearchTitleTagsIdsByPopularity($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle, $searchTagsString);
        }
        else
        {
            if(isset($this->parsedBody['searchTitle']))
            {
                if(strlen($this->parsedBody['searchTitle']) > 0)
                {
                    $searchTitle = '%'.$this->parsedBody['searchTitle'].'%';
                    
                    $articleIds = $this->model->loadArticlesWaitingApproveSearchTitleIdsByPopularity($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle);
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

                    $articleIds = $this->model->loadArticlesWaitingApproveSearchTagsIdsByPopularity($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTagsString);
                }
                else
                {
                    throw new Error(400, "Invalid search tags", "Invalid search tags");
                }
            }
            else
            {
                $articleIds = $this->model->loadArticlesWaitingApproveIdsByPopularity($this->parsedBody['count'], $this->parsedBody['lastLoaded']);
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

    private function loadArticlesWaitingPremoderateByPopularity()
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

            $articleIds = $this->model->loadArticlesWaitingPremoderateSearchTitleTagsIdsByPopularity($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle, $searchTagsString);
        }
        else
        {
            if(isset($this->parsedBody['searchTitle']))
            {
                if(strlen($this->parsedBody['searchTitle']) > 0)
                {
                    $searchTitle = '%'.$this->parsedBody['searchTitle'].'%';
                    
                    $articleIds = $this->model->loadArticlesWaitingPremoderateSearchTitleIdsByPopularity($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle);
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

                    $articleIds = $this->model->loadArticlesWaitingPremoderateSearchTagsIdsByPopularity($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTagsString);
                }
                else
                {
                    throw new Error(400, "Invalid search tags", "Invalid search tags");
                }
            }
            else
            {
                $articleIds = $this->model->loadArticlesWaitingPremoderateIdsByPopularity($this->parsedBody['count'], $this->parsedBody['lastLoaded']);
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

    private function loadArticlesSearchByPopularity()
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
            
            $articleIds = $this->model->loadArticlesSearchTitleTagsIdsByPopularity($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle, $searchTagsString);
        }
        else
        {
            if(isset($this->parsedBody['searchTitle']))
            {
                if(strlen($this->parsedBody['searchTitle']) > 0)
                {
                    $searchTitle = '%'.$this->parsedBody['searchTitle'].'%';
                    
                    $articleIds = $this->model->loadArticlesSearchTitleIdsByPopularity($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle);
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

                    $articleIds = $this->model->loadArticlesSearchTagsIdsByPopularity($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTagsString);
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
        if(!empty($this->parsedBody['searchTitle']) && isset($this->parsedBody['searchTags']))
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
        if($this->parsedBody['sortType'] == 'rate')
        {
            if(AdminStatusHandler::isAdmin($this->cookiesBody))
            {
                if($this->parsedBody['category'] == 'EditoriallyArticles')
                {
                    $this->loadEditoriallyArticlesByPopularity();
                }
                else if($this->parsedBody['category'] == 'EditoriallyApprovedArticles')
                {
                    $this->loadEditoriallyApprovedArticlesByPopularity();
                }
                else if($this->parsedBody['category'] == 'AbyssArticles')
                {
                    $this->loadAbyssArticlesByPopularity();
                }
                else if($this->parsedBody['category'] == 'ArticlesWaitingApprove')
                {
                    $this->loadArticlesWaitingApproveByPopularity();
                }
                else if($this->parsedBody['category'] == 'ArticlesWaitingPremoderate')
                {
                    $this->loadArticlesWaitingPremoderateByPopularity();
                }
                else if($this->parsedBody['category'] == 'ArticlesSearch')
                {
                    $this->loadArticlesSearchByPopularity();    
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
                    $this->loadEditoriallyArticlesByPopularity();
                }
                else if($this->parsedBody['category'] == 'EditoriallyApprovedArticles')
                {
                    $this->loadEditoriallyApprovedArticlesByPopularity();
                }
                else if($this->parsedBody['category'] == 'AbyssArticles')
                {
                    $this->loadAbyssArticlesByPopularity();
                }
                else if($this->parsedBody['category'] == 'ArticlesSearch')
                {
                    $this->loadArticlesSearchByPopularity();
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
}