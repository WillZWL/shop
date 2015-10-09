<?php
namespace ESG\Panther\Dao;

class SoHoldReasonDao extends BaseDao implements HooksInsert
{
    private $tableName = "so_hold_reason";
    private $voClassName = "SoHoldReasonVo";

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

    public function insertAfterExecute($obj)
    {
        $this->tableFieldsHooksInsert($obj);
    }

    public function tableFieldsHooksInsert($obj)
    {
        $table1 = [
                    'table' => 'so',
                    'where' => ['so_no'=>$obj->getSoNo(),],
                    'keyValue'=>['hold_reason' => $obj->getId(),]
                  ];

        $this->updateTables([$table1,]);
    }

    public function getReasonList()
    {
        $sql = "select distinct s.reason from so_hold_reason s";
        $query = $this->db->query($sql);

        foreach ($query->result() as $tmp) {
            $obj[$tmp->reason] = $tmp->reason;
        }

        return $obj;
    }

    public function getListWithUname($where = [], $option = [], $classname = "HoldHistoryUnameDto")
    {
        $this->db->from("so_hold_reason sh");

        $this->db->join("user u", "u.id = sh.create_by", "INNER");

        $this->db->select("sh.reason, u.username, sh.create_on");

        $this->db->order_by("sh.create_on DESC");

        $this->db->where($where);

        if ($query = $this->db->get()) {
            $ret = [];

            foreach ($query->result($classname) as $obj) {
                $ret[] = $obj;
            }

            return $ret;
        }
        echo $this->db->last_query() . " " . $this->db->_error_message();
        return FALSE;
    }
}


