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
            'articles',
            [
                'id',
                'created_date',
                'rating',
                'comments_count',
                'view_code',
                'view_id'
            ],
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
        $sql = "SELECT id, created_date, rating, comments_count, view_code, view_id FROM articles WHERE premoderation_status = :premoderation_status AND approvededitorially_status != :approvededitorially_status AND current_tags @> :tags ORDER BY rating DESC LIMIT :count OFFSET :lastLoaded";

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
        $sql = "SELECT id, created_date, rating, comments_count, view_code, view_id FROM articles WHERE premoderation_status = :premoderation_status AND approvededitorially_status != :approvededitorially_status AND current_title LIKE :title AND current_tags @> :tags ORDER BY rating DESC LIMIT :count OFFSET :lastLoaded";

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
            'articles',
            [
                'id',
                'created_date',
                'rating',
                'comments_count',
                'view_code',
                'view_id'
            ],
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
        $sql = "SELECT id, created_date, rating, comments_count, view_code, view_id FROM articles WHERE premoderation_status = :premoderation_status AND approvededitorially_status != :approvededitorially_status AND current_tags @> :tags ORDER BY created_date DESC LIMIT :count OFFSET :lastLoaded";

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
        $sql = "SELECT id, created_date, rating, comments_count, view_code, view_id FROM articles WHERE premoderation_status = :premoderation_status AND approvededitorially_status != :approvededitorially_status AND current_title LIKE :title AND current_tags @> :tags ORDER BY created_date DESC LIMIT :count OFFSET :lastLoaded";

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
            'articles',
            [
                'id',
                'created_date',
                'rating',
                'comments_count',
                'view_code',
                'view_id'
            ],
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
            'articles',
            [
                'id',
                'created_date',
                'rating',
                'comments_count',
                'view_code',
                'view_id'
            ],
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
        $sql = "SELECT id, created_date, rating, comments_count, view_code, view_id FROM articles WHERE editorially_status = :editorially_status AND current_tags @> :tags ORDER BY rating DESC LIMIT :count OFFSET :lastLoaded";

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
        $sql = "SELECT id, created_date, rating, comments_count, view_code, view_id FROM articles WHERE editorially_status = :editorially_status AND current_title LIKE :title AND current_tags @> :tags ORDER BY rating DESC LIMIT :count OFFSET :lastLoaded";

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
            'articles',
            [
                'id',
                'created_date',
                'rating',
                'comments_count',
                'view_code',
                'view_id'
            ],
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
            'articles',
            [
                'id',
                'created_date',
                'rating',
                'comments_count',
                'view_code',
                'view_id'
            ],
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
        $sql = "SELECT id, created_date, rating, comments_count, view_code, view_id FROM articles WHERE editorially_status = :editorially_status AND current_tags @> :tags ORDER BY created_date DESC LIMIT :count OFFSET :lastLoaded";

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
        $sql = "SELECT id, created_date, rating, comments_count, view_code, view_id FROM articles WHERE editorially_status = :editorially_status AND current_title LIKE :title AND current_tags @> :tags ORDER BY created_date DESC LIMIT :count OFFSET :lastLoaded";

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
            'articles',
            [
                'id',
                'created_date',
                'rating',
                'comments_count',
                'view_code',
                'view_id'
            ],
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
            'articles',
            [
                'id',
                'created_date',
                'rating',
                'comments_count',
                'view_code',
                'view_id'
            ],
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
        $sql = "SELECT id, created_date, rating, comments_count, view_code, view_id FROM articles WHERE approvededitorially_status = :approvededitorially_status AND editorially_status != :editorially_status AND current_tags @> :tags ORDER BY rating DESC LIMIT :count OFFSET :lastLoaded";

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
        $sql = "SELECT id, created_date, rating, comments_count, view_code, view_id FROM articles WHERE approvededitorially_status = :approvededitorially_status AND editorially_status != :editorially_status AND current_title LIKE :title AND current_tags @> :tags ORDER BY rating DESC LIMIT :count OFFSET :lastLoaded";

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
            'articles',
            [
                'id',
                'created_date',
                'rating',
                'comments_count',
                'view_code',
                'view_id'
            ],
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
            'articles',
            [
                'id',
                'created_date',
                'rating',
                'comments_count',
                'view_code',
                'view_id'
            ],
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
        $sql = "SELECT id, created_date, rating, comments_count, view_code, view_id FROM articles WHERE approvededitorially_status = :approvededitorially_status AND editorially_status != :editorially_status AND current_tags @> :tags ORDER BY created_date DESC LIMIT :count OFFSET :lastLoaded";

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
        $sql = "SELECT id, created_date, rating, comments_count, view_code, view_id FROM articles WHERE approvededitorially_status = :approvededitorially_status AND editorially_status != :editorially_status AND current_title LIKE :title AND current_tags @> :tags ORDER BY created_date DESC LIMIT :count OFFSET :lastLoaded";

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
            'articles',
            [
                'id',
                'created_date',
                'rating',
                'comments_count',
                'view_code',
                'view_id'
            ],
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "rating" => "DESC",
                ],
                'premoderation_status' => [2, 3],
                'editorially_status[!]' => 1,
                'approvededitorially_status[!]' => [2, 3],
            ]
        );
    }

    public function loadAbyssArticlesSearchTitleIdsByRate($count = 4, $lastLoaded = 0, $searchTitle = '')
    {
        return $this->database->select(
            'articles',
            [
                'id',
                'created_date',
                'rating',
                'comments_count',
                'view_code',
                'view_id'
            ],
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "rating" => "DESC",
                ],
                'premoderation_status' => [2, 3],
                'editorially_status[!]' => 1,
                'approvededitorially_status[!]' => [2, 3],
                'current_title[~]' => $searchTitle,
            ]
        );
    }

    public function loadAbyssArticlesSearchTagsIdsByRate($count = 4, $lastLoaded = 0, $searchTags = '')
    {
        $sql = "SELECT id, created_date, rating, comments_count, view_code, view_id FROM articles WHERE (premoderation_status = :premoderation_status OR premoderation_status = :premoderation_status_two) AND editorially_status != :editorially_status AND approvededitorially_status != :approvededitorially_status AND approvededitorially_status != :approvededitorially_status_two AND current_tags @> :tags ORDER BY rating DESC LIMIT :count OFFSET :lastLoaded";

        $bindings = [
            ':premoderation_status' => 2,
            ':premoderation_status_two' => 3,
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
        $sql = "SELECT id, created_date, rating, comments_count, view_code, view_id FROM articles WHERE (premoderation_status = :premoderation_status OR premoderation_status = :premoderation_status_two) AND editorially_status != :editorially_status AND approvededitorially_status != :approvededitorially_status AND approvededitorially_status != :approvededitorially_status_two AND current_title LIKE :title AND current_tags @> :tags ORDER BY rating DESC LIMIT :count OFFSET :lastLoaded";

        $bindings = [
            ':premoderation_status' => 2,
            ':premoderation_status_two' => 3,
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
            'articles',
            [
                'id',
                'created_date',
                'rating',
                'comments_count',
                'view_code',
                'view_id'
            ],
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "created_date" => "DESC",
                ],
                'premoderation_status' => [2, 3],
                'editorially_status[!]' => 1,
                'approvededitorially_status[!]' => [2, 3],
            ]
        );
    }

    public function loadAbyssArticlesSearchTitleIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTitle = '')
    {
        return $this->database->select(
            'articles',
            [
                'id',
                'created_date',
                'rating',
                'comments_count',
                'view_code',
                'view_id'
            ],
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "created_date" => "DESC",
                ],
                'premoderation_status' => [2, 3],
                'editorially_status[!]' => 1,
                'approvededitorially_status[!]' => [2, 3],
                'current_title[~]' => $searchTitle,
            ]
        );
    }

    public function loadAbyssArticlesSearchTagsIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTags = '')
    {
        $sql = "SELECT id, created_date, rating, comments_count, view_code, view_id FROM articles WHERE (premoderation_status = :premoderation_status OR premoderation_status = :premoderation_status_two) AND editorially_status != :editorially_status AND approvededitorially_status != :approvededitorially_status AND approvededitorially_status != :approvededitorially_status_two AND current_tags @> :tags ORDER BY created_date DESC LIMIT :count OFFSET :lastLoaded";

        $bindings = [
            ':premoderation_status' => 2,
            ':premoderation_status_two' => 3,
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
        $sql = "SELECT id, created_date, rating, comments_count, view_code, view_id FROM articles WHERE (premoderation_status = :premoderation_status OR premoderation_status = :premoderation_status_two) AND editorially_status != :editorially_status AND approvededitorially_status != :approvededitorially_status AND approvededitorially_status != :approvededitorially_status_two AND current_title LIKE :title AND current_tags @> :tags ORDER BY created_date DESC LIMIT :count OFFSET :lastLoaded";

        $bindings = [
            ':premoderation_status' => 2,
            ':premoderation_status_two' => 3,
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
            'articles',
            [
                'id',
                'created_date',
                'rating',
                'comments_count',
                'view_code',
                'view_id'
            ],
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
            'articles',
            [
                'id',
                'created_date',
                'rating',
                'comments_count',
                'view_code',
                'view_id'
            ],
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
        $sql = "SELECT id, created_date, rating, comments_count, view_code, view_id FROM articles WHERE approvededitorially_status = :approvededitorially_status AND current_tags @> :tags ORDER BY rating DESC LIMIT :count OFFSET :lastLoaded";

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
        $sql = "SELECT id, created_date, rating, comments_count, view_code, view_id FROM articles WHERE approvededitorially_status = :approvededitorially_status AND current_title LIKE :title AND current_tags @> :tags ORDER BY rating DESC LIMIT :count OFFSET :lastLoaded";

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
            'articles',
            [
                'id',
                'created_date',
                'rating',
                'comments_count',
                'view_code',
                'view_id'
            ],
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
            'articles',
            [
                'id',
                'created_date',
                'rating',
                'comments_count',
                'view_code',
                'view_id'
            ],
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
        $sql = "SELECT id, created_date, rating, comments_count, view_code, view_id FROM articles WHERE approvededitorially_status = :approvededitorially_status AND current_tags @> :tags ORDER BY created_date DESC LIMIT :count OFFSET :lastLoaded";

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
        $sql = "SELECT id, created_date, rating, comments_count, view_code, view_id FROM articles WHERE approvededitorially_status = :approvededitorially_status AND current_title LIKE :title AND current_tags @> :tags ORDER BY created_date DESC LIMIT :count OFFSET :lastLoaded";

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
            'articles',
            [
                'id',
                'created_date',
                'rating',
                'comments_count',
                'view_code',
                'view_id'
            ],
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "rating" => "DESC",
                ],
                'premoderation_status' => [1, 3]
            ]
        );
    }

    public function loadArticlesWaitingPremoderateSearchTitleIdsByRate($count = 4, $lastLoaded = 0, $searchTitle = '')
    {
        return $this->database->select(
            'articles',
            [
                'id',
                'created_date',
                'rating',
                'comments_count',
                'view_code',
                'view_id'
            ],
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "rating" => "DESC",
                ],
                'premoderation_status' => [1, 3],
                'current_title[~]' => $searchTitle
            ]
        );
    }

    public function loadArticlesWaitingPremoderateSearchTagsIdsByRate($count = 4, $lastLoaded = 0, $searchTags = '')
    {
        $sql = "SELECT id, created_date, rating, comments_count, view_code, view_id FROM articles WHERE (premoderation_status = :premoderation_status OR premoderation_status = :premoderation_status_two) AND current_tags @> :tags ORDER BY rating DESC LIMIT :count OFFSET :lastLoaded";

        $bindings = [
            ':premoderation_status' => 1,
            ':premoderation_status_two' => 3,
            
            ':count' => $count,
            ':lastLoaded' => $lastLoaded,
            ':tags' => $searchTags
        ];

        return $this->database->query($sql, $bindings)->fetchAll();
    }

    public function loadArticlesWaitingPremoderateSearchTitleTagsIdsByRate($count = 4, $lastLoaded = 0, $searchTitle = '', $searchTags = '')
    {
        $sql = "SELECT id, created_date, rating, comments_count, view_code, view_id FROM articles WHERE (premoderation_status = :premoderation_status OR premoderation_status = :premoderation_status_two) AND current_title LIKE :title AND current_tags @> :tags ORDER BY rating DESC LIMIT :count OFFSET :lastLoaded";

        $bindings = [
            ':premoderation_status' => 1,
            ':premoderation_status_two' => 3,
            
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
            'articles',
            [
                'id',
                'created_date',
                'rating',
                'comments_count',
                'view_code',
                'view_id'
            ],
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "created_date" => "DESC",
                ],
                'premoderation_status' => [1, 3]
            ]
        );
    }

    public function loadArticlesWaitingPremoderateSearchTitleIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTitle = '')
    {
        return $this->database->select(
            'articles',
            [
                'id',
                'created_date',
                'rating',
                'comments_count',
                'view_code',
                'view_id'
            ],
            [
                'LIMIT' => [$lastLoaded, $lastLoaded + $count],
                "ORDER" => [
                    "created_date" => "DESC",
                ],
                'premoderation_status' => [1, 3],
                'current_title[~]' => $searchTitle
            ]
        );
    }

    public function loadArticlesWaitingPremoderateSearchTagsIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTags = '')
    {
        $sql = "SELECT id, created_date, rating, comments_count, view_code, view_id FROM articles WHERE (premoderation_status = :premoderation_status OR premoderation_status = :premoderation_status_two) AND current_tags @> :tags ORDER BY created_date DESC LIMIT :count OFFSET :lastLoaded";

        $bindings = [
            ':premoderation_status' => 1,
            ':premoderation_status_two' => 3,
            
            ':count' => $count,
            ':lastLoaded' => $lastLoaded,
            ':tags' => $searchTags
        ];

        return $this->database->query($sql, $bindings)->fetchAll();
    }

    public function loadArticlesWaitingPremoderateSearchTitleTagsIdsByCreatedDate($count = 4, $lastLoaded = 0, $searchTitle = '', $searchTags = '')
    {
        $sql = "SELECT id, created_date, rating, comments_count, view_code, view_id FROM articles WHERE (premoderation_status = :premoderation_status OR premoderation_status = :premoderation_status_two) AND current_title LIKE :title AND current_tags @> :tags ORDER BY created_date DESC LIMIT :count OFFSET :lastLoaded";

        $bindings = [
            ':premoderation_status' => 1,
            ':premoderation_status_two' => 3,
            
            ':count' => $count,
            ':lastLoaded' => $lastLoaded,
            ':title' => $searchTitle,
            ':tags' => $searchTags
        ];

        return $this->database->query($sql, $bindings)->fetchAll();
    }

    // Load articles from article ids

    // Articles search 

    public function loadArticlesSearch($articles)
    {
        if($articles)
        {
            if(is_array($articles))
            {
                if(count($articles) > 0)
                {
                    foreach($articles as $key => $article) 
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
                                'premoderation_status', 
                                'approvededitorially_status'
                            ],
                            [
                                "ORDER" => [
                                    "version_id" => "DESC",
                                ],
                                'article_id' => $article['id'], 
                                'premoderation_status' => 2,
                                'approvededitorially_status[!]' => 2,
                                'approvededitorially_status[!]' => 3
                            ]
                        );
                        
                        foreach($articleVersions as $versionNum => $versionInfo) 
                        {
                            if($versionInfo['tags'] != null) 
                            {
                                $tagsString = substr(substr($versionInfo["tags"], 1), 0, -1);
                                $articleVersions[$versionNum]['tags'] = explode(',', $tagsString);
                            }
                        }
                        
                        $articles[$key]['versions'] = $articleVersions;
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

    public function loadEditoriallyArticles($articles)
    {
        if($articles)
        {
            if(is_array($articles))
            {
                if(count($articles) > 0)
                {
                    foreach($articles as $key => $article) 
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
                                'premoderation_status',
                                'approvededitorially_status'
                            ], 
                            [
                                "ORDER" => [
                                    "version_id" => "DESC",
                                ],
                                'article_id' => $article['id'], 
                                'editorially_status' => 1
                            ]
                        );

                        foreach($articleVersions as $versionNum => $versionInfo) 
                        {
                            if($versionInfo['tags'] != null) 
                            {
                                $tagsString = substr(substr($versionInfo["tags"], 1), 0, -1);
                                $articleVersions[$versionNum]['tags'] = explode(',', $tagsString);
                            }
                        }
                        
                        $articles[$key]['versions'] = $articleVersions;
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

    public function loadEditoriallyApprovedArticles($articles)
    {
        if($articles)
        {
            if(is_array($articles))
            {
                if(count($articles) > 0)
                {
                    foreach($articles as $key => $article) 
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
                                'premoderation_status',
                                'approvededitorially_status'
                            ],
                            [
                                "ORDER" => [
                                    "version_id" => "DESC",
                                ],
                                'article_id' => $article['id'],
                                'approvededitorially_status' => 2,
                                'editorially_status[!]' => 1,
                            ]
                        );

                        foreach($articleVersions as $versionNum => $versionInfo) 
                        {
                            if($versionInfo['tags'] != null) 
                            {
                                $tagsString = substr(substr($versionInfo["tags"], 1), 0, -1);
                                $articleVersions[$versionNum]['tags'] = explode(',', $tagsString);
                            }
                        }
                        
                        $articles[$key]['versions'] = $articleVersions;
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

    public function loadAbyssArticles($articles)
    {
        if($articles)
        {
            if(is_array($articles))
            {
                if(count($articles) > 0)
                {
                    foreach($articles as $key => $article) 
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
                                'premoderation_status', 
                                'approvededitorially_status'
                            ],
                            [
                                "ORDER" => [
                                    "version_id" => "DESC",
                                ],
                                'article_id' => $article['id'], 
                                'premoderation_status' => 2,
                                'editorially_status[!]' => 1,
                                'approvededitorially_status[!]' => 2,
                                'approvededitorially_status[!]' => 3
                            ]
                        );

                        foreach($articleVersions as $versionNum => $versionInfo) 
                        {
                            if($versionInfo['tags'] != null) 
                            {
                                $tagsString = substr(substr($versionInfo["tags"], 1), 0, -1);
                                $articleVersions[$versionNum]['tags'] = explode(',', $tagsString);
                            }
                        }
                        
                        $articles[$key]['versions'] = $articleVersions;
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

    public function loadArticlesWaitingApprove($articles)
    {
        if($articles)
        {
            if(is_array($articles))
            {
                if(count($articles) > 0)
                {
                    foreach($articles as $key => $article) 
                    {
                        $articleVersions =  $this->database->select(
                            'articles_versions', 
                            [
                                'title',
                                'text', 
                                'tags', 
                                'created_date', 
                                'version_id', 
                                'editorially_status', 
                                'premoderation_status', 
                                'approvededitorially_status'
                            ], 
                            [
                                "ORDER" => [
                                    "version_id" => "DESC",
                                ],
                                'article_id' => $article['id'], 
                                'premoderation_status' => 2, 
                                'approvededitorially_status' => 1
                            ]
                        );
                        
                        foreach($articleVersions as $versionNum => $versionInfo) 
                        {
                            if($versionInfo['tags'] != null) 
                            {
                                $tagsString = substr(substr($versionInfo["tags"], 1), 0, -1);
                                $articleVersions[$versionNum]['tags'] = explode(',', $tagsString);
                            }
                        }

                        $articles[$key]['versions'] = $articleVersions;
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

    public function loadArticlesWaitingPremoderate($articles)
    {
        if($articles)
        {
            if(is_array($articles))
            {
                if(count($articles) > 0)
                {
                    foreach($articles as $key => $article) 
                    {
                        $articleVersions = $this->database->select(
                            'articles_versions', 
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
                                "ORDER" => [
                                    "version_id" => "DESC",
                                ],
                                'article_id' => $article['id'], 
                                'premoderation_status' => 1
                            ]
                        );

                        foreach($articleVersions as $versionNum => $versionInfo) 
                        {
                            if($versionInfo['tags'] != null) 
                            {
                                $tagsString = substr(substr($versionInfo["tags"], 1), 0, -1);
                                $articleVersions[$versionNum]['tags'] = explode(',', $tagsString);
                            }
                        }
                        
                        $articles[$key]['versions'] = $articleVersions;
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