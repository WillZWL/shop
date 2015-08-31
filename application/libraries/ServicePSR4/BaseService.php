<?php
namespace AtomV2\Service;

class BaseService
{
    private $dao;

    public function __construct()
    {
    }

    public function getDao()
    {
        return $this->dao;
    }

    public function setDao($dao)
    {
        $this->dao = $dao;
    }

    public function get($where = [], $className = '')
    {
        return $this->dao->get($where, $className);
    }

    public function getList($where = [], $option = [], $className = "")
    {
        return $this->dao->getList($where, $option, $className);
    }
}
