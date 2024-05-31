<?php
namespace Api\Handlers;

use Core\Error;

use Base\BaseHandlerRouteWithArgs;

use Api\Models\ArticleEditPreloadModel;

use Api\Handlers\AdminStatusHandler;

class ArticleEditPreloadHandler extends BaseHandlerRouteWithArgs
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
                    if(!empty($this->args['editCode']))
                    {
                        $this->cookiesBody = $this->request->getCookieParams();

                        $this->model = new ArticleEditPreloadModel();
                    }
                    else
                    {
                        throw new Error(404, "Article for editing not found", "Article for editing not found");
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
        $articleId = $this->model->getArticleIdByEditCode($this->args['editCode']);

        if($articleId)
        {
            if(AdminStatusHandler::isAdmin($this->cookiesBody))
            {
                $this->response = $this->response->withJson($this->model->viewArticleAdmin($articleId));
            }
            else
            {
                $this->response = $this->response->withJson($this->model->viewArticle($articleId));
            }
        }
        else
        {
            throw new Error(404, "Article for editing not found", "Article for editing not found");
        }
    }
}
