<?php
namespace Api\Models;

use Core\Database;

use Core\Critical;

use Base\BaseModel;

class AdminSettingsSetModel extends BaseModel
{
    public static function _setSetting($settingName, $settingValue)
    {
        $database = Database::getConnection();

        if($database)
        {
            $result = $database->update('settings', ['value' => $settingValue], ['name' => $settingName]);
            if ($result->rowCount() == 0) 
            {
                $database->insert('settings', ['name' => $settingName, 'value' => $settingValue]);
            }
            return true;
        }
        else
        {
            throw new Critical(500, "Failed to establish database connenction", "Failed to establish database connenction");
        }
    }

    public function setSetting($settingName, $settingValue)
    {
        $result = $this->database->update('settings', ['value' => $settingValue], ['name' => $settingName]);
        if ($result->rowCount() == 0) 
        {
            $this->database->insert('settings', ['name' => $settingName, 'value' => $settingValue]);
        }
    }
}