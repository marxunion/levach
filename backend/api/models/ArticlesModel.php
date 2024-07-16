<?php
namespace Api\Models;

use Medoo\Medoo;

use Base\BaseModel;

class ArticlesModel extends BaseModel
{
    // Article search

    // Rate

    public function loadArticlesSearchTitleIdsByPopularity(int $count = 4, int $lastLoaded = 0, string $searchTitle = '')
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
                    "popularity_sort_value" => "DESC",
                ],
                'premoderation_status' => 2,
                'approvededitorially_status[!]' => 3,
                'current_title[~]' => $searchTitle
            ]
        );
    }

    public function loadArticlesSearchTagsIdsByPopularity(int $count = 4, int $lastLoaded = 0, string $searchTags = '')
    {
        $sql = "SELECT id, created_date, rating, comments_count, view_code, view_id FROM articles WHERE premoderation_status = :premoderation_status AND approvededitorially_status != :approvededitorially_status AND current_tags @> :tags ORDER BY popularity_sort_value DESC LIMIT :count OFFSET :lastLoaded";

        $bindings = [
            ':premoderation_status' => 2,
            ':approvededitorially_status' => 3,

            ':count' => $count,
            ':lastLoaded' => $lastLoaded,
            ':tags' => $searchTags
        ];

        return $this->database->query($sql, $bindings)->fetchAll();
    }

    public function loadArticlesSearchTitleTagsIdsByPopularity(int $count = 4, int $lastLoaded = 0, string $searchTitle = '', string $searchTags = '')
    {
        $sql = "SELECT id, created_date, rating, comments_count, view_code, view_id FROM articles WHERE premoderation_status = :premoderation_status AND approvededitorially_status != :approvededitorially_status AND current_title LIKE :title AND current_tags @> :tags ORDER BY popularity_sort_value DESC LIMIT :count OFFSET :lastLoaded";

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

    public function loadArticlesSearchTitleIdsByCreatedDate(int $count = 4, int $lastLoaded = 0, string $searchTitle = '')
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

    public function loadArticlesSearchTagsIdsByCreatedDate(int $count = 4, int $lastLoaded = 0, string $searchTags = '')
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

    public function loadArticlesSearchTitleTagsIdsByCreatedDate(int $count = 4, int $lastLoaded = 0, string $searchTitle = '', string $searchTags = '')
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

    public function loadEditoriallyArticlesIdsByPopularity(int $count = 4, int $lastLoaded = 0)
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
                    "popularity_sort_value" => "DESC",
                ],
                'editorially_status' => 1,
            ]
        );
    }

    public function loadEditoriallyArticlesSearchTitleIdsByPopularity(int $count = 4, int $lastLoaded = 0, string $searchTitle = '')
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
                    "popularity_sort_value" => "DESC",
                ],
                'editorially_status' => 1,
                'current_title[~]' => $searchTitle,
            ]
        );
    }

    public function loadEditoriallyArticlesSearchTagsIdsByPopularity(int $count = 4, int $lastLoaded = 0, string $searchTags = '')
    {
        $sql = "SELECT id, created_date, rating, comments_count, view_code, view_id FROM articles WHERE editorially_status = :editorially_status AND current_tags @> :tags ORDER BY popularity_sort_value DESC LIMIT :count OFFSET :lastLoaded";

        $bindings = [
            ':editorially_status' => 1,
            ':count' => $count,
            ':lastLoaded' => $lastLoaded,
            ':tags' => $searchTags
        ];

        return $this->database->query($sql, $bindings)->fetchAll();
    }

    public function loadEditoriallyArticlesSearchTitleTagsIdsByPopularity(int $count = 4, int $lastLoaded = 0, string $searchTitle = '', string $searchTags = '')
    {
        $sql = "SELECT id, created_date, rating, comments_count, view_code, view_id FROM articles WHERE editorially_status = :editorially_status AND current_title LIKE :title AND current_tags @> :tags ORDER BY popularity_sort_value DESC LIMIT :count OFFSET :lastLoaded";

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

    public function loadEditoriallyArticlesIdsByCreatedDate(int $count = 4, int $lastLoaded = 0)
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

    public function loadEditoriallyArticlesSearchTitleIdsByCreatedDate(int $count = 4, int $lastLoaded = 0, string $searchTitle = '')
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

    public function loadEditoriallyArticlesSearchTagsIdsByCreatedDate(int $count = 4, int $lastLoaded = 0, string $searchTags = '')
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

    public function loadEditoriallyArticlesSearchTitleTagsIdsByCreatedDate(int $count = 4, int $lastLoaded = 0, string $searchTitle = '', string $searchTags = '')
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

    public function loadEditoriallyApprovedArticlesIdsByPopularity(int $count = 4, int $lastLoaded = 0)
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
                    "popularity_sort_value" => "DESC",
                ],
                'approvededitorially_status' => 2,
                'editorially_status[!]' => 1,
            ]
        );
    }
    
    public function loadEditoriallyApprovedArticlesSearchTitleIdsByPopularity(int $count = 4, int $lastLoaded = 0, string $searchTitle = '')
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
                    "popularity_sort_value" => "DESC",
                ],
                'approvededitorially_status' => 2,
                'editorially_status[!]' => 1,
                'current_title[~]' => $searchTitle,
            ]
        );
    }

    public function loadEditoriallyApprovedArticlesSearchTagsIdsByPopularity(int $count = 4, int $lastLoaded = 0, string $searchTags = '')
    {
        $sql = "SELECT id, created_date, rating, comments_count, view_code, view_id FROM articles WHERE approvededitorially_status = :approvededitorially_status AND editorially_status != :editorially_status AND current_tags @> :tags ORDER BY popularity_sort_value DESC LIMIT :count OFFSET :lastLoaded";

        $bindings = [
            ':approvededitorially_status' => 2,
            ':editorially_status' => 1,

            ':count' => $count,
            ':lastLoaded' => $lastLoaded,
            ':tags' => $searchTags
        ];

        return $this->database->query($sql, $bindings)->fetchAll();
    }

    public function loadEditoriallyApprovedArticlesSearchTitleTagsIdsByPopularity(int $count = 4, int $lastLoaded = 0, string $searchTitle = '', string $searchTags = '')
    {
        $sql = "SELECT id, created_date, rating, comments_count, view_code, view_id FROM articles WHERE approvededitorially_status = :approvededitorially_status AND editorially_status != :editorially_status AND current_title LIKE :title AND current_tags @> :tags ORDER BY popularity_sort_value DESC LIMIT :count OFFSET :lastLoaded";

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

    public function loadEditoriallyApprovedArticlesIdsByCreatedDate(int $count = 4, int $lastLoaded = 0)
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

    public function loadEditoriallyApprovedArticlesSearchTitleIdsByCreatedDate(int $count = 4, int $lastLoaded = 0, string $searchTitle = '')
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

    public function loadEditoriallyApprovedArticlesSearchTagsIdsByCreatedDate(int $count = 4, int $lastLoaded = 0, string $searchTags = '')
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
    
    public function loadEditoriallyApprovedArticlesSearchTitleTagsIdsByCreatedDate(int $count = 4, int $lastLoaded = 0, string $searchTitle = '', string $searchTags = '')
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

    public function loadAbyssArticlesIdsByPopularity(int $count = 4, int $lastLoaded = 0)
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
                    "popularity_sort_value" => "DESC",
                ],
                'premoderation_status' => [2, 3],
                'editorially_status[!]' => 1,
                'approvededitorially_status[!]' => [2, 3],
            ]
        );
    }

    public function loadAbyssArticlesSearchTitleIdsByPopularity(int $count = 4,int $lastLoaded = 0, string $searchTitle = '')
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
                    "popularity_sort_value" => "DESC",
                ],
                'premoderation_status' => [2, 3],
                'editorially_status[!]' => 1,
                'approvededitorially_status[!]' => [2, 3],
                'current_title[~]' => $searchTitle,
            ]
        );
    }

    public function loadAbyssArticlesSearchTagsIdsByPopularity(int $count = 4, int $lastLoaded = 0, string $searchTags = '')
    {
        $sql = "SELECT id, created_date, rating, comments_count, view_code, view_id FROM articles WHERE (premoderation_status = :premoderation_status OR premoderation_status = :premoderation_status_two) AND editorially_status != :editorially_status AND approvededitorially_status != :approvededitorially_status AND approvededitorially_status != :approvededitorially_status_two AND current_tags @> :tags ORDER BY popularity_sort_value DESC LIMIT :count OFFSET :lastLoaded";

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

    public function loadAbyssArticlesSearchTitleTagsIdsByPopularity(int $count = 4, int $lastLoaded = 0, string $searchTitle = '', string $searchTags = '')
    {
        $sql = "SELECT id, created_date, rating, comments_count, view_code, view_id FROM articles WHERE (premoderation_status = :premoderation_status OR premoderation_status = :premoderation_status_two) AND editorially_status != :editorially_status AND approvededitorially_status != :approvededitorially_status AND approvededitorially_status != :approvededitorially_status_two AND current_title LIKE :title AND current_tags @> :tags ORDER BY popularity_sort_value DESC LIMIT :count OFFSET :lastLoaded";

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

    public function loadAbyssArticlesIdsByCreatedDate(int $count = 4, int $lastLoaded = 0)
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

    public function loadAbyssArticlesSearchTitleIdsByCreatedDate(int $count = 4, int $lastLoaded = 0, string $searchTitle = '')
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

    public function loadAbyssArticlesSearchTagsIdsByCreatedDate(int $count = 4, int $lastLoaded = 0, string $searchTags = '')
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

    public function loadAbyssArticlesSearchTitleTagsIdsByCreatedDate(int $count = 4, int $lastLoaded = 0, string $searchTitle = '', string $searchTags = '')
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

    public function loadArticlesWaitingApproveIdsByPopularity(int $count = 4, int $lastLoaded = 0)
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
                    "popularity_sort_value" => "DESC",
                ],
                'approvededitorially_status' => 1,
            ]
        );
    }

    public function loadArticlesWaitingApproveSearchTitleIdsByPopularity(int $count = 4, int $lastLoaded = 0, string $searchTitle = '')
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
                    "popularity_sort_value" => "DESC",
                ],
                'approvededitorially_status' => 1,
                'current_title[~]' => $searchTitle,
            ]
        );
    }

    public function loadArticlesWaitingApproveSearchTagsIdsByPopularity(int $count = 4, int $lastLoaded = 0, string $searchTags = '')
    {
        $sql = "SELECT id, created_date, rating, comments_count, view_code, view_id FROM articles WHERE approvededitorially_status = :approvededitorially_status AND current_tags @> :tags ORDER BY popularity_sort_value DESC LIMIT :count OFFSET :lastLoaded";

        $bindings = [
            ':approvededitorially_status' => 1,
            
            ':count' => $count,
            ':lastLoaded' => $lastLoaded,
            ':tags' => $searchTags
        ];

        return $this->database->query($sql, $bindings)->fetchAll();
    }

    public function loadArticlesWaitingApproveSearchTitleTagsIdsByPopularity(int $count = 4, int $lastLoaded = 0, string $searchTitle = '', string $searchTags = '')
    {
        $sql = "SELECT id, created_date, rating, comments_count, view_code, view_id FROM articles WHERE approvededitorially_status = :approvededitorially_status AND current_title LIKE :title AND current_tags @> :tags ORDER BY popularity_sort_value DESC LIMIT :count OFFSET :lastLoaded";

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

    public function loadArticlesWaitingApproveIdsByCreatedDate(int $count = 4, int $lastLoaded = 0)
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

    public function loadArticlesWaitingApproveSearchTitleIdsByCreatedDate(int $count = 4, int $lastLoaded = 0, string $searchTitle = '')
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

    public function loadArticlesWaitingApproveSearchTagsIdsByCreatedDate(int $count = 4, int $lastLoaded = 0, string $searchTags = '')
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

    public function loadArticlesWaitingApproveSearchTitleTagsIdsByCreatedDate(int $count = 4, int $lastLoaded = 0, string $searchTitle = '', string $searchTags = '')
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

    public function loadArticlesWaitingPremoderateIdsByPopularity(int $count = 4, int $lastLoaded = 0)
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
                    "popularity_sort_value" => "DESC",
                ],
                'premoderation_status' => [1, 3]
            ]
        );
    }

    public function loadArticlesWaitingPremoderateSearchTitleIdsByPopularity(int $count = 4, int $lastLoaded = 0, string $searchTitle = '')
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
                    "popularity_sort_value" => "DESC",
                ],
                'premoderation_status' => [1, 3],
                'current_title[~]' => $searchTitle
            ]
        );
    }

    public function loadArticlesWaitingPremoderateSearchTagsIdsByPopularity(int $count = 4, int $lastLoaded = 0, string $searchTags = '')
    {
        $sql = "SELECT id, created_date, rating, comments_count, view_code, view_id FROM articles WHERE (premoderation_status = :premoderation_status OR premoderation_status = :premoderation_status_two) AND current_tags @> :tags ORDER BY popularity_sort_value DESC LIMIT :count OFFSET :lastLoaded";

        $bindings = [
            ':premoderation_status' => 1,
            ':premoderation_status_two' => 3,
            
            ':count' => $count,
            ':lastLoaded' => $lastLoaded,
            ':tags' => $searchTags
        ];

        return $this->database->query($sql, $bindings)->fetchAll();
    }

    public function loadArticlesWaitingPremoderateSearchTitleTagsIdsByPopularity(int $count = 4, int $lastLoaded = 0, string $searchTitle = '', string $searchTags = '')
    {
        $sql = "SELECT id, created_date, rating, comments_count, view_code, view_id FROM articles WHERE (premoderation_status = :premoderation_status OR premoderation_status = :premoderation_status_two) AND current_title LIKE :title AND current_tags @> :tags ORDER BY popularity_sort_value DESC LIMIT :count OFFSET :lastLoaded";

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

    public function loadArticlesWaitingPremoderateIdsByCreatedDate(int $count = 4, int $lastLoaded = 0)
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

    public function loadArticlesWaitingPremoderateSearchTitleIdsByCreatedDate(int $count = 4, int $lastLoaded = 0, string $searchTitle = '')
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

    public function loadArticlesWaitingPremoderateSearchTagsIdsByCreatedDate(int $count = 4, int $lastLoaded = 0, string $searchTags = '')
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

    public function loadArticlesWaitingPremoderateSearchTitleTagsIdsByCreatedDate(int $count = 4, int $lastLoaded = 0, string $searchTitle = '', string $searchTags = '')
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