<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Ls_transactions_dao extends Base_dao
{
    private $table_name = "ls_transactions";
    private $vo_class_name = "Ls_transactions_vo";
    private $seq_name = "";
    private $seq_mapping_field = "";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_lstrans_list($where = array(), $option = array(), $classname = "Lstrans_dto")
    {
        $this->db->from('ls_transactions AS lst');
        $this->db->join('so', 'lst.so_no = so.so_no', 'LEFT');
        $this->db->join('so_extend AS soe', 'lst.so_no = soe.so_no', 'LEFT');
        $this->db->join('so_payment_status AS sops', 'lst.so_no = sops.so_no', 'LEFT');
        $this->db->join('client AS c', 'so.client_id = c.id', 'LEFT');
        $this->db->join('product AS p', 'lst.item_sku = p.sku', 'LEFT');

        if ($where) {
            $this->db->where($where);
        }

        if (empty($option["num_rows"])) {

            $this->include_dto($classname);

            $this->db->select('lst.*, so.delivery_postcode, soe.conv_site_ref, soe.ls_time_entered, sops.pay_date, c.email, p.name AS prod_name');

            if (isset($option["orderby"])) {
                $this->db->order_by($option["orderby"]);
            }

            if (empty($option["limit"])) {
                $option["limit"] = $this->rows_limit;
            } elseif ($option["limit"] == -1) {
                $option["limit"] = "";
            }

            if (!isset($option["offset"])) {
                $option["offset"] = 0;
            }

            if ($this->rows_limit != "") {
                $this->db->limit($option["limit"], $option["offset"]);
            }

            $rs = array();

            if ($query = $this->db->get()) {
                foreach ($query->result($classname) as $obj) {
                    $rs[] = $obj;
                }
                return (object)$rs;
            }

        } else {
            $this->db->select('COUNT(*) AS total');
            if ($query = $this->db->get()) {
                return $query->row()->total;
            }
        }

        return FALSE;
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
}

/* End of file ls_transactions_dao.php */
/* Location: ./system/application/libraries/dao/Ls_transactions_dao.php */