<?php
namespace Routes\Api\Admin\Quit;

use Core\Warning;
use Core\Error;

use Base\BaseModel;

class MainModel extends BaseModel
{
    public function quit(string $token, string $nickname, string|int $expirationTime)
    {
        if(empty($token))
        {
            throw new Warning(400, "Admin token not found", "Admin token not found");
        }
        if(empty($nickname))
        {
            throw new Warning(400, "Admin nickname not found", "Admin nickname not found");
        }
        if(empty($expirationTime))
        {
            throw new Warning(400, "Admin expiration_time not found", "Admin expiration_time not found");
        }
        
        $adminInfo = $this->database->get('admins_tokens', ['nickname_encrypted', 'expiration_time_encrypted'], ['token' => $token]);
        if($adminInfo)
        {
            if(!password_verify($nickname, $adminInfo['nickname_encrypted']))
            {
                throw new Error(400, "Invalid nickname for token", "Invalid nickname for token");
            }
            if(!password_verify($expirationTime, $adminInfo['expiration_time_encrypted']))
            {
                throw new Error(400, "Invalid expiration_time for token", "Invalid expiration_time for token");
            }
            $this->database->delete('admins_tokens', ['token' => $token]);
        }
    }   
}