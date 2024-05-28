<?php
namespace Api\Models;

use Base\BaseModel;

class AdminRejectAllPremoderateModel extends BaseModel
{
    public function rejectAllPremoderateModel()
    {
        $articleIds = $this->database->select('articles', 'id', ['premoderation_status' => 1]);
        foreach($articleIds as &$articleId) 
        {
            $this->database->delete('comments', ['article_id' => $articleId]);
        }
        
        $this->database->delete('articles', ['premoderation_status' => 1]);
        $this->database->delete('articles_versions', ['premoderation_status' => 1]);
    }
}