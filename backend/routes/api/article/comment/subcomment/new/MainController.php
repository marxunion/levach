<?php
namespace Routes\Api\Article\Comment\Subcomment\New;

use Core\Error;

use Base\BaseControllerRouteWithArgs;

use Api\Models\ArticleCommentSubcommentNewModel;

class MainController extends BaseControllerRouteWithArgs
{
    public function Init()
    {
        $parsedBody = $this->request->getParsedBody();
        if(is_array($parsedBody))
        {
            $this->parsedBody = $parsedBody;
            
            if(!empty($this->parsedBody['csrfToken']))
            {
                if(CSRFTokenHandler::checkCsrfToken($this->parsedBody['csrfToken']))
                {
                    if(!empty($this->args['viewCode']))
                    {
                        if(isset($this->parsedBody['parent_comment_id']))
                        {
                            if(is_numeric($this->parsedBody['parent_comment_id']))
                            {
                                if($this->parsedBody['parent_comment_id'] > 2147483646)
                                {
                                    $this->parsedBody['parent_comment_id'] = 2147483647;
                                }
                                
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
                                        $this->model = new ArticleCommentSubcommentNewModel();
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
                                throw new Error(400, "Invalid parent comment id", "Invalid parent comment id");
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
            $this->model->newSubcomment($articleId, $this->parsedBody['parent_comment_id'], $this->parsedBody['text'], $this->parsedBody['ratingInfluence']);
            $this->response = $this->response->withJson(['success' => true]);
        }
        else
        {
            throw new Error(403, "Invalid article viewCode", "Invalid article viewCode");
        }
    }
}