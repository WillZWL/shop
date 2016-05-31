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
}
