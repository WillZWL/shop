<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Interface_so_dao extends Base_dao
{
    private $table_name="interface_so";
    private $vo_class_name="Interface_so_vo";
    private $seq_name="int_customer_order";
    private $seq_mapping_field="so_no";

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

    public function get_not_complete($batch_id)
    {
        $sql =  "
                SELECT COUNT(*) AS total
                FROM interface_so
                WHERE batch_id = ?
                AND batch_status <> 'S'
                ";

        if ($query = $this->db->query($sql, $batch_id))
        {
            return $query->row()->total;
        }
        return TRUE;
    }

}

/* End of file so_dao.php */
/* Location: ./system/application/libraries/dao/So_dao.php */