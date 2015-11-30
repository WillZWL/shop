<?php

namespace ESG\Panther\Dao;

class AffiliateDao extends BaseDao
{
    private $tableName = "affiliate";
    private $voClassname = "AffiliateVo";

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


