<?php
namespace Api\Models;

use Base\BaseModel;

class ArticlesModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    // Article search
    
    public function loadArticlesSearchTitleIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTitle = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "created_date" => "DESC",
                ],
                'premoderation_status' => 2,
                'current_title[~]' => $searchTitle,
                'approvededitorially_status[!]' => 3
            ]
        );
    }

    public function loadArticlesSearchTagsIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTags = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "created_date" => "DESC",
                ],
                'premoderation_status' => 2,
                'current_tags &&' => $searchTags,
                'approvededitorially_status[!]' => 3
            ]
        );
    }

    public function loadArticlesSearchTitleTagsIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTitle = '', $searchTags = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "created_date" => "DESC",
                ],
                'premoderation_status' => 2,
                'current_title[~]' => $searchTitle,
                'current_tags &&' => $searchTags,
                'approvededitorially_status[!]' => 3
            ]
        );
    }

    public function loadArticlesSearchTitleIdsByRate($count = 4, $lastLoaded = 0, $searchTitle = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "rating" => "DESC",
                ],
                'premoderation_status' => 2,
                'approvededitorially_status[!]' => 3,
                'current_title[~]' => $searchTitle
            ]
        );
    }

    public function loadArticlesSearchTagsIdsByRate($count = 4, $lastLoaded = 0, $searchTags = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "rating" => "DESC",
                ],
                'premoderation_status' => 2,
                'current_tags &&' => $searchTags,
                'approvededitorially_status[!]' => 3
            ]
        );
    }

    public function loadArticlesSearchTitleTagsIdsByRate($count = 4, $lastLoaded = 0, $searchTitle = '', $searchTags = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "rating" => "DESC",
                ],
                'premoderation_status' => 2,
                'current_title[~]' => $searchTitle,
                'current_tags &&' => $searchTags,
                'approvededitorially_status[!]' => 3
            ]
        );
    }

    // EditoriallyArticles

    // Created date
    public function loadEditoriallyArticlesIdsByCreatedDate($count = 4, $lastLoaded = 0)
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "created_date" => "DESC",
                ],
                'editorially_status' => 1,
            ]
        );
    }

    public function loadEditoriallyArticlesSearchTitleIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTitle = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "created_date" => "DESC",
                ],
                'editorially_status' => 1,
                'current_title[~]' => $searchTitle,
            ]
        );
    }

    public function loadEditoriallyArticlesSearchTagsIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTags = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "created_date" => "DESC",
                ],
                'editorially_status' => 1,
                'current_tags &&' => $searchTags,
            ]
        );
    }

    public function loadEditoriallyArticlesSearchTitleTagsIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTitle = '', $searchTags = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "created_date" => "DESC",
                ],
                'editorially_status' => 1,
                'current_title[~]' => $searchTitle,
                'current_tags &&' => $searchTags,
            ]
        );
    }
    
    // Rate

    public function loadEditoriallyArticlesIdsByRate($count = 4, $lastLoaded = 0)
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "rating" => "DESC",
                ],
                'editorially_status' => 1,
            ]
        );
    }

    public function loadEditoriallyArticlesSearchTitleIdsByRate($count = 4, $lastLoaded = 0, $searchTitle = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "rating" => "DESC",
                ],
                'editorially_status' => 1,
                'current_title[~]' => $searchTitle,
            ]
        );
    }

    public function loadEditoriallyArticlesSearchTagsIdsByRate($count = 4, $lastLoaded = 0, $searchTags = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "rating" => "DESC",
                ],
                'editorially_status' => 1,
                'current_tags &&' => $searchTags,
            ]
        );
    }

    public function loadEditoriallyArticlesSearchTitleTagsIdsByRate($count = 4, $lastLoaded = 0, $searchTitle = '', $searchTags = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "rating" => "DESC",
                ],
                'editorially_status' => 1,
                'current_title[~]' => $searchTitle,
                'current_tags &&' => $searchTags,
            ]
        );
    }

    // EditoriallyApprovedArticles

    // Created Date

    public function loadEditoriallyApprovedArticlesIdsByCreatedDate($count = 4, $lastLoaded = 0)
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "created_date" => "DESC",
                ],
                'approvededitorially_status' => 2,
                'editorially_status[!]' => 1,
            ]
        );
    }

    public function loadEditoriallyApprovedArticlesSearchTitleIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTitle = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "created_date" => "DESC",
                ],
                'approvededitorially_status' => 2,
                'editorially_status[!]' => 1,
                'current_title[~]' => $searchTitle,
            ]
        );
    }

    public function loadEditoriallyApprovedArticlesSearchTagsIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTags = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "created_date" => "DESC",
                ],
                'approvededitorially_status' => 2,
                'editorially_status[!]' => 1,
                'current_tags &&' => $searchTags,
            ]
        );
    }
    
    public function loadEditoriallyApprovedArticlesSearchTitleTagsIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTitle = '', $searchTags = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "created_date" => "DESC",
                ],
                'approvededitorially_status' => 2,
                'editorially_status[!]' => 1,
                'current_title[~]' => $searchTitle,
                'current_tags &&' => $searchTags,
            ]
        );
    }
    
    // Rate

    public function loadEditoriallyApprovedArticlesIdsByRate($count = 4, $lastLoaded = 0)
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "rating" => "DESC",
                ],
                'approvededitorially_status' => 2,
                'editorially_status[!]' => 1,
            ]
        );
    }
    
    public function loadEditoriallyApprovedArticlesSearchTitleIdsByRate($count = 4, $lastLoaded = 0, $searchTitle = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "rating" => "DESC",
                ],
                'approvededitorially_status' => 2,
                'editorially_status[!]' => 1,
                'current_title[~]' => $searchTitle,
            ]
        );
    }

    public function loadEditoriallyApprovedArticlesSearchTagsIdsByRate($count = 4, $lastLoaded = 0, $searchTags = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "rating" => "DESC",
                ],
                'approvededitorially_status' => 2,
                'editorially_status[!]' => 1,
                'current_tags &&' => $searchTags,
            ]
        );
    }

    public function loadEditoriallyApprovedArticlesSearchTitleTagsIdsByRate($count = 4, $lastLoaded = 0, $searchTitle = '', $searchTags = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "rating" => "DESC",
                ],
                'approvededitorially_status' => 2,
                'editorially_status[!]' => 1,
                'current_title[~]' => $searchTitle,
                'current_tags &&' => $searchTags,
            ]
        );
    }
    

    // AbyssArticles

    // Created Date

    public function loadAbyssArticlesIdsByCreatedDate($count = 4, $lastLoaded = 0)
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "created_date" => "DESC",
                ],
                'premoderation_status' => 2,
                'editorially_status[!]' => 1,
                'approvededitorially_status[!]' => 2,
                'approvededitorially_status[!]' => 3,
            ]
        );
    }

    public function loadAbyssArticlesSearchTitleIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTitle = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "created_date" => "DESC",
                ],
                'premoderation_status' => 2,
                'editorially_status[!]' => 1,
                'approvededitorially_status[!]' => 2,
                'approvededitorially_status[!]' => 3,
                'current_title[~]' => $searchTitle,
            ]
        );
    }

    public function loadAbyssArticlesSearchTagsIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTags = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "created_date" => "DESC",
                ],
                'premoderation_status' => 2,
                'editorially_status[!]' => 1,
                'approvededitorially_status[!]' => 2,
                'approvededitorially_status[!]' => 3,
                'current_tags &&' => $searchTags,
            ]
        );
    }

    public function loadAbyssArticlesSearchTitleTagsIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTitle = '', $searchTags = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "created_date" => "DESC",
                ],
                'premoderation_status' => 2,
                'editorially_status[!]' => 1,
                'approvededitorially_status[!]' => 2,
                'approvededitorially_status[!]' => 3,
                'current_title[~]' => $searchTitle,
                'current_tags &&' => $searchTags,
            ]
        );
    }
    
    // Rate

    public function loadAbyssArticlesIdsByRate($count = 4, $lastLoaded = 0)
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "rating" => "DESC",
                ],
                'premoderation_status' => 2,
                'editorially_status[!]' => 1,
                'approvededitorially_status[!]' => 2,
                'approvededitorially_status[!]' => 3,
            ]
        );
    }

    public function loadAbyssArticleSearchTitleIdsByRate($count = 4, $lastLoaded = 0, $searchTitle = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "rating" => "DESC",
                ],
                'premoderation_status' => 2,
                'editorially_status[!]' => 1,
                'approvededitorially_status[!]' => 2,
                'approvededitorially_status[!]' => 3,
                'current_title[~]' => $searchTitle,
            ]
        );
    }

    public function loadAbyssArticlesSearchTagsIdsByRate($count = 4, $lastLoaded = 0, $searchTags = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "rating" => "DESC",
                ],
                'premoderation_status' => 2,
                'editorially_status[!]' => 1,
                'approvededitorially_status[!]' => 2,
                'approvededitorially_status[!]' => 3,
                'current_tags &&' => $searchTags,
            ]
        );
    }

    public function loadAbyssArticlesSearchTitleTagsIdsByRate($count = 4, $lastLoaded = 0, $searchTitle = '', $searchTags = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "rating" => "DESC",
                ],
                'premoderation_status' => 2,
                'editorially_status[!]' => 1,
                'approvededitorially_status[!]' => 2,
                'approvededitorially_status[!]' => 3,
                'current_title[~]' => $searchTitle,
                'current_tags &&' => $searchTags,
            ]
        );
    }

    // ArticlesWaitingApprove

    // Created Date

    public function loadArticlesWaitingApproveIdsByCreatedDate($count = 4, $lastLoaded = 0)
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "created_date" => "DESC",
                ],
                'approvededitorially_status' => 1,
            ]
        );
    }

    public function loadArticlesWaitingApproveSearchTitleIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTitle = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "created_date" => "DESC",
                ],
                'approvededitorially_status' => 1,
                'current_title[~]' => $searchTitle,
            ]
        );
    }

    public function loadArticlesWaitingApproveSearchTagsIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTags = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "created_date" => "DESC",
                ],
                'approvededitorially_status' => 1,
                'current_tags &&' => $searchTags,
            ]
        );
    }

    public function loadArticlesWaitingApproveSearchTitleTagsIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTitle = '', $searchTags = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "created_date" => "DESC",
                ],
                'approvededitorially_status' => 1,
                'current_title[~]' => $searchTitle,
                'current_tags &&' => $searchTags,
            ]
        );
    }
    
    // Rate

    public function loadArticlesWaitingApproveSearchIdsByRate($count = 4, $lastLoaded = 0)
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "rating" => "DESC",
                ],
                'approvededitorially_status' => 1,
            ]
        );
    }

    public function loadArticlesWaitingApproveSearchTitleIdsByRate($count = 4, $lastLoaded = 0, $searchTitle = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "rating" => "DESC",
                ],
                'approvededitorially_status' => 1,
                'current_title[~]' => $searchTitle,
            ]
        );
    }

    public function loadArticlesWaitingApproveSearchTagsIdsByRate($count = 4, $lastLoaded = 0, $searchTags = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "rating" => "DESC",
                ],
                'approvededitorially_status' => 1,
                'current_tags &&' => $searchTags,
            ]
        );
    }

    public function loadArticlesWaitingApproveSearchTitleTagsIdsByRate($count = 4, $lastLoaded = 0, $searchTitle = '', $searchTags = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "rating" => "DESC",
                ],
                'approvededitorially_status' => 1,
                'current_title[~]' => $searchTitle,
                'current_tags &&' => $searchTags,
            ]
        );
    }

    // ArticlesWaitingPremoderate

    // Created Date

    public function loadArticlesWaitingPremoderateIdsByCreatedDate($count = 4, $lastLoaded = 0)
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "created_date" => "DESC",
                ],
                'premoderation_status' => 1,
            ]
        );
    }

    public function loadArticlesWaitingPremoderateSearchTitleIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTitle = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "created_date" => "DESC",
                ],
                'premoderation_status' => 1,
                'current_title[~]' => $searchTitle,
            ]
        );
    }

    public function loadArticlesWaitingPremoderateSearchTagsIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTags = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "created_date" => "DESC",
                ],
                'premoderation_status' => 1,
                'current_tags &&' => $searchTags,
            ]
        );
    }

    public function loadArticlesWaitingPremoderateSearchTitleTagsIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTitle = '', $searchTags = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "created_date" => "DESC",
                ],
                'premoderation_status' => 1,
                'current_title[~]' => $searchTitle,
                'current_tags &&' => $searchTags,
            ]
        );
    }
    
    // Rate

    public function loadArticlesWaitingPremoderateIdsByRate($count = 4, $lastLoaded = 0)
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "rating" => "DESC",
                ],
                'premoderation_status' => 1,
            ]
        );
    }

    public function loadArticlesWaitingPremoderateSearchTitleIdsByRate($count = 4, $lastLoaded = 0, $searchTitle = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "rating" => "DESC",
                ],
                'premoderation_status' => 1,
                'current_title[~]' => $searchTitle,
            ]
        );
    }

    public function loadArticlesWaitingPremoderateSearchTagsIdsByRate($count = 4, $lastLoaded = 0, $searchTags = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "rating" => "DESC",
                ],
                'premoderation_status' => 1,
                'current_tags &&' => $searchTags,
            ]
        );
    }

    public function loadArticlesWaitingPremoderateSearchTitleTagsIdsByRate($count = 4, $lastLoaded = 0, $searchTitle = '', $searchTags = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "rating" => "DESC",
                ],
                'premoderation_status' => 1,
                'current_title[~]' => $searchTitle,
                'current_tags &&' => $searchTags,
            ]
        );
    }

    // Load articles from article ids

    // Articles search 

    public function loadArticlesSearch($articleIds)
    {
        if($articleIds)
        {
            if(is_array($articleIds))
            {
                if(count($articleIds) > 0)
                {
                    $articles = [];
                    foreach ($articleIds as &$articleId) 
                    {
                        $articleVersions = $this->database->select(
                            'articles', 
                            [
                                'version_id', 
                                'title', 
                                'text', 
                                'tags', 
                                'created_date', 
                                'editorially_status', 
                                'premoderation_status', 
                                'approvededitorially_status'
                            ],
                            [
                                'id' => $articleId, 
                                'premoderation_status' => 2,
                                'approvededitorially_status[!]' => 2,
                                'approvededitorially_status[!]' => 3
                            ]
                        );
                        
                        foreach ($articleVersions as $versionNum => $versionInfo) 
                        {
                            if ($versionInfo['tags'] != null) 
                            {
                                $tagsString = substr(substr($versionInfo["tags"], 1), 0, -1);
                                $articleVersions[$versionNum]['tags'] = explode(',', $tagsString);
                            }
                        }
                        
                        $article = [
                            'id' => $articleId,
                            'versions' => $articleVersions,
                            'statistics' => $this->database->get('statistics', ['created_date','rating', 'comments'], ['article_id' => $articleId]),
                            'view_code' => $this->database->get('codes', 'view_code', ['article_id' => $articleId])
                        ];
                        array_push($articles, $article);
                    }
                    return $articles;
                }
                else
                {
                    return null;
                }
            }
            else
            {
                return null;
            }
        }
        else
        {
            return null;
        }
    }

    // EditoriallyArticles

    public function loadEditoriallyArticles($articleIds)
    {
        if($articleIds)
        {
            if(is_array($articleIds))
            {
                if(count($articleIds) > 0)
                {
                    $articles = [];
                    foreach ($articleIds as &$articleId) 
                    {
                        $articleVersions = $this->database->select(
                            'articles', 
                            [
                                'version_id', 
                                'title', 
                                'text',
                                'tags',
                                'created_date',
                                'editorially_status',
                                'premoderation_status',
                                'approvededitorially_status'
                            ], 
                            [
                                'id' => $articleId, 
                                'editorially_status' => 1
                            ]
                        );

                        foreach ($articleVersions as $versionNum => $versionInfo) 
                        {
                            if ($versionInfo['tags'] != null) 
                            {
                                $tagsString = substr(substr($versionInfo["tags"], 1), 0, -1);
                                $articleVersions[$versionNum]['tags'] = explode(',', $tagsString);
                            }
                        }
                        
                        $article = [
                            'id' => $articleId,
                            'versions' => $articleVersions,
                            'statistics' => $this->database->get('statistics', ['created_date','rating', 'comments'], ['article_id' => $articleId]),
                            'view_code' => $this->database->get('codes', 'view_code', ['article_id' => $articleId])
                        ];
                        array_push($articles, $article);
                    }
                    return $articles;
                }
                else
                {
                    return null;
                }
            }
            else
            {
                return null;
            }
        }
        else
        {
            return null;
        }
    }

    // EditoriallyApprovedArticles

    public function loadEditoriallyApprovedArticles($articleIds)
    {
        if($articleIds)
        {
            if(is_array($articleIds))
            {
                if(count($articleIds) > 0)
                {
                    $articles = [];
                    foreach ($articleIds as &$articleId) 
                    {
                        $articleVersions = $this->database->select(
                            'articles', 
                            [
                                'version_id', 
                                'title', 
                                'text', 
                                'tags',
                                'created_date',
                                'editorially_status',
                                'premoderation_status',
                                'approvededitorially_status'
                            ],
                            [
                                'id' => $articleId,
                                'approvededitorially_status' => 2,
                                'editorially_status[!]' => 1,
                            ]
                        );

                        foreach ($articleVersions as $versionNum => $versionInfo) 
                        {
                            if ($versionInfo['tags'] != null) 
                            {
                                $tagsString = substr(substr($versionInfo["tags"], 1), 0, -1);
                                $articleVersions[$versionNum]['tags'] = explode(',', $tagsString);
                            }
                        }
                        
                        $article = [
                            'id' => $articleId,
                            'versions' => $articleVersions,
                            'statistics' => $this->database->get('statistics', ['created_date','rating', 'comments'], ['article_id' => $articleId]),
                            'view_code' => $this->database->get('codes', 'view_code', ['article_id' => $articleId])
                        ];
                        array_push($articles, $article);
                    }
                    return $articles;
                }
                else
                {
                    return null;
                }
            }
            else
            {
                return null;
            }
        }
        else
        {
            return null;
        }
    }

    // AbyssArticles

    public function loadAbyssArticles($articleIds)
    {
        if($articleIds)
        {
            if(is_array($articleIds))
            {
                if(count($articleIds) > 0)
                {
                    $articles = [];
                    foreach ($articleIds as &$articleId) 
                    {
                        $articleVersions = $this->database->select(
                            'articles', 
                            [
                                'version_id', 
                                'title', 
                                'text', 
                                'tags', 
                                'created_date', 
                                'editorially_status', 
                                'premoderation_status', 
                                'approvededitorially_status'
                            ],
                            [
                                'id' => $articleId, 
                                'premoderation_status' => 2,
                                'editorially_status[!]' => 1,
                                'approvededitorially_status[!]' => 2,
                                'approvededitorially_status[!]' => 3
                            ]
                        );

                        foreach ($articleVersions as $versionNum => $versionInfo) 
                        {
                            if ($versionInfo['tags'] != null) 
                            {
                                $tagsString = substr(substr($versionInfo["tags"], 1), 0, -1);
                                $articleVersions[$versionNum]['tags'] = explode(',', $tagsString);
                            }
                        }
                        
                        $article = [
                            'id' => $articleId,
                            'versions' => $articleVersions,
                            'statistics' => $this->database->get('statistics', ['created_date','rating', 'comments'], ['article_id' => $articleId]),
                            'view_code' => $this->database->get('codes', 'view_code', ['article_id' => $articleId])
                        ];
                        array_push($articles, $article);
                    }
                    return $articles;
                }
                else
                {
                    return null;
                }
            }
            else
            {
                return null;
            }
        }
        else
        {
            return null;
        }
    }

    // ArticlesWaitingApprove

    public function loadArticlesWaitingApprove($articleIds)
    {
        if($articleIds)
        {
            if(is_array($articleIds))
            {
                if(count($articleIds) > 0)
                {
                    $articles = [];
                    foreach ($articleIds as &$articleId) 
                    {
                        $articleVersions =  $this->database->select(
                            'articles', 
                            [
                                'version_id', 
                                'title',
                                'text', 
                                'tags', 
                                'created_date', 
                                'editorially_status', 
                                'premoderation_status', 
                                'approvededitorially_status'
                            ], 
                            [
                                'id' => $articleId, 
                                'premoderation_status' => 2, 
                                'approvededitorially_status' => 1
                            ]
                        );
                        
                        foreach ($articleVersions as $versionNum => $versionInfo) 
                        {
                            if ($versionInfo['tags'] != null) 
                            {
                                $tagsString = substr(substr($versionInfo["tags"], 1), 0, -1);
                                $articleVersions[$versionNum]['tags'] = explode(',', $tagsString);
                            }
                        }

                        $article = [
                            'id' => $articleId,
                            'versions' => $articleVersions,
                            'statistics' => $this->database->get('statistics', ['created_date','rating', 'comments'], ['article_id' => $articleId]),
                            'view_code' => $this->database->get('codes', 'view_code', ['article_id' => $articleId])
                        ];
                        array_push($articles, $article);
                    }
                    return $articles;
                }
                else
                {
                    return null;
                }
            }
            else
            {
                return null;
            }
        }
        else
        {
            return null;
        }
    }

    // ArticlesWaitingPremoderate

    public function loadArticlesWaitingPremoderate($articleIds)
    {
        if($articleIds)
        {
            if(is_array($articleIds))
            {
                if(count($articleIds) > 0)
                {
                    $articles = [];
                    foreach ($articleIds as &$articleId) 
                    {
                        $articleVersions = $this->database->select(
                            'articles', 
                            [
                                'version_id', 
                                'title',
                                'text', 
                                'tags', 
                                'created_date', 
                                'editorially_status', 
                                'premoderation_status', 
                                'approvededitorially_status'
                            ], 
                            [
                                'id' => $articleId, 
                                'premoderation_status' => 1
                            ]
                        );

                        foreach ($articleVersions as $versionNum => $versionInfo) 
                        {
                            if ($versionInfo['tags'] != null) 
                            {
                                $tagsString = substr(substr($versionInfo["tags"], 1), 0, -1);
                                $articleVersions[$versionNum]['tags'] = explode(',', $tagsString);
                            }
                        }
                        
                        $article = [
                            'id' => $articleId,
                            'versions' => $articleVersions,
                            'statistics' => $this->database->get('statistics', ['created_date','rating', 'comments'], ['article_id' => $articleId]),
                            'view_code' => $this->database->get('codes', 'view_code', ['article_id' => $articleId])
                        ];
                        array_push($articles, $article);
                    }
                    return $articles;
                }
                else
                {
                    return null;
                }
            }
            else
            {
                return null;
            }
        }
        else
        {
            return null;
        }
    }
}