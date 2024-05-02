<?php
namespace Api\Models;

use Core\Database;
use Core\Error;

use Base\BaseModel;

class AdminEditArticleStatusModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getArticleByViewCode($viewCode)
    {
        return $this->database->get('codes', 'article_id', ['view_code' => $viewCode]);
    }

    public acceptPremoderate($articleId)
    {
        if($this->database->update('statistics', ['premoderation_status' => 2], ['article_id' => $articleId]))
        {
            if($this->database->update('articles', ['premoderation_status' => 2], ['id' => $articlerId]))
            {
                return true;
            }
            else
            {   
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    public rejectPremoderate($articleId)
    {
        if($this->database->delete('statistics', ['article_id' => $articleId]))
        {
            if($this->database->delete('articles', ['id' => $articleId]))
            {
                return true;
            }
            else
            {   
                return false;
            }
        }
        else
        {
            return false;
        }
        return $this->database->delete();
    }
}