<?php
namespace Base;

use Core\Critical;

use Core\S3Client;
use Core\Database;

class BaseModel
{
    protected $database;

    public function __construct()
    {
        $this->database = Database::getConnection();
        if(!$this->database)
        {
            throw new Critical(500, "Failed to establish database connenction", "Failed to establish database connenction");
        }
    }
}