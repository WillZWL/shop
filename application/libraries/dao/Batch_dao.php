<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Batch_dao extends Base_dao
{
    private $table_name="batch";
    private $vo_class_name="Batch_vo";
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

    public function get_batch_list($where=array(), $option=array(), $classname="Batch_dto")
    {

        $this->db->from('batch AS b');
        $this->db->join('(
                        SELECT id, TIMEDIFF(end_time, create_on) AS duration
                        FROM batch
                        ) AS bd', 'b.id = bd.id', 'INNER');

        $this->db->where($where);

        if (empty($option["num_rows"]))
        {

            $this->db->select('b.*, bd.duration');

            $this->include_dto($classname);

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

    public function get_batch_status_for_order($batch_id = "")
    {
        if($batch_id == "")
        {
            return FALSE;
        }

        $sql = " SELECT a.batch_id, COUNT(a.batch_id) as complete, b.total
             FROM interface_so a
             JOIN (SELECT batch_id, COUNT(batch_id) as total
                FROM interface_so
                WHERE batch_id = ?
                GROUP BY batch_id) AS b
                ON b.batch_id = a.batch_id
             WHERE a.batch_status = 'S'
             AND a.batch_id = ?
             GROUP BY a.batch_id
             LIMIT 1
            ";

        if($query = $this->db->query($sql,array($batch_id,$batch_id)))
        {

            return array("completed"=>$query->row()->complete,"total"=>$query->row()->total);
        }
        return FALSE;
    }

}

/* End of file batch_dao.php */
/* Location: ./system/application/libraries/dao/Batch_dao.php */
