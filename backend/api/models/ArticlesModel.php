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

    public function loadArticlesIdsByTimestamp($count = 4, $lastLoadedArticleId = 2147483645, $lastLoadedArticleTimestamp = 2147483645)
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => $count,
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
    public function loadArticlesIdsByRate($count = 4, $lastLoadedArticleId = 0, $lastLoadedArticleRate = 0, )
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => $count,
                'AND' => [
                    'OR' => [
                        'rate[<]' => $lastLoadedArticleRate,
                        'AND' => [
                            'rate' => $lastLoadedArticleRate,
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
                            'versions' => $this->database->select('articles', ['title', 'text', 'tags', 'date', 'premoderation_status', 'acceptededitorially_status'], ['id' => $articleId]),
                            'statistics' => $this->database->get('statistics', ['rating', 'comments'], ['article_id' => $articleId]),
                            'viewCode' => $this->database->get('codes', 'view_code', ['article_id' => $articleId])
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