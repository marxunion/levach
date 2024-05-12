<?php
namespace Api\Handlers;

use Core\Critical;
use Core\Error;

use Base\BaseHandlerRoute;

use Api\Handlers\AdminStatusHandler;

use Api\Models\AdminArticlesCommentsGetModel;

class AdminArticlesCommentsGetHandler extends BaseHandlerRoute
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
                        if(empty($this->parsedBody['articlesCount']))
                        {
                            $this->parsedBody['articlesCount'] = 0;
                        }
                        else
                        {
                            if(is_numeric($this->parsedBody['articlesCount']))
                            {
                                if($this->parsedBody['articlesCount'] > 10000)
                                {
                                    $this->parsedBody['articlesCount'] = 10000;
                                }
                            }
                            else
                            {
                                $this->parsedBody['articlesCount'] = 0;
                            }
                        }

                        if(empty($this->parsedBody['commentsCount']))
                        {
                            $this->parsedBody['commentsCount'] = 0;
                        }
                        else
                        {
                            if(is_numeric($this->parsedBody['commentsCount']))
                            {
                                if($this->parsedBody['commentsCount'] > 10000)
                                {
                                    $this->parsedBody['commentsCount'] = 10000;
                                }
                            }
                            else
                            {
                                $this->parsedBody['commentsCount'] = 0;
                            }
                        }

                        if(empty($this->parsedBody['articleDateBefore']))
                        {
                            $this->parsedBody['articleDateBefore'] = 0;
                        }
                        else
                        {
                            if(is_numeric($this->parsedBody['articleDateBefore']))
                            {
                                if($this->parsedBody['articleDateBefore'] > 2147483646)
                                {
                                    $this->parsedBody['articleDateBefore'] = 2147483647;
                                }
                            }
                            else
                            {
                                $this->parsedBody['articleDateBefore'] = 0;
                            }
                        }

                        if(empty($this->parsedBody['articleDateAfter']))
                        {
                            $this->parsedBody['articleDateAfter'] = time();
                        }
                        else
                        {
                            if(is_numeric($this->parsedBody['articleDateAfter']))
                            {
                                if($this->parsedBody['articleDateAfter'] > 2147483646)
                                {
                                    $this->parsedBody['articleDateAfter'] = 2147483647;
                                }
                            }
                            else
                            {
                                $this->parsedBody['articleDateAfter'] = 0;
                            }
                        }

                        if(empty($this->parsedBody['commentDateBefore']))
                        {
                            $this->parsedBody['commentDateBefore'] = 0;
                        }
                        else
                        {
                            if(is_numeric($this->parsedBody['commentDateBefore']))
                            {
                                if($this->parsedBody['commentDateBefore'] > 2147483646)
                                {
                                    $this->parsedBody['commentDateBefore'] = 2147483647;
                                }
                            }
                            else
                            {
                                $this->parsedBody['commentDateBefore'] = 0;
                            }
                        }

                        if(empty($this->parsedBody['commentDateAfter']))
                        {
                            $this->parsedBody['commentDateAfter'] = time();
                        }
                        else
                        {
                            if(is_numeric($this->parsedBody['commentDateAfter']))
                            {
                                if($this->parsedBody['commentDateAfter'] > 2147483646)
                                {
                                    $this->parsedBody['commentDateAfter'] = 2147483647;
                                }
                            }
                            else
                            {
                                $this->parsedBody['commentDateAfter'] = 0;
                            }
                        }

                        if(empty($this->parsedBody['articleRegexPattern']))
                        {
                            $this->parsedBody['articleRegexPattern'] = '[a-z0-9]*';
                        }

                        if(empty($this->parsedBody['commentRegexPattern']))
                        {
                            $this->parsedBody['commentRegexPattern'] = '[a-z0-9]*';
                        }

                        $this->model = new AdminArticlesCommentsGetModel();
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
        $this->response = $this->response->withJson($this->model->getComments($this->parsedBody['articlesCount'], $this->parsedBody['commentsCount'], $this->parsedBody['articleDateBefore'], $this->parsedBody['articleDateAfter'], $this->parsedBody['commentDateBefore'], $this->parsedBody['commentDateAfter'], $this->parsedBody['articleRegexPattern'], $this->parsedBody['commentRegexPattern']));
    }
}