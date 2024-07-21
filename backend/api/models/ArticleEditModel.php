<?php
namespace Api\Models;

use Core\Critical;
use Core\Error;
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
        $newText = StringFormatter::filterHtmlTags($newText);

        $articleData = $this->database->get(
            'articles', 
            [
                'current_version',
                'current_title',
                'current_text',
                'current_tags',
                'edit_timeout_to_date',
                'editorially_status',
                'premoderation_status',
                'approvededitorially_status'
            ], 
            [
                'id' => $articleId
            ]
        );
        
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

                        $articleVersionData = [
                            'article_id' => $articleId,
                            'version_id' => $newVersionId,
                            'created_date' => $newArticleCreatedDate,

                            'title' => $newTitle,
                            'text' => $newText,

                            'editorially_status' => $articleData['editorially_status'],
                            'premoderation_status' => 1,
                            'approvededitorially_status' => $articleData['approvededitorially_status']
                        ];

                        if($articleData['premoderation_status'] == 1)
                        {
                            $articleData = [
                                'current_version' => $newVersionId, 
                                'created_date' => $newArticleCreatedDate,
    
                                'current_title' => $newTitle, 
                                'current_text' => $newText,
    
                                'edit_timeout_to_date' => $newArticleCreatedDate
                            ];
                        }
                        else
                        {
                            $articleData = 
                            [
                                'current_version' => $newVersionId, 
                                'created_date' => $newArticleCreatedDate,
    
                                'current_title' => $newTitle, 
                                'current_text' => $newText,
    
                                'premoderation_status' => 3,
    
                                'edit_timeout_to_date' => $newArticleCreatedDate
                            ];
                        }

                        $articleEditTimeoutMinutes = AdminSettingsGetHandler::getSetting('article_edit_timeout_minutes');
                        if(!empty($articleEditTimeoutMinutes))
                        {
                            $articleData['edit_timeout_to_date'] = ceil(($newArticleCreatedDate + ($articleEditTimeoutMinutes * 60)) / 60) * 60;
                        }
            
                        $newTagsString = '';
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
                                        $articleData['current_tags'] = $newTagsString;
                                        $articleVersionData['tags'] = $newTagsString;
                                    }
                                    else
                                    {
                                        throw new Warning(400, 'Article has duplicated tags', 'Article has duplicated tags');
                                    }
                                }
                            }
                        }
                        
                        $this->database->update(
                            'articles', 
                            $articleData, 
                            [
                                'id' => $articleId
                            ]
                        );
            
                        $this->database->insert(
                            'articles_versions',
                            $articleVersionData
                        );
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
        $newText = StringFormatter::replaceViewIdsToViewIdsLinks($newText);
        $newText = StringFormatter::filterHtmlTags($newText);

        $articleData = $this->database->get(
            'articles', 
            [
                'current_version', 
                'current_title',
                'current_text', 
                'current_tags', 
                'editorially_status', 
                'premoderation_status', 
                'approvededitorially_status'
            ], 
            [
                'id' => $articleId
            ]
        );
        
        if(isset($articleData))
        {
            if($articleData['current_title'] == $newTitle && $articleData['current_text'] == $newText && $articleData['current_tags'] == $newTags)
            {
                throw new Error(400, 'Please make changes for edit', 'Please make changes for edit');
            }
            
            $newVersionId = $articleData['current_version'] + 1;
            $newArticleCreatedDate = time();

            $articleVersionData = $articleData = $this->database->get(
                'articles_versions', 
                [
                    'editorially_status', 
                    'premoderation_status', 
                    'approvededitorially_status'
                ], 
                [
                    'article_id' => $articleId,
                    'version_id' => $articleData['current_version']
                ]
            );

            $articleVersionData = [
                'article_id' => $articleId,
                'version_id' => $newVersionId,
                'created_date' => $newArticleCreatedDate,

                'title' => $newTitle,
                'text' => $newText,
            ];

            $articleData = 
            [
                'current_version' => $newVersionId, 
                'created_date' => $newArticleCreatedDate,

                'current_title' => $newTitle, 
                'current_text' => $newText, 
                'edit_timeout_to_date' => $newArticleCreatedDate
            ];

            $newTagsString = '';
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
                            $articleData['current_tags'] = $newTagsString;
                            $articleVersionData['tags'] = $newTagsString;
                        }
                        else
                        {
                            throw new Warning(400, 'Article has duplicated tags', 'Article has duplicated tags');
                        }
                    }
                }
            }
            
            $this->database->update(
                'articles', 
                $articleData, 
                [
                    'id' => $articleId
                ]
            );

            $this->database->insert(
                'articles_versions',
                $articleVersionData
            );
        }
        else
        {
            throw new Error(404, 'Article for editing not found', 'Article for editing not found');
        }
    }
}