<?php
namespace ESG\Panther\Dao;

class EmailTemplateDao extends BaseDao
{
    private $tableName = "email_template";
    private $voClassName = "EmailTemplateVo";

    public function getVoClassname()
    {
        return $this->voClassName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getFormatTemplate()
    {

    }
}
