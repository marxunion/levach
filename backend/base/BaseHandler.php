<?php
namespace Base;

use Base\BaseModel;

abstract class BaseHandler
{
    protected BaseModel $model;
    public function __construct()
    {

    }

    abstract function Init();
    abstract function Process();
    abstract function Finish();

    abstract function Handle();
}
