<?php
namespace Api\Models;

use Base\BaseModel;

use Core\Database;

class ArticleEditPreloadModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getArticleIdByEditCode($editCode)
    {
        return $this->database->get('codes', 'article_id', ['edit_code' => $editCode]);
    }
    
    public function viewArticle($articleId)
    {
        $article = $this->database->get('articles', '*', [
            'id' => $articleId,
            'ORDER' => ['version_id' => 'DESC'],
            'LIMIT' => 1
        ]);
        $articleStatistics = $this->database->get('statistics', '*', ['article_id' => $articleId]);

        return [
            "title" => $article['title'],
            "text" => $article['text'],
            "tags" => $article['tags'],
            "premoderationStatus" => $article['premoderationStatus'],
            "acceptedEditoriallyStatus" => $article['acceptedEditoriallyStatus'],
            "statistics" => [
                "rating" => $articleStatistics['rating'],
                "comments" => $articleStatistics['comments']
            ]
        ];
    }
}