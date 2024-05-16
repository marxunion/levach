<?php
namespace Api\Models;

use Base\BaseModel;

class AdminRejectAllPremoderateModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function rejectAllPremoderateModel()
    {
        $articleIds = $this->database->select('statistics', 'article_id', ['premoderation_status' => 1]);
        foreach ($articleIds as &$articleId) 
        {
            $this->database->delete('codes', ['article_id' => $articleId]);
            $this->database->delete('comments', ['article_id' => $articleId]);
        }
        
        $this->database->delete('articles', ['premoderation_status' => 1]);
        $this->database->delete('statistics', ['premoderation_status' => 1]);
    }
}