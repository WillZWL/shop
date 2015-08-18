<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Release_order_report_dao extends Base_dao
{
    private $table_name = "release_order_history";
    private $vo_class_name = "release_order_history_vo";
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

    public function order_release_activity_log($where, $option = array(), $classname = "order_release_activity_log_dto")
    {
        $this->db->from("(select temp5.so_no, temp5.create_at, temp5.create_on, temp5.create_by, release_reason from release_order_history temp5
                inner JOIN
                    (select so_no, max(create_on) as create_on from release_order_history group by so_no)
                    as temp4
                    on temp4.so_no = temp5.so_no and temp4.create_on=temp5.create_on
                ) as roh

                inner join

                (select temp3.so_no, temp3.create_on, create_at, create_by, reason from so_hold_reason temp3
                inner join
                    (select so_no, max(create_on) as create_on from so_hold_reason group by so_no)
                    as temp1
                    on temp1.so_no = temp3.so_no and temp1.create_on=temp3.create_on
                ) as sohr
                on roh.so_no = sohr.so_no");

        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, "roh.so_no as order_number,sohr.reason as hold_reason, sohr.create_on as hold_date, sohr.create_by as hold_by,roh.release_reason as release_reason,roh.create_on as release_date, roh.create_at as release_at, roh.create_by as release_by");
    }
}


