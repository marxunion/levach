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
        $parsedBody = $this->request->getQueryParams();

        if(is_array($parsedBody))
        {
            $this->data = $parsedBody;
            if(isset($this->data['nickname']))
            {
                if(!isset($this->data['password']))
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
            throw new Error(400, "Admin nickname not found", "Admin nickname not found");
        }
    }
    public function Process()
    {
        $nickname = $this->data['nickname'];
        $password = $this->data['password'];
        $this->response = $this->response->withStatus(200)->withJson($this->model->login($nickname, $password));
    }
}