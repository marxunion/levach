<?php
namespace Api\Models;

use Core\Error;
use Core\Warning;
use Core\Critical;

use Base\BaseModel;

class ArticleCommentsGetModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function getArticleByViewCode($viewCode)
    {
        return $this->database->get('codes', 'article_id', ['view_code' => $viewCode]);
    }
    public function getSubcomments($articleId, $commentId)
    {

    }

    public function getCommentsByRate($articleId, $count, $lastLoaded)
    {

    }

    public function getCommentsByCreatedDate($articleId, $count, $lastLoaded)
    {

    }
}