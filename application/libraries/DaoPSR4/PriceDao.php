<?php
namespace ESG\Panther\Dao;

class PriceDao extends BaseDao
{
    private $table_name = 'price';
    private $vo_class_name = 'PriceVo';

    public function getVoClassname()
    {
        return $this->vo_class_name;
    }

    public function getTableName()
    {
        return $this->table_name;
    }

    public function getItemsWithPrice($where = [], $classname = "ItemWithPriceDto")
    {
        $this->db->from('v_prod_items AS vpi');
        $this->db->join('price AS p', 'vpi.item_sku = p.sku', 'LEFT');

        if ($where) {
            $this->db->where($where);
        }

        $rs = [];

        if ($query = $this->db->get()) {
            // $this->include_dto($classname);
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }
            return (object)$rs;
        }

        return FALSE;
    }

    public function getPlatformPriceList($where = [], $option = [])
    {
        $classname = $this->getVoClassname();

        $this->db->from('price AS p');
        $this->db->join('selling_platform AS sp', 'sp.selling_platform_id= p.platform_id', 'INNER');

        return $this->commonGetList($classname, $where, $option, 'p.*');
    }

    public function getProductPriceWithCost($where = [], $option = [], $classname = "ProductPriceWithCostDto")
    {
        $this->db->from('product p');
        $this->db->join("freight_category fc", "fc.id = p.freight_cat_id", "INNER");
        $this->db->join("supplier_prod sp", "p.sku = sp.prod_sku AND sp.order_default = 1", "INNER");
        // $this->db->join("supplier s", "sp.supplier_id = s.id", "INNER");
        $this->db->join("exchange_rate sper", "sp.currency_id = sper.from_currency_id", "INNER");
        $this->db->join("platform_biz_var pbv", "pbv.platform_currency_id = sper.to_currency_id", "INNER");
        $this->db->join("sub_cat_platform_var scpv", "p.sub_cat_id = scpv.sub_cat_id AND pbv.selling_platform_id = scpv.platform_id", "INNER");
        $this->db->join("product_custom_classification cc", "cc.sku = p.sku AND cc.country_id = pbv.platform_country_id", "INNER");
        $this->db->join("price pr", "pr.sku = sp.prod_sku AND pr.platform_id = pbv.selling_platform_id", "LEFT");

        $this->db->order_by("pbv.platform_country_id asc, pr.id asc");


        if ($option['sum_complementary_cost']) {
            $select_str = "sum((sp.cost * sper.rate)) supplier_cost";
            $this->db->group_by("pbv.selling_platform_id");
        } else {
            $select_str = " p.sku AS sku,
                            pbv.selling_platform_id AS platform_id,
                            pbv.platform_country_id AS platform_country_id,
                            pbv.vat_percent AS vat_percent,
                            pbv.payment_charge_percent AS payment_charge_percent,
                            pbv.free_delivery_limit AS free_delivery_limit,
                            pbv.admin_fee AS admin_fee,
                            0.00 AS delivery_cost,
                            0.00 AS delivery_charge,
                            fc.declared_pcent AS declared_pcent,
                            fc.weight AS prod_weight,
                            (sp.cost * sper.rate) AS supplier_cost,
                            cc.duty_pcent AS duty_pcent,
                            scpv.platform_commission AS platform_commission,
                            scpv.fixed_fee AS listing_fee,
                            scpv.profit_margin AS sub_cat_margin,
                            pbv.platform_currency_id AS platform_currency_id,
                            pbv.forex_fee_percent AS forex_fee_percent,
                            pr.id price_id,
                            pr.price,
                            IF (length(pr.listing_status)>0, pr.listing_status, 'N') listing_status
                            ";
        }

        return $this->commonGetList($classname, $where, $option, $select_str);
    }

    public function getPriceCostDto($sku, $platform, $shiptype = "", $classname = "ProductCostDto")
    {
        $where = $option = [];
        $this->db->from('product p');
        $this->db->join("freight_category fc", "fc.id = p.freight_cat_id", "LEFT");
        $this->db->join("supplier_prod sp", "p.sku = sp.prod_sku AND sp.order_default = 1", "LEFT");
        $this->db->join("supplier s", "sp.supplier_id = s.id", "INNER");
        $this->db->join("exchange_rate sper", "sp.currency_id = sper.from_currency_id", "INNER");
        $this->db->join("platform_biz_var pbv", "pbv.platform_currency_id = sper.to_currency_id", "INNER");
        $this->db->join("sub_cat_platform_var scpv", "p.sub_cat_id = scpv.sub_cat_id AND pbv.selling_platform_id = scpv.platform_id", "LEFT");
        $this->db->join("product_custom_classification cc", "cc.sku = p.sku AND cc.country_id = pbv.platform_country_id", "LEFT");
        $this->db->join("price_extend px", "px.sku = p.sku AND  px.platform_id = pbv.selling_platform_id", "LEFT");

        $where['p.sku'] = $sku;
        $where['pbv.selling_platform_id'] = $platform;
        $option['limit'] = 1;

        $select_str = " p.sku AS sku,
                        p.prod_grp_cd AS prod_grp_cd,
                        p.version_id AS version_id,
                        p.colour_id AS colour_id,
                        p.name AS prod_name,
                        pbv.selling_platform_id AS platform_id,
                        pbv.platform_region_id AS platform_region_id,
                        pbv.platform_country_id AS platform_country_id,
                        pbv.vat_percent AS vat_percent,
                        pbv.payment_charge_percent AS payment_charge_percent,
                        COALESCE (fc.declared_pcent, 100) AS declared_pcent,
                        COALESCE (cc.duty_pcent, 0) AS duty_pcent,
                        cc.code AS cc_code,
                        cc.description AS cc_desc,
                        COALESCE (pbv.admin_fee, 0) AS admin_fee,
                        0 AS freight_cost,
                        0 AS delivery_cost,
                        COALESCE ((sp.cost * sper.rate),0) AS supplier_cost,
                        sp.cost AS item_cost,
                        sp.modify_on AS purchaser_updated_date,
                        0 AS delivery_charge,
                        fc.weight AS prod_weight,
                        pbv.free_delivery_limit AS free_delivery_limit,
                        p.quantity AS quantity,
                        p.clearance AS clearance,
                        p.website_quantity AS website_quantity,
                        px.ext_qty AS ext_qty,
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
                        scpv.platform_commission AS platform_commission,
                        scpv.fixed_fee AS listing_fee,
                        scpv.profit_margin AS sub_cat_margin,
                        pbv.platform_currency_id AS platform_currency_id,
                        pbv.language_id AS language_id,
                        pbv.forex_fee_percent AS forex_fee_percent,
                        px.ext_item_id AS ext_item_id,
                        px.handling_time AS handling_time";

        return $this->commonGetList($classname, $where, $option, $select_str);
    }

    public function getDefaultConvertedPrice($where = [], $option = [], $classname = "ProductCostDto")
    {
        $this->db->from('price pr');
        $this->db->join("(platform_biz_var pbv INNER JOIN exchange_rate er)", "er.from_currency_id = 'HKD' AND er.to_currency_id = pbv.platform_currency_id", "INNER");
        $this->db->join("config c", "c.variable = 'default_platform_id' AND pr.platform_id = c.value", "INNER");

        $select_str = "
                        pbv.selling_platform_id AS platform_id,
                        pr.sku AS sku,round((pr.price * er.rate),2) AS default_platform_converted_price
                      ";
        return $this->commonGetList($classname, $where, $option, $select_str);
    }

    public function getListingInfo($sku = "", $platform_id = "", $lang_id = "en", $option = [], $class_name = 'ListingInfoDto')
    {
        if (empty($sku) || empty($platform_id)) {
            return false;
        }

        if (is_array($sku)) {
            $sku = "'" . implode("','", array_keys($sku)) . "'";
        } else {
            $sku = "'" . $sku . "'";
        }

        $sql =
            "SELECT pd.cat_id, pd.sub_cat_id, pd.sub_sub_cat_id, pbv.selling_platform_id AS platform_id, pd.sku, IFNULL(pc.prod_name, pd.name) prod_name, pc.youtube_id_1, pc.youtube_id_2, pc.youtube_caption_1, pc.youtube_caption_2, pc.short_desc, pd.image AS image_ext, pbv.platform_currency_id AS currency_id, p.price, p.fixed_rrp, p.rrp_factor, IF(pd.display_quantity > pd.website_quantity,pd.website_quantity, pd.display_quantity) AS qty, IF((p.listing_status = 'L') AND IF(pd.display_quantity > pd.website_quantity,pd.website_quantity, pd.display_quantity) > 0 , pd.website_status, 'O') AS status, pd.warranty_in_month,
                p.delivery_scenarioid
                FROM product pd
                LEFT JOIN price p
                ON pd.sku = p.sku AND p.platform_id = ?
                JOIN platform_biz_var pbv
                ON pbv.selling_platform_id = p.platform_id
                LEFT JOIN product_content pc
                ON pc.prod_sku = p.sku AND pc.lang_id = '" . $lang_id . "'
                WHERE pd.status = 2 AND pd.sku IN (" . $sku . ") ";

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
        if ($query = $this->db->query($sql, array($platform_id))) {
            foreach ($query->result($class_name) as $obj) {
                $rs[] = $obj;
            }

            return (count($rs) > 1) ? $rs : $rs[0];
        } else {
            return false;
        }
    }

    public function getListWithBundleChecking($sku, $platform = 'WEBHK', $lang_id = 'en', $classname = 'ProductCostDto')
    {
        $sql = "SELECT p.expected_delivery_date,COALESCE(pw.warranty_in_month, p.warranty_in_month) AS warranty_in_month, a.discount, COALESCE(pc.prod_name, p.name) AS bundle_name, a.component_order, b.*
                FROM v_prod_items a
                JOIN v_prod_overview_wo_shiptype b
                    ON a.item_sku = b.sku
                INNER JOIN product AS p
                    ON a.prod_sku = p.sku
                LEFT JOIN product_warranty pw
                    ON pw.sku = p.sku and pw.platform_id = ?
                LEFT JOIN product_content pc
                    ON a.prod_sku = pc.prod_sku AND pc.lang_id = ?
                WHERE a.prod_sku = ?
                AND b.platform_id= ?
                ORDER BY a.component_order";

        $rs = [];
        if ($query = $this->db->query($sql, [$platform, $lang_id, $sku, $platform])) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }

            return empty($rs) ? $rs : (object)$rs;
        } else {
            return false;
        }
	}
}
