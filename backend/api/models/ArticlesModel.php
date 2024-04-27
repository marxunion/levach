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

    public function loadArticlesIdsByTimestamp($count = 4, $lastLoadedArticleId = 2147483645, $lastLoadedArticleTimestamp = 2147483645)
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
    
    public function loadArticlesIdsByRate($count = 4, $lastLoadedArticleId = 2147483645, $lastLoadedArticleRate = 2147483645)
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

    public function loadArticles($articleIds, $category)
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
                        if($category == 'editoriallyArticles')
                        {
                            $articleVersions = $this->database->select('articles', ['version_id', 'title', 'text', 'tags', 'date', 'editorially_status', 'premoderation_status', 'acceptededitorially_status'], ['id' => $articleId, 'editorially_status' => 1]);
                        }
                        elseif ($category == 'editoriallyApprovedArticles')
                        {
                            $articleVersions = $this->database->select('articles', ['version_id', 'title', 'text', 'tags', 'date', 'editorially_status', 'premoderation_status', 'acceptededitorially_status'], ['id' => $articleId, 'premoderation_status' => 2, 'acceptededitorially_status' => 1]);
                        }
                        else if($category == 'abyssArticles')
                        {
                            $articleVersions = $this->database->select('articles', ['version_id', 'title', 'text', 'tags', 'date', 'editorially_status', 'premoderation_status', 'acceptededitorially_status'], ['id' => $articleId, 'premoderation_status' => 2]);
                        }
                        else if($category == 'articlesWaitingApproval')
                        {
                            $articleVersions =  $this->database->select('articles', ['version_id', 'title', 'text', 'tags', 'date', 'editorially_status', 'premoderation_status', 'acceptededitorially_status'], ['id' => $articleId, 'premoderation_status' => 2, 'acceptededitorially_status' => 1]);
                        }
                        else if($category == 'articlesWaitingPremoderate')
                        {
                            $articleVersions = $this->database->select('articles', ['version_id', 'title', 'text', 'tags', 'date', 'editorially_status', 'premoderation_status', 'acceptededitorially_status'], ['id' => $articleId, 'premoderation_status' => 1]);
                        }
                        else
                        {
                            throw new Error(400, 'Unknown category', 'Unknown category');
                        }
                        
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