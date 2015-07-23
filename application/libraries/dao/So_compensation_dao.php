<?php

include_once 'Base_dao.php';

class So_compensation_dao extends Base_dao
{
    private $table_name = "so_compensation";
    private $vo_classname = "So_compensation_vo";
    private $seq_name = "";
    private $seq_mapping_field = "";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_table_name()
    {
        return $this->table_name;
    }

    public function get_vo_classname()
    {
        return $this->vo_classname;
    }

    public function get_seq_name()
    {
        return $this->seq_name;
    }

    public function get_seq_mapping_field()
    {
        return $this->seq_mapping_field;
    }

    public function get_orders_eligible_for_compensation($where = array(), $option = array(), $classname = "Compensation_order_dto")
    {
        $this->db->from("so");
        $this->db->where(array("so.status = 3" => null, "so.refund_status" => 0, "so.hold_status" => 0));
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, "so.*");
    }

    public function get_compensation_so_list($where = array(), $option = array(), $classname = "Request_order_compensation_dto")
    {
        $this->db->from("so");
        $this->db->join("so_compensation AS cp", "so.so_no = cp.so_no", "INNER");
        $this->db->join("(
                            SELECT * FROM
                            (
                                SELECT compensation_id, so_no, note, create_on
                                FROM so_compensation_history cph
                                WHERE status = 1
                                ORDER BY create_on DESC
                            )a
                            GROUP BY a.so_no
                         )cph", "so.so_no = cph.so_no AND cp.id = cph.compensation_id", "INNER");
        $this->db->join("product AS p", "p.sku = cp.item_sku", "INNER");
        $this->db->where(array("so.hold_status" => 1, "cp.status" => 1));
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, "cp.id compensation_id, so.so_no, so.platform_id, cp.item_sku, p.name prod_name, cph.note, cph.create_on request_on");
    }

    public function get_order_compensated_item($where = array(), $option = array(), $classname = "Compensation_item_dto")
    {
        $this->db->from("so");
        $this->db->join("so_compensation AS cp", "so.so_no = cp.so_no", "INNER");
        $this->db->join("product AS p", "p.sku = cp.item_sku", "INNER");
        $this->db->join("price AS pr", "pr.sku = p.sku and pr.platform_id=so.platform_id", "LEFT");
        $this->db->where(array("cp.status" => 1));
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, "so.so_no, so.platform_id, cp.item_sku, p.name prod_name, so.currency_id, pr.price");
    }
}


