<?php
namespace Api\Handlers;

use Core\Error;
use Core\Warning;

use Base\BaseHandlerRoute;

use Api\Models\ArticleNewModel;

use Api\Handlers\csrfTokenHandler;

class ArticleNewHandler extends BaseHandlerRoute
{
    public function Init()
    {
        $this->model = new ArticleNewModel();
        $parsedBody = $this->request->getParsedBody();

        if(is_array($parsedBody))
        {
            $this->parsedBody = $parsedBody;
            if(!isset($this->parsedBody['text']))
            {
                throw new Warning(400, "Please add a title for the article", "Empty article title");
            }
        }
        else
        {
            throw new Warning(400, "Please add a title for the article", "Empty article title");
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
                            $viewCode = hash('sha3-224', uniqid().bin2hex(random_bytes(random_int(60,80))).$title);
                            $editCode = hash('sha3-256', uniqid().bin2hex(random_bytes(random_int(70,90))).$title);

                            if(isset($this->parsedBody['tags']))
                            {
                                $this->model->newArticle($title, $this->parsedBody['text'], $this->parsedBody['tags'], $viewCode, $editCode);
                            }
                            else
                            {
                                $this->model->newArticle($title, $this->parsedBody['text'], null, $viewCode, $editCode);
                            }
                            
                            $this->response = $this->response->withJson(['viewCode' => $viewCode, 'editCode' => $editCode]);
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
                    throw new Warning(400, "Title must contain between 5 and 120 characters", "Invalid article title length");
                }
            } 
            else 
            {
                throw new Warning(400, "Please add a title for the article.", "Invalid article title");
            }
        } 
        else 
        {
            throw new Warning(400, "Please add a title for the article", "Empty article title");
        }
    }
}