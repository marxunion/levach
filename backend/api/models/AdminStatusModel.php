<?php
namespace Api\Models;

use Core\Database;
use Core\Error;

use Base\BaseModel;

use Api\Handlers\AdminModel;

class AdminStatusModel extends BaseModel
{
    public function isAdmin()
    {
        return AdminModel::isAdmin();
    }   
}