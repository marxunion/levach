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
        $articleVersions = $this->database->select('articles', ['title', 'text', 'tags', 'created_date', 'premoderation_status', 'approvededitorially_status'], ['id' => $articleId, 'premoderation_status' => 2]);
        if(isset($articleVersions))
        {
            $articleStatistics = $this->database->get('statistics', ['rating', 'comments'], ['article_id' => $articleId, 'premoderation_status' => 2]);
            if(isset($articleStatistics))
            {
                foreach ($articleVersions as $versionNum => $versionInfo) 
                {
                    if ($versionInfo['tags'] != null) 
                    {
                        $tagsString = substr(substr($versionInfo["tags"], 1), 0, -1);
                        $articleVersions[$versionNum]['tags'] = explode(',', $tagsString);
                    }
                }

                $article = [
                    'versions' => $articleVersions,
                    'statistics' => $articleStatistics
                ];
                return $article;
            }
        }
        else
        {
            return null;
        }
    }

    public function viewArticleAdmin($articleId)
    {
        $articleVersions = $this->database->select('articles', ['title', 'text', 'tags', 'created_date', 'premoderation_status', 'approvededitorially_status'], ['id' => $articleId]);
        if(isset($articleVersions))
        {
            $articleStatistics = $this->database->get('statistics', ['rating', 'comments'], ['article_id' => $articleId]);

            foreach ($articleVersions as $versionNum => $versionInfo) 
            {
                if ($versionInfo['tags'] != null) 
                {
                    $tagsString = substr(substr($versionInfo["tags"], 1), 0, -1);
                    $articleVersions[$versionNum]['tags'] = explode(',', $tagsString);
                }
            }
    
            $article = [
                'versions' => $articleVersions,
                'statistics' => $articleStatistics
            ];
            return $article;
        }
        else
        {
            return null;
        }
    }
}