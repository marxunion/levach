<?php
namespace Api\Models;

use Base\BaseModel;

class ArticleCommentsGetBeforeIdModel extends BaseModel
{
    private $articleId;

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

    public function getRootComment($comment)
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
                return $this->getRootComment($parentComment);
            }
            else
            {
                return $comment;
            }
        }
        else
        {
            return $comment;
        }
    }

    public function getRootCommentId($commentId)
    {
        $comment = $this->database->get(
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
                'id' => $commentId
            ]
        );
        if($comment)
        {
            return $this->getRootComment($comment)['id'];
        }
        else
        {
            return $comment['id'];
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
                'view_id'
            ], 
            [
                "ORDER" => [
                    "created_date" => "DESC",
                ],
                'article_id' => $this->articleId, 
                'parent_comment_id' => $parentCommentId
            ]
        );

        foreach($comments as &$comment) 
        {
            $subcomments = $this->getSubcomments($comment['id']);
            if(!empty($subcomments)) 
            {
                $comment['subcomments'] = $subcomments;
            }
        }

        return $comments;
    }

    public function getComments($articleId, $commentId)
    {
        $this->articleId = $articleId;
        $comments = $this->database->select(
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
                "ORDER" => [
                    "created_date" => "DESC",
                ],
                'article_id' => $this->articleId,
                'id[>=]' => $this->getRootCommentId($commentId),
                'parent_comment_id' => null
            ]
        );

        foreach($comments as &$comment) 
        {
            $subcomments = $this->getSubcomments($comment['id']);
            if(!empty($subcomments)) 
            {
                $comment['subcomments'] = $subcomments;
            }
        }
        return $comments;
    }
}