<?php
namespace Api\Handlers;

use Core\Error;
use Core\Warning;

use Base\BaseHandlerRouteWithArgs;

use Api\Models\ArticleEditPreloadModel;

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
                        $this->model = new ArticleEditPreloadModel();
                    }
                    else
                    {
                        throw new Error(404, "Article for edit not found", "Article for edit not found");
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
        $articleId = $this->model->getArticleIdByEditCode($this->args['editCode']);

        if($articleId)
        {
            $this->response = $this->response->withJson($this->model->viewArticle($articleId));
        }
        else
        {
            throw new Error(404, "Article for edit not found", "Article for edit not found");
        }
    }
}
