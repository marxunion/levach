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

    public function quit($token, $nickname, $password)
    {
        if($token && $nickname && $timestamp)
        {
            $adminInfo = $this->database->get('admins_tokens', ['nickname_encrypted', 'created_at_encrypted'], ['token' => $token]);
            if($adminInfo)
            {
                if(password_verify($nickname, $adminInfo['nickname_encrypted']))
                {
                    if(password_verify($timestamp, $adminInfo['created_at_encrypted']))
                    {
                        return true;
                    }
                    else
                    {
                        return false;
                    }
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }   
}