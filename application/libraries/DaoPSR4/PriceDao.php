<?php
namespace AtomV2\Dao;

class PriceDao extends BaseDao
{
    private $table_name = 'price';
    private $vo_class_name = 'PriveVo';

    public function getVoClassname()
    {
        return $this->vo_class_name;
    }

    public function getTableName()
    {
        return $this->table_name;
    }

    public function getPriceCostDto($sku, $platform, $shiptype = "", $classname = "ProductCostDto")
    {
        $where = $option = [];
        $select_str = "*";
        $this->db->from('v_prod_w_platform_biz_var');
        $where['sku'] = $sku;
        $where['platform_id'] = $platform;
        $option['limit'] = 1;
        return $this->commonGetList($classname, $where, $option, $select_str);
    }

    public function getDefaultConvertedPrice($where = [], $option = [])
    {
        $option["limit"] = 1;

        return $this->getDefaultConvertedPriceList($where, $option);
    }

    public function getDefaultConvertedPriceList($where = [], $option = [], $classname = "ProductCostDto")
    {
        $select_str = "*";
        $this->db->from('v_default_converted_price');
        return $this->common_get_list($classname, $where, $option, $select_str);
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
            "SELECT pbv.selling_platform_id AS platform_id, pd.sku, IFNULL(pc.prod_name, pd.name) prod_name, pc.youtube_id_1, pc.youtube_id_2, pc.youtube_caption_1, pc.youtube_caption_2, pc.short_desc, pd.image AS image_ext, pbv.platform_currency_id AS currency_id, p.price, p.fixed_rrp, p.rrp_factor, IF(pd.display_quantity > pd.website_quantity,pd.website_quantity, pd.display_quantity) AS qty, IF((p.listing_status = 'L') AND IF(pd.display_quantity > pd.website_quantity,pd.website_quantity, pd.display_quantity) > 0 , pd.website_status, 'O') AS status, pd.warranty_in_month,
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
}
