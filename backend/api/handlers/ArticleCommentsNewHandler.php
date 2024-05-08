<?php
namespace Api\Handlers;

use Core\Critical;
use Core\Error;

use Base\BaseHandlerRoute;

use Api\Models\ArticleCommentsNewModel;

class ArticleCommentsNewHandler extends BaseHandlerRoute
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
                        if(isset($this->parsedBody['commentType']))
                        {
                            if(isset($this->parsedBody['articleViewCode']))
                            {
                                if(isset($this->parsedBody['text']))
                                {
                                    $this->model = new ArticleCommentsNewModel();
                                }
                                else
                                {
                                    throw new Error(400, "Invalid comment text", "Invalid article text");
                                }
                                
                            }
                            else
                            {
                                throw new Error(400, "Invalid article viewcode", "Invalid article viewcode");
                            }
                        }
                        else
                        {
                            throw new Error(400, "Invalid comment type", "Invalid comment type");
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
            if($this->parsedBody['commentType'] == 'article')
            {
                
            }
            else if($this->parsedBody['commentType'] == 'comment') 
            {
                if(isset($this->parsedBody['commentId']))
                {
                    c
                }
                else
                {

                }
            }
            else
            {
                throw new Error(403, "Invalid article viewcode", "Invalid article code");
            }
        }
        else
        {
            throw new Error(403, "Invalid article viewcode", "Invalid article code");
        }
    }
}