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

    public function rejectApproved($articleId)
    {

    }

    public function acceptApproved($articleId)
    {

    }

    public function acceptApprovedWithChanges($articleId, $newTitle, $newText, $newTags)
    {

    }
}