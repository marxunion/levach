<?php
namespace Api\Handlers;

use Core\Error;

use Base\BaseHandlerRoute;

use Api\Models\AdminStatusModel;

class AdminStatusHandler extends BaseHandlerRoute
{
    public static function _isAdmin($cookiesData)
    {
        if(isset($cookiesData['admin_token']))
        {
            $token = $cookiesData['admin_token'];
            if(isset($cookiesData['admin_nickname']))
            {
                $nickname = $cookiesData['admin_nickname'];
                if(isset($cookiesData['admin_expiration_time']))
                {
                    $expirationTime = $cookiesData['admin_expiration_time'];
                    return AdminStatusModel::_isAdmin($token, $nickname, $expirationTime);
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
        $cookiesBody = $this->request->getCookieParams();

        if(is_array($cookiesBody))
        {
            $this->cookiesBody = $cookiesBody;
        }
        else
        {
            throw new Error(400, "Admin token cookie not found", "Admin token cookie not found");
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