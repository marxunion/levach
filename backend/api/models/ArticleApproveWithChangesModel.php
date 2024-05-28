<?php
namespace Api\Models;

use Base\BaseModel;

class ArticleApproveWithChangesModel extends BaseModel
{
    public function getArticleIdByEditCode($editCode)
    {
        return $this->database->get('articles', 'id', ['edit_code' => $editCode]);
    }

    public function rejectApproveWithChanges($articleId)
    {
        $articlesData = $this->database->get('articles', ['current_version', 'approvededitorially_status'], ['id' => $articleId]);
        if($articlesData['approvededitorially_status'] == 3)
        {
            $this->database->delete('articles_versions', ['article_id' => $articleId, 'version_id' => $articlesData['current_version']]);
            $this->database->update('articles_versions', ['approvededitorially_status' => 0], ['article_id' => $articleId]);
            $this->database->update('articles', ['approvededitorially_status' => 0], ['id' => $articleId]);
        }
        else
        {
            throw new Error(400, "Article not approved with changes", "Article not approved with changes");
        }
    }

    public function acceptApproveWithChanges($articleId)
    {
        $articlesData = $this->database->get('articles', ['current_version', 'approvededitorially_status'], ['id' => $articleId]);
        if($articlesData['approvededitorially_status'] == 3)
        {
            $this->database->delete('articles_versions', ['article_id' => $articleId, 'version_id[!]' => $articlesData['current_version']]);
            $this->database->update('articles_versions', ['approvededitorially_status' => 2, 'version_id' => 1], ['article_id' => $articleId]);
            $this->database->update('articles', ['approvededitorially_status' => 2, 'current_version' => 1], ['id' => $articleId]);
        }
        else
        {
            throw new Error(400, "Article not approved with changes", "Article not approved with changes");
        }
    }
}