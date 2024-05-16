<?php
namespace Api\Models;

use Core\Critical;
use Core\Error;
use Core\Warning;

use Base\BaseModel;

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
                throw new Warning(400, "Please ether password", "Please ether password");
            }
        } 
        else
        {
            throw new Warning(400, "Please ether nickname", "Please ether nickname");
        }
    } 
    public function safeToken($token, $nickname, $expirationTime)
    {
        $data = 
        [
            'token' =>  $token,
            'nickname_encrypted' => password_hash($nickname, PASSWORD_DEFAULT),
            'expiration_time_encrypted' => password_hash($expirationTime, PASSWORD_DEFAULT)
        ];
        if($this->database->insert('admins_tokens', $data))
        {
            return true;
        }
        else
        {
            throw new Critical(500, "Unknown error", "Failed to safe admin token");
        }
    }
}