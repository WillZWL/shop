<?php
namespace ESG\Panther\Dao;

class WmsInventoryDao extends BaseDao
{
    private $tableName = "wms_inventory";
    private $voClassName = "WmsInventoryVo";

    public function __construct()
    {
        parent::__construct();
    }

    public function getVoClassname()
    {
        return $this->voClassName;
    }

    public function emptyTable()
    {
        return $this->db->empty_table($this->getTableName());
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function renewInventory($inv = [])
    {
        if (sizeof($inv) > 0) {
            $data = [];
            foreach ($inv as $warehouse_id => $master_sku_list) {
                foreach ($master_sku_list as $master_sku => $qty) {
                    $data[] = "('" . $warehouse_id . "','" . $master_sku . "'," . $qty['inventory'] . "," . $qty['git'] . ")";
                }
            }
            $sql = 'insert into ' . $this->getTableName() . ' (warehouse_id, master_sku, inventory, git) values ' . implode(',', $data) . ';';

            $this->db->trans_start();
            if ($this->db->query($sql)) {
                $this->db->trans_complete();
                return TRUE;
            } else {
                $this->db->trans_rollback();
                return FALSE;
            }
        }

        return TRUE;
    }

    public function getInventoryList($where = [])
    {
        if ($where["sku"] == "") {
            return FALSE;
        }

        $sql = "select w.warehouse_id, coalesce(inv.inventory, 0) as inventory, coalesce(inv.git, 0) as git from wms_warehouse w left join
                (
                    SELECT wms.warehouse_id, SUM(wms.inventory) as inventory, SUM(wms.git) as git
                    FROM wms_inventory wms INNER JOIN sku_mapping map ON wms.master_sku = map.ext_sku AND map.ext_sys = 'WMS' AND map.status = 1
                    WHERE map.sku = ?
                    GROUP BY warehouse_id
                ) inv on w.warehouse_id = inv.warehouse_id
                ORDER BY w.warehouse_id
                ";

        $this->include_vo();

        $rs = [];
        if ($query = $this->db->query($sql, [$where["sku"]])) {
            foreach ($query->result($$this->getVoClassname()) as $obj) {
                $rs[] = $obj;
            }
            return $rs;
        }
        return FALSE;
    }
}
