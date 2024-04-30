<?php
namespace Api\Models;

use Core\Database;

use Core\Error;
use Core\Critical;
use Core\Warning;

use Base\BaseModel;

use Api\Handlers\AdminStatusHandler;
use Api\Handlers\AdminSettingsGetHandler;

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
        $statisticsData = $this->database->get('statistics', ['current_version', 'edit_timeout_to_date'], ['article_id' => $articleId]);
        
        if(isset($statisticsData))
        {
            if(isset($statisticsData['edit_timeout_to_date']))
            {
                if($statisticsData['edit_timeout_to_date'] < time())
                {
                    $articleData = $this->database->get('articles', '*', [
                        'id' => $articleId,
                        'ORDER' => ['version_id' => 'DESC'],
                        'LIMIT' => 1
                    ]);

                    if(isset($articleData))
                    {
                        if($articleData['title'] == $newTitle && $articleData['text'] == $newText && $articleData['tags'] == $newTags)
                        {
                            throw new Error(400, 'Please make changes for edit', 'Please make changes for edit');
                        }
            
                        $newVersion = $statisticsData['current_version'] + 1;
            
                        $articleData['version_id'] = $newVersion;
                        $articleData['title'] = $newTitle;
                        $articleData['text'] = $newText;
                        $articleData['date'] = time();
                        
                        if(is_array($newTags))
                        {
                            if(count($newTags) > 0)
                            {
                                if(count($newTags) == count(array_unique($newTags)))
                                {
                                    $newTagsString = implode(',', $newTags);
                                    $articleData['tags'] = '{'.$newTagsString.'}';
                                }
                                else
                                {
                                    throw new Warning(400, 'Article has duplicated tags', 'Article has duplicated tags');
                                }
                            }
                        }
            
                        $this->database->insert('articles', $articleData);
            
                        $articleEditTimeoutMinutes = AdminSettingsGetHandler::getSetting('article_edit_timeout_minutes');
                        if(isset($articleEditTimeoutMinutes))
                        {
                            $this->database->update('statistics', ['current_version' => $newVersion, 'edit_timeout_to_date' => time() + ($articleEditTimeoutMinutes * 60)], ['article_id' => $articleId]);
                        }
                        else
                        {
                            throw new Critical(500, 'Unknown error', "Article edit timeout minutes not found in settings");
                        }
                    }
                    else
                    {
                        throw new Error(404, 'Article for editing not found', 'Article for editing not found');
                    }
                } 
                else 
                {
                    throw new Warning(403, 'Wait for a timeout to re-edit the article', 'Wait for a timeout to re-edit the article', ["edit_timeout_to_date" => $statisticsData['edit_timeout_to_date']]);
                }
            }
            else
            {
                throw new Critical(500, 'Unknown error', "Article edit timeout date not found");
            }
        }
        else
        {
            throw new Error(404, 'Article for editing not found', 'Article for editing not found');
        }
    }

    public function editArticleAdmin($articleId, $newTitle, $newText, $newTags)
    {
        $statisticsData = $this->database->get('statistics', ['current_version', 'edit_timeout_to_date'], ['article_id' => $articleId]);
        
        if(isset($statisticsData))
        {
            $articleData = $this->database->get('articles', '*', [
                'id' => $articleId,
                'ORDER' => ['version_id' => 'DESC'],
                'LIMIT' => 1
            ]);

            if(isset($articleData))
            {
                if($articleData['title'] == $newTitle && $articleData['text'] == $newText && $articleData['tags'] == $newTags)
                {
                    throw new Error(400, 'Please make changes for edit', 'Please make changes for edit');
                }
            
                $newVersion = $statisticsData['current_version'] + 1;
            
                $articleData['version_id'] = $newVersion;
                $articleData['title'] = $newTitle;
                $articleData['text'] = $newText;
                $articleData['date'] = time();
                        
                if(is_array($newTags))
                {
                    if(count($newTags) > 0)
                    {
                        if(count($newTags) == count(array_unique($newTags)))
                        {
                            $newTagsString = implode(',', $newTags);
                            $articleData['tags'] = '{'.$newTagsString.'}';
                        }
                        else
                        {
                            throw new Warning(400, 'Article has duplicated tags', 'Article has duplicated tags');
                        }
                    }
                }
            
                $this->database->insert('articles', $articleData);
            
                $this->database->update('statistics', ['current_version' => $newVersion, 'edit_timeout_to_date' => time()], ['article_id' => $articleId]);
            }
            else
            {
                throw new Error(404, 'Article for editing not found', 'Article for editing not found');
            }
        }
        else
        {
            throw new Error(404, 'Article for editing not found', 'Article for editing not found');
        }
    }
}