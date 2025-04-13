<?php
namespace Routes\Api\Articles;

use Core\Error;

use Base\BaseControllerRouteWithArgs;

use Api\Models\ArticleViewModel;

use Api\Handlers\AdminStatusHandler;

class MainController extends BaseControllerRouteWithArgs
{
    public function Init()
    {
        if(!empty($this->args['viewCode']))
        {
            $this->cookiesBody = $this->request->getCookieParams();
            
            $this->model = new ArticleViewModel();
        }
        else
        {
            throw new Error(404, "Article not found", "Article not found");
        }
        
    }
    public function Process()
    {
        if(strpos($this->args['viewCode'], '#') === 0)
        {
            if(AdminStatusHandler::isAdmin($this->cookiesBody))
            {
                $this->response = $this->response->withJson($this->model->getArticleByViewIdAdmin((int)substr($this->args['viewCode'], 1)));
            }
            else
            {
                $this->response = $this->response->withJson($this->model->getArticleByViewId((int)substr($this->args['viewCode'], 1)));
            }
        }
        else
        {
            $articleId = $this->model->getArticleByViewCode($this->args['viewCode']);
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
                throw new Error(404, "Article not found", "Article not found");
            }
        }
    }
}