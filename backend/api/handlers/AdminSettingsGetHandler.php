<?php
namespace Api\Handlers;

use Core\Error;

use Core\Settings;

use Base\BaseHandlerRoute;

use Api\Models\AdminSettingsGetModel;

use Api\Handlers\CSRFTokenHandler;

class AdminSettingsGetHandler extends BaseHandlerRoute
{
    public static function getSetting($settingName)
    {
        if(!empty($settingName))
        {
            $responseData = AdminSettingsGetModel::_getSetting($settingName);
            if(isset($responseData))
            {
                return $responseData;
            }
            else
            {
                $responseData = Settings::getSetting('default_changeable_'.$settingName);
                if(isset($responseData))
                {
                    return $responseData;
                }
                else
                {
                    return null;
                }
            }
        }
        else
        {
            return null;
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
                        if(!empty($this->parsedBody['settings']))
                        {
                            if(is_array($this->parsedBody['settings']))
                            {
                                $this->model = new AdminSettingsGetModel();
                            }
                            else
                            {
                                throw new Error(404, "Please set settings for select", "Please set settings for select");
                            }
                        }
                        else
                        {
                            throw new Error(404, "Please set settings for select", "Please set settings for select");
                        }
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
        $settingsToReturn = [];
        foreach($this->parsedBody['settings'] as &$settingName) 
        {
            $responseData = $this->model->getSetting($settingName);
            if(isset($responseData))
            {
                $settingsToReturn[$settingName] = $responseData;
            }
            else
            {
                $responseData = Settings::getSetting('default_changeable_'.$settingName);
                if(isset($responseData))
                {
                    $settingsToReturn[$settingName] = $responseData;
                }
            }
        }
        if(count($settingsToReturn) > 0)
        {
            $this->response = $this->response->withJson($settingsToReturn);
        }
        else
        {
            throw new Error(404, "Settings not found", "Settings not found");
        }
    }
}