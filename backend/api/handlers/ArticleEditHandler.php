<?php
namespace Api\Handlers;

use Core\Warning;

use Base\BaseHandlerRouteWithArgs;
use Base\BaseModel;

use Api\Models\ArticleEditModel;

use Api\Handlers\AdminStatusHandler;

class ArticleEditHandler extends BaseHandlerRouteWithArgs
{
    public function Init()
    {
        if(!empty($this->args['editCode']))
        {
            $this->model = new ArticleEditModel();
            $parsedBody = $this->request->getParsedBody();
            
            if(is_array($parsedBody))
            {
                $this->parsedBody = $parsedBody;
                $this->cookiesBody = $this->request->getCookieParams();
                
                if(empty($this->parsedBody['text']))
                {
                    throw new Warning(400, "Please add a title for the article", "Empty article title");
                }
            }
            else
            {
                throw new Warning(400, "Please add a title for the article", "Empty article title");
            }
        }
        else
        {
            throw new Warning(404, "Article for editing not found", "Article for edit not found");
        }
    }

    public function Process()
    {
        $articleId = $this->model->getArticleIdByEditCode($this->args['editCode']);

        if($articleId)
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
                                throw new Warning(400, "Article content must contain between 25 and 10000 characters", "Invalid length of article content");
                            }
                        } 
                        else 
                        {
                            throw new Warning(400, "Please add content for the article", "Empty article content");
                        }
                    } 
                    else 
                    {
                        throw new Warning(400, "The title must contain between 5 and 120 characters", "Invalid article title length");
                    }
                } 
                else 
                {
                    throw new Warning(400, "Please add a title for the article", "Invalid article title");
                }
            } 
            else 
            {
                throw new Warning(400, "Please add a title for the article", "Empty article title");
            }
        }
        else
        {
            throw new Warning(404, "Article for editing not found", "Article for editing not found");
        }
    }
}