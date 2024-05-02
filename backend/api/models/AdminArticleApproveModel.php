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
        if($this->database->update('statistics', [''], ['']))
        {
            if($this->database->update('statistics', [''], ['article_id' => $articleId]))
            {

            }
            else
            {

            }
        }
        else
        {

        }
    }

    public function acceptApprove($articleId)
    {
        if($this->database)
        {
            if()
            {

            }
            else
            {

            }
        }
        else
        {

        }
    }

    public function acceptApproveWithChanges($articleId, $newTitle, $newText, $newTags)
    {
        $articleInfo = $this->database->get('statistics', ['current_title', 'current_', ''], ['article_id' => $articleId]);
    }
}