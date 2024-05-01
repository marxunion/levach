<?php
namespace Api\Models;

use Core\Database;

use Base\BaseModel;

class ArticleViewModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getArticleByViewCode($viewCode)
    {
        return $this->database->get('codes', 'article_id', ['view_code' => $viewCode]);
    }

    public function viewArticle($articleId)
    {
        $articleVertions = $this->database->select('articles', ['title', 'text', 'tags', 'date', 'premoderation_status', 'approvededitorially_status'], ['id' => $articleId]);
        $articleStatistics = $this->database->get('statistics', ['rating', 'comments'], ['article_id' => $articleId]);

        foreach ($articleVertions as $versionNum => $versionInfo) 
        {
            if ($versionInfo['tags'] != null) 
            {
                $tagsString = substr(substr($versionInfo["tags"], 1), 0, -1);
                $articleVertions[$versionNum]['tags'] = explode(',', $tagsString);
            }
        }

        $article = [
            'versions' => $articleVertions,
            'statistics' => $articleStatistics
        ];
        return $article;
    }
}