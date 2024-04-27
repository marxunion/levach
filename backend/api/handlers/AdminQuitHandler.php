<?php
namespace Api\Handlers;

use Core\Error;

use Base\BaseHandlerRoute;

use Api\Models\AdminQuitModel;

class AdminQuitHandler extends BaseHandlerRoute
{
    public function Init()
    {
        $this->model = new AdminQuitModel();
        $parsedBody = $this->request->getCookieParams();

        if(is_array($parsedBody))
        {
            $this->data = $parsedBody;
        }
        else
        {
            throw new Error(400, "Admin token not found", "Admin token not found");
        }
    }
    public function Process()
    {
        if(isset($this->data['admin_token']))
        {
            $token = $this->data['admin_token'];
            if(isset($this->data['admin_nickname']))
            {
                $nickname = $this->data['admin_nickname'];
                if(isset($this->data['admin_expiration_time']))
                {
                    $expirationTime = $this->data['admin_expiration_time'];
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