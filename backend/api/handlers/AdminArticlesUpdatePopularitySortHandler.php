<?php
namespace Api\Handlers;

use Core\Error;
use Core\Logger;

use Base\BaseHandlerRoute;

use Api\Models\AdminArticlesUpdatePopularitySortModel;

class AdminArticlesUpdatePopularitySortHandler extends BaseHandlerRoute
{
    public static function _updatePopularityValues()
    {
        AdminArticlesUpdatePopularitySortModel::_updatePopularityValues();
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
                        $this->model = new AdminArticlesUpdatePopularitySortModel();
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
        $this->model->updateTriggers();
        $this->model->updatePopularityValues();
        $this->response = $this->response->withJson(['success' => true]);
    }
}