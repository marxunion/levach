<?php
namespace Api\Models;

use Core\Database;

use Base\BaseModel;

class ArticleSearchModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function searchArticle($queryStr = "")
    {
        
    }
    public function searchArticleWithTags($queryStr = "")
    {

    }
}