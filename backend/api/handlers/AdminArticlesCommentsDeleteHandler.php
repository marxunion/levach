<?php
namespace Api\Handlers;

use Core\Critical;
use Core\Error;

use Base\BaseHandlerRouteWithArgs;

use Api\Handlers\AdminStatusHandler;

use Api\Models\AdminArticlesCommentsDeleteModel;

class AdminArticlesCommentsDeleteHandler extends BaseHandlerRouteWithArgs
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
                            if(empty($this->parsedBody['articlesCount']))
                            {
                                $this->parsedBody['articlesCount'] = 0;
                            }

                            if(empty($this->parsedBody['commentsCount']))
                            {
                                $this->parsedBody['commentsCount'] = 0;
                            }

                            if(empty($this->parsedBody['articleDateBefore']))
                            {
                                $this->parsedBody['articleDateBefore'] = 0;
                            }

                            if(empty($this->parsedBody['articleDateAfter']))
                            {
                                $this->parsedBody['articleDateAfter'] = time();
                            }

                            if(empty($this->parsedBody['commentDateBefore']))
                            {
                                $this->parsedBody['commentDateBefore'] = 0;
                            }

                            if(empty($this->parsedBody['commentDateAfter']))
                            {
                                $this->parsedBody['commentDateAfter'] = time();
                            }

                            if(empty($this->parsedBody['articleRegexPattern']))
                            {
                                $this->parsedBody['articleRegexPattern'] = '[a-z0-9]*';
                            }

                            if(empty($this->parsedBody['commentRegexPattern']))
                            {
                                $this->parsedBody['commentRegexPattern'] = '[a-z0-9]*';
                            }

                            $this->model = new AdminArticlesCommentsDeleteModel();
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
            $this->model->deleteComments($articleId, $this->parsedBody['count'], $this->parsedBody['dateBefore'], $this->parsedBody['dateAfter'], $this->parsedBody['regexPattern']);
            $this->response = $this->response->withJson(['success' => true]);
        }
        else
        {
            throw new Error(403, "Invalid article viewCode", "Invalid article viewCode");
        }
    }
}