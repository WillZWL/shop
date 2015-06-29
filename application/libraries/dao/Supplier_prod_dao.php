<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Supplier_prod_dao extends Base_dao
{
    private $table_name = "supplier_prod";
    private $vo_class_name = "Supplier_prod_vo";
    private $seq_name = "";
    private $seq_mapping_field = "";

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

    public function get_supplier_prod_list_w_name($where = array(), $option = array(), $classname = "Supplier_prod_w_name_dto")
    {

        $this->db->from('supplier_prod AS sp');
        $this->db->join('(supplier AS s)', 'sp.supplier_id = s.id', 'LEFT');

        if (isset($option["to_currency"])) {
            $this->db->join('exchange_rate er', 'sp.currency_id = er.from_currency_id AND er.to_currency_id = "' . $option["to_currency"] . '"', 'LEFT');
        }
        $this->db->where("s.status = 1");
        $this->db->where($where);

        if (empty($option["num_rows"])) {

            $this->include_dto($classname);

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

            if (isset($option["to_currency"])) {
                $this->db->select('sp.*, s.name AS supplier_name, s.origin_country, sp.cost*er.rate AS total_cost', FALSE);
            } else {
                $this->db->select('sp.*, s.name AS supplier_name, s.origin_country, sp.cost AS total_cost');
            }

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

    public function get_supplier_prod_history_for_report($start_time = '',
                                                         $end_time = '', $platform_id = 'WSGB', $classname = 'Product_cost_change_dto')
    {
        $sql = "SELECT vpo.*, scha.cost * scha.rate - schb.cost * schb.rate cost_diff,
                    CASE schb.cost
                        WHEN NULL THEN NULL
                        ELSE (scha.cost * scha.rate - schb.cost * schb.rate) * 100 / (schb.cost * schb.rate)
                    END pcent_chg, a.inventory, 0 is_new
                FROM supplier_cost_history scha
                INNER JOIN
                    (SELECT tsch.prod_sku, tsch.create_on, SUM(inv.inventory) inventory
                    FROM inventory inv
                    INNER JOIN (SELECT sch1.prod_sku, MAX(sch1.create_on) create_on
                        FROM supplier_cost_history sch1
                        WHERE sch1.create_on >= ? AND sch1.create_on <= ?
                        GROUP BY sch1.prod_sku) tsch
                    ON (inv.prod_sku = tsch.prod_sku)
                    GROUP BY tsch.prod_sku, tsch.create_on) a
                    ON (scha.prod_sku = a.prod_sku AND scha.create_on = a.create_on)
                LEFT JOIN
                    (SELECT sch2.prod_sku, MAX(sch2.create_on) create_on
                    FROM supplier_cost_history sch2
                    WHERE sch2.create_on < ?
                    GROUP BY sch2.prod_sku) b
                    ON (b.prod_sku = scha.prod_sku)
                LEFT JOIN supplier_cost_history schb
                    ON (schb.prod_sku = b.prod_sku AND schb.create_on = b.create_on)
                INNER JOIN v_prod_overview vpo
                    ON (vpo.sku = scha.prod_sku AND vpo.platform_id = ?)";

        $resultp = $this->db->query($sql, array($start_time, $end_time, $start_time, $platform_id));

        $array = $resultp->result_array();

        $this->include_dto($classname);
        $result_arr = array();
        include_once APPPATH . "helpers/object_helper.php";
        $dto = new $classname;

        foreach ($array as $row) {
            $obj = clone $dto;
            set_value($obj, $row);
            $result_arr[] = $obj;
        }

        return $result_arr;
    }

    public function get_supplier_cost_by_sku_date($sku, $date)
    {
        $this->db->select('sch.currency_id, sch.cost');
        $this->db->join('sku_mapping map', "map.sku = sch.prod_sku AND map.ext_sys = 'WMS' AND map.status = 1", 'INNER');
        $this->db->from('supplier_cost_history sch');
        $this->db->where(array('sch.order_default' => 1, 'map.ext_sku' => $sku, "sch.modify_on <= '" . $date . " 00:00:00'" => null));
        $this->db->orderby('sch.modify_on DESC');
        $this->db->limit(1);

        if ($query = $this->db->get()) {
            return (array)$query->row();
        }

        return false;
    }

    public function get_current_supplier_cost($sku)
    {
        $this->db->select('sp.currency_id, sp.cost');
        $this->db->join('sku_mapping map', "map.sku = sp.prod_sku AND map.ext_sys = 'WMS' AND map.status = 1", 'INNER');
        $this->db->from('supplier_prod sp');
        $this->db->where(array('sp.order_default' => 1, 'map.ext_sku' => $sku));
        $this->db->limit(1);

        if ($query = $this->db->get()) {
            return (array)$query->row();
        }

        return false;
    }

}

/* End of file supplier_prod_dao.php */
/* Location: ./system/application/libraries/dao/Supplier_prod_dao.php */