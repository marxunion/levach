<?php
namespace Core\Base;

use Base\BaseModel;

abstract class BaseController
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
