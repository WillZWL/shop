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

    public function clearGoogleStatusByPlatform($platform_id) {
        $userId = $this->getUserId();
        $sql = "UPDATE price SET google_status = '', google_update_result = '', modify_by='" . $userId . "' WHERE platform_id = ?";
        $this->db->query($sql, [$platform_id]);

        return $this->db->affected_rows();
    }

    /**
     * @return affected rows
     */
    public function updateSkuPrice($platform_id, $sku, $price)
    {
        $sql = "UPDATE price SET auto_price = 'N', price = ? WHERE sku = ? AND platform_id = ?";
        $this->db->query($sql, [$price, $sku, $platform_id]);

        return $this->db->affected_rows();
    }

    public function getItemsWithPrice($where = [], $classname = "ItemWithPriceDto")
    {
        $this->db->from('product p');
        $this->db->join('bundle b', 'p.sku = b.prod_sku', 'LEFT');
        $this->db->join('product pd', 'pd.sku = b.component_sku', 'LEFT');
        $this->db->join('price AS pr', 'coalesce(pd.sku, p.sku) = pr.sku', 'LEFT');

        $this->db->select('p.sku AS prod_sku, coalesce(pd.sku, p.sku) AS item_sku, coalesce(p.discount,0) AS discount, pr.price, pr.listing_status');

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

    public function getPriceWithCost($where = [], $option = [], $classname = "PriceWithCostDto")
    {
        $this->db->from('product p');
        $this->db->join("freight_category fc", "fc.id = p.freight_cat_id", "LEFT");
        $this->db->join("supplier_prod sp", "p.sku = sp.prod_sku AND sp.order_default = 1", "LEFT");
        $this->db->join("exchange_rate sper", "sp.currency_id = sper.from_currency_id", "INNER");
        $this->db->join("platform_biz_var pbv", "pbv.platform_currency_id = sper.to_currency_id", "INNER");
        $this->db->join("sub_cat_platform_var scpv", "p.sub_cat_id = scpv.sub_cat_id AND pbv.selling_platform_id = scpv.platform_id", "INNER");
        $this->db->join("product_custom_classification cc", "cc.sku = p.sku AND cc.country_id = pbv.platform_country_id", "LEFT");
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
                            fc.declared_pcent AS declared_pcent,
                            fc.weight AS prod_weight,
                            (sp.cost * sper.rate) AS supplier_cost,
                            cc.duty_pcent AS duty_pcent,
                            scpv.platform_commission_percent,
                            scpv.fixed_fee AS listing_fee,
                            scpv.profit_margin AS sub_cat_margin,
                            pbv.platform_currency_id AS platform_currency_id,
                            pbv.forex_fee_percent AS forex_fee_percent,
                            pr.id price_id,
                            pr.price,
                            pr.listing_status,
                            pr.google_status,
                            pr.google_update_result
                            ";
        }

        return $this->commonGetList($classname, $where, $option, $select_str);
    }

    public function getDefaultConvertedPrice($where = [], $option = [], $classname = "PriceWithCostDto")
    {
        $this->db->from('price pr');
        $this->db->join("(platform_biz_var pbv INNER JOIN exchange_rate er)", "er.from_currency_id = 'GBP' AND er.to_currency_id = pbv.platform_currency_id", "INNER");
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
            "SELECT pd.cat_id, pd.sub_cat_id, pd.sub_sub_cat_id, pbv.selling_platform_id AS platform_id, pd.sku, IFNULL(pc.prod_name, pd.name) prod_name, pc.product_url, pc.youtube_id_1, pc.youtube_id_2, pc.youtube_caption_1, pc.youtube_caption_2, pc.short_desc, pd.image AS image_ext, pbv.platform_currency_id AS currency_id, p.price, p.fixed_rrp, p.rrp_factor, IF(pd.display_quantity > pd.website_quantity,pd.website_quantity, pd.display_quantity) AS qty, IF((p.listing_status = 'L') AND IF(pd.display_quantity > pd.website_quantity,pd.website_quantity, pd.display_quantity) > 0 , pd.website_status, 'O') AS status, pd.warranty_in_month,
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

    public function getListWithBundleChecking($sku, $platform = 'WEBUK', $lang_id = 'en', $classname = 'ProductCostDto')
    {


        $sql = "SELECT
                    sp.cost,
                    if((pr.price > 0),pr.price,round((dp.price * er.rate),2)) AS price,
                    p.sku,
                    pbv.platform_country_id AS platform_country_id,
                    pbv.vat_percent AS vat_percent,
                    COALESCE(pc.prod_name, p.name) AS bundle_name,
                    sp.pricehkd,
                    p.expected_delivery_date,
                    COALESCE(pw.warranty_in_month, p.warranty_in_month) AS warranty_in_month,
                    coalesce(p.discount,0) discount,
                    coalesce(b.component_order,-(1)) component_order,
                    prod.sku AS sku,
                    prod.prod_grp_cd AS prod_grp_cd,
                    prod.version_id AS version_id,
                    prod.colour_id AS colour_id,
                    prod.name AS prod_name,
                    pbv.selling_platform_id AS platform_id,
                    pbv.platform_region_id AS platform_region_id,
                    pbv.vat_percent AS vat_percent,
                    pbv.payment_charge_percent AS payment_charge_percent,
                    coalesce(fc.declared_pcent,100) AS declared_pcent,
                    coalesce(cc.duty_pcent,0) AS duty_pcent,
                    cc.code AS cc_code,
                    cc.description AS cc_desc,
                    coalesce(pbv.admin_fee,0) AS admin_fee,
                    0 AS freight_cost,
                    0 AS delivery_cost,
                    coalesce((sp.cost * sper.rate),0) AS supplier_cost,
                    sp.cost AS item_cost,
                    sp.modify_on AS purchaser_updated_date,
                    0 AS delivery_charge,
                    fc.weight AS prod_weight,
                    pbv.free_delivery_limit AS free_delivery_limit,
                    prod.quantity AS quantity,
                    prod.clearance AS clearance,
                    prod.website_quantity AS website_quantity,
                    px.ext_qty AS ext_qty,
                    prod.proc_status AS proc_status,
                    prod.website_status AS website_status,
                    prod.sourcing_status AS sourcing_status,
                    prod.cat_id AS cat_id,
                    prod.sub_cat_id AS sub_cat_id,
                    prod.sub_sub_cat_id AS sub_sub_cat_id,
                    prod.brand_id AS brand_id,
                    prod.image AS image,
                    sp.supplier_id AS supplier_id,
                    sp.supplier_status,
                    prod.freight_cat_id AS freight_cat_id,
                    prod.ean AS ean,
                    prod.mpn AS mpn,
                    prod.upc AS upc,
                    prod.status AS prod_status,
                    prod.display_quantity AS display_quantity,
                    prod.youtube_id AS youtube_id,
                    prod.ex_demo AS ex_demo,
                    scpv.platform_commission_percent AS platform_commission,
                    scpv.fixed_fee AS listing_fee,
                    scpv.profit_margin AS sub_cat_margin,
                    pbv.platform_currency_id AS platform_currency_id,
                    pbv.language_id AS language_id,
                    pbv.forex_fee_percent AS forex_fee_percent,
                    px.ext_item_id AS ext_item_id,
                    px.handling_time AS handling_time,

                    pr.price AS current_platform_price,
                    round((dp.price * er.rate),2) AS default_platform_converted_price,
                    pr.platform_code AS platform_code,
                    pr.listing_status AS listing_status,
                    pr.auto_price AS auto_price
                FROM product p
                LEFT JOIN bundle b ON p.sku = b.prod_sku
                LEFT JOIN product pd ON pd.sku = b.component_sku
                INNER JOIN  product prod on coalesce(pd.sku, p.sku) = prod.sku
                LEFT JOIN freight_category fc ON fc.id = prod.freight_cat_id
                LEFT JOIN (
                   supplier_prod sp
                   JOIN supplier s
                   JOIN exchange_rate sper
                   JOIN platform_biz_var pbv
                ) ON prod.sku = sp.prod_sku
                   AND sp.supplier_id = s.id
                   AND sp.currency_id = sper.from_currency_id
                   AND pbv.platform_currency_id = sper.to_currency_id
                   AND sp.order_default = 1
                LEFT JOIN sub_cat_platform_var scpv ON prod.sub_cat_id = scpv.sub_cat_id AND pbv.selling_platform_id = scpv.platform_id
                LEFT JOIN product_custom_classification cc ON cc.sku = prod.sku AND cc.country_id = pbv.platform_country_id
                LEFT JOIN price_extend px ON px.sku = prod.sku AND px.platform_id = pbv.selling_platform_id
                LEFT JOIN price pr on pr.sku = prod.sku AND pbv.selling_platform_id = pr.platform_id
                LEFT JOIN (
                   price dp
                   JOIN exchange_rate er
                   JOIN config cf on cf.variable = 'default_platform_id'
                ) on prod.sku = dp.sku
                   AND dp.platform_id = cf.value
                   AND er.from_currency_id = 'GBP'
                   AND er.to_currency_id = pbv.platform_currency_id
                LEFT JOIN product_warranty pw
                    ON pw.sku = p.sku and pw.platform_id = pbv.selling_platform_id
                LEFT JOIN product_content pc
                    ON p.sku = pc.prod_sku AND pc.lang_id = ?
                WHERE p.sku = ?
                AND pbv.selling_platform_id = ?
                AND isnull(b.prod_sku)
                ORDER BY coalesce(b.component_order,-(1))
        ";

        $rs = [];
        if ($query = $this->db->query($sql, [$lang_id, $sku, $platform])) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }

            return empty($rs) ? $rs : (object)$rs;
        } else {
            return false;
        }
	}
}
