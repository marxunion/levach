<?php
namespace Api\Models;

use Base\BaseModel;

class ArticlesModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    # Article search
    public function loadArticlesSearchIdsByCreatedDateWithTags($count = 4, $lastLoaded = 0, $searchTitle = '', $searchTags = '')
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

    public function loadArticlesSearchIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTitle = '')
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

    public function loadArticlesSearchIdsByRateWithTags($count = 4, $lastLoaded = 0, $searchTitle = '', $searchTags = '')
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

    public function loadArticlesSearchIdsByRate($count = 4, $lastLoaded = 0, $searchTitle = '')
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
    



    # EditoriallyArticles
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

    public function loadEditoriallyArticlesIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTitle = '')
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

    public function loadEditoriallyArticlesIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTitle = '', $searchTags = '')
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

    public function loadEditoriallyArticlesIdsByRate($count = 4, $lastLoaded = 0, $searchTitle = '')
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

    public function loadEditoriallyArticlesIdsByRate($count = 4, $lastLoaded = 0, $searchTitle = '', $searchTags = '')
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

    # EditoriallyApprovedArticles

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

    public function loadEditoriallyApprovedArticlesSearchIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTitle = '')
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

    public function loadEditoriallyApprovedArticlesSearchIdsByCreatedDateWithTags($count = 4, $lastLoaded = 0, $searchTitle = '', $searchTags = '')
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
    
    public function loadEditoriallyApprovedArticlesSearchIdsByRate($count = 4, $lastLoaded = 0, $searchTitle = '')
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

    public function loadEditoriallyApprovedArticlesSearchIdsByRateWithTags($count = 4, $lastLoaded = 0, $searchTitle = '', $searchTags = '')
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
    

    # AbyssArticles

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

    public function loadAbyssArticlesSearchIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTitle = '')
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

    public function loadAbyssArticlesSearchIdsByCreatedDateWithTags($count = 4, $lastLoaded = 0, $searchTitle = '', $searchTags = '')
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

    public function loadAbyssArticleSearchIdsByRate($count = 4, $lastLoaded = 0, $searchTitle = '')
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

    public function loadAbyssArticlesSearchIdsByRateWithTags($count = 4, $lastLoaded = 0, $searchTitle = '', $searchTags = '')
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

    # ArticlesWaitingApprove

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

    public function loadArticlesWaitingApproveSearchIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTitle = '')
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

    public function loadArticlesWaitingApproveSearchIdsByCreatedDateWithTags($count = 4, $lastLoaded = 0, $searchTitle = '', $searchTags = '')
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

    public function loadArticlesWaitingApproveSearchIdsByRate($count = 4, $lastLoaded = 0, $searchTitle = '')
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

    public function loadArticlesWaitingApproveSearchIdsByRateWithTags($count = 4, $lastLoaded = 0, $searchTitle = '', $searchTags = '')
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

    # ArticlesWaitingPremoderate

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

    public function loadArticlesWaitingPremoderateSearchIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTitle = '')
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

    public function loadArticlesWaitingPremoderateSearchIdsByCreatedDateWithTags($count = 4, $lastLoaded = 0, $searchTitle = '', $searchTags = '')
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

    public function loadArticlesWaitingPremoderateSearchIdsByRate($count = 4, $lastLoaded = 0, $searchTitle = '')
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

    public function loadArticlesWaitingPremoderateSearchIdsByRateWithTags($count = 4, $lastLoaded = 0, $searchTitle = '', $searchTags = '')
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

    # Load articles from article ids

    # Articles search 

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

    # EditoriallyArticles

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

    # EditoriallyApprovedArticles

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

    # AbyssArticles

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

    # ArticlesWaitingApprove

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

    # ArticlesWaitingPremoderate

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