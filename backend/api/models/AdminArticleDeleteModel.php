<?php
namespace Api\Models;

use Base\BaseModel;

class AdminArticleDeleteModel extends BaseModel
{
    public function getArticleByViewCode($viewCode)
    {
        return $this->database->get('articles', 'id', ['view_code' => $viewCode]);
    }

    public function deleteArticle($articleId)
    {
        $this->database->delete('articles', ['id' => $articleId]);
    }
}