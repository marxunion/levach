<?php
namespace Api\Models;

use Base\BaseModel;

class AdminArticleCommentDeleteModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getArticleByViewCode($viewCode)
    {
        return $this->database->get('codes', 'article_id', ['view_code' => $viewCode]);
    }

    public function deleteComment($articleId, $commentId)
    {
        $this->database->delete('comments', ['article_id' => $articleId, 'id' => $commentId]);
    }
}