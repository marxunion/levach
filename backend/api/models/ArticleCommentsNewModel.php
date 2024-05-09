<?php
namespace Api\Models;

use Core\Error;

use Base\BaseModel;

class ArticleCommentsNewModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getArticleByViewCode($viewCode)
    {
        return $this->database->get('codes', 'article_id', ['view_code' => $viewCode]);
    }

    public function newComment($articleId, $text = '', $ratingInfluence = 0)
    {
        $newCommentId = 1;
        $lastCommentId = $this->database->max('comments', 'id', ['article_id' => $articleId]);

        if($lastCommentId != null) 
        {
            $newCommentId = $lastCommentId + 1;
        }

        'comments', 
        [
            'article_id' => $articleId,
            'parent_comment_id' => null,
            'text' => $text,
            'rating' => 0,
            'rating_influence' => $ratingInfluence,
            'created_date' => time()
        ]
    }

    public function newSubcomment($articleId, $parentCommentId, $text = '', $ratingInfluence = 0)
    {
        $newCommentId = 1;
        $lastCommentId = $this->database->max('comments', 'id', ['article_id' => $articleId]);

        if($lastCommentId != null) 
        {
            $newCommentId = $lastCommentId + 1;
        }

        $this->database->insert(
            'comments', 
            [
                'article_id' => $articleId,
                'parent_comment_id' => $parentCommentId,
                'text' => $text,
                'rating' => 0,
                'rating_influence' => $ratingInfluence,
                'created_date' => time()
            ]
        );
    }
}