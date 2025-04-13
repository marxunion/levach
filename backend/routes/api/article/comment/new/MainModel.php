<?php
namespace Routes\Api\Article\Comment\Set;

use Helpers\StringFormatter;

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

    public function newComment($articleId, $text = '', $ratingInfluence = 0)
    {
        $text = StringFormatter::replaceViewIdsToViewIdsLinks($text);
        $text = StringFormatter::filterHtmlTags($text);

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