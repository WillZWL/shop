<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Delivery_dao extends Base_dao
{
    private $table_name="delivery";
    private $vo_class_name="Delivery_vo";
    private $seq_name="";
    private $seq_mapping_field="";

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

    public function get_latency($delivery_type_id, $country_id)
    {
        $sql = "
                SELECT GREATEST(COALESCE(d.max_day,0),COALESCE(d.min_day,0)) AS latency
                FROM delivery AS d
                WHERE d.delivery_type_id = ?
                    AND d.country_id = ?
                LIMIT 1
                ";
        if ($query = $this->db->query($sql, array($delivery_type_id, $country_id)))
        {
            return $query->row()->latency;
        }
        else
        {
            return FALSE;
        }
    }
/*
    public function get_list_w_country($where=array(), $option=array())
    {

        $this->db->from('delivery AS d');
        $this->db->join('region AS r', 'd.region_id = r.id', 'INNER');
        $this->db->join('region_country AS rc', 'r.id = rc.region_id', 'INNER');

        if ($where)
        {
            $this->db->where($where);
        }

        if (empty($option["num_rows"]))
        {

            $this->include_vo();

            $this->db->select('d.*');

            if (isset($option["orderby"]))
            {
                $this->db->order_by($option["orderby"]);
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
                return $rs?($option["limit"] == 1?$rs[0]:(object)$rs):$rs;
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
*/
}

/* End of file delivery_dao.php */
/* Location: ./system/application/libraries/dao/Delivery_dao.php */