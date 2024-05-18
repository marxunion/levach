<?php
namespace Api\Handlers;

use Core\Error;
use Core\Warning;

use Base\BaseHandlerRouteWithArgs;
use Base\BaseModel;

use Api\Models\ArticleEditModel;

use Api\Handlers\CaptchaTokenHandler;
use Api\Handlers\AdminStatusHandler;

class ArticleEditHandler extends BaseHandlerRouteWithArgs
{
    public function Init()
    {
        $parsedBody = $this->request->getParsedBody();
            
        if(is_array($parsedBody))
        {
            $this->parsedBody = $parsedBody;
            if(!empty($this->parsedBody['captchaToken']))
            {
                if(CaptchaTokenHandler::checkCaptchaToken($this->request->getServerParam('REMOTE_ADDR'), $this->parsedBody['captchaToken']))
                {
                    if(!empty($this->parsedBody['csrfToken']))
                    {
                        if(CSRFTokenHandler::checkCsrfToken($this->parsedBody['csrfToken']))
                        {
                            if(!empty($this->args['editCode']))
                            {
                                if(!empty($this->parsedBody['text']))
                                {
                                    $this->cookiesBody = $this->request->getCookieParams();

                                    $this->model = new ArticleEditModel();
                                }
                                else
                                {
                                    throw new Warning(400, "Please add a title for the article", "Please add a title for the article");
                                }
                            }
                            else
                            {
                                throw new Warning(404, "Article for editing not found", "Article for editing not found");
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
                    throw new Error(400, "Invalid captcha solving", "Invalid captcha solving");
                }
            }
            else
            {
                throw new Error(400, "Invalid captcha solving", "Invalid captcha solving");
            }
        }
        else
        {
            throw new Error(400, "Invalid request body", "Invalid request body");
        }
    }

    public function Process()
    {
        $articleId = $this->model->getArticleIdByEditCode($this->args['editCode']);

        if($articleId)
        {
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
                            if(strlen($content) >= 25 && strlen($content) <= 10000) 
                            {
                                if(AdminStatusHandler::isAdmin($this->cookiesBody))
                                {
                                    $this->model->editArticleAdmin($articleId, $title, $this->parsedBody['text'], $this->parsedBody['tags']);
                                }
                                else
                                {
                                    $this->model->editArticle($articleId, $title, $this->parsedBody['text'], $this->parsedBody['tags']);
                                }
                        
                                $this->response = $this->response->withJson(['success' => true]);
                            } 
                            else 
                            {
                                throw new Warning(400, "Article content must contain between 25 and 10000 characters", "Article content must contain between 25 and 10000 characters");
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
            throw new Warning(404, "Article for editing not found", "Article for editing not found");
        }
    }
}