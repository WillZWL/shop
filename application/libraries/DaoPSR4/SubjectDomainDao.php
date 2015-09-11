<?php
namespace ESG\Panther\Dao;

class SubjectDomainDao extends BaseDao
{
    private $tableName = "subject_domain";
    private $voClassName = "SubjectDomainVo";

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
