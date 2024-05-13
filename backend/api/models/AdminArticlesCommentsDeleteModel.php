<?php
namespace Api\Models;

use Core\Error;

use Base\BaseModel;

class AdminArticlesCommentsDeleteModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getArticleByViewCode($viewCode)
    {
        return $this->database->get('codes', 'article_id', ['view_code' => $viewCode]);
    }

    public function deleteComments($articlesCount, $commentsCount, $articleDateBefore, $articleDateAfter, $commentDateBefore, $commentDateAfter, $articleRegexPattern, $commentRegexPattern)
    {
        $this->commentDateBefore = $commentDateBefore;
        $this->commentDateAfter = $commentDateAfter;
        $this->commentRegexPattern = $commentRegexPattern;

        $sql = "SELECT article_id, current_title, created_date FROM statistics WHERE created_date BETWEEN :date_before AND :date_after AND current_text ~ :regex_pattern AND comments > 0 ORDER BY created_date DESC LIMIT :count";  
        $bindings = [
            ':date_before' => $articleDateBefore,
            ':date_after' => $articleDateAfter,
            ':regex_pattern' => $articleRegexPattern,
            ':count' => $articlesCount
        ];

        $articles = $this->database->query($sql, $bindings)->fetchAll();

        $articlesReturn = [];

        foreach ($articles as &$article) 
        {
            
        }

        $sql = "DELETE FROM comments
            WHERE 
            id IN (
                SELECT id
                FROM comments
                AND created_date BETWEEN :date_before AND :date_after 
                AND text ~ :regex_pattern 
                ORDER BY created_date DESC 
                LIMIT :count) 
            AND 
            article_id IN (
                SELECT article_id
                FROM statistics
                WHERE created_date BETWEEN :date_before AND :date_after 
                AND current_text ~ :regex_pattern 
                ORDER BY created_date DESC
                LIMIT :count)
        ";

        $bindings = [
            ':date_before' => $dateBefore,
            ':date_after' => $dateAfter,
            ':regex_pattern' => $regexPattern,
            ':count' => $count
        ];
        $this->database->query($sql, $bindings);
    }
}