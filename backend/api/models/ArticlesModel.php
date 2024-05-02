<?php
namespace Api\Models;

use Core\Error;
use Core\Database;

use Base\BaseModel;

class ArticlesModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }


    # Article search
    public function loadArticleSearchIdsByCreatedAtWithTags($count = 4, $lastLoadedArticleId = 2147483645, $lastLoadedArticleCreatedAt = 2147483645, $searchTitle = '', $searchTags = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => $count,
                "ORDER" => [
                    "article_id" => "DESC",
                ],
                'AND' => [
                    'premoderation_status' => 2,
                    'current_title[~]' => $searchTitle,
                    'current_tags &&' => $searchTags,
                    'OR' => [
                        'created_at[<]' => $lastLoadedArticleCreatedAt,
                        'AND' => [
                            'created_at' => $lastLoadedArticleCreatedAt,
                            'article_id[<]' => $lastLoadedArticleId
                        ]
                    ]
                ]
            ]
        );
    }

    public function loadArticleSearchIdsByCreatedAt($count = 4, $lastLoadedArticleId = 2147483645, $lastLoadedArticleCreatedAt = 2147483645, $searchTitle = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => $count,
                "ORDER" => [
                    "article_id" => "DESC",
                ],
                'AND' => [
                    'premoderation_status' => 2,
                    'current_title[~]' => $searchTitle,
                    'OR' => [
                        'created_at[<]' => $lastLoadedArticleCreatedAt,
                        'AND' => [
                            'created_at' => $lastLoadedArticleCreatedAt,
                            'article_id[<]' => $lastLoadedArticleId
                        ]
                    ]
                ]
            ]
        );
    }

    public function loadArticleSearchIdsByRateWithTags($count = 4, $lastLoadedArticleId = 2147483645, $lastLoadedArticleRate = 2147483645, $searchTitle = '', $searchTags = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => $count,
                "ORDER" => [
                    "article_id" => "DESC",
                ],
                'AND' => [
                    'premoderation_status' => 2,
                    'current_title[~]' => $searchTitle,
                    'current_tags &&' => $searchTags,
                    'OR' => [
                        'rating[<]' => $lastLoadedArticleRate,
                        'AND' => [
                            'rating' => $lastLoadedArticleRate,
                            'article_id[<]' => $lastLoadedArticleId
                        ]
                    ]
                ]
            ]
        );
    }

    public function loadArticleSearchIdsByRate($count = 4, $lastLoadedArticleId = 2147483645, $lastLoadedArticleRate = 2147483645, $searchTitle = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => $count,
                "ORDER" => [
                    "article_id" => "DESC",
                ],
                'AND' => [
                    'premoderation_status' => 2,
                    'current_title[~]' => $searchTitle,
                    'OR' => [
                        'rating[<]' => $lastLoadedArticleRate,
                        'AND' => [
                            'rating' => $lastLoadedArticleRate,
                            'article_id[<]' => $lastLoadedArticleId
                        ]
                    ]
                ]
            ]
        );
    }

    
    # EditoriallyArticles
    public function loadEditoriallyArticlesIdsByCreatedAt($count = 4, $lastLoadedArticleId = 2147483645, $lastLoadedArticleCreatedAt = 2147483645)
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => $count,
                "ORDER" => [
                    "created_at" => "DESC",
                ],
                'AND' => [
                    'editorially_status' => 1,
                    'OR' => [
                        'created_at[<]' => $lastLoadedArticleCreatedAt,
                        'AND' => [
                            'created_at' => $lastLoadedArticleCreatedAt,
                            'article_id[<]' => $lastLoadedArticleId
                        ]
                    ]
                ]
            ]
        );
    }
    
    public function loadEditoriallyArticlesIdsByRate($count = 4, $lastLoadedArticleId = 2147483645, $lastLoadedArticleRate = 2147483645)
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => $count,
                "ORDER" => [
                    "rating" => "DESC",
                ],
                'AND' => [
                    'editorially_status' => 1,
                    'OR' => [
                        'rating[<]' => $lastLoadedArticleRate,
                        'AND' => [
                            'rating' => $lastLoadedArticleRate,
                            'article_id[<]' => $lastLoadedArticleId
                        ]
                    ]
                ]
            ]
        );
    }

    # EditoriallyApprovedArticles

    public function loadEditoriallyApprovedArticlesIdsByCreatedAt($count = 4, $lastLoadedArticleId = 2147483645, $lastLoadedArticleCreatedAt = 2147483645)
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => $count,
                "ORDER" => [
                    "created_at" => "DESC",
                ],
                'AND' => [
                    'approvededitorially_status' => 2,
                    'OR' => [
                        'created_at[<]' => $lastLoadedArticleCreatedAt,
                        'AND' => [
                            'created_at' => $lastLoadedArticleCreatedAt,
                            'article_id[<]' => $lastLoadedArticleId
                        ]
                    ]
                ]
            ]
        );
    }
    
    public function loadEditoriallyApprovedArticlesIdsByRate($count = 4, $lastLoadedArticleId = 2147483645, $lastLoadedArticleRate = 2147483645)
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => $count,
                "ORDER" => [
                    "rating" => "DESC",
                ],
                'AND' => [
                    'approvededitorially_status' => 2,
                    'OR' => [
                        'rating[<]' => $lastLoadedArticleRate,
                        'AND' => [
                            'rating' => $lastLoadedArticleRate,
                            'article_id[<]' => $lastLoadedArticleId
                        ]
                    ]
                ]
            ]
        );
    }

    # AbyssArticles

    public function loadAbyssArticlesIdsByCreatedAt($count = 4, $lastLoadedArticleId = 2147483645, $lastLoadedArticleCreatedAt = 2147483645)
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => $count,
                "ORDER" => [
                    "created_at" => "DESC",
                ],
                'AND' => [
                    'premoderation_status' => 2,
                    'OR' => [
                        'created_at[<]' => $lastLoadedArticleCreatedAt,
                        'AND' => [
                            'created_at' => $lastLoadedArticleCreatedAt,
                            'article_id[<]' => $lastLoadedArticleId
                        ]
                    ]
                ]
            ]
        );
    }
    
    public function loadAbyssArticlesIdsByRate($count = 4, $lastLoadedArticleId = 2147483645, $lastLoadedArticleRate = 2147483645, $searchTitle = '', $searchTags = null)
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => $count,
                "ORDER" => [
                    "rating" => "DESC",
                ],
                'AND' => [
                    'premoderation_status' => 2,
                    'OR' => [
                        'rating[<]' => $lastLoadedArticleRate,
                        'AND' => [
                            'rating' => $lastLoadedArticleRate,
                            'article_id[<]' => $lastLoadedArticleId
                        ]
                    ]
                ]
            ]
        );
    }

    # ArticlesWaitingApproval

    public function loadArticlesWaitingApprovalIdsByCreatedAt($count = 4, $lastLoadedArticleId = 2147483645, $lastLoadedArticleCreatedAt = 2147483645)
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => $count,
                "ORDER" => [
                    "created_at" => "DESC",
                ],
                'AND' => [
                    'approvededitorially_status' => 1,
                    'OR' => [
                        'created_at[<]' => $lastLoadedArticleCreatedAt,
                        'AND' => [
                            'created_at' => $lastLoadedArticleCreatedAt,
                            'article_id[<]' => $lastLoadedArticleId
                        ]
                    ]
                ]
            ]
        );
    }
    
    public function loadArticlesWaitingApprovalIdsByRate($count = 4, $lastLoadedArticleId = 2147483645, $lastLoadedArticleRate = 2147483645)
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => $count,
                "ORDER" => [
                    "rating" => "DESC",
                ],
                'AND' => [
                    'approvededitorially_status' => 1,
                    'OR' => [
                        'rating[<]' => $lastLoadedArticleRate,
                        'AND' => [
                            'rating' => $lastLoadedArticleRate,
                            'article_id[<]' => $lastLoadedArticleId
                        ]
                    ]
                ]
            ]
        );
    }

    # ArticlesWaitingPremoderate

    public function loadArticlesWaitingPremoderateIdsByCreatedAt($count = 4, $lastLoadedArticleId = 2147483645, $lastLoadedArticleCreatedAt = 2147483645)
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => $count,
                "ORDER" => [
                    "created_at" => "DESC",
                ],
                'AND' => [
                    'premoderation_status' => 1,
                    'OR' => [
                        'created_at[<]' => $lastLoadedArticleCreatedAt,
                        'AND' => [
                            'created_at' => $lastLoadedArticleCreatedAt,
                            'article_id[<]' => $lastLoadedArticleId
                        ]
                    ]
                ]
            ]
        );
    }
    
    public function loadArticlesWaitingPremoderateIdsByRate($count = 4, $lastLoadedArticleId = 2147483645, $lastLoadedArticleRate = 2147483645)
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => $count,
                "ORDER" => [
                    "rating" => "DESC",
                ],
                'AND' => [
                    'premoderation_status' => 1,
                    'OR' => [
                        'rating[<]' => $lastLoadedArticleRate,
                        'AND' => [
                            'rating' => $lastLoadedArticleRate,
                            'article_id[<]' => $lastLoadedArticleId
                        ]
                    ]
                ]
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
                        $articleVersions = $this->database->select('articles', ['version_id', 'title', 'text', 'tags', 'date', 'editorially_status', 'premoderation_status', 'approvededitorially_status'], ['id' => $articleId, 'premoderation_status' => 2]);
                        
                        $article = [
                            'id' => $articleId,
                            'versions' => $articleVersions,
                            'statistics' => $this->database->get('statistics', ['created_at','rating', 'comments'], ['article_id' => $articleId]),
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
                        $articleVersions = $this->database->select('articles', ['version_id', 'title', 'text', 'tags', 'date', 'editorially_status', 'premoderation_status', 'approvededitorially_status'], ['id' => $articleId, 'editorially_status' => 1]);
                        
                        $article = [
                            'id' => $articleId,
                            'versions' => $articleVersions,
                            'statistics' => $this->database->get('statistics', ['created_at','rating', 'comments'], ['article_id' => $articleId]),
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

    public function loadAEditoriallypprovedArticles($articleIds)
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
                        $articleVersions = $this->database->select('articles', ['version_id', 'title', 'text', 'tags', 'date', 'editorially_status', 'premoderation_status', 'approvededitorially_status'], ['id' => $articleId, 'premoderation_status' => 2, 'approvededitorially_status' => 1]);
                        
                        $article = [
                            'id' => $articleId,
                            'versions' => $articleVersions,
                            'statistics' => $this->database->get('statistics', ['created_at','rating', 'comments'], ['article_id' => $articleId]),
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
                        $articleVersions = $this->database->select('articles', ['version_id', 'title', 'text', 'tags', 'date', 'editorially_status', 'premoderation_status', 'approvededitorially_status'], ['id' => $articleId, 'premoderation_status' => 2]);
                        
                        $article = [
                            'id' => $articleId,
                            'versions' => $articleVersions,
                            'statistics' => $this->database->get('statistics', ['created_at','rating', 'comments'], ['article_id' => $articleId]),
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
                        $articleVersions =  $this->database->select('articles', ['version_id', 'title', 'text', 'tags', 'date', 'editorially_status', 'premoderation_status', 'approvededitorially_status'], ['id' => $articleId, 'premoderation_status' => 2, 'approvededitorially_status' => 1]);
                        
                        $article = [
                            'id' => $articleId,
                            'versions' => $articleVersions,
                            'statistics' => $this->database->get('statistics', ['created_at','rating', 'comments'], ['article_id' => $articleId]),
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
                        $articleVersions = $this->database->select('articles', ['version_id', 'title', 'text', 'tags', 'date', 'editorially_status', 'premoderation_status', 'approvededitorially_status'], ['id' => $articleId, 'premoderation_status' => 1]);
                        
                        $article = [
                            'id' => $articleId,
                            'versions' => $articleVersions,
                            'statistics' => $this->database->get('statistics', ['created_at','rating', 'comments'], ['article_id' => $articleId]),
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