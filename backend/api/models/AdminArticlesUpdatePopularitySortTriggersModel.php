<?php
namespace Api\Models;

use Core\Database;
use Core\Warning;
use Core\Error;

use Base\BaseModel;

use Api\Models\AdminSettingsSetModel;

class AdminArticlesUpdatePopularitySortTriggersModel extends BaseModel
{
    public function updateTriggers()
    {
        $formula = $this->database->get('settings', 'value', ['name' => 'articles_popularity_sort_formula']);
        
        $this->database->query("
            CREATE OR REPLACE FUNCTION calculate_popularity_sort_value() RETURNS TRIGGER AS $$
            BEGIN
                NEW.popularity_sort_value := $formula;
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ");

        $this->database->query("
            CREATE OR REPLACE FUNCTION update_popularity_sort_value() RETURNS TRIGGER AS $$
            BEGIN
                NEW.popularity_sort_value := $formula;
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ");
        
        $current_timestamp = time();

        $updateQuery = "UPDATE articles SET popularity_sort_value = $formula;";

        //NEW.rating * NEW.comments_count / GREATEST(EXTRACT(EPOCH FROM (current_timestamp - to_timestamp(NEW.created_date))) / 600000 * GREATEST(EXTRACT(EPOCH FROM (current_timestamp - to_timestamp(NEW.created_date))) / 600000, 1), 1)

        $updateQuery = preg_replace(
            [
                '/EXTRACT\(EPOCH FROM\s*\((.*?)\)\s*\)/iu',
                '/to_timestamp\s*\((.*?)\)/iu', 
                '/(NEW|OLD)\./i', 
                '/\:\:timestamp/iu',
                '/(\s*;)/i',
                '/(current_timestamp)/i'
            ],
            [
                '($1)',
                '($1)',
                '', 
                '', 
                ';',
                "$current_timestamp"
            ],
            $updateQuery
        );

        $this->database->query($updateQuery);
    }
}