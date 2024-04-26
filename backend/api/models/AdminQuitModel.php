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

    public function quit($token, $nickname, $created_at)
    {
        if(isset($token))
        {
            if(isset($nickname))
            {
                if(isset($created_at))
                {
                    $adminInfo = $this->database->get('admins_tokens', ['nickname_encrypted', 'created_at_encrypted'], ['token' => $token]);
                    if($adminInfo)
                    {
                        if(password_verify($nickname, $adminInfo['nickname_encrypted']))
                        {
                            if(password_verify($created_at, $adminInfo['created_at_encrypted']))
                            {
                                $this->database->delete('admins_tokens', ['token' => $token]);
                                return ['success' => true];
                            }
                            else
                            {
                                throw new Error(400, "Invalid created_at for token", "Invalid created_at for token");
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
                    throw new Error(400, "Admin created_at not found", "Admin created_at not found");
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