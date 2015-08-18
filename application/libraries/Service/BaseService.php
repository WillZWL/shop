<?php
namespace AtomV2\Service;

class BaseService
{
    private $dao;

    protected function getDao()
    {
        return $this->dao;
    }

    public function setDao($dao)
    {
        $this->dao = $dao;
    }

    public function get($where = [], $classname = '')
    {
        return $this->dao->get($where, $classname);
    }
}
