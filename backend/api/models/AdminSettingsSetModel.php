<?php
namespace Api\Models;

use Core\Database;
use Core\Error;

use Base\BaseModel;

class AdminSettingsSetModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function Set($settings)
    {
        if(isset($settings['editArticleTimeoutMinutes']))
        {
            if(gettype($settings['editArticleTimeoutMinutes'] == "integer"))
            {
                
            }
        }
    }
}