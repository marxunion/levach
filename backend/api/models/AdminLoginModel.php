<?php
namespace Api\Models;

use Core\Database;
use Core\Error;

use Base\BaseModel;

use Core\Database;

class AdminLoginModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function isAdmin()
    {
        if()
        {
            $adminToken = $_COOKIE['adminToken'];
            if($database)
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