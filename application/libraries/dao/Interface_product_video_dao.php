<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Interface_product_video_dao extends Base_dao {
    private $table_name="interface_product_video";
    private $vo_class_name="Interface_product_video_vo";
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

    public function get_batch_record_list($where=array(), $option=array())
    {
        $this->db->from('interface_product_video AS iyt');
        $this->db->where($where);

        if (empty($option["num_rows"]))
        {

            $this->include_vo();

            if (isset($option["orderby"]))
            {
                $this->db->order_by($option["orderby"]);
            }

            if (isset($option["groupby"]))
            {
                $this->db->group_by($option["groupby"]);
            }

            if (empty($option["limit"]))
            {
                $option["limit"] = $this->rows_limit;
            }

            elseif ($option["limit"] == -1)
            {
                $option["limit"] = "";
            }

            if (!isset($option["offset"]))
            {
                $option["offset"] = 0;
            }

            if ($this->rows_limit != "")
            {
                $this->db->limit($option["limit"], $option["offset"]);
            }

            $rs = array();

            if ($query = $this->db->get())
            {
                foreach ($query->result($this->get_vo_classname()) as $obj)
                {
                    $rs[] = $obj;
                }
                return $rs;
            }
        }
        else
        {
            $this->db->select('COUNT(*) AS total');
            if ($query = $this->db->get())
            {
                return $query->row()->total;
            }
        }

        return FALSE;
    }
}

/* End of file interface_product_video_dao.php */
/* Location: ./system/application/libraries/dao/Interface_product_video_dao.php */