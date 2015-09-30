<?php

namespace ESG\Panther\Dao;

class RaGroupContentDao extends BaseDao
{
    private $tableName = "ra_group_content";
    private $voClassName = "RaGroupContentVo";

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
        return $this->voClassName;
    }

}


