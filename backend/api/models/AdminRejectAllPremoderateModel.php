<?php
namespace Api\Models;

use Base\BaseModel;

class AdminRejectAllPremoderateModel extends BaseModel
{
    public function rejectAllPremoderateModel()
    {
        $this->database->update('articles', ['premoderation_status' => 2], ['premoderation_status' => 3]);
        $this->database->delete('articles', ['premoderation_status' => 1]);
    }
}