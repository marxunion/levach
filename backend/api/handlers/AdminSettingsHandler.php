<?php
namespace Api\Handlers;

use Core\Error;

use Base\BaseHandlerRoute;

use Api\Models\AdminSettingsGetModel;
use Api\Handlers\AdminStatusHandler;

class AdminSettingsHandler extends BaseHandlerRoute
{
    public static function _getProperties()
    {

    }

    public static function _getProperty()
    {

    }

    public static function _setProperty($cookieParams)
    {
        if(AdminStatusHandler::_isAdmin($cookieParams)) 
        {
            
        }
        else
        {
            
        }
    }

    public function getProperties()
    {

    }

    public function getProperty()
    {

    }

    public function setProperty()
    {
        if(AdminStatusHandler::_isAdmin($this->request->getCookieParams()))
        {

        }
    }
    public function Init()
    {
        if(AdminStatusHandler::_isAdmin($this->request->getCookieParams()))
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