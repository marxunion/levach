<?php
namespace Api\Handlers;

use Core\Error;

use Base\BaseHandlerRoute;

use Api\Models\AdminSettingsModel;
use Api\Handlers\AdminStatusHandler;

class AdminSettingsHandler extends BaseHandlerRoute
{
    public static function _getProperties()
    {
        $this->response = $this->response->withJson(AdminSettingsModel::_getProperties());
    }

    public static function _getProperty($propertyName)
    {
        if(isset($propertyName))
        {
            $this->response = $this->response->withJson(AdminSettingsModel::_getProperty($propertyName));
        }
    }

    public static function _setProperty($propertyName, $propertyValue, $cookieParams)
    {
        if(isset($propertyName))
        {
            if(isset($propertyValue))
            {
                if(isset($cookieParams))
                {
                    if(AdminStatusHandler::_isAdmin($cookieParams)) 
                    {
                        if(AdminSettingsModel::_setProperty($propertyName, $propertyValue))
                        {
                            $this->response = $this->response->withJson(['success' => true]);
                        }
                        else
                        {
                            throw new Error(500, "Failed to set property", "Failed to set property");
                        }
                    }
                    else
                    {
                        throw new Error(400, "Token is invalid", "Token is invalid");
                    }
                }
                else
                {
                    throw new Error(400, "Token is invalid", "Token is invalid");
                }
            }
            else
            {
                throw new Warning(400, "Please select value to set", "Please select value to set");
            }
        }
        else
        {
            throw new Warning(400, "Please select setting to set", "Please select setting to set");
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

    public function getProperties()
    {
        
    }

    public function getProperty($propertyName)
    {
        if(isset($propertyName))
        {
            
        }
    }

    public function setProperty($propertyName, $propertyValue)
    {
        if(isset($propertyName))
        {
            if(isset($propertyValue))
            {
                if(AdminSettingsModel::_setProperty($propertyName, $propertyValue))
                {
                    $this->response = $this->response->withJson(['success' => true]);
                }
                else
                {
                    throw new Error(500, "Failed to set property", "Failed to set property");
                }
            }
            else
            {
                throw new Warning(400, "Please select value to set", "Please select value to set");
            }
        }
        else
        {
            throw new Warning(400, "Please select setting to set", "Please select setting to set");
        }
    }
}