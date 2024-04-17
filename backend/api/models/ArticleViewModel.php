<?php
namespace Api\Models;

use Base\BaseModel;

use Core\Database;

class ArticleViewModel extends BaseModel
{
    public function __construct($data)
    {
        parent::__construct();
    }

    public function getArticleByViewCode($viewCode)
    {
        $this->database->get('codes', 'article_id', ['view_code' => $viewCode]);
    }

    public function viewArticle($articleId)
    {
        $article = $this->database->get('articles', '*', [
            'id' => $articleId
        ]);
        $articleStatistics = $this->database->get('statistics', '*', ['article_id' => $articleId]);

        return $article;
        $this->database->get('articles', '*', ['id' => $articleId]);
    }
}