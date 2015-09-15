<?php
namespace ESG\Panther\Dao;

class ConfigDao extends BaseDao
{
    private $tableName = "config";
    private $voClassName = "ConfigVo";

    public function __construct()
    {
        parent::__construct();
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getVoClassname()
    {
        return $this->voClassName;
    }

    public function valueOf($variable = "")
    {
        $this->db->select('value');
        if ($query = $this->db->get_where($this->getTableName(), array("variable" => $variable)))
            return $query->row()->value;
        else
            return FALSE;
    }
}
