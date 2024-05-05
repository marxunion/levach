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
        $articleData = $this->database->get('statistics', ['current_version','current_title', 'current_text', 'current_tags', 'edit_timeout_to_date', 'editorially_status', 'premoderation_status', 'approvededitorially_status'], ['article_id' => $articleId]);
        
        if(isset($articleData))
        {
            if(isset($articleData['edit_timeout_to_date']))
            {
                if($articleData['edit_timeout_to_date'] < time())
                {
                    if($articleData['current_title'] == $newTitle && $articleData['current_text'] == $newText && $articleData['current_tags'] == $newTags)
                    {
                        throw new Error(400, 'Please make changes for edit', 'Please make changes for edit');
                    }
        
                    $newVersionId = $articleData['current_version'] + 1;
                    $newArticleCreatedDate = time();
        
                    if(isset($newTags))
                    {
                        if(is_array($newTags))
                        {
                            if(count($newTags) > 0)
                            {
                                if(count($newTags) == count(array_unique($newTags)))
                                {
                                    $newTagsString = '{'.implode(',', $newTags).'}';
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
                    
                    $this->database->insert(
                        'articles', 
                        [
                            'id' => $articleId,
                            'version_id' => $newVersionId,
                            'created_date' => $newArticleCreatedDate,

                            'title' => $newTitle,
                            'text' => $newText,
                            'tags' => $newTagsString,
                            
                            'editorially_status' => $articleData['editorially_status'],
                            'premoderation_status' => $articleData['premoderation_status'],
                            'approvededitorially_status' => $articleData['approvededitorially_status']
                        ]
                    );
        
                    $articleEditTimeoutMinutes = AdminSettingsGetHandler::getSetting('article_edit_timeout_minutes');
                    if(isset($articleEditTimeoutMinutes))
                    {
                        $this->database->update(
                            'statistics', 
                            [
                                'current_version' => $newVersionId, 
                                'current_title' => $newTitle, 
                                'current_text' => $newText, 
                                'current_tags' => $newTagsString,
                                'created_date' => $newArticleCreatedDate,

                                'editorially_status' => $articleData['editorially_status'],
                                'premoderation_status' => $articleData['premoderation_status'],
                                'approvededitorially_status' => $articleData['approvededitorially_status'],

                                'edit_timeout_to_date' => $newArticleCreatedDate + ($articleEditTimeoutMinutes * 60)
                            ], 
                            [
                                'article_id' => $articleId
                            ]
                        );
                    }
                    else
                    {
                        throw new Critical(500, 'Unknown error', "Article edit timeout minutes not found in settings");
                    }
                } 
                else 
                {
                    throw new Warning(403, 'Wait for a timeout to re-edit the article', 'Wait for a timeout to re-edit the article', ["edit_timeout_to_date" => $articleData['edit_timeout_to_date']]);
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
        $articleData = $this->database->get('statistics', ['current_version', 'current_title', 'current_text', 'current_tags', 'editorially_status', 'premoderation_status', 'approvededitorially_status'], ['article_id' => $articleId]);
        
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
                            $newTagsString = '{'.implode(',', $newTags).'}';
                        }
                        else
                        {
                            throw new Warning(400, 'Article has duplicated tags', 'Article has duplicated tags');
                        }
                    }
                    else
                    {
                        $newTagsString = '{}';
                    }
                }
                else
                {
                    $newTagsString = '{}';
                }
            }
            else
            {
                $newTagsString = '{}';
            }
            
            $this->database->insert(
                'articles',
                [
                    'id' => $articleId,
                    'version_id' => $newVersionId,
                    'created_date' => $newArticleCreatedDate,

                    'title' => $newTitle,
                    'text' => $newText,
                    'tags' => $newTags,

                    'editorially_status' => $articleData['editorially_status'],
                    'premoderation_status' => $articleData['premoderation_status'],
                    'approvededitorially_status' => $articleData['approvededitorially_status']
                ]
            );
            
            $this->database->update(
                'statistics', 
                [
                    'current_version' => $newVersionId, 
                    'created_date' => $newArticleCreatedDate,

                    'current_title' => $newTitle, 
                    'current_text' => $newText, 
                    'current_tags' => $newTags, 
                    
                    'editorially_status' => $articleData['editorially_status'],
                    'premoderation_status' => $articleData['premoderation_status'],
                    'approvededitorially_status' => $articleData['approvededitorially_status'],

                    'edit_timeout_to_date' => $newArticleCreatedDate
                ], 
                [
                    'article_id' => $articleId
                ]
            );
        }
        else
        {
            throw new Error(404, 'Article for editing not found', 'Article for editing not found');
        }
    }
}