<?php
namespace AtomV2\Dao;

class FulfillmentCentreDao extends BaseDao
{
    private $tableName = "fulfillment_centre";
    private $voClassName = "FulfillmentCentreVo";

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


