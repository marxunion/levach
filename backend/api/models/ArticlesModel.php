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
    public function loadArticleSearchIdsByCreatedDateWithTags($count = 4, $lastLoaded = 0, $searchTitle = '', $searchTags = '')
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

    public function loadArticleSearchIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTitle = '')
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

    public function loadArticleSearchIdsByRateWithTags($count = 4, $lastLoaded = 0, $searchTitle = '', $searchTags = '')
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

    public function loadArticleSearchIdsByRate($count = 4, $lastLoaded = 0, $searchTitle = '')
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
    
    public function loadAbyssArticlesIdsByRate($count = 4, $lastLoaded = 0, $searchTitle = '', $searchTags = null)
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

    # ArticlesWaitingApproval

    public function loadArticlesWaitingApprovalIdsByCreatedDate($count = 4, $lastLoaded = 0)
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
    
    public function loadArticlesWaitingApprovalIdsByRate($count = 4, $lastLoaded = 0)
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

    # ArticlesWaitingApproval

    public function loadArticlesWaitingApproval($articleIds)
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