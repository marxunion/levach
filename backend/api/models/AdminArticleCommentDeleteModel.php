<?php
namespace Api\Models;

use Base\BaseModel;

class AdminArticleCommentDeleteModel extends BaseModel
{
    public function getArticleByViewCode($viewCode)
    {
        return $this->database->get('articles', 'id', ['view_code' => $viewCode]);
    }

    public function deleteComment($articleId, $commentId)
    {
        $this->database->delete('comments', ['article_id' => $articleId, 'id' => $commentId]);
    }
}