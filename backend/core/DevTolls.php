<?php 

namespace backend\core;

class DevTools
{
  public static function Debug ($object) {
    
    if (SettingsLoader::SettingLoader('DEBUG_MODE') and SettingsLoader::SettingLoader('DEV_TOLLS')) 
    {
      $objecttype = gettype($object);
      if ($objecttype == 'array') 
      {
        DevTools::DebugArray($object); 
      }
      elseif ($objecttype == 'object') 
      {
        DevTools::DebugObject($object); 
      }
      elseif ($objecttype == ('string' || 'integer' || 'double')) 
      {
        echo $object;
      }
      elseif ($objecttype == 'boolean') 
      {
        if ($object == true) 
        {
          echo 'True';
        } 
        else 
        {
          echo 'False';
        }
      }
      elseif ($objecttype == 'null') 
      {
        echo 'NULL';
      } 
      else 
      {
        echo 'Variable type impossimble undebuging';
      }
    }
    
  }


  public static function DebugArray($array) 
  {
    if (SettingsLoader::SettingLoader('DEBUG_MODE') and SettingsLoader::SettingLoader('DEV_TOLLS')) 
    {
      echo '<pre>';
      var_dump($array);
      echo '</pre>';
    }
  }

  public static function DebugObject($object) 
  {
    if (SettingsLoader::SettingLoader('DEBUG_MODE') and SettingsLoader::SettingLoader('DEV_TOLLS')) 
    {
      echo '<pre>';
      var_dump($object);
      echo '</pre>';
    }
  }
}
