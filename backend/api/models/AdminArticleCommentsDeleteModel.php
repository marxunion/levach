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

    public function deleteComments($articleId, $count, $dateBefore, $dateAfter, $text)
    {
        $sql = "WITH rows_to_delete AS (
            SELECT id
            FROM comments 
            WHERE article_id = :article_id 
            AND created_date BETWEEN :date_before AND :date_after 
            AND text ~ :regex_pattern 
            ORDER BY created_date DESC 
            LIMIT :count
        )
        DELETE FROM comments 
        WHERE id IN (SELECT id FROM rows_to_delete);";

        $bindings = [
            ':article_id' => $this->articleId,
            ':date_before' => $this->dateBefore,
            ':date_after' => $this->dateAfter,
            ':regex_pattern' => $this->regexPattern,
            ':count' => $count
        ];
        $this->database->query($sql, $bindings);
    }
}