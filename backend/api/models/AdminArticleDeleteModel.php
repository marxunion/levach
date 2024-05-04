<?php
namespace Api\Models;

use Core\Database;
use Core\Error;

use Base\BaseModel;

class AdminArticleDeleteModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function getArticleByViewCode($viewCode)
    {
        return $this->database->get('codes', 'article_id', ['view_code' => $viewCode]);
    }

    public function deleteArticle($articleId)
    {
        if($this->database->delete('codes', ['article_id' => $articleId]))
        {
            if($this->model-delete('statistics', ['article_id' => $articleId]))
            {
                if($this->model-delete('articles', ['id' => $articleId]))
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
}