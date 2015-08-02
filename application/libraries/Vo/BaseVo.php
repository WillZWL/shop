<?php

namespace AtomV2\Vo;

abstract class BaseVo
{
    abstract public function getPrimaryKey();
    abstract public function getFieldNameSet();
}
