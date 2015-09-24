<?php
namespace ESG\Panther\Dao;

class SoReleaseOrderDao extends Base_dao
{
    private $tableName = "release_order_history";
    private $voClassname = "ReleaseOrderHistoryVo";

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


