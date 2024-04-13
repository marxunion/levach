<?php
namespace Api\Models;

use Base\BaseModel;

use Core\Database;

class ArticleEditPreloadModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getArticleIdByEditCode($editCode)
    {
        return $this->database->get('codes', 'article_id', ['edit_code' => $editCode]);
    }
    
    public function viewArticle($articleId)
    {
        return $this->database->get('articles', '*', [
            'id' => $articleId,
            'ORDER' => ['version_id' => 'DESC'],
            'LIMIT' => 1
        ]);
    }
}