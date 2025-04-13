<?php
namespace Routes\Api\Article\Approve\Request;

use Core\Error;

use Api\Handlers\AdminSettingsGetHandler;

use Base\BaseModel;

class MainModel extends BaseModel
{
    public function getArticleIdByEditCode($editCode)
    {
        return $this->database->get('articles', 'id', ['edit_code' => $editCode]);
    }

    public function requestApprove($articleId)
    {
        $ratingToRequestApprove = AdminSettingsGetHandler::getSetting("article_need_rating_to_approve_editorially");
        
        if(isset($ratingToRequestApprove))
        {
            if($this->database->get('articles', 'rating', ['id' => $articleId]) >= $ratingToRequestApprove)
            {
                $this->database->update('articles_versions', ['approvededitorially_status' => 1], ['article_id' => $articleId]);
                $this->database->update('articles', ['approvededitorially_status' => 1], ['id' => $articleId]);
            }
            else
            {
                throw new Error(403, "Approve request insufficient number of ratings", "Approve request insufficient number of ratings");
            }
        }
    }
}