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
            throw new Warning(400, "Please add a title for the article", "Empty article title");
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
                    $this->response = $this->response->withStatus(200)->withJson($this->model->quit($_COOKIE));
                }
                else
                {
                    throw new Error(404, "Admin created_at not found", "Admin created_at not found");
                }
            }
            else
            {
                throw new Error(404, "Admin nickname not found", "Admin nickname not found");
            }
        }
        else
        {
            throw new Error(404, "Admin token not found", "Admin token not found");
        }
    }
}