<?php
namespace Api\Models;

use Base\BaseModel;

use Core\Database;

class ArticleEditModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }
    public function editArticle($newTitle, $newText, $newTags)
    {
        if ($currentVersion !== false) 
        {
            $articleData = $database->get('articles', '*', [
                'id' => $articleId,
                'ORDER' => ['version_id' => 'DESC'],
                'LIMIT' => 1
            ]);
            
            $newVersion = $articleData['version_id'] + 1;

            $articleData['version_id'] = $newVersion;
            $articleData['title'] = $newTitle;
            $articleData['text'] = $newText;
            $articleData['tags'] = $newTags;

            $database->insert('articles', $articleData);
        } 
        else 
        {
            throw new Critical(500,'Unknown error', 'Failed to create article');
        }
    }
}