<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Fd_section_sub_cat_dao extends Base_dao
{
    private $table_name="fd_section_sub_cat";
    private $vo_class_name="Fd_section_sub_cat_vo";
    private $seq_name="";
    private $seq_mapping_field="";

    public function __construct(){
        parent::__construct();
    }

    public function get_vo_classname(){
        return $this->vo_class_name;
    }

    public function get_table_name(){
        return $this->table_name;
    }

    public function get_seq_name(){
        return $this->seq_name;
    }

    public function get_seq_mapping_field(){
        return $this->seq_mapping_field;
    }

    public function verify_festive_link_id($festive="", $link_id="")
    {
        $time = date("Y-m-d H:i:s");
        $sql = "SELECT fdssc.*
                FROM fd_section_sub_cat fdssc
                JOIN fd_section_cat fdsc
                    ON fdssc.fdsc_id = fdsc.id
                    AND fdsc.id = ?
                JOIN fd_section fds
                    ON fds.id = fdsc.fds_id
                JOIN festive_deal fd
                    ON fd.link_name = ?
                    AND fd.id = fds.fd_id
                    AND fd.start_date <= ?
                    AND fd.end_date >= ?
                    AND display = 'Y'
                ORDER BY fdssc.display_order ASC";

        $this->include_vo();

        if($query = $this->db->query($sql,array($link_id,$festive,$time,$time)))
        {
            $ret = array();
            foreach($query->result($$this->get_vo_classname()) as $obj)
            {
                $ret[] = $obj;
            }
            return $ret;
        }
        return FALSE;
    }
}