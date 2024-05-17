<?php
namespace Api\Models;

use Core\Database;
use Core\Error;

use Base\BaseModel;

class AdminStatusModel extends BaseModel
{
    public static function _isAdmin($token, $nickname, $expirationTime)
    {
        if(isset($token))
        {
            if(isset($nickname))
            {
                if(isset($expirationTime))
                {
                    $database = Database::getConnection();
                    $adminInfo = $database->get('admins_tokens', ['nickname_encrypted', 'expiration_time_encrypted'], ['token' => $token]);
                    if($adminInfo)
                    {
                        if(password_verify($nickname, $adminInfo['nickname_encrypted']))
                        {
                            if(password_verify($expirationTime, $adminInfo['expiration_time_encrypted']))
                            {
                                if(time() < $expirationTime)
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
    public function isAdmin($token, $nickname, $expirationTime)
    {
        if(isset($token))
        {
            if(isset($nickname))
            {
                if(isset($expirationTime))
                {
                    $adminInfo = $this->database->get('admins_tokens', ['nickname_encrypted', 'expiration_time_encrypted'], ['token' => $token]);
                    if($adminInfo)
                    {
                        if(password_verify($nickname, $adminInfo['nickname_encrypted']))
                        {
                            if(password_verify($expirationTime, $adminInfo['expiration_time_encrypted']))
                            {
                                if(time() < $expirationTime)
                                {
                                    return ['success' => true];
                                }
                                else
                                {
                                    throw new Error(400, "Token already expired", "Token already expired");
                                }
                            }
                            else
                            {
                                throw new Error(400, "Invalid expiration_time for token", "Invalid expiration_time for token");
                            }
                        }
                        else
                        {
                            throw new Error(400, "Invalid nickname for token", "Invalid nickname for token");
                        }
                    }
                    else
                    {
                        throw new Error(403, "Invalid admin token", "Invalid admin token");
                    }
                }
                else
                {
                    throw new Error(400, "Admin expiration_time not found", "Admin expiration_time not found");
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