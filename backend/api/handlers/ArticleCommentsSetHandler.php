<?php
namespace Api\Handlers;

use Core\Critical;
use Core\Error;

use Base\BaseHandlerRoute;

use Api\Models\ArticleCommentsSetModel;

class ArticleCommentsSetHandler extends BaseHandlerRoute
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
                    if(AdminStatusHandler::isAdmin($this->request->getCookieParams()))
                    {
                        if(isset($this->parsedBody['commentType']))
                        {
                            $this->model = new ArticleCommentsSetModel();
                        }
                        else
                        {
                            throw new Error(400, "Invalid admin token", "Invalid admin token");
                        }
                    }
                    else
                    {
                        throw new Error(400, "Invalid admin token", "Invalid admin token");
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
        if($this->parsedBody['commentType'] == 'article')
        {   
            if($this->parsedBody['articleViewCode'])
            {

            }
            else
            {

            }
        }
        else if($this->parsedBody['commentType'] == 'comment')
        {
            if()
            {

            }
            else
            {
                if($this->parsedBody['commentType'])
                {
    
                }
                else
                {
                    
                }
            }
            
        }
        else
        {
            throw new Error(403, "Invalid comment type", "Invalid comment type");
        }
    }
}