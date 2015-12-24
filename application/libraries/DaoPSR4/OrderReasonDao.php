<?php
namespace ESG\Panther\Dao;

class OrderReasonDao extends BaseDao
{
    private $table_name = "order_reason";
    private $vo_classname = "OrderReasonVo";

    public function getTableName()
    {
        return $this->table_name;
    }

    public function getVoClassname()
    {
        return $this->vo_classname;
    }
}


