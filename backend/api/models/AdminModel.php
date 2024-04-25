<?php
namespace Api\Models;

use Core\Database;
use Core\Error;

use Base\BaseModel;

use Core\Database;

class AdminModel extends BaseModel
{
    public static isAdmin($token, $nickname, $timestamp)
    {
        $database = Database::getConnection();
        if($token && $nickname && $timestamp)
        {
            $adminInfo = $database->get('admins_tokens', ['nickname_encrypted', 'created_at_encrypted'], ['token' => $token]);
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