<?php
namespace Api\Handlers;

use Core\Critical;
use Core\Error;

use Base\BaseHandlerRouteWithArgs;

use Api\Models\ArticleCommentsNewModel;

class ArticleCommentsNewHandler extends BaseHandlerRouteWithArgs
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
                            if(isset($this->parsedBody['parent_comment_id']))
                            {
                                if(isset($this->parsedBody['text']))
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
                                    $this->model = new ArticleCommentsNewModel();
                                }
                                else
                                {
                                    throw new Error(400, "Invalid comment text", "Invalid article text");
                                }
                            }
                            else
                            {
                                throw new Error(400, "Invalid parent comment id", "Invalid parent comment id");
                            }
                        }
                        else
                        {
                            throw new Error(400, "Invalid article viewcode", "Invalid article viewcode");
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
            $this->model->newSubcomment($articleId, $this->parsedBody['parent_comment_id'], $this->parsedBody['text'], $this->parsedBody['ratingInfluence']);
            $this->response = $this->response->withJson(['success' => true]);
        }
        else
        {
            throw new Error(403, "Invalid article viewCode", "Invalid article viewCode");
        }
    }
}