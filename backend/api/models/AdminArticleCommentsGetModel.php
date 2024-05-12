<?php
namespace Api\Models;

use Core\Error;
use Core\Warning;
use Core\Critical;

use Base\BaseModel;

class ArticleCommentsGetModel extends BaseModel
{
    private $articleId;
    private $dateBefore;
    private $dateAfter;
    private $regexPattern;


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
                'id' => $comment['id']
            ]
        );
        if($parentComment)
        {
            if($parentComment['created_date'] > $this->dateBefore && $parentComment['created_date'] < $this->dateAfter && preg_match($comment['text'], $regexPattern))
            {
                return true;
            }
            else
            {
                return checkParents($parentComment);
            }
        }
        else
        {
            return false;
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

        foreach ($comments as &$comment) 
        {
            $subcomments = $this->getSubcomments($articleId, $comment['id']);
            if(!empty($subcomments)) 
            {
                $comment['subcomments'] = $subcomments;
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
                'LIMIT' => $count,
                "ORDER" => [
                    "created_date" => "DESC",
                ],
                'article_id' => $this->articleId,
                'created_date[<>]' => [$this->dateBefore, $this->dateAfter],
                'text[REGEXP]' => $this->regexPattern
            ]
        );

        foreach ($comments as $key => $comment) 
        {
            if(checkParents($comment))
            {
                unset($comments[$key]);
            }
            $subcomments = $this->getSubcomments($comment['id']);
            if(!empty($subcomments)) 
            {
                $comment['subcomments'] = $subcomments;
            }
        }
        return $comments;
    }
}