<?php
namespace Api\Models;

use Base\BaseModel;

use Core\Database;

class ArticleViewModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getArticleByViewCode($viewCode)
    {
        return $this->database->get('codes', 'article_id', ['view_code' => $viewCode]);
    }

    public function viewArticle($articleId)
    {
        $articleVertions = $this->database->select('articles', ['title', 'text', 'tags', 'date', 'premoderation_status', 'acceptededitorially_status'], ['id' => $articleId]);
        $articleStatistics = $this->database->get('statistics', ['rating', 'comments'], ['article_id' => $articleId]);

        $article = [
            'versions' => $articleVertions,
            'statistics' => $articleStatistics
        ];
        return $article;
    }
}