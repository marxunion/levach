<?php
namespace Api\Models;

use Base\BaseModel;

use Core\Database;

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