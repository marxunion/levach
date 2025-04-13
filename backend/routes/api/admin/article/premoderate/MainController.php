<?php
namespace Routes\Api\Admin\Article\Premoderate;

use Core\Error;

use Base\BaseControllerRouteWithArgs;

use Api\Models\AdminArticlePremoderateModel;

class AdminArticlePremoderateHandler extends BaseControllerRouteWithArgs
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
                        if(isset($this->parsedBody['status']))
                        {
                            if(isset($this->parsedBody['version_id']))
                            {
                                if(!empty($this->args['viewCode']))
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
                                throw new Error(400, "Premoderation version not found", "Premoderation version not found");
                            }
                        }
                        else 
                        {
                            throw new Error(400, "Premoderation status not found", "Premoderation status not found");
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
        if(strpos($this->args['viewCode'], '#') === 0)
        {
            $articleId = $this->model->getArticleByViewId((int)substr($this->args['viewCode'], 1));
        }
        else
        {
            $articleId = $this->model->getArticleByViewCode($this->args['viewCode']);
        }

        if(isset($articleId))
        {
            if($this->parsedBody['status'] == 0)
            {
                if($this->model->rejectPremoderate($articleId, $this->parsedBody['version_id']))
                {
                    $this->response = $this->response->withJson(['success' => true, 'deletedAllVersions' => true]);
                }
                else
                {
                    $this->response = $this->response->withJson(['success' => true, 'deletedAllVersions' => false]);
                }
            }
            else if($this->parsedBody['status'] == 1)
            {
                if($this->model->acceptPremoderate($articleId, $this->parsedBody['version_id']))
                {
                    $this->response = $this->response->withJson(['success' => true, 'acceptedAllVersions' => true]);
                }
                else
                {
                    $this->response = $this->response->withJson(['success' => true, 'acceptedAllVersions' => false]);
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
    }
}