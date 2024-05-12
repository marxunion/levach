<?php
namespace Api\Handlers;

use Core\Critical;
use Core\Error;

use Base\BaseHandlerRouteWithArgs;

use Api\Handlers\AdminStatusHandler;

use Api\Models\AdminArticleCommentsGetModel;

class AdminArticleCommentsGetHandler extends BaseHandlerRouteWithArgs
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
                        if(!empty($this->args['viewCode']))
                        {
                            if(empty($this->parsedBody['count']))
                            {
                                $this->parsedBody['count'] = 0;
                            }
                            else
                            {
                                if(is_numeric($this->parsedBody['count']))
                                {
                                    if($this->parsedBody['count'] > 10000)
                                    {
                                        $this->parsedBody['count'] = 10000;
                                    }
                                }
                                else
                                {
                                    $this->parsedBody['count'] = 0;
                                }
                            }

                            if(empty($this->parsedBody['dateBefore']))
                            {
                                $this->parsedBody['dateBefore'] = 0;
                            }
                            else
                            {
                                if(is_numeric($this->parsedBody['dateBefore']))
                                {
                                    if($this->parsedBody['dateBefore'] > 2147483646)
                                    {
                                        $this->parsedBody['dateBefore'] = 2147483647;
                                    }
                                }
                                else
                                {
                                    $this->parsedBody['dateBefore'] = 0;
                                }
                            }

                            if(empty($this->parsedBody['dateAfter']))
                            {
                                $this->parsedBody['dateAfter'] = time();
                            }
                            else
                            {
                                if(is_numeric($this->parsedBody['dateAfter']))
                                {
                                    if($this->parsedBody['dateAfter'] > 2147483646)
                                    {
                                        $this->parsedBody['dateAfter'] = 2147483647;
                                    }
                                }
                                else
                                {
                                    $this->parsedBody['dateAfter'] = 0;
                                }
                            }

                            if(empty($this->parsedBody['regexPattern']))
                            {
                                $this->parsedBody['regexPattern'] = '[a-z0-9]*';
                            }

                            $this->model = new AdminArticleCommentsGetModel();
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
            $this->response = $this->response->withJson($this->model->getComments($articleId, $this->parsedBody['count'], $this->parsedBody['dateBefore'], $this->parsedBody['dateAfter'], $this->parsedBody['regexPattern']));
        }
        else
        {
            throw new Error(403, "Invalid article viewCode", "Invalid article viewCode");
        }
    }
}