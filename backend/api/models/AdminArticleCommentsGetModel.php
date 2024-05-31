<?php
namespace Api\Models;

use Base\BaseModel;

class AdminArticleCommentsGetModel extends BaseModel
{
    private $articleId;
    private $dateBefore;
    private $dateAfter;
    private $regexPattern;
    
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
                    'parent_comment_id',
                    'view_id'
                ],
                [
                    'article_id' => $this->articleId,
                    'id' => $comment['parent_comment_id']
                ]
            );
            if($parentComment)
            {
                if($parentComment['created_date'] > $this->dateBefore && $parentComment['created_date'] < $this->dateAfter && preg_match('/'.$this->regexPattern.'/', $parentComment['text'] ))
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
                'parent_comment_id',
                'vies_id'
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
            foreach($comments as &$comment) 
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

    public function getComments($articleId, $count, $dateBefore, $dateAfter, $regexPattern)
    {
        $this->articleId = $articleId;
        $this->dateBefore = $dateBefore;
        $this->dateAfter = $dateAfter;
        $this->regexPattern = $regexPattern;

        $sql = "SELECT id, text, rating, created_date, rating_influence, parent_comment_id FROM comments WHERE article_id = :article_id AND created_date BETWEEN :date_before AND :date_after AND text ~ :regex_pattern ORDER BY created_date DESC LIMIT :count";  
        $bindings = [
            ':article_id' => $this->articleId,
            ':date_before' => $this->dateBefore,
            ':date_after' => $this->dateAfter,
            ':regex_pattern' => $this->regexPattern,
            ':count' => $count
        ];

        $comments = $this->database->query($sql, $bindings)->fetchAll();
        $commentsReturn = [];

        foreach($comments as &$comment) 
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

        return $commentsReturn;
    }
}