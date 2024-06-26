<?php
namespace Api\Handlers;

use Core\Error;

use Base\BaseHandlerRouteWithArgs;

use Api\Models\ArticleCommentNewModel;

class ArticleCommentNewHandler extends BaseHandlerRouteWithArgs
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
                            if(!empty($this->args['viewCode']))
                            {
                                if(!empty($this->parsedBody['text']))
                                {
                                    if(strlen($this->parsedBody['text']) < 1000000)
                                    {
                                        $this->parsedBody['ratingInfluence'] = 0;
                                        if(isset($this->parsedBody['rating_influence']))
                                        {
                                            if($this->parsedBody['rating_influence'] == 1)
                                            {
                                                $this->parsedBody['ratingInfluence'] = 1;
                                            }
                                            else if($this->parsedBody['rating_influence'] == 2)
                                            {
                                                $this->parsedBody['ratingInfluence'] = -1;
                                            }
                                        }
                                            
                                        $this->model = new ArticleCommentNewModel();
                                    }
                                    else
                                    {
                                        throw new Error(400, "The content of the comment should be no more than 1000000 characters", "The content of the comment should be no more than 1000000 characters");
                                    }
                                }
                                else
                                {
                                    throw new Error(400, "Invalid comment text", "Invalid comment text");
                                }
                            }
                            else
                            {
                                throw new Error(400, "Invalid article viewCode", "Invalid article viewCode");
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
            $this->model->newComment($articleId, $this->parsedBody['text'], $this->parsedBody['ratingInfluence']);
            $this->response = $this->response->withJson(['success' => true]);
        }
        else
        {
            throw new Error(403, "Invalid article viewCode", "Invalid article viewCode");
        }
    }
}