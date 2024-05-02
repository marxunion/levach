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
                        $articleData['premoderation_status'] = 0;
                        $articleData['approvededitorially_status'] = 0;
                        
                        $articleData['created_date'] = time();
                        
                        if(isset($newTags))
                        {
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
            $articleData = $this->database->select('statistics', ['current_title', 'current_text', 'current_tags'], ['article_id' => $articleId]);

            if(isset($articleData))
            {
                if($articleData['current_title'] == $newTitle && $articleData['current_text'] == $newText && $articleData['current_tags'] == $newTags)
                {
                    throw new Error(400, 'Please make changes for edit', 'Please make changes for edit');
                }
            
                $newVersionId = $statisticsData['current_version'] + 1;
                $newArticleCreatedDate = time();
                      
                if(isset($newTags))
                {
                    if(is_array($newTags))
                    {
                        if(count($newTags) > 0)
                        {
                            if(count($newTags) == count(array_unique($newTags)))
                            {
                                $newTagsString = implode(',', $newTags);
                                $newTagsString = '{'.$newTagsString.'}';
                            }
                            else
                            {
                                throw new Warning(400, 'Article has duplicated tags', 'Article has duplicated tags');
                            }
                        }
                    }
                }
                else
                {
                    $newTagsString = '{}';
                }
            
                $this->database->insert('articles',
                [
                    'id' => $articleId,
                    'version_id' => $newVersionId,
                    'created_date'

                    'title' => $newTitle,
                    'text' => $newText,
                    'tags' => $newTags,
                    
                    'editorially_status' => 0,
                    'premoderation_status' => 0,
                    'approvededitorially_status' => 0,
                ]);
            
                $this->database->update('statistics', ['current_version' => $newVersionId, 'current_title' => $newTitle, 'current_text' => $newText, 'current_tags' => $newTags, 'createdDate' => , 'edit_timeout_to_date' => $newArticleCreatedDate], ['article_id' => $articleId]);
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