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
                if(CSRFTokenHandler::checkCsrfToken($this->parsedBody['csrfToken']))
                {
                    if(AdminStatusHandler::isAdmin($this->request->getCookieParams()))
                    {
                        if(!empty($this->args['viewCode']))
                        {
                            if(isset($this->parsedBody['status']))
                            {
                                $this->model = new AdminArticleApproveModel();
                            }
                            else
                            {
                                throw new Error(404, "Approved status not found", "Approved status not found");
                            }
                        }
                        else
                        {
                            throw new Warning(404, "Article for approve not found", "Article for approve not found");
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
                if(empty($this->parsedBody['text']))
                {
                    throw new Warning(400, "Please add a title for the article", "Please add a title for the article");
                }
                $contentParts = explode("\n", $this->parsedBody['text']);
                if(count($contentParts) >= 1) 
                {
                    $title = $contentParts[0];
                    if(strpos($title, '# ') === 0) 
                    {
                        $title = substr($title, 2);
                        if(strlen($title) >= 5 && strlen($title) <= 120) 
                        {
                            if(count($contentParts) >= 2) 
                            {
                                $content = implode("\n", array_slice($contentParts, 1));
                                if(strlen($content) >= 25) 
                                {
                                    $this->model->acceptApproveWithChanges($articleId, $title, $this->parsedBody['text'], $this->parsedBody['tags']);
                                    $this->response = $this->response->withJson(['success' => true]);
                                }            
                                else 
                                {
                                    throw new Warning(400, "The content of the article must be more than 25 characters", "The content of the article must be more than 25 characters");
                                }
                            } 
                            else 
                            {
                                throw new Warning(400, "Please add content for the article", "Please add content for the article");
                            }
                        } 
                        else 
                        {
                            throw new Warning(400, "Title must contain between 5 and 120 characters", "Title must contain between 5 and 120 characters");
                        }
                    } 
                    else 
                    {
                        throw new Warning(400, "Please add a title for the article", "Please add a title for the article");
                    }
                } 
                else 
                {
                    throw new Warning(400, "Please add a title for the article", "Please add a title for the article");
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