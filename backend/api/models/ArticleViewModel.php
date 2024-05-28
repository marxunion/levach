<?php
namespace Api\Models;

use Base\BaseModel;

class ArticleViewModel extends BaseModel
{
    public function getArticleByViewCode($viewCode)
    {
        return $this->database->get('articles', 'id', ['view_code' => $viewCode]);
    }

    public function viewArticle($articleId)
    {
        $articleVersions = $this->database->select(
            'articles_versions', 
            [
                'title', 
                'text', 
                'tags', 
                'created_date',
                'editorially_status', 
                'approvededitorially_status', 
                'premoderation_status'
            ],
            [
                "ORDER" => [
                    "version_id" => "ASC",
                ],
                'article_id' => $articleId, 
                'premoderation_status' => 2
            ]
        );
        if(isset($articleVersions))
        {
            $article = $this->database->get('articles', ['rating', 'comments_count', 'editorially_status', 'approvededitorially_status', 'premoderation_status'], ['id' => $articleId, 'premoderation_status' => 2]);
            if(isset($article))
            {
                foreach($articleVersions as $versionNum => $versionInfo) 
                {
                    if($versionInfo['tags'] != null) 
                    {
                        $tagsString = substr(substr($versionInfo["tags"], 1), 0, -1);
                        $articleVersions[$versionNum]['tags'] = explode(',', $tagsString);
                    }
                }

                $article['versions'] = $articleVersions;
                
                return $article;
            }
            else
            {
                return null;
            }
        }
        else
        {
            return null;
        }
    }

    public function viewArticleAdmin($articleId)
    {
        $articleVersions = $this->database->select(
            'articles_versions', 
            [
                'title', 
                'text', 
                'tags', 
                'created_date', 
                'editorially_status', 
                'approvededitorially_status', 
                'premoderation_status'
            ], 
            [
                "ORDER" => [
                    "version_id" => "ASC",
                ],
                'article_id' => $articleId
            ]
        );
        if(isset($articleVersions))
        {
            $article = $this->database->get('articles', ['rating', 'comments_count', 'editorially_status', 'approvededitorially_status', 'premoderation_status'], ['id' => $articleId]);

            if(isset($article))
            {
                foreach($articleVersions as $versionNum => $versionInfo) 
                {
                    if($versionInfo['tags'] != null) 
                    {
                        $tagsString = substr(substr($versionInfo["tags"], 1), 0, -1);
                        $articleVersions[$versionNum]['tags'] = explode(',', $tagsString);
                    }
                }
        
                $article['versions'] = $articleVersions;
                return $article;
            }
            else
            {
                return null;
            }
        }
        else
        {
            return null;
        }
    }
}