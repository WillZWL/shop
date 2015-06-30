<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Price_dao extends Base_dao
{
    private $table_name = "price";
    private $vo_class_name = "Price_vo";
    private $seq_name = "";
    private $seq_mapping_field = "";

    public function __construct()
    {
        parent::__construct();
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

    public function get_price_cost_dto($sku, $platform, $shiptype = "", $classname = "Product_cost_dto")
    {
        $this->include_dto($classname);

        $sql =
            "
            SELECT *
            FROM v_prod_w_platform_biz_var
            WHERE sku = ?
            AND platform_id = ?
        ";

        $rs = array();
        if ($query = $this->db->query($sql, array($sku, $platform))) {
            foreach ($query->result($classname) as $obj) {
                // $rs[] = $obj;
            }

            return $obj;
        } else {
            return FALSE;
        }
    }

    public function get_list_with_bundle_checking($sku, $platform = 'WSGB', $lang_id = 'en', $classname = 'Product_cost_dto')
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

        $this->include_dto($classname);

        $rs = array();
        if ($query = $this->db->query($sql, array($platform, $lang_id, $sku, $platform))) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }

            return empty($rs) ? $rs : (object)$rs;
        } else {
            return false;
        }
    }

    public function get_items_w_price($where = array(), $classname = "Item_w_price_dto")
    {
        $this->db->from('v_prod_items AS vpi');
        $this->db->join('price AS p', 'vpi.item_sku = p.sku', 'LEFT');

        if ($where) {
            $this->db->where($where);
        }

        $rs = array();

        if ($query = $this->db->get()) {
            $this->include_dto($classname);
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }
            return (object)$rs;
        }

        return FALSE;
    }

    public function get_default_converted_price($where = array(), $option = array())
    {
        $option["limit"] = 1;
        return $this->get_default_converted_price_list($where, $option);
    }

    public function get_default_converted_price_list($where = array(), $option = array(), $classname = "Product_cost_dto")
    {
        $select_str = "*";
        $this->db->from('v_default_converted_price');
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, $select_str);
    }

    public function get_website_display_price($sku, $platform_id)
    {
        $sql = "SELECT vpo.price website_display_price
                FROM product p
                JOIN v_prod_overview_wo_shiptype vpo
                    ON vpo.sku = p.sku
                WHERE vpo.platform_id = '$platform_id' and vpo.sku = '$sku'
                    AND vpo.listing_status = 'L'
                    AND vpo.prod_status = '2'";
        /*
        $sql =  "SELECT ROUND(IFNULL(pr2.price, pr.price*ex.rate),2) website_display_price
                FROM product p
                LEFT JOIN price pr
                    ON p.sku = pr.sku AND pr.platform_id = 'WEBGB' AND pr.listing_status = 'L'
                LEFT JOIN price pr2
                    ON p.sku = pr2.sku AND pr2.platform_id = '$platform_id'
                LEFT JOIN platform_biz_var pbv
                    ON pbv.selling_platform_id = '$platform_id'
                LEFT JOIN exchange_rate ex
                    ON ex.from_currency_id = 'GBP' AND ex.to_currency_id = pbv.platform_currency_id
                WHERE (pr2.listing_status = 'L' OR pr2.listing_status IS NULL)
                    AND (pr.price OR pr2.price) > 0
                    AND p.website_quantity > 0
                    AND p.website_status = 'I'
                    AND p.sku = '$sku'";
        */
        if ($query = $this->db->query($sql)) {
            return $query->row()->website_display_price;
        }

        return FALSE;
    }

    public function get_bundle_price($sku, $platform_id)
    {
        $sql = "SELECT ROUND(IFNULL(pr2.price, pr.price*ex.rate),2) website_display_price
                FROM product p
                LEFT JOIN price pr
                    ON p.sku = pr.sku AND pr.platform_id = 'WEBGB' AND pr.listing_status = 'L'
                LEFT JOIN price pr2
                    ON p.sku = pr2.sku AND pr2.platform_id = '$platform_id'
                LEFT JOIN platform_biz_var pbv
                    ON pbv.selling_platform_id = '$platform_id'
                LEFT JOIN exchange_rate ex
                    ON ex.from_currency_id = 'GBP' AND ex.to_currency_id = pbv.platform_currency_id
                WHERE (pr2.listing_status = 'L') AND p.status = 2
                    AND (pr.price OR pr2.price) > 0
                    AND p.website_quantity > 0
                    AND p.website_status = 'I'
                    AND p.sku = '$sku'";

        if ($query = $this->db->query($sql)) {
            return $query->row()->website_display_price;
        }

        return FALSE;
    }

    public function get_price_comparison_report_item_list($country_id, $classname = "price_comparison_report_item_list_dto")
    {
        $sql = "SELECT p.sku, p.name, c.name country, pr.platform_id website_platform, FORMAT(pr.price,2) website_price, pr2.platform_id skype_platform, FORMAT(pr2.price,2) skype_price
                FROM product p
                LEFT JOIN price pr
                    ON p.sku = pr.sku AND pr.platform_id = 'WEB$country_id'
                LEFT JOIN price pr2
                    ON p.sku = pr2.sku AND pr2.platform_id = 'WS$country_id'
                JOIN platform_biz_var pbv
                    ON pbv.selling_platform_id = pr.platform_id
                JOIN country c
                    ON c.id = pbv.platform_country_id
                WHERE
                    pr.price <> pr2.price AND p.status = 2 AND pr.listing_status = 'L' AND pr2.listing_status = 'L'
                ";

        $rs = array();
        if ($query = $this->db->query($sql)) {
            $this->include_dto($classname);

            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }

            return $rs;
        } else {
            return FALSE;
        }
    }

    public function get_version_copmarison_list($classname = "Version_comparison_report_dto")
    {
        $sql = "SELECT c.name AS country_name, pbv.platform_country_id, vpo.platform_id, vpo.sku, vpo.prod_name
FROM v_prod_overview_wo_cost AS vpo
INNER JOIN
(
SELECT pr.platform_id, prod_grp_cd, colour_id
FROM product AS p
INNER JOIN price AS pr
ON p.sku = pr.sku AND pr.listing_status = 'L'
GROUP BY pr.platform_id, p.prod_grp_cd, p.colour_id
HAVING count(*) > 1
) AS pgc
ON vpo.platform_id = pgc.platform_id AND vpo.prod_grp_cd = pgc.prod_grp_cd AND vpo.listing_status = 'L'
INNER JOIN platform_biz_var pbv
ON pbv.selling_platform_id = vpo.platform_id
INNER JOIN country c
ON c.id = pbv.platform_country_id";

        $this->include_dto($classname);

        if ($query = $this->db->query($sql)) {
            $ret = array();
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }

            return $rs;
        }
        return FALSE;
    }

    public function get_listing_info($sku = "", $platform_id = "", $lang_id = "en", $option = array(), $classname = "listing_info_dto")
    {
        if (empty($sku) || empty($platform_id)) {
            return FALSE;
        }

        if (is_array($sku)) {
            $sku = "'" . implode("','", $sku) . "'";
        } else {
            $sku = "'" . $sku . "'";
        }

        $sql =
            "
                SELECT pbv.selling_platform_id AS platform_id, pd.sku, IFNULL(pc.prod_name, pd.name) prod_name, pc.youtube_id_1, pc.youtube_id_2, pc.youtube_caption_1, pc.youtube_caption_2, pc.short_desc, pd.image AS image_ext, pbv.platform_currency_id AS currency_id, p.price, p.fixed_rrp, p.rrp_factor, IF(pd.display_quantity > pd.website_quantity,pd.website_quantity, pd.display_quantity) AS qty, IF((p.listing_status = 'L') AND IF(pd.display_quantity > pd.website_quantity,pd.website_quantity, pd.display_quantity) > 0 , pd.website_status, 'O') AS status, pd.warranty_in_month,
                p.delivery_scenarioid
                FROM product pd
                LEFT JOIN price p
                ON pd.sku = p.sku AND p.platform_id = ?
                JOIN platform_biz_var pbv
                ON pbv.selling_platform_id = p.platform_id
                LEFT JOIN product_content pc
                ON pc.prod_sku = p.sku AND pc.lang_id = '" . $lang_id . "'
                WHERE pd.status = 2 AND pd.sku IN (" . $sku . ")
            ";

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
        if ($query = $this->db->query($sql, array($platform_id))) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }
            if (count($rs) > 1) {
                return $rs;
            } else {
                return $obj;
            }

        } else {
            return FALSE;
        }
    }

    //By Thomas, Used in extracting product listing status, please don't add uncessary field

    public function get_platform_price_list($where = array(), $option = array())
    {
        $this->db->from('price AS p');
        $this->db->join('selling_platform AS sp', 'sp.id= p.platform_id', 'INNER');
        $this->include_vo($classname = $this->get_vo_classname());
        return $this->common_get_list($where, $option, $classname, 'p.*');
    }

    public function get_vo_classname()
    {
        return $this->vo_class_name;
    }

    public function update_rrp_factor()
    {
        // range of discount : 0.25 ~ 0.4
        // factor = 1 / (1 - discount)
        $min_rrp_factor = 1.34;
        $max_rrp_factor = 1.66;

        $sql = 'update price set rrp_factor = ' . $min_rrp_factor . ' where rrp_factor is NULL';
        $this->db->query($sql);

        // Hard code to exclude the Warranty products (SBF #2338), and 11833-AA-NA, 11712-AA-BK(GB,FR,ES) requested by Chapman on 20130726
        $sql = 'SELECT sku FROM product where cat_id != 10 and sub_cat_id != 538 and sku not in ("11833-AA-NA", "11712-AA-BK", "13849-AA-NA", "12052-AA-NA", "12039-AA-NA", "11546-AA-SL", "10342-AA-NA", "11588-AA-BK", "11595-AA-NA", "10365-AA-NA", "12725-AA-NA", "11597-AA-NA", "11601-AA-NA", "11600-AA-NA", "11192-AA-NA", "12050-AA-NA", "12040-AA-NA", "11807-AA-BK", "11807-AA-BL", "13698-AA-BK", "13698-AA-PR", "13698-AA-RD", "13698-AA-WH", "10186-AA-NA", "13977-AA-BK", "13977-AA-RD", "13977-AA-SL", "13977-AA-WH", "13979-AA-NA", "10111-AA-BK", "13450-AA-BK", "10265-AA-NA", "12703-AA-BL", "12703-AA-PK", "12703-AA-SL", "12662-AA-NA", "14011-AA-BK") order by sku';
        if ($query = $this->db->query($sql)) {
            foreach ($query->result() as $row) {
                $sku = $row->sku;

                $change_value = mt_rand(-5, 5) / 100;
                if ($change_value != 0) {
                    $sql = 'update price set modify_at = "127.0.0.1", modify_by = "system", rrp_factor = least(greatest(rrp_factor + (' . $change_value . ' * 1), ' . $min_rrp_factor . '), ' . $max_rrp_factor . ') where sku = "' . $sku . '" and rrp_factor < 10';
                    if (!$this->db->query($sql)) {
                        error_log(__FILE__ . '@' . __LINE__ . ':' . $this->db->_error_message());
                    }
                }
            }
        }

        $this->trans_complete();
    }

    public function update_sku_price($platform_id = "", $local_sku = "", $price = "", $commit = false)
    {
        $sql =
            "
            update price set
                auto_price = 'N',
                price      = ?
            where 1
            and platform_id    = ?
            and sku            = ?
            and listing_status = 'L'
        ";
        $this->db->query($sql, array($price, $platform_id, $local_sku));
        $affected = $this->db->affected_rows();

        if ($commit) $this->commit();

        return $affected;
    }

    public function commit()
    {
        $this->db->query("commit");
        return true;
    }

}
