<?php
namespace ESG\Panther\Dao;

class SoHoldReasonDao extends BaseDao
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

    public function getLatestRequest($where = [])
    {
        $sql = "SELECT *
                FROM so_hold_reason
                WHERE so_no = ?
                AND reason LIKE '%_log_app'
                ORDER BY create_on DESC
                LIMIT 1";

        if ($query = $this->db->query($sql, [$where["so_no"]])) {
            foreach ($query->result($$this->getVoClassname()) as $tmp) {
                $obj = $tmp;
            }

            return $obj;
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

    public function getListWithUname($where = [], $option = [], $classname = "Hold_history_uname_dto")
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


