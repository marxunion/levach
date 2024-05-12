<?php
namespace Api\Handlers;

use Core\Error;
use Core\Warning;

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
            
            if(!empty($this->parsedBody['csrfToken']))
            {
                if(csrfTokenHandler::checkCsrfToken($this->parsedBody['csrfToken']))
                {
                    if(AdminStatusHandler::isAdmin($this->request->getCookieParams()))
                    {
                        if(isset($this->parsedBody['status']))
                        {
                            if(!empty($this->args['viewCode']))
                            {
                                $this->model = new AdminArticleApproveModel();
                            }
                            else
                            {
                                throw new Warning(404, "Article for approve not found", "Article for approve not found");
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
    }

    public function Process()
    {
        $articleId = $this->model->getArticleByViewCode($this->args['viewCode']);
        if(isset($articleId))
        {
            if($this->parsedBody['status'] == 0)
            {
                $this->model->rejectApprove($articleId);
                $this->response = $this->response->withJson(['success' => true]);
            }
            else if($this->parsedBody['status'] == 1)
            {
                $this->model->acceptApprove($articleId);
                $this->response = $this->response->withJson(['success' => true]);
            }
            else if($this->parsedBody['status'] == 2)
            {
                $contentParts = explode("\n", $this->parsedBody['text']);
                if (count($contentParts) >= 1) 
                {
                    $title = $contentParts[0];
                    if (strpos($title, '# ') === 0) 
                    {
                        $title = substr($title, 2);
                        if (strlen($title) >= 5 && strlen($title) <= 120) 
                        {
                            if (count($contentParts) >= 2) 
                            {
                                $content = implode("\n", array_slice($contentParts, 1));
                                if (strlen($content) >= 25 && strlen($content) <= 10000) 
                                {
                                    $this->model->acceptApproveWithChanges($articleId, $title, $this->parsedBody['text'], $this->parsedBody['tags']);
                                    $this->response = $this->response->withJson(['success' => true]);
                                }            
                                else 
                                {
                                    throw new Warning(400, "Invalid length of article content", "Invalid length of article content");
                                }
                            } 
                            else 
                            {
                                throw new Warning(400, "Empty article content", "Empty article content");
                            }
                        } 
                        else 
                        {
                            throw new Warning(400, "Invalid length of article title", "Invalid length of article title");
                        }
                    } 
                    else 
                    {
                        throw new Warning(400, "Invalid article title", "Invalid article title");
                    }
                } 
                else 
                {
                    throw new Warning(400, "Empty article title", "Empty article title");
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