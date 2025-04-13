<?php
namespace Routes\Api\Articles;

use Core\Error;

use Base\BaseControllerRoute;

use Api\Models\ArticlesModel;
use Api\Handlers\AdminStatusHandler;

class MainContoller extends BaseControllerRoute
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
                $this->parsedBody['sortType'] = 'popularity';
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

    private function loadEditoriallyArticlesByLastEditDate()
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

            $articleIds = $this->model->loadEditoriallyArticlesSearchTitleTagsIdsByLastEditDate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle, $searchTagsString);
        }
        else
        {
            if(isset($this->parsedBody['searchTitle']))
            {
                if(strlen($this->parsedBody['searchTitle']) > 0)
                {
                    $searchTitle = '%'.$this->parsedBody['searchTitle'].'%';
                    
                    $articleIds = $this->model->loadEditoriallyArticlesSearchTitleIdsByLastEditDate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle);
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

                    $articleIds = $this->model->loadEditoriallyArticlesSearchTagsIdsByLastEditDate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTagsString);
                }
                else
                {
                    throw new Error(400, "Invalid search tags", "Invalid search tags");
                }
            }
            else
            {
                $articleIds = $this->model->loadEditoriallyArticlesIdsByLastEditDate($this->parsedBody['count'], $this->parsedBody['lastLoaded']);
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

    private function loadEditoriallyApprovedArticlesByLastEditDate()
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

            $articleIds = $this->model->loadEditoriallyApprovedArticlesSearchTitleTagsIdsByLastEditDate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle, $searchTagsString);
        }
        else
        {
            if(isset($this->parsedBody['searchTitle']))
            {
                if(strlen($this->parsedBody['searchTitle']) > 0)
                {
                    $searchTitle = '%'.$this->parsedBody['searchTitle'].'%';
                    
                    $articleIds = $this->model->loadEditoriallyApprovedArticlesSearchTitleIdsByLastEditDate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle);
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

                    $articleIds = $this->model->loadEditoriallyApprovedArticlesSearchTagsIdsByLastEditDate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTagsString);
                }
                else
                {
                    throw new Error(400, "Invalid search tags", "Invalid search tags");
                }
            }
            else
            {
                $articleIds = $this->model->loadEditoriallyApprovedArticlesIdsByLastEditDate($this->parsedBody['count'], $this->parsedBody['lastLoaded']);
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

    private function loadAbyssArticlesByLastEditDate()
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

            $articleIds = $this->model->loadAbyssArticlesSearchTitleTagsIdsByLastEditDate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle, $searchTagsString);
        }
        else
        {
            if(isset($this->parsedBody['searchTitle']))
            {
                if(strlen($this->parsedBody['searchTitle']) > 0)
                {
                    $searchTitle = '%'.$this->parsedBody['searchTitle'].'%';
                    
                    $articleIds = $this->model->loadAbyssArticlesSearchTitleIdsByLastEditDate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle);
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

                    $articleIds = $this->model->loadAbyssArticlesSearchTagsIdsByLastEditDate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTagsString);
                }
                else
                {
                    throw new Error(400, "Invalid search tags", "Invalid search tags");
                }
            }
            else
            {
                $articleIds = $this->model->loadAbyssArticlesIdsByLastEditDate($this->parsedBody['count'], $this->parsedBody['lastLoaded']);
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

    private function loadArticlesWaitingApproveByLastEditDate()
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

            $articleIds = $this->model->loadArticlesWaitingApproveSearchTitleTagsIdsByLastEditDate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle, $searchTagsString);
        }
        else
        {
            if(isset($this->parsedBody['searchTitle']))
            {
                if(strlen($this->parsedBody['searchTitle']) > 0)
                {
                    $searchTitle = '%'.$this->parsedBody['searchTitle'].'%';
                    
                    $articleIds = $this->model->loadArticlesWaitingApproveSearchTitleIdsByLastEditDate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle);
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

                    $articleIds = $this->model->loadArticlesWaitingApproveSearchTagsIdsByLastEditDate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTagsString);
                }
                else
                {
                    throw new Error(400, "Invalid search tags", "Invalid search tags");
                }
            }
            else
            {
                $articleIds = $this->model->loadArticlesWaitingApproveIdsByLastEditDate($this->parsedBody['count'], $this->parsedBody['lastLoaded']);
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

    private function loadArticlesWaitingPremoderateByLastEditDate()
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

            $articleIds = $this->model->loadArticlesWaitingPremoderateSearchTitleTagsIdsByLastEditDate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle, $searchTagsString);
        }
        else
        {
            if(isset($this->parsedBody['searchTitle']))
            {
                if(strlen($this->parsedBody['searchTitle']) > 0)
                {
                    $searchTitle = '%'.$this->parsedBody['searchTitle'].'%';
                    
                    $articleIds = $this->model->loadArticlesWaitingPremoderateSearchTitleIdsByLastEditDate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle);
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

                    $articleIds = $this->model->loadArticlesWaitingPremoderateSearchTagsIdsByLastEditDate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTagsString);
                }
                else
                {
                    throw new Error(400, "Invalid search tags", "Invalid search tags");
                }
            }
            else
            {
                $articleIds = $this->model->loadArticlesWaitingPremoderateIdsByLastEditDate($this->parsedBody['count'], $this->parsedBody['lastLoaded']);
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

    private function loadArticlesSearchByLastEditDate()
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
            
            $articleIds = $this->model->loadArticlesSearchTitleTagsIdsByLastEditDate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle, $searchTagsString);
        }
        else
        {
            if(isset($this->parsedBody['searchTitle']))
            {
                if(strlen($this->parsedBody['searchTitle']) > 0)
                {
                    $searchTitle = '%'.$this->parsedBody['searchTitle'].'%';
                    
                    $articleIds = $this->model->loadArticlesSearchTitleIdsByLastEditDate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTitle);
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

                    $articleIds = $this->model->loadArticlesSearchTagsIdsByLastEditDate($this->parsedBody['count'], $this->parsedBody['lastLoaded'], $searchTagsString);
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
        if($this->parsedBody['sortType'] == 'popularity')
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
        else if($this->parsedBody['sortType'] == 'last_edit_date')
        {
            if(AdminStatusHandler::isAdmin($this->cookiesBody))
            {
                if($this->parsedBody['category'] == 'EditoriallyArticles')
                {
                    $this->loadEditoriallyArticlesByLastEditDate();
                }
                else if($this->parsedBody['category'] == 'EditoriallyApprovedArticles')
                {
                    $this->loadEditoriallyApprovedArticlesByLastEditDate();
                }
                else if($this->parsedBody['category'] == 'AbyssArticles')
                {
                    $this->loadAbyssArticlesByLastEditDate();
                }
                else if($this->parsedBody['category'] == 'ArticlesWaitingApprove')
                {
                    $this->loadArticlesWaitingApproveByLastEditDate();
                }
                else if($this->parsedBody['category'] == 'ArticlesWaitingPremoderate')
                {
                    $this->loadArticlesWaitingPremoderateByLastEditDate();
                }
                else if($this->parsedBody['category'] == 'ArticlesSearch')
                {
                    $this->loadArticlesSearchByLastEditDate();
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
                    $this->loadEditoriallyArticlesByLastEditDate();
                }
                else if($this->parsedBody['category'] == 'EditoriallyApprovedArticles')
                {
                    $this->loadEditoriallyApprovedArticlesByLastEditDate();
                }
                else if($this->parsedBody['category'] == 'AbyssArticles')
                {
                    $this->loadAbyssArticlesByLastEditDate();
                }
                else if($this->parsedBody['category'] == 'ArticlesSearch')
                {
                    $this->loadArticlesSearchByLastEditDate();
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