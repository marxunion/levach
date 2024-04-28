<?php
namespace Api\Handlers;

use Core\Error;

use Base\BaseHandlerRoute;

use Api\Models\AdminSettingsGetModel;

class AdminSettingsGetHandler extends BaseHandlerRoute
{
    public function Init()
    {
        if(AdminStatusHandler::isAdmin())
        {
            $this->model = new AdminSettingsGetModel();
        }
        else
        {
            throw new Error(400, "Token is invalid", "Token is invalid");
        }
    }

    public function Process()
    {
        $this->response = $this->response->withJson($this->model->get());
    }
}