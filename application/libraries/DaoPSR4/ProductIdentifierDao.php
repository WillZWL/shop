<?php
namespace ESG\Panther\Dao;

class ProductIdentifierDao extends BaseDao
{
    private $table_name = 'product_identifier';
    private $vo_class_name = 'ProductIdentifierVo';

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


