<?php
namespace Api\Models;

use Base\BaseModel;

class ArticleCommentsGetBeforeIdModel extends BaseModel
{
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
                'created_date',
                'rating_influence',
                'parent_comment_id'
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

    public function getComments($articleId, $commentId)
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
                    "rating" => "DESC",
                ],
                'article_id' => $articleId,
                'id[<=]' => $commentId,
                'parent_comment_id' => null
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
}