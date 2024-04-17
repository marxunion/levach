<?php
namespace Base;

use Core\Database;

class BaseModel
{
    protected $database; 
    public function __construct()
    {
        $this->database = Database::getConnection();
    }
}