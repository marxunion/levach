<?php
namespace Api\Models;

use Core\Error;
use Core\Warning;
use Core\Critical;

use Base\BaseModel;

class AdminRejectAllApproveModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function rejectAllApproveModel()
    {
        $this->database->update('articles', ['approvededitorially_status' => 0], ['approvededitorially_status' => 1]);
        $this->database->update('statistics', ['approvededitorially_status' => 0], ['approvededitorially_status' => 1]);
    }
}