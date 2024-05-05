<?php
namespace Api\Handlers;

use Core\Error;

use Base\BaseHandlerRouteWithArgs;

use Api\Models\ArticleApproveRequestModel;

class ArticleApproveRequestHandler extends BaseHandlerRouteWithArgs
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
                        $this->model = new ArticleApproveRequestModel();
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
            $ratingToRequestApprove = AdminSettingsGetHandler::getSetting("article_need_rating_to_approve_editorially");
            if(isset($ratingToRequestApprove))
            {
                if(> $ratingToRequestApprove)
                {
                    $this->model->requestApprove($articleId);
                }
                else
                {

                }
            }
            
        }
        else
        {
            throw new Error(404, "Article for edit not found", "Article for edit not found");
        }
    }
}