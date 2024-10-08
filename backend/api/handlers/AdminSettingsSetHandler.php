<?php
namespace Api\Handlers;

use Core\Warning;
use Core\Error;

use Base\BaseHandlerRoute;

use Api\Models\AdminSettingsSetModel;

use Api\Handlers\CSRFTokenHandler;

class AdminSettingsSetHandler extends BaseHandlerRoute
{
    public static function setSettings($settings, $cookiesBody)
    {
        if(AdminStatusHandler::isAdmin($cookiesBody))
        {
            if(is_array($settings))
            {
                foreach($settings as $settingName => $settingValue) 
                {
                    $this->model->setSetting($settingName, $settingValue);
                }
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }  
    }
    
    public static function setSetting($settingName, $settingValue, $cookiesBody)
    {
        if(AdminStatusHandler::isAdmin($cookiesBody))
        {
            if(isset($settingName))
            {
                if(isset($settingValue))
                {
                    if(AdminSettingsSetModel::_setSetting($settingName, $settingValue))
                    {
                        if($settingName == "articles_popularity_sort_formula")
                        {
                            AdminSettingsSetModel::_updatePopularitySortTriggers($settingValue);
                        }
                        return true;
                    }
                    else
                    {
                        return false;
                    }
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    public function Init()
    {
        $parsedBody = $this->request->getParsedBody();
        if(is_array($parsedBody))
        {
            $this->parsedBody = $parsedBody;
            
            if(!empty($this->parsedBody['csrfToken']))
            {
                if(CSRFTokenHandler::checkCsrfToken($this->parsedBody['csrfToken']))
                {
                    if(AdminStatusHandler::isAdmin($this->request->getCookieParams()))
                    {
                        $this->model = new AdminSettingsSetModel();
                    }
                    else
                    {
                        throw new Error(403, "Invalid admin token", "Invalid admin token");
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
        else
        {
            throw new Error(400, "Invalid request body", "Invalid request body");
        }
    }

    public function Process()
    {
        if(isset($this->parsedBody['settings']))
        {
            if(is_array($this->parsedBody['settings']))
            {
                foreach($this->parsedBody['settings'] as $settingName => $settingValue) 
                {
                    $this->model->setSetting($settingName, $settingValue);
                }
                $this->response = $this->response->withJson(['success' => true]);
            }
            else
            {
                throw new Warning(400, "Please select settings to set", "Please select settings to set");
            }
            
        }
        else if(isset($this->parsedBody['settingName']))
        {
            if(isset($this->parsedBody['settingValue']))
            {
                $this->model->setSetting($this->parsedBody['settingName'], $this->parsedBody['settingValue']);
                $this->response = $this->response->withJson(['success' => true]);
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