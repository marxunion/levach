<?php
namespace Api\Models;

use Base\BaseModel;

class ArticleCommentNewModel extends BaseModel
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

        $this->database->insert(
            'comments', 
            [
                'id' => $newCommentId,
                'article_id' => $articleId,
                'parent_comment_id' => null,
                'text' => $text,
                'rating' => 0,
                'rating_influence' => $ratingInfluence,
                'created_date' => time()
            ]
        );
    }
}