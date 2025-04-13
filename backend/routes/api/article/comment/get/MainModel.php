<?php
namespace Routes\Api\Article\Comment\Get;

use Base\BaseModel;

class MainModel extends BaseModel
{
    public function getArticleByViewCode($viewCode)
    {
        return $this->database->get('articles', 'id', ['view_code' => $viewCode]);
    }

    public function getArticleByViewId($viewId)
    {
        $articleId = $this->database->get('articles', 'id', ['view_id' => $viewId]);

        if(!isset($articleId))
        {
            $comment = $this->database->get('comments', ['id', 'article_id'], ['view_id' => $viewId]);

            if(isset($comment))
            {
                $articleId = $comment['article_id'];
            }
        }

        return $articleId;
    }


    public function getSubcomments($articleId, $parentCommentId)
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
                'article_id' => $articleId, 
                'parent_comment_id' => $parentCommentId
            ]
        );

        foreach($comments as &$comment) 
        {
            $subcomments = $this->getSubcomments($articleId, $comment['id']);
            if(!empty($subcomments)) 
            {
                $comment['subcomments'] = $subcomments;
            }
        }

        return $comments;
    }

    public function getComment($articleId, $commentId)
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
                'article_id' => $articleId, 
                'id' => $commentId
            ]
        );

        $subcomments = $this->getSubcomments($articleId, $commentId);
        if(!empty($subcomments)) 
        {
            $comment['subcomments'] = $subcomments;
        }

        return $comment;
    }
}