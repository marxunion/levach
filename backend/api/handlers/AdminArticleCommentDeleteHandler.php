<?php
namespace Api\Handlers;

use Core\Error;

use Base\BaseHandlerRouteWithArgs;

use Api\Handlers\AdminStatusHandler;

use Api\Models\AdminArticleCommentDeleteModel;

class AdminArticleCommentDeleteHandler extends BaseHandlerRouteWithArgs
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
                            if(isset($this->parsedBody['commentId']))
                            {
                                if(is_numeric($this->parsedBody['commentId']))
                                {
                                    $this->model = new AdminArticleCommentDeleteModel();
                                }
                                else
                                {
                                    throw new Error(400, "Comment not found", "Comment not found");
                                }
                            }
                            else
                            {
                                throw new Error(400, "Comment not found", "Comment not found");
                            }
                        }
                        else
                        {
                            throw new Error(400, "Article not found", "Article not found");
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
            $this->model->deleteComment($articleId, $this->parsedBody['commentId']);
            $this->response = $this->response->withJson(['success' => true]);
        }
        else
        {
            throw new Error(403, "Invalid article viewCode", "Invalid article viewCode");
        }
    }
}