<?php
namespace Api\Models;

use Core\Error;

use Base\BaseModel;

class AdminArticleCommentDeleteModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function deleteComment($articleId, $commentId)
    {
        $this->database->delete('comments', ['article_id' => $articleId, 'comment_id' => $commentId]);
    }
}