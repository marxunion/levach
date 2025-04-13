<?php
namespace Routes\Api\Admin\Articles\Comments\RejectAllApprove;

use Base\BaseModel;

class MainModel extends BaseModel
{
    public function rejectAllApproveModel()
    {
        $this->database->update('articles', ['approvededitorially_status' => 0], ['approvededitorially_status' => 1]);
        $this->database->update('articles_versions', ['approvededitorially_status' => 0], ['approvededitorially_status' => 1]);
    }
}