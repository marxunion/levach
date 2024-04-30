<?php
namespace Api\Models;

use Core\Database;
use Core\Settings;
use Core\Error;
use Core\Critical;

use Base\BaseModel;

class AdminSettingsGetModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public static function _getAllSettings()
    {
        $database = Database::getConnection();
        if($database)
        {
            $data = $database->select('settings', '*');
            $settings = [];

            foreach ($data as &$setting) 
            {
                $settings[$setting['name']] = $setting['value'];
            }

            return $settings;
        }
        else
        {
            throw new Critical(500, "Failed to establish database connenction", "Failed to establish database connenction");
        }
    }

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

    public function getAllSettings()
    {
        $data = $this->database->select('settings', '*');
        $settings = [];

        foreach ($data as &$setting) 
        {
            $settings[$setting['name']] = $setting['value'];
        }
        
        return $settings;
    }

    public function getSetting($settingName)
    {
        return $this->database->get('settings', 'value', ['name' => $settingName]);
    }
}