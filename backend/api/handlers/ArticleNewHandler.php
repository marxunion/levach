<?php
namespace Api\Handlers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;
use Slim\Factory\AppFactory;
use Slim\Psr7\Stream;
use Slim\App;

use Core\Error;
use Core\Warning;

use Base\BaseHandlerRoute;

use Api\Models\ArticleNewModel;

class ArticleNewHandler extends BaseHandlerRoute
{
    public function Init()
    {
        $this->model = new ArticleNewModel();
        $this->data = $this->request->getParsedBody();
    }

    public function Process()
    {
        $contentParts = explode("\n", $data['text']);

        if (count($contentParts) >= 1) 
        {
            $title = $contentParts[0];
            if (strlen($title) >= 5 && strlen($title) <= 120) 
            {
                if (strpos($title, '# ') === 0) 
                {
                    if (count($contentParts) >= 2) 
                    {
                        $content = implode("\n", array_slice($contentParts, 1));
                        if (strlen($content) >= 25 && strlen($content) <= 10000) 
                        {
                            // Все данные валидны, можно сохранить статью
                            // Здесь может быть ваш код для сохранения статьи в базу данных или другой обработки
                            $response->withJson(['success' => true, 'message' => 'Article successfully saved']);
                        } 
                        else 
                        {
                            throw new Warning(400, "Article content must contain between 25 and 10000 characters", "Invalid length of article content", "003001");
                            return;
                        }
                    } 
                    else 
                    {
                        throw new Warning(400, "Please add content for the article", "Empty article content", "003002");
                        return;
                    }
                } 
                else 
                {
                    throw new Warning(400, "Please add a title for the article.", "Invalid article title", "003003");
                    return;
                }
            } 
            else 
            {
                throw new Warning(400, "ArticleNew Please add a title for the article. The title must contain between 5 and 120 characters", "Invalid article title length", "003004");
                return;
            }
        } 
        else 
        {
            throw new Warning(400, "Please add a title for the article", "Empty article title", "003005");
            return;
        }
    }
}