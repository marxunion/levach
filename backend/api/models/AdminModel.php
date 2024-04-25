<?php
namespace Api\Models;

use Core\Database;
use Core\Error;

use Base\BaseModel;

use Core\Database;

class AdminModel extends BaseModel
{
    public static isAdmin()
    {
        $database = Database::getConnection();
        if(!isset($_COOKIE['adminToken']))
        {
            $adminToken = $_COOKIE['adminToken'];
            if()
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }   
}