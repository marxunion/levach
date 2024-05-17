<?php
namespace Api\Models;

use Base\BaseModel;

class AdminArticleApprovePreloadModel extends BaseModel
{
    public function getArticleByViewCode($viewCode)
    {
        return $this->database->get('codes', 'article_id', ['view_code' => $viewCode]);
    }

    public function viewArticle($articleId)
    {
        $article['statistics'] = $this->database->get('statistics', ['current_title', 'current_text' ,'current_tags','created_date', 'rating', 'comments', 'premoderation_status', 'approvededitorially_status', 'editorially_status'], ['article_id' => $articleId]);

        if($article['statistics']['current_tags'] != null)
        {
            $tagsString = substr(substr($article['statistics']['current_tags'], 1), 0, -1);

            $article['statistics']['current_tags'] = explode(',', $tagsString);
        }

        return $article;
    }
}