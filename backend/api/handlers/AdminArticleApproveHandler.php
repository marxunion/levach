<?php
namespace Api\Handlers;

use Core\Error;
use Core\Critical;

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
                        if(isset($this->parsedBody['status']))
                        {
                            if(isset($this->args['viewCode']))
                            {
                                $this->model = new AdminArticleApproveModel();
                            }
                            else
                            {
                                throw new Error(404, "Article not found", "Article not found");
                            }
                        }
                        else
                        {
                            throw new Error(404, "Approved status not found", "Approved status not found");
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
                if($this->model->rejectApprove())
                {
                    
                }
                else
                {
                    throw new Critical(500, "Failed to reject approve article", "Failed to reject approve article");
                }
            }
            else if($this->parsedBody['status'] == 1)
            {
                
            }
            else if($this->parsedBody['status'] == 2)
            {
                if(isset($this->parsedBody['newTitle']) && isset($this->parsedBody['newText']) && isset($this->parsedBody['newTags']))
                { 
                    if(isset($this->parsedBody['newTags']))
                    {
                        if($this->model->acceptApproveWithChanges($articleId, $this->parsedBody['newTitle']))
                        {

                        }
                        else
                        {

                        }
                    }
                    else
                    {
                        if($this->model->acceptApproveWithChanges())
                        {

                        }
                        else
                        {
                            
                        }
                    }
                }
                else
                {
                    throw new Error(400, "Changes in article not found", "Changes in article not found");
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