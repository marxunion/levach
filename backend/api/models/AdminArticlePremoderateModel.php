<?php
namespace Api\Models;

use Base\BaseModel;

class AdminArticlePremoderateModel extends BaseModel
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

    public function acceptPremoderate($articleId)
    {
        $this->database->update('articles', ['premoderation_status' => 2], ['id' => $articleId]);
        $this->database->update('articles_versions', ['premoderation_status' => 2], ['article_id' => $articleId]);
    }

    public function rejectPremoderate($articleId)
    {
        $this->database->delete('articles', ['id' => $articleId]);
    }
}