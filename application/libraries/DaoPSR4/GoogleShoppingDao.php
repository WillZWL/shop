<?php
namespace ESG\Panther\Dao;

class GoogleShoppingDao extends BaseDao
{
    private $table_name = "google_shopping";
    private $vo_classname = "GoogleShoppingVo";
    private $seq_name = "";
    private $seq_mapping_field = "";

    public function __construct()
    {
        parent::__construct();
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getVoClassname()
    {
        return $this->voClassname;
    }
}