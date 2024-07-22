<?php
namespace Api\Handlers;

use Core\Database;
use Core\Warning;
use Core\Error;

use Base\BaseHandlerRoute;

use Api\Models\AdminSettingsSetModel;

class AdminSettingsSetArticlesPopularitySortFormulaHandler extends BaseHandlerRoute
{
    public static function _updatePopularitySortTriggers()
    {
        $database = Database::getConnection();

        if($database)
        {
            $formula = $this->database->get('settings', 'value', ['settings' => 'articles_popularity_sort_formula']);

        }
        else
        {
            throw new Critical(500, "Failed to establish database connenction", "Failed to establish database connenction");
        }
    }

    public function updatePopularitySortTriggers()
    {
        $formula = $this->database->get('settings', 'value', ['settings' => 'articles_popularity_sort_formula']);


    }
}