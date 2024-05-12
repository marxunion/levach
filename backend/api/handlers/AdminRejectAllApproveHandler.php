<?php
namespace Api\Handlers;

use Core\Error;
use Core\Warning;
use Core\Critical;

use Base\BaseHandlerRoute;

use Api\Models\AdminRejectAllApproveModel;

class AdminRejectAllApproveHandler extends BaseHandlerRoute
{
    public function Init()
    {
        $parsedBody = $this->request->getParsedBody();
        if(is_array($parsedBody))
        {
            $this->parsedBody = $parsedBody;
            
            if(!empty($this->parsedBody['csrfToken']))
            {
                if(csrfTokenHandler::checkCsrfToken($this->parsedBody['csrfToken']))
                {
                    if(AdminStatusHandler::isAdmin($this->request->getCookieParams()))
                    {
                        $this->model = new AdminRejectAllApproveModel();
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
        $this->model->rejectAllApproveModel();
        $this->response = $this->response->withJson(['success' => true]);
    }
}