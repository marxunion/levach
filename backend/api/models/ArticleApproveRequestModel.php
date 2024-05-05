<?php
namespace Api\Models;

use Core\Error;
use Core\Warning;
use Core\Critical;

use Base\BaseModel;

class ArticleApproveRequestModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getArticleIdByEditCode($editCode)
    {
        return $this->database->get('codes', 'article_id', ['edit_code' => $editCode]);
    }

    public function requestApprove($articleId)
    {
        $this->database->update('articles', ['approvededitorially_status' => 1], ['article_id' => $articleId]);
        $this->database->update('articles', ['approvededitorially_status' => 1], ['article_id' => $articleId]);
    }
}