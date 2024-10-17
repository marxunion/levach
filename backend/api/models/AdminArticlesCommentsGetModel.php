<?php
namespace Api\Models;

use Base\BaseModel;

class AdminArticlesCommentsGetModel extends BaseModel
{
    private $articleId;
    private $commentDateBefore;
    private $commentDateAfter;
    private $commentRegexPattern;
    
    public function checkParents($comment)
    {
        if($comment['parent_comment_id'] != null)
        {
            $parentComment = $this->database->get(
                'comments', 
                [
                    'id',
                    'text',
                    'rating',
                    'created_date',
                    'rating_influence',
                    'parent_comment_id',
                    'view_id'
                ],
                [
                    'article_id' => $this->articleId,
                    'id' => $comment['parent_comment_id']
                ]
            );
            if($parentComment)
            {
                if($parentComment['created_date'] > $this->commentDateBefore && $parentComment['created_date'] < $this->commentDateAfter && preg_match('/'.$this->commentRegexPattern.'/', $parentComment['text'] ))
                {
                    return false;
                }
                else
                {
                    return $this->checkParents($parentComment);
                }
            }
            else
            {
                return true;
            }
        }
        else
        {
            return true;
        }
    }

    public function getSubcomments($parentCommentId)
    {
        $comments = $this->database->select(
            'comments', 
            [
                'id',
                'text', 
                'rating',
                'created_date',
                'rating_influence',
                'parent_comment_id',
                'view_id'
            ], 
            [
                "ORDER" => [
                    "created_date" => "DESC",
                ],
                'article_id' => $this->articleId,
                'parent_comment_id' => $parentCommentId
            ]
        );

        if(!empty($comments)) 
        {
            foreach($comments as &$comment) 
            {
                $subcomments = $this->getSubcomments($comment['id']);
                if(!empty($subcomments)) 
                {
                    $comment['subcomments'] = $subcomments;
                }
            }
        }
        return $comments;
    }

    public function getComments($articlesCount, $commentsCount, $articleDateBefore, $articleDateAfter, $commentDateBefore, $commentDateAfter, $articleRegexPattern, $commentRegexPattern)
    {
        $this->commentDateBefore = $commentDateBefore;
        $this->commentDateAfter = $commentDateAfter;
        $this->commentRegexPattern = $commentRegexPattern;

        $sql = "SELECT id 
                FROM articles 
                WHERE created_date BETWEEN :article_date_before AND :article_date_after 
                AND current_text ~* :article_regex_pattern 
                AND comments_count > 0
                ORDER BY created_date DESC
                LIMIT :count";

        $bindings = [
            ':article_date_before' => $articleDateBefore,
            ':article_date_after' => $articleDateAfter,
            ':article_regex_pattern' => $articleRegexPattern,
            ':count' => $articlesCount
        ];

        $articles = $this->database->query($sql, $bindings)->fetchAll();

        $articleIds = array_column($articles, 'id');

        if(!empty($articleIds)) 
        {
            $placeholders = implode(',', array_fill(0, count($articleIds), '?'));

            $sql = "SELECT id, text, rating, created_date, rating_influence, parent_comment_id, view_id, article_id 
                    FROM comments 
                    WHERE article_id IN ($placeholders)
                    AND created_date BETWEEN ? AND ?
                    AND text ~* ?
                    ORDER BY created_date DESC
                    LIMIT ?";

            $bindings = array_merge($articleIds, [
                $this->commentDateBefore,
                $this->commentDateAfter,
                $this->commentRegexPattern,
                $commentsCount
            ]);

            $stmt = $this->database->pdo->prepare($sql);
            $stmt->execute($bindings);
            $comments = $stmt->fetchAll();

            $commentsToReturn = [];

            foreach($comments as &$comment) 
            {
                $this->articleId = $comment['article_id'];
                if($this->checkParents($comment))
                {
                    $subcomments = $this->getSubcomments($comment['id']);
                    if(!empty($subcomments)) 
                    {
                        $comment['subcomments'] = $subcomments;
                    }
                    array_push($commentsToReturn, $comment);
                }
            }

            return $commentsToReturn;
        }

        return [];
    }
}