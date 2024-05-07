<?php
namespace Api\Handlers;

use Core\Error;

use Base\BaseHandlerRouteWithArgs;

use Api\Models\ArticleApproveWithChangesModel;

class ArticleApproveWithChangesHandler extends BaseHandlerRouteWithArgs
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
                    if(isset($this->args['editCode']))
                    {
                        $this->model = new ArticleApproveWithChangesModel();
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
            if($this->parsedBody['status'] == 0)
            {
                $this->model->rejectApproveWithChanges($articleId);
            }
            else if($this->parsedBody['status'] == 1)
            {
                $this->model->acceptApproveWithChanges($articleId);
            }
            else
            {
                throw new Error(404, "Unknown status", "Unknown status");
            }
            $this->response = $this->response->withJson(['success' => true]);
        }
        else
        {
            throw new Error(404, "Article for edit not found", "Article for edit not found");
        }
    }
}