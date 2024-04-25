<?php
namespace Api\Models;

use Core\Database;
use Core\Error;

use Base\BaseModel;

use Api\Handlers\AdminModel;

class AdminStatusModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function isAdmin($token, $nickname, $timestamp)
    {
        return AdminModel::isAdmin($token, $nickname, $timestamp);
    }   
}