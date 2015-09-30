<?php
namespace ESG\Panther\Dao;

class ProductContentDao extends BaseDao
{
    private $table_name = 'product_content';
    private $vo_class_name = 'ProductContentVo';

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
