<?php
namespace Api\Models;

use Base\BaseModel;

class ArticleApproveWithChangesModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getArticleIdByEditCode($editCode)
    {
        return $this->database->get('codes', 'article_id', ['edit_code' => $editCode]);
    }

    public function rejectApproveWithChanges($articleId)
    {
        $statisticsData = $this->database->get('statistics', ['current_version', 'approvededitorially_status'], ['article_id' => $articleId]);
        if($statisticsData['approvededitorially_status'] == 3)
        {
            $this->database->delete('articles', ['id' => $articleId, 'version_id' => $statisticsData['current_version']]);
            $this->database->update('articles', ['approvededitorially_status' => 0], ['id' => $articleId]);
            $this->database->update('statistics', ['approvededitorially_status' => 0], ['article_id' => $articleId]);
        }
        else
        {
            throw new Error(400, "Article not approved with changes", "Article not approved with changes");
        }
    }

    public function acceptApproveWithChanges($articleId)
    {
        if($this->database->get('statistics', 'approvededitorially_status', ['article_id' => $articleId]) == 3)
        {
            $this->database->delete('articles', ['id' => $articleId, 'version_id[i]' => $statisticsData['current_version']]);
            $this->database->update('articles', ['approvededitorially_status' => 2, 'version_id' => 1], ['id' => $articleId]);
            $this->database->update('statistics', ['approvededitorially_status' => 2, 'current_version' => 1], ['article_id' => $articleId]);
        }
        else
        {
            throw new Error(400, "Article not approved with changes", "Article not approved with changes");
        }
    }
}