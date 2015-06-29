<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class So_allocate_dao extends Base_dao
{
    private $table_name="so_allocate";
    private $vo_class_name="So_allocate_vo";
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

    public function get_allocate_list($where=array(), $option=array(), $classname="So_list_w_name_dto")
    {

        $jsql = '(
                        SELECT sal.so_no, sal.sh_no, IF(COUNT(sal.item_sku)>1 OR SUM(sal.qty)>1, "Y", "N") AS multiple, SUM(t_qty) AS t_qty, GROUP_CONCAT(CONCAT_WS("::", sal.item_sku, p.name, CAST(sal.t_qty AS CHAR)) ORDER BY sal.line_no SEPARATOR "||") AS items, warehouse_id
                        FROM (
                            SELECT *, SUM(qty) AS t_qty
                            FROM so_allocate
                            GROUP BY so_no, line_no, item_sku
                            ) AS sal
                        LEFT JOIN product AS p
                            ON (sal.item_sku = p.sku)
                ';

        if ($option["list_type"] == "toship")
        {
            $select_str = "so.*, so.delivery_type_id AS delivery_courier, soal.multiple, soal.items, soal.warehouse_id";
            $this->db->from('so');
            $jsql .= 'WHERE sal.status=1
                        GROUP BY so_no, warehouse_id
                    ) AS soal
                ';
            $this->db->join($jsql, 'so.so_no = soal.so_no', 'INNER');
        }
        elseif ($option["list_type"] == "dispatch")
        {
            $select_str = "so.so_no, so.platform_id, so.order_create_date, so.currency_id, so.amount, so.delivery_name, so.delivery_postcode, so.delivery_country_id, sosh.courier_id, sosh.tracking_no, soal.multiple, soal.items, soal.warehouse_id, soal.sh_no";
            $this->db->from('so_shipment AS sosh');
            $jsql .= 'WHERE sal.status=2
                        GROUP BY sh_no
                    ) AS soal
                ';
            $this->db->join($jsql, 'sosh.sh_no = soal.sh_no', 'INNER');
            $this->db->join('so', 'so.so_no = soal.so_no', 'INNER');
        }

        if (!$option["hide_client"])
        {
            $this->db->join('(
                            SELECT cl.id, cl.forename AS client_name
                            FROM client AS cl
                            ) AS c', 'c.id = so.client_id', 'INNER');
            $select_str .=", c.client_name";
        }

        if ($option["notes"])
        {
            $this->db->join('(
                                    SELECT so_no, note, MAX(create_on) AS mco
                                    FROM order_notes
                                    GROUP BY so_no
                            ) AS so_n', 'so_n.so_no = so.so_no', 'LEFT');
            $select_str .= ", so_n.note";
        }

        if (!$option["hide_payment"])
        {
            $this->db->join('so_payment_status AS sops', 'sops.so_no = so.so_no', 'LEFT');
            $select_str .= ", sops.payment_gateway_id";
        }

        if ($where)
        {
            $this->db->where($where);
        }

        if (empty($option["num_rows"]) && empty($option["total_items"]))
        {

            $this->db->select($select_str);

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
            $this->db->select(($option["num_rows"]?'COUNT(*)':'SUM(soal.t_qty)').' AS total');
            if ($query = $this->db->get())
            {
                return $query->row()->total;
            }
        }
        return FALSE;
    }

    public function get_allocate_list_by_item_sku($where=array(), $option=array(), $classname="So_list_w_name_dto")
    {
        $this->db->from('so');
        $this->db->join("(
                            SELECT *, SUM(qty) AS t_qty
                            FROM so_allocate
                            GROUP BY so_no, line_no, item_sku
                        ) AS soal", 'so.so_no = soal.so_no', 'INNER');

//      $select_str = "so.*, so.delivery_type_id AS delivery_courier";
        $select_str = "so.so_no, so.platform_id, so.order_create_date, so.delivery_name, so.currency_id, so.amount, so.delivery_postcode, so.delivery_country_id";

        if (empty($option["num_rows"]))
        {
            $this->db->join('product AS p', 'soal.item_sku = p.sku', 'LEFT');
            $select_str .= ", CONCAT_WS('::', soal.item_sku, p.name, CAST(soal.t_qty AS CHAR)) AS items";
        }

        if ($option["list_type"] == "toship")
        {
            $select_str .= ", soal.warehouse_id";
            $this->db->where("soal.status = 1");
        }
        elseif ($option["list_type"] == "dispatch")
        {
//          $select_str .= ", sosh.courier_id, sosh.tracking_no, soal.sh_no, soal.warehouse_id";
            $select_str .= ", sosh.courier_id, sosh.tracking_no, soal.sh_no, soal.warehouse_id";
            $this->db->join('so_shipment AS sosh', 'soal.sh_no = sosh.sh_no', 'INNER');
            $this->db->where("soal.status = 2");
        }


        if (empty($option["num_rows"]) || isset($where["multiple"]))
        {
            $this->db->join("(
                            SELECT so_no, IF(COUNT(item_sku)>1 OR SUM(qty)>1, 'Y', 'N') AS multiple
                            FROM so_allocate
                            GROUP BY so_no
                            ) AS sal", 'so.so_no = sal.so_no', 'INNER');
            $select_str .= ", sal.multiple";
        }

        if ($option["notes"])
        {
            $this->db->join('(
                                    SELECT so_no, note, MAX(create_on) AS mco
                                    FROM order_notes
                                    GROUP BY so_no
                            ) AS so_n', 'so_n.so_no = so.so_no', 'LEFT');
            $select_str .= ", so_n.note";
        }

        if (!$option["hide_payment"])
        {
            $this->db->join('so_payment_status AS sops', 'sops.so_no = so.so_no', 'LEFT');
            $select_str .= ", sops.payment_gateway_id";
        }

        if ($where)
        {
            $this->db->where($where);
        }

        if (empty($option["num_rows"]) && empty($option["total_items"]))
        {

            $this->db->select($select_str, FALSE);

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
            $this->db->select(($option["num_rows"]?'COUNT(*)':'SUM(soal.t_qty)').' AS total');
            if ($query = $this->db->get())
            {
                return $query->row()->total;
            }
        }
        return FALSE;
    }

    public function get_in_so_list($where, $option)
    {

        $this->db->from('so_allocate AS soal');
        $this->db->join('so', 'so.so_no = soal.so_no', 'INNER');

        if ($where)
        {
            $this->db->where($where);
        }

        if ($option["solist"] != "")
        {
            $this->db->where_in("soal.so_no", $option["solist"]);
        }

        if ($option["shlist"] != "")
        {
            $this->db->where_in("sh_no", $option["shlist"]);
        }

        if (empty($option["num_rows"]))
        {

            $this->db->select('soal.*', FALSE);

            $this->include_vo();

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

    public function get_last_sh_no($so_no)
    {
        $sql  = "
                SELECT MAX(sh_no) AS last_sh_no
                FROM so_allocate
                WHERE so_no = ?
                ";

        if ($query = $this->db->query($sql, $so_no))
        {
            return $query->row()->last_sh_no;
        }
        else
        {
            return FALSE;
        }
    }

    public function get_awaiting_shipment_info($classname = 'So_awaiting_shipment_dto')
    {
        $sql = "SELECT soa.item_sku AS ext_ref_sku ,soa.item_sku AS log_sku ,SUM(soa.qty) AS qty,m.ext_sku AS sku
                FROM so_allocate soa
                LEFT JOIN sku_mapping m
                    ON m.sku = soa.item_sku AND m.ext_sys = 'WMS' AND m.status = 1
                WHERE soa.status = 1
                GROUP BY m.ext_sku,soa.item_sku
                ";

        $this->include_dto($classname);
        $result = $this->db->query($sql);

        if (!$result)
        {
            return FALSE;
        }

        foreach ($result->result("object", $classname) as $obj)
        {
            $array[] = $obj;
        }
        return $array;

    }

    public function get_purchaser_feed_list($where=array(), $option=array(), $classname="Purchaser_feed_dto")
    {
        $this->db->from('so_allocate AS soal');
        $this->db->join('so_item_detail AS soid', 'soal.so_no = soid.so_no AND soal.line_no = soid.line_no AND soal.item_sku = soid.item_sku AND soal.status = 3', 'INNER');
        $this->db->join('so', 'soal.so_no = so.so_no', 'LEFT');
        $this->db->join('client AS c', 'so.client_id = c.id', 'LEFT');

        if ($where)
        {
            $this->db->where($where);
        }

        if (empty($option["num_rows"]))
        {

            $this->include_dto($classname);

            $this->db->select('c.title, c.surname, c.email, so.order_create_date, so.client_id, so.so_no, so.delivery_postcode, soid.item_sku, so.currency_id, ROUND(soid.amount/soid.qty, 2) AS price, c.forename, so.delivery_country_id, soid.qty', FALSE);

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

    public function get_tracking_info($so_no)
    {
        $sql = "SELECT soal.so_no, sosh.courier_id, c.tracking_link, sosh.tracking_no
                FROM so_allocate soal
                LEFT JOIN so_shipment sosh
                    ON (soal.sh_no = sosh.sh_no)
                LEFT JOIN courier c
                    ON (sosh.courier_id = c.id)
                WHERE soal.so_no = ?
                LIMIT 1
                ";

        if ($result = $this->db->query($sql, $so_no))
        {
            return $result->row_array();
        }

        return FALSE;
    }

    public function get_integrated_allocate_list($where=array(), $option=array(), $classname="So_list_w_name_dto")
    {
        if ($option["list_type"] == "toship")
        {
            $select_str = "iof.*,  `soal`.`warehouse_id`, soal.qty, sm.ext_sku as master_sku";
            $this->db->from('integrated_order_fulfillment as iof');
            $this->db->join('so_allocate as soal', 'iof.so_no = soal.so_no and iof.line_no = soal.line_no and iof.sku = soal.item_sku and soal.`status` = 1', 'INNER');
            /*$this->db->join('inventory as inv', 'iof.sku=inv.prod_sku and soal.warehouse_id=inv.warehouse_id');*/
            $this->db->join('sku_mapping sm',"sm.sku = iof.sku and sm.ext_sys='WMS'",'INNER');
        }

        elseif ($option["list_type"] == "dispatch")
        {
            $select_str = "iof.*, sosh.create_on as packing_date, sosh.courier_id, sosh.tracking_no, soal.warehouse_id, soal.sh_no, soal.qty, sm.ext_sku as master_sku";
            $this->db->from('so_shipment AS sosh');
            $this->db->join('so_allocate as soal', 'sosh.sh_no = soal.sh_no and soal.`status`=2', 'LEFT');
            $this->db->join('integrated_order_fulfillment as iof', 'soal.so_no=iof.so_no and soal.line_no=iof.line_no and soal.item_sku=iof.sku', 'INNER');
            $this->db->join('sku_mapping sm',"sm.sku = iof.sku and sm.ext_sys='WMS'",'INNER');
        }

        if (strpos($option['orderby'], 'product_name_ref') !== FALSE)
        {
            $this->db->join('(SELECT so_no AS so_no_ref, product_name AS product_name_ref FROM integrated_order_fulfillment WHERE line_no = 1 GROUP BY so_no, line_no) AS iof_ref', 'iof_ref.so_no_ref = iof.so_no');
        }
        if ($where)
        {
            $this->db->where($where);
        }
        //var_dump($this->db->last_query());
        if (empty($option["num_rows"]) && empty($option["total_items"]))
        {

            $this->db->select($select_str);

            $this->include_dto($classname);

            # ORDER BY so_no sequence is important. if not will cause display problem
            if (isset($option["orderby"]))
            {
                if(strpos($option["orderby"], "so_no") === false)
                {
                    $this->db->order_by($option["orderby"]);
                    $this->db->order_by("so_no");
                }
                else
                    $this->db->order_by($option["orderby"]);
            }
            else
            {
                $this->db->order_by("so_no");
            }
            $this->db->order_by("iof.line_no");
            $this->db->order_by("iof.split_so_group desc");

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
            $this->db->select(($option["num_rows"]?'COUNT(distinct(iof.so_no))':'COALESCE(SUM(soal.qty),0)').' AS total');
            if ($query = $this->db->get())
            {
                return $query->row()->total;
            }
        }
        return FALSE;
    }
}

/* End of file so_allocate_dao.php */
/* Location: ./system/application/libraries/dao/So_allocate_dao.php */