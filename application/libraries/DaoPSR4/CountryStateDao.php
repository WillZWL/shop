<?php
namespace AtomV2\Dao;

class CountryStateDao extends BaseDao
{
    private $tableName = "country_state";
    private $voClassName = "CountryStateVo";

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


