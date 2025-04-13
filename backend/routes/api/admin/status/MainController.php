<?php
namespace Routes\Api\Admin\Status;

use Core\Error;

use Base\BaseControllerRoute;

use Api\Models\AdminStatusModel;

class MainController extends BaseControllerRoute
{
    public static function isAdmin($cookiesBody)
    {
        if(!empty($cookiesBody['admin_token']))
        {
            $token = $cookiesBody['admin_token'];
            if(!empty($cookiesBody['admin_nickname']))
            {
                $nickname = $cookiesBody['admin_nickname'];
                if(!empty($cookiesBody['admin_expiration_time']))
                {
                    $expirationTime = $cookiesBody['admin_expiration_time'];
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
        $cookiesBody = $this->request->getCookieParams();

        if(is_array($cookiesBody))
        {
            $this->cookiesBody = $cookiesBody;

            $this->model = new AdminStatusModel();
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
                    $this->response = $this->response->withJson($this->model->isAdmin($token, $nickname, $expirationTime));
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