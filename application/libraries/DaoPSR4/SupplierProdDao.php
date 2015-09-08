<?php
namespace AtomV2\Dao;

class SupplierProdDao extends BaseDao
{
    private $table_name = 'supplier_prod';
    private $vo_class_name = 'SupplierProdVo';

    public function getVoClassname()
    {
        return $this->vo_class_name;
    }

    public function getTableName()
    {
        return $this->table_name;
    }

    public function __construct()
    {
        parent::__construct();
    }
}
