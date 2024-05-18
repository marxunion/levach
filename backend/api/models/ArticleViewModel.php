<?php
namespace Api\Models;

use Base\BaseModel;

class ArticleViewModel extends BaseModel
{
    public function getArticleByViewCode($viewCode)
    {
        return $this->database->get('codes', 'article_id', ['view_code' => $viewCode]);
    }

    public function viewArticle($articleId)
    {
        $articleVersions = $this->database->select(
            'articles', 
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
                'id' => $articleId, 
                'premoderation_status' => 2
            ]
        );
        if(isset($articleVersions))
        {
            $articleStatistics = $this->database->get('statistics', ['rating', 'comments', 'editorially_status', 'approvededitorially_status', 'premoderation_status'], ['article_id' => $articleId, 'premoderation_status' => 2]);
            if(isset($articleStatistics))
            {
                foreach($articleVersions as $versionNum => $versionInfo) 
                {
                    if($versionInfo['tags'] != null) 
                    {
                        $tagsString = substr(substr($versionInfo["tags"], 1), 0, -1);
                        $articleVersions[$versionNum]['tags'] = explode(',', $tagsString);
                    }
                }

                $article = [
                    'versions' => $articleVersions,
                    'statistics' => [
                        'rating' => $articleStatistics['rating'],
                        'comments' => $articleStatistics['comments']
                    ],
                    'editorially_status' => $articleStatistics['editorially_status'],
                    'approvededitorially_status' => $articleStatistics['approvededitorially_status'],
                    'premoderation_status' => $articleStatistics['premoderation_status']
                ];
                
                return $article;
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
            'articles', 
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
                'id' => $articleId
            ]
        );
        if(isset($articleVersions))
        {
            $articleStatistics = $this->database->get('statistics', ['rating', 'comments', 'editorially_status', 'approvededitorially_status', 'premoderation_status'], ['article_id' => $articleId]);

            foreach($articleVersions as $versionNum => $versionInfo) 
            {
                if($versionInfo['tags'] != null) 
                {
                    $tagsString = substr(substr($versionInfo["tags"], 1), 0, -1);
                    $articleVersions[$versionNum]['tags'] = explode(',', $tagsString);
                }
            }
    
            $article = [
                'versions' => $articleVersions,
                'statistics' => [
                    'rating' => $articleStatistics['rating'],
                    'comments' => $articleStatistics['comments']
                ],
                'editorially_status' => $articleStatistics['editorially_status'],
                'approvededitorially_status' => $articleStatistics['approvededitorially_status'],
                'premoderation_status' => $articleStatistics['premoderation_status']
            ];
            return $article;
        }
        else
        {
            return null;
        }
    }
}