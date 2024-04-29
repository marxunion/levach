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
        $parsedBody = $this->request->getParsedBody();

        if(is_array($parsedBody))
        {
            $this->parsedBody = $parsedBody;
            if(isset($this->parsedBody['csrfToken']))
            {
                if(csrfTokenHandler::checkCsrfToken($this->parsedBody['csrfToken']))
                {
                    if(isset($this->parsedBody['nickname']))
                    {
                        if(!isset($this->parsedBody['password']))
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
                    throw new Error(403, "Invalid CSRF token", "Invalid CSRF token");
                }
            }
            else
            {
                throw new Error(403, "Invalid CSRF token", "Invalid CSRF token");
            }
        }
        else
        {
            throw new Error(400, "Admin nickname not found", "Admin nickname not found");
        }
    }
    public function Process()
    {
        $nickname = $this->parsedBody['nickname'];
        $password = $this->parsedBody['password'];
        
        if($this->model->login($nickname, $password))
        {
            $token = bin2hex(random_bytes(random_int(5,15))).hash('sha3-512', uniqid().bin2hex(random_bytes(random_int(120,150)))).bin2hex(random_bytes(random_int(5,15)));
            
            if(isset($this->parsedBody['rememberMe']))
            {
                if($this->parsedBody['rememberMe'])
                {
                    $expirationTime = time() + (7 * 24 * 60 * 60);
                }
                else
                {
                    $expirationTime = time() + (60 * 60);
                }
            }
            else
            {
                $expirationTime = time() + (60 * 60);
            }

            if($this->model->safeToken($token, $nickname, $expirationTime))
            {
                setcookie('admin_token', $token, $expirationTime, '/');
                setcookie('admin_nickname', $nickname, $expirationTime, '/');
                setcookie('admin_expiration_time', $expirationTime, $expirationTime, '/');
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