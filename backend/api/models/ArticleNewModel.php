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
            'title' => $title,
            'text' => $text,
            'premoderationStatus' => 0,
            'acceptedEditoriallyStatus' => 0,
            'tags' => null
        ];

        if(is_array($tags))
        {
            if(count($tags) > 0)
            {
                $data['tags'] = $tags;
            }
        }

        $this->database->insert('articles', $data);
        $articleId = $this->database->id();
        if($articleId)
        {
            $statisticsData = [
                'article_id' => $articleId,
                'rating' => 0,
                'comments' => 0
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