<?php
namespace Api\Models;

use Core\Error;

use Base\BaseModel;

class AdminArticleCommentsDeleteModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getArticleByViewCode($viewCode)
    {
        return $this->database->get('codes', 'article_id', ['view_code' => $viewCode]);
    }

    public function deleteComments($articleId, $count, $dateBefore, $dateAfter, $text)
    {
        $this->database->delete('comments', ['article_id' => $articleId, 'count' => $count]);
    }
}