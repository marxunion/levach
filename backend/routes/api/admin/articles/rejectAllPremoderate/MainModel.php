<?php
namespace Routes\Api\Admin\Articles\Comments\RejectAllPremoderate;

use Base\BaseModel;

class MainModel extends BaseModel
{
    public function rejectAllPremoderateModel()
    {
        $this->database->update('articles', ['premoderation_status' => 2], ['premoderation_status' => 3]);
        $this->database->delete('articles', ['premoderation_status' => 1]);
    }
}