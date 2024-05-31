<?php
namespace Api\Models;

use Base\BaseModel;

class AdminArticleCommentDeleteModel extends BaseModel
{
    public function getArticleByViewCode($viewCode)
    {
        return $this->database->get('articles', 'id', ['view_code' => $viewCode]);
    }

    public function getArticleByViewId($viewId)
    {
        $articleId = $this->database->get('articles', 'id', ['view_id' => $view_id]);

        if(!isset($articleId))
        {
            $comment = $this->database->get('comments', ['id', 'article_id'], ['view_id' => $view_id]);

            if(isset($comment))
            {
                $articleId = $comment['article_id'];
            }
        }

        return $articleId;
    }
    public function deleteComment($articleId, $commentId)
    {
        $this->database->delete('comments', ['article_id' => $articleId, 'id' => $commentId]);
    }
}