<?php
namespace Api\Models;

use Core\Database;
use Core\Error;
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
            if(!$database->update('settings', ['value' => $settingValue], ['name' => $settingName]))
            {
                if($database->insert('settings', 
                [
                    'name' => $settingName, 
                    'value' => $settingValue
                ]))
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return true;
            }
        }
        else
        {
            throw new Critical(500, "Failed to establish database connenction", "Failed to establish database connenction");
        }
    }

    public function setSetting($settingName, $settingValue)
    {
        if(!$this->database->update('settings', ['value' => $settingValue], ['name' => $settingName]))
        {
            if($this->database->insert('settings', 
            [
                'name' => $settingName, 
                'value' => $settingValue
            ]))
            {
                return true;
            }
            else
            {
                throw new Critical(500, "Failed to set setting", "Failed to set setting");
            }
        }
        else
        {
            return true;
        }
    }
}