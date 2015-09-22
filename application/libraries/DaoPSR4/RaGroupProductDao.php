<?php
namespace ESG\Panther\Dao;

class RaGroupProductDao extends BaseDao
{
    private $tableName = "ra_group_product";
    private $voClassName = "RaGroupProductVo";
	
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


