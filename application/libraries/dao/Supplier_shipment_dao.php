<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

Class Supplier_shipment_dao extends Base_dao
{
    private $table_name = "supplier_shipment";
    private $vo_class_name = "Supplier_shipment_vo";
    private $seq_name = "supplier_shipment";
    private $seq_mapping_field = "shipment_id";

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

    public function get_shipment_information_old($po_number = "", $classname = "Shipment_info_dto")
    {

        if ($po_number == "") {
            return FALSE;
        } else {
            $sql = "SELECT v.sid,v.detail,s.status,s.reason_code as reason, s.remark
                    FROM supplier_shipment s
                    JOIN (SELECT pis.sid, pis.po_number, GROUP_CONCAT(CONCAT(poi.sku,'||',p.name,'||',CAST(pis.create_on as char),'||',CAST(pis.qty as char),'||',CAST(pis.received_qty as char),'||',pis.to_location,'||',pis.reason_code) ORDER BY pis.line_number SEPARATOR '::') as detail
                          FROM po_item_shipment pis
                          JOIN po_item poi
                            ON  poi.po_number = pis.po_number
                            AND poi.line_number = pis.line_number
                          JOIN product p
                            ON p.sku = poi.sku
                          GROUP BY pis.sid) as v
                        ON s.shipment_id = v.sid
                    WHERE v.po_number = ?";

            $this->include_dto($classname);

            $rs = array();

            if ($query = $this->db->query($sql, $po_number)) {

                foreach ($query->result($classname) as $obj) {
                    $rs[] = $obj;
                }
                return (object)$rs;
            } else {
                return FALSE;
            }
        }
    }

    public function get_shipment_information($po_number = "", $classname = "Shipment_info_dto")
    {

        if ($po_number == "") {
            return FALSE;
        } else {
            $sql = "SELECT s.shipment_id as sid, s.status,s.reason_code as reason, s.remark, s.tracking_no, s.courier
                    FROM supplier_shipment s
                    WHERE s.shipment_id LIKE '$po_number%'";

            $this->include_dto($classname);

            $rs = array();

            if ($query = $this->db->query($sql, $po_number)) {

                foreach ($query->result($classname) as $obj) {
                    $tmp = $this->get_shipment_detail($obj->get_sid());
                    if ($tmp === FALSE) {
                        return $tmp;
                    }
                    $obj->set_detail($tmp);
                    $rs[] = $obj;
                    unset($tmp);
                }
                return (object)$rs;
            } else {
                return FALSE;
            }
        }
    }

    private function get_shipment_detail($shipment_id = "")
    {
        if ($shipment_id == "") {
            return FALSE;
        }
        $sql = "SELECT CONCAT(poi.sku,'||',p.name,'||',CAST(pis.create_on as char),'||',CAST(pis.qty as char),'||',CAST(pis.received_qty as char),'||',pis.to_location,'||',IFNULL(pis.reason_code,''),'||',IFNULL(u.username,''),'||',IFNULL(pis.modify_on,''))  as detail
                FROM po_item_shipment pis
                JOIN po_item poi
                    ON  poi.po_number = pis.po_number
                    AND poi.line_number = pis.line_number
                 JOIN product p
                    ON p.sku = poi.sku
                 LEFT JOIN user u
                    ON u.id = pis.modify_by
                WHERE pis.sid = ?
                ";

        if ($query = $this->db->query($sql, $shipment_id)) {
            $tmp = array();
            foreach ($query->result("object") as $obj) {
                $tmp[] = $obj->detail;
            }

            return implode("::", $tmp);
        }
        return FALSE;
    }

    public function get_shipment_csv_info($shipment_id, $classname = "Shipment_csv_dto")
    {

        $sql = "
                SELECT ss.shipment_id, poi.sku, p.name AS prod_name, pois.qty, ss.courier, ss.tracking_no
                FROM supplier_shipment AS ss
                INNER JOIN po_item_shipment AS pois
                    ON ss.shipment_id = pois.sid
                INNER JOIN po_item AS poi
                    ON (pois.po_number = poi.po_number AND pois.line_number = poi.line_number)
                INNER JOIN product AS p
                    ON poi.sku = p.sku
                WHERE ss.shipment_id = ?
            ";

        $this->include_dto($classname);

        $rs = array();

        if ($query = $this->db->query($sql, $shipment_id)) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }
            return (object)$rs;
        } else {
            return FALSE;
        }
    }

}

/* End of file purchase_order_item_shipment_dao.php */
/* Location: ./system/application/libraries/dao/Purchase_order_item_shipment_dao.php */
?>