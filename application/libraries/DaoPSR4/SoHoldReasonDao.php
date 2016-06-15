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
                    'keyValue'=>['hold_reason' => $this->getHoldReasonById($obj->getReason()),]
                  ];

        $this->updateTables([$table1,]);
    }

    public function getHoldReasonById($id = "")
    {
        if ($id == "") {
            return FALSE;
        }

        $sql = "SELECT reason_type, reason_cat cat, description reason from hold_reason WHERE id = ?";

        if ($query = $this->db->query($sql, $id)) {
            $cat = $query->row()->cat;
            // $reason = $query->row()->reason;
            $reason_type = $query->row()->reason_type;
            // $hrcategory = ["CS"=>"Hold By Customer Service","COMP"=>"Hold By Compliance","LG"=>"Hold By Logisitcs", "OT"=>"Others"];
            // $hold_reason = $reason_type . " " . $hrcategory[$cat] ." - ". $reason;

            return $reason_type;
        }

        return FALSE;
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

        $this->db->join("hold_reason hr", "hr.id = sh.reason", "INNER");

        $this->db->join("user u", "u.id = sh.create_by", "INNER");

        $this->db->select("hr.reason_type reason, u.username, sh.create_on");

        $this->db->order_by("sh.create_on DESC");

        $this->db->where($where);

        if ($query = $this->db->get()) {
            $ret = [];

            foreach ($query->result($classname) as $obj) {
                $ret[] = $obj;
            }

            return $ret;
        }

        return FALSE;
    }
}


