<?php
namespace Api\Models;

use Core\Error;

use Api\Handlers\AdminSettingsGetHandler;

use Base\BaseModel;

class ArticleApproveRequestModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getArticleIdByEditCode($editCode)
    {
        return $this->database->get('codes', 'article_id', ['edit_code' => $editCode]);
    }

    public function requestApprove($articleId)
    {
        $ratingToRequestApprove = AdminSettingsGetHandler::getSetting("article_need_rating_to_approve_editorially");
        
        if(isset($ratingToRequestApprove))
        {
            if($this->database->get('statistics', 'rating', ['article_id' => $articleId]) >= $ratingToRequestApprove)
            {
                $this->database->update('articles', ['approvededitorially_status' => 1], ['id' => $articleId]);
                $this->database->update('statistics', ['approvededitorially_status' => 1], ['article_id' => $articleId]);
            }
            else
            {
                throw new Error(403, "Approve request insufficient number of ratings", "Approve request insufficient number of ratings");
            }
        }
    }
}