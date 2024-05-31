<?php
namespace Api\Models;

use Core\Error;
use Core\Critical;
use Core\Warning;

use Helpers\StringFormatter;

use Base\BaseModel;

use Api\Handlers\AdminStatusHandler;
use Api\Handlers\AdminSettingsGetHandler;

class ArticleEditModel extends BaseModel
{
    public function getArticleIdByEditCode($editCode)
    {
        return $this->database->get('articles', 'id', ['edit_code' => $editCode]);
    }
    
    public function editArticle($articleId, $newTitle, $newText, $newTags)
    {
        $newText = StringFormatter::replaceViewIdsToViewIdsLinks($newText);
        $articleData = $this->database->get('articles', ['current_version','current_title', 'current_text', 'current_tags', 'edit_timeout_to_date', 'editorially_status', 'premoderation_status', 'approvededitorially_status'], ['id' => $articleId]);
        
        if(isset($articleData))
        {
            if($articleData['approvededitorially_status'] == 0)
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
                                    foreach ($newTags as $key => $value) 
                                    {
                                        $newTags[$key] = trim($newTags[$key]);
                                    }
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
                            $newTagsString = null;
                        }
                        
                        $this->database->insert(
                            'articles_versions', 
                            [
                                'article_id' => $articleId,
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
                                'articles', 
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
                                    'id' => $articleId
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
                throw new Error(403, 'You cannot edit an article after it has been approved', 'You cannot edit an article after it has been approved');
            }
        }
        else
        {
            throw new Error(404, 'Article for editing not found', 'Article for editing not found');
        }
    }

    public function editArticleAdmin($articleId, $newTitle, $newText, $newTags)
    {
        $articleData = $this->database->get('articles', ['current_version', 'current_title', 'current_text', 'current_tags', 'editorially_status', 'premoderation_status', 'approvededitorially_status'], ['id' => $articleId]);
        
        if(isset($articleData))
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
                        foreach ($newTags as $key => $value) 
                        {
                            $newTags[$key] = trim($newTags[$key]);
                        }
                        if(count($newTags) == count(array_unique($newTags)))
                        {
                            $newTagsString = "{".implode(',', $newTags).'}';
                        }
                        else
                        {
                            throw new Warning(400, 'Article has duplicated tags', 'Article has duplicated tags');
                        }
                    }
                    else
                    {
                        $newTagsString = null;
                    }
                }
                else
                {
                    $newTagsString = null;
                }
            }
            else
            {
                $newTagsString = null;
            }
            
            $this->database->insert(
                'articles_versions',
                [
                    'article_id' => $articleId,
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
            
            $this->database->update(
                'articles', 
                [
                    'current_version' => $newVersionId, 
                    'created_date' => $newArticleCreatedDate,

                    'current_title' => $newTitle, 
                    'current_text' => $newText, 
                    'current_tags' => $newTagsString, 
                    
                    'editorially_status' => $articleData['editorially_status'],
                    'premoderation_status' => $articleData['premoderation_status'],
                    'approvededitorially_status' => $articleData['approvededitorially_status'],

                    'edit_timeout_to_date' => $newArticleCreatedDate
                ], 
                [
                    'id' => $articleId
                ]
            );
        }
        else
        {
            throw new Error(404, 'Article for editing not found', 'Article for editing not found');
        }
    }
}