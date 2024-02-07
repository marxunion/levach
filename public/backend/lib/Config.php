<?php 

namespace backend\lib;

class Config 
{
  
  protected static function MainConfig() 
  {
    require 'backend/settings/configs/config.php';
    return $config;
  }
  public static function DbLoaderConfig() 
  {
    require 'backend/settings/configs/config_db.php';
    return $config;
  }
  public static function MainLoaderConfig($value)
  {
    $config = self::MainConfig();
    return $config[$value];
  }
}







?>