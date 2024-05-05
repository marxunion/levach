<?php
namespace Api\Models;

use Core\Database;
use Core\Critical;

use Base\BaseModel;

class AdminSettingsSetModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public static function _setSetting($settingName, $settingValue)
    {
        $database = Database::getConnection();

        if($database)
        {
            $database->update('settings', ['value' => $settingValue], ['name' => $settingName]);
            $database->insert('settings', ['name' => $settingName, 'value' => $settingValue]);
            return true;
        }
        else
        {
            throw new Critical(500, "Failed to establish database connenction", "Failed to establish database connenction");
        }
    }

    public function setSetting($settingName, $settingValue)
    {
        $this->database->update('settings', ['value' => $settingValue], ['name' => $settingName]);
        $this->database->insert('settings', ['name' => $settingName, 'value' => $settingValue]);
    }
}