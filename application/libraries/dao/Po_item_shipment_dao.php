<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

Class Po_item_shipment_dao extends Base_dao
{
    private $table_name="po_item_shipment";
    private $vo_class_name="Po_item_shipment_vo";
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

    public function get_item_list($po_number, $line_number, $to_location)
    {
        $classname = "Shipment_item_dto";

        $sql = "SELECT s.sid,s.po_number, s.line_number, s.qty, s.to_location, s.reason_code, p.status, p.create_on, p.create_by
                FROM po_item_shipment s
                JOIN supplier_shipment p
                    ON p.shipment_id = s.sid
                WHERE s.po_number = ?
                AND s.line_number = ?
                AND s.to_location = ?
                ORDER BY sid ASC";

        $this->include_dto($classname);
        $rs = array();

        if($query = $this->db->query($sql, array($po_number,$line_number,$to_location)))
        {
            foreach ($query->result($classname) as $obj)
            {
                $rs[] = $obj;
            }
            return (object) $rs;
        }
        else
        {
            return FALSE;
        }
    }

    public function get_shipment_count($po_number)
    {
        if($po_number == "")
        {
            return FALSE;
        }

        $this->db->from($this->get_table_name());

        $this->db->where("po_number",$po_number);

        $this->db->select("COUNT(DISTINCT(sid)) as total",FALSE);

        if($query = $this->db->get())
        {
            return $query->row()->total;
        }
        return FALSE;
    }
}

/* End of file purchase_order_item_shipment_dao.php */
/* Location: ./system/application/libraries/dao/Purchase_order_item_shipment_dao.php */
?>