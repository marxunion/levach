<?php
namespace Api\Models;

use Base\BaseModel;

class AdminArticlesCommentsDeleteModel extends BaseModel
{
    public function getArticleByViewCode($viewCode)
    {
        return $this->database->get('codes', 'article_id', ['view_code' => $viewCode]);
    }

    public function deleteComments($articlesCount, $commentsCount, $articleDateBefore, $articleDateAfter, $commentDateBefore, $commentDateAfter, $articleRegexPattern, $commentRegexPattern)
    {
        $sql = "DELETE FROM comments 
        WHERE article_id IN (
            SELECT article_id 
            FROM statistics 
            WHERE created_date BETWEEN :article_date_before AND :article_date_after 
            AND current_text ~ :article_regex_pattern 
            ORDER BY created_date DESC 
            LIMIT :articles_count
        ) 
        AND id IN (
            SELECT id 
            FROM comments 
            WHERE created_date BETWEEN :comment_date_before AND :comment_date_after 
            AND text ~ :comment_regex_pattern 
            ORDER BY created_date DESC 
            LIMIT :comments_count
        )";

        $bindings = [
            ':article_date_before' => $articleDateBefore,
            ':article_date_after' => $articleDateAfter,
            ':article_regex_pattern' => $articleRegexPattern,
            ':articles_count' => $articlesCount,

            ':comment_date_before' => $commentDateBefore,
            ':comment_date_after' => $commentDateAfter,
            ':comment_regex_pattern' => $commentRegexPattern,
            ':comments_count' => $commentsCount
        ];
        $this->database->query($sql, $bindings);
    }
}