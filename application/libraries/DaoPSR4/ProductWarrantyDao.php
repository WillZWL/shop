<?php
namespace ESG\Panther\Dao;

class ProductWarrantyDao extends BaseDao
{
    private $table_name = 'product_warranty';
    private $vo_class_name = 'ProductWarrantyVo';

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
