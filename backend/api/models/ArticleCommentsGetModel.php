<?php
namespace Api\Models;

use Core\Error;
use Core\Warning;
use Core\Critical;

use Base\BaseModel;

class ArticleCommentsGetModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function getArticleByViewCode($viewCode)
    {
        return $this->database->get('codes', 'article_id', ['view_code' => $viewCode]);
    }
    public function getSubcomments($articleId, $parentCommentId)
    {
        $comments = $this->database->select(
            'comments', 
            [
                'id',
                'text', 
                'rating',
                'rating_influence'
            ], 
            [
                "ORDER" => [
                    "created_date" => "DESC",
                ],
                'article_id' => $articleId, 
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

    public function getCommentsByRate($articleId, $count, $lastLoaded)
    {
        $comments = $this->database->select(
            'comments', 
            [
                'id',
                'text', 
                'rating',
                'rating_influence'
            ], 
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "rating" => "DESC",
                ],
                'article_id' => $articleId,
                'parent_comment_id' => null
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

    public function getCommentsByCreatedDate($articleId, $count, $lastLoaded)
    {
        $comments = $this->database->select(
            'comments', 
            [
                'id',
                'text', 
                'rating',
                'rating_influence'
            ], 
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "created_date" => "DESC",
                ],
                'article_id' => $articleId,
                'parent_comment_id' => null
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
}