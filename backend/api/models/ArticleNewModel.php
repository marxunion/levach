<?php
namespace Api\Models;

use Base\BaseModel;

use Core\Database;

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

        $data = [
            'id' => $newArticleId,
            'version_id' => 1,
            'title' => $title,
            'text' => $text,
            'tags' => null,
            
            'editorially_status' => 0,
            'premoderation_status' => 0,
            'acceptededitorially_status' => 0,
            
            'date' => time()
        ];

        $tagsString = '';
        if(is_array($tags))
        {
            if(count($tags) > 0)
            {
                if(count($tags) == count(array_unique($tags)))
                {
                    $tagsString = '{'.implode(',', $tags).'}';
                    $data['tags'] = $tagsString;
                }
                else
                {
                    throw new Warning(400, 'Article has duplicated tags', 'Article has duplicated tags');
                }
            }
        }

        $this->database->insert('articles', $data);

        $statisticsData = 
        [
            'article_id' => $newArticleId,
            'rating' => 0,
            'comments' => 0,
            'created_at' => time(),
            'current_version' => 1,
            'edit_timeout_to_date' => time()
        ];
        $this->database->insert('statistics', $statisticsData);

        $codesData = 
        [
            'article_id' => $newArticleId,
            'view_code' => $viewCode,
            'edit_code' => $editCode
        ];
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

        $data = [
            'id' => $newArticleId,
            'version_id' => 1,
            'title' => $title,
            'text' => $text,
            'tags' => null,
            
            'editorially_status' => 1,
            
            'premoderation_status' => 2,
            'acceptededitorially_status' => 2,
            
            'date' => time()
        ];

        $tagsString = '';
        if(is_array($tags))
        {
            if(count($tags) > 0)
            {
                if(count($tags) == count(array_unique($tags)))
                {
                    $tagsString = '{'.implode(',', $tags).'}';
                    $data['tags'] = $tagsString;
                }
                else
                {
                    throw new Warning(400, 'Article has duplicated tags', 'Article has duplicated tags');
                }
            }
        }

        $this->database->insert('articles', $data);

        $statisticsData = 
        [
            'article_id' => $newArticleId,
            'rating' => 0,
            'comments' => 0,
            'created_at' => time(),
            'current_version' => 1,
            'edit_timeout_to_date' => time()
        ];
        $this->database->insert('statistics', $statisticsData);

        $codesData = 
        [
            'article_id' => $newArticleId,
            'view_code' => $viewCode,
            'edit_code' => $editCode
        ];
        $this->database->insert('codes', $codesData);
    }
}