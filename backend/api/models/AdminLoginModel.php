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
                        throw new Error(400, "Nickname or password is incorrect", "Nickname or password is incorrect");
                    }
                }
                else
                {
                    throw new Error(400, "Nickname or password is incorrect", "Nickname or password is incorrect");
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
    public function createToken($nickname)
    {
        if(isset($nickname))
        {
            $token = bin2hex(random_bytes(random_int(5,15))).hash('sha3-512', uniqid().bin2hex(random_bytes(32))).bin2hex(random_bytes(random_int(5,15)))
            $expiresTimestamp = time() + (24 * 60 * 60);

        }
        
    }
}