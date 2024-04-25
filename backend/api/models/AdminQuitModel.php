<?php
namespace Api\Models;

use Core\Database;
use Core\Error;

use Base\BaseModel;

use Core\Database;

class AdminModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public static isAdmin()
    {
        $database = Database::getConnection();
        if(!isset($_COOKIE['adminToken']))
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