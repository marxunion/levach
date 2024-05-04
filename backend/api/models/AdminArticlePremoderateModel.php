<?php
namespace Api\Models;

use Core\Database;
use Core\Error;

use Base\BaseModel;

class AdminArticlePremoderateModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getArticleByViewCode($viewCode)
    {
        return $this->database->get('codes', 'article_id', ['view_code' => $viewCode]);
    }

    public function acceptPremoderate($articleId)
    {
        if($this->database->update('statistics', ['premoderation_status' => 2], ['article_id' => $articleId]))
        {
            if($this->database->update('articles', ['premoderation_status' => 2], ['id' => $articleId]))
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

    public function rejectPremoderate($articleId)
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