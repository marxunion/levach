<?php
namespace Api\Models;

use Base\BaseModel;

class AdminRejectAllPremoderateModel extends BaseModel
{
    public function rejectAllPremoderateModel()
    { 
        $this->database->delete('articles', ['premoderation_status' => 1]);
        $this->database->delete('articles_versions', ['premoderation_status' => 1]);
    }
}