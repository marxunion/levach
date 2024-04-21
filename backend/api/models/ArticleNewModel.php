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
        $data = [
            'version_id' => 1,
            'title' => $title,
            'text' => $text,
            'tags' => null,
            'premoderation_status' => 0,
            'acceptededitorially_status' => 0,
            'date' => time()
        ];

        if(is_array($tags))
        {
            if(count($tags) > 0)
            {
                $tagsString = implode(', ', $tags);
                $data['tags'] = '{'.$tagsString.'}';
            }
        }

        $this->database->insert('articles', $data);
        $articleId = $this->database->id();
        if($articleId)
        {
            $statisticsData = [
                'article_id' => $articleId,
                'rating' => 0,
                'comments' => 0,
                "current_version" => 1
            ];
            $this->database->insert('statistics', $statisticsData);

            $codesData = [
                'article_id' => $articleId,
                'view_code' => $viewCode,
                'edit_code' => $editCode
            ];
            $this->database->insert('codes', $codesData);
        }
        else
        {
            throw new Critical(500, "Unknown Error", "Failed to create article");
        }
    }
}