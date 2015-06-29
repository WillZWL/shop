<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class So_hold_reason_dao extends Base_dao
{
    private $table_name = "so_hold_reason";
    private $vo_class_name = "So_hold_reason_vo";
    private $seq_name = "";
    private $seq_mapping_field = "";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_vo_classname()
    {
        return $this->vo_class_name;
    }

    public function get_table_name()
    {
        return $this->table_name;
    }

    public function get_seq_name()
    {
        return $this->seq_name;
    }

    public function get_seq_mapping_field()
    {
        return $this->seq_mapping_field;
    }

    public function get_latest_request($where = array())
    {
        $sql = "SELECT *
                FROM so_hold_reason
                WHERE so_no = ?
                AND reason LIKE '%_log_app'
                ORDER BY create_on DESC
                LIMIT 1";

        $this->include_vo();

        if ($query = $this->db->query($sql, array($where["so_no"]))) {
            foreach ($query->result($$this->get_vo_classname()) as $tmp) {
                $obj = $tmp;
            }

            return $obj;
        }
        return FALSE;
    }

    public function get_reason_list()
    {
        $sql = "select distinct s.reason from so_hold_reason s";
        $query = $this->db->query($sql);

        foreach ($query->result() as $tmp) {
            $obj[$tmp->reason] = $tmp->reason;
        }

        return $obj;
    }

    public function get_list_w_uname($where = array(), $option = array(), $classname = "Hold_history_uname_dto")
    {
        $this->db->from("so_hold_reason sh");

        $this->db->join("user u", "u.id = sh.create_by", "INNER");

        $this->db->select("sh.reason, u.username, sh.create_on");

        $this->db->order_by("sh.create_on DESC");

        $this->db->where($where);

        if ($query = $this->db->get()) {
            $ret = array();
            $this->include_dto($classname);

            foreach ($query->result($classname) as $obj) {
                $ret[] = $obj;
            }

            return $ret;
        }
        echo $this->db->last_query() . " " . $this->db->_error_message();
        return FALSE;
    }
}


