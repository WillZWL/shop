<?php
namespace ESG\Panther\Dao;

class SoPaymentQueryLogDao extends BaseDao
{
    private $table_name = "so_payment_query_log";
    private $vo_class_name = "SoPaymentQueryLogVo";

    public function __construct()
    {
        parent::__construct();
    }

    public function getVoClassname()
    {
        return $this->vo_class_name;
    }

    public function getTableName()
    {
        return $this->table_name;
    }
}


