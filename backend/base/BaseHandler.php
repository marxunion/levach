<?php
namespace Base;

use Base\BaseModel;

abstract class BaseHandler
{
    protected BaseModel $model;
    public function __construct()
    {

    }

    abstract protected function _Init();
    abstract public function Init();

    abstract protected function _Process();
    abstract public function Process();

    abstract protected function _Finish();
    abstract public function Finish();

    abstract public function Handle();
}
