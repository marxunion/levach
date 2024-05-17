<?php
namespace Api\Models;

use Base\BaseModel;

class AdminRejectAllApproveModel extends BaseModel
{
    public function rejectAllApproveModel()
    {
        $this->database->update('articles', ['approvededitorially_status' => 0], ['approvededitorially_status' => 1]);
        $this->database->update('statistics', ['approvededitorially_status' => 0], ['approvededitorially_status' => 1]);
    }
}