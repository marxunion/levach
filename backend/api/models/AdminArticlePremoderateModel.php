<?php
namespace Api\Models;

use Base\BaseModel;

class AdminArticlePremoderateModel extends BaseModel
{
    public function getArticleByViewCode($viewCode)
    {
        return $this->database->get('articles', 'id', ['view_code' => $viewCode]);
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