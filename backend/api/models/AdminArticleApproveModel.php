<?php
namespace Api\Models;

use Core\Error;
use Core\Warning;

use Helpers\StringFormatter;

use Base\BaseModel;

class AdminArticleApproveModel extends BaseModel
{
    public function getArticleByViewCode($viewCode)
    {
        return $this->database->get('articles', 'id', ['view_code' => $viewCode]);
    }

    public function getArticleByViewId($viewId)
    {
        $articleId = $this->database->get('articles', 'id', ['view_id' => $viewId]);

        if(!isset($articleId))
        {
            $comment = $this->database->get('comments', ['id', 'article_id'], ['view_id' => $viewId]);

            if(isset($comment))
            {
                $articleId = $comment['article_id'];
            }
        }

        return $articleId;
    }

    public function rejectApprove($articleId)
    {
        $this->database->update('articles', ['approvededitorially_status' => 0], ['id' => $articleId]);
        $this->database->update('articles_versions', ['approvededitorially_status' => 0], ['article_id' => $articleId]);
    }

    public function acceptApprove($articleId)
    {
        $this->database->update('articles', ['approvededitorially_status' => 2], ['id' => $articleId]);
        $this->database->update('articles_versions', ['approvededitorially_status' => 2], ['article_id' => $articleId]);
    }

    public function acceptApproveWithChanges($articleId, $newTitle, $newText, $newTags)
    {
        $newText = StringFormatter::replaceViewIdsToViewIdsLinks($newText);
        $newText = StringFormatter::filterHtmlTags($newText);
        
        $this->database->update('articles_versions', ['approvededitorially_status' => 3], ['article_id' => $articleId]);
        $articleData = $this->database->get('articles', ['current_version', 'current_title', 'current_text', 'current_tags'], ['id' => $articleId]);
        if(isset($articleData))
        {
            if($articleData['current_title'] == $newTitle && $articleData['current_text'] == $newText && $articleData['current_tags'] == $newTags)
            {
                throw new Error(400, 'Please make changes for edit', 'Please make changes for edit');
            }

            $newVersionId = $articleData['current_version'] + 1;
            $newArticleDate = time();

            $newTagsString = '';

            $articleData = [
                'current_version' => $newVersionId, 
                'last_edit_date' => $newArticleDate,

                'current_title' => $newTitle, 
                'current_text' => $newText,
                
                'editorially_status' => 0,
                'premoderation_status' => 2,
                'approvededitorially_status' => 3,

                'edit_timeout_to_date' => $newArticleDate
            ];

            $articleVersionData = [
                'article_id' => $articleId,
                'version_id' => $newVersionId,
                'created_date' => $newArticleDate,

                'title' => $newTitle,
                'text' => $newText,

                'editorially_status' => 0,
                'premoderation_status' => 2,
                'approvededitorially_status' => 3
            ];

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
                'articles_versions', 
                [
                    'approvededitorially_status' => 3
                ],
                [
                    'article_id' => $articleId
                ]
            );

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
            throw new Error(404, "Article not found", "Article not found");
        }
    }
}