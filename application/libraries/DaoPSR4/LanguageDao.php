<?php
namespace AtomV2\Dao;

class LanguageDao extends BaseDao
{
    private $tableName = "language";
    private $voClassName = "LanguageVo";

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
