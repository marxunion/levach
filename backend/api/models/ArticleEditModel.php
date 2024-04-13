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
    
    public function getArticleIdByEditCode($editCode)
    {
        return $this->database->get('codes', 'article_id', ['edit_code' => $editCode]);
    }
    
    public function editArticle($articleId, $newTitle, $newText, $newTags)
    {
        $articleData = $this->database->get('articles', '*', [
            'id' => $articleId,
            'ORDER' => ['version_id' => 'DESC'],
            'LIMIT' => 1
        ]);

        if($articleData)
        {
            $newVersion = $articleData['version_id'] + 1;

            $articleData['version_id'] = $newVersion;
            $articleData['title'] = $newTitle;
            $articleData['text'] = $newText;
            $articleData['tags'] = $newTags;

            $this->database->insert('articles', $articleData);
        } 
        else 
        {
            throw new Critical(500, 'Unknown error', 'Failed to edit article');
        }
    }
}