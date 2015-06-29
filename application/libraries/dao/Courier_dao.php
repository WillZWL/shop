<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Courier_dao extends Base_dao {
    private $table_name="courier";
    private $vo_class_name="Courier_vo";
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

    public function get_list_w_name($where=array(), $option=array(), $classname="")
    {

        $this->db->from('courier AS c');
        $this->db->join('courier_region AS crc', 'c.id = crc.courier_id', 'INNER');
        $this->db->join('region AS r', 'crc.region_id = r.id', 'LEFT');

        if (!empty($where["courier_id"]))
        {
            $this->db->where('c.id', $where["courier_id"]);
        }

        if (empty($option["orderby"]))
        {
            $option["orderby"] = "crc.region_id ASC";
        }

        if (empty($option["num_rows"]))
        {

            $this->include_dto($classname);

            $this->db->select('crc.courier_id, crc.region_id, r.region_name');

            $this->db->order_by($option["orderby"]);

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
                foreach ($query->result($classname) as $obj)
                {
                    $rs[] = $obj;
                }
                return (object) $rs;
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


    public function get_region_country_list($where=array(), $option=array(), $classname="")
    {

        $this->db->from('courier_region AS crc');
        $this->db->join('region_country AS rc', 'crc.region_id = rc.region_id', 'LEFT');
        $this->db->join('region AS r', 'rc.region_id = r.id', 'LEFT');
        $this->db->join('country c', 'rc.country_id = c.id', 'LEFT');
        $this->db->group_by(array("crc.courier_id", "r.region_name"));

        if (!empty($where["courier_id"]))
        {
            $this->db->where('crc.courier_id', $where["courier_id"]);
        }

        if (empty($option["orderby"]))
        {
            $option["orderby"] = "r.region_name ASC";
        }

        if (empty($option["num_rows"]))
        {

            $this->include_dto($classname);

            $this->db->select('crc.courier_id, r.region_name, GROUP_CONCAT(c.name ORDER BY c.name SEPARATOR ", ") AS countries', FALSE);

            $this->db->order_by($option["orderby"]);

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
                foreach ($query->result($classname) as $obj)
                {
                    $rs[] = $obj;
                }
                return (object) $rs;
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

/* End of file courier_dao.php */
/* Location: ./system/application/libraries/dao/Courier_dao.php */