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
    public function publishArticle($title, $text, $tags, $viewCode, $editCode)
    {
        $data = [
            'title' => $title,
            'text' => $text,
            'tags' => $tags
        ];

        $articleId = $this->database->insert('articles', $data);
        if($articleId)
        {
            $ratingData = [
                'article_id' => $articleId,
                'rating' => 0
            ];

            $this->database->insert('ratings', $ratingData);

            $codesData = [
                'article_id' => $articleId,
                'view_code' => $viewCode
                'edit_code' => $editCode
            ];

            $database->insert('codes', $codesData);
        }
        else
        {
            throw new Critical(500, "Unknown Error", "Failed to create article");
        }
    }
}