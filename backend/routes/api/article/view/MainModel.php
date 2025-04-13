<?php
namespace Routes\Api\Article\View;

use Base\BaseModel;

class MainModel extends BaseModel
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

                $articleVersions = $this->database->select(
                    'articles_versions', 
                    [
                        'title', 
                        'text', 
                        'tags', 
                        'created_date',
                        'version_id',
                        'editorially_status', 
                        'approvededitorially_status', 
                        'premoderation_status'
                    ],
                    [
                        "ORDER" => [
                            "version_id" => "DESC",
                        ],
                        'article_id' => $articleId, 
                        'premoderation_status' => 2
                    ]
                );
                if(isset($articleVersions))
                {
                    $article = $this->database->get(
                        'articles', 
                        [
                            'rating',
                            'comments_count', 
                            'editorially_status', 
                            'approvededitorially_status', 
                            'premoderation_status', 
                            'view_id'
                        ], 
                        [
                            'id' => $articleId, 
                            'premoderation_status' => 2
                        ]
                    );

                    $article['scrollToCommentId'] = $comment['id'];
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
            else
            {
                return null;
            }
        }
        else
        {
            $articleVersions = $this->database->select(
                'articles_versions', 
                [
                    'title', 
                    'text', 
                    'tags', 
                    'created_date',
                    'version_id',
                    'editorially_status', 
                    'approvededitorially_status', 
                    'premoderation_status'
                ],
                [
                    "ORDER" => [
                        "version_id" => "DESC",
                    ],
                    'article_id' => $articleId, 
                    'premoderation_status' => 2
                ]
            );
            if(isset($articleVersions))
            {
                $article = $this->database->get(
                    'articles', 
                    [
                        'rating', 
                        'comments_count',
                        'editorially_status',
                        'approvededitorially_status', 
                        'premoderation_status'
                    ], 
                    [
                        'id' => $articleId, 
                        'premoderation_status' => [2, 3]
                    ]
                );
                $article['view_id'] = $viewId;
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
    
    public function getArticleByViewIdAdmin($viewId)
    {
        $articleId = $this->database->get(
            'articles', 
            'id', 
            [
                'view_id' => $viewId
            ]
        );

        if(!isset($articleId))
        {
            $comment = $this->database->get(
                'comments', 
                [
                    'id', 
                    'article_id'
                ],
                [
                    'view_id' => $viewId
                ]
            );

            if(isset($comment))
            {
                $articleId = $comment['article_id'];

                $articleVersions = $this->database->select(
                    'articles_versions', 
                    [
                        'title', 
                        'text', 
                        'tags', 
                        'created_date',
                        'version_id',
                        'editorially_status', 
                        'approvededitorially_status', 
                        'premoderation_status'
                    ],
                    [
                        "ORDER" => [
                            "version_id" => "DESC",
                        ],
                        'article_id' => $articleId
                    ]
                );
                if(isset($articleVersions))
                {
                    $article = $this->database->get(
                        'articles', 
                        [
                            'rating', 
                            'comments_count', 
                            'editorially_status',
                            'approvededitorially_status',
                            'premoderation_status',
                            'view_id'
                        ], 
                        [
                            'id' => $articleId
                        ]
                    );

                    $article['scrollToCommentId'] = $comment['id'];
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
            else
            {
                return null;
            }
        }
        else
        {
            $articleVersions = $this->database->select(
                'articles_versions', 
                [
                    'title', 
                    'text', 
                    'tags', 
                    'created_date',
                    'version_id',
                    'editorially_status', 
                    'approvededitorially_status', 
                    'premoderation_status'
                ],
                [
                    "ORDER" => [
                        "version_id" => "DESC",
                    ],
                    'article_id' => $articleId
                ]
            );
            if(isset($articleVersions))
            {
                $article = $this->database->get(
                    'articles', 
                    [
                        'rating', 
                        'comments_count', 
                        'editorially_status', 
                        'approvededitorially_status', 
                        'premoderation_status'
                    ], 
                    [
                        'id' => $articleId
                    ]
                );
                $article['view_id'] = $viewId;
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

    public function viewArticle($articleId)
    {
        $articleVersions = $this->database->select(
            'articles_versions', 
            [
                'title', 
                'text', 
                'tags', 
                'created_date',
                'version_id',
                'editorially_status', 
                'approvededitorially_status', 
                'premoderation_status'
            ],
            [
                "ORDER" => [
                    "version_id" => "DESC",
                ],
                'article_id' => $articleId, 
                'premoderation_status' => 2
            ]
        );
        if(isset($articleVersions))
        {
            $article = $this->database->get(
                'articles', 
                [
                    'rating', 
                    'comments_count', 
                    'editorially_status', 
                    'approvededitorially_status', 
                    'premoderation_status', 
                    'view_id'
                ], 
                [
                    'id' => $articleId, 
                    'premoderation_status' => [2, 3]
                ]
            );
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
                'version_id',
                'editorially_status', 
                'approvededitorially_status', 
                'premoderation_status'
            ], 
            [
                "ORDER" => [
                    "version_id" => "DESC",
                ],
                'article_id' => $articleId
            ]
        );
        if(isset($articleVersions))
        {
            $article = $this->database->get(
                'articles', 
                [
                    'rating', 
                    'comments_count', 
                    'editorially_status', 
                    'approvededitorially_status', 
                    'premoderation_status', 
                    'view_id'
                ], 
                [
                    'id' => $articleId
                ]
            );

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