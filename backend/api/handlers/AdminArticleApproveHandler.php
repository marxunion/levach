<?php
namespace Api\Handlers;

use Core\Error;

use Base\BaseHandlerRouteWithArgs;

use Api\Models\AdminArticleApproveModel;

class AdminArticleApproveHandler extends BaseHandlerRouteWithArgs
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
                            $this->model = new AdminArticleApproveModel();
                        }
                        else
                        {
                            throw new Error(400, "Article not found", "Article not found");
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
            if(isset($this->parsedBody['status']))
            {   
                if($this->parsedBody['status'])
                {
                    if($this->model->acceptPremoderate())
                    {
                        return $this->response->withJson(['success' => true]);
                    }
                    else
                    {
                        throw new Critical(500, "Failed to premoderate article", "Failed to premoderate article");
                    }
                }
                else
                {

                }
            }
            else 
            {
                throw new Error(400, "Approve status", "Unknown premoderation status");
            }
        }
        else
        {
            throw new Error(400, "Article not found", "Article not found");
        }
    };
}