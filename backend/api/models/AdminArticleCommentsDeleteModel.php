<?php
namespace Api\Models;

use Base\BaseModel;

class AdminArticleCommentsDeleteModel extends BaseModel
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

    public function deleteComments($articleId, $count, $dateBefore, $dateAfter, $regexPattern)
    {
        $sql = "DELETE FROM comments
        WHERE id IN (
            SELECT id
            FROM comments
            WHERE article_id = :article_id 
            AND created_date BETWEEN :date_before AND :date_after 
            AND text ~ :regex_pattern 
            ORDER BY created_date DESC 
            LIMIT :count
        ) AND article_id = :article_id;";

        $bindings = [
            ':article_id' => $articleId,
            ':date_before' => $dateBefore,
            ':date_after' => $dateAfter,
            ':regex_pattern' => $regexPattern,
            ':count' => $count
        ];
        $this->database->query($sql, $bindings);
    }
}