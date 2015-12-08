<?php
namespace ESG\Panther\Dao;
class FlexBatchDao extends BaseDao
{
    private $tableName = "flex_batch";
    private $voClassName = "FlexBatchVo";
    private $seqName = "";
    private $seqMappingField = "";

    public function __construct()
    {
        parent::__construct();
    }

    public function getVoClassName()
    {
        return $this->voClassName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getSeqName()
    {
        return $this->seqName;
    }

    public function getSeqMappingField()
    {
        return $this->seqMappingField;
    }
}