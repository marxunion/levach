<?php
namespace Api\Handlers;

use Core\Error;

use Base\BaseHandlerRoute;

use Api\Models\AdminSettingsModel;

use Api\Handlers\csrfTokenHandler;
use Api\Handlers\AdminStatusHandler;

class AdminSettingsHandler extends BaseHandlerRoute
{
    public static function _getAllProperties()
    {
        $this->response = $this->response->withJson(AdminSettingsModel::_getProperties());
    }

    public static function _getProperty($propertyName)
    {
        if(isset($propertyName))
        {
            $responseData = AdminSettingsModel::_getProperty($propertyName);
            if($responseData)
            {
                $this->response = $this->response->withJson($responseData);
            }
            else
            {
                $responseData = Settings::getProperty('default_'.$propertyName);
                if($responseData)
                {
                    $this->response = $this->response->withJson($responseData);
                }
                else
                {
                    throw new Error(404, "Selected property not found", "Selected property not found");
                }
            }
            
        }
        else
        {
            throw new Warning(400, "Please select setting to get", "Please select setting to get");
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
                            throw new Critical(500, "Failed to set property", "Failed to set property");
                        }
                    }
                    else
                    {
                        throw new Error(400, "Invalid admin token", "Invalid admin token");
                    }
                }
                else
                {
                    throw new Error(400, "Invalid admin token", "Invalid admin token");
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
        $parsedBody = $this->request->getParsedBody();
        if(is_array($parsedBody))
        {
            $this->data = $parsedBody;
            if(isset($this->data['csrfToken']))
            {
                if(csrfTokenHandler::checkCsrfToken($this->data['csrfToken']))
                {
                    if(AdminStatusHandler::_isAdmin($this->request->getCookieParams()))
                    {
                        $this->model = new AdminSettingsModel();
                    }
                    else
                    {
                        throw new Error(400, "Invalid admin token", "Invalid admin token");
                    }
                }
                else
                {
                    throw new Error(403, "Invalid CSRF token", "Invalid CSRF token");
                }
            }
            else
            {
                throw new Error(403, "Invalid CSRF token", "Invalid CSRF token");
            }
        }
    }

    public function getAllProperties()
    {
        $this->response = $this->response->withJson($this->model->getAllProperties());
    }

    public function getProperty($propertyName)
    {
        if(isset($propertyName))
        {
            $responseData = $this->model->getProperty();
            if($responseData)
            {
                $this->response = $this->response->withJson($responseData);
            }
            else
            {
                $responseData = Settings::getProperty('default_'.$propertyName);
                if($responseData)
                {
                    $this->response = $this->response->withJson($responseData);
                }
                else
                {
                    throw new Error(404, "Selected property not found", "Selected property not found");
                }
            }
            
        }
        else
        {
            throw new Warning(400, "Please select setting to get", "Please select setting to get");
        }
    }

    public function setProperty($propertyName, $propertyValue)
    {
        if(isset($propertyName))
        {
            if(isset($propertyValue))
            {
                if($this->model->setProperty($propertyName, $propertyValue))
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