<?php
namespace Api\Models;

use Base\BaseModel;

class ArticleAcceptApproveWithChangesModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getArticleIdByEditCode($editCode)
    {
        return $this->database->get('codes', 'article_id', ['edit_code' => $editCode]);
    }

    public function acceptApproveWithChanges($articleId)
    {
        if($this->database->get('statistics', 'approvededitorially_status', ['article_id' => $articleId]) == 3)
        {
            $this->database->update('statistics', ['approvededitorially_status' => 2], ['article_id' => $articleId]);
        }
        else
        {
            throw new Error(400, "Article not approved with changes", "Article not approved with changes");
        }
    }
}