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

        $updateQuery = "UPDATE articles SET popularity_sort_value = $formula;";
        $updateQuery = str_replace("NEW.", "", $updateQuery);
        echo $updateQuery;
        $this->database->query($updateQuery);
    }
}