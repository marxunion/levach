<?php
namespace Api\Models;

use Core\Warning;

use Base\BaseModel;

class ArticleNewModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }
    public function newArticle($title, $text, $tags, $viewCode, $editCode)
    {
        $lastArticleId = $this->database->max('articles', 'id');
        $newArticleId = 1;

        if($lastArticleId !== '') 
        {
            $newArticleId = $lastArticleId + 1;
        }

        $articleData = [
            'id' => $newArticleId,
            'version_id' => 1,
            'title' => $title,
            'text' => $text,
            'tags' => null,
            
            'editorially_status' => 0,
            'premoderation_status' => 1,
            'approvededitorially_status' => 0,
            
            'created_date' => time()
        ];

        $statisticsData = 
        [
            'article_id' => $newArticleId,
            'rating' => 0,
            'comments' => 0,
            'created_date' => time(),
            
            'edit_timeout_to_date' => time(),

            'current_version' => 1,
            'current_title' => $title,
            'current_text' => $text,
            'current_tags' => null,

            'editorially_status' => 0,
            'premoderation_status' => 1,
            'approvededitorially_status' => 0
        ];

        $codesData = 
        [
            'article_id' => $newArticleId,
            'view_code' => $viewCode,
            'edit_code' => $editCode
        ];
        
        $tagsString = '';
        if(is_array($tags))
        {
            if(count($tags) > 0)
            {
                if(count($tags) == count(array_unique($tags)))
                {
                    $tagsString = '{'.implode(',', $tags).'}';
                    $articleData['tags'] = $tagsString;
                    $statisticsData['current_tags'] = $tagsString;
                }
                else
                {
                    throw new Warning(400, 'Article has duplicated tags', 'Article has duplicated tags');
                }
            }
        }

        $this->database->insert('articles', $articleData);
        $this->database->insert('statistics', $statisticsData);
        $this->database->insert('codes', $codesData);
    }

    public function newArticleAdmin($title, $text, $tags, $viewCode, $editCode)
    {
        $lastArticleId = $this->database->max('articles', 'id');
        $newArticleId = 1;

        if($lastArticleId !== '') 
        {
            $newArticleId = $lastArticleId + 1;
        }

        $articleData = [
            'id' => $newArticleId,
            'version_id' => 1,
            'title' => $title,
            'text' => $text,
            'tags' => null,
            
            'editorially_status' => 1,
            
            'premoderation_status' => 2,
            'approvededitorially_status' => 2,
            
            'created_date' => time()
        ];

        $statisticsData = 
        [
            'article_id' => $newArticleId,
            'rating' => 0,
            'comments' => 0,
            'created_date' => time(),
            
            'edit_timeout_to_date' => time(),

            'current_version' => 1,
            'current_title' => $title,
            'current_text' => $text,
            'current_tags' => null,

            'editorially_status' => 1,
            'premoderation_status' => 2,
            'approvededitorially_status' => 2
        ];

        $codesData = 
        [
            'article_id' => $newArticleId,
            'view_code' => $viewCode,
            'edit_code' => $editCode
        ];

        $tagsString = '';
        if(is_array($tags))
        {
            if(count($tags) > 0)
            {
                if(count($tags) == count(array_unique($tags)))
                {
                    $tagsString = '{'.implode(',', $tags).'}';
                    $articleData['tags'] = $tagsString;
                    $statisticsData['current_tags'] = $tagsString;
                }
                else
                {
                    throw new Warning(400, 'Article has duplicated tags', 'Article has duplicated tags');
                }
            }
            else
            {
                $articleData['tags'] = '{}';
                $statisticsData['current_tags'] =  '{}';
            }
        }
        else
        {
            $articleData['tags'] = '{}';
            $statisticsData['current_tags'] = '{}';
        }

        $this->database->insert('articles', $articleData);
        $this->database->insert('statistics', $statisticsData);
        $this->database->insert('codes', $codesData);
    }
}