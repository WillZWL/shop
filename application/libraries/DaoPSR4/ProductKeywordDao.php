<?php
namespace ESG\Panther\Dao;

class ProductKeywordDao extends BaseDao
{
    private $table_name = 'product_keyword';
    private $vo_class_name = 'ProductKeywordVo';

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
