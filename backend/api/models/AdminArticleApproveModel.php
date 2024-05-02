<?php
namespace Api\Models;

use Core\Database;
use Core\Error;

use Base\BaseModel;

class AdminArticleApproveModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function getArticleByViewCode($viewCode)
    {
        return $this->database->get('codes', 'article_id', ['view_code' => $viewCode]);
    }

    public function rejectApprove($articleId)
    {
        if($this->database->update('statistics', ['approvededitorially_status' => 0], ['article_id' => $articleId]))
        {
            if($this->database->update('articles', ['approvededitorially_status' => 0], ['id' => $articleId]))
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

    public function acceptApprove($articleId)
    {
        if($this->database->update('statistics', ['approvededitorially_status' => 2], ['article_id' => $articleId]))
        {
            if($this->database->update('articles', ['approvededitorially_status' => 2], ['id' => $articleId]))
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

    public function acceptApproveWithChanges($articleId, $newTitle, $newText, $newTags)
    {
        if($this->database->update('statistics', ['approvededitorially_status' => 3], ['article_id' => $articleId]))
        {
            if($this->database->update('articles', ['approvededitorially_status' => 3], ['id' => $articleId]))
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
        $articleInfo = $this->database->get('statistics', ['current_title', 'current_text', 'current_tags'], ['article_id' => $articleId]);
        if(isset($articleInfo))
        {
            
        }
        else 
        {
            throw new Error(404, "Article not found", "Article not found");
        }
    }
}