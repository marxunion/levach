<?php
namespace Api\Models;

use Core\Error;
use Core\Warning;
use Core\Critical;

use Base\BaseModel;

class AdminRejectPremoderateAllModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function rejectPremoderateAllModel()
    {
        $this->database->delete('articles', ['premoderation_status' => 2]);
        $this->database->delete('statistics', ['premoderation_status' => 2]);
    }
}