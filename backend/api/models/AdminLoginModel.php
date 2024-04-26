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

    public function login($nickname, $password)
    {
        if(isset($nickname))
        {
            if(isset($password))
            {
                $passwordEncrypted = $this->database->get('admins', 'password', ['nickname' => $nickname]);
                if(isset($passwordEncrypted))
                {
                    if(password_verify($password, $passwordEncrypted))
                    {
                        return true;
                    }
                    else
                    {
                        throw new Error(400, "Admin nickname or password is incorrect", "Admin nickname or password is incorrect");
                    }
                }
                else
                {
                    throw new Error(400, "Admin nickname or password is incorrect", "Admin nickname or password is incorrect");
                }
            }
            else
            {
                throw new Error(400, "Admin password not found", "Admin password not found");
            }
        } 
        else
        {
            throw new Error(400, "Admin nickname not found", "Admin nickname not found");
        }
    } 
    public function safeToken($token, $nickname, $expirationTime)
    {
        $data = 
        [
            'token' =>  $token,
            'nickname_encrypted' => password_hash($nickname, PASSWORD_DEFAULT),
            'expiration_time_encrypted' => password_hash($expirationTime, PASSWORD_DEFAULT)
        ]
        if($this->database->insert('admin_tokens', $data))
        {
            return true;
        }
        else
        {
            throw new Critical(500, "Unknown error", "Failed to safe admin token");
        }
    }
}