<?php
namespace Api\Models;

use Core\Error;
use Core\Warning;
use Core\Critical;

use Base\BaseModel;

class AdminArticlesCommentsGetModel extends BaseModel
{
    private $articleId;
    private $commentDateBefore;
    private $commentDateAfter;
    private $commentRegexPattern;

    public function __construct()
    {
        parent::__construct();
    }
    
    public function getArticleByViewCode($viewCode)
    {
        return $this->database->get('codes', 'article_id', ['view_code' => $viewCode]);
    }

    public function checkParents($comment)
    {
        if($comment['parent_comment_id'] != null)
        {
            $parentComment = $this->database->get(
                'comments', 
                [
                    'id',
                    'text',
                    'rating',
                    'created_date',
                    'rating_influence',
                    'parent_comment_id'
                ],
                [
                    'article_id' => $this->articleId,
                    'id' => $comment['parent_comment_id']
                ]
            );
            if($parentComment)
            {
                if($parentComment['created_date'] > $this->commentDateBefore && $parentComment['created_date'] < $this->commentDateAfter && preg_match('/'.$this->commentRegexPattern.'/', $parentComment['text'] ))
                {
                    return false;
                }
                else
                {
                    return $this->checkParents($parentComment);
                }
            }
            else
            {
                return true;
            }
        }
        else
        {
            return true;
        }
    }

    public function getSubcomments($parentCommentId)
    {
        $comments = $this->database->select(
            'comments', 
            [
                'id',
                'text', 
                'rating',
                'created_date',
                'rating_influence',
                'parent_comment_id'
            ], 
            [
                "ORDER" => [
                    "created_date" => "DESC",
                ],
                'article_id' => $this->articleId,
                'parent_comment_id' => $parentCommentId
            ]
        );

        if(!empty($comments)) 
        {
            foreach ($comments as &$comment) 
            {
                $subcomments = $this->getSubcomments($comment['id']);
                if(!empty($subcomments)) 
                {
                    $comment['subcomments'] = $subcomments;
                }
            }
        }
        return $comments;
    }

    public function getComments($articlesCount, $commentsCount, $articleDateBefore, $articleDateAfter, $commentDateBefore, $commentDateAfter, $articleRegexPattern, $commentRegexPattern)
    {
        $this->commentDateBefore = $commentDateBefore;
        $this->commentDateAfter = $commentDateAfter;
        $this->commentRegexPattern = $commentRegexPattern;

        echo($this->commentDateBefore);
        echo($this->commentDateAfter);

        $sql = "SELECT article_id, current_title FROM statistics WHERE created_date BETWEEN :date_before AND :date_after AND current_text ~ :regex_pattern ORDER BY created_date DESC LIMIT :count";  
        $bindings = [
            ':date_before' => $articleDateBefore,
            ':date_after' => $articleDateAfter,
            ':regex_pattern' => $articleRegexPattern,
            ':count' => $articlesCount
        ];

        $articles = $this->database->query($sql, $bindings)->fetchAll();

        var_dump($articles);
        $articlesReturn = [];

        foreach ($articles as &$article) 
        {
            
            $this->articleId = $article['article_id'];
            
            $sql = "SELECT id, text, rating, created_date, rating_influence, parent_comment_id FROM comments WHERE article_id = :article_id AND created_date BETWEEN :date_before AND :date_after AND text ~ :regex_pattern ORDER BY created_date DESC LIMIT :count";  
            $bindings = [
                ':article_id' => $this->articleId,
                ':date_before' => $this->commentDateBefore,
                ':date_after' => $this->commentDateAfter,
                ':regex_pattern' => $this->commentRegexPattern,
                ':count' => $commentsCount
            ];

            $comments = $this->database->query($sql, $bindings)->fetchAll();
            $commentsReturn = [];

            foreach ($comments as &$comment) 
            {
                if($this->checkParents($comment))
                {
                    $subcomments = $this->getSubcomments($comment['id']);
                    if(!empty($subcomments)) 
                    {
                        $comment['subcomments'] = $subcomments;
                    }
                    array_push($commentsReturn, $comment);
                }
            }

            if(!empty($commentsReturn)) 
            {
                $articleToReturn = [
                    "statistics" => [
                        "current_title" => $article['current_title']
                    ],
                    "comments" => $commentsReturn,
                    'view_code' => $this->database->get('codes', 'view_code', ['article_id' => $this->articleId])
                ];
                array_push($articlesReturn, $articleToReturn);
            }
        }
        return $articlesReturn;
    }
}