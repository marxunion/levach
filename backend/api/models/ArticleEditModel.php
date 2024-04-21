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

        $currentVersion = $this->database->get('statistics', 'current_version', ['article_id' => $articleId]);

        if($articleData)
        {
            $newVersion = $currentVersion + 1;

            $articleData['version_id'] = $newVersion;
            $articleData['title'] = $newTitle;
            $articleData['text'] = $newText;

            if(is_array($newTags))
            {
                if(count($newTags) > 0)
                {
                    $newTagsString = implode(',', $newTags);
                    $articleData['tags'] = '{'.$newTagsString.'}';
                }
            }
            
            $this->database->insert('articles', $articleData);

            $this->database->update('statistics', ['current_version' => $newVersion], ['article_id' => $articleId]);
        } 
        else 
        {
            throw new Critical(500, 'Unknown error', 'Failed to edit article');
        }
    }
}