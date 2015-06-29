<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Flex_so_fee_dao extends Base_dao
{
    private $table_name="flex_so_fee";
    private $vo_class_name="Flex_so_fee_vo";
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

    public function get_so_fee_invoice($where, $classname="so_fee_invoice_dto")
    {
        $option['limit'] = -1;
        $this->db->from("flex_so_fee fsf");
        $this->db->join("flex_gateway_mapping fgm", "fgm.gateway_id = fsf.gateway_id AND fgm.currency_id = fsf.currency_id", "INNER");
        $this->db->join("so", "so.so_no = fsf.so_no", "INNER");

        $this->db->join("(
                            SELECT so_no, count(1) AS qty
                            FROM so_item_detail soid
                            GROUP BY so_no
                            )
                            a", "a.so_no = fsf.so_no", "INNER");
        $this->db->orderby("fsf.flex_batch_id, fsf.txn_time");
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, 'fsf.so_no AS so_no, fsf.status AS type, fsf.txn_time, fsf.currency_id AS currency, fgm.gateway_code AS gateway_id, fsf.flex_batch_id AS batch_id, a.qty, so.amount as order_amount, fsf.amount AS fee, fsf.txn_id AS txn_ref');
    }
}

/* End of file flex_so_fee_dao.php */
/* Location: ./system/application/libraries/dao/Flex_so_fee_dao.php */
