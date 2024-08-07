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

        $sql = "SELECT id, current_title, created_date, view_code, view_id FROM articles WHERE created_date BETWEEN :date_before AND :date_after AND current_text ~* :regex_pattern AND comments_count > 0 ORDER BY created_date DESC LIMIT :count";  
        $bindings = [
            ':date_before' => $articleDateBefore,
            ':date_after' => $articleDateAfter,
            ':regex_pattern' => $articleRegexPattern,
            ':count' => $articlesCount
        ];

        $articles = $this->database->query($sql, $bindings)->fetchAll();

        $articlesToReturn = [];

        foreach($articles as &$article) 
        {
            $this->articleId = $article['id'];
            
            $sql = "SELECT id, text, rating, created_date, rating_influence, parent_comment_id, view_id FROM comments WHERE article_id = :article_id AND created_date BETWEEN :date_before AND :date_after AND text ~* :regex_pattern ORDER BY created_date DESC LIMIT :count";  
            $bindings = [
                ':article_id' => $this->articleId,
                ':date_before' => $this->commentDateBefore,
                ':date_after' => $this->commentDateAfter,
                ':regex_pattern' => $this->commentRegexPattern,
                ':count' => $commentsCount
            ];

            $comments = $this->database->query($sql, $bindings)->fetchAll();
            $commentsToReturn = [];

            foreach($comments as &$comment) 
            {
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

            if(!empty($commentsToReturn)) 
            {
                $article["comments"] = $commentsToReturn;
                array_push($articlesToReturn, $article);
            }
        }
        return $articlesToReturn;
    }
}