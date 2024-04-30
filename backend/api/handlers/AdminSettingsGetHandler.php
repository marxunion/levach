<?php
namespace Api\Handlers;

use Core\Error;

use Base\BaseHandlerRoute;

use Api\Models\AdminSettingsGetModel;

use Api\Handlers\csrfTokenHandler;

class AdminSettingsGetHandler extends BaseHandlerRoute
{
    public static function getAllSettings()
    {
        return AdminSettingsModel::_getAllSettings();
    }

    public static function getSetting($settingName)
    {
        if(isset($settingName))
        {
            $responseData = AdminSettingsGetModel::_getSetting($settingName);
            if(isset($responseData))
            {
                return $responseData;
            }
            else
            {
                $responseData = Settings::getSetting('default_'.$settingName);
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
            
            if(isset($this->parsedBody['csrfToken']))
            {
                if(csrfTokenHandler::checkCsrfToken($this->parsedBody['csrfToken']))
                {
                    if(AdminStatusHandler::isAdmin($this->request->getCookieParams()))
                    {
                        $this->model = new AdminSettingsGetModel();
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
        else
        {
            throw new Error(403, "Invalid CSRF token", "Invalid CSRF token");
        }
    }

    public function Process()
    {
        if(isset($this->parsedBody['settingName']))
        {
            $responseData = $this->model->getSetting($this->parsedBody['settingName']);
            if(isset($responseData))
            {
                $this->response = $this->response->withJson($responseData);
            }
            else
            {
                $responseData = Settings::getSetting('default_'.$settingName);
                if(isset($responseData))
                {
                    $this->response = $this->response->withJson($responseData);
                }
                else
                {
                    throw new Error(404, "Selected setting not found", "Selected setting not found");
                }
            }
        }   
        else
        {
            $this->response = $this->response->withJson($this->model->getAllSettings());
        }
    }
}