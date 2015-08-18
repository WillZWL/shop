<?php
namespace AtomV2\Dao;

class ProductDao extends BaseDao
{
    private $table_name = 'product';
    private $vo_class_name = 'ProductVo';

    public function getVoClassname()
    {
        return $this->vo_class_name;
    }

    public function getTableName()
    {
        return $this->table_name;
    }

    public function getHomeProduct($where = [], $option = [], $class_name = 'SimpleProductDto')
    {
        $where['pd.status'] = 2;
        $where['pr.listing_status'] = 'L';
        $where['pd.website_status <>'] = 'O';

        $this->db->from('landpage_listing ll');
        $this->db->join('product pd', 'pd.sku = ll.selection', 'inner');
        $this->db->join('price pr', 'pr.platform_id = ll.platform_id', 'inner');
        $this->db->where($where);
        $this->db->where(['pr.sku = ll.selection' => null]);
        $this->db->group_by('ll.selection');
        $this->db->order_by("ll.mode = 'M' DESC, ll.rank");

        return $this->commonGetList($class_name, [], $option, 'pd.sku');
    }

    public function getListingInfo($sku, $platform_id, $lang_id, $option, $class_name = 'ListingInfoDto')
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

    public function getProductOverview($where = [], $option = [], $class_name = "ProductOverviewDto")
    {
        $option = ['limit' => 1];
        $this->db->from('v_prod_items AS vpi');
        $this->db->join('product AS p', 'vpi.prod_sku = p.sku', 'INNER');
        $this->db->join('v_prod_overview_wo_cost AS vpo', 'vpi.item_sku = vpo.sku', 'INNER');

        return $this->commonGetList($class_name, $where, $option, 'vpo.*, p.expected_delivery_date, p.warranty_in_month');
    }
}
