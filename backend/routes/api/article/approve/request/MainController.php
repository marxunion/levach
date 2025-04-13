<?php
namespace Routes\Api\Article\Approve\Request;

use Core\Error;

use Base\BaseControllerRouteWithArgs;

use Api\Models\ArticleApproveRequestModel;

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
                    if(!empty($this->args['editCode']))
                    {
                        $this->model = new ArticleApproveRequestModel();
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
            $this->model->requestApprove($articleId);
            $this->response = $this->response->withJson(['success' => true]);
        }
        else
        {
            throw new Error(404, "Article for editing not found", "Article for editing not found");
        }
    }
}