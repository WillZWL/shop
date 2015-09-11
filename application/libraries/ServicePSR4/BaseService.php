<?php
namespace ESG\Panther\Service;

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

    public function getNumRows($where = [])
    {
        return $this->dao->getNumRows($where);
    }

    public function getList($where = [], $option = [], $className = "")
    {
        return $this->dao->getList($where, $option, $className);
    }
}
