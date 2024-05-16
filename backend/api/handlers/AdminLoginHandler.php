<?php
namespace Api\Handlers;

use Core\Error;
use Core\Critical;

use Base\BaseHandlerRoute;

use Api\Handlers\AdminStatusHandler;

use Api\Models\AdminLoginModel;

class AdminLoginHandler extends BaseHandlerRoute
{
    public function Init()
    {
        $parsedBody = $this->request->getParsedBody();

        if(is_array($parsedBody))
        {
            $this->parsedBody = $parsedBody;
            
            if(!empty($this->parsedBody['captchaToken']))
            {
                if(CaptchaTokenHandler::checkCaptchaToken($this->request->getServerParam('REMOTE_ADDR'), $this->parsedBody['captchaToken']))
                {
                    if(!empty($this->parsedBody['csrfToken']))
                    {
                        if(CSRFTokenHandler::checkCsrfToken($this->parsedBody['csrfToken']))
                        {
                            if(!AdminStatusHandler::isAdmin($this->request->getCookieParams()))
                            {
                                if(!empty($this->parsedBody['nickname']))
                                {
                                    if(!empty($this->parsedBody['password']))
                                    {
                                        $this->model = new AdminLoginModel();
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
                            else
                            {
                                throw new Error(400, "Your already logged in", "Your already logged in");
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
                    throw new Error(400, "Invalid captcha solving", "Invalid captcha solving");
                }
            }
            else
            {
                throw new Error(400, "Invalid captcha solving", "Invalid captcha solving");
            }
        }
        else
        {
            throw new Error(400, "Invalid request body", "Invalid request body");
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
                $this->response = $this->response->withJson(['success' => true]);
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