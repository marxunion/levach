<?php
namespace Routes\Api\Admin\Articles\Settings\Get;

use Core\Database;
use Core\Settings;
use Core\Critical;

use Base\BaseModel;

class MainModel extends BaseModel
{
    public static function _getSetting($settingName)
    {
        $database = Database::getConnection();

        if($database)
        {
            return $database->get('settings', 'value', ['name' => $settingName]);
        }
        else
        {
            throw new Critical(500, "Failed to establish database connenction", "Failed to establish database connenction");
        }
    }

    public function getSetting($settingName)
    {
        return $this->database->get('settings', 'value', ['name' => $settingName]);
    }
}