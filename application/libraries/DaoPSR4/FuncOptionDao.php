<?php
namespace ESG\Panther\Dao;

class FuncOptionDao extends BaseDao
{
    private $tableName = "func_option";
    private $voClassName = "FuncOptionVo";

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

    public function text_of($func_id, $lang_id = "en")
    {
        $this->db->select('text');
        if ($query = $this->db->get_where($this->getTableName(), array("func_id" => $func_id, "lang_id" => $lang_id), 1)) {
            return $query->row()->text;
        } else {
            return FALSE;
        }
    }
}


