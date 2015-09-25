<?php
namespace ESG\Panther\Dao;

class PmgwCardDao extends BaseDao
{
    private $table_name = 'pmgw_card';
    private $vo_class_name = 'PmgwCardVo';

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
