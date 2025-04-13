<?php
namespace Routes\Api\Article\New;

use Core\Error;
use Core\Warning;

use Base\BaseControllerRoute;

use Api\Models\ArticleNewModel;

use Api\Handlers\CaptchaTokenHandler;
use Api\Handlers\CSRFTokenHandler;

class MainController extends BaseControllerRoute
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
                            if(!empty($this->parsedBody['text']))
                            {
                                $this->cookiesBody = $this->request->getCookieParams();
                                
                                $this->model = new ArticleNewModel();
                            }
                            else
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
            throw new Error(400, "Invalid request body", "Invalid request body");
        }
    }

    public function Process()
    {
        $contentParts = explode("\n", $this->parsedBody['text']);

        if(count($contentParts) >= 1) 
        {
            $title = $contentParts[0];
            if(strpos($title, '# ') === 0) 
            {
                $title = substr($title, 2);
                if(strlen($title) >= 4 && strlen($title) <= 120) 
                {
                    if(count($contentParts) >= 2) 
                    {
                        $content = implode("\n", array_slice($contentParts, 1));
                        if(strlen($content) >= 25) 
                        {
                            if(strlen($content) < 1000000) 
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
                                throw new Warning(400, "The content of the article should be no more than 1000000 characters", "The content of the article should be no more than 1000000 characters");
                            }
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
                    throw new Warning(400, "Title must contain between 4 and 120 characters", "Title must contain between 4 and 120 characters");
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
}