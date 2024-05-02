<?php
namespace Api\Handlers;

use Core\Error;

use Base\BaseHandlerRouteWithArgs;

use Api\Models\AdminArticlePremoderateModel;

class AdminArticlePremoderateHandler extends BaseHandlerRouteWithArgs
{
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
                        if(isset($this->args['viewCode']))
                        {
                            $this->model = new AdminArticlePremoderateModel();
                        }
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
    };

    public function Process()
    {
        if(isset($this->parsedBody['status']))
        {   
            if($this->parsedBody['status'])
            {
                if($this->model->acceptPremoderate())
                {

                }
                else
                {

                }
            }
            else
            {
                if($this->model->rejectPremoderate())
                {

                }
                else
                {

                }
            }
        }
        else 
        {
            throw new Error(400, "Unknown premoderation status", "Unknown premoderation status");
        }
    };
}