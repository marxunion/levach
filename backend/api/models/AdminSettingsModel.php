<?php
namespace Api\Models;

use Core\Database;
use Core\Settings;
use Core\Error;

use Base\BaseModel;

class AdminSettingsModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public static function _getAllProperties()
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

    public static function _getProperty($propertyName)
    {
        $database = Database::getConnection();

        if($database)
        {
            return $this->database->get('settings', '*', ['name' => $propertyName]);
        }
        else
        {
            throw new Critical(500, "Failed to establish database connenction", "Failed to establish database connenction");
        }
        
    }

    public static function _setProperty($propertyName, $propertyValue)
    {
        $database = Database::getConnection();

        if($database)
        {
            if(!$database->update('settings', ['value' => $propertyValue], ['name' => $propertyName]))
            {
                if($database->insert('settings', 
                [
                    'name' => $propertyName, 
                    'value' => $propertyValue
                ]))
                {
                    return true;
                }
                else
                {
                    throw new Critical(500, "Failed to set property", "Failed to set property");
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

    public function getAllProperties()
    {
        $data = $this->database->select('settings', '*');
        $settings = [];

        foreach ($data as &$setting) 
        {
            $settings[$setting['name']] = $setting['value'];
        }
            
        return $settings;
    }

    public function getProperty($propertyName)
    {
        return $this->database->get('settings', '*', ['name' => $propertyName]);
    }

    public function setProperty($propertyName, $propertyValue)
    {
        if(!$this->database->update('settings', ['value' => $propertyValue], ['name' => $propertyName]))
        {
            if($this->database->insert('settings', 
            [
                'name' => $propertyName, 
                'value' => $propertyValue
            ]))
            {
                return true;
            }
            else
            {
                throw new Critical(500, "Failed to set property", "Failed to set property");
            }
        }
        else
        {
            return true;
        }
    }
}