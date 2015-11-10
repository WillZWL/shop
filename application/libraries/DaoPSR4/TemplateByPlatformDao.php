<?php
namespace ESG\Panther\Dao;

class TemplateByPlatformDao extends BaseDao
{
    private $tableName = "template_by_platform";
    private $voClassName = "TemplateByPlatformVo";
    // private $seq_name = "Template_by_platform";
    // private $seq_mapping_field = "id";

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


