<?php
namespace Api\Models;

use Base\BaseModel;

class AdminArticleApprovePreloadModel extends BaseModel
{
    public function getArticleByViewCode($viewCode)
    {
        return $this->database->get('articles', 'id', ['view_code' => $viewCode]);
    }

    public function viewArticle($articleId)
    {
        $article = $this->database->get('articles', ['current_title', 'current_text' ,'current_tags','created_date', 'rating', 'comments_count', 'premoderation_status', 'approvededitorially_status', 'editorially_status'], ['id' => $articleId]);

        if($article['current_tags'] != null)
        {
            $tagsString = substr(substr($article['current_tags'], 1), 0, -1);

            $article['current_tags'] = explode(',', $tagsString);
        }

        return $article;
    }
}