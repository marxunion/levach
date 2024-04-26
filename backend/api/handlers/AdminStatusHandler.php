<?php
namespace Api\Handlers;

use Core\Error;

use Base\BaseHandlerRoute;

use Api\Models\AdminStatusModel;

class AdminQuitHandler extends BaseHandlerRoute
{
    public function static isAdmin($cookiesData)
    {
        if(isset($cookiesData['admin_token']))
        {
            $token = $cookiesData['admin_token'];
            if(isset($cookiesData['admin_nickname']))
            {
                $nickname = $cookiesData['admin_nickname'];
                if(isset($cookiesData['admin_expiration_time']))
                {
                    return AdminStatusModel::_isAdmin();
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        } 
    }

    public function Init()
    {
        $this->model = new AdminStatusModel();
        $parsedBody = $this->request->getCookieParams();

        if(is_array($parsedBody))
        {
            $this->data = $parsedBody;
        }
        else
        {
            throw new Error(400, "Admin token cookie not found", "Admin token cookie not found");
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
                    $this->response = $this->response->withStatus(200)->withJson($this->model->isAdmin($token, $nickname, $expirationTime));
                }
                else
                {
                    throw new Error(400, "Admin expiration_time cookie not found", "Admin expiration_time cookie not found");
                }
            }
            else
            {
                throw new Error(400, "Admin nickname cookie not found", "Admin nickname cookie not found");
            }
        }
        else
        {
            throw new Error(400, "Admin token cookie not found", "Admin token cookie not found");
        }
    }
}