<?php
namespace Api\Handlers;

use Core\Error;

use Base\BaseHandlerRoute;

use Api\Models\AdminLoginModel;

class AdminLoginHandler extends BaseHandlerRoute
{
    public function Init()
    {
        $this->model = new AdminLoginModel();
        $parsedBody = $this->request->getQueryParams();

        if(is_array($parsedBody))
        {
            $this->data = $parsedBody;
            if(isset($this->data['nickname']))
            {
                if(!isset($this->data['password']))
                {
                    throw new Error(400, "Admin password not found", "Admin password not found");
                }
            }
            else
            {
                throw new Error(400, "Admin nickname not found", "Admin nickname not found");
            }
        }
        else
        {
            throw new Error(400, "Admin nickname not found", "Admin nickname not found");
        }
    }
    public function Process()
    {
        $nickname = $this->data['nickname'];
        $password = $this->data['password'];
        
        if($this->model->login($nickname, $password))
        {
            $token = bin2hex(random_bytes(random_int(5,15))).hash('sha3-512', uniqid().bin2hex(random_bytes(32))).bin2hex(random_bytes(random_int(5,15)))
            $expirationTime = time() + (24 * 60 * 60);

            if($this->model->safeToken($token, $nickname, $expirationTime))
            {
                $this->response = $response->withCookie('admin_token', $token, 
                [
                    'expires' => $expirationTime,
                    'path' => '/',
                ]);

                $this->response = $response->withCookie('admin_nickname', $nickname, 
                [
                    'expires' => $expirationTime,
                    'path' => '/',
                ]);               
                
                $this->response = $response->withCookie('admin_expiration_time', $expirationTime, 
                [
                    'expires' => $expirationTime,
                    'path' => '/',
                ]);
                $this->response = $this->response->withStatus(200)->withJson(['success' => true]);
            }
            else
            {
                throw new Critical(500, "Unknown error", "Failed to safe admin token");
            }
        }
        else
        {
            throw new Error(400, "Admin nickname or password is incorrect", "Admin nickname or password is incorrect");
        }
    }
}