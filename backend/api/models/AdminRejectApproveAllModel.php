<?php
namespace Api\Models;

use Core\Error;
use Core\Warning;
use Core\Critical;

use Base\BaseModel;

class AdminRejectApproveAllModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function rejectApproveAllModel()
    {
        $this->database->update('articles', ['approvededitorially_status' => 1], ['approvededitorially_status' => 0]);
        $this->database->update('statistics', ['approvededitorially_status' => 1], ['approvededitorially_status' => 0]);
    }
}