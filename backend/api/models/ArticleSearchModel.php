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

    public function search($queryStr = "")
    {
        
    }
    public function searchWithTags($queryStr = "")
    {

    }
}