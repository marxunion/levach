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
        $article = $this->database->get('articles', ['title', 'text' ,'tags','date', 'premoderationStatus', 'acceptedEditoriallyStatus'], ['id' => $articleId, 'ORDER' => ['version_id' => 'DESC'],'LIMIT' => 1]);
        $articleStatistics = $this->database->get('statistics', ['rating', 'comments'], ['article_id' => $articleId]);

        $article['statistics'] = $articleStatistics;
        return $article;
    }
}