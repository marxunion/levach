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
        $parsedBody = $this->request->cookies;

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
        if(isset($this->data['token']))
        {
            $token = $this->data['admin_token'];
            if(isset($this->data['admin_nickname']))
            {
                $nickname = $this->data['admin_nickname'];
                if(isset($this->data['admin_created_at']))
                {
                    $created_at = $this->data['admin_created_at'];
                    $this->response = $this->response->withStatus(200)->withJson($this->model->quit($token, $nickname, $created_at));
                }
                else
                {
                    throw new Error(400, "Admin created_at not found", "Admin created_at not found");
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