<?php
namespace Api\Models;

use Api\Handlers\AdminSettingsGetHandler;

use Base\BaseModel;

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
        $article = $this->database->get('articles', ['title', 'text' ,'tags','created_date', 'premoderation_status', 'approvededitorially_status', 'editorially_status'], ['id' => $articleId, 'ORDER' => ['version_id' => 'DESC'], 'LIMIT' => 1]);
        $articleStatistics = $this->database->get('statistics', ['rating', 'comments'], ['article_id' => $articleId]);

        if($article['tags'] != null)
        {
            $tagsString = substr(substr($article["tags"], 1), 0, -1);

            $article['tags'] = explode(',', $tagsString);
        }

        if($article['approvededitorially_status'] == 0)
        {
            $ratingToRequestApprove = AdminSettingsGetHandler::getSetting("article_need_rating_to_approve_editorially");
            if(isset($ratingToRequestApprove))
            {
                if($articleStatistics['rating'] > $ratingToRequestApprove)
                {
                    $article['canRequestApprove'] = true;
                }
                else
                {
                    $article['canRequestApprove'] = false;
                }
            }
            else
            {
                $article['canRequestApprove'] = false;
            }
        }
        else
        {
            $article['canRequestApprove'] = false;
        }

        $article['statistics'] = $articleStatistics;

        return $article;
    }
}