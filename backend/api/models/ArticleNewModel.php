<?php
namespace Api\Models;

use Base\BaseModel;

use Core\Database;

class ArticleNewModel extends BaseModel
{
    public function __construct()
    {

    }
    public function publicArticle($data)
    {
        $database->insert('articles', $data);
    }
}