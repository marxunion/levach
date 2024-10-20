<?php
namespace Api\Models;

use Core\Warning;

use Helpers\StringFormatter;

use Base\BaseModel;

class ArticleNewModel extends BaseModel
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

    public function newArticle($title, $text, $tags, $viewCode, $editCode)
    {
        $text = StringFormatter::replaceViewIdsToViewIdsLinks($text);
        $text = StringFormatter::filterHtmlTags($text);

        $timestamp = time();

        $articleVersionData = [
            'version_id' => 1,
            'title' => $title,
            'text' => $text,
            'tags' => null,
            
            'editorially_status' => 0,
            'premoderation_status' => 1,
            'approvededitorially_status' => 0,
            
            'created_date' => $timestamp
        ];

        $articleData = 
        [
            'current_version' => 1,
            'current_title' => $title,
            'current_text' => $text,
            'current_tags' => null,

            'rating' => 0,
            'comments_count' => 0,
            'created_date' => $timestamp,
            'last_edit_date' => $timestamp,
            
            'edit_timeout_to_date' => $timestamp,

            'view_code' => $viewCode,
            'edit_code' => $editCode,

            'editorially_status' => 0,
            'premoderation_status' => 1,
            'approvededitorially_status' => 0
        ];
        
        $tagsString = '';
        if(is_array($tags))
        {
            if(count($tags) > 0)
            {
                foreach ($tags as $key => $value) 
                {
                    $tags[$key] = trim($tags[$key]);
                }
                if(count($tags) == count(array_unique($tags)))
                {
                    $tagsString = '{'.implode(',', $tags).'}';
                    $articleVersionData['tags'] = $tagsString;
                    $articleData['current_tags'] = $tagsString;
                }
                else
                {
                    throw new Warning(400, 'Article has duplicated tags', 'Article has duplicated tags');
                }
            }
        }

        $this->database->insert('articles', $articleData);
        $articleVersionData['article_id'] = $this->getArticleByViewCode($viewCode);
        $this->database->insert('articles_versions', $articleVersionData);
    }

    public function newArticleAdmin($title, $text, $tags, $viewCode, $editCode)
    {
        $text = StringFormatter::replaceViewIdsToViewIdsLinks($text);
        $text = StringFormatter::filterHtmlTags($text);

        $timestamp = time();

        $articleData = 
        [
            'current_version' => 1,
            'current_title' => $title,
            'current_text' => $text,
            'current_tags' => null,

            'rating' => 0,
            'comments_count' => 0,
            'created_date' => $timestamp,
            'last_edit_date' => $timestamp,
            
            'edit_timeout_to_date' => $timestamp,

            'view_code' => $viewCode,
            'edit_code' => $editCode,

            'editorially_status' => 1,
            'premoderation_status' => 2,
            'approvededitorially_status' => 2
        ];
        $articleVersionData = 
        [
            'version_id' => 1,
            'title' => $title,
            'text' => $text,
            'tags' => null,
            
            'editorially_status' => 1,
            
            'premoderation_status' => 2,
            'approvededitorially_status' => 2,
            
            'created_date' => $timestamp
        ];

        $tagsString = '';
        if(is_array($tags))
        {
            if(count($tags) > 0)
            {
                foreach ($tags as $key => $value) 
                {
                    $tags[$key] = trim($tags[$key]);
                }
                if(count($tags) == count(array_unique($tags)))
                {
                    $tagsString = '{'.implode(',', $tags).'}';
                    $articleData['current_tags'] = $tagsString;
                    $articleVersionData['tags'] = $tagsString;
                }
                else
                {
                    throw new Warning(400, 'Article has duplicated tags', 'Article has duplicated tags');
                }
            }
        }        

        $this->database->insert('articles', $articleData);
        $articleVersionData['article_id'] = $this->getArticleByViewCode($viewCode);
        $this->database->insert('articles_versions', $articleVersionData);
    }
}