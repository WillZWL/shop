<?php
namespace ESG\Panther\Dao;

class ProductImageDao extends BaseDao
{
    private $table_name = 'product_image';
    private $vo_class_name = 'ProductImageVo';

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
