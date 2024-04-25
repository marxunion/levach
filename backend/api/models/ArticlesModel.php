<?php
namespace Api\Models;

use Base\BaseModel;

use Core\Database;

class ArticlesModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function loadEditieditoriallyArticlesIdsByTimestamp($count = 4, $lastLoadedArticleId = 2147483645, $lastLoadedArticleTimestamp = 2147483645)
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
                    'OR' => [
                        'created_at[<]' => $lastLoadedArticleTimestamp,
                        'AND' => [
                            'created_at' => $lastLoadedArticleTimestamp,
                            'article_id[<]' => $lastLoadedArticleId
                        ]
                    ]
                ]
            ]
        );
    }

    
    public function loadEditieditoriallyArticlesIdsByRate($count = 4, $lastLoadedArticleId = 2147483645, $lastLoadedArticleRate = 2147483645 )
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




    public function loadEditoriallyApprovedArticlesIdsByTimestamp($count = 4, $lastLoadedArticleId = 2147483645, $lastLoadedArticleTimestamp = 2147483645 )
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
                    'OR' => [
                        'created_at[<]' => $lastLoadedArticleTimestamp,
                        'AND' => [
                            'created_at' => $lastLoadedArticleTimestamp,
                            'article_id[<]' => $lastLoadedArticleId
                        ]
                    ]
                ]
            ]
        );
    }

    public function loadEditoriallyApprovedArticlesIdsByRate($count = 4, $lastLoadedArticleId = 2147483645, $lastLoadedArticleRate = 2147483645 )
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




    public function loadAbyssArticlesIdsByTimestamp($count = 4, $lastLoadedArticleId = 2147483645, $lastLoadedArticleTimestamp = 2147483645 )
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
                    'OR' => [
                        'created_at[<]' => $lastLoadedArticleTimestamp,
                        'AND' => [
                            'created_at' => $lastLoadedArticleTimestamp,
                            'article_id[<]' => $lastLoadedArticleId
                        ]
                    ]
                ]
            ]
        );
    }
    public function loadAbyssArticlesIdsByRate($count = 4, $lastLoadedArticleId = 2147483645, $lastLoadedArticleRate = 2147483645 )
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

    public function loadArticles($articleIds)
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
                        $article = [
                            'id' => $articleId,
                            'versions' => $this->database->select('articles', ['version_id', 'title', 'text', 'tags', 'date', 'premoderation_status', 'acceptededitorially_status'], ['id' => $articleId]),
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