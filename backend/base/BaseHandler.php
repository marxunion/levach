<?php
namespace Base;

use Base\BaseModel;

abstract class BaseHandler
{
    protected BaseModel $model;
    public function __construct()
    {

    }

    abstract public function Init();

    abstract public function Process();

    abstract public function Finish();

    abstract public function Handle();
}
