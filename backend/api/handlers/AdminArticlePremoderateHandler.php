<?php
namespace Api\Handlers;

use Core\Error;
use Core\Critical;

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
                        if(isset($this->parsedBody['status']))
                        { 
                            if(isset($this->args['viewCode']))
                            {
                                $this->model = new AdminArticlePremoderateModel();
                            }
                            else
                            {
                                throw new Error(400, "Article not found", "Article not found");
                            }
                        }
                        else 
                        {
                            throw new Error(400, "Premoderation status not found", "Premoderation status not found");
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
        $articleId = $this->model->getArticleByViewCode($this->args['viewCode'];);
        if(isset($articleId))
        {
            if($this->parsedBody['status'] == 0)
            {
                
                if($this->model->rejectPremoderate())
                {
                    $this->response = $this->response->withJson(['success' => true]);
                }
                else
                {
                    throw new Critical(500, "Failed to reject premoderate article", "Failed to reject premoderate article");
                }
            }
            else if($this->parsedBody['status'] == 1)
            {
                if($this->model->acceptPremoderate())
                {
                    $this->response = $this->response->withJson(['success' => true]);
                }
                else
                {
                    throw new Critical(500, "Failed to premoderate article", "Failed to premoderate article");
                }
            }
            else
            {
                throw new Error(404, "Unknown status", "Unknown status");
            }
        }
        else
        {
            throw new Error(404, "Article not found", "Article not found");
        }
    };
}