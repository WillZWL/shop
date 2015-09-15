<?php
namespace ESG\Panther\Dao;

class CourierFeedDao extends BaseDao
{
    private $tableName = "courier_feed";
    private $voClassName = "CourierFeedVO";

    public function __construct()
    {
        parent::__construct();
    }

    public function getVoClassname()
    {
        return $this->voClassName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }
}
