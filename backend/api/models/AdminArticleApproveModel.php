<?php
namespace Api\Models;

use Core\Warning;
use Core\Error;

use Base\BaseModel;

class AdminArticleApproveModel extends BaseModel
{
    public function getArticleByViewCode($viewCode)
    {
        return $this->database->get('codes', 'article_id', ['view_code' => $viewCode]);
    }

    public function rejectApprove($articleId)
    {
        $this->database->update('statistics', ['approvededitorially_status' => 0], ['article_id' => $articleId]);
        $this->database->update('articles', ['approvededitorially_status' => 0], ['id' => $articleId]);
    }

    public function acceptApprove($articleId)
    {
        $this->database->update('statistics', ['approvededitorially_status' => 2], ['article_id' => $articleId]);
        $this->database->update('articles', ['approvededitorially_status' => 2], ['id' => $articleId]);
    }

    public function acceptApproveWithChanges($articleId, $newTitle, $newText, $newTags)
    {
        $this->database->update('articles', ['approvededitorially_status' => 3], ['id' => $articleId]);
        $articleData = $this->database->get('statistics', ['current_version', 'current_title', 'current_text', 'current_tags'], ['article_id' => $articleId]);
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
                            $newTagsString = '{'.implode(',', $newTags).'}';
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

            $this->database->update(
                'articles', 
                [
                    'approvededitorially_status' => 3
                ],
                [
                    'id' => $articleId
                ]
            );

            $this->database->insert(
                'articles',
                [
                    'id' => $articleId,
                    'version_id' => $newVersionId,
                    'created_date' => $newArticleCreatedDate,

                    'title' => $newTitle,
                    'text' => $newText,
                    'tags' => $newTagsString,

                    'editorially_status' => 0,
                    'premoderation_status' => 2,
                    'approvededitorially_status' => 3
                ]
            );
            
            $this->database->update(
                'statistics', 
                [
                    'current_version' => $newVersionId, 
                    'created_date' => $newArticleCreatedDate,

                    'current_title' => $newTitle, 
                    'current_text' => $newText, 
                    'current_tags' => $newTagsString, 
                    
                    'editorially_status' => 0,
                    'premoderation_status' => 2,
                    'approvededitorially_status' => 3,

                    'edit_timeout_to_date' => $newArticleCreatedDate
                ], 
                [
                    'article_id' => $articleId
                ]
            );
        }
        else 
        {
            throw new Error(404, "Article not found", "Article not found");
        }
    }
}