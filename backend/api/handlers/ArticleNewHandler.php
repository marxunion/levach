<?php
namespace Api\Handlers;

use Core\Error;
use Core\Warning;

use Base\BaseHandlerRoute;

use Api\Models\ArticleNewModel;

class ArticleNewHandler extends BaseHandlerRoute
{
    public function Init()
    {
        $this->model = new ArticleNewModel();
        $parsedBody = $this->request->getParsedBody();

        if(is_array($parsedBody))
        {
            $this->data = $parsedBody;
        }
        else
        {
            throw new Warning(400, "Please add a title for the article", "Empty article title");
        }
    }

    public function Process()
    {
        $contentParts = explode("\n", $this->data['text']);

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
                            $viewCode = hash('sha3-224', uniqid().bin2hex(random_bytes(32)).$title);
                            $editCode = hash('sha3-256', uniqid().bin2hex(random_bytes(32)).$title);

                            $this->model->newArticle($title, $this->data['text'], $this->data['tags'], $viewCode, $editCode);
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