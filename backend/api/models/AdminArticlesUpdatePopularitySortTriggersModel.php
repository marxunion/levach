<?php
namespace Api\Handlers;

use Core\Database;
use Core\Warning;
use Core\Error;

use Base\BaseHandlerRoute;

use Api\Models\AdminSettingsSetModel;

class AdminArticlesUpdatePopularitySortTriggersModel extends BaseHandlerRoute
{
    public function updatePopularitySortTriggers()
    {
        $formula = $this->database->get('settings', 'value', ['settings' => 'articles_popularity_sort_formula']);
        
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
    }
}