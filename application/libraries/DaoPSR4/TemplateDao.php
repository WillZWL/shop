<?php
namespace ESG\Panther\Dao;

class TemplateDao extends BaseDao
{
    private $tableName = "template";
    private $voClassName = "TemplateVo";

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

    public function getTplWithMsg($where = "")
    {
        return $this->get($where, "TplMsgWithAttDto");
    }
}
