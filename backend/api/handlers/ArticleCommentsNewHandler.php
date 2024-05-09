<?php
namespace Api\Handlers;

use Core\Critical;
use Core\Error;

use Base\BaseHandlerRoute;

use Api\Models\ArticleCommentsNewModel;

class ArticleCommentsNewHandler extends BaseHandlerRouteWith
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
                            if(isset($this->args['viewCode']))
                            {
                                if(isset($this->parsedBody['text']))
                                {
                                    $this->ratingInfluence = 0;
                                    if(isset($this->parsedBody['reactionType']))
                                    {
                                        if($this->parsedBody['reactionType'] == 1)
                                        {
                                            $this->ratingInfluence = 1;
                                        }
                                        else if($this->parsedBody['reactionType'] == 2)
                                        {
                                            $this->ratingInfluence = -1;
                                        }
                                    }
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
            if($this->parsedBody['commentType'] == 'comment')
            {
                $this->model->newComment($articleId, $this->parsedBody['text'], $this->ratingInfluence);
            }
            else if($this->parsedBody['commentType'] == 'subcomment') 
            {
                if(isset($this->parsedBody['parent_comment_id']))
                {
                    $this->model->newSubcomment($articleId, $this->parsedBody['parent_comment_id'], $this->parsedBody['text'], $this->ratingInfluence);
                }
                else
                {
                    throw new Error(403, "Invalid comment id", "Invalid comment id");
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