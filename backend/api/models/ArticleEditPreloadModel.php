<?php
namespace Api\Models;

use Api\Handlers\AdminSettingsGetHandler;

use Base\BaseModel;

class ArticleEditPreloadModel extends BaseModel
{
    public function getArticleIdByEditCode($editCode)
    {
        return $this->database->get('codes', 'article_id', ['edit_code' => $editCode]);
    }
    
    public function viewArticle($articleId)
    {
        $article = [];
        $article['statistics'] = $this->database->get('statistics', ['current_tags', 'current_title', 'current_text', 'created_date', 'premoderation_status', 'approvededitorially_status', 'editorially_status', 'rating', 'comments'], ['article_id' => $articleId]);
        $article['view_code'] = $this->database->get('codes', 'view_code', ['article_id' => $articleId]);

        if($article['statistics']['current_tags'] != null)
        {
            $tagsString = substr(substr($article['statistics']["current_tags"], 1), 0, -1);

            $article['statistics']['current_tags'] = explode(',', $tagsString);
        }

        if($article['statistics']['approvededitorially_status'] == 0)
        {
            $ratingToRequestApprove = AdminSettingsGetHandler::getSetting("article_need_rating_to_approve_editorially");
            if(isset($ratingToRequestApprove))
            {
                if($article['statistics']['rating'] >= $ratingToRequestApprove)
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