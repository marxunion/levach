<?php
namespace Api\Models;

use Core\Warning;

use Base\BaseModel;

class ArticleNewModel extends BaseModel
{
    public function newArticle($title, $text, $tags, $viewCode, $editCode)
    {
        $newArticleId = 1;
        $lastArticleId = $this->database->max('articles', 'id');

        if($lastArticleId != null) 
        {
            $newArticleId = $lastArticleId + 1;
        }

        $articleVersionData = [
            'article_id' => $newArticleId,
            'version_id' => 1,
            'title' => $title,
            'text' => $text,
            'tags' => null,
            
            'editorially_status' => 0,
            'premoderation_status' => 1,
            'approvededitorially_status' => 0,
            
            'created_date' => time()
        ];

        $articleData = 
        [
            'id' => $newArticleId,
            'rating' => 0,
            'comments_count' => 0,
            'created_date' => time(),
            
            'edit_timeout_to_date' => time(),

            'current_version' => 1,
            'current_title' => $title,
            'current_text' => $text,
            'current_tags' => null,

            'view_code' => $viewCode,
            'edit_code' => $editCode,

            'editorially_status' => 0,
            'premoderation_status' => 1,
            'approvededitorially_status' => 0
        ];
        
        $tagsString = '';
        if(is_array($tags))
        {
            if(count($tags) > 0)
            {
                foreach ($tags as $key => $value) 
                {
                    $tags[$key] = trim($tags[$key]);
                }
                if(count($tags) == count(array_unique($tags)))
                {
                    $tagsString = '{'.implode(',', $tags).'}';
                    $articleVersionData['tags'] = $tagsString;
                    $articleData['current_tags'] = $tagsString;
                }
                else
                {
                    throw new Warning(400, 'Article has duplicated tags', 'Article has duplicated tags');
                }
            }
        }

        $this->database->insert('articles_versions', $articleVersionData);
        $this->database->insert('articles', $articleData);
    }

    public function newArticleAdmin($title, $text, $tags, $viewCode, $editCode)
    {
        $lastArticleId = $this->database->max('articles', 'id');
        $newArticleId = 1;

        if($lastArticleId !== '') 
        {
            $newArticleId = $lastArticleId + 1;
        }

        $articleVersionData = [
            'article_id' => $newArticleId,
            'version_id' => 1,
            'title' => $title,
            'text' => $text,
            'tags' => null,
            
            'editorially_status' => 1,
            
            'premoderation_status' => 2,
            'approvededitorially_status' => 2,
            
            'created_date' => time()
        ];

        $articleData = 
        [
            'id' => $newArticleId,
            'rating' => 0,
            'comments_count' => 0,
            'created_date' => time(),
            
            'edit_timeout_to_date' => time(),

            'current_version' => 1,
            'current_title' => $title,
            'current_text' => $text,
            'current_tags' => null,

            'view_code' => $viewCode,
            'edit_code' => $editCode,

            'editorially_status' => 1,
            'premoderation_status' => 2,
            'approvededitorially_status' => 2
        ];

        if(is_array($tags))
        {
            if(count($tags) > 0)
            {
                foreach ($tags as $key => $value) 
                {
                    $tags[$key] = trim($tags[$key]);
                }
                if(count($tags) == count(array_unique($tags)))
                {
                    $tagsString = '{'.implode(',', $tags).'}';
                    $articleVersionData['tags'] = $tagsString;
                    $articleData['current_tags'] = $tagsString;
                }
                else
                {
                    throw new Warning(400, 'Article has duplicated tags', 'Article has duplicated tags');
                }
            }
        }

        $this->database->insert('articles_versions', $articleVersionData);
        $this->database->insert('articles', $articleData);
    }
}