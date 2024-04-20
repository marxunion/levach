<?php
namespace Api\Models;

use Base\BaseModel;

use Core\Database;

class ArticleEditPreloadModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getArticleIdByEditCode($editCode)
    {
        return $this->database->get('codes', 'article_id', ['edit_code' => $editCode]);
    }
    
    public function viewArticle($articleId)
    {
        $article = $this->database->get('articles', ['title', 'text' ,'tags','date', 'premoderation_status', 'acceptededitorially_status'], ['id' => $articleId, 'ORDER' => ['version_id' => 'DESC'],'LIMIT' => 1]);
        $articleStatistics = $this->database->get('statistics', ['rating', 'comments'], ['article_id' => $articleId]);

        if($article['tags'] != null)
        {
            $tagsString = substr(substr($article["tags"], 1), 0, -1);

            $article['tags'] = explode(',', $tagsString);
        }

        $article['statistics'] = $articleStatistics;
        return $article;
    }
}