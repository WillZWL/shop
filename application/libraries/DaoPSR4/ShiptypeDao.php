<?php
namespace ESG\Panther\Dao;

class ShiptypeDao extends BaseDao
{
    private $tableName = "shiptype";
    private $voClassName = "ShiptypeVo";

    public function __construct()
    {
        parent::__construct();
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getVoClassname()
    {
        return $this->voClassName;
    }

    public function getProductShiptype($where = [], $option = [], $classname = "ProductCostDto")
    {

        $this->db->from('v_prod_st_w_price');

        $this->db->where($where);

        if (!empty($option["group_by"])) {
            $this->db->group_by("sku");
        }

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

            if ($query = $this->db->get()) {
                foreach ($query->result($classname) as $obj) {
                    $rs[] = $obj;
                }
                if ($option["limit"] == 1) {
                    return $rs[0];
                } else {
                    return (object)$rs;
                }
            }

        } else {
            $this->db->select('COUNT(*) AS total');
            if ($query = $this->db->get()) {
                return $query->row()->total;
            }
        }

        return FALSE;
    }

    public function getFreightRegProdShiptype($shiptype, $region_id, $sku)
    {
        $sql = "
                SELECT COALESCE(ffcc.amount*ffccer.rate, 0) AS freight_cost
                FROM product AS p
                LEFT JOIN (sub_cat_platform_var AS scpv, custom_classification AS cc, shiptype AS st, platform_biz_var AS pbv)
                    ON (p.sub_cat_id = scpv.sub_cat_id AND scpv.custom_class_id = cc.id AND st.platform_id = scpv.platform_id AND st.id = ? AND st.platform_id = pbv.selling_platform_id)
                LEFT JOIN freight_category AS fc
                    ON (p.freight_cat_id = fc.id)
                LEFT JOIN (freight_cat_charge AS ffcc, exchange_rate AS ffccer)
                    ON (st.courier_id = ffcc.courier_id AND ffcc.region_id = ? AND fc.id = ffcc.fcat_id AND ffcc.currency_id = ffccer.from_currency_id AND pbv.platform_currency_id = ffccer.to_currency_id)
                WHERE p.sku = ?
                ";

        if ($query = $this->db->query($sql, array($shiptype, $region_id, $sku))) {
            return $query->row()->freight_cost;
        } else {
            return FALSE;
        }
    }

    public function getPlatformShiptypeList($platform_type = 'WEBSITE')
    {
        $this->include_vo();
        $option = array("limit" => -1);
        $this->db->from("shiptype st");
        $this->db->join("platform_shiptype pst", "st.id = pst.shiptype_id AND pst.platform_type = '$platform_type'", "INNER");
        $this->db->where(array("pst.status" => 1));
        $this->include_vo();
        $classname = $this->getVoClassname();

        return $this->common_get_list($where, $option, $classname, 'st.*');
    }
}


