<?php
namespace Api\Models;

use Base\BaseModel;

class AdminArticleDeleteModel extends BaseModel
{
    public function getArticleByViewCode($viewCode)
    {
        return $this->database->get('codes', 'article_id', ['view_code' => $viewCode]);
    }

    public function deleteArticle($articleId)
    {
        $this->database->delete('codes', ['article_id' => $articleId]);
        $this->database->delete('comments', ['article_id' => $articleId]);
        $this->database->delete('statistics', ['article_id' => $articleId]);
        $this->database->delete('articles', ['id' => $articleId]);
    }
}