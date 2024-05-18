<?php
namespace Api\Models;

use Core\Database;
use Core\Error;

use Base\BaseModel;

class AdminStatusModel extends BaseModel
{
    public static function _isAdmin(string $token, string $nickname, string|int $expirationTime)
    {
        if(empty($token))
        {
            return false;
        }
        if(empty($nickname))
        {
            return false;
        }

        if(empty($expirationTime))
        {
            return false;
        }

        $database = Database::getConnection();
        $adminInfo = $database->get('admins_tokens', ['nickname_encrypted', 'expiration_time_encrypted'], ['token' => $token]);
        if(!$adminInfo)
        {
            return false;
        }
        if(!password_verify($nickname, $adminInfo['nickname_encrypted']))
        {
            return false;
        }
            
        if(!password_verify($expirationTime, $adminInfo['expiration_time_encrypted']))
        {
            return false;
        }

        if(time() >= $expirationTime)
        {
            return false;
        }
        return true;
    }

    public function isAdmin(string $token, string $nickname, string|int $expirationTime)
    {
        if(empty($token)) 
        {
            throw new Error(400, "Admin token not found", "Admin token not found");
        }
        if(empty($nickname)) 
        {
            throw new Error(400, "Admin nickname not found", "Admin nickname not found");
        }
        if(empty($expirationTime)) 
        {
            throw new Error(400, "Admin expiration_time not found", "Admin expiration_time not found");
        }

        $adminInfo = $this->database->get('admins_tokens', ['nickname_encrypted', 'expiration_time_encrypted'], ['token' => $token]);

        if(!$adminInfo) 
        {
            throw new Error(403, "Invalid admin token", "Invalid admin token");
        }
        if(!password_verify($nickname, $adminInfo['nickname_encrypted'])) 
        {
            throw new Error(400, "Invalid nickname for token", "Invalid nickname for token");
        }
        if(!password_verify($expirationTime, $adminInfo['expiration_time_encrypted'])) 
        {
            throw new Error(400, "Invalid expiration_time for token", "Invalid expiration_time for token");
        }
        if(time() >= $expirationTime) 
        {
            throw new Error(400, "Token already expired", "Token already expired");
        }

        return ['success' => true];
    }  
}