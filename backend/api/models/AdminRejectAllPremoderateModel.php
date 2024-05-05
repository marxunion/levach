<?php
namespace Api\Models;

use Core\Error;
use Core\Warning;
use Core\Critical;

use Base\BaseModel;

class AdminRejectAllPremoderateModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function rejectAllPremoderateModel()
    {
        $this->database->delete('articles', ['premoderation_status' => 1]);
        $this->database->delete('statistics', ['premoderation_status' => 1]);
    }
}