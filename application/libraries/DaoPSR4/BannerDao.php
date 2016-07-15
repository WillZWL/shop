<?php

namespace ESG\Panther\Dao;

class BannerDao extends BaseDao
{
    private $tableName = "banner";
    private $voClassname = "BannerVo";

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