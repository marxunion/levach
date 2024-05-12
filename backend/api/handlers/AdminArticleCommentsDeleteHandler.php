<?php
namespace Api\Handlers;

use Core\Critical;
use Core\Error;

use Base\BaseHandlerRouteWithArgs;

use Api\Handlers\AdminStatusHandler;

use Api\Models\AdminArticleCommentsDeleteModel;

class AdminArticleCommentsDeleteHandler extends BaseHandlerRouteWithArgs
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
                            if(empty($this->parsedBody['count']))
                            {
                                $this->parsedBody['count'] = 0;
                            }

                            if(empty($this->parsedBody['dateBefore']))
                            {
                                $this->parsedBody['dateBefore'] = 0;
                            }

                            if(empty($this->parsedBody['dateAfter']))
                            {
                                $this->parsedBody['dateAfter'] = time();
                            }

                            if(empty($this->parsedBody['regexPattern']))
                            {
                                $this->parsedBody['regexPattern'] = '';
                            }

                            $this->model = new AdminArticleCommentsDeleteModel();
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
    }

    public function Process()
    {
        $articleId = $this->model->getArticleByViewCode($this->args['viewCode']);
        if(isset($articleId))
        {
            $this->response = $this->response->withJson($this->model->deleteComments($articleId, $this->parsedBody['count'], $this->parsedBody['dateBefore'], $this->parsedBody['dateAfter'], $this->parsedBody['text']));
        }
        else
        {
            throw new Error(403, "Invalid article viewCode", "Invalid article viewCode");
        }
    }
}