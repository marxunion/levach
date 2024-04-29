<?php
namespace Api\Handlers;

use Core\Error;

use Base\BaseHandlerRoute;

use Api\Models\AdminQuitModel;

use Api\Handlers\csrfTokenHandler;

class AdminQuitHandler extends BaseHandlerRoute
{
    public function Init()
    {
        $this->model = new AdminQuitModel();
        $cookiesBody = $this->request->getCookieParams();
        $parsedBody = $this->request->getParsedBody();

        if(is_array($cookiesBody))
        {
            $this->cookiesBody = $cookiesBody;
        }
        else
        {
            throw new Error(400, "Admin token not found", "Admin token not found");
        }

        if(is_array($parsedBody))
        {
            $this->parsedBody = $parsedBody;
            if(isset($this->parsedBody['csrfToken']))
            {
                if(!csrfTokenHandler::checkCsrfToken($this->parsedBody['csrfToken']))
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
            throw new Error(400, "Invalid CSRF token", "Invalid CSRF token");
        }
    }
    public function Process()
    {
        if(isset($this->cookiesBody['admin_token']))
        {
            $token = $this->cookiesBody['admin_token'];
            if(isset($this->cookiesBody['admin_nickname']))
            {
                $nickname = $this->cookiesBody['admin_nickname'];
                if(isset($this->cookiesBody['admin_expiration_time']))
                {
                    $expirationTime = $this->cookiesBody['admin_expiration_time'];
                    $this->response = $this->response->withStatus(200)->withJson($this->model->quit($token, $nickname, $expirationTime));
                    setcookie('admin_token', '', -1, '/');
                    setcookie('admin_nickname', '', -1, '/');
                    setcookie('admin_expiration_time', '', -1, '/');
                }
                else
                {
                    throw new Error(400, "Admin expiration_time not found", "Admin expiration_time not found");
                }
            }
            else
            {
                throw new Error(400, "Admin nickname not found", "Admin nickname not found");
            }
        }
        else
        {
            throw new Error(400, "Admin token not found", "Admin token not found");
        }
    }
}