<?php
namespace Api\Handlers;

use Core\Warning;
use Core\Error;

use Base\BaseHandlerRoute;

use Api\Models\AdminSettingsSetModel;

class AdminSettingsSetHandler extends BaseHandlerRoute
{
    public function Init()
    {
        if(AdminStatusHandler::isAdmin())
        {
            $this->model = new AdminSettingsSetModel();
            $parsedBody = $this->request->getParsedBody();

            if(!is_array($parsedBody))
            {
                throw new Warning(400, "Settings for set not found", "Settings for set not found");
            }
        }
        else
        {
            throw new Error(400, "Token is invalid", "Token is invalid");
        }
    }

    public function Process()
    {
        
    }
}