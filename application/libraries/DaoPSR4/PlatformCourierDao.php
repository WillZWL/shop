<?php
namespace ESG\Panther\Dao;

class PlatformCourierDao extends BaseDao
{
    private $tableName = "platform_courier";
    private $voClassName = "PlatformCourierVo";

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
