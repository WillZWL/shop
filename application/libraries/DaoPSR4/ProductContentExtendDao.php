<?php
namespace ESG\Panther\Dao;

class ProductContentExtendDao extends BaseDao
{
    private $table_name = 'product_content_extend';
    private $vo_class_name = 'ProductContentExtendVo';

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
