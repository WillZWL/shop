<?php
namespace ESG\Panther\Dao;

class RaGroupDao extends BaseDao
{	
    private $tableName = "ra_group";
    private $voClassName = "RaGroupVo";
	
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


