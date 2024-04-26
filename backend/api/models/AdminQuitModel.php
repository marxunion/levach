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

    public function quit($token, $nickname, $expires_time)
    {
        if(isset($token))
        {
            if(isset($nickname))
            {
                if(isset($expires_time))
                {
                    $adminInfo = $this->database->get('admins_tokens', ['nickname_encrypted', 'expires_time_encrypted'], ['token' => $token]);
                    if($adminInfo)
                    {
                        if(password_verify($nickname, $adminInfo['nickname_encrypted']))
                        {
                            if(password_verify($expires_time, $adminInfo['expires_time_encrypted']))
                            {
                                $this->database->delete('admins_tokens', ['token' => $token]);
                                return ['success' => true];
                            }
                            else
                            {
                                throw new Error(400, "Invalid expires_time for token", "Invalid expires_time for token");
                            }
                        }
                        else
                        {
                            throw new Error(400, "Invalid nickname for token", "Invalid nickname for token");
                        }
                    }
                    else
                    {
                        throw new Error(400, "Token is invalid", "Token is invalid");
                    }
                }
                else
                {
                    throw new Error(400, "Admin expires_time not found", "Admin expires_time not found");
                }
            }
            else
            {
                throw new Error(400, "Admin nickname not found", "Admin nickname not found");
            }
        }
        else
        {
            throw new Error(400, "Admin token not found", "Admin token not found");
        }
    }   
}