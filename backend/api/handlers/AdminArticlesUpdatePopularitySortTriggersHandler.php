<?php
namespace Api\Handlers;

use Core\Warning;
use Core\Error;

use Base\BaseHandlerRoute;

use Api\Models\AdminArticlesUpdatePopularitySortTriggersModel;

class AdminArticlesUpdatePopularitySortTriggersHandler extends BaseHandlerRoute
{
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
                        $this->model = new AdminArticlesUpdatePopularitySortTriggersModel();
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