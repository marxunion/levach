<?php
namespace Api\Models;

use Medoo\Medoo;

use Base\BaseModel;

class ArticlesModel extends BaseModel
{
    // Article search

    // Rate

    public function loadArticlesSearchTitleIdsByRate($count = 4, $lastLoaded = 0, $searchTitle = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "rating" => "DESC",
                ],
                'premoderation_status' => 2,
                'approvededitorially_status[!]' => 3,
                'current_title[~]' => $searchTitle
            ]
        );
    }

    public function loadArticlesSearchTagsIdsByRate($count = 4, $lastLoaded = 0, $searchTags = '')
    {
        $sql = "SELECT article_id FROM statistics WHERE premoderation_status = :premoderation_status AND approvededitorially_status != :approvededitorially_status AND current_tags @> :tags ORDER BY rating DESC LIMIT :count OFFSET :lastLoaded";

        $bindings = [
            ':premoderation_status' => 2,
            ':approvededitorially_status' => 3,

            ':count' => $count,
            ':lastLoaded' => $lastLoaded,
            ':tags' => $searchTags
        ];

        return $this->database->query($sql, $bindings)->fetchAll();
    }

    public function loadArticlesSearchTitleTagsIdsByRate($count = 4, $lastLoaded = 0, $searchTitle = '', $searchTags = '')
    {
        $sql = "SELECT article_id FROM statistics WHERE premoderation_status = :premoderation_status AND approvededitorially_status != :approvededitorially_status AND current_title LIKE :title AND current_tags @> :tags ORDER BY rating DESC LIMIT :count OFFSET :lastLoaded";

        $bindings = [
            ':premoderation_status' => 2,
            ':approvededitorially_status' => 3,

            ':count' => $count,
            ':lastLoaded' => $lastLoaded,
            ':title' => $searchTitle,
            ':tags' => $searchTags
        ];

        return $this->database->query($sql, $bindings)->fetchAll();
    }

    // Created Date

    public function loadArticlesSearchTitleIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTitle = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "created_date" => "DESC",
                ],
                'premoderation_status' => 2,
                'current_title[~]' => $searchTitle,
                'approvededitorially_status[!]' => 3
            ]
        );
    }

    public function loadArticlesSearchTagsIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTags = '')
    {
        $sql = "SELECT article_id FROM statistics WHERE premoderation_status = :premoderation_status AND approvededitorially_status != :approvededitorially_status AND current_tags @> :tags ORDER BY created_date DESC LIMIT :count OFFSET :lastLoaded";

        $bindings = [
            ':premoderation_status' => 2,
            ':approvededitorially_status' => 3,

            ':count' => $count,
            ':lastLoaded' => $lastLoaded,
            ':tags' => $searchTags
        ];

        return $this->database->query($sql, $bindings)->fetchAll();
    }

    public function loadArticlesSearchTitleTagsIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTitle = '', $searchTags = '')
    {
        $sql = "SELECT article_id FROM statistics WHERE premoderation_status = :premoderation_status AND approvededitorially_status != :approvededitorially_status AND current_title LIKE :title AND current_tags @> :tags ORDER BY created_date DESC LIMIT :count OFFSET :lastLoaded";

        $bindings = [
            ':premoderation_status' => 2,
            ':approvededitorially_status' => 3,

            ':count' => $count,
            ':lastLoaded' => $lastLoaded,
            ':title' => $searchTitle,
            ':tags' => $searchTags
        ];

        return $this->database->query($sql, $bindings)->fetchAll();
    }

    // EditoriallyArticles

    // Rate

    public function loadEditoriallyArticlesIdsByRate($count = 4, $lastLoaded = 0)
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "rating" => "DESC",
                ],
                'editorially_status' => 1,
            ]
        );
    }

    public function loadEditoriallyArticlesSearchTitleIdsByRate($count = 4, $lastLoaded = 0, $searchTitle = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "rating" => "DESC",
                ],
                'editorially_status' => 1,
                'current_title[~]' => $searchTitle,
            ]
        );
    }

    public function loadEditoriallyArticlesSearchTagsIdsByRate($count = 4, $lastLoaded = 0, $searchTags = '')
    {
        $sql = "SELECT article_id FROM statistics WHERE editorially_status = :editorially_status AND current_tags @> :tags ORDER BY rating DESC LIMIT :count OFFSET :lastLoaded";

        $bindings = [
            ':editorially_status' => 1,
            ':count' => $count,
            ':lastLoaded' => $lastLoaded,
            ':tags' => $searchTags
        ];

        return $this->database->query($sql, $bindings)->fetchAll();
    }

    public function loadEditoriallyArticlesSearchTitleTagsIdsByRate($count = 4, $lastLoaded = 0, $searchTitle = '', $searchTags = '')
    {
        $sql = "SELECT article_id FROM statistics WHERE editorially_status = :editorially_status AND current_title LIKE :title AND current_tags @> :tags ORDER BY rating DESC LIMIT :count OFFSET :lastLoaded";

        $bindings = [
            ':editorially_status' => 1,
            ':count' => $count,
            ':lastLoaded' => $lastLoaded,
            ':title' => $searchTitle,
            ':tags' => $searchTags
        ];

        return $this->database->query($sql, $bindings)->fetchAll();
    }
    
    // Created date

    public function loadEditoriallyArticlesIdsByCreatedDate($count = 4, $lastLoaded = 0)
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "created_date" => "DESC",
                ],
                'editorially_status' => 1,
            ]
        );
    }

    public function loadEditoriallyArticlesSearchTitleIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTitle = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "created_date" => "DESC",
                ],
                'editorially_status' => 1,
                'current_title[~]' => $searchTitle,
            ]
        );
    }

    public function loadEditoriallyArticlesSearchTagsIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTags = '')
    {
        $sql = "SELECT article_id FROM statistics WHERE editorially_status = :editorially_status AND current_tags @> :tags ORDER BY created_date DESC LIMIT :count OFFSET :lastLoaded";

        $bindings = [
            ':editorially_status' => 1,
            ':count' => $count,
            ':lastLoaded' => $lastLoaded,
            ':tags' => $searchTags
        ];

        return $this->database->query($sql, $bindings)->fetchAll();
    }

    public function loadEditoriallyArticlesSearchTitleTagsIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTitle = '', $searchTags = '')
    {
        $sql = "SELECT article_id FROM statistics WHERE editorially_status = :editorially_status AND current_title LIKE :title AND current_tags @> :tags ORDER BY created_date DESC LIMIT :count OFFSET :lastLoaded";

        $bindings = [
            ':editorially_status' => 1,
            ':count' => $count,
            ':lastLoaded' => $lastLoaded,
            ':title' => $searchTitle,
            ':tags' => $searchTags
        ];

        return $this->database->query($sql, $bindings)->fetchAll();
    }
    
    

    // EditoriallyApprovedArticles

    // Rate

    public function loadEditoriallyApprovedArticlesIdsByRate($count = 4, $lastLoaded = 0)
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "rating" => "DESC",
                ],
                'approvededitorially_status' => 2,
                'editorially_status[!]' => 1,
            ]
        );
    }
    
    public function loadEditoriallyApprovedArticlesSearchTitleIdsByRate($count = 4, $lastLoaded = 0, $searchTitle = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "rating" => "DESC",
                ],
                'approvededitorially_status' => 2,
                'editorially_status[!]' => 1,
                'current_title[~]' => $searchTitle,
            ]
        );
    }

    public function loadEditoriallyApprovedArticlesSearchTagsIdsByRate($count = 4, $lastLoaded = 0, $searchTags = '')
    {
        $sql = "SELECT article_id FROM statistics WHERE approvededitorially_status = :approvededitorially_status AND editorially_status != :editorially_status AND current_tags @> :tags ORDER BY rating DESC LIMIT :count OFFSET :lastLoaded";

        $bindings = [
            ':approvededitorially_status' => 2,
            ':editorially_status' => 1,

            ':count' => $count,
            ':lastLoaded' => $lastLoaded,
            ':tags' => $searchTags
        ];

        return $this->database->query($sql, $bindings)->fetchAll();
    }

    public function loadEditoriallyApprovedArticlesSearchTitleTagsIdsByRate($count = 4, $lastLoaded = 0, $searchTitle = '', $searchTags = '')
    {
        $sql = "SELECT article_id FROM statistics WHERE approvededitorially_status = :approvededitorially_status AND editorially_status != :editorially_status AND current_title LIKE :title AND current_tags @> :tags ORDER BY rating DESC LIMIT :count OFFSET :lastLoaded";

        $bindings = [
            ':approvededitorially_status' => 2,
            ':editorially_status' => 1,

            ':count' => $count,
            ':lastLoaded' => $lastLoaded,
            ':title' => $searchTitle,
            ':tags' => $searchTags 
        ];

        return $this->database->query($sql, $bindings)->fetchAll();
    }

    // Created Date

    public function loadEditoriallyApprovedArticlesIdsByCreatedDate($count = 4, $lastLoaded = 0)
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "created_date" => "DESC",
                ],
                'approvededitorially_status' => 2,
                'editorially_status[!]' => 1,
            ]
        );
    }

    public function loadEditoriallyApprovedArticlesSearchTitleIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTitle = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "created_date" => "DESC",
                ],
                'approvededitorially_status' => 2,
                'editorially_status[!]' => 1,
                'current_title[~]' => $searchTitle,
            ]
        );
    }

    public function loadEditoriallyApprovedArticlesSearchTagsIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTags = '')
    {
        $sql = "SELECT article_id FROM statistics WHERE approvededitorially_status = :approvededitorially_status AND editorially_status != :editorially_status AND current_tags @> :tags ORDER BY created_date DESC LIMIT :count OFFSET :lastLoaded";

        $bindings = [
            ':approvededitorially_status' => 2,
            ':editorially_status' => 1,

            ':count' => $count,
            ':lastLoaded' => $lastLoaded,
            ':tags' => $searchTags
        ];

        return $this->database->query($sql, $bindings)->fetchAll();
    }
    
    public function loadEditoriallyApprovedArticlesSearchTitleTagsIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTitle = '', $searchTags = '')
    {
        $sql = "SELECT article_id FROM statistics WHERE approvededitorially_status = :approvededitorially_status AND editorially_status != :editorially_status AND current_title LIKE :title AND current_tags @> :tags ORDER BY created_date DESC LIMIT :count OFFSET :lastLoaded";

        $bindings = [
            ':approvededitorially_status' => 2,
            ':editorially_status' => 1,

            ':count' => $count,
            ':lastLoaded' => $lastLoaded,
            ':title' => $searchTitle,
            ':tags' => $searchTags 
        ];

        return $this->database->query($sql, $bindings)->fetchAll();
    }

    // AbyssArticles

    // Rate

    public function loadAbyssArticlesIdsByRate($count = 4, $lastLoaded = 0)
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "rating" => "DESC",
                ],
                'premoderation_status' => 2,
                'editorially_status[!]' => 1,
                'approvededitorially_status[!]' => 2,
                'approvededitorially_status[!]' => 3,
            ]
        );
    }

    public function loadAbyssArticlesSearchTitleIdsByRate($count = 4, $lastLoaded = 0, $searchTitle = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "rating" => "DESC",
                ],
                'premoderation_status' => 2,
                'editorially_status[!]' => 1,
                'approvededitorially_status[!]' => 2,
                'approvededitorially_status[!]' => 3,
                'current_title[~]' => $searchTitle,
            ]
        );
    }

    public function loadAbyssArticlesSearchTagsIdsByRate($count = 4, $lastLoaded = 0, $searchTags = '')
    {
        $sql = "SELECT article_id FROM statistics WHERE premoderation_status = :premoderation_status AND editorially_status != :editorially_status AND approvededitorially_status != :approvededitorially_status AND approvededitorially_status != :approvededitorially_status_two AND current_tags @> :tags ORDER BY rate DESC LIMIT :count OFFSET :lastLoaded";

        $bindings = [
            ':premoderation_status' => 2,
            ':editorially_status' => 1,
            ':approvededitorially_status' => 2,
            ':approvededitorially_status_two' => 3,
            
            ':count' => $count,
            ':lastLoaded' => $lastLoaded,
            ':tags' => $searchTags
        ];

        return $this->database->query($sql, $bindings)->fetchAll();
    }

    public function loadAbyssArticlesSearchTitleTagsIdsByRate($count = 4, $lastLoaded = 0, $searchTitle = '', $searchTags = '')
    {
        $sql = "SELECT article_id FROM statistics WHERE premoderation_status = :premoderation_status AND editorially_status != :editorially_status AND approvededitorially_status != :approvededitorially_status AND approvededitorially_status != :approvededitorially_status_two AND current_title LIKE :title AND current_tags @> :tags ORDER BY rate DESC LIMIT :count OFFSET :lastLoaded";

        $bindings = [
            ':premoderation_status' => 2,
            ':editorially_status' => 1,
            ':approvededitorially_status' => 2,
            ':approvededitorially_status_two' => 3,
            
            ':count' => $count,
            ':lastLoaded' => $lastLoaded,
            ':title' => $searchTitle,
            ':tags' => $searchTags
        ];

        return $this->database->query($sql, $bindings)->fetchAll();
    }

    // Created Date

    public function loadAbyssArticlesIdsByCreatedDate($count = 4, $lastLoaded = 0)
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "created_date" => "DESC",
                ],
                'premoderation_status' => 2,
                'editorially_status[!]' => 1,
                'approvededitorially_status[!]' => 2,
                'approvededitorially_status[!]' => 3,
            ]
        );
    }

    public function loadAbyssArticlesSearchTitleIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTitle = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "created_date" => "DESC",
                ],
                'premoderation_status' => 2,
                'editorially_status[!]' => 1,
                'approvededitorially_status[!]' => 2,
                'approvededitorially_status[!]' => 3,
                'current_title[~]' => $searchTitle,
            ]
        );
    }

    public function loadAbyssArticlesSearchTagsIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTags = '')
    {
        $sql = "SELECT article_id FROM statistics WHERE premoderation_status = :premoderation_status AND editorially_status != :editorially_status AND approvededitorially_status != :approvededitorially_status AND approvededitorially_status != :approvededitorially_status_two AND current_tags @> :tags ORDER BY created_date DESC LIMIT :count OFFSET :lastLoaded";

        $bindings = [
            ':premoderation_status' => 2,
            ':editorially_status' => 1,
            ':approvededitorially_status' => 2,
            ':approvededitorially_status_two' => 3,
            
            ':count' => $count,
            ':lastLoaded' => $lastLoaded,
            ':tags' => $searchTags
        ];

        return $this->database->query($sql, $bindings)->fetchAll();
    }

    public function loadAbyssArticlesSearchTitleTagsIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTitle = '', $searchTags = '')
    {
        $sql = "SELECT article_id FROM statistics WHERE premoderation_status = :premoderation_status AND editorially_status != :editorially_status AND approvededitorially_status != :approvededitorially_status AND approvededitorially_status != :approvededitorially_status_two AND current_title LIKE :title AND current_tags @> :tags ORDER BY created_date DESC LIMIT :count OFFSET :lastLoaded";

        $bindings = [
            ':premoderation_status' => 2,
            ':editorially_status' => 1,
            ':approvededitorially_status' => 2,
            ':approvededitorially_status_two' => 3,
            
            ':count' => $count,
            ':lastLoaded' => $lastLoaded,
            ':title' => $searchTitle,
            ':tags' => $searchTags
        ];

        return $this->database->query($sql, $bindings)->fetchAll();
    }

    // ArticlesWaitingApprove

    // Rate

    public function loadArticlesWaitingApproveIdsByRate($count = 4, $lastLoaded = 0)
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "rating" => "DESC",
                ],
                'approvededitorially_status' => 1,
            ]
        );
    }

    public function loadArticlesWaitingApproveSearchTitleIdsByRate($count = 4, $lastLoaded = 0, $searchTitle = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "rating" => "DESC",
                ],
                'approvededitorially_status' => 1,
                'current_title[~]' => $searchTitle,
            ]
        );
    }

    public function loadArticlesWaitingApproveSearchTagsIdsByRate($count = 4, $lastLoaded = 0, $searchTags = '')
    {
        $sql = "SELECT article_id FROM statistics WHERE AND approvededitorially_status = :approvededitorially_status AND current_tags @> :tags ORDER BY rate DESC LIMIT :count OFFSET :lastLoaded";

        $bindings = [
            ':approvededitorially_status' => 1,
            
            ':count' => $count,
            ':lastLoaded' => $lastLoaded,
            ':tags' => $searchTags
        ];

        return $this->database->query($sql, $bindings)->fetchAll();
    }

    public function loadArticlesWaitingApproveSearchTitleTagsIdsByRate($count = 4, $lastLoaded = 0, $searchTitle = '', $searchTags = '')
    {
        $sql = "SELECT article_id FROM statistics WHERE AND approvededitorially_status = :approvededitorially_status AND current_title LIKE :title AND current_tags @> :tags ORDER BY rate DESC LIMIT :count OFFSET :lastLoaded";

        $bindings = [
            ':approvededitorially_status' => 1,
            
            ':count' => $count,
            ':lastLoaded' => $lastLoaded,
            ':title' => $searchTitle,
            ':tags' => $searchTags
        ];

        return $this->database->query($sql, $bindings)->fetchAll();
    }

    // Created Date

    public function loadArticlesWaitingApproveIdsByCreatedDate($count = 4, $lastLoaded = 0)
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "created_date" => "DESC",
                ],
                'approvededitorially_status' => 1,
            ]
        );
    }

    public function loadArticlesWaitingApproveSearchTitleIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTitle = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "created_date" => "DESC",
                ],
                'approvededitorially_status' => 1,
                'current_title[~]' => $searchTitle,
            ]
        );
    }

    public function loadArticlesWaitingApproveSearchTagsIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTags = '')
    {
        $sql = "SELECT article_id FROM statistics WHERE AND approvededitorially_status = :approvededitorially_status AND current_tags @> :tags ORDER BY created_date DESC LIMIT :count OFFSET :lastLoaded";

        $bindings = [
            ':approvededitorially_status' => 1,
            
            ':count' => $count,
            ':lastLoaded' => $lastLoaded,
            ':tags' => $searchTags
        ];

        return $this->database->query($sql, $bindings)->fetchAll();
    }

    public function loadArticlesWaitingApproveSearchTitleTagsIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTitle = '', $searchTags = '')
    {
        $sql = "SELECT article_id FROM statistics WHERE AND approvededitorially_status = :approvededitorially_status AND current_title LIKE :title AND current_tags @> :tags ORDER BY created_date DESC LIMIT :count OFFSET :lastLoaded";

        $bindings = [
            ':approvededitorially_status' => 1,
            
            ':count' => $count,
            ':lastLoaded' => $lastLoaded,
            ':title' => $searchTitle,
            ':tags' => $searchTags
        ];

        return $this->database->query($sql, $bindings)->fetchAll();
    }

    // ArticlesWaitingPremoderate

    // Rate

    public function loadArticlesWaitingPremoderateIdsByRate($count = 4, $lastLoaded = 0)
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "rating" => "DESC",
                ],
                'premoderation_status' => 1,
            ]
        );
    }

    public function loadArticlesWaitingPremoderateSearchTitleIdsByRate($count = 4, $lastLoaded = 0, $searchTitle = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "rating" => "DESC",
                ],
                'premoderation_status' => 1,
                'current_title[~]' => $searchTitle,
            ]
        );
    }

    public function loadArticlesWaitingPremoderateSearchTagsIdsByRate($count = 4, $lastLoaded = 0, $searchTags = '')
    {
        $sql = "SELECT article_id FROM statistics WHERE AND premoderation_status = :premoderation_status AND current_tags @> :tags ORDER BY rate DESC LIMIT :count OFFSET :lastLoaded";

        $bindings = [
            ':premoderation_status' => 1,
            
            ':count' => $count,
            ':lastLoaded' => $lastLoaded,
            ':tags' => $searchTags
        ];

        return $this->database->query($sql, $bindings)->fetchAll();
    }

    public function loadArticlesWaitingPremoderateSearchTitleTagsIdsByRate($count = 4, $lastLoaded = 0, $searchTitle = '', $searchTags = '')
    {
        $sql = "SELECT article_id FROM statistics WHERE AND premoderation_status = :premoderation_status AND current_title LIKE :title AND current_tags @> :tags ORDER BY rate DESC LIMIT :count OFFSET :lastLoaded";

        $bindings = [
            ':premoderation_status' => 1,
            
            ':count' => $count,
            ':lastLoaded' => $lastLoaded,
            ':title' => $searchTitle,
            ':tags' => $searchTags
        ];

        return $this->database->query($sql, $bindings)->fetchAll();
    }

    // Created Date

    public function loadArticlesWaitingPremoderateIdsByCreatedDate($count = 4, $lastLoaded = 0)
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "created_date" => "DESC",
                ],
                'premoderation_status' => 1,
            ]
        );
    }

    public function loadArticlesWaitingPremoderateSearchTitleIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTitle = '')
    {
        return $this->database->select(
            'statistics',
            'article_id',
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "created_date" => "DESC",
                ],
                'premoderation_status' => 1,
                'current_title[~]' => $searchTitle,
            ]
        );
    }

    public function loadArticlesWaitingPremoderateSearchTagsIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTags = '')
    {
        $sql = "SELECT article_id FROM statistics WHERE AND premoderation_status = :premoderation_status AND current_tags @> :tags ORDER BY created_date DESC LIMIT :count OFFSET :lastLoaded";

        $bindings = [
            ':premoderation_status' => 1,
            
            ':count' => $count,
            ':lastLoaded' => $lastLoaded,
            ':tags' => $searchTags
        ];

        return $this->database->query($sql, $bindings)->fetchAll();
    }

    public function loadArticlesWaitingPremoderateSearchTitleTagsIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTitle = '', $searchTags = '')
    {
        $sql = "SELECT article_id FROM statistics WHERE AND premoderation_status = :premoderation_status AND current_title LIKE :title AND current_tags @> :tags ORDER BY created_date DESC LIMIT :count OFFSET :lastLoaded";

        $bindings = [
            ':premoderation_status' => 1,
            
            ':count' => $count,
            ':lastLoaded' => $lastLoaded,
            ':title' => $searchTitle,
            ':tags' => $searchTags
        ];

        return $this->database->query($sql, $bindings)->fetchAll();
    }

    // Load articles from article ids

    // Articles search 

    public function loadArticlesSearch($articleIds)
    {
        if($articleIds)
        {
            if(is_array($articleIds))
            {
                if(count($articleIds) > 0)
                {
                    $articles = [];
                    foreach($articleIds as &$articleId) 
                    {
                        $articleVersions = $this->database->select(
                            'articles', 
                            [
                                'version_id', 
                                'title', 
                                'text', 
                                'tags', 
                                'created_date', 
                                'editorially_status', 
                                'premoderation_status', 
                                'approvededitorially_status'
                            ],
                            [
                                'id' => $articleId, 
                                'premoderation_status' => 2,
                                'approvededitorially_status[!]' => 2,
                                'approvededitorially_status[!]' => 3
                            ]
                        );
                        
                        foreach($articleVersions as $versionNum => $versionInfo) 
                        {
                            if ($versionInfo['tags'] != null) 
                            {
                                $tagsString = substr(substr($versionInfo["tags"], 1), 0, -1);
                                $articleVersions[$versionNum]['tags'] = explode(',', $tagsString);
                            }
                        }
                        
                        $article = [
                            'id' => $articleId,
                            'versions' => $articleVersions,
                            'statistics' => $this->database->get('statistics', ['created_date','rating', 'comments'], ['article_id' => $articleId]),
                            'view_code' => $this->database->get('codes', 'view_code', ['article_id' => $articleId])
                        ];
                        array_push($articles, $article);
                    }
                    return $articles;
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

    // EditoriallyArticles

    public function loadEditoriallyArticles($articleIds)
    {
        if($articleIds)
        {
            if(is_array($articleIds))
            {
                if(count($articleIds) > 0)
                {
                    $articles = [];
                    foreach($articleIds as &$articleId) 
                    {
                        $articleVersions = $this->database->select(
                            'articles', 
                            [
                                'version_id', 
                                'title', 
                                'text',
                                'tags',
                                'created_date',
                                'editorially_status',
                                'premoderation_status',
                                'approvededitorially_status'
                            ], 
                            [
                                'id' => $articleId, 
                                'editorially_status' => 1
                            ]
                        );

                        foreach($articleVersions as $versionNum => $versionInfo) 
                        {
                            if ($versionInfo['tags'] != null) 
                            {
                                $tagsString = substr(substr($versionInfo["tags"], 1), 0, -1);
                                $articleVersions[$versionNum]['tags'] = explode(',', $tagsString);
                            }
                        }
                        
                        $article = [
                            'id' => $articleId,
                            'versions' => $articleVersions,
                            'statistics' => $this->database->get('statistics', ['created_date','rating', 'comments'], ['article_id' => $articleId]),
                            'view_code' => $this->database->get('codes', 'view_code', ['article_id' => $articleId])
                        ];
                        array_push($articles, $article);
                    }
                    return $articles;
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

    // EditoriallyApprovedArticles

    public function loadEditoriallyApprovedArticles($articleIds)
    {
        if($articleIds)
        {
            if(is_array($articleIds))
            {
                if(count($articleIds) > 0)
                {
                    $articles = [];
                    foreach($articleIds as &$articleId) 
                    {
                        $articleVersions = $this->database->select(
                            'articles', 
                            [
                                'version_id', 
                                'title', 
                                'text', 
                                'tags',
                                'created_date',
                                'editorially_status',
                                'premoderation_status',
                                'approvededitorially_status'
                            ],
                            [
                                'id' => $articleId,
                                'approvededitorially_status' => 2,
                                'editorially_status[!]' => 1,
                            ]
                        );

                        foreach($articleVersions as $versionNum => $versionInfo) 
                        {
                            if ($versionInfo['tags'] != null) 
                            {
                                $tagsString = substr(substr($versionInfo["tags"], 1), 0, -1);
                                $articleVersions[$versionNum]['tags'] = explode(',', $tagsString);
                            }
                        }
                        
                        $article = [
                            'id' => $articleId,
                            'versions' => $articleVersions,
                            'statistics' => $this->database->get('statistics', ['created_date','rating', 'comments'], ['article_id' => $articleId]),
                            'view_code' => $this->database->get('codes', 'view_code', ['article_id' => $articleId])
                        ];
                        array_push($articles, $article);
                    }
                    return $articles;
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

    // AbyssArticles

    public function loadAbyssArticles($articleIds)
    {
        if($articleIds)
        {
            if(is_array($articleIds))
            {
                if(count($articleIds) > 0)
                {
                    $articles = [];
                    foreach($articleIds as &$articleId) 
                    {
                        $articleVersions = $this->database->select(
                            'articles', 
                            [
                                'version_id', 
                                'title', 
                                'text', 
                                'tags', 
                                'created_date', 
                                'editorially_status', 
                                'premoderation_status', 
                                'approvededitorially_status'
                            ],
                            [
                                'id' => $articleId, 
                                'premoderation_status' => 2,
                                'editorially_status[!]' => 1,
                                'approvededitorially_status[!]' => 2,
                                'approvededitorially_status[!]' => 3
                            ]
                        );

                        foreach($articleVersions as $versionNum => $versionInfo) 
                        {
                            if ($versionInfo['tags'] != null) 
                            {
                                $tagsString = substr(substr($versionInfo["tags"], 1), 0, -1);
                                $articleVersions[$versionNum]['tags'] = explode(',', $tagsString);
                            }
                        }
                        
                        $article = [
                            'id' => $articleId,
                            'versions' => $articleVersions,
                            'statistics' => $this->database->get('statistics', ['created_date','rating', 'comments'], ['article_id' => $articleId]),
                            'view_code' => $this->database->get('codes', 'view_code', ['article_id' => $articleId])
                        ];
                        array_push($articles, $article);
                    }
                    return $articles;
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

    // ArticlesWaitingApprove

    public function loadArticlesWaitingApprove($articleIds)
    {
        if($articleIds)
        {
            if(is_array($articleIds))
            {
                if(count($articleIds) > 0)
                {
                    $articles = [];
                    foreach($articleIds as &$articleId) 
                    {
                        $articleVersions =  $this->database->select(
                            'articles', 
                            [
                                'version_id', 
                                'title',
                                'text', 
                                'tags', 
                                'created_date', 
                                'editorially_status', 
                                'premoderation_status', 
                                'approvededitorially_status'
                            ], 
                            [
                                'id' => $articleId, 
                                'premoderation_status' => 2, 
                                'approvededitorially_status' => 1
                            ]
                        );
                        
                        foreach($articleVersions as $versionNum => $versionInfo) 
                        {
                            if ($versionInfo['tags'] != null) 
                            {
                                $tagsString = substr(substr($versionInfo["tags"], 1), 0, -1);
                                $articleVersions[$versionNum]['tags'] = explode(',', $tagsString);
                            }
                        }

                        $article = [
                            'id' => $articleId,
                            'versions' => $articleVersions,
                            'statistics' => $this->database->get('statistics', ['created_date','rating', 'comments'], ['article_id' => $articleId]),
                            'view_code' => $this->database->get('codes', 'view_code', ['article_id' => $articleId])
                        ];
                        array_push($articles, $article);
                    }
                    return $articles;
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

    // ArticlesWaitingPremoderate

    public function loadArticlesWaitingPremoderate($articleIds)
    {
        if($articleIds)
        {
            if(is_array($articleIds))
            {
                if(count($articleIds) > 0)
                {
                    $articles = [];
                    foreach($articleIds as &$articleId) 
                    {
                        $articleVersions = $this->database->select(
                            'articles', 
                            [
                                'version_id', 
                                'title',
                                'text', 
                                'tags', 
                                'created_date', 
                                'editorially_status', 
                                'premoderation_status', 
                                'approvededitorially_status'
                            ], 
                            [
                                'id' => $articleId, 
                                'premoderation_status' => 1
                            ]
                        );

                        foreach($articleVersions as $versionNum => $versionInfo) 
                        {
                            if ($versionInfo['tags'] != null) 
                            {
                                $tagsString = substr(substr($versionInfo["tags"], 1), 0, -1);
                                $articleVersions[$versionNum]['tags'] = explode(',', $tagsString);
                            }
                        }
                        
                        $article = [
                            'id' => $articleId,
                            'versions' => $articleVersions,
                            'statistics' => $this->database->get('statistics', ['created_date','rating', 'comments'], ['article_id' => $articleId]),
                            'view_code' => $this->database->get('codes', 'view_code', ['article_id' => $articleId])
                        ];
                        array_push($articles, $article);
                    }
                    return $articles;
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
}