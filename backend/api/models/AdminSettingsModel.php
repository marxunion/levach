<?php
namespace Api\Models;

use Core\Database;
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
        return $database->select('settings', '*');
    }

    public static function _getProperty($propertyName)
    {
        $database = Database::getConnection();
        return $this->database->get('settings', '*', ['name' => $propertyName]);
    }

    public static function _setProperty($propertyName, $propertyValue)
    {
        $database = Database::getConnection();
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
    }

    public function getAllProperties()
    {
        return $this->database->select('settings', '*');
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
    }
}