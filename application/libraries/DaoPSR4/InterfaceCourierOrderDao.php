<?php
namespace ESG\Panther\Dao;

class InterfaceCourierOrderDao extends BaseDao
{
    private $tableName = "interface_courier_order";
    private $voClassName = "InterfaceCourierOrderVo";

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
}
