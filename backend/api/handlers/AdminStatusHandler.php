<?php
namespace Api\Handlers;

use Core\Error;

use Base\BaseHandlerRoute;

use Api\Models\AdminStatusModel;

class AdminQuitHandler extends BaseHandlerRoute
{
    public function Init()
    {
        $this->model = new AdminStatusModel();
        $parsedBody = $this->request->cookies;

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
        if(isset($this->data['token']))
        {
            $token = $this->data['admin_token'];
            if(isset($this->data['token']))
            {
                $nickname = $this->data['admin_nickname'];
                if(isset($this->data['token']))
                {
                    $created_at = $this->data['admin_created_at'];
                    $this->response = $this->response->withStatus(200)->withJson($this->model->isAdmin($token, $nickname, $created_at));
                }
                else
                {
                    throw new Error(400, "Admin created_at cookie not found", "Admin created_at cookie not found");
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