<?php
namespace ESG\Panther\Dao;

class SupplierProdDao extends BaseDao
{
    private $table_name = 'supplier_prod';
    private $vo_class_name = 'SupplierProdVo';

    public function getVoClassname()
    {
        return $this->vo_class_name;
    }

    public function getTableName()
    {
        return $this->table_name;
    }

    public function getSupplierCostBySkuDate($master_sku, $date)
    {
        $this->db->select('sch.currency_id, sch.cost');
        $this->db->join('sku_mapping map', "map.sku = sch.prod_sku AND map.ext_sys = 'WMS' AND map.status = 1", 'INNER');
        $this->db->from('supplier_cost_history sch');
        $this->db->where(array('sch.order_default'=>1, 'map.ext_sku'=>$master_sku, "sch.modify_on <= '" . $date . " 00:00:00'"=>null));
        $this->db->order_by('sch.modify_on DESC');
        $this->db->limit(1);
        if ($query = $this->db->get())
        {
            return (array)$query->row();
        }
        return false;
    }

    public function getCurrentSupplierCost($master_sku)
    {
        $this->db->select('sp.currency_id, sp.cost');
        $this->db->join('sku_mapping map', "map.sku = sp.prod_sku AND map.ext_sys = 'WMS' AND map.status = 1", 'INNER');
        $this->db->from('supplier_prod sp');
        $this->db->where(array('sp.order_default'=>1, 'map.ext_sku'=>$master_sku));
        $this->db->limit(1);
        if ($query = $this->db->get())
        {
            return (array)$query->row();
        }
        return false;
    }

    public function getSupplierProdListWithName($where = array(), $option = array(), $classname = "SupplierProdWithNameDto")
    {

        $this->db->from('supplier_prod AS sp');
        $this->db->join('(supplier AS s)', 'sp.supplier_id = s.id', 'LEFT');

        if (isset($option["to_currency"])) {
            $this->db->join('exchange_rate er', 'sp.currency_id = er.from_currency_id AND er.to_currency_id = "' . $option["to_currency"] . '"', 'LEFT');
        }
        $this->db->where("s.status = 1");
        $this->db->where($where);

        if (empty($option["num_rows"])) {
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
}
