<?php
namespace Api\Handlers;

use Core\Error;
use Core\Warning;

use Base\BaseHandlerRoute;

use Api\Models\ArticleNewModel;

use Api\Handlers\CaptchaTokenHandler;
use Api\Handlers\CSRFTokenHandler;

class ArticleNewHandler extends BaseHandlerRoute
{
    public function Init()
    {
        $this->model = new ArticleNewModel();
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
                            $this->cookiesBody = $this->request->getCookieParams();
                
                            if(empty($this->parsedBody['text']))
                            {
                                throw new Warning(400, "Please add a title for the article", "Please add a title for the article");
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
            throw new Warning(400, "Invalid request", "Invalid request");
        }
    }

    public function Process()
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
                            if(AdminStatusHandler::isAdmin($this->cookiesBody))
                            {
                                $viewCode = bin2hex(random_bytes(random_int(5,15))).uniqid().hash('sha3-256', uniqid().bin2hex(random_bytes(random_int(80,100))).$title).bin2hex(random_bytes(random_int(5,15)));
                                $editCode = bin2hex(random_bytes(random_int(10,25))).uniqid().hash('sha3-512', uniqid().bin2hex(random_bytes(random_int(130,150))).$title).bin2hex(random_bytes(random_int(10,25)));

                                if(isset($this->parsedBody['tags']))
                                {
                                    $this->model->newArticleAdmin($title, $this->parsedBody['text'], $this->parsedBody['tags'], $viewCode, $editCode);
                                }
                                else
                                {
                                    $this->model->newArticleAdmin($title, $this->parsedBody['text'], null, $viewCode, $editCode);
                                }
                            }
                            else
                            {
                                $viewCode = bin2hex(random_bytes(random_int(5,15))).uniqid().hash('sha3-224', uniqid().bin2hex(random_bytes(random_int(60,80))).$title).bin2hex(random_bytes(random_int(5,15)));
                                $editCode = bin2hex(random_bytes(random_int(5,15))).uniqid().hash('sha3-256', uniqid().bin2hex(random_bytes(random_int(70,90))).$title).bin2hex(random_bytes(random_int(5,15)));

                                if(isset($this->parsedBody['tags']))
                                {
                                    
                                    $this->model->newArticle($title, $this->parsedBody['text'], $this->parsedBody['tags'], $viewCode, $editCode);
                                }
                                else
                                {
                                    $this->model->newArticle($title, $this->parsedBody['text'], null, $viewCode, $editCode);
                                }
                                
                            }
                            
                            $this->response = $this->response->withJson(['viewCode' => $viewCode, 'editCode' => $editCode]);
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
                throw new Warning(400, "Please add a title for the article.", "Please add a title for the article");
            }
        } 
        else 
        {
            throw new Warning(400, "Please add a title for the article", "Please add a title for the article");
        }
    }
}