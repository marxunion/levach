<?php
namespace Api\Models;

use Core\Error;

use Base\BaseModel;

class AdminArticleCommentsDeleteModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getArticleByViewCode($viewCode)
    {
        return $this->database->get('codes', 'article_id', ['view_code' => $viewCode]);
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
        );";

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