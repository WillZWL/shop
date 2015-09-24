<?php
namespace ESG\Panther\Dao;

class RaProductDao extends BaseDao
{	
	private $tableName = "ra_product";
    private $voClassName = "RaProductVo";
	
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


