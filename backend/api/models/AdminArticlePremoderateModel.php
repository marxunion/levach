<?php
namespace Api\Models;

use Base\BaseModel;

class AdminArticlePremoderateModel extends BaseModel
{
    public function getArticleByViewCode($viewCode)
    {
        return $this->database->get('articles', 'id', ['view_code' => $viewCode]);
    }

    public function getArticleByViewId($viewId)
    {
        $articleId = $this->database->get('articles', 'id', ['view_id' => $viewId]);

        if(!isset($articleId))
        {
            $comment = $this->database->get('comments', ['id', 'article_id'], ['view_id' => $viewId]);

            if(isset($comment))
            {
                $articleId = $comment['article_id'];
            }
        }

        return $articleId;
    }

    public function acceptPremoderate($articleId, $versionId)
    {
        $acceptedAllVersionsStatus = false;
        $premoderationStatus = $this->database->get('articles', 'premoderation_status', ['id' => $articleId]);
        $maxVersionId = $this->database->max('articles_versions', 'version_id', ['article_id' => $articleId]);
        if($premoderationStatus == 3)
        {
            if($versionId == $maxVersionId)
            {
                $acceptedAllVersionsStatus = true;
                $this->database->update('articles', ['premoderation_status' => 2], ['id' => $articleId]);
            }
        }
        else
        {
            if($versionId == $maxVersionId)
            {
                $acceptedAllVersionsStatus = true;
                $this->database->update('articles', ['premoderation_status' => 2], ['id' => $articleId]);
            }
            else
            {
                $this->database->update('articles', ['premoderation_status' => 3], ['id' => $articleId]);
            }
        }
        $this->database->update('articles_versions', ['premoderation_status' => 2], ['premoderation_status' => 1, 'version_id[<=]' => $versionId, 'article_id' => $articleId]);
        return $acceptedAllVersionsStatus;
    }

    public function rejectPremoderate($articleId, $versionId)
    {
        $deletedAllVersionsStatus = false;
        $premoderationStatus = $this->database->get('articles', 'premoderation_status', ['id' => $articleId]);
        
        if($premoderationStatus == 3)
        {
            $minVersionId = $this->database->min('articles_versions', 'version_id', ['premoderation_status' => 1, 'article_id' => $articleId]);
            if($versionId == $minVersionId)
            {
                $deletedAllVersionsStatus = true;
                $this->database->update('articles', ['premoderation_status' => 2], ['id' => $articleId]);
            }
        }
        else
        {
            if($versionId == 0)
            {
                $deletedAllVersionsStatus = true;
                $this->database->delete('articles', ['id' => $articleId]);
            }
        }
        $this->database->delete('articles_versions', ['premoderation_status' => 1, 'version_id[>=]' => $versionId, 'article_id' => $articleId]);
        return $deletedAllVersionsStatus;
    }
}