<?php
namespace Api\Models;

use Api\Handlers\AdminSettingsGetHandler;

use Base\BaseModel;

class ArticleEditPreloadModel extends BaseModel
{
    public function getArticleIdByEditCode($editCode)
    {
        return $this->database->get('articles', 'id', ['edit_code' => $editCode]);
    }
    
    public function viewArticle($articleId)
    {
        $article = $this->database->get('articles', ['current_tags', 'current_title', 'current_text', 'created_date', 'premoderation_status', 'approvededitorially_status', 'editorially_status', 'rating', 'comments_count', 'view_code'], ['id' => $articleId]);

        if($article['current_tags'] != null)
        {
            $tagsString = substr(substr($article["current_tags"], 1), 0, -1);

            $article['current_tags'] = explode(',', $tagsString);
        }

        if($article['approvededitorially_status'] == 0)
        {
            $ratingToRequestApprove = AdminSettingsGetHandler::getSetting("article_need_rating_to_approve_editorially");
            if(isset($ratingToRequestApprove))
            {
                if($article['rating'] >= $ratingToRequestApprove)
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

        return $article;
    }
}