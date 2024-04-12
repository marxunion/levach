<?php
namespace Base;

use Core\Database;

class BaseModel
{
    private $database; 
    public function __construct()
    {
        $this->database = Database::getConnection();
    }
}