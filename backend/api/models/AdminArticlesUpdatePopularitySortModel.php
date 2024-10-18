<?php
namespace Api\Models;

use Core\Database;
use Core\Warning;
use Core\Error;

use Base\BaseModel;

use Api\Models\AdminSettingsSetModel;

class AdminArticlesUpdatePopularitySortModel extends BaseModel
{
    private $formula;

    public function __construct() 
    {
        parent::__construct();
        $this->formula = $this->database->get('settings', 'value', ['name' => 'articles_popularity_sort_formula']);
    }

    public static function _updatePopularityValues()
    {
        $database = Database::getConnection();
        $formula = $database->get('settings', 'value', ['name' => 'articles_popularity_sort_formula']);

        $current_timestamp = time();

        $updateQuery = "UPDATE articles SET popularity_sort_value = ".$formula.";";

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

        $database->query($updateQuery);
    }

    public function updateTriggers()
    {
        $this->database->query("
            CREATE OR REPLACE FUNCTION calculate_popularity_sort_value() RETURNS TRIGGER AS $$
            BEGIN
                NEW.popularity_sort_value := ".$this->formula.";
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ");

        $this->database->query("
            CREATE OR REPLACE FUNCTION update_popularity_sort_value() RETURNS TRIGGER AS $$
            BEGIN
                NEW.popularity_sort_value := ".$this->formula.";
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ");
    }

    public function updatePopularityValues()
    {
        $current_timestamp = time();

        $updateQuery = "UPDATE articles SET popularity_sort_value = ".$this->formula.";";

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