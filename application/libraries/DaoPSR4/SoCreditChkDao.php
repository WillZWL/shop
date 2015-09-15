<?php
namespace ESG\Panther\Dao;

class SoCreditChkDao extends BaseDao
{
    private $tableName = "so_credit_chk";
    private $voClassName = "SoCreditChkVo";

    public function __construct()
    {
        parent::__construct();
    }

    public function getVoClassname()
    {
        return $this->voClassName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function get_cc_list()
    {
        $list = $this->getList(["t3m_result IS" => ""], ["limit" => "-1"]);

        $ret = [];

        foreach ($list as $obj) {
            $ret[$obj->getSoNo()] = 1;
        }

        return $ret;
    }
}
