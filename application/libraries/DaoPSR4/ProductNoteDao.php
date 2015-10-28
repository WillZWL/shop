<?php
namespace ESG\Panther\Dao;

class ProductNoteDao extends BaseDao
{
    private $table_name = 'product_note';
    private $vo_class_name = 'ProductNoteVo';

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

