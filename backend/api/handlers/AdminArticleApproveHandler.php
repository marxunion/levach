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
                                    throw new Warning(400, "Article content must contain between 25 and 10000 characters", "Invalid length of article content");
                                }
                            } 
                            else 
                            {
                                throw new Warning(400, "Please add content for the article", "Empty article content");
                            }
                        } 
                        else 
                        {
                            throw new Warning(400, "The title must contain between 5 and 120 characters", "Invalid article title length");
                        }
                    } 
                    else 
                    {
                        throw new Warning(400, "Please add a title for the article", "Invalid article title");
                    }
                } 
                else 
                {
                    throw new Warning(400, "Please add a title for the article", "Empty article title");
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