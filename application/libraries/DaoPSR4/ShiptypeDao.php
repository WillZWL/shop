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
        $this->db->from("(
                product p
                LEFT JOIN bundle b ON p.sku = b.prod_sku
                INNER JOIN shiptype st
                JOIN platform_biz_var pbv
        )");

        $this->db->join("freight_category fc", "fc.id = p.freight_cat_id", "LEFT", FALSE);

        $this->db->join("(
                supplier_prod sp
                JOIN supplier s
                JOIN exchange_rate sper
        )", "p.sku = sp.prod_sku AND sp.supplier_id = s.id AND sp.currency_id = sper.from_currency_id AND pbv.platform_currency_id = sper.to_currency_id AND sp.order_default = 1", "LEFT", FALSE);

        $this->db->join("(
                sub_cat_platform_var scpv
                JOIN custom_classification cc
        )", "p.sub_cat_id = scpv.sub_cat_id AND scpv.custom_class_id = cc.id AND pbv.selling_platform_id = scpv.platform_id", "LEFT", FALSE);

        $this->db->join("price pr", "p.sku = pr.sku AND pbv.selling_platform_id = pr.platform_id", "LEFT", FALSE);

        $this->db->join("(
                price dp
                JOIN exchange_rate er
                JOIN config
        )", "p.sku = dp.sku AND config.variable = 'default_platform_id' AND dp.platform_id = config.value AND er.from_currency_id = 'GBP' AND er.to_currency_id = pbv.platform_currency_id", "LEFT", FALSE);

        $select_str = "
            p.sku AS sku,
            p.prod_grp_cd AS prod_grp_cd,
            p.version_id AS version_id,
            p.colour_id AS colour_id,
            p.name AS prod_name,
            pbv.selling_platform_id AS platform_id,
            pbv.platform_region_id AS platform_region_id,
            pbv.platform_country_id AS platform_country_id,
            st.id AS shiptype,
            st.name AS shiptype_name,
            pbv.vat_percent AS vat_percent,
            pbv.payment_charge_percent AS payment_charge_percent,
            coalesce(fc.declared_pcent,100) AS declared_pcent,
            coalesce(cc.duty_pcent,0) AS duty_pcent,
            cc.code AS cc_code,
            cc.description AS cc_desc,
            coalesce(pbv.admin_fee,0) AS admin_fee,
            0 profit,
            0 low_profit,
            0 AS freight_cost,
            0 AS delivery_cost,
            sp.pricehkd,
            coalesce((sp.cost * sper.rate),0) AS supplier_cost,
            sp.modify_on AS purchaser_updated_date,
            0 AS delivery_charge,
            fc.weight AS prod_weight,
            pbv.free_delivery_limit AS free_delivery_limit,
            p.quantity AS quantity,
            p.clearance AS clearance,
            p.website_quantity AS website_quantity,
            p.proc_status AS proc_status,
            p.website_status AS website_status,
            p.sourcing_status AS sourcing_status,
            p.cat_id AS cat_id,
            p.sub_cat_id AS sub_cat_id,
            p.sub_sub_cat_id AS sub_sub_cat_id,
            p.brand_id AS brand_id,
            p.image AS image,
            sp.supplier_id AS supplier_id,
            p.freight_cat_id AS freight_cat_id,
            p.ean AS ean,
            p.mpn AS mpn,
            p.upc AS upc,
            p.status AS prod_status,
            p.display_quantity AS display_quantity,
            p.youtube_id AS youtube_id,
            p.ex_demo AS ex_demo,
            pbv.default_shiptype AS platform_default_shiptype,
            scpv.platform_commission_percent AS platform_commission,
            pbv.platform_currency_id AS platform_currency_id,
            pbv.language_id AS language_id,
            IF((pr.price > 0),pr.price,round((dp.price * er.rate),2)) AS price,
            pr.price AS current_platform_price,
            round((dp.price * er.rate),2) AS default_platform_converted_price,
            pr.platform_code AS platform_code
        ";

        $this->db->select($select_str, FALSE);

        $where["b.prod_sku is null"] = null;

        $this->db->where($where);

        if (!empty($option["group_by"])) {
            $this->db->group_by("sku");
        }

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

            $rs = [];

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

        if ($query = $this->db->query($sql, [$shiptype, $region_id, $sku])) {
            return $query->row()->freight_cost;
        } else {
            return FALSE;
        }
    }

    public function getPlatformShiptypeList($platform_type = 'WEBSITE')
    {
        $this->include_vo();
        $option = ["limit" => -1];
        $this->db->from("shiptype st");
        $this->db->join("platform_shiptype pst", "st.id = pst.shiptype_id AND pst.platform_type = '$platform_type'", "INNER");
        $this->db->where(["pst.status" => 1]);

        $classname = $this->getVoClassname();

        return $this->common_get_list($classname, $where, $option, 'st.*');
    }
}


