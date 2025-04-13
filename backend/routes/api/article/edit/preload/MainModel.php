<?php
namespace Routes\Api\Article\Edit\Preload;

use Core\Critical;
use Core\Error;
use Core\Warning;

use Base\BaseModel;

class MainModel extends BaseModel
{
    public function login(string $nickname, string $password)
    {
        if(empty($nickname))
        {
            throw new Warning(400, "Please ether nickname", "Please ether nickname");
        }
        if(empty($password))
        {
            throw new Warning(400, "Please ether password", "Please ether password");
        }

        $passwordEncrypted = $this->database->get('admins', 'password', ['nickname' => $nickname]);
        if(empty($passwordEncrypted))
        {
            throw new Error(400, "Admin nickname or password is incorrect", "Admin nickname or password is incorrect");
        }
        if(!password_verify($password, $passwordEncrypted))
        {
            throw new Error(400, "Admin nickname or password is incorrect", "Admin nickname or password is incorrect");
        }
        
        return true;
    } 
    public function safeToken(string $token, string $nickname, int $expirationTime)
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