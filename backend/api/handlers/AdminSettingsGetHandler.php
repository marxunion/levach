<?php
namespace Api\Handlers;

use Core\Error;

use Core\Settings;

use Base\BaseHandlerRouteWithArgs;

use Api\Models\AdminSettingsGetModel;

use Api\Handlers\CSRFTokenHandler;

class AdminSettingsGetHandler extends BaseHandlerRouteWithArgs
{
    public static function getAllSettings()
    {
        return AdminSettingsModel::_getAllSettings();
    }

    public static function getSetting($settingName)
    {
        if(!empty($settingName))
        {
            $responseData = AdminSettingsGetModel::_getSetting($settingName);
            if(!empty($responseData))
            {
                return $responseData;
            }
            else
            {
                $responseData = Settings::getSetting('default_changeable_'.$settingName);
                if(!empty($responseData))
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
                        if(!empty($this->args['settingName']))
                        {
                            $this->model = new AdminSettingsGetModel();
                        }
                        else
                        {
                            throw new Error(404, "Please set setting for select", "Please set setting for select");
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
        $responseData = $this->model->getSetting($this->args['settingName']);
        if(!empty($responseData))
        {
            $this->response = $this->response->withJson($responseData);
        }
        else
        {
            $responseData = Settings::getSetting('default_changeable_'.$this->args['settingName']);
            if(!empty($responseData))
            {
                $this->response = $this->response->withJson($responseData);
            }
            else
            {
                throw new Error(404, "Selected setting not found", "Selected setting not found");
            }
        }
    }
}