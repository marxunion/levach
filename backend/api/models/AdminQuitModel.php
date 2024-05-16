<?php
namespace Api\Models;

use Core\Warning;
use Core\Error;

use Base\BaseModel;

class AdminQuitModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function quit($token, $nickname, $expirationTime)
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
                                $this->database->delete('admins_tokens', ['token' => $token]);
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
                    throw new Warning(400, "Admin expiration_time not found", "Admin expiration_time not found");
                }
            }
            else
            {
                throw new Warning(400, "Admin nickname not found", "Admin nickname not found");
            }
        }
        else
        {
            throw new Warning(400, "Admin token not found", "Admin token not found");
        }
    }   
}