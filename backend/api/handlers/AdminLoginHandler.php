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
        }
        else
        {
            throw new Error(400, "Admin nickname not found", "Admin nickname not found");
        }
    }
    public function Process()
    {
        if(isset($this->data['c']))
        {

        }
    }
}