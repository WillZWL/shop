<?php
namespace ESG\Panther\Dao;

class GoogleRequestBatchDao extends BaseDao
{
    private $tableName = "google_request_batch";
    private $voClassName = "GoogleShoppingVo";

    public function __construct() {
        parent::__construct();
    }

    public function getVoClassname() {
        return $this->voClassName;
    }

    public function getTableName() {
        return $this->tableName;
    }
}