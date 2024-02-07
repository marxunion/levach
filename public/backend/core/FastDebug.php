<?php

function debug ($object) 
{
    $objectType = gettype($object);
    if ($objectType == ('array' || 'object')) 
    {
      echo '<pre>';
      var_dump($object);
      echo '</pre>';
    }
    elseif ($objectType == ('string' || 'integer' || 'double')) 
    {
      echo $object;
    }
    elseif ($objectType == 'boolean') 
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
    elseif ($objectType == 'null') 
    {
      echo 'NULL';
    } 
    else 
    {
      echo 'Variable type impossimble undebuging';
    }
}