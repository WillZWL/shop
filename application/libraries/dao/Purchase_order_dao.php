<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

Class Purchase_order_dao extends Base_dao
{
    private $table_name="purchase_order";
    private $vo_class_name="Purchase_order_vo";
    private $seq_name="purchase_order";
    private $seq_mapping_field="po_number";

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

    public function get_complete_status($po_number)
    {
        if($po_number == "")
        {
            return FALSE;
        }
        else
        {
            $sql = "SELECT distinct(z.po_number), IFNULL(y.completed,0) AS completed,IFNULL(x.total,0) AS total
                    FROM po_item_shipment z
                    JOIN(
                        SELECT a.po_number,count(distinct(a.line_number)) AS total
                        FROM po_item_shipment a
                        GROUP BY po_number
                        ) AS x
                        ON x.po_number = z.po_number
                    LEFT JOIN
                        (
                        SELECT a.po_number,count(distinct(a.line_number)) AS completed
                        FROM po_item_shipment a
                        JOIN supplier_shipment s
                            ON s.shipment_id = a.sid
                            AND s.status = 'C'
                        GROUP BY po_number
                        ) AS y
                        ON y.po_number = z.po_number
                    WHERE z.po_number = ?
                    GROUP BY z.po_number
                    LIMIT 1";

            $rs = array();

            if($query = $this->db->query($sql,$po_number))
            {
                foreach ($query->result($classname) as $obj)
                {
                    return array("total"=>$obj->total,"completed"=>$obj->completed);
                }
            }
            else
            {
                return false;
            }
        }
    }

    public function get_list_index($where=array(),$option=array(),$classname="")
    {

        $status_where = "WHERE u.status = 'A'";
        if($where["status"] == "CL")
        {
            $status_where = "";
        }

        $sql = "
                FROM purchase_order po
                LEFT JOIN (SELECT pm.po_number, GROUP_CONCAT(CONCAT_WS('::',pm.message,u.username,pm.create_on) ORDER BY pm.create_on DESC SEPARATOR '||') AS po_message
                            FROM po_message pm
                            JOIN user u
                                ON u.id = pm.create_by
                            GROUP BY pm.po_number) as msg
                    ON msg.po_number = po.po_number
                JOIN currency c
                    ON c.id = po.currency
                JOIN supplier s
                    ON po.supplier_id = s.id";

        $query = array();

        if (!empty($where["po_number"]))
        {
            $query[] = " po.po_number LIKE '%".$where["po_number"]."%' ";
        }

        if (!empty($where["supplier"]))
        {
            $query[] = " s.name LIKE '%".$where["supplier"]."%' ";
        }

        if (!empty($where["supplier_invoice_number"]))
        {
            $query[] = " po.supplier_invoice_number LIKE '%".$where["supplier_invoice_number"]."%' ";
        }

        if (!empty($where["delivery_mode"]))
        {
            $query[] = " po.delivery_mode = '".$where["delivery_mode"]."' ";
        }

        if (!empty($where["status"]))
        {
            $query[] = " po.status = '".$where["status"]."' ";
        }

        if(!empty($where["eta"]))
        {
            $query[] = "po.eta = '".$where["eta"]."'";
        }

        if(!empty($where["status <>"]))
        {
            $query[] = "po.status <> 'CL'";
        }

        if (!empty($option["orderby"]))
        {
            $sort["orderby"] = " ORDER BY ".$option["orderby"];
        }
        else
        {
            $sort["orderby"] = " ORDER BY po.po_number ASC ";
        }

        $sql .= (count($query)?" WHERE ".implode(" AND ",$query):"");

        if (empty($option["num_rows"]))
        {

            $this->include_dto($classname);

            if (empty($option["limit"]))
            {
                $option["limit"] = $this->rows_limit;
            }

            elseif ($option["limit"] == -1)
            {
                $option["limit"] = "";
            }

            if (empty($option["offset"]))
            {
                $option["offset"] = 0;
            }

            if ($this->rows_limit != "")
            {
                $order = ' LIMIT '.$option["offset"].','.$option["limit"].' ';
            }

            $sql .= " ".$sort["orderby"]." ".$order;


            $rs = array();

            $selete_str = "SELECT po.*, s.name as supplier_name, c.name as currency_name, msg.po_message";

            $sql = $selete_str.$sql;

            if ($query = $this->db->query($sql))
            {

                foreach ($query->result($classname) as $obj)
                {
                    $obj->set_purchase_detail($this->get_purchse_order_item($obj->get_po_number(), $obj->get_status()));
                    $rs[] = $obj;
                }
                return (object) $rs;
            }
            else
            {
                return FALSE;
            }
        }
        else
        {
            $select_str =  "SELECT COUNT(*) AS total";
            $sql = $select_str.$sql;

            if ($query = $this->db->query($sql))
            {
                return $query->row()->total;
            }
        }
        return FALSE;
    }

    private function get_purchse_order_item($po_number = "", $status="")
    {
        $status_where = "WHERE u.status = 'A' AND u.po_number = '$po_number'";
        if($status == "CL")
        {
            $status_where = "WHERE u.po_number = '$po_number'";
        }

        $sql = "SELECT CONCAT(u.sku,'||',p.name,'||',CAST(u.order_qty AS char),'||',CAST(u.unit_price AS char)) as pinfo
                FROM po_item u
                JOIN product p
                    ON p.sku = u.sku
                $status_where";

        if($query = $this->db->query($sql))
        {
            $tmp = array();
            foreach($query->result() as $line)
            {
                $tmp[] = $line->pinfo;
            }
            return implode("::",$tmp);
        }
        return FALSE;
    }
}

/* End of file purchase_order_dao.php */
/* Location: ./system/application/libraries/dao/Purchase_order_dao.php */
?>