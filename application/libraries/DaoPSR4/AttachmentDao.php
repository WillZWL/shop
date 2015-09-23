<?php
namespace ESG\Panther\Dao;

class AttachmentDao extends BaseDao
{
    private $tableName = "attachment";
    private $voClassName = "AttachmentVo";

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


