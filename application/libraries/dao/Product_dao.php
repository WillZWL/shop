<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Product_dao extends Base_dao
{
    const SHIPMENT_RESTRICTED_TYPE_BATTERY = 1;

    private $table_name = "product";
    private $vo_class_name = "Product_vo";
    private $seq_name = "product";
    private $seq_mapping_field = "sku";
    private $debug = 0;

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . 'helpers/object_helper.php');
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

    public function check_battery_inside_cart($battery_cat_list, $item_list)
    {
        $sql = "select sku from product where sku in " . $item_list . " and
                (sub_cat_id in " . $battery_cat_list . " or sub_sub_cat_id in " . $battery_cat_list . " or shipment_restricted_type=" . self::SHIPMENT_RESTRICTED_TYPE_BATTERY . ")";
        $result = $this->db->query($sql);

        $rs = array();
        if ($result !== FALSE) {
            $skuList = $result->result_array();
            foreach ($skuList as $sku) {
                $rs[] = $sku["sku"];
            }
            return $rs;
        } else
            return FALSE;
    }

    public function get_detail_w_name($sku, $platform_id = 'WSGB', $lang_id = 'en', $classname = 'website_prod_search_info_dto')
    {
        $sql = 'SELECT a.sku, a.name, a.cat_id, a.cat_name,
                        a.sub_cat_id, a.sub_cat_name,
                        a.sub_sub_cat_id, a.sub_sub_cat_name,
                        a.brand_name, a.colour_id, a.website_status, a.website_quantity,
                        a.image_file_ext, a.currency,
                        SUM(a.quantity) quantity
                    FROM (SELECT p.sku, pc.prod_name name, p.cat_id, c.name as cat_name,
                            p.sub_cat_id, sc.name as sub_cat_name,
                            p.sub_sub_cat_id, ssc.name as sub_sub_cat_name,
                            b.brand_name, p.colour_id, p.website_status, p.website_quantity,
                            p.image image_file_ext, pbv.platform_currency_id currency,
                            IFNULL(inv.inventory, 0) + IFNULL(inv.git, 0) quantity
                        FROM product_content pc
                        INNER JOIN product p
                            ON (p.sku = pc.prod_sku)
                        INNER JOIN brand b
                            ON (p.brand_id = b.id)
                        INNER JOIN platform_biz_var pbv
                            ON (pbv.selling_platform_id = ?)
                        INNER JOIN category c
                            ON (c.id = p.cat_id)
                        INNER JOIN category sc
                            ON (sc.id = p.sub_cat_id)
                        INNER JOIN category ssc
                            ON (ssc.id = p.sub_sub_cat_id)
                        LEFT JOIN inventory inv
                            ON (pc.prod_sku = inv.prod_sku)
                        WHERE pc.prod_sku = ?) a
                    GROUP BY a.sku, a.name, a.cat_id, a.cat_name,
                        a.sub_cat_id, a.sub_cat_name,
                        a.sub_sub_cat_id, a.sub_sub_cat_name,
                        a.brand_name, a.colour_id, a.website_status, a.website_quantity,
                        a.image_file_ext, a.currency';

        $result = $this->db->query($sql, array($platform_id, $sku));

        if (!$result) {
            return FALSE;
        }

        $this->include_dto($classname);

        $temp_dto = new $classname;
        set_value($temp_dto, $array[0]);

        return $temp_dto;
    }

    public function get_list_by_keyword($keyword, $page_no = 0, $row_limit = 20,
                                        $platform_id = 'WSGB', $lang_id = 'en', $classname = 'website_prod_search_info_dto')
    {
        $start_pt = $page_no * $row_limit;
        $_keyword = preg_quote(str_replace('?', '', $keyword));

        $sql_select = 'SELECT a.sku, a.name, a.cat_id, a.cat_name,
                            a.sub_cat_id, a.sub_cat_name,
                            a.sub_sub_cat_id, a.sub_sub_cat_name,
                            a.brand_name, a.colour_id, a.website_status, a.website_quantity,
                            a.thumbnail, a.image_file_ext,
                            a.currency, a.with_bundle, SUM(a.quantity) quantity
                        FROM (SELECT p.sku, pc.prod_name name, p.cat_id, c.name as cat_name,
                                p.sub_cat_id, sc.name as sub_cat_name,
                                p.sub_sub_cat_id, ssc.name as sub_sub_cat_name,
                                b.brand_name, p.colour_id, p.website_status, p.website_quantity,
                                p.quantity, concat(p.sku, \'_s.\', p.image) thumbnail,
                                p.image image_file_ext,
                                pbv.platform_currency_id currency,
                                p.with_bundle';

        $sql_body1 = ' FROM product_content pc
                    INNER JOIN v_product_w_bundle p
                        ON (p.sku = pc.prod_sku AND p.status > 1)
                    INNER JOIN brand b
                        ON (p.brand_id = b.id)
                    INNER JOIN platform_biz_var pbv
                        ON (pbv.selling_platform_id = ?)
                    INNER JOIN category c
                        ON (c.id = p.cat_id)
                    INNER JOIN category sc
                        ON (sc.id = p.sub_cat_id)
                    INNER JOIN category ssc
                        ON (ssc.id = p.sub_sub_cat_id)
                    INNER JOIN price pr
                        ON (pr.sku = p.sku AND pbv.selling_platform_id = pr.platform_id AND pr.price > 0 AND pr.listing_status = \'L\')';

        $sql_body2 = 'LEFT JOIN inventory inv
                            ON (pc.prod_sku = inv.prod_sku) ';

//      $sql_body3 = 'WHERE pc.keywords regexp \'(^|,| )' . $_keyword . '($|,| )\'
//                      OR pc.prod_name regexp \'(^|,| |\\\\.)'. $_keyword . '($|,| |\\\\.|\\\\!)\'';
        $sql_body3 = 'WHERE pc.keywords regexp \'(^|,| |-)' . $_keyword . '\'
                        OR pc.prod_name regexp \'(^|,| |-|\\\\.)' . $_keyword . '\'';

        $sql_limit = ') a
                     GROUP BY a.sku, a.name, a.cat_id, a.cat_name,
                            a.sub_cat_id, a.sub_cat_name,
                            a.sub_sub_cat_id, a.sub_sub_cat_name,
                            a.brand_name, a.colour_id, a.website_status, a.website_quantity,
                            a.thumbnail, a.image_file_ext,
                            a.currency, a.with_bundle
                     LIMIT ?, ?';

        $sql = $sql_select . $sql_body1 . $sql_body2 . $sql_body3 . $sql_limit;

        $result = $this->db->query($sql, array($platform_id, $start_pt, $row_limit * 1));
//echo $this->db->last_query();

        if (!$result) {
            return FALSE;
        }

        $array = $result->result_array();
        $this->include_dto($classname);

        $result_array = array();

        foreach ($array as $row) {
            $temp_dto = new $classname;
            set_value($temp_dto, $row);
            $result_array[] = $temp_dto;
        }

        // Get the row count

        $sql_count = 'SELECT count(1) AS prodcnt' . $sql_body1 . $sql_body3;

        $result = $this->db->query($sql_count, array($platform_id));

        $result_arr = $result->result_array();
        $prodcnt = $result_arr[0]['prodcnt'];

        return array('prodlist' => $result_array, 'prodcnt' => $prodcnt);
    }

    public function get_list_w_name_for_purchaser_list($where = array(), $option = array(),
                                                       $classname = "Product_list_w_name_dto")
    {
        $this->db->from('product AS p');
        $this->db->join('bundle AS bd', 'p.sku = bd.prod_sku', 'LEFT');
        $this->db->join('sku_mapping AS map', 'map.sku = p.sku AND map.ext_sys = \'WMS\' AND map.status = 1', 'LEFT');
        $this->db->where('bd.prod_sku IS NULL', null);

        if ($where["keywords"] != "") {
            $name_list = explode(' ', $where['keywords']);

            foreach ($name_list as $name) {
                if (!empty($name)) {
                    $this->db->like('p.name', $name);
                }
            }
        }

        if ($where["sku"] != "") {
            $this->db->like('p.sku', $where["sku"]);
        }

        if ($where['master_sku'] != "") {
            $this->db->like('map.ext_sku', $where['master_sku']);
        }

        //  if (!$option["purchaser"] )
        //  {
        //      $this->db->where('p.status >= 1');
        //  }

        if (empty($option["num_rows"])) {

            $this->include_dto($classname);

            $this->db->select('p.sku, p.name, p.proc_status, p.website_status, p.website_quantity, p.image AS image_file, p.status, p.create_on, p.create_at, p.create_by, p.modify_on, p.modify_at, p.modify_by, map.ext_sku master_sku');

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

    public function get_list_w_name($where = array(), $option = array(), $classname = "Product_list_w_name_dto")
    {
        $this->db->from('product AS p');
//      $this->db->join('supplier_prod AS sp', 'p.sku = sp.prod_sku AND order_default = 1', 'LEFT');
        $this->db->join('category AS c', 'p.cat_id = c.id', 'LEFT');
        $this->db->join('colour AS cl', 'p.colour_id = cl.id', 'LEFT');
        $this->db->join('category AS sc', 'p.sub_cat_id = sc.id', 'LEFT');
        $this->db->join('category AS ssc', 'p.sub_sub_cat_id = ssc.id', 'LEFT');
        $this->db->join('brand AS b', 'p.brand_id = b.id', 'LEFT');
        $this->db->join('sku_mapping AS map', "map.sku = p.sku AND map.ext_sys = 'WMS' AND map.status = 1", 'LEFT');

        if ($where["language_id"]) {
            $this->db->join('product_content AS pc', 'p.sku = pc.prod_sku AND pc.lang_id ="' . $where["language_id"] . '"', 'INNER');
            $this->db->join('product_content_extend AS pce', 'p.sku = pce.prod_sku AND pce.lang_id ="' . $where["language_id"] . '"', 'INNER');
        }

        if ($where["keywords"] != "") {
            $this->db->join('product_content AS pc', 'p.sku = pc.prod_sku AND pc.lang_id ="en"', 'INNER');
            $this->db->where('(pc.keywords regexp \'(^|,| |-)' . $where["keywords"] . '\' OR pc.prod_name regexp \'(^|,| |-|\\\\.)' . $where["keywords"] . '\')');
        }

        if ($where["name"] != "") {
            $name_list = explode(' ', $where['name']);

            foreach ($name_list as $name) {
                if (!empty($name)) {
                    $this->db->like('p.name', $name);
                }
            }
        }

        if ($option["exclude_bundle"] || $option["purchaser"]) {
            $this->db->join('bundle AS bd', 'p.sku = bd.prod_sku', 'LEFT');
            $this->db->where('bd.prod_sku IS NULL', null);
        }

        if ($option["exclude_complementary_acc"]) {
            # exclude complementary accessories
            $this->db->where('c.id != 750 AND c.parent_cat_id != 750', null);
        }

        if ($where["prod_grp_cd"] != "") {
            $this->db->like('p.prod_grp_cd', $where["prod_grp_cd"]);
        }

        if ($where["colour_id"] != "") {
            $this->db->like('p.colour_id', $where["colour_id"]);
        }

        if ($where["sku"] != "") {
            $this->db->like('p.sku', $where["sku"]);
        }

        if ($where["master_sku"] != "") {
            $this->db->like('map.ext_sku', $where["master_sku"]);
        }

        if ($where["listing_status"] != "") {
            $this->db->join('price pr', " pr.sku = p.sku AND pr.listing_status = 'L' AND " . (isset($option["selling_platform"]) ? "pr.platform_id = '" . $option["selling_platform"] . "'" : "pr.platform_id LIKE 'WEB%'") . (isset($option["pricegtzero"]) ? " AND pr.price > 0" : ""), "INNER");
            $this->db->where('p.website_status', 'I');
        }
        if ($where["proc_status"] != "") {
            if ($where["proc_status"] == 0) {
                $this->db->where('p.proc_status <', "3");
            } else {
                $this->db->where('p.proc_status', $where["proc_status"]);
            }
        }

        if ($where["colour"] != "") {
            $this->db->like('cl.name', $where["colour"]);
        }

        if ($where["category"] != "") {
            $this->db->like('c.name', $where["category"]);
        }

        if ($where["sub_cat"] != "") {
            $this->db->like('sc.name', $where["sub_cat"]);
        }

        if ($where["sub_sub_cat"] != "") {
            $this->db->like('ssc.name', $where["sub_sub_cat"]);
        }

        if ($where["brand"] != "") {
            $this->db->like('b.brand_name', $where["brand"]);
        }

        if ($where["website_status"] != "") {
            $this->db->where('p.website_status', $where["website_status"]);
        }

        if ($where["sourcing_status"] != "") {
            $this->db->where('p.sourcing_status', $where["sourcing_status"]);
        }

        if ($where["website_quantity"] != "") {
            $this->db->where('p.website_quantity > 0');
        }

        if ($where["create_on"] != "") {
            $this->db->where('p.create_on >=', $where["create_on"] . " 00:00:00");
            $this->db->where('p.create_on <=', $where["create_on"] . " 23:59:59");
        }

        if ($where["start_date"] && $where["end_date"] && ($where["start_date"] < $where["end_date"])) {
            $this->db->where('p.create_on >=', $where["start_date"] . " 00:00:00");
            $this->db->where('p.create_on <=', $where["end_date"] . " 23:59:59");
        }

        if ($where["cat_id"] != "") {
            $this->db->where('p.cat_id', $where["cat_id"]);
        }

        if ($where["sub_cat_id"] != "") {
            $this->db->where('p.sub_cat_id', $where["sub_cat_id"]);
        }

        if ($where["sub_sub_cat_id"] != "") {
            $this->db->where('p.sub_sub_cat_id', $where["sub_sub_cat_id"]);
        }

        if ($where["status"] != "") {
            $this->db->where('p.status', $where["status"]);
        }

        if ($where["warranty_in_month"] != "") {
            $this->db->where('p.warranty_in_month', $where["warranty_in_month"]);
        } else if (!$option["purchaser"]) {
            //$this->db->where('p.status >= 1');
        } else {

        }

        if ($where["weblist"] != "") {
            $this->db->where('p.status', '2');
        }

        if ($where["platform_id"] != "") {
            $this->db->where('pr.platform_id', $where['platform_id']);
        }

        if (empty($option["num_rows"])) {

            $this->include_dto($classname);

            $this->db->select('p.sku, p.name, c.name AS category, sc.name AS sub_cat, cl.name AS colour, ssc.name AS sub_sub_cat, b.brand_name AS brand, p.proc_status, p.website_status, p.website_quantity, p.image AS image_file, p.status, p.create_on, p.create_at, p.create_by, p.modify_on, p.modify_at, p.modify_by, map.ext_sku master_sku, p.warranty_in_month');

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

    public function get_list_w_pname($where = array(), $option = array(), $classname = "Product_list_w_name_dto")
    {
        /*  a variant of get_list_w_name
         *  get WSGB price if current platform price doesn't exist
         */
        $this->db->from('product AS p');
//      $this->db->join('supplier_prod AS sp', 'p.sku = sp.prod_sku AND order_default = 1', 'LEFT');
        $this->db->join('category AS c', 'p.cat_id = c.id', 'LEFT');
        $this->db->join('colour AS cl', 'p.colour_id = cl.id', 'LEFT');
        $this->db->join('category AS sc', 'p.sub_cat_id = sc.id', 'LEFT');
        $this->db->join('category AS ssc', 'p.sub_sub_cat_id = ssc.id', 'LEFT');
        $this->db->join('brand AS b', 'p.brand_id = b.id', 'LEFT');

        if ($where["keywords"] != "") {
            $this->db->join('product_content AS pc', 'p.sku = pc.prod_sku AND pc.lang_id ="en"', 'INNER');
            $this->db->where('(pc.keywords regexp \'(^|,| |-)' . $where["keywords"] . '\' OR pc.prod_name regexp \'(^|,| |-|\\\\.)' . $where["keywords"] . '\')');
        }

        if ($where["name"] != "") {
            $name_list = explode(' ', $where['name']);

            foreach ($name_list as $name) {
                if (!empty($name)) {
                    $this->db->like('p.name', $name);
                }
            }
        }

        if ($option["exclude_bundle"] || $option["purchaser"]) {
            $this->db->join('bundle AS bd', 'p.sku = bd.prod_sku', 'LEFT');
            $this->db->where('bd.prod_sku IS NULL', null);
        }

        if ($where["prod_grp_cd"] != "") {
            $this->db->like('p.prod_grp_cd', $where["prod_grp_cd"]);
        }

        if ($where["sku"] != "") {
            $this->db->like('p.sku', $where["sku"]);
        }

        if ($where["listing_status"] != "") {
            $this->db->join('price pr, v_default_platform_id vdp', " pr.sku = p.sku AND pr.listing_status = 'L' AND pr.platform_id = vdp.platform_id" . (isset($option["pricegtzero"]) ? " AND pr.price > 0" : ""), "LEFT");
            $this->db->join('price pr2', " pr2.sku = p.sku AND pr2.platform_id = pbv.selling_platform_id" . (isset($option["pricegtzero"]) ? " AND pr2.price > 0" : ""), "LEFT");
            $this->db->where('p.website_status', 'I');
            //$this->db->where('(pr.listing_status <> \'N\' OR pr2.listing_status IS NULL)');
            $this->db->where("(pr2.listing_status = 'L')");
        }
        if ($where["proc_status"] != "") {
            if ($where["proc_status"] == 0) {
                $this->db->where('p.proc_status <', "3");
            } else {
                $this->db->where('p.proc_status', $where["proc_status"]);
            }
        }

        if ($where["colour"] != "") {
            $this->db->like('cl.name', $where["colour"]);
        }

        if ($where["category"] != "") {
            $this->db->like('c.name', $where["category"]);
        }

        if ($where["sub_cat"] != "") {
            $this->db->like('sc.name', $where["sub_cat"]);
        }

        if ($where["sub_sub_cat"] != "") {
            $this->db->like('ssc.name', $where["sub_sub_cat"]);
        }

        if ($where["brand"] != "") {
            $this->db->like('b.brand_name', $where["brand"]);
        }

        if ($where["website_status"] != "") {
            $this->db->where('p.website_status', $where["website_status"]);
        }

        if ($where["sourcing_status"] != "") {
            $this->db->where('p.sourcing_status', $where["sourcing_status"]);
        }

        if ($where["website_quantity"] != "") {
            $this->db->where('p.website_quantity > 0');
        }

        if ($where["create_on"] != "") {
            $this->db->where('p.create_on >=', $where["create_on"] . " 00:00:00");
            $this->db->where('p.create_on <=', $where["create_on"] . " 23:59:59");
        }

        if ($where["cat_id"] != "") {
            $this->db->where('p.cat_id', $where["cat_id"]);
        }

        if ($where["sub_cat_id"] != "") {
            $this->db->where('p.sub_cat_id', $where["sub_cat_id"]);
        }

        if ($where["sub_sub_cat_id"] != "") {
            $this->db->where('p.sub_sub_cat_id', $where["sub_sub_cat_id"]);
        }

        if ($where["status"] != "") {
            $this->db->where('p.status', $where["status"]);
        } else if (!$option["purchaser"]) {
            $this->db->where('p.status >= 1');
        } else {

        }

        if ($where["weblist"] != "") {
            $this->db->where('p.status', '2');
        }

        if ($where["platform_id"] != "") {
            $this->db->where('pr.platform_id', $where['platform_id']);
        }

        if (empty($option["num_rows"])) {

            $this->include_dto($classname);

            $this->db->select('p.sku, p.name, c.name AS category, sc.name AS sub_cat, cl.name AS colour, ssc.name AS sub_sub_cat, b.brand_name AS brand, p.proc_status, p.website_status, p.website_quantity, p.image AS image_file, p.status, p.create_on, p.create_at, p.create_by, p.modify_on, p.modify_at, p.modify_by');

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

    public function get_list_w_country_id($where, $option, $classname = "Product_list_w_name_dto")
    {
        if ($option['num_rows'] != "") {
            $sql = "SELECT COUNT(*) AS total ";
        } else {
            $sql = "SELECT
                        p.sku, p.name, c.name AS category, sc.name AS sub_cat, cl.name AS colour, ssc.name AS sub_sub_cat,
                        b.brand_name AS brand, p.proc_status, p.website_status, p.website_quantity, p.image AS image_file, p.status,
                        p.create_on, p.create_at, p.create_by, p.modify_on, p.modify_at, p.modify_by";
        }
        $sql .= "
                    FROM product AS p
                    LEFT JOIN category AS c
                        ON (p.cat_id = c.id)
                    LEFT JOIN colour AS cl
                        ON (p.colour_id = cl.id)
                    LEFT JOIN category AS sc
                        ON (p.sub_cat_id = sc.id)
                    LEFT JOIN category AS ssc
                        ON (p.sub_sub_cat_id = ssc.id)
                    LEFT JOIN brand AS b
                        ON (p.brand_id = b.id)
                    WHERE p.website_status = 'I'
                        AND p.sourcing_status = 'A'
                        AND p.website_quantity > 0
                        AND p.status >= 1
                        AND p.status = '2'
                ";

        if ($where['sku'] != "") {
            $sql .= " AND p.sku LIKE '%" . $where['sku'] . "%'";
        }
        if ($where['name'] != "") {
            $sql .= " AND p.name LIKE '%" . $where['name'] . "%'";
        }

        if ($option['num_rows'] != "") {
            if ($query = $this->db->query($sql)) {
                return $query->row()->total;
            }
        } else {
            if ($option['limit'] != "") {
                $sql .= "
                        LIMIT " . $option['limit'];
                if ($option['offset'] != "") {
                    $sql .= "
                            OFFSET " . $option['offset'];
                }
            }

            $this->include_dto($classname);

            $rs = array();

            if ($query = $this->db->query($sql)) {
                foreach ($query->result($classname) as $obj) {
                    $rs[] = $obj;
                }
                return (object)$rs;
            }
        }
    }

    public function get_video_list_w_name($where = array(), $option = array(), $classname = "Video_list_w_name_dto")
    {
        $this->db->from('product AS p');
//      $this->db->join('supplier_prod AS sp', 'p.sku = sp.prod_sku AND order_default = 1', 'LEFT');
        $this->db->join('product_video AS pv', 'p.sku = pv.sku', 'INNER');
        $this->db->join('category AS c', 'p.cat_id = c.id', 'LEFT');
        $this->db->join('colour AS cl', 'p.colour_id = cl.id', 'LEFT');
        $this->db->join('category AS sc', 'p.sub_cat_id = sc.id', 'LEFT');
        $this->db->join('category AS ssc', 'p.sub_sub_cat_id = ssc.id', 'LEFT');
        $this->db->join('brand AS b', 'p.brand_id = b.id', 'LEFT');

        if ($where["keywords"] != "") {
            $this->db->join('product_content AS pc', 'p.sku = pc.prod_sku AND pc.lang_id ="en"', 'INNER');
            $this->db->where('(pc.keywords regexp \'(^|,| |-)' . $where["keywords"] . '\' OR pc.prod_name regexp \'(^|,| |-|\\\\.)' . $where["keywords"] . '\')');
        }

        if ($where["name"] != "") {
            $name_list = explode(' ', $where['name']);

            foreach ($name_list as $name) {
                if (!empty($name)) {
                    $this->db->like('p.name', $name);
                }
            }
        }

        if ($option["exclude_bundle"] || $option["purchaser"]) {
            $this->db->join('bundle AS bd', 'p.sku = bd.prod_sku', 'LEFT');
            $this->db->where('bd.prod_sku IS NULL', null);
        }

        if ($where["prod_grp_cd"] != "") {
            $this->db->like('p.prod_grp_cd', $where["prod_grp_cd"]);
        }

        if ($where["sku"] != "") {
            $this->db->like('p.sku', $where["sku"]);
        }

        if ($where["listing_status"] != "") {
            $this->db->join('price pr, v_default_platform_id vdp', " pr.sku = p.sku AND pr.listing_status = 'L' AND pr.platform_id = vdp.platform_id" . (isset($option["pricegtzero"]) ? " AND pr.price > 0" : ""), "LEFT");
            $this->db->join('price pr2', " pr2.sku = p.sku AND " . (isset($option["selling_platform"]) ? "pr2.platform_id = '" . $option["selling_platform"] . "'" : "pr2.platform_id LIKE 'WS%'") . (isset($option["pricegtzero"]) ? " AND pr2.price > 0" : ""), "LEFT");
            $this->db->join('platform_biz_var pbv', "pbv.platform_country_id = pv.country_id AND pbv.selling_platform_id='" . $option["selling_platform"] . "'", 'INNER');
            $this->db->where('p.website_status', 'I');
            //$this->db->where('(pr.listing_status <> \'N\' OR pr2.listing_status IS NULL)');
            $this->db->where("(pr2.listing_status = 'L')");
        }
        if ($where["proc_status"] != "") {
            if ($where["proc_status"] == 0) {
                $this->db->where('p.proc_status <', "3");
            } else {
                $this->db->where('p.proc_status', $where["proc_status"]);
            }
        }

        if ($where["colour"] != "") {
            $this->db->like('cl.name', $where["colour"]);
        }

        if ($where["category"] != "") {
            $this->db->like('c.name', $where["category"]);
        }

        if ($where["sub_cat"] != "") {
            $this->db->like('sc.name', $where["sub_cat"]);
        }

        if ($where["sub_sub_cat"] != "") {
            $this->db->like('ssc.name', $where["sub_sub_cat"]);
        }

        if ($where["brand"] != "") {
            $this->db->like('b.brand_name', $where["brand"]);
        }

        if ($where["website_status"] != "") {
            $this->db->where('p.website_status', $where["website_status"]);
        }

        if ($where["sourcing_status"] != "") {
            $this->db->where('p.sourcing_status', $where["sourcing_status"]);
        }

        if ($where["website_quantity"] != "") {
            $this->db->where('p.website_quantity > 0');
        }

        if ($where["create_on"] != "") {
            $this->db->where('p.create_on >=', $where["create_on"] . " 00:00:00");
            $this->db->where('p.create_on <=', $where["create_on"] . " 23:59:59");
        }

        if ($where["cat_id"] != "") {
            $this->db->where('p.cat_id', $where["cat_id"]);
        }

        if ($where["sub_cat_id"] != "") {
            $this->db->where('p.sub_cat_id', $where["sub_cat_id"]);
        }

        if ($where["sub_sub_cat_id"] != "") {
            $this->db->where('p.sub_sub_cat_id', $where["sub_sub_cat_id"]);
        }

        if ($where["status"] != "") {
            $this->db->where('p.status', $where["status"]);
        } else if (!$option["purchaser"]) {
            $this->db->where('p.status >= 1');
        } else {

        }

        if ($where["weblist"] != "") {
            $this->db->where('p.status', '2');
        }

        if ($where["platform_id"] != "") {
            $this->db->where('pr.platform_id', $where['platform_id']);
        }

        if ($where["video_platform"] != "") {
            $this->db->where("pv.platform_id", $where['video_platform']);
        }

        if ($where["video_type"] != "") {
            $this->db->where('pv.type', $where['video_type']);
        }

        if ($where["video_src"] != "") {
            $this->db->where('pv.src', $where['video_src']);
        }

        if (empty($option["num_rows"])) {

            $this->include_dto($classname);

            $this->db->select('pv.id, pv.sku, pv.country_id, pv.lang_id, pv.type, pv.src, pv.ref_id, pv.description, pv.view_count, pv.status, p.name as prod_name, c.name AS category, sc.name AS sub_cat, ssc.name AS sub_sub_cat, cl.name AS colour, b.brand_name AS brand, p.proc_status, p.website_status, p.website_quantity, p.image AS image_file, p.status AS prod_status, pv.create_on, pv.create_at, pv.create_by, pv.modify_on, pv.modify_at, pv.modify_by');

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

    public function get_prod_list($where = array(), $option = array(), $classname = "Product_list_w_name_dto")
    {
        $this->db->from('product AS p');
        $this->db->join('category AS c', 'p.cat_id = c.id', 'LEFT');
//      $this->db->join('colour AS cl', 'p.colour_id = cl.id', 'LEFT');
        $this->db->join('category AS sc', 'p.sub_cat_id = sc.id', 'LEFT');
        $this->db->join('category AS ssc', 'p.sub_sub_cat_id = ssc.id', 'LEFT');
        $this->db->join('brand AS b', 'p.brand_id = b.id', 'LEFT');

        if ($where["keywords"] != "") {
            $this->db->join('product_content AS pc', 'p.sku = pc.prod_sku AND pc.lang_id ="en"', 'INNER');
            $this->db->where('(pc.keywords regexp \'(^|,| |-)' . $where["keywords"] . '\' OR pc.prod_name regexp \'(^|,| |-|\\\\.)' . $where["keywords"] . '\')');
            unset($where["keywords"]);
        }

        if ($option["exclude_bundle"]) {
            $this->db->join('bundle AS bd', 'p.sku = bd.prod_sku', 'LEFT');
            $where["bd.prod_sku IS NULL"] = null;
        }

        if ($where) {
            $this->db->where($where);
        }

        if (empty($option["num_rows"])) {

            $this->include_dto($classname);

            $this->db->select('p.sku, p.name, c.name AS category, sc.name AS sub_cat, ssc.name AS sub_sub_cat, b.brand_name AS brand, p.proc_status, p.website_status, p.website_quantity, p.image AS image_file, p.status, p.create_on, p.create_at, p.create_by, p.modify_on, p.modify_at, p.modify_by', 'p.warranty_in_month');

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

    // Same as function get_list_w_name but dynamic where

    public function get_ra_prod_list_w_name($where = array(), $option = array(), $classname = "")
    {
        $this->db->from('(SELECT sku FROM ra_prod_prod) AS rpp');
        $this->db->join('product AS p', 'p.sku = rpp.sku', 'INNER');
        $this->db->join('category AS c', 'p.cat_id = c.id', 'LEFT');
        $this->db->join('colour AS cl', 'p.colour_id = cl.id', 'LEFT');
        $this->db->join('category AS sc', 'p.sub_cat_id = sc.id', 'LEFT');
        $this->db->join('category AS ssc', 'p.sub_sub_cat_id = ssc.id', 'LEFT');
        $this->db->join('brand AS b', 'p.brand_id = b.id', 'LEFT');

        if ($where["prod_grp_cd"] != "") {
            $this->db->like('p.prod_grp_cd', $where["prod_grp_cd"]);
        }

        if ($where["sku"] != "") {
            $this->db->like('p.sku', $where["sku"]);
        }

        if ($where["name"] != "") {
            $this->db->like('p.name', $where["name"]);
        }

        if ($where["colour"] != "") {
            $this->db->like('cl.name', $where["colour"]);
        }

        if ($where["category"] != "") {
            $this->db->like('c.name', $where["category"]);
        }

        if ($where["sub_cat"] != "") {
            $this->db->like('sc.name', $where["sub_cat"]);
        }

        if ($where["sub_sub_cat"] != "") {
            $this->db->like('ssc.name', $where["sub_sub_cat"]);
        }

        if ($where["brand"] != "") {
            $this->db->like('b.brand_name', $where["brand"]);
        }

        if (empty($option["num_rows"])) {

            $this->include_dto($classname);

            $this->db->select('p.sku, p.name, c.name AS category, sc.name AS sub_cat, cl.name AS colour, ssc.name AS sub_sub_cat, b.brand_name AS brand, p.status, p.create_on, p.create_at, p.create_by, p.modify_on, p.modify_at, p.modify_by');

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

    public function get_ra_prod_w_name($where = array(), $classname = "")
    {
        if ($query = $this->db->get_where("v_ra_prod_prod", $where, 1, 0)) {
            $this->include_dto($classname);

            $rs = $query->result($classname);

            if (empty($rs)) {
                return $rs;
            } else {
                return $rs[0];
            }
        } else {
            return FALSE;
        }
    }

    public function get_prod_wo_bundle($where = array(), $option = array(), $classname = "")
    {
        $this->db->from('product AS p');
        $this->db->join('(SELECT DISTINCT prod_sku FROM bundle) AS b', 'p.sku = b.prod_sku', 'LEFT');

        $where["b.prod_sku IS NULL"] = NULL;
        $this->db->where($where);

        if (empty($option["num_rows"])) {

            if ($classname == "") {
                $classname = $this->get_vo_classname();
                $rs_include = $this->include_vo();
            } else {
                $rs_include = $this->include_dto($classname);
            }

            if ($rs_include === FALSE) {
                return FALSE;
            }

            $this->db->select('p.*');

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
                return $rs ? ($option["limit"] == 1 ? $rs[0] : (object)$rs) : $rs;
            }
        } else {
            $this->db->select('COUNT(*) AS total');
            if ($query = $this->db->get()) {
                return $query->row()->total;
            }
        }

        return FALSE;
    }

    public function get_vo_classname()
    {
        return $this->vo_class_name;
    }

    public function get_components_w_name($where = array(), $option = array(), $classname = "Product_cost_dto")
    {

        $this->db->from('bundle AS b');
        $this->db->join('v_prod_overview_wo_shiptype AS p', 'p.sku = b.component_sku', 'LEFT');

        if (!isset($where["platform_id"])) {
            $where["platform_id"] = "WSGB";
        }

        $this->db->where('platform_id', $where["platform_id"]);

        if ($where["sku"] != "") {
            $this->db->where('b.prod_sku', $where["sku"]);
        }

        if (isset($option["orderby"])) {
            $this->db->order_by($option["orderby"]);
        }

        $this->include_dto($classname);

        $this->db->select('p.*');

        if ($query = $this->db->get()) {
            $rs = array();
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }
            if ($option["limit"] == 1) {
                return $rs[0];
            } else {
                return (object)$rs;
            }
        } else {
            return FALSE;
        }
    }

    public function get_ra_product_overview($sku = "", $platform_id = "", $classname = "Product_cost_dto")
    {
        $this->include_dto($classname);

        $sql = "
                SELECT vpo.*
                FROM
                v_prod_overview_wo_shiptype AS vpo
                WHERE EXISTS
                (
                    SELECT 1 FROM ra_prod_prod AS rpp
                    WHERE rpp.sku = ?
                    AND
                    (
                        vpo.sku = ?
                        OR
                        (
                            vpo.sku = rpp.rcm_prod_id_1
                        )
                    )
                )
                AND vpo.platform_id = ?
                AND vpo.website_status = 'I'
                ";

        $rs = array();
        if ($query = $this->db->query($sql, array($sku, $sku, $platform_id))) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }
            return (object)$rs;
        } else {
            return FALSE;
        }

    }

    public function get_product_overview($where = array(), $option = array(), $classname = "Product_cost_dto")
    {
        $this->db->from('v_prod_overview_wo_shiptype');
        $select_str = "v_prod_overview_wo_shiptype.*";

        if ($option["master_sku"]) {
            $this->db->join('sku_mapping AS map', "v_prod_overview_wo_shiptype.sku = map.sku AND map.ext_sys = 'wms' AND map.status = 1", "LEFT");
            $select_str .= ", map.ext_sku master_sku";
        }

        if ($option["delivery_time"]) {
            $this->db->join('price AS pr', "v_prod_overview_wo_shiptype.sku = pr.sku AND pr.platform_id = v_prod_overview_wo_shiptype.platform_id", "LEFT");
            $this->db->join('delivery_time AS dt', "v_prod_overview_wo_shiptype.platform_country_id = dt.country_id AND pr.delivery_scenarioid = dt.scenarioid", "LEFT");
            $select_str .= ", pr.delivery_scenarioid, CONCAT_WS(' - ', dt.ship_min_day, dt.ship_max_day) AS ship_day, CONCAT_WS(' - ', dt.del_min_day, dt.del_max_day) AS delivery_day ";
        } elseif (isset($where["pr.listing_status"])) {
            $this->db->join('price AS pr', "v_prod_overview_wo_shiptype.sku = pr.sku AND pr.platform_id = v_prod_overview_wo_shiptype.platform_id", "LEFT");
        }

        if ($option["desc_lang"]) {
            $this->db->join('product_content AS pc', "v_prod_overview_wo_shiptype.sku = pc.prod_sku AND pc.lang_id = '{$option["desc_lang"]}'", 'LEFT');
            $select_str .= ", pc.prod_name AS content_prod_name, pc.detail_desc";
        }

        if ($option["inventory"]) {
            $this->db->join('product p', 'p.sku = v_prod_overview_wo_shiptype.sku', 'INNER');
            $this->db->join('v_prod_inventory AS vpi', "v_prod_overview_wo_shiptype.sku = vpi.prod_sku", 'LEFT');
            $select_str .= ", vpi.inventory, p.surplus_quantity";
        }

        if ($option["product_feed"]) {
            $this->db->join('(SELECT sku, GROUP_CONCAT(CONCAT_WS("::", feeder, IF(ISNULL(value_1), "", value_1), IF(ISNULL(value_2), "", value_2), IF(ISNULL(value_3), "", value_3), CAST(status AS CHAR)) SEPARATOR "||") AS feeds
                            FROM product_feed
                            GROUP BY sku) AS pf', "v_prod_overview_wo_shiptype.sku = pf.sku", 'LEFT');
            $select_str .= ", pf.feeds";
        }

        if ($option["refresh_margin"]) {
            $this->db->join('price_margin pm', 'pm.sku = v_prod_overview_wo_shiptype.sku  AND v_prod_overview_wo_shiptype.platform_id = pm.platform_id', 'INNER');
            $select_str .= ", pm.profit, pm.margin";
        }

        if ($option["frontend"]) {
            $this->db->join('product p', 'p.sku = v_prod_overview_wo_shiptype.sku', 'INNER');
            $this->db->join('product_content pc', "pc.prod_sku = p.sku AND pc.lang_id='" . ($option["language"] ? $option["language"] : "en") . "'", 'LEFT');
            $select_str .= ", p.image,p.display_quantity,p.youtube_id, pc.prod_name AS content_prod_name, pc.extra_info";
        }

        if ($option["price_extend"]) {
            $this->db->join('price_extend prext', 'prext.sku = v_prod_overview_wo_shiptype.sku AND prext.platform_id = v_prod_overview_wo_shiptype.platform_id', 'LEFT');
            $select_str .= ", prext.ext_qty, prext.fulfillment_centre_id, prext.amazon_reprice_name";
        }

        if (isset($where["platform_id"])) {
            $where["v_prod_overview_wo_shiptype.platform_id"] = $where["platform_id"];
            unset($where["platform_id"]);
        }

        if ($option["affiliate_feed"]) {
            $criteria = "asp.sku = map.sku and asp.affiliate_id = '{$option['affiliate_feed']}'";
            if ($option["feed_status"] > 0) $criteria .= " and asp.`status` = {$option['feed_status']}";

            $this->db->join("affiliate_sku_platform as asp", $criteria, "inner");
            // inner join affiliate_sku_platform asp on asp.sku = map.sku and asp.affiliate_id = "KOES" and asp.`status` = 2
        }

        if ($option["show_name"]) {
            $this->db->join('category AS c', 'v_prod_overview_wo_shiptype.cat_id = c.id', 'LEFT');
            $this->db->join('category AS sc', 'v_prod_overview_wo_shiptype.sub_cat_id = sc.id', 'LEFT');
            $this->db->join('category AS ssc', 'v_prod_overview_wo_shiptype.sub_sub_cat_id = ssc.id', 'LEFT');
            $this->db->join('brand AS b', 'v_prod_overview_wo_shiptype.brand_id = b.id', 'LEFT');
            $select_str .= ", c.name AS category, sc.name AS sub_category, ssc.name AS sub_sub_category, b.brand_name";
        } else {
            if (!isset($option["skip_prod_status_checking"])) {
                $this->db->where('v_prod_overview_wo_shiptype.prod_status !=', 0);
            } else {
                unset($option["skip_prod_status_checking"]);
            }
        }

        if ($option["active_supplier"]) {
            $option["supplier_prod"] = 1;
        }

        if ($option["supplier_prod"]) {
            $this->db->join('supplier_prod sp', 'sp.supplier_id = v_prod_overview_wo_shiptype.supplier_id AND sp.prod_sku = v_prod_overview_wo_shiptype.sku', 'LEFT');
            $select_str .= ", sp.supplier_status";
        }

        if ($option["active_supplier"]) {
            $this->db->join('supplier s', 's.id = sp.supplier_id', 'INNER');
            $this->db->where(array("s.status" => 1, "sp.order_default" => 1));
        }

        if ($option["wms_inventory"]) {
            $join_sql = "(
                                SELECT inv.master_sku, group_concat(concat(inv.warehouse_id, ',', cast(inv.inventory as char), ',', cast(inv.git as char)) separator '|') wms_inv FROM
                                (
                                    SELECT warehouse_id, master_sku, SUM(inventory) as inventory, SUM(git) as git
                                    FROM wms_inventory
                                    GROUP BY warehouse_id, master_sku
                                ) inv
                                GROUP BY inv.master_sku
                            ) wms ";
            $this->db->join($join_sql, 'map.ext_sku = wms.master_sku', 'LEFT');
            $select_str .= ", wms.wms_inv";
        }

        $this->db->select($select_str);

        $this->db->where($where);

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
                // echo "<pre>"; var_dump($option);
                // var_dump($this->db->last_query()); die();
                foreach ($query->result($classname) as $obj) {
                    $rs[] = $obj;
                }
                return $rs ? ($option["limit"] == 1 ? $rs[0] : (object)$rs) : $rs;
            }

        } else {
            $this->db->select('COUNT(*) AS total');
            if ($query = $this->db->get()) {
//               echo "<pre>"; var_dump($this->db->last_query()); die();
                return $query->row()->total;
            }
        }

        return FALSE;
    }

    public function get_product_overview_v2($where = array(), $option = array(), $classname = "Product_cost_dto")
    {
        /*
            *   new version adapted from v_prod_overview_wo_shiptype
            *   Also, this has MPN, EAN, UPC from product_identifier tb (correct version) instead of product tb
        */

        $this->db->from("(product AS p) JOIN platform_biz_var AS pbv");
        $select_str .= "p.sku, p.prod_grp_cd, p.colour_id, p.version_id, p.name AS prod_name, p.freight_cat_id, p.cat_id, p.sub_cat_id,
                        p.sub_sub_cat_id, p.brand_id, p.clearance, p.surplus_quantity, p.slow_move_7_days, p.quantity, p.display_quantity,
                        p.website_quantity, p.china_oem, p.ex_demo, p.rrp, p.image, p.flash, p.youtube_id, p.discount,
                        p.proc_status, p.website_status, p.sourcing_status, p.expected_delivery_date, p.warranty_in_month, p.cat_upselling,
                        p.lang_restricted, p.shipment_restricted_type, p.status AS prod_status,
                        0 AS freight_cost, 0 AS delivery_cost";

        // $this->db->join("platform_biz_var AS pbv", "", "INNER");
        $select_str .= ",pbv.vat_percent, COALESCE(pbv.admin_fee,0) AS admin_fee, pbv.platform_region_id, pbv.language_id, pbv.forex_fee_percent,
                        pbv.free_delivery_limit, pbv.payment_charge_percent, pbv.platform_country_id, pbv.platform_currency_id,  pbv.language_id ";

        $this->db->join("price AS pr", "p.sku = pr.sku AND pr.platform_id = pbv.selling_platform_id", "LEFT");
        $select_str .= ",pbv.selling_platform_id AS platform_id, pr.sales_qty, pr.status, pr.allow_express, pr.is_advertised,
                        pr.ext_mapping_code, pr.latency, pr.oos_latency, pr.listing_status, pr.platform_code, pr.max_order_qty, pr.auto_price, pr.fixed_rrp, pr.rrp_factor,
                        pr.delivery_scenarioid";

        # gives a converted price from WEBHK price if current platform price is empty
        $this->db->join("(price dp JOIN exchange_rate AS er)", "dp.sku = p.sku AND dp.platform_id = 'WEBHK' AND er.from_currency_id = 'HKD' AND er.to_currency_id = pbv.platform_currency_id", "LEFT");
        $select_str .= ", IF(pr.price > 0, pr.price, ROUND((dp.price * er.rate),2)) AS price,
                        pr.price AS current_platform_price,
                        ROUND((dp.price * er.rate),2) AS default_platform_converted_price";

        $this->db->join("price_extend AS prx", "prx.sku = p.sku AND prx.platform_id = pbv.selling_platform_id", "LEFT");
        $select_str .= ", prx.ext_qty, prx.ext_item_id, prx.ext_status, prx.handling_time, prx.ext_ref_1, prx.ext_ref_2, prx.ext_ref_3, prx.remark";

        $this->db->join("product_identifier pi", "pi.prod_grp_cd = p.prod_grp_cd AND pi.country_id = pbv.platform_country_id AND pi.status = 1 AND pi.colour_id = p.colour_id", "LEFT");
        $select_str .= ", pi.mpn, pi.ean, pi.upc";

        $this->db->join("((supplier_prod AS sp JOIN supplier s) JOIN exchange_rate sper)", "sp.prod_sku = p.sku AND sp.supplier_id = s.id AND sp.currency_id = sper.from_currency_id AND pbv.platform_currency_id = sper.to_currency_id AND sp.order_default = 1 ", "LEFT");
        $select_str .= ", COALESCE((sp.cost * sper.rate), 0) AS supplier_cost, sp.modify_on AS purchaser_updated_date, sp.supplier_id";

        # this portion calculates logistic cost
        // $this->db->join("freight_category fc", "fc.id = p.freight_cat_id", "LEFT");
        $this->db->join("((freight_cat_charge AS fcc JOIN freight_category fc) JOIN exchange_rate logex)",
            "fcc.origin_country = left(s.fc_id,2) AND fcc.dest_country = pbv.dest_country AND fc.id = fcc.fcat_id AND fc.id = p.freight_cat_id AND logex.from_currency_id = fcc.currency_id AND logex.to_currency_id = pbv.platform_currency_id", "LEFT");
        $select_str .= ", COALESCE(fc.declared_pcent, 100) AS declared_pcent, fc.weight AS prod_weight, IF(ISNULL(fcc.amount), 0, round(fcc.amount*logex.rate,2)) as logistic_cost ";


        $this->db->join("product_custom_classification cc", "cc.sku = p.sku AND cc.country_id = pbv.platform_country_id", "LEFT");
        $select_str .= ", COALESCE(cc.duty_pcent, 0) AS duty_pcent, cc.code AS cc_code, cc.description AS cc_desc";

        $this->db->join("sub_cat_platform_var scpv", "scpv.sub_cat_id = p.sub_cat_id AND pbv.selling_platform_id = scpv.platform_id", "LEFT");
        $select_str .= ", scpv.platform_commission AS platform_commission, scpv.fixed_fee AS listing_fee, scpv.profit_margin AS sub_cat_margin";

        # if desc_lang passed in, then we don't join this
        if (!$option["desc_lang"]) {
            $this->db->join('product_content AS pc', "p.sku = pc.prod_sku AND pc.lang_id = pbv.language_id", 'LEFT');
            $select_str .= ", pc.prod_name AS content_prod_name, pc.detail_desc";
        }

        if ($option["google_shopping"]) {
            $this->db->join("google_shopping gs", "gs.platform_id = pbv.selling_platform_id AND gs.sku = p.sku", "LEFT");
            $this->db->join("adwords_data ad", "ad.platform_id = pbv.selling_platform_id and ad.sku = p.sku", "LEFT");

            $select_str .= ", gs.status AS gsc_status, gs.api_request_result, gs.comment, ad.api_request_result AS ad_api_request_result, ad.`status` AS ad_status";
        }

        if (isset($option["wms_inventory"]))
            $option["master_sku"] = 1;

        if ($option["master_sku"]) {
            $this->db->join('sku_mapping AS map', "p.sku = map.sku AND map.ext_sys = 'wms' AND map.status = 1", "LEFT");
            $select_str .= ", map.ext_sku master_sku";
        }

        if ($option["delivery_time"]) {
            // $this->db->join('price AS pr', "p.sku = pr.sku AND pr.platform_id = pr.platform_id", "LEFT");
            $this->db->join('delivery_time AS dt', "pbv.platform_country_id = dt.country_id AND pr.delivery_scenarioid = dt.scenarioid", "LEFT");
            $select_str .= ", pr.delivery_scenarioid, CONCAT_WS(' - ', dt.ship_min_day, dt.ship_max_day) AS ship_day, CONCAT_WS(' - ', dt.del_min_day, dt.del_max_day) AS delivery_day ";
        } elseif (isset($where["pr.listing_status"])) {
            // $this->db->join('price AS pr', "p.sku = pr.sku AND pr.platform_id = pr.platform_id", "LEFT");
        }

        if ($option["desc_lang"]) {
            $this->db->join('product_content AS pc', "p.sku = pc.prod_sku AND pc.lang_id = '{$option["desc_lang"]}'", 'LEFT');
            $select_str .= ", pc.prod_name AS content_prod_name, pc.detail_desc";
        }

        if ($option["product_feed"]) {
            $this->db->join('(SELECT sku, GROUP_CONCAT(CONCAT_WS("::", feeder, IF(ISNULL(value_1), "", value_1), IF(ISNULL(value_2), "", value_2), IF(ISNULL(value_3), "", value_3), CAST(status AS CHAR)) SEPARATOR "||") AS feeds
                            FROM product_feed
                            GROUP BY sku) AS pf', "p.sku = pf.sku", 'LEFT');
            $select_str .= ", pf.feeds";
        }

        if ($option["price_extend"]) {
            // $this->db->join('price_extend prext','prext.sku = p.sku AND prext.platform_id = pr.platform_id','LEFT');
            $select_str .= ", prx.fulfillment_centre_id, prx.amazon_reprice_name";
        }

        if ($option["active_supplier"]) {
            $option["supplier_prod"] = 1;
        }

        if ($option["supplier_prod"]) {
            // $this->db->join('supplier_prod sp', 'sp.supplier_id = v_prod_overview_wo_shiptype.supplier_id AND sp.prod_sku = p.sku', 'LEFT');
            $select_str .= ", sp.supplier_status";
        }

        if ($option["active_supplier"]) {
            // $this->db->join('supplier s2', 's2.id = sp.supplier_id', 'INNER');
            $this->db->where(array("s.status" => 1, "sp.order_default" => 1));
        }

        if ($option["inventory"]) {
            // $this->db->join('product p','p.sku = p.sku','INNER');
            $this->db->join('(SELECT prod_sku, SUM(inventory) as inventory FROM inventory GROUP BY inventory.prod_sku) AS inv', "p.sku = inv.prod_sku", 'LEFT');
            $select_str .= ", inv.inventory, inv.prod_sku";
        }

        if ($option["refresh_margin"]) {
            // price_margin table is mainly used for speeding up searching. Table is refreshed by cron jobs (exchange rate, cps supplier price etc)
            $this->db->join('price_margin pm', 'pm.sku = p.sku  AND pbv.selling_platform_id = pm.platform_id', 'LEFT');
            $select_str .= ", pm.profit, pm.margin";
        }

        if ($option["frontend"]) {
            // $this->db->join('product p','p.sku = p.sku','INNER');
            $this->db->join('product_content pc', "pc.prod_sku = p.sku AND pc.lang_id='" . ($option["language"] ? $option["language"] : "en") . "'", 'LEFT');
            $select_str .= ", p.image,p.display_quantity,p.youtube_id, pc.prod_name AS content_prod_name, pc.extra_info";
        }

        if (isset($where["platform_id"])) {
            $where["pr.platform_id"] = $where["platform_id"];
            unset($where["platform_id"]);
        }

        if ($option["affiliate_feed"]) {
            $criteria = "asp.sku = map.sku and asp.affiliate_id = '{$option['affiliate_feed']}'";
            if ($option["feed_status"] > 0) $criteria .= " and asp.`status` = {$option['feed_status']}";

            $this->db->join("affiliate_sku_platform as asp", $criteria, "inner");
            // inner join affiliate_sku_platform asp on asp.sku = map.sku and asp.affiliate_id = "KOES" and asp.`status` = 2
        }

        if ($option["show_name"]) {
            $this->db->join('category AS c', 'p.cat_id = c.id', 'LEFT');
            $this->db->join('category AS sc', 'p.sub_cat_id = sc.id', 'LEFT');
            $this->db->join('category AS ssc', 'p.sub_sub_cat_id = ssc.id', 'LEFT');
            $this->db->join('brand AS b', 'p.brand_id = b.id', 'LEFT');
            $select_str .= ", c.name AS category, sc.name AS sub_category, ssc.name AS sub_sub_category, b.brand_name";
        } else {
            if (!isset($option["skip_prod_status_checking"])) {
                $this->db->where('p.status !=', 0);
            } else {
                unset($option["skip_prod_status_checking"]);
            }
        }

        if ($option["wms_inventory"]) {
            $join_sql = "(
                            SELECT inv.master_sku, group_concat(concat(inv.warehouse_id, ',', cast(inv.inventory as char), ',', cast(inv.git as char)) separator '|') wms_inv FROM
                            (
                                SELECT warehouse_id, master_sku, SUM(inventory) as inventory, SUM(git) as git
                                FROM wms_inventory
                                GROUP BY warehouse_id, master_sku
                            ) inv
                            GROUP BY inv.master_sku
                        ) wms ";
            $this->db->join($join_sql, 'map.ext_sku = wms.master_sku', 'LEFT');
            $select_str .= ", wms.wms_inv";
        }
        $this->db->where(array("p.version_id <> 'EX'" => null));
        $this->db->where($where);

        if (empty($option["num_rows"])) {
            $this->db->select($select_str, false);

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

            /*
            echo "<pre>";
            var_dump($where);
            var_dump($option);
            $query = $this->db->get();
            var_dump($this->db->last_query());
            var_dump($this->db->_error_message());
            die();
            */
            if ($query = $this->db->get()) {
                // echo "<pre>"; var_dump($option);
                // echo "<pre>"; var_dump($this->db->last_query());
                foreach ($query->result($classname) as $obj) {
                    $rs[] = $obj;
                }
                return $rs ? ($option["limit"] == 1 ? $rs[0] : (object)$rs) : $rs;
            }

        } else {
            $this->db->select('COUNT(*) AS total');
            if ($query = $this->db->get()) {
//               echo "<pre>"; var_dump($this->db->last_query()); die();
                return $query->row()->total;
            }
        }

        return FALSE;
    }

    public function get_listed_prod_supplier_info($where = array(), $option = array(), $classname = "Prod_supplier_info_dto")
    {
        // get listed products' price and supplier related info
        $this->db->from("product AS p");
        $this->db->join("price pr", "pr.sku = p.sku", "LEFT");
        $this->db->join("supplier_prod sp", "sp.prod_sku = p.sku", "LEFT");
        $this->db->join("supplier s", "s.id = sp.supplier_id", "JOIN");
        $this->db->join("sku_mapping skm", "skm.sku = p.sku and skm.status=1 and skm.ext_sys='WMS'", "LEFT");
        $this->db->join("(select warehouse_id, master_sku, sum(inventory) as inventory, sum(git) as git from wms_inventory group by master_sku) inv", "inv.master_sku = skm.ext_sku", "LEFT");

        $this->db->where($where);
        $this->db->where(array(
                "p.status" => 2,
                "s.status" => 1,             #active supplier
                "pr.listing_status" => "L",  #listed on platform
                "sp.order_default" => 1
            )
        );
        $this->db->select("p.sku,
                            p.name,
                            p.surplus_quantity,
                            p.slow_move_7_days,
                            pr.platform_id,
                            pr.price,
                            sp.supplier_id,
                            sp.supplier_status,
                            s.origin_country,
                            s.name AS supplier_name,
                            inv.git");

        $this->include_dto($classname);
        $rs = array();
        if ($query = $this->db->get()) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }
            return $rs;
        }
        return FALSE;
    }

    public function get_prod_by_component($where = array())
    {
        $this->db->from('product AS p');
        $this->db->join('bundle AS b', 'p.sku = b.prod_sku', 'RIGHT');

        $this->db->where($where);

        if (empty($option["num_rows"])) {

            $this->include_vo();

            $this->db->select('p.*');

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
                foreach ($query->result($this->get_vo_classname()) as $obj) {
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

    public function get_ixtens_repice_list($where = array(), $option = array(), $classname = "Product_cost_dto")
    {

        $this->db->from('v_prod_overview_wo_shiptype AS vpo');
        $this->db->join('interface_price AS ip', 'vpo.sku = ip.sku', 'INNER');
        $this->db->where($where);

        $option["limit"] = -1;

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

            $this->db->select('vpo.*, ip.price AS int_price', FALSE);

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

    public function get_product_feed($platform, $classname = "Amazon_prod_feed_dto")
    {
        if ($platform == "") {
            return false;
        } else {
            $sql = "SELECT '$fulfillmentCentreID' AS fulfillmentCentreID, '$isSecondHand' AS isSecondHand, '$shiptype' as shiptype, p.listing_status,
                    prod.sku, p.price, p.platform_code, prod.name, prodc.prod_name, b.brand_name, prodc.detail_desc as contents, prod.mpn, fc.weight, p.latency, p.max_order_qty as moq, prodc.keywords, prod.website_quantity as quantity, prex.ext_qty, pbv.latency_in_stock, pbv.latency_out_of_stock, prod.clearance, p.auto_price,p.default_shiptype as shiptype, IFNULL(inv.qty, 0) as inv_qty, pc.condition, prex.note condition_note
                    FROM product prod
                    LEFT JOIN price p
                        ON p.sku = prod.sku AND p.platform_id = ?
                    LEFT JOIN price p_de
                        ON p_de.sku = prod.sku AND p_de.platform_id = 'AMDE'
                    LEFT JOIN price p_fr
                        ON p_fr.sku = prod.sku AND p_fr.platform_id = 'AMFR'
                    LEFT JOIN price p_uk
                        ON p_uk.sku = prod.sku AND p_uk.platform_id = 'AMUK'
                    LEFT JOIN price p_us
                        ON p_us.sku = prod.sku and p_us.platform_id = 'AMUS'
                    JOIN platform_biz_var pbv
                        ON pbv.selling_platform_id = p.platform_id
                    LEFT JOIN product_content prodc
                        ON prodc.prod_sku = prod.sku AND prodc.lang_id = pbv.language_id
                    JOIN brand b
                        ON b.id = prod.brand_id
                    JOIN freight_category fc
                        ON prod.freight_cat_id = fc.id
                    LEFT JOIN ( SELECT prod_sku, SUM(inventory) as qty
                                FROM inventory
                                GROUP BY prod_sku) as inv
                        ON inv.prod_sku = prod.sku
                    LEFT JOIN price_extend prex
                        ON p.sku = prex.sku AND p.platform_id = prex.platform_id
                    LEFT JOIN product_condition pc
                        ON prex.ext_condition = pc.id
                    WHERE p.price > 0
                    AND (p.listing_status = 'L' OR p_de.listing_status = 'L' OR p_fr.listing_status = 'L' OR p_uk.listing_status = 'L' OR p_us.listing_status = 'L')
                    AND ((prod.website_quantity > 0 AND prod.website_status = 'I' AND prex.fulfillment_centre_id IN ('', 'DEFAULT', null))
                            OR prex.fulfillment_centre_id IN ('AMAZON_NA', 'AMAZON_EU'))
                    AND prod.status > 0
                    AND p.platform_code <> ''";

            $rs = array();

            $this->include_dto($classname);

            if ($query = $this->db->query($sql, array($platform))) {
                foreach ($query->result($classname) as $obj) {
                    $rs[] = $obj;
                }
                return (object)$rs;
            } else {

                return false;
            }
        }
    }

    public function get_discontinued_product_list($platform = "")
    {
        $ret = array();
        if ($platform == "") {
            return FALSE;
        } else {
            $sql = "SELECT p.sku
                    FROM product p
                    LEFT JOIN price pr
                        ON pr.sku = p.sku AND pr.platform_id = 'AMUK'
                    LEFT JOIN price pr2
                        ON pr2.sku = p.sku AND pr2.platform_id = 'AMDE'
                    LEFT JOIN price pr3
                        ON pr3.sku = p.sku and pr3.platform_id = 'AMFR'
                    LEFT JOIN price pr4
                        ON pr4.sku = p.sku AND pr4.platform_id = 'AMUS'
                    WHERE (pr.listing_status != 'L' AND pr2.listing_status != 'L' AND pr3.listing_status != 'L' AND pr4.listing_status != 'L') OR p.status = 0
                    ";

            if ($query = $this->db->query($sql)) {
                foreach ($query->result("object", "") as $obj) {
                    $ret[] = $obj->sku;
                }

                return $ret;
            }
            return FALSE;
        }

    }

    public function get_product_with_price($sku, $site = 'WEBHK', $classname = 'Product_price_dto')
    {
        $sql = "select p.sku AS sku
                ,p.name AS name
                ,p.cat_id AS cat_id
                ,p.sub_cat_id AS sub_cat_id
                ,p.sub_sub_cat_id AS sub_sub_cat_id
                ,p.brand_id AS brand_id
                ,p.website_quantity AS website_quantity
                ,p.image AS image
                ,p.discount AS discount
                ,p.website_status AS website_status
                ,p.status AS status
                ,pr.platform_id AS platform_id
                ,sum(round(((pr.price * (100 - coalesce(p.discount,0))) / 100),2)) AS price
                from (((product p left join bundle b on((p.sku = b.prod_sku))) left join product pd on((pd.sku = b.component_sku))) left join price pr on((coalesce(pd.sku,p.sku) = pr.sku)))
                where p.sku=?
                and pr.platform_id=?
                group by p.sku,pr.platform_id
                LIMIT 1";

        $this->include_dto($classname);

        $rs = array();

        if ($query = $this->db->query($sql, array($sku, $site))) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }
            return $obj;
        } else {
            return FALSE;
        }
    }

    public function get_product_brand_cat($level, $catid, $brand, $where = array(), $option = array(), $platform = "WSGB", $classname = "Brand_cat_prod_dto")
    {
        switch ($level) {
            case 1:
                $cat = "cat_id";
                break;

            case 2:
                $cat = "sub_cat_id";
                break;

            case 3:
                $cat = "sub_sub_cat_id";
                break;

            default:
                return false;
                break;
        }

        $select = "SELECT p.sku, p.name, p.colour_id, p.status, p.quantity, p.website_quantity ,p.image, p.website_status, pr.price ";

        $fromsql = "FROM product p
                    JOIN price pr
                        ON pr.sku = p.SKU
                        AND pr.platform_id = '$platform'
                        AND pr.listing_status = 'L'
                        AND pr.price > 0
                    JOIN platform_biz_var pbv
                        ON pbv.selling_platform_id = '$platform'";

        $wheresql = " WHERE p.$cat = '$catid'
                   AND p.brand_id = '$brand'
                   AND p.status = '2'";


        if ($where["colour"] != "") {
            $wheresql .= " AND p.colour_id = '" . $where['colour'] . "' ";
        }

        if ($option["num_row"] == "") {
            if (isset($option["sort"])) {
                $sort = " ORDER BY " . $option["sort"] . " " . ($option["order"] == "A" ? " " : "DESC ");
            } else {
                //Default Sorting - product name
                $sort = " ORDER BY p.name ";
            }

            if (!isset($option["limit"])) {
                //default item per page
                $lim = 10;
            } else {
                $lim = $option["limit"];
            }

            if (!isset($option["limit_from"])) {
                $from = 0;
            } else {
                $from = $option["limit_from"] * $lim;
            }

            $this->include_dto($classname);

            $rs = array();

            $sql = $select . $fromsql . $wheresql . $sort . " LIMIT $from,$lim";

            if ($query = $this->db->query($sql)) {
                foreach ($query->result($classname) as $obj) {
                    $rs[] = $obj;
                }

                return $rs;
            }
        } else {
            $sql = "SELECT COUNT(*) AS total " . $fromsql . $wheresql;

            if ($query = $this->db->query($sql)) {
                return $query->row()->total;
            }
        }
        return FALSE;
    }

    public function get_pblist()
    {
        $sql = "SELECT DISTINCT(prod_sku) as sku
                FROM v_prod_items";

        $rs = array();

        if ($query = $this->db->query($sql)) {
            foreach ($query->result("object", "") as $obj) {
                $rs[$obj->sku] = 1;
            }
        }

        return $rs;
    }

    public function get_item_contain($sku)
    {
        if ($sku != "") {
            $sql = "SELECT item_sku
                    FROM v_prod_items
                    WHERE prod_sku = ?";

            $rs = array();

            if ($query = $this->db->query($sql, $sku)) {
                foreach ($query->result("object", "") as $obj) {
                    $rs[] = $obj->item_sku;
                }

                return $rs;
            }
        }
        return FALSE;
    }

    public function get_prod_list_for_website($where = array(), $option = array(), $platform = 'WSGB', $classname = "Website_prod_info_dto")
    {
        $select = "SELECT p.sku, p.name, p.cat_id, p.sub_cat_id, p.sub_sub_cat_id, b.brand_name, p.colour_id, p.website_status, p.website_quantity, p.quantity,
                p.image , pr.price, pr.fixed_rrp, pr.rrp_factor, pbv.platform_currency_id as currency ";

        $from_sql = "FROM product p
                 JOIN price pr
                    ON pr.sku = p.sku AND pr.platform_id = '$platform' AND pr.listing_status = 'L' AND pr.price > 0
                 JOIN platform_biz_var pbv
                    ON pbv.selling_platform_id = '$platform'
                 JOIN brand b
                    ON p.brand_id = b.id";

        $this->include_dto($classname);

        $where_sql = array();

        if (isset($where["cat"])) {
            $where_sql[] = " p.cat_id = '" . $where["cat"] . "' ";
        }

        if (isset($where["scat"])) {
            $where_sql[] = " p.sub_cat_id = '" . $where['scat'] . "' ";
        }

        if (isset($where["sscat"])) {
            $where_sql[] = " p.sub_sub_cat_id = '" . $where['sscat'] . "' ";
        }

        if (isset($where["colour"])) {
            $where_sql[] = " p.colour_id = '" . $where['colour'] . "' ";
        }

        if (isset($where["brand"])) {
            $where_sql[] = " p.brand_id = '" . $where['brand'] . "'";
        }

        if (isset($where["sku_list"])) {
            $where_sql[] = " p.sku in (" . $where['sku_list'] . ")";
        }

        $where_sql[] = " p.status = '2' ";

        if ($option["num_row"] == "") {

            if (isset($option["sort"])) {
                $sort = " ORDER BY " . $option["sort"] . " " . ($option["order"] == "A" ? " " : "DESC ");
            } else {
                //Default Sorting - product name
                $sort = " ORDER BY p.name ";
            }

            if (!isset($option["limit"])) {
                //default item per page
                $lim = 10;
            } else {
                $lim = $option["limit"];
            }

            if (!isset($option["limit_from"])) {
                $from = 0;
            } else {
                $from = $option["limit_from"] * $lim;
            }

            $sql = $select . $from_sql . (count($where_sql) ? " WHERE " . implode("AND", $where_sql) : " ") . $sort . " LIMIT $from, $lim";

            $rs = array();

            if ($query = $this->db->query($sql)) {
                foreach ($query->result($classname) as $obj) {
                    $rs[] = $obj;
                }

                return (object)$rs;
            }
        } else {
            $sql = "SELECT COUNT(*) AS total " . $from_sql . (count($where_sql) ? " WHERE " . implode("AND", $where_sql) : "");

            if ($query = $this->db->query($sql)) {
                return $query->row()->total;
            }
        }
        return FALSE;
    }

    public function get_skype_feed($sku, $lang_id, $platform, $currency, $classname = "Skype_prod_feed_dto")
    {
        $sql = "
                SELECT vpo.sku, COALESCE(pc.prod_name, vpo.prod_name) AS name, ROUND(vpo.price * er.rate,2) AS price, LEAST(vpo.website_quantity, vpo.display_quantity) AS qty, IF(vpo.website_quantity > 0 AND vpo.website_status = 'I' AND vpo.sourcing_status <> 'O' AND prod_status = 2 AND vpo.listing_status = 'L', 'true', 'false') AS in_stock, vpo.delivery_charge AS delivery_cost
                FROM v_prod_overview_wo_shiptype AS vpo
                LEFT JOIN product_content pc
                    ON (vpo.sku = pc.prod_sku AND pc.lang_id = ?)
                LEFT JOIN exchange_rate er
                    ON (er.from_currency_id = vpo.platform_currency_id
                        AND er.to_currency_id = ?
                    )
                WHERE vpo.sku = ?
                AND vpo.platform_id = ?
                ";

        $this->include_dto($classname);

        $rs = array();
        if ($query = $this->db->query($sql, array($lang_id, $currency, $sku, $platform))) {
            foreach ($query->result($classname) as $obj) {
                $rs = $obj;
            }
            return $rs;

        }
        return FALSE;
    }

    public function get_sli_feed($classname = "Sli_prod_feed_dto")
    {
        include_once(APPPATH . "libraries/service/Context_config_service.php");
        $cfg = new Context_config_service();

        $sql = "SELECT v.sku, b.brand_name, b.brand_name as manufacturer_name, v.prod_name, pc.short_desc, CONCAT_WS('|',c1.name,c2.name,c3.name) as category, v.website_status, ROUND(v.price / 0.80,2) as retail_price, p.image,v.website_quantity, v.price as priceGBP, p.mpn, p.ean, v.platform_currency_id, pc.keywords, pc.detail_desc, v.delivery_charge, round(v.price * er.rate,2) as priceEUR
                FROM v_prod_overview_wo_shiptype v
                JOIN product_content pc
                    ON pc.prod_sku = v.sku
                JOIN exchange_rate er
                    ON from_currency_id = v.platform_currency_id
                    AND to_currency_id = 'EUR'
                JOIN product p
                    ON p.sku = v.sku
                JOIN brand b
                    ON b.id = v.brand_id
                JOIN category c1
                    ON c1.id = v.cat_id
                JOIN category c2
                    ON c2.id = v.sub_cat_id
                JOIN category c3
                    ON c3.id = v.sub_sub_cat_id
                WHERE v.platform_id = 'WSGB'
                AND v.prod_status = '2'
                AND v.listing_status = 'L'
                AND v.price > 0";

        $this->include_dto($classname);

        $rs = array();

        if ($query = $this->db->query($sql)) {
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }
            return (object)$rs;
        }
        return FALSE;
    }


    public function get_clearance_list($where = array(), $option = array(), $platform = 'WSGB', $classname = "Website_prod_info_dto")
    {
        $selectsql = "  SELECT p.sku, p.name, p.colour_id, p.quantity, b.brand_name, p.website_quantity, p.website_status, p.image, c1.name as cat, c2.name as subcat, c3.name as sub_subcat,pr.price, pbv.platform_currency_id as currency ";
        $fromsql = "   FROM product p
                        JOIN price pr
                            ON  pr.sku = p.sku
                            AND pr.platform_id = '$platform'
                        JOIN category c1
                            ON c1.id = p.cat_id
                        JOIN category c2
                            ON c2.id = p.sub_cat_id
                        JOIN category c3
                            ON c3.id = p.sub_sub_cat_id
                        JOIN brand b
                            ON p.brand_id = b.id
                        JOIN platform_biz_var pbv
                            ON pbv.selling_platform_id = '$platform'";

        $where_sql = array();
        $where_sql[] = "p.status = '2' ";
        $where_sql[] = "p.clearance = '1' ";

        $this->include_dto($classname);

        if ($where["colour"] != "") {
            $where_sql[] = "p.colour_id = '" . $where["colour"] . "'";
        }

        if ($where["cat"] != "") {
            $where_sql[] = "p.cat_id = '" . $where["cat"] . "'";
        }

        if ($where["scat"] != "") {
            $where_sql[] = "p.sub_cat_id = '" . $where["cat"] . "'";
        }

        if ($where["sscat"] != "") {
            $where_sql[] = "p.sub_sub_cat_id = '" . $where["cat"] . "'";
        }

        if ($where["brand"] != "") {
            $where_sql[] = "p.brand_id = '" . $where["brand"] . "'";
        }

        $wheresql = " WHERE " . implode(" AND ", $where_sql) . " ";

        if ($option["num_row"] == "") {

            $ob = $option["sort"];

            if ($ob == "") {
                $ob = "p.name";
            }

            if ($option["order"] == "") {
                $o = "ASC";
            } else {
                $o = "DESC";
            }

            $ordersql = " ORDER BY $ob $o ";

            $limit = $option["limit"];
            if ($option["limit"] == "") {
                $limit = 20;
            }

            $limit_from = $option["page"] * $limit;

            $limitsql = " LIMIT $limit_from, $limit";

            $sql = $selectsql . $fromsql . $wheresql . $ordersql . $limitsql;

            $rs = array();

            if ($query = $this->db->query($sql)) {

                foreach ($query->result($classname) as $obj) {
                    $rs[] = $obj;
                }

                return $rs;

            }
        } else {
            $sql = "SELECT COUNT(*) AS total " . $fromsql . $wheresql;

            if ($query = $this->db->query($sql)) {
                return $query->row()->total;
            }
        }
        return FALSE;
    }

    public function get_best_seller_list_by_cat($filter_column = '',
                                                $cat_id = 0, $day_count = 0, $limit = 0, $platform, $is_skype_certified = '')
    {
        if (($filter_column === '' && $cat_id !== 0) || !is_numeric($cat_id)
            || !is_numeric($day_count) || $day_count <= 0 || empty($platform)
        ) {
            return FALSE;
        }

        $cat_filter_str = '';
        $limit_str = '';
        $input_array = array($day_count);

        if ($cat_id !== 0) {
            $cat_filter_str = " AND p.$filter_column = ?";
            array_push($input_array, $cat_id);
        }

        if ($limit > 0) {
            $limit_str = "LIMIT ?";
            array_push($input_array, $limit);
        }

        if ($is_skype_certified) {
            $join_clause = " JOIN product_type pt
                            ON pt.sku = p.sku AND pt.type_id = 'SC'";
        }

        $sql = "SELECT p.*
                FROM product p
                JOIN
                (
                    SELECT soi.prod_sku, SUM(soi.qty) ttl_qty
                    FROM so_item AS soi
                    INNER JOIN so ON (so.so_no = soi.so_no AND so.status > 2
                        AND DATEDIFF(now(), so.create_on) <= ? AND so.platform_id = '$platform')
                    GROUP BY soi.prod_sku
                ) a
                ON (p.sku = a.prod_sku{$cat_filter_str})
                $join_clause
                WHERE p.status = 2 AND p.website_status= 'I' AND p.website_quantity > 0
                ORDER BY a.ttl_qty DESC
                $limit_str";

        if ($result = $this->db->query($sql, $input_array)) {
            $this->include_vo();

            $result_arr = array();
            $classname = $this->get_vo_classname();

            foreach ($result->result("object", $classname) as $obj) {
                array_push($result_arr, $obj);
            }
            return $result_arr;
        }
        return FALSE;
    }

    public function get_pick_of_the_day_list_by_cat($filter_column = '',
                                                    $cat_id = 0, $day_count = 0, $limit = 0, $platform)
    {
        if (($filter_column === '' && $cat_id !== 0) || !is_numeric($cat_id)
            || !is_numeric($day_count) || $day_count <= 0 || empty($platform)
        ) {
            return FALSE;
        }

        $cat_filter_str = '';
        $limit_str = '';
        $input_array = array($day_count);

        if ($cat_id !== 0) {
            $cat_filter_str = " AND p.$filter_column = ?";
            array_push($input_array, $cat_id);
        }

        if ($limit > 0) {
            $limit_str = "LIMIT ?";
            array_push($input_array, $limit);
        }

        $sql = "SELECT p.*, if(pr2.price>0, pr2.price, pr.price*ex.rate) AS platform_price
                FROM product p
                LEFT JOIN (price pr, v_default_platform_id vdp)
                    ON (p.sku = pr.sku AND pr.platform_id = vdp.platform_id AND pr.listing_status = 'L')
                LEFT JOIN price pr2
                    ON (p.sku = pr2.sku AND pr2.platform_id = '$platform')
                JOIN platform_biz_var pbv
                    ON (pbv.selling_platform_id = pr2.platform_id)
                JOIN exchange_rate ex
                    ON (ex.from_currency_id='GBP' AND ex.to_currency_id = pbv.platform_currency_id)
                JOIN exchange_rate ex2
                    ON (ex2.from_currency_id='USD' AND ex2.to_currency_id = pbv.platform_currency_id)
                JOIN
                (
                    SELECT soid.item_sku, SUM(soid.margin*soid.qty)/SUM(soid.qty) avg_margin, SUM(soid.qty) ttl_qty
                    FROM so_item_detail AS soid
                    INNER JOIN so ON (so.so_no = soid.so_no AND so.status > 2
                        AND DATEDIFF(now(), so.create_on) <= ? AND so.platform_id = '$platform')
                    GROUP BY soid.item_sku
                ) a
                    ON (p.sku = a.item_sku{$cat_filter_str})
                WHERE p.status = 2 AND (pr2.listing_status = 'L') AND p.website_status= 'I' AND p.website_quantity > 0
                ORDER BY (platform_price >=20*ex2.rate AND platform_price <=70*ex2.rate) DESC, a.avg_margin DESC, a.ttl_qty DESC
                $limit_str";

        $result = $this->db->query($sql, $input_array);
        //echo $this->db->last_query()."<br>";

        $this->include_vo();

        $result_arr = array();
        $classname = $this->get_vo_classname();

        foreach ($result->result("object", $classname) as $obj) {
            array_push($result_arr, $obj);
        }

        return $result_arr;

    }


    public function get_latest_arrivals_list_by_cat($filter_column = '',
                                                    $cat_id = 0, $day_count = 0, $limit = 0, $platform)
    {
        if (($filter_column === '' && $cat_id !== 0) || !is_numeric($cat_id)
            || !is_numeric($day_count) || $day_count <= 0 || empty($platform)
        ) {
            return FALSE;
        }

        $cat_filter_str = '';
        $limit_str = '';
        $input_array = array($day_count);

        if ($cat_id !== 0) {
            $cat_filter_str = " AND p.$filter_column = ?";
            array_push($input_array, $cat_id);
        }

        if ($limit > 0) {
            $limit_str = "LIMIT ?";
            array_push($input_array, $limit);
        }

        $sql = "SELECT p.*, if(pr2.price>0, pr2.price, pr.price*ex.rate) AS platform_price
                FROM product p
                LEFT JOIN (price pr, v_default_platform_id vdp)
                    ON (p.sku = pr.sku AND pr.platform_id = vdp.platform_id AND pr.listing_status = 'L')
                LEFT JOIN price pr2
                    ON (p.sku = pr2.sku AND pr2.platform_id = '$platform')
                JOIN platform_biz_var pbv
                    ON (pbv.selling_platform_id = pr2.platform_id)
                JOIN exchange_rate ex
                    ON (ex.from_currency_id='GBP' AND ex.to_currency_id = pbv.platform_currency_id)
                JOIN exchange_rate ex2
                    ON (ex2.from_currency_id='USD' AND ex2.to_currency_id = pbv.platform_currency_id)
                WHERE p.status = 2 AND (pr2.listing_status = 'L') AND p.website_status= 'I' AND p.website_quantity > 0{$cat_filter_str}
                ORDER BY p.create_on DESC
                $limit_str";

        $result = $this->db->query($sql, $input_array);
        //echo $this->db->last_query()."<br>";

        $this->include_vo();

        $result_arr = array();
        $classname = $this->get_vo_classname();

        foreach ($result->result("object", $classname) as $obj) {
            array_push($result_arr, $obj);
        }

        return $result_arr;
    }

    public function get_product_price($platform, $sku)
    {
        $sql = "SELECT p.*, if(pr2.price>0, pr2.price, pr.price*ex.rate) AS platform_price
                FROM product p
                LEFT JOIN (price pr, v_default_platform_id vdp)
                    ON (p.sku = pr.sku AND pr.platform_id = vdp.platform_id AND pr.listing_status = 'L')
                LEFT JOIN price pr2
                    ON (p.sku = pr2.sku AND pr2.platform_id = '$platform')
                JOIN platform_biz_var pbv
                    ON (pbv.selling_platform_id = pr2.platform_id)
                JOIN exchange_rate ex
                    ON (ex.from_currency_id='GBP' AND ex.to_currency_id = pbv.platform_currency_id)
                JOIN exchange_rate ex2
                    ON (ex2.from_currency_id='USD' AND ex2.to_currency_id = pbv.platform_currency_id)
                WHERE p.status = 2 AND (pr2.listing_status = 'L') AND p.website_status= 'I' AND p.website_quantity > 0 AND p.sku='$sku'";

        if ($query = $this->db->query($sql, $input_array)) {
            $ret = array();
            $array = $query->result_array();
            foreach ($array as $row) {
                $ret[] = $row;
            }
            return $ret;
        }
        return FALSE;
    }


    public function get_listed_product_list($platform_id = 'WEBGB', $classname = 'Website_prod_info_dto')
    {
        $sql = "SELECT * FROM v_prod_overview_wo_shiptype vpo
                WHERE vpo.platform_id = ?
                    AND vpo.listing_status = 'L'";

        $result = $this->db->query($sql, array('platform_id' => $platform_id));

        $this->include_dto($classname);
        $result_arr = array();

        if ($result) {
            foreach ($result->result("object", $classname) as $obj) {
                $result_arr[] = $obj;
            }
        }

        return $result_arr;
    }

    public function get_product_w_price_info($platform_id = 'WEBGB', $sku = "", $classname = 'Website_prod_info_dto')
    {

        $sql = "SELECT * FROM v_prod_overview_wo_shiptype vpo WHERE vpo.platform_id = ? AND sku = ?";
        $result = $this->db->query($sql, array($platform_id, $sku));

        $this->include_dto($classname);
        $result_arr = array();

        if ($result->num_rows() > 0) {
            foreach ($result->result("object", $classname) as $obj) {
                $result_arr[$obj->get_sku()] = $obj;
            }
        }

        return $result_arr;
    }

    public function get_top_deal_list_by_cat($filter_column = '',
                                             $cat_id = 0, $limit = 0, $platform)
    {
        if (($filter_column === '' && $cat_id !== 0) || !is_numeric($cat_id)) {
            return FALSE;
        }

        $cat_filter_str = '';
        $limit_str = '';
        $input_array = array();

        $cat_filter_str = "WHERE p.status = 2 AND p.website_status= 'I' AND p.website_quantity > 0 AND pm.platform_id = '$platform'";
        if ($cat_id !== 0) {
            $cat_filter_str .= " AND p.$filter_column = ?";

            array_push($input_array, $cat_id);
        }


        if ($limit > 0) {
            $limit_str = "LIMIT ?";
            array_push($input_array, $limit);
        }

        $sql = "SELECT pm.margin, pm.profit, P.* FROM product p
                JOIN price_margin pm
                    ON (p.sku = pm.sku)
                $cat_filter_str

                ORDER BY pm.margin, pm.profit DESC
                $limit_str";

        $result = $this->db->query($sql, $input_array);

        $this->include_vo();

        $result_arr = array();
        $classname = $this->get_vo_classname();

        foreach ($result->result("object", $classname) as $obj) {
            array_push($result_arr, $obj);
        }

        return $result_arr;
    }

    public function get_current_supplier($sku = "")
    {
        if ($sku == "") {
            return false;
        }

        $this->db->from('supplier s');

        $this->db->join("(  SELECT supplier_id, supplier_status
                            FROM supplier_prod
                            WHERE prod_sku = '$sku'
                            AND order_default = '1'
                            LIMIT 1) AS sp", "sp.supplier_id = s.id", "INNER");

        $this->db->limit(1);

        $this->db->select('s.name, sp.supplier_status');

        if ($query = $this->db->get()) {
            return (array)$query->row();
        }

        return FALSE;
    }

    public function get_total_default_supplier($sku)
    {
        $sql = "SELECT count(1) num_row
                FROM supplier_prod
                WHERE prod_sku = ?";

        if ($query = $this->db->query($sql, array($sku))) {
            return $query->row()->num_row;
        }

        return FALSE;
    }

    public function get_list_having_price($where = array(), $option = array())
    {
        $table_alias = array('product' => 'p', 'price' => 'pr', 'product_type' => 'pt');
        include_once APPPATH . "helpers/string_helper.php";
        $new_where = replace_db_alias($where, $table_alias);

        $value_list = array();

        if ($new_where && count($new_where) > 0) {
            $where_clause = '';
            $counter = 0;

            foreach ($new_where as $key => $value) {
                if ($counter <= 0) {
                    $where_clause = ' WHERE ';
                } else {
                    $where_clause .= ' AND ';
                }

                if ($this->db->_has_operator($key)) {
                    $where_clause .= "$key ?";
                } else {
                    $where_clause .= "$key = ?";
                }
                array_push($value_list, $value);
                $counter++;
            }
        }

        if ($option && count($option) > 0) {
            $option_clause = '';

            foreach ($option as $key => $value) {
                if ($key == 'orderby') {
                    $option_clause .= " ORDER BY $value";
                } else {
                    $option_clause .= " $key $value";
                }
            }
        }

        $sql = "SELECT p.* FROM product p
                JOIN price pr ON (p.sku = pr.sku AND pr.price > 0)";

        if ($where['product_type.type_id']) {
            $sql .= "
                LEFT JOIN product_type pt
                    ON (p.sku = pt.sku)";
        }
        $sql .= "$where_clause
                $option_clause";

        $result_arr = array();
        if ($result = $this->db->query($sql, $value_list)) {
            $this->include_vo();

            $classname = $this->get_vo_classname();

            foreach ($result->result("object", $classname) as $obj) {
                $result_arr[] = $obj;
            }
        }
        return $result_arr;
    }


    public function get_listed_video_list($where = array(), $option = array(), $classname = "listed_video_list_dto")
    {
        $table_alias = array('product' => 'p', 'price' => 'pr', 'product_video' => 'pv', 'platform_biz_var' => 'pbv');
        include_once APPPATH . "helpers/string_helper.php";
        $new_where = replace_db_alias($where, $table_alias);

        $value_list = array();

        if ($new_where && count($new_where) > 0) {
            $where_clause = '';
            $counter = 0;

            foreach ($new_where as $key => $value) {
                if ($counter <= 0) {
                    $where_clause = ' WHERE ';
                } else {
                    $where_clause .= ' AND ';
                }

                if ($this->db->_has_operator($key)) {
                    $where_clause .= "$key ?";
                } else {
                    $where_clause .= "$key = ?";
                }
                array_push($value_list, $value);
                $counter++;
            }
        }

        if ($option && count($option) > 0) {
            $option_clause = '';

            foreach ($option as $key => $value) {
                if ($key == 'orderby') {
                    $option_clause .= " ORDER BY $value";
                } else {
                    $option_clause .= " $key $value";
                }
            }
        }

        if ($new_where['pbv.selling_platform_id']) {
            $pr_str = " AND pr2.platform_id = '" . $new_where['pbv.selling_platform_id'] . "'";
        }

        $sql = "SELECT p.cat_id, p.sub_cat_id, p.sub_sub_cat_id, pv.* FROM product p
                JOIN product_video pv ON (p.sku = pv.sku)";
        if ($new_where['pbv.selling_platform_id']) {
            $sql .= " JOIN platform_biz_var pbv ON (pbv.selling_platform_id = '" . $new_where['pbv.selling_platform_id'] . "')";
        }

        $sql .= "LEFT JOIN (price pr, v_default_platform_id vdp) ON (p.sku = pr.sku AND pr.platform_id = vdp.platform_id AND pr.listing_status = 'L')
                LEFT JOIN price pr2 ON (p.sku = pr2.sku $pr_str)
                $where_clause
                $option_clause";

        $this->include_dto($classname);

        if ($query = $this->db->query($sql, $value_list)) {
            $ret = array();
            foreach ($query->result($classname) as $obj) {
                $ret[] = $obj;
            }

            return $ret;
        }
        //echo $this->db->last_query();
        return FALSE;
    }

    public function get_video_detail($where = array(), $option = array(), $classname = "listed_video_list_dto")
    {
        $this->db->from('product p');
        $this->db->join('product_video pv', 'p.sku = pv.sku', 'INNER');
        $this->db->where($where);

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

            $this->db->select('p.cat_id, p.sub_cat_id, p.sub_sub_cat_id, pv.*');

            if ($query = $this->db->get()) {
                $ret = array();
                foreach ($query->result($classname) as $obj) {
                    $ret[] = $obj;
                }

                return $ret;
            }

        } else {
            $this->db->select('COUNT(*) AS total');
            if ($query = $this->db->get()) {
                return $query->row()->total;
            }
        }
        return FALSE;
    }

    public function get_t3m_product_info($sku = "", $classname = "T3m_prod_info_dto")
    {
        if ($sku == "") {
            return FALSE;
        }

        $this->db->from('product p');

        $this->db->join('category c1', 'c1.id = p.cat_id', 'INNER');

        $this->db->join('category c2', 'c2.id = p.sub_cat_id', 'INNER');

        $this->db->join('category c3', 'c3.id = p.sub_sub_cat_id', 'INNER');

        $this->db->select('p.sku, p.cat_id, p.sub_cat_id, p.sub_sub_cat_id, p.name, c1.name as cat_name, c2.name as sub_cat_name, c3.name as sub_sub_cat_name', FALSE);

        $this->db->where(array("p.sku" => $sku));

        $this->db->limit(1);

        $this->include_dto($classname);

        if ($query = $this->db->get()) {

            $ret = array();
            foreach ($query->result($classname) as $obj) {
                $ret[] = $obj;
            }

            return $ret;
        }
        //echo $this->db->last_query();
        return FALSE;
    }

    public function get_website_prod($where = array())
    {
        $sql = "SELECT COUNT(*) as total
                FROM v_prod_overview_wo_shiptype
                WHERE platform_id = ?
                AND sku = ?
                AND prod_status = '2'
                AND listing_status = 'L'
                LIMIT 1";

        if ($query = $this->db->query($sql, array($where["platform_id"], $where["sku"]))) {
            $cnt = $query->row()->total;
            if ($cnt) {
                return $this->get(array("sku" => $where["sku"]));
            } else {
                return FALSE;
            }
        }

        return FALSE;
    }

    public function get_product_w_margin_req_update($where = array(), $classname = 'Website_prod_info_dto')
    {
        $table_alias = array('v_prod_overview_w_update_time' => 'vpo',
            'supplier_cost_history' => 'sch', 'price_margin' => 'pm');
        include_once APPPATH . "helpers/string_helper.php";
        $new_where = replace_db_alias($where, $table_alias);

        if (!isset($new_where['vpo.platform_id'])) {
            $new_where['vpo.platform_id'] = 'WSGB';
        }

        $new_key_list = array_keys($new_where);

        if ($new_key_list && count($new_key_list) > 0) {
            $found = false;

            foreach ($new_key_list as $new_key) {
                if (strstr($new_key, 'vpo.prod_status')) {
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $new_where['vpo.prod_status >'] = 0;
            }
        }

        $value_list = array();

        if ($new_where && count($new_where) > 0) {
            $where_clause = '';

            foreach ($new_where as $key => $value) {
                $where_clause .= ' AND ';

                if ($this->db->_has_operator($key)) {
                    $where_clause .= "$key ?";
                } else {
                    $where_clause .= "$key = ?";
                }
                array_push($value_list, $value);
                $counter++;
            }
        }


        $sql = "SELECT DISTINCT vpo.*
                FROM v_prod_overview_w_update_time vpo
                INNER JOIN supplier_cost_history sch
                    ON (sch.prod_sku = vpo.sku)
                LEFT JOIN price_margin pm
                    ON (pm.sku = vpo.sku AND pm.platform_id = vpo.platform_id)
                WHERE
                    (vpo.price_modify_on > pm.modify_on OR sch.create_on > pm.modify_on)
                    $where_clause";

        $result = $this->db->query($sql, $value_list);

        $this->include_dto($classname);
        $result_arr = array();

        foreach ($result->result("object", $classname) as $obj) {
            $result_arr[] = $obj;
        }

        return $result_arr;
    }

    public function get_new_product_for_report($start_time = '',
                                               $end_time = '', $platform_id = 'WSGB', $classname = 'Product_cost_change_dto')
    {
        $sql = "SELECT vpo.*, a.inventory, 1 is_new
                FROM v_prod_overview_wo_shiptype vpo
                INNER JOIN
                    (SELECT inv.prod_sku, SUM(inv.inventory) inventory
                    FROM inventory inv
                    JOIN product p
                    ON (inv.prod_sku = p.sku AND p.create_on >= ?
                        AND p.create_on <= ?)
                    GROUP BY inv.prod_sku) a
                ON (a.prod_sku = vpo.sku)
                WHERE vpo.platform_id = ?";

        $resultp = $this->db->query($sql, array($start_time, $end_time, $platform_id));

        $array = $resultp->result_array();

        $this->include_dto($classname);
        $result_arr = array();
        include_once APPPATH . "helpers/object_helper.php";
        $dto = new $classname;

        foreach ($array as $row) {
            $obj = clone $dto;
            set_value($obj, $row);
            $result_arr[] = $obj;
        }

        return $result_arr;
    }

    public function get_product_shipping_override_info($platform_id = 'AMUK',
                                                       $dto_class = 'Product_shipping_override_dto')
    {
        $sql = 'SELECT p.sku, r.region_name ship_option, "false" do_not_ship,
                    "Exclusive" type, wcc.amount shipping_charge
                FROM product p
                JOIN freight_category fc
                    ON (fc.id = p.freight_cat_id)
                JOIN weight_category wc
                    ON (wc.weight = fc.weight)
                JOIN weight_cat_charge wcc
                    ON (wcc.wcat_id = wc.id AND wcc.type = \'CH\')
                JOIN region r
                    ON (r.id = wcc.region_id)
                JOIN price pr
                    ON (pr.sku = p.sku AND pr.platform_id = ?
                        AND pr.listing_status = \'L\')';

        $result = $this->db->query($sql, $platform_id);

        $this->include_dto($dto_class);
        $result_arr = array();

        foreach ($result->result("object", $dto_class) as $obj) {
            $result_arr[] = $obj;
        }

        return $result_arr;
    }

    public function get_existing_colour($where)
    {
        $this->db->from("product p");
        $this->db->join("colour c", "p.colour_id=c.id", "INNER");
        $this->db->where($where);

        $this->db->select("DISTINCT(p.colour_id) AS colour_id, c.name ", FALSE);
        if ($query = $this->db->get()) {
            $ret = array();
            $array = $query->result_array();
            foreach ($array as $row) {
                $ret[] = $row["colour_id"] . "::" . $row["name"];
            }
            return $ret;
        }
        return FALSE;
    }

    public function get_existing_version($where)
    {
        $this->db->from("product");
        $this->db->where($where);
        $this->db->select("DISTINCT(version_id) AS version_id", FALSE);
        if ($query = $this->db->get()) {
            $ret = array();
            $array = $query->result_array();
            foreach ($array as $row) {
                $ret[] = $row["version_id"];
            }
            return $ret;
        }
        return FALSE;
    }

    public function get_bundle_win_min_dcnt($res = NULL)
    {
        $sql = "SELECT b.prod_sku, MIN(p.display_quantity) as dqty
                FROM v_bundle_list b
                JOIN product p
                    ON b.component_sku = p.sku
                GROUP BY b.prod_sku
                ORDER BY b.prod_sku";

        if ($query = $this->db->query($sql)) {
            $ret = array();
            foreach ($query->result("object", "") as $obj) {
                $ret[] = array("sku" => $obj->prod_sku, "qty" => $obj->dqty);
            }
            return $ret;
        }
        return FALSE;
    }


    public function get_bundle_components_overview($where = array(), $option = array(), $classname = "Product_cost_dto")
    {
        $this->db->from('v_prod_items AS vpi');
        $this->db->join('product AS p', 'vpi.prod_sku = p.sku', 'INNER');
        $this->db->join('v_prod_overview_wo_cost AS vpo', 'vpi.item_sku = vpo.sku', 'INNER');

        if ($option["product"]) {
            $select_str = 'p.*, COALESCE(p.youtube_id, vpo.youtube_id) AS youtube_id';
            $classname = "";
        } else {
            $select_str = 'vpo.*, p.expected_delivery_date, p.warranty_in_month';
        }

        if ($option["cart"]) {
            $this->db->join('product_content pc', "pc.prod_sku = vpi.prod_sku AND pc.lang_id='" . ($option["language"] ? $option["language"] : "en") . "'", 'LEFT');
            $select_str .= ', IF(p.image IS NULL, vpo.sku, p.sku) AS sku, IF(p.image IS NULL, vpo.image, p.image) AS image, vpi.component_order, IF(component_order > -1, p.name, pc.prod_name) AS content_prod_name, pc.extra_info, vpi.discount';
        }

        $this->db->where($where);

        if (empty($option["num_rows"])) {

            if ($classname == "") {
                $classname = $this->get_vo_classname();
                $rs_include = $this->include_vo();
            } else {
                $rs_include = $this->include_dto($classname);
            }

            if ($rs_include === FALSE) {
                return FALSE;
            }

            $this->db->select($select_str, FALSE);

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
                return $rs ? ($option["limit"] == 1 ? $rs[0] : (object)$rs) : $rs;
            }
        } else {
            $this->db->select('COUNT(*) AS total');
            if ($query = $this->db->get()) {
                return $query->row()->total;
            }
        }

        return false;
    }

    public function search_by_product_name($where, $option, $classname = "product_search_list_dto")
    {
        // Keywords are matched as wild card with the product name
        $platform_id = $where['platform_id'];
        $limit = $where['limit'];
        $offset = $where['offset'];

        $f_arr = $where['skey']['formated'];
        $uf_arr = $where['skey']['unformated'];
        if (!empty($f_arr)) {
            $f_skey = implode(" ", $f_arr);
        }
        $uf_skey = preg_quote(implode(" ", $uf_arr));
        $to_date = date("Y-m-d", time());
        $subtract = time() - (86400 * 7);
        $from_date = date("Y-m-d", $subtract);

        $sql = "SELECT
                    p.sku, p.prod_grp_cd, p.colour_id, p.version_id, p.name, p.freight_cat_id, p.cat_id, p.sub_cat_id, p.sub_sub_cat_id, p.brand_id, p.clearance, p.quantity,
                    p.display_quantity, p.website_quantity, p.ex_demo, p.rrp, p.image, p.flash, p.youtube_id, p.ean, p.mpn, p.upc, p.discount, p.proc_status, p.website_status,
                    p.sourcing_status, p.status, pc.create_on, p.create_at, p.create_by, p.modify_on, p.modify_at, p.modify_by,
                    IFNULL(pc.prod_name, p.name) prod_name, cat.name cat_name, br.brand_name, pc.short_desc, pc.detail_desc,
                    pr.platform_id, ex.from_currency_id, IFNULL(pr2.platform_id, '$platform_id') site_platform_id, ex.to_currency_id,
                    ROUND(IF(pr2.price>0, pr2.price, pr.price*ex.rate),2) price, vpb.with_bundle ";

        if ($option['orderby'] == 'b.sold_amount') {
            $sql .= ",a.sold_amount";
        }

        $sql .= "
                FROM v_product p
                JOIN product_content pc
                    ON (pc.prod_sku = p.sku AND p.status = 2 AND pc.lang_id = '" . get_lang_id() . "')
                JOIN category cat
                    ON (p.cat_id = cat.id)
                JOIN brand br
                    ON (p.brand_id = br.id)
                LEFT JOIN (price pr, v_default_platform_id vdp)
                    ON (p.sku = pr.sku AND pr.platform_id = vdp.platform_id AND pr.listing_status = 'L')
                LEFT JOIN price pr2
                    ON (p.sku = pr2.sku AND pr2.platform_id = '$platform_id')
                JOIN platform_biz_var pbv
                    ON (pbv.selling_platform_id = pr2.platform_id)
                JOIN exchange_rate ex
                    ON (pbv.platform_currency_id = ex.to_currency_id AND ex.from_currency_id = 'GBP')
                JOIN v_product_w_bundle vpb
                    ON (p.sku = vpb.sku)
                ";

        if ($option['orderby'] == 'b.sold_amount') {
            $sql .= "
                LEFT JOIN
                (
                    SELECT soid.item_sku, SUM(soid.qty) as sold_amount
                    FROM so
                    JOIN so_item_detail soid
                        ON (so.so_no  = soid.so_no)
                    WHERE so.create_on > '$from_date 00:00:00' AND so.create_on < '$to_date 23:59:59'
                    GROUP BY soid.item_sku
                )a
                    ON (a.item_sku = p.sku)
                ";
        }
        foreach ($uf_arr AS $key) {
            $reg_arr[] = "pc.prod_name REGEXP '" . $key . "'";
        }
        $reg_script = implode(" OR ", $reg_arr);
        $sql .= "WHERE ({$reg_script})";
        /*
        $sql .= "
                WHERE (pc.prod_name REGEXP '$uf_skey'";

        if(!empty($f_skey))
        {
            $sql .= " OR pc.prod_name REGEXP '$f_skey'";
        }
        */

        $sql .= " AND (pr2.listing_status = 'L') AND ((pr2.price OR pr.price) > 0)";

        if ($where['cat_name']) {
            $sql .= " AND cat.name = '" . $where['cat_name'] . "'";
        }
        if ($where['brand_name']) {
            $sql .= " AND br.brand_name = '" . $where['brand_name'] . "'";
        }
        if ($where['brand_id']) {
            $sql .= " AND br.id = '" . $where['brand_id'] . "'";
        }
        if ($where['min_price'] || $where['max_price']) {
            $sql .= " AND (ROUND(IF(pr2.price>0, pr2.price, pr.price*ex.rate),2) > " . $where['min_price'];
            if ($where['max_price']) {
                $sql .= " AND ROUND(IF(pr2.price>0, pr2.price, pr.price*ex.rate),2) <= " . $where['max_price'];
            }
            $sql .= ")";
        }

        if ($where['with_bundle']) {
            $sql .= " AND vpb.with_bundle = '1'";
        }

        $sql .= "
                GROUP BY p.sku, p.prod_grp_cd, p.colour_id, p.version_id, p.name, p.freight_cat_id, p.cat_id, p.sub_cat_id, p.brand_id, p.clearance, p.quantity, p.display_quantity, p.website_quantity,
                        p.ex_demo, p.rrp, p.image, p.flash, p.youtube_id, p.ean, p.mpn, p.upc, p.discount, p.proc_status, p.website_status, p.sourcing_status, p.status, p.create_on
                ORDER BY pc.prod_name
                ";

        if (!$option['num_rows']) {
            if ($option['orderby']) {
                $sql = "SELECT *
                        FROM
                        (
                            $sql
                        )b
                        ORDER BY " . $option['orderby'];
            }

            if ($option['groupby']) {
                $sql = "
                    SELECT c.sku, c.prod_name, c.price, c.brand_id, brand_name, cat_name, count(*) as num
                    FROM
                    (
                        $sql
                    )c
                    GROUP BY " . $option['groupby'];
            }

            if ($limit) {
                $sql .= " LIMIT $limit";
            }
            if ($offset) {
                $sql .= " OFFSET $offset";
            }
            $this->include_dto($classname);

            $rs = array();

            if ($query = $this->db->query($sql)) {
                if ($this->debug == 1) {
                    echo "<br>First Level Search<br>";
                    echo $this->db->last_query();
                    echo "<br>";
                }
                foreach ($query->result($classname) as $obj) {
                    $rs[] = $obj;
                }
                return $rs;
            }
        } else {
            $sql = "
                    SELECT COUNT(*) AS total
                    FROM
                    (
                        $sql
                    )t
                    ";
            if ($query = $this->db->query($sql)) {
                return $query->row()->total;
            }
        }

        return FALSE;
    }

    public function search_by_keyword_full_match($where, $option, $classname = "product_search_list_dto")
    {
        /*  Searching Criteria
         *  1. Every keyword provided by the customer must be appeared in the product keyword found.
         *  2. Keywords are matched as wild card with the product keyword found.
         *  3. Product name of each language is treated as keyword.
         *
         *  Default Config:
         *  DEFAULT_LANG    : en        // Keywords of this language will be included into search scope for any language.
         *  DEFAULT_MIN_KEY : 2         // Minimum keywords required to be inputed by user to produce result
         */

        $default['DEFAULT_MIN_KEY'] = "2";
        $default['DEFAULT_LANG'] = "en";
        $min_key = $default['DEFAULT_MIN_KEY'];
        $d_lang = $default['DEFAULT_LANG'];
        $p_lang = $where['lang_id'];
        $p_id = $where['platform_id'];

        $limit = $where['limit'];
        $offset = $where['offset'];

        $to_date = date("Y-m-d", time());
        $subtract = time() - (86400 * 7);
        $from_date = date("Y-m-d", $subtract);

        if ($where['skey'] && (sizeof($where['skey']['formated']) >= $min_key && sizeof($where['skey']['unformated']) >= $min_key)) {
            $sql = "SELECT
                        p.sku, p.prod_grp_cd, p.colour_id, p.version_id, p.name, p.freight_cat_id, p.cat_id, p.sub_cat_id, p.sub_sub_cat_id, p.brand_id, p.clearance, p.quantity,
                        p.display_quantity, p.website_quantity, p.ex_demo, p.rrp, p.image, p.flash, p.youtube_id, p.ean, p.mpn, p.upc, p.discount, p.proc_status, p.website_status,
                        p.sourcing_status, p.status, pc.create_on, p.create_at, p.create_by, p.modify_on, p.modify_at, p.modify_by,
                        IFNULL(pc.prod_name, p.name) prod_name, cat.name cat_name, br.brand_name, pc.short_desc, pc.detail_desc,
                        pr.platform_id, ex.from_currency_id, IFNULL(pr2.platform_id, '$platform_id') site_platform_id, ex.to_currency_id,
                        ROUND(IF(pr2.price>0, pr2.price, pr.price*ex.rate),2) price, vpb.with_bundle ";

            if ($option['orderby'] == 'b.sold_amount') {
                $sql .= ",a.sold_amount";
            }


            $sql .= "
                    FROM v_product p
                    JOIN product_content pc
                        ON pc.prod_sku = p.sku AND p.status = 2 AND pc.lang_id = '" . get_lang_id() . "'
                    JOIN category cat
                        ON p.cat_id = cat.id
                    JOIN brand br
                        ON p.brand_id = br.id
                    LEFT JOIN (price pr, v_default_platform_id vdp)
                        ON p.sku = pr.sku AND pr.platform_id = vdp.platform_id AND pr.listing_status = 'L'
                    LEFT JOIN price pr2
                        ON p.sku = pr2.sku AND pr2.platform_id = '$p_id'
                    JOIN platform_biz_var pbv
                        ON pbv.selling_platform_id = '$p_id'
                    JOIN exchange_rate ex
                        ON pbv.platform_currency_id = ex.to_currency_id AND ex.from_currency_id = 'GBP'
                    JOIN v_product_w_bundle vpb
                        ON (p.sku = vpb.sku)
                    LEFT JOIN product_type trial
                        ON (p.sku = trial.sku AND trial.type_id = 'TRIAL')
                    ";


            if ($option['prod_video']) {
                $sql .= "
                        JOIN product_video pv
                            ON (p.sku = pv.sku AND pv.country_id = '" . PLATFORMCOUNTRYID . "')
                        ";
            }

            if ($where['prod_type']) {
                $sql .= "
                        JOIN product_type pt
                            ON (p.sku = pt.sku AND pt.type_id = '" . $where['prod_type'] . "')";
            }

            if ($option['orderby'] == 'b.sold_amount') {
                $sql .= "
                    LEFT JOIN
                    (
                        SELECT soid.item_sku, SUM(soid.qty) as sold_amount
                        FROM so
                        JOIN so_item_detail soid
                            ON (so.so_no  = soid.so_no)
                        WHERE so.create_on > '$from_date 00:00:00' AND so.create_on < '$to_date 23:59:59'
                        GROUP BY soid.item_sku
                    )a
                        ON (a.item_sku = p.sku)
                    ";
            }

            foreach ($where['skey']['formated'] as $k => $v) {
                $pk_alias = "pk" . $k;
                $sql .= " JOIN product_keyword AS $pk_alias
                        ON p.sku = $pk_alias.sku AND ";

                if ($d_lang == $p_lang) {
                    $sql .= " $pk_alias.lang_id = '$d_lang'";
                } else {
                    $sql .= "($pk_alias.lang_id = '$d_lang' OR $pk_alias.lang_id = '$p_lang')";
                }

                $sql .= " AND $pk_alias.keyword REGEXP '$v'";
            }

            $sql .= " WHERE (pr2.listing_status = 'L') AND ((pr2.price OR pr.price) > 0 || trial.type_id = 'TRIAL')";

            if ($where['cat_name']) {
                $sql .= " AND cat.name = '" . $where['cat_name'] . "'";
            }

            if ($where['brand_name']) {
                $sql .= " AND br.brand_name = '" . $where['brand_name'] . "'";
            }

            if ($where['brand_id']) {
                $sql .= " AND br.id = '" . $where['brand_id'] . "'";
            }

            if ($where['min_price'] || $where['max_price']) {
                $sql .= " AND (ROUND(IF(pr2.price>0, pr2.price, pr.price*ex.rate),2) > " . $where['min_price'];
                if ($where['max_price']) {
                    $sql .= " AND ROUND(IF(pr2.price>0, pr2.price, pr.price*ex.rate),2) <= " . $where['max_price'];
                }
                $sql .= ")";
            }

            if ($where['with_bundle']) {
                $sql .= " AND vpb.with_bundle = '1'";
            }

            $sql .= " GROUP BY p.sku, p.prod_grp_cd, p.colour_id, p.version_id, p.name, p.freight_cat_id, p.cat_id, p.sub_cat_id, p.brand_id, p.clearance, p.quantity, p.display_quantity, p.website_quantity,
                        p.ex_demo, p.rrp, p.image, p.flash, p.youtube_id, p.ean, p.mpn, p.upc, p.discount, p.proc_status, p.website_status, p.sourcing_status, p.status, p.create_on";

            if (!$option['num_rows']) {
                if ($option['orderby']) {
                    $sql = "SELECT *
                            FROM
                            (
                                $sql
                            )b
                            ORDER BY " . $option['orderby'];
                }

                if ($option['groupby']) {
                    $sql = "
                    SELECT c.sku, c.prod_name, c.price, c.brand_id, brand_name, cat_name, count(*) as num
                    FROM
                    (
                        $sql
                    )c
                    GROUP BY " . $option['groupby'];
                }


                if ($limit) {
                    $sql .= " LIMIT $limit";
                }
                if ($offset) {
                    $sql .= " OFFSET $offset";
                }

                $this->include_dto($classname);

                $rs = array();

                if ($query = $this->db->query($sql)) {
                    if ($this->debug == 1) {
                        echo "<br>Second Level Search<br>";
                        echo $this->db->last_query();
                        echo "<br>";
                    }
                    foreach ($query->result($classname) as $obj) {
                        $rs[] = $obj;
                    }
                    return (array)$rs;
                }
            } else {
                $sql = "
                        SELECT COUNT(*) AS total
                        FROM
                        (
                            $sql
                        )t
                        ";
                if ($query = $this->db->query($sql)) {
                    return $query->row()->total;
                }
            }
        }
        return FALSE;
    }

    public function search_by_keyword_partial_match($where, $option, $classname = "product_search_list_dto")
    {
        /*  Searching Criteria
         *  1. Any of the keyword provided by the customer matches with the product keyword.
         *  2. Keywords are matched as wild card with the product keyword found.
         *  3. Product name of each language is treated as keyword.
         *
         *  Default Config:
         *  DEFAULT_LANG    : en        // Keywords of this language will be included into search scope for any language.
         *  DEFAULT_MIN_KEY : 0         // Minimum keywords required to be inputed by user to produce result
         */

        $default['DEFAULT_MIN_KEY'] = "0";
        $default['DEFAULT_LANG'] = "en";
        $min_key = $default['DEFAULT_MIN_KEY'];
        $d_lang = $default['DEFAULT_LANG'];
        $p_lang = $where['lang_id'];
        $p_id = $where['platform_id'];

        $limit = $where['limit'];
        $offset = $where['offset'];

        $to_date = date("Y-m-d", time());
        $subtract = time() - (86400 * 7);
        $from_date = date("Y-m-d", $subtract);

        if ($where['skey'] && sizeof($where['skey']['formated']) >= $min_key && sizeof($where['skey']['unformated']) >= $min_key) {
            $sql = "SELECT
                        p.sku, p.prod_grp_cd, p.colour_id, p.version_id, p.name, p.freight_cat_id, p.cat_id, p.sub_cat_id, p.sub_sub_cat_id, p.brand_id, p.clearance, p.quantity,
                        p.display_quantity, p.website_quantity, p.ex_demo, p.rrp, p.image, p.flash, p.youtube_id, p.ean, p.mpn, p.upc, p.discount, p.proc_status, p.website_status,
                        p.sourcing_status, p.status, pc.create_on, p.create_at, p.create_by, p.modify_on, p.modify_at, p.modify_by,
                        IFNULL(pc.prod_name, p.name) prod_name, cat.name cat_name, br.brand_name, pc.short_desc, pc.detail_desc,
                        pr.platform_id, ex.from_currency_id, IFNULL(pr2.platform_id, '$platform_id') site_platform_id, ex.to_currency_id,
                        ROUND(IF(pr2.price>0, pr2.price, pr.price*ex.rate),2) price, vpb.with_bundle ";
            if ($option['orderby'] == 'b.sold_amount') {
                $sql .= ",a.sold_amount ";
            }

            $sql .= "FROM v_product p
                    JOIN product_content pc
                        ON pc.prod_sku = p.sku AND p.status = 2 AND pc.lang_id = '" . get_lang_id() . "'
                    JOIN category cat
                        ON p.cat_id = cat.id
                    JOIN brand br
                        ON p.brand_id = br.id
                    LEFT JOIN (price pr, v_default_platform_id vdp)
                        ON p.sku = pr.sku AND pr.platform_id = vdp.platform_id AND pr.listing_status = 'L'
                    LEFT JOIN price pr2
                        ON p.sku = pr2.sku AND pr2.platform_id = '$p_id'
                    JOIN platform_biz_var pbv
                        ON pbv.selling_platform_id = '$p_id'
                    JOIN exchange_rate ex
                        ON pbv.platform_currency_id = ex.to_currency_id AND ex.from_currency_id = 'GBP'
                    JOIN v_product_w_bundle vpb
                    ON (p.sku = vpb.sku)
                    LEFT JOIN product_type trial
                    ON (p.sku = trial.sku AND trial.type_id = 'TRIAL')
                    ";

            if ($option['prod_video']) {
                $sql .= "
                        JOIN product_video pv
                            ON (p.sku = pv.sku AND pv.country_id = '" . PLATFORMCOUNTRYID . "')
                        ";
            }

            if ($where['prod_type']) {
                $sql .= "
                        JOIN product_type pt
                            ON (p.sku = pt.sku AND pt.type_id = '" . $where['prod_type'] . "')";
            }

            if ($option['orderby'] == 'b.sold_amount') {
                $sql .= "
                    LEFT JOIN
                    (
                        SELECT soid.item_sku, SUM(soid.qty) as sold_amount
                        FROM so
                        JOIN so_item_detail soid
                            ON (so.so_no  = soid.so_no)
                        WHERE so.create_on > '$from_date 00:00:00' AND so.create_on < '$to_date 23:59:59'
                        GROUP BY soid.item_sku
                    )a
                        ON (a.item_sku = p.sku)
                    ";
            }
            $sql .= "JOIN product_keyword pk
                        ON pk.sku = p.sku AND (pk.lang_id = '$d_lang' OR pk.lang_id = '$p_lang')";

            $sql .= " WHERE (pr2.listing_status = 'L') AND ((pr2.price OR pr.price) > 0 || trial.type_id = 'TRIAL')";

            $isfirst = TRUE;
            if (!empty($where['skey']['formated'])) {
                foreach ($where['skey']['formated'] as $v) {
                    $sql .= ($isfirst ? " AND (" : " OR ") . " pk.keyword REGEXP '$v'";
                    $isfirst = FALSE;
                }
            }

            $sql .= ")";

            if ($where['cat_name']) {
                $sql .= " AND cat.name = '" . $where['cat_name'] . "'";
            }

            if ($where['brand_name']) {
                $sql .= " AND br.brand_name = '" . $where['brand_name'] . "'";
            }


            if ($where['brand_id']) {
                $sql .= " AND br.id = '" . $where['brand_id'] . "'";
            }

            if ($where['min_price'] || $where['max_price']) {

                $sql .= " AND (ROUND(IF(pr2.price>0, pr2.price, pr.price*ex.rate),2) > " . $where['min_price'];
                if ($where['max_price']) {
                    $sql .= " AND ROUND(IF(pr2.price>0, pr2.price, pr.price*ex.rate),2) <= " . $where['max_price'];
                }
                $sql .= ")";
            }

            if ($where['with_bundle']) {
                $sql .= " AND vpb.with_bundle = '1'";
            }

            $sql .= " GROUP BY p.sku, p.prod_grp_cd, p.colour_id, p.version_id, p.name, p.freight_cat_id, p.cat_id, p.sub_cat_id, p.brand_id, p.clearance, p.quantity, p.display_quantity, p.website_quantity,
                        p.ex_demo, p.rrp, p.image, p.flash, p.youtube_id, p.ean, p.mpn, p.upc, p.discount, p.proc_status, p.website_status, p.sourcing_status, p.status, p.create_on
                    ORDER BY p.name";

            if (!$option['num_rows']) {
                if ($option['orderby']) {
                    $sql = "SELECT *
                            FROM
                            (
                                $sql
                            )b
                            ORDER BY " . $option['orderby'];
                }

                if ($option['groupby']) {
                    $sql = "
                        SELECT c.sku, c.prod_name, c.price, c.brand_id, brand_name, cat_name, count(*) as num
                        FROM
                        (
                            $sql
                        )c
                        GROUP BY " . $option['groupby'] .
                        " ORDER BY " . $option['groupby'];
                }

                if ($limit) {
                    $sql .= " LIMIT $limit";
                }
                if ($offset) {
                    $sql .= " OFFSET $offset";
                }

                $this->include_dto($classname);

                $rs = array();

                if ($query = $this->db->query($sql)) {
                    if ($this->debug == 1) {
                        echo "<br>Third Level Search<br>";
                        echo $this->db->last_query();
                        echo "<br>";
                    }
                    foreach ($query->result($classname) as $obj) {
                        $rs[] = $obj;
                    }
                    return (array)$rs;
                }
            } else {
                $sql = "
                        SELECT COUNT(*) AS total
                        FROM
                        (
                            $sql
                        )t
                        ";
                if ($query = $this->db->query($sql)) {
                    return $query->row()->total;
                }
            }
        }
        return FALSE;
    }

    public function search_without_keyword($where, $option, $classname = "product_search_list_dto")
    {
        $platform_id = $where['platform_id'];
        $limit = $where['limit'];
        $offset = $where['offset'];

        $to_date = date("Y-m-d", time());
        $subtract = time() - (86400 * 7);
        $from_date = date("Y-m-d", $subtract);

        $sql = "SELECT
                    p.sku, p.prod_grp_cd, p.colour_id, p.version_id, p.name, p.freight_cat_id, p.cat_id, p.sub_cat_id, p.sub_sub_cat_id, p.brand_id, p.clearance, p.quantity,
                    p.display_quantity, p.website_quantity, p.ex_demo, p.rrp, p.image, p.flash, p.youtube_id, p.ean, p.mpn, p.upc, p.discount, p.proc_status, p.website_status,
                    p.sourcing_status, p.status, pc.create_on, p.create_at, p.create_by, p.modify_on, p.modify_at, p.modify_by,
                    IFNULL(pc.prod_name, p.name) prod_name, cat.name cat_name, br.brand_name, pc.short_desc, pc.detail_desc,
                    pr.platform_id, ex.from_currency_id, IFNULL(pr2.platform_id, '$platform_id') site_platform_id, ex.to_currency_id,
                    ROUND(IF(pr2.price>0, pr2.price, pr.price*ex.rate),2) price, vpb.with_bundle ";

        if ($option['orderby'] == 'b.sold_amount') {
            $sql .= ",a.sold_amount";
        }

        $sql .= "
                FROM v_product p
                JOIN product_content pc
                    ON (pc.prod_sku = p.sku AND p.status = 2) AND pc.lang_id = '" . get_lang_id() . "'
                JOIN category cat
                    ON (p.cat_id = cat.id)
                JOIN brand br
                    ON (p.brand_id = br.id)
                LEFT JOIN (price pr, v_default_platform_id vdp)
                    ON (p.sku = pr.sku AND pr.platform_id = vdp.platform_id AND pr.listing_status = 'L')
                LEFT JOIN price pr2
                    ON (p.sku = pr2.sku AND pr2.platform_id = '$platform_id')
                JOIN platform_biz_var pbv
                    ON (pbv.selling_platform_id = pr2.platform_id)
                JOIN exchange_rate ex
                    ON (pbv.platform_currency_id = ex.to_currency_id AND ex.from_currency_id = 'GBP')
                JOIN v_product_w_bundle vpb
                    ON (p.sku = vpb.sku)
                LEFT JOIN product_type trial
                ON (p.sku = trial.sku AND trial.type_id = 'TRIAL')
                ";

        if ($option['orderby'] == 'b.sold_amount') {
            $sql .= "
                LEFT JOIN
                (
                    SELECT soid.item_sku, SUM(soid.qty) as sold_amount
                    FROM so
                    JOIN so_item_detail soid
                        ON (so.so_no  = soid.so_no)
                    WHERE so.create_on > '$from_date 00:00:00' AND so.create_on < '$to_date 23:59:59'
                    GROUP BY soid.item_sku
                )a
                    ON (a.item_sku = p.sku)
                ";
        }

        if ($where['prod_type']) {
            $sql .= "
                    JOIN product_type pt
                        ON (p.sku = pt.sku) AND pt.type_id = '" . $where['prod_type'] . "'";
        }

        if ($option['prod_video']) {
            $sql .= "
                    JOIN product_video pv
                        ON (p.sku = pv.sku AND pv.country_id = '" . PLATFORMCOUNTRYID . "')
                    ";
        }

        $sql .= " WHERE (pr2.listing_status = 'L') AND ((pr2.price OR pr.price) > 0 || trial.type_id = 'TRIAL') ";

        if ($where['cat_id']) {
            $sql .= " AND (p.cat_id = '" . $where['cat_id'] . "' OR p.sub_cat_id = '" . $where['cat_id'] . "')";
        }
        if ($where['cat_name']) {
            $sql .= " AND cat.name = '" . $where['cat_name'] . "'";
        }
        if ($where['brand_name']) {
            $sql .= " AND br.brand_name = '" . $where['brand_name'] . "'";
        }
        if ($where['brand_id']) {
            $sql .= " AND br.id = '" . $where['brand_id'] . "'";
        }
        if ($where['min_price'] || $where['max_price']) {
            $sql .= ($is_first ? " WHERE " : " AND ") . "(ROUND(IF(pr2.price>0, pr2.price, pr.price*ex.rate),2) > " . $where['min_price'];
            if ($where['max_price']) {
                $sql .= " AND ROUND(IF(pr2.price>0, pr2.price, pr.price*ex.rate),2) <= " . $where['max_price'];
            }
            $sql .= ")";
        }

        if ($where['with_bundle']) {
            $sql .= " AND vpb.with_bundle = 1";
        }

        $sql .= "
                GROUP BY p.sku, p.prod_grp_cd, p.colour_id, p.version_id, p.name, p.freight_cat_id, p.cat_id, p.sub_cat_id, p.brand_id, p.clearance, p.quantity, p.display_quantity, p.website_quantity,
                        p.ex_demo, p.rrp, p.image, p.flash, p.youtube_id, p.ean, p.mpn, p.upc, p.discount, p.proc_status, p.website_status, p.sourcing_status, p.status, p.create_on
                ORDER BY pc.prod_name
                ";

        if (!$option['num_rows']) {
            if ($option['orderby']) {
                $sql = "SELECT *
                        FROM
                        (
                            $sql
                        )b
                        ORDER BY " . $option['orderby'];
            }

            if ($option['groupby']) {
                $sql = "
                    SELECT c.sku, c.prod_name, c.price, c.brand_id, brand_name, cat_name, count(*) as num
                    FROM
                    (
                        $sql
                    )c
                    GROUP BY " . $option['groupby'];
            }

            if ($limit) {
                $sql .= " LIMIT $limit";
            }
            if ($offset) {
                $sql .= " OFFSET $offset";
            }
            $this->include_dto($classname);

            $rs = array();

            if ($query = $this->db->query($sql)) {
                if ($this->debug == 1) {
                    echo "<br>No Keyword Search<br>";
                    echo $this->db->last_query();
                    echo "<br>";
                }
                foreach ($query->result($classname) as $obj) {
                    $rs[] = $obj;
                }
                return $rs;
            }
            //echo $this->db->last_query();
        } else {
            $sql = "
                    SELECT COUNT(*) AS total
                    FROM
                    (
                        $sql
                    )t
                    ";
            if ($query = $this->db->query($sql)) {
                return $query->row()->total;
            }
        }
        return FALSE;
    }

    public function get_prod_overview($where = array(), $option = array(), $classname = "Product_cost_dto")
    {
        $this->db->from('v_prod_overview_wo_shiptype');
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, '*');
    }

    public function get_prod_overview_wo_cost($where = array(), $option = array(), $classname = "Product_cost_dto")
    {
        $this->db->from('v_prod_overview_wo_cost');
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, '*');
    }

    public function get_prod_overview_wo_shiptype($where = array(), $option = array(), $classname = "Product_cost_dto")
    {
        $to_currency_id = isset($option["to_currency_id"]) ? $option["to_currency_id"] : "GBP";
        $this->db->from('v_prod_overview_wo_cost AS vpo');
        $this->db->join('exchange_rate AS er', 'er.from_currency_id = vpo.platform_currency_id AND er.to_currency_id = "' . $to_currency_id . '"', 'LEFT');
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, 'vpo.*, vpo.price *  er.rate AS price');
    }

    public function get_prod_overview_wo_cost_w_rate($where = array(), $option = array(), $classname = "Product_cost_dto")
    {
        $to_currency_id = isset($option["to_currency_id"]) ? $option["to_currency_id"] : "GBP";
        $this->db->from('v_prod_overview_wo_cost AS vpo');
        $this->db->join('exchange_rate AS er', 'er.from_currency_id = vpo.platform_currency_id AND er.to_currency_id = "' . $to_currency_id . '"', 'LEFT');
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, 'vpo.*, vpo.price *  er.rate AS price');
    }

    public function get_prod_overview_wo_cost_w_content_name($where = array(), $option = array(), $classname = "Product_cost_dto")
    {
        $this->db->from('v_prod_overview_wo_cost AS vpo');
        $this->db->join('product_content AS pc', 'vpo.sku = pc.prod_sku', 'LEFT');
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, 'vpo.*, COALESCE(pc.prod_name, vpo.prod_name) AS prod_name');
    }

    public function get_prod_w_content_name($where = array(), $option = array())
    {
        $this->db->from('product AS p');
        $this->db->join('product_content AS pc', 'p.sku = pc.prod_sku', 'LEFT');
        $this->include_vo($classname = $this->get_vo_classname());
        return $this->common_get_list($where, $option, $classname, 'p.*, COALESCE(pc.prod_name, p.name) AS name');
    }

    public function get_prod_overview_extended($where = array(), $option = array(), $classname = "Product_cost_dto")
    {
        $this->db->from('v_prod_overview_extended vpoe');

        $select_str = "vpoe.sku,vpoe.prod_grp_cd,vpoe.version_id,vpoe.colour_id,vpoe.platform_id,vpoe.platform_region_id,
                        vpoe.platform_country_id,vpoe.vat_percent,vpoe.payment_charge_percent,vpoe.declared_pcent,vpoe.duty_pcent,
                        vpoe.cc_code,vpoe.cc_desc,vpoe.admin_fee,vpoe.freight_cost,vpoe.delivery_cost,vpoe.supplier_cost,vpoe.purchaser_updated_date,
                        vpoe.delivery_charge,vpoe.prod_weight,vpoe.free_delivery_limit,vpoe.quantity,vpoe.clearance,vpoe.website_quantity,
                        vpoe.proc_status,vpoe.website_status,vpoe.sourcing_status,vpoe.cat_id,vpoe.sub_cat_id,vpoe.sub_sub_cat_id,vpoe.brand_id,
                        vpoe.image,vpoe.supplier_id,vpoe.freight_cat_id,vpoe.ean,vpoe.mpn,vpoe.upc,vpoe.prod_status,vpoe.display_quantity,vpoe.youtube_id,
                        vpoe.platform_commission,vpoe.platform_currency_id,vpoe.language_id,vpoe.price,vpoe.current_platform_price,vpoe.default_platform_converted_price,
                        vpoe.platform_code,vpoe.listing_status,vpoe.title,vpoe.ext_ref_1,vpoe.ext_ref_2,vpoe.ext_ref_3,vpoe.ext_ref_4,vpoe.ext_qty,
                        vpoe.ext_item_id,vpoe.ext_status,vpoe.action,vpoe.remark,vpoe.handling_time ";

        if ($option["desc_lang"]) {
            $this->db->join("product_content pc", "pc.lang_id = vpoe.language_id and pc.prod_sku = vpoe.sku", "LEFT");
            $select_str .= ", p.lang_restricted, IFNULL(pc.prod_name, vpoe.prod_name) AS prod_name, pc.detail_desc, pc.model_1, pc.model_2, pc.model_3, pc.model_4, pc.model_5 ";
            $this->db->join("product p", "p.sku = vpoe.sku", "LEFT");
        } else {
            $select_str .= ", vpoe.prod_name";
        }
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, $select_str);
    }

    // TODO
    // will remove
    public function is_trial_software($sku = "")
    {
        $sql = "SELECT IF(COUNT(*), 1, 0) AS is_trial
                        FROM product_type pt
                        WHERE pt.sku = '$sku' AND pt.type_id = 'TRIAL'";

        if ($query = $this->db->query($sql)) {
            return $query->row()->is_trial;
        }
    }

    // TODO
    // will remove
    public function is_software($sku = "")
    {
        $sql = "SELECT IF(COUNT(*), 1, 0) AS is_software
                        FROM product_type pt
                        WHERE pt.sku = '$sku' AND pt.type_id = 'VIRTUAL'";

        if ($query = $this->db->query($sql)) {
            return $query->row()->is_software;
        }
    }

    // TODO
    // will remove
    public function get_product_type_w_sku($sku = "")
    {
        $sql = "SELECT type_id
                FROM product_type pt
                WHERE pt.sku = '$sku'";

        if ($query = $this->db->query($sql)) {
            foreach ($query->result() as $row) {
                $res[$row->type_id] = 1;
            }
            return $res;
        }
        return FALSE;
    }

    public function get_prod_w_sales($where = array(), $option = array())
    {
        $past_day = isset($option["past_day"]) ? $option["past_day"] : 2;

        $this->db->from('product AS p');
        $this->db->join('(SELECT item_sku, SUM(qty) AS sales
                        FROM so_item_detail AS soid
                        JOIN so
                            ON so.so_no = soid.so_no
                            AND so.status > 2
                            AND so.order_create_date >= DATE_SUB(NOW(), INTERVAL ' . $past_day . ' DAY)
                        GROUP BY item_sku) AS s', 'p.sku = s.item_sku', 'LEFT');
        $this->include_vo($classname = $this->get_vo_classname());
        return $this->common_get_list($where, $option, $classname, 'p.*');
    }

    public function get_website_cat_page_product_list($where = array(), $option = array())
    {
        $this->db->from('product AS p');
        $this->db->join('price AS pr', 'p.sku = pr.sku AND pr.listing_status = "L" AND p.status = "2"', 'INNER');
        $this->db->join('category AS cat', 'cat.id = p.cat_id AND cat.status = 1', 'INNER');
        $this->db->join('category AS sc', 'sc.id = p.sub_cat_id AND sc.status = 1', 'INNER');
        $this->db->join('category AS ssc', 'ssc.id = p.sub_sub_cat_id AND ssc.status = 1', 'LEFT');
        $this->db->join('brand AS br', 'br.id = p.brand_id AND br.status = 1', 'INNER');
        $this->db->where($where);

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

            #SBF1905, push all the Out of stock to bottom
            #SBF2580, push all the Arriving stock to bottom before Out of stock

            $this->db->select('*, if(p.website_status = "O",1,0) is_oos, if(p.website_status = "A",1,0) is_arr');

            if ($query = $this->db->get()) {
                // var_dump($this->db->last_query()); die();
                $ret = array();
                $result = $query->result_array();
                foreach ($result as $row) {
                    $ret[] = $row["sku"];
                }

                return $ret;
            }
        } else {
            $this->db->select('COUNT(*) AS total');
            if ($query = $this->db->get()) {
                return $query->row()->total;
            }
        }

        return FALSE;
    }

    public function get_admin_product_feed_dto_old($where = array(), $option = array(), $classname = "Admin_product_feed_dto")
    {
        $this->db->from("product p");
        $this->db->join("sku_mapping map", "map.sku = p.sku and map.ext_sys = 'WMS'", "LEFT");
        $this->db->join("product_content pc", "map.sku = pc.prod_sku AND pc.lang_id = 'en'", "LEFT");
        $this->db->join("category cat", "cat.id = p.cat_id", "LEFT");
        $this->db->join("category scat", "scat.id = p.sub_cat_id", "LEFT");
        $this->db->join("category sscat", "sscat.id = p.sub_sub_cat_id", "LEFT");
        $this->db->join("price pr", "p.sku = pr.sku AND pr.platform_id LIKE 'WEB%'", "LEFT");
        // $this->db->where("pr.listing_status", 'L');
        $this->db->group_by("p.sku");
        $this->db->order_by("map.ext_sku");
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, "
                cat.name AS cat, scat.name AS sub_cat, sscat.name AS sub_sub_cat,
                p.name AS product_name,
                p.status as status, p.website_status,
                p.create_by, p.create_on, p.modify_by, p.modify_on,
                pc.youtube_id_1, pc.youtube_caption_1, pc.youtube_id_2, pc.youtube_caption_2,
                map.ext_sku AS master_sku,
                p.sku,
                pc.prod_name AS website_display_name,
                GROUP_CONCAT(pr.price) price,
                GROUP_CONCAT(pr.platform_id) platform_id
                ");

    }

    public function get_admin_product_feed_dto($where = array(), $option = array(), $classname = "Admin_product_feed_dto")
    {
        $this->db->from("product p");
        $this->db->join("sku_mapping map", "map.sku = p.sku and map.ext_sys = 'WMS'", "LEFT");
        $this->db->join("supplier_prod sp", "sp.prod_sku = p.sku and sp.order_default = 1");
        $this->db->join("supplier s", "s.id = sp.supplier_id");
        $this->db->join("product_content pc", "map.sku = pc.prod_sku AND pc.lang_id = 'en'", "LEFT");
        $this->db->join("category cat", "cat.id = p.cat_id", "LEFT");
        $this->db->join("category scat", "scat.id = p.sub_cat_id", "LEFT");
        $this->db->join("category sscat", "sscat.id = p.sub_sub_cat_id", "LEFT");

        if (isset($option['platform_type']) && $option['platform_type'] == "marketplace")
            $this->db->join("price pr", "p.sku = pr.sku AND pr.platform_id NOT LIKE 'WEB%'", "LEFT");
        else
            $this->db->join("price pr", "p.sku = pr.sku AND pr.platform_id LIKE 'WEB%'", "LEFT");

        $this->db->join("platform_biz_var pbv", "pbv.selling_platform_id = pr.platform_id", "LEFT");
        $this->db->join("delivery_time dt", "pr.delivery_scenarioid = dt.scenarioid AND pbv.platform_country_id  = dt.country_id", "LEFT");
        // $this->db->where("pr.listing_status", 'L');
        $this->db->group_by("p.sku");
        $this->db->where($where);
        $this->db->select("
                    IFNULL(map.ext_sku, ' ') AS master_sku,
                    p.sku,
                    p.name AS product_name,
                    pc.prod_name AS website_display_name,
                    cat.name AS cat, scat.name AS sub_cat, IFNULL(sscat.name, ' ') AS sub_sub_cat,
                    s.origin_country AS supplier_country,

                    p.status as status, p.website_status,
                    p.create_by, p.create_on, p.modify_by, p.modify_on,
                    pc.youtube_id_1, pc.youtube_caption_1, pc.youtube_id_2, pc.youtube_caption_2,

                    GROUP_CONCAT(pr.price) price,
                    GROUP_CONCAT(pr.platform_id) platform_id,
                    GROUP_CONCAT(CAST(CONCAT(' ', (CONCAT_WS('-', dt.ship_min_day, dt.ship_max_day))) AS CHAR)) as ship_day,
                    GROUP_CONCAT(CAST(CONCAT(' ', (CONCAT_WS('-', dt.del_min_day, dt.del_max_day))) AS CHAR)) as delivery_day
                ", false);
        // $this->include_dto($classname);
        $rs = array();
        $debug = false;

        // if debugging, set $this->db->save_queries = true
        if ($debug) {
            $this->db->save_queries = true;
            $this->db->get();
            echo "<pre>";
            var_dump($this->db->last_query());
            var_dump($this->db->_error_message());
            die();
        }

        if ($query = $this->db->get()) {
            if ($query->num_rows() > 0) {
                foreach ($query->result_array() as $row) {
                    $rs[] = $row;
                }
            }
            return $rs;
        } else {
            return false;
        }

        // full query as of 16/07/2014
        // SELECT IFNULL(map.ext_sku, ' ') AS master_sku, p.sku, p.name AS product_name, pc.prod_name AS website_display_name, cat.name AS cat, scat.name AS sub_cat, IFNULL(sscat.name, ' ') AS sub_sub_cat, p.status as status, p.website_status, p.create_by, p.create_on, p.modify_by, p.modify_on, pc.youtube_id_1, pc.youtube_caption_1, pc.youtube_id_2, pc.youtube_caption_2, GROUP_CONCAT(pr.price) price, GROUP_CONCAT(pr.platform_id) platform_id, GROUP_CONCAT(CAST(CONCAT(' ', (CONCAT_WS('-', dt.ship_min_day, dt.ship_max_day))) AS CHAR)) as ship_day, GROUP_CONCAT(CAST(CONCAT(' ', (CONCAT_WS('-', dt.del_min_day, dt.del_max_day))) AS CHAR)) as delivery_day
        //  FROM (`product` p)
        //  LEFT JOIN `sku_mapping` map ON `map`.`sku` = `p`.`sku` and map.ext_sys = 'WMS'
        // LEFT JOIN supplier_prod sp ON sp.prod_sku = p.sku and sp.order_default = 1
        // LEFT JOIN supplier s ON s.id = sp.supplier_id
        //  LEFT JOIN `product_content` pc ON `map`.`sku` = `pc`.`prod_sku` AND pc.lang_id = 'en'
        //  LEFT JOIN `category` cat ON `cat`.`id` = `p`.`cat_id`
        //  LEFT JOIN `category` scat ON `scat`.`id` = `p`.`sub_cat_id`
        //  LEFT JOIN `category` sscat ON `sscat`.`id` = `p`.`sub_sub_cat_id`
        //  LEFT JOIN `price` pr ON `p`.`sku` = `pr`.`sku` AND pr.platform_id LIKE 'WEB%'
        //  LEFT JOIN `platform_biz_var` pbv ON `pbv`.`selling_platform_id` = `pr`.`platform_id`
        //  LEFT JOIN `delivery_time` dt ON `pr`.`delivery_scenarioid` = `dt`.`scenarioid` AND pbv.platform_country_id = dt.country_id
        //  WHERE `p`.`status` = 2
        //  GROUP BY `p`.`sku`
    }

    public function get_reevoo_product_feed_dto($classname = "Reevoo_product_feed_dto", $country_id = NULL)
    {
        $option = array("limit" => -1);

        $this->db->from("product p");
        $this->db->join("product_content pc", "pc.prod_sku = p.sku", "INNER");
        $this->db->join("brand b", "b.id = p.brand_id", "INNER");
        $this->db->join("category_extend ce_cat", "ce_cat.cat_id = p.cat_id", "INNER");
        $this->db->join("category_extend ce_sc", "ce_sc.cat_id = p.sub_cat_id", "INNER");
        $this->db->join("product_identifier pi", "pi.prod_grp_cd = p.prod_grp_cd AND pi.colour_id = p.colour_id", "INNER");
        $this->db->join('platform_biz_var pbv', 'pbv.language_id = ce_cat.lang_id AND pbv.language_id = ce_sc.lang_id AND pbv.language_id = pc.lang_id AND pi.country_id = pbv.platform_country_id AND pbv.selling_platform_id = "WEB' . $country_id . '"', 'INNER');

        $this->db->where("p.status", 2);

        $this->include_dto($classname);

        return $this->common_get_list($where, $option, $classname, 'b.brand_name, p.mpn, p.sku, pc.prod_name, p.image, ce_cat.name cat_name, ce_sc.name sub_cat_name, p.ean');
    }

    public function get_googlebase_product_feed_dto($platform_id = "WEBGB", $where, $classname = "Googlebase_product_feed_dto")
    {
        $option = array("limit" => -1);
        $this->db->from("product p");
        $this->db->join("price pr", "pr.sku = p.sku AND pr.listing_status = 'L'", "INNER");
        $this->db->join("colour clr", "clr.id = p.colour_id", "INNER");
        $this->db->join("platform_biz_var pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
        $this->db->join("category cat", "cat.id = p.cat_id", "INNER");
        $this->db->join("category sc", "sc.id = p.sub_cat_id", "INNER");
        $this->db->join("brand br", "br.id = p.brand_id", "INNER");
        $this->db->join("product_content pc", "pc.prod_sku = p.sku AND pc.lang_id = pbv.language_id", "INNER");
        $this->db->join("freight_category fc", "fc.id = p.freight_cat_id", "LEFT");
        $this->db->join("category_mapping map", "p.sku = map.id AND map.ext_party = 'GOOGLEBASE' AND map.level = 0 AND pbv.platform_country_id = map.country_id AND map.status = 1 AND map.product_name is not null", "INNER");
        $this->db->join("ext_category_mapping ecm", "ecm.category_id=if(p.sub_sub_cat_id = 0, if(p.sub_cat_id = 0, p.cat_id, p.sub_cat_id), p.sub_sub_cat_id) AND ecm.ext_party='GOOGLEBASE' AND ecm.country_id = map.country_id", "INNER");
        $this->db->join("external_category ext_c", "ext_c.id = ecm.ext_id", "INNER");
        $this->db->join("product_identifier pi", "pi.prod_grp_cd = p.prod_grp_cd AND pi.colour_id = p.colour_id AND pi.country_id = pbv.platform_country_id  AND pi.status = 1", "LEFT");
        $this->db->where(array("p.status" => 2, "pr.listing_status" => "L", "p.website_status in ('I', 'P')" => null, "pr.platform_id" => $platform_id, "pr.price IS NOT NULL" => null, "pr.is_advertised" => "Y"));
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, 'pr.platform_id, p.sku, p.prod_grp_cd, p.version_id, p.colour_id, clr.name colour_name, pbv.platform_country_id, pbv.language_id,
                    IFNULL(map.product_name, pc.prod_name) prod_name, p.cat_id, cat.name cat_name, p.sub_cat_id, sc.name sub_cat_name, p.brand_id, br.brand_name, pi.mpn, pi.upc, pi.ean,
                    pc.short_desc, pc.detail_desc, pc.contents, fc.weight, p.image, pbv.platform_currency_id, pr.price,
                    CONCAT("http://cdn.valuebasket.com/808AA1/vb/images/product/", p.sku, ".", p.image) image_url,
                    p.quantity, p.display_quantity, p.website_quantity, p.website_status, p.status prod_status, pr.listing_status, p.ex_demo, ext_c.ext_name google_product_category, fc.weight prod_weight');
    }

    public function get_mediaforge_product_feed_dto($where, $option, $classname = "Mediaforge_product_feed_dto", $platform_id)
    {
        switch ($platform_id) {
            case 'WEBFR':
                return $this->get_mediaforge_product_feed_fr_dto($where, $option, $classname);
                break;
            case 'WEBES':
                return $this->get_mediaforge_product_feed_es_dto($where, $option, $classname);
                break;
            case 'WEBNZ':
                return $this->get_mediaforge_product_feed_nz_dto($where, $option, $classname);
                break;
            case 'WEBSG':
                return $this->get_mediaforge_product_feed_sg_dto($where, $option, $classname);
                break;
            case 'WEBMY':
                return $this->get_mediaforge_product_feed_my_dto($where, $option, $classname);
                break;
            case 'WEBIT':
                return $this->get_mediaforge_product_feed_it_dto($where, $option, $classname);
                break;
        }
    }

    private function get_mediaforge_product_feed_fr_dto($where, $option, $classname = "Mediaforge_product_feed_dto")
    {
        $option = array("limit" => -1);
        $this->db->from("product p");
        $this->db->join("product_content pc", "p.sku = pc.prod_sku AND pc.lang_id = 'fr'", "LEFT");
        $this->db->join("price pr", "pr.sku = p.sku AND pr.listing_status = 'L' and pr.platform_id='WEBFR'", "INNER");
        $this->db->join("category_extend cat", "cat.cat_id = p.cat_id AND cat.lang_id = pc.lang_id", "INNER");
        $this->db->join("brand br", "br.id = p.brand_id", "INNER");
        $this->db->join("platform_biz_var pbv", "pbv.selling_platform_id = pr.platform_id");
        $this->db->join("product_identifier AS pi", "pi.prod_grp_cd = p.prod_grp_cd AND pi.colour_id = p.colour_id AND pi.country_id = pbv.platform_country_id AND pi.status = 1", "LEFT");
        $this->db->join("delivery del", "del.country_id = pbv.platform_country_id AND del.status = 1 AND del.delivery_type_id = 'STD'", "INNER");
        $this->db->where(array("p.status" => 2, "p.website_quantity > 0" => null, "p.website_status" => "I"));
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, "
            pc.prod_name prod_name,
            p.sku, cat.name cat_name,
            CONCAT('http://www.valuebasket.fr/fr_FR/mainproduct/view/', p.sku, '?AF=MFFR') product_url,
            CONCAT('http://cdn.valuebasket.com/808AA1/vb/images/product/', p.sku, '.', p.image) image_url,
            REPLACE(REPLACE(CONCAT(substr(pc.detail_desc, 1, 200), ' ...'), '\r\n', ' '), '\n', ' ') short_desc,
            REPLACE(REPLACE(pc.detail_desc, '\r\n', ' '), '\n', ' ') detail_desc,
            pr.price sale_price,
            pr.price,
            br.brand_name,
            pi.mpn,
            pi.upc,
            br.brand_name manufacturer,
            CONCAT('Généralement expédié en ', del.min_day, IF(del.max_day IS NULL, '', CONCAT('-', del.max_day)), ' jours ouvrables') shipping_info,
            0 discount, 'amount' disc_type, 0 shipping_fee, 'En Stock' stock_status,
            'N' delete_flag, 'Y' all_flag, 'Y' prod_link_flag, 'Y' storefront_flag, 'Y' merc_flag,
            pbv.platform_currency_id currency");
    }

    private function get_mediaforge_product_feed_es_dto($where, $option, $classname = "Mediaforge_product_feed_dto")
    {
        $option = array("limit" => -1);
        $this->db->from("product p");
        $this->db->join("product_content pc", "p.sku = pc.prod_sku AND pc.lang_id = 'es'", "LEFT");
        $this->db->join("price pr", "pr.sku = p.sku AND pr.listing_status = 'L' and pr.platform_id='WEBES'", "INNER");
        $this->db->join("category_extend cat", "cat.cat_id = p.cat_id AND cat.lang_id = pc.lang_id", "INNER");
        $this->db->join("brand br", "br.id = p.brand_id", "INNER");
        $this->db->join("platform_biz_var pbv", "pbv.selling_platform_id = pr.platform_id");
        $this->db->join("product_identifier AS pi", "pi.prod_grp_cd = p.prod_grp_cd AND pi.colour_id = p.colour_id AND pi.country_id = pbv.platform_country_id AND pi.status = 1", "LEFT");
        $this->db->join("delivery del", "del.country_id = pbv.platform_country_id AND del.status = 1 AND del.delivery_type_id = 'STD'", "INNER");
        $this->db->where(array("p.status" => 2, "p.website_quantity > 0" => null, "p.website_status" => "I"));
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, "
            pc.prod_name prod_name,
            p.sku,
            cat.name cat_name,
            CONCAT('http://www.valuebasket.es/es_ES/mainproduct/view/', p.sku, '?AF=MFES') product_url,
            CONCAT('http://cdn.valuebasket.com/808AA1/vb/images/product/', p.sku, '.', p.image) image_url,
            REPLACE(REPLACE(CONCAT(substr(pc.detail_desc, 1, 200), ' ...'), '\r\n', ' '), '\n', ' ') short_desc,
            REPLACE(REPLACE(pc.detail_desc, '\r\n', ' '), '\n', ' ') detail_desc,
            pr.price sale_price,
            pr.price,
            br.brand_name,
            0 shipping_fee,
            pi.mpn,
            pi.upc,
            br.brand_name manufacturer,
            CONCAT('Usually ships in ', del.min_day, IF(del.max_day IS NULL, '', CONCAT('-', del.max_day)), ' Business Days') shipping_info,
            0 discount, 'amount' disc_type,
            'In Stock' stock_status,
            'N' delete_flag, 'Y' all_flag, 'Y' prod_link_flag, 'Y' storefront_flag, 'Y' merc_flag,
            pbv.platform_currency_id currency");

    }

    private function get_mediaforge_product_feed_nz_dto($where, $option, $classname = "Mediaforge_product_feed_dto")
    {
        $option = array("limit" => -1);
        $this->db->from("product p");
        $this->db->join("product_content pc", "p.sku = pc.prod_sku AND pc.lang_id = 'en'", "LEFT");
        $this->db->join("price pr", "pr.sku = p.sku AND pr.listing_status = 'L' and pr.platform_id='WEBNZ'", "INNER");
        $this->db->join("category_extend cat", "cat.cat_id = p.cat_id AND cat.lang_id = pc.lang_id", "INNER");
        $this->db->join("brand br", "br.id = p.brand_id", "INNER");
        $this->db->join("platform_biz_var pbv", "pbv.selling_platform_id = pr.platform_id");
        $this->db->join("product_identifier AS pi", "pi.prod_grp_cd = p.prod_grp_cd AND pi.colour_id = p.colour_id AND pi.country_id = pbv.platform_country_id AND pi.status = 1", "LEFT");
        $this->db->join("delivery del", "del.country_id = pbv.platform_country_id AND del.status = 1 AND del.delivery_type_id = 'STD'", "INNER");
        $this->db->where(array("p.status" => 2, "p.website_quantity > 0" => null, "p.website_status" => "I"));
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, "
            pc.prod_name prod_name,
            p.sku, cat.name cat_name,
            CONCAT('http://www.valuebasket.co.nz/en_NZ/mainproduct/view/', p.sku, '?AF=MFNZ') product_url,
            CONCAT('http://cdn.valuebasket.com/808AA1/vb/images/product/', p.sku, '.', p.image) image_url,
            REPLACE(REPLACE(CONCAT(substr(pc.detail_desc, 1, 200), ' ...'), '\r\n', ' '), '\n', ' ') short_desc,
            REPLACE(REPLACE(pc.detail_desc, '\r\n', ' '), '\n', ' ') detail_desc,
            pr.price sale_price,
            pr.price, br.brand_name,
            pi.mpn,
            pi.upc,
            br.brand_name manufacturer,
            '2-4 Business Days' shipping_info,
            0 discount, 'amount' disc_type,0 shipping_fee,
            'In Stock' stock_status,
            'N' delete_flag, 'Y' all_flag, 'Y' prod_link_flag, 'Y' storefront_flag, 'Y' merc_flag,
            pbv.platform_currency_id currency");
    }

    private function get_mediaforge_product_feed_sg_dto($where, $option, $classname = "Mediaforge_product_feed_dto")
    {
        $this->db->from("product AS p");
        $this->db->join("product_content pc", "p.sku=pc.prod_sku AND pc.lang_id='en'", "LEFT");
        $this->db->join("price pr", "pr.sku=p.sku AND pr.listing_status = 'L' AND pr.platform_id='WEBSG'", "INNER");
        $this->db->join("category_extend cat", "cat.cat_id = p.cat_id AND cat.lang_id = pc.lang_id", "INNER");
        $this->db->join("brand br", "p.brand_id=br.id", "INNER");
        $this->db->join("platform_biz_var pbv", "pbv.selling_platform_id = pr.platform_id");
        $this->db->join("product_identifier AS pi", "pi.prod_grp_cd = p.prod_grp_cd AND pi.colour_id = p.colour_id AND pi.country_id = pbv.platform_country_id AND pi.status = 1", "LEFT");
        $this->db->join("delivery del", "del.country_id = pbv.platform_country_id AND del.status = 1 AND del.delivery_type_id = 'STD'", "INNER");
        $this->db->where(array("p.website_quantity > 0" => null, "p.status" => 2, "p.website_status IN ('I','P')" => null));
        $this->include_dto($classname);

        return $this->common_get_list($where, $option, $classname, "
            pc.prod_name prod_name, p.sku,
            cat.name cat_name,
            CONCAT('http://www.valuebasket.com.sg/en_SG/mainproduct/view/', p.sku, '?AF=MFSG') product_url,
            CONCAT('http://cdn.valuebasket.com/808AA1/vb/images/product/', p.sku, '.', p.image) image_url,
            REPLACE(REPLACE(CONCAT(substr(pc.detail_desc, 1, 200), ' ...'), '\r\n', ' '), '\n', ' ') short_desc,
            REPLACE(REPLACE(pc.detail_desc, '\r\n', ' '), '\n', ' ') detail_desc,
            pr.price sale_price,
            pr.price,
            br.brand_name,
            pi.mpn,
            pi.upc,
            br.brand_name manufacturer,
            CONCAT('Usually ships in ', del.min_day, IF(del.max_day IS NULL, '', CONCAT('-', del.max_day)), ' Business Days') shipping_info,
            0 discount, 'amount' disc_type,
            0 shipping_fee,
            'In Stock' stock_status,
            'Y' prod_link_flag, 'N' delete_flag, 'Y' all_flag, 'Y' storefront_flag, 'Y' merc_flag,
            pbv.platform_currency_id currency");
    }

    private function get_mediaforge_product_feed_my_dto($where, $option, $classname = "Mediaforge_product_feed_dto")
    {
        $this->db->from("product AS p");
        $this->db->join("product_content pc", "p.sku=pc.prod_sku AND pc.lang_id='en'", "LEFT");
        $this->db->join("price pr", "pr.sku=p.sku AND pr.listing_status = 'L' AND pr.platform_id='WEBMY'", "INNER");
        $this->db->join("category_extend cat", "cat.cat_id = p.cat_id AND cat.lang_id = pc.lang_id", "INNER");
        $this->db->join("brand br", "p.brand_id=br.id", "INNER");
        $this->db->join("platform_biz_var pbv", "pbv.selling_platform_id = pr.platform_id");
        $this->db->join("product_identifier AS pi", "pi.prod_grp_cd = p.prod_grp_cd AND pi.colour_id = p.colour_id AND pi.country_id = pbv.platform_country_id AND pi.status = 1", "LEFT");
        $this->db->join("delivery del", "del.country_id = pbv.platform_country_id AND del.status = 1 AND del.delivery_type_id = 'STD'", "INNER");
        $this->db->where(array("p.website_quantity > 0" => null, "p.status" => 2, "p.website_status IN ('I','P')" => null));
        $this->include_dto($classname);

        return $this->common_get_list($where, $option, $classname, "
            pc.prod_name prod_name, p.sku,
            cat.name cat_name,
            CONCAT('http://www.valuebasket.com/en_MY/mainproduct/view/', p.sku, '?AF=MFMY') product_url,
            CONCAT('http://cdn.valuebasket.com/808AA1/vb/images/product/', p.sku, '.', p.image) image_url,
            REPLACE(REPLACE(CONCAT(substr(pc.detail_desc, 1, 200), ' ...'), '\r\n', ' '), '\n', ' ') short_desc,
            REPLACE(REPLACE(pc.detail_desc, '\r\n', ' '), '\n', ' ') detail_desc,
            pr.price sale_price,
            pr.price,
            br.brand_name,
            pi.mpn,
            pi.upc,
            br.brand_name manufacturer,
            CONCAT('Usually ships in ', del.min_day, IF(del.max_day IS NULL, '', CONCAT('-', del.max_day)), ' Business Days') shipping_info,
            0 discount, 'amount' disc_type,
            0 shipping_fee,
            'In Stock' stock_status,
            'N' delete_flag, 'Y' all_flag, 'Y' prod_link_flag, 'Y' storefront_flag, 'Y' merc_flag,
            pbv.platform_currency_id currency");
    }

    private function get_mediaforge_product_feed_it_dto($where, $option, $classname = "Mediaforge_product_feed_dto")
    {
        $this->db->from("product AS p");
        $this->db->join("product_content pc", "p.sku=pc.prod_sku AND pc.lang_id='it'", "LEFT");
        $this->db->join("price pr", "pr.sku=p.sku AND pr.listing_status = 'L' AND pr.platform_id='WEBIT'", "INNER");
        $this->db->join("category_extend cat", "cat.cat_id = p.cat_id AND cat.lang_id = pc.lang_id", "INNER");
        $this->db->join("brand br", "p.brand_id=br.id", "INNER");
        $this->db->join("platform_biz_var pbv", "pbv.selling_platform_id = pr.platform_id");
        $this->db->join("product_identifier AS pi", "pi.prod_grp_cd = p.prod_grp_cd AND pi.colour_id = p.colour_id AND pi.country_id = pbv.platform_country_id AND pi.status = 1", "LEFT");
        $this->db->join("delivery del", "del.country_id = pbv.platform_country_id AND del.status = 1 AND del.delivery_type_id = 'STD'", "INNER");
        $this->db->where(array("p.website_quantity > 0" => null, "p.status" => 2, "p.website_status IN ('I','P')" => null));
        $this->include_dto($classname);

        return $this->common_get_list($where, $option, $classname, "
            pc.prod_name prod_name,
            p.sku,
            cat.name cat_name,
            CONCAT('http://www.valuebasket.com/it_IT/mainproduct/view/', p.sku, '?AF=MFIT') product_url,
            CONCAT('http://cdn.valuebasket.com/808AA1/vb/images/product/', p.sku, '.', p.image) image_url,
            REPLACE(REPLACE(CONCAT(substr(pc.detail_desc, 1, 200), ' ...'), '\r\n', ' '), '\n', ' ') short_desc,
            REPLACE(REPLACE(pc.detail_desc, '\r\n', ' '), '\n', ' ') detail_desc,
            pr.price sale_price,
            pr.price,
            br.brand_name,
            0 shipping_fee,
            pi.mpn,
            pi.upc,
            br.brand_name manufacturer,
            CONCAT('Usually ships in ', del.min_day, IF(del.max_day IS NULL, '', CONCAT('-', del.max_day)), ' Business Days') shipping_info,
            0 discount, 'amount' disc_type,
            'In Stock' stock_status,
            'N' delete_flag, 'Y' all_flag, 'Y' prod_link_flag, 'Y' storefront_flag, 'Y' merc_flag,
            pbv.platform_currency_id currency");
    }

    public function get_linkshare_product_feed_dto($where, $option, $classname = "Linkshare_product_feed_dto")
    {
        $option = array("limit" => -1);
        $this->db->from("product p");
        $this->db->join("product_content pc", "p.sku = pc.prod_sku AND pc.lang_id = 'en'", "LEFT");
        $this->db->join("price pr", "pr.sku = p.sku AND pr.listing_status = 'L'", "INNER");
        $this->db->join("category cat", "cat.id = p.cat_id", "INNER");
        $this->db->join("brand br", "br.id = p.brand_id", "INNER");
        $this->db->join("platform_biz_var pbv", "pbv.selling_platform_id = pr.platform_id");
        $this->db->join("delivery del", "del.country_id = pbv.platform_country_id AND del.status = 1 AND del.delivery_type_id = 'STD'", "INNER");
        $this->db->where(array("p.status" => 2, "p.website_quantity > 0" => null, "p.website_status" => "I", "pc.detail_desc is not null" => null, "pc.detail_desc <> ''" => null));
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, 'p.name prod_name, p.sku, cat.name cat_name, IF(p.image IS NOT NULL, CONCAT("http://www.valuebasket.com/images/product/", p.sku, ".", p.image), "http://www.valuebasket.com/images/product/imageunavailable.jpg") image_url, IF(LENGTH(pc.detail_desc) > 200, CONCAT(substr(pc.detail_desc,1,200)," ..."), pc.detail_desc) short_desc, pc.detail_desc, 0 discount, "amount" disc_type, pr.price sale_price, pr.price, br.brand_name, 0 shipping_fee, "N" delete_flag, "Y" all_flag, p.mpn, br.brand_name manufacturer, CONCAT("Usually ships in ", del.min_day, IF(del.max_day IS NULL, "", CONCAT("-", del.max_day)), " Business Days") shipping_info, "In Stock" stock_status, p.upc, "Y" prod_link_flag, "Y" storefront_flag, "Y" merc_flag, pbv.platform_currency_id currency');
    }

    public function get_linkshare_product_feed_2_dto($where, $option, $classname = "Linkshare_product_feed_dto")
    {
        $option = array("limit" => -1);
        $this->db->from("product p");
        $this->db->join("product_content pc", "p.sku = pc.prod_sku AND pc.lang_id = 'en'", "LEFT");
        $this->db->join("price pr", "pr.sku = p.sku AND pr.listing_status = 'L'", "INNER");
        $this->db->join("category cat", "cat.id = p.cat_id", "INNER");
        $this->db->join("brand br", "br.id = p.brand_id", "INNER");
        $this->db->join("platform_biz_var pbv", "pbv.selling_platform_id = pr.platform_id");
        $this->db->join("delivery del", "del.country_id = pbv.platform_country_id AND del.status = 1 AND del.delivery_type_id = 'STD'", "INNER");
        $this->db->where(array("p.status" => 2, "p.website_quantity > 0" => null, "p.website_status" => "I", "pc.detail_desc is not null" => null, "pc.detail_desc <> ''" => null));
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname,
            'p.name prod_name,
                p.sku,
                cat.name cat_name,
                IF(p.image IS NOT NULL, CONCAT("http://www.valuebasket.com/images/product/", p.sku, ".", p.image), "http://www.valuebasket.com/images/product/imageunavailable.jpg") image_url,
                IF(LENGTH(pc.detail_desc) > 200, CONCAT(substr(pc.detail_desc,1,200)," ..."), pc.detail_desc) short_desc,
                pc.detail_desc,
                0 discount,
                "amount" disc_type,
                pr.price sale_price,
                pr.price, br.brand_name,
                0 shipping_fee,
                "N" delete_flag,
                "Y" all_flag,
                p.mpn,
                br.brand_name manufacturer,
                "2-4 Business Days" shipping_info,
                "In Stock" stock_status, p.upc,
                "Y" prod_link_flag, "Y" storefront_flag, "Y" merc_flag,
                pbv.platform_currency_id currency');
    }

    public function get_shopping_com_product_feed_dto($classname = "Shopping_com_product_feed_dto")
    {
        $option = array("limit" => -1);
        $this->db->from("product p");
        $this->db->join("product_content pc", "p.sku = pc.prod_sku AND pc.lang_id = 'en'", "LEFT");
        $this->db->join("price pr", "pr.sku = p.sku AND pr.platform_id = 'WEBGB' AND pr.listing_status = 'L' AND pr.price > 100", "INNER");
        $this->db->join("category cat", "cat.id = p.cat_id", "INNER");
        $this->db->join("category sc", "sc.id = p.sub_cat_id", "INNER");
        $this->db->join("brand br", "br.id = p.brand_id", "INNER");
        $this->db->where(array("p.status" => 2, "p.website_quantity > 0" => null, "p.website_status" => "I"));
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, "null prod_id, p.sku, p.name, CONCAT('http://www.valuebasket.com/mainproduct/view/', p.sku) product_url,  CONCAT('http://www.valuebasket.com/images/product/', p.sku, '.', p.image) image_url, pr.price, CONCAT_WS(' > ', cat.name, sc.name) cat_name, IF(p.website_status='I','Y','N') stock_status, 0 shipping_rate, p.mpn, p.ean, 'New' prod_condition, br.brand_name, pc.detail_desc, 'Free Shipping For All Orders' stock_desc, 'New' merc_type, 'N' is_bundle");
    }

    public function get_sli_product_feed_product_info_dto($where = array(), $option = array(), $classname = "Sli_product_feed_product_info_dto")
    {
        $this->db->from("product p");
        $this->db->join("price pr", "p.sku = pr.sku", "INNER");
        $this->db->join("platform_biz_var pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
        $this->db->join("selling_platform sp", "pbv.selling_platform_id = sp.id AND sp.type = 'WEBSITE'", "INNER");
        $this->db->join("category cat", "p.cat_id = cat.id", "INNER");
        $this->db->join("category sc", "p.sub_cat_id = sc.id", "LEFT");
        $this->db->join("brand br", "p.brand_id = br.id", "INNER");
        $this->db->join("product_content pc", "p.sku = pc.prod_sku AND pc.lang_id = pbv.language_id", "LEFT");
        $this->db->join("(SELECT DISTINCT component_sku FROM bundle b) AS bn", "p.sku = bn.component_sku", "LEFT");
        $this->db->where(array("p.status" => 2, "pr.listing_status" => "L"));
        $this->db->group_by("p.sku");
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, 'p.sku, pc.prod_name prod_name, pc.short_desc, pc.detail_desc, IF(p.image IS NOT NULL, CONCAT("http://www.valuebasket.com/images/product/", p.sku, ".", p.image), CONCAT("http://www.valuebasket.com/images/product/imageunavailable.jpg")) image_url, cat.name cat_name, sc.name sub_cat_name, br.brand_name, p.mpn, p.ean, p.upc, (bn.component_sku IS NOT NULL) inbundle');
    }

    public function get_sli_product_feed_price_info_dto($where = array(), $option = array(), $classname = "Sli_product_feed_price_info_dto")
    {
        $this->db->from("product p");
        $this->db->join("price pr", "p.sku = pr.sku", "INNER");
        $this->db->join("platform_biz_var pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
        $this->db->join("selling_platform sp", "pbv.selling_platform_id = sp.id AND sp.type = 'WEBSITE'", "INNER");
        $this->db->join("category cat", "p.cat_id = cat.id", "INNER");
        $this->db->join("category sc", "p.sub_cat_id = sc.id", "LEFT");
        $this->db->join("brand br", "p.brand_id = br.id", "INNER");
        $this->db->join("product_content pc", "p.sku = pc.prod_sku AND pc.lang_id = pbv.language_id", "LEFT");
        $this->db->join("(SELECT DISTINCT component_sku FROM bundle b) AS bn", "p.sku = bn.component_sku", "LEFT");
        $this->db->where(array("p.status" => 2, "pr.listing_status" => "L"));
        $this->db->where($where);
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, 'pr.platform_id, pbv.platform_country_id country_id, p.sku, CONCAT("http://www.valuebasket.com/",pbv.language_id,"_",pbv.platform_country_id,"/", REPLACE(REPLACE(p.name, ".", "-"), " ", "-") ,"/mainproduct/view/", p.sku) product_url, pbv.platform_currency_id currency_id, pr.price, pr.fixed_rrp, pr.rrp_factor, p.website_status, if(p.display_quantity < p.website_quantity, p.display_quantity, p.website_quantity) quantity');
    }

    public function get_sli_product_feed_dto($where = array(), $option = array(), $classname = "Sli_product_feed_dto")
    {
        $option = array("limit" => -1);
        $this->db->from("v_product_w_bundle p");
        $this->db->join("price pr", "p.sku = pr.sku", "INNER");
        $this->db->join("platform_biz_var pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
        $this->db->join("selling_platform sp", "pbv.selling_platform_id = sp.id AND sp.type = 'WEBSITE'", "INNER");
        $this->db->join("category cat", "p.cat_id = cat.id", "INNER");
        $this->db->join("category sc", "p.sub_cat_id = sc.id", "LEFT");
        $this->db->join("brand b", "p.brand_id = b.id", "INNER");
        $this->db->join("product_content pc", "p.sku = pc.prod_sku AND pc.lang_id = pbv.language_id", "LEFT");
        $this->db->where(array("p.status" => 2, "pr.listing_status" => "L"));
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, 'pr.platform_id, pbv.platform_country_id country_id, p.sku, p.name prod_name, pc.short_desc, pc.detail_desc, CONCAT("http://www.valuebasket.com/mainproduct/view/", p.sku) product_url, IF(p.image IS NOT NULL, CONCAT("http://www.valuebasket.com/images/product/", p.sku, ".", p.image), "http://www.valuebasket.com/images/product/imageunavailable.jpg") image_url, cat.name cat_name, sc.name sub_cat_name, b.brand_name, pbv.platform_currency_id currency_id, pr.price, p.website_status, if(p.display_quantity < p.website_quantity, p.display_quantity, p.website_quantity) quantity, p.mpn, p.ean, p.upc, p.with_bundle inbundle');
    }

    public function get_searchspring_product_feed_product_info_dto($where = array(), $option = array(), $classname = "Searchspring_product_feed_product_info_dto")
    {
        $this->db->from("product p");
        $this->db->join("price pr", "p.sku = pr.sku", "INNER");
        $this->db->join("platform_biz_var pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
        $this->db->join("selling_platform sp", "pbv.selling_platform_id = sp.id AND sp.type = 'WEBSITE'", "INNER");
        $this->db->join("category_extend cat", "p.cat_id = cat.cat_id and pbv.language_id = cat.lang_id", "LEFT");
        $this->db->join("category_extend sc", "p.sub_cat_id = sc.cat_id and pbv.language_id = sc.lang_id", "LEFT");
        $this->db->join("brand br", "p.brand_id = br.id", "INNER");
        $this->db->join("product_content pc", "p.sku = pc.prod_sku AND pc.lang_id = pbv.language_id", "LEFT");
        $this->db->join("product_content default_pc", "p.sku = default_pc.prod_sku AND default_pc.lang_id = 'en'", "LEFT");
        $this->db->join("(SELECT DISTINCT component_sku FROM bundle b) AS bn", "p.sku = bn.component_sku", "LEFT");
        $this->db->where(array("p.status" => 2, "pr.listing_status" => "L"));
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, 'p.sku, coalesce(pc.prod_name, default_pc.prod_name) prod_name, coalesce(pc.short_desc, default_pc.short_desc) short_desc, coalesce(pc.detail_desc, default_pc.detail_desc) detail_desc, p.image, CONCAT("/cart/add_item/", p.sku) add_cart_url, cat.name cat_name, sc.name sub_cat_name, br.brand_name, p.mpn, p.ean, p.upc, (bn.component_sku IS NOT NULL) inbundle, p.clearance, p.create_on create_date');
    }

    public function get_searchspring_product_feed_price_info_dto($where = array(), $option = array(), $classname = "Searchspring_product_feed_price_info_dto")
    {
        $this->db->from("product p");
        $this->db->join("price pr", "p.sku = pr.sku", "INNER");
        $this->db->join("platform_biz_var pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
        $this->db->join("selling_platform sp", "pbv.selling_platform_id = sp.id AND sp.type = 'WEBSITE'", "INNER");
        $this->db->join("category_extend cat", "p.cat_id = cat.cat_id and pbv.language_id = cat.lang_id", "LEFT");
        $this->db->join("category_extend sc", "p.sub_cat_id = sc.cat_id and pbv.language_id = sc.lang_id", "LEFT");
        $this->db->join("brand br", "p.brand_id = br.id", "INNER");
        $this->db->join("product_content pc", "p.sku = pc.prod_sku AND pc.lang_id = pbv.language_id", "LEFT");
        $this->db->join("(SELECT DISTINCT component_sku FROM bundle b) AS bn", "p.sku = bn.component_sku", "LEFT");
        $this->db->where(array("p.status" => 2, "pr.listing_status" => "L"));
        $this->db->where($where);
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, 'pr.platform_id, pbv.platform_country_id country_id, p.sku, CONCAT("/",pbv.language_id,"_",pbv.platform_country_id,"/", REPLACE(REPLACE(p.name, ".", "-"), " ", "-") ,"/mainproduct/view/", p.sku) product_url, pbv.platform_currency_id currency_id, pr.price, pr.fixed_rrp, pr.rrp_factor, p.website_status, if(p.display_quantity < p.website_quantity, p.display_quantity, p.website_quantity) quantity');
    }

    public function get_website_product_info($sku = "", $platform_id = "WEBHK", $lang_id = "en", $classname = "Website_product_info_dto")
    {
        $option['limit'] = 1;
        $this->db->from("product AS p, platform_biz_var pbv");
        $this->db->join("product_content AS pc", "pc.prod_sku = p.sku", "LEFT");
        $this->db->join("product_content_extend AS pcex", "pcex.lang_id = pc.lang_id AND pcex.prod_sku = p.sku", "LEFT");
        $this->db->join("category AS cat", "cat.id = p.cat_id", "INNER");
        $this->db->join("category AS sc", "sc.id = p.sub_cat_id", "INNER");
        $this->db->join("category AS ssc", "ssc.id = p.sub_sub_cat_id", "LEFT");
        $this->db->join("brand AS b", "b.id = p.brand_id", "INNER");
        $this->db->where(array("p.sku" => $sku, "p.status" => 2, "pbv.selling_platform_id" => $platform_id, "pc.lang_id" => $lang_id));
        $this->include_dto($classname);

        return $this->common_get_list($where, $option, $classname,
            'p.expected_delivery_date,

            pc.website_status_long_text, pc.website_status_short_text, p.sku, cat.id cat_id, cat.name cat_name, sc.id sub_cat_id, sc.name sub_cat_name, ssc.id sub_sub_cat_id, ssc.name sub_sub_cat_name, b.id brand_id, b.brand_name,
            pc.lang_id, IFNULL(pc.prod_name,p.name) prod_name, p.youtube_id, pc.short_desc, pc.detail_desc, pc.extra_info, pc.contents, pcex.feature, pcex.specification, pcex.requirement, pcex.instruction, pcex.apply_enhanced_listing, pcex.enhanced_listing,
            pc.contents_original, pc.keywords_original, pc.detail_desc_original, pcex.feature_original, pcex.spec_original, p.lang_restricted');
    }

    public function get_home_best_seller_grid_info($platform_id)
    {
        $sql = "SELECT ll.selection
                    FROM landpage_listing ll
                    JOIN product p
                        ON p.sku = ll.selection
                    JOIN price pr
                        ON pr.platform_id = ll.platform_id
                    WHERE ll.platform_id = ? AND ll.type = 'BS' AND p.status = 2 and pr.sku=ll.selection AND pr.listing_status = 'L'
                    group by ll.selection ORDER BY ll.type = 'BS' DESC, field(ll.mode, 'M', 'A'), ll.rank
                LIMIT 10";

        if ($query = $this->db->query($sql, $platform_id)) {
            foreach ($query->result() as $row) {
                $res[] = $row->selection;
            }
            return $res;
        }
        return FALSE;
    }

    public function get_home_latest_arrival_grid_info($platform_id)
    {
        $sql = "SELECT ll.selection
                    FROM landpage_listing ll
                    JOIN product p
                        ON p.sku = ll.selection
                    JOIN price pr
                        ON pr.platform_id = ll.platform_id
                    WHERE ll.platform_id = ? AND ll.type = 'LA' AND p.status = 2 and pr.sku=ll.selection AND pr.listing_status = 'L'
                    group by ll.selection ORDER BY ll.mode='M' DESC, ll.rank
                LIMIT 10";

        if ($query = $this->db->query($sql, $platform_id)) {
            foreach ($query->result() as $row) {
                $res[] = $row->selection;
            }
            return $res;
        }
        return FALSE;
    }

    public function get_clearance_product_gird_info($platform_id)
    {
        $sql = "SELECT ll.selection
                    FROM landpage_listing ll
                    INNER JOIN product p
                        ON p.sku = ll.selection
                    INNER JOIN price pr
                        ON pr.platform_id = ll.platform_id
                    WHERE ll.platform_id = ? AND ll.type = 'CL' AND p.status = 2 and pr.sku=ll.selection AND pr.listing_status = 'L'
                    group by ll.selection ORDER BY ll.mode='M' DESC, ll.rank
                LIMIT 4";

        if ($query = $this->db->query($sql, $platform_id)) {
            foreach ($query->result() as $row) {
                $res[] = $row->selection;
            }
            return $res;
        }
        return FALSE;
    }

    public function get_shopprice_product_feed_dto($where = array(), $option = array(), $classname = "Get_shopprice_product_feed_dto")
    {
        $this->db->from("product AS p");
        $this->db->join("price AS pr", "p.sku = pr.sku", "INNER");
        $this->db->join("category AS cat", "cat.id = p.cat_id", "INNER");
        $this->db->join("brand AS b", "b.id = p.brand_id", "INNER");
        $this->db->join("platform_biz_var AS pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
        $this->db->join("product_content AS pc", "pc.prod_sku = p.sku AND pbv.language_id = pc.lang_id", "LEFT");
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, "p.sku, p.sku prod_id, p.upc, p.name prod_name,
            IF(p.warranty_in_month IS NOT NULL, CONCAT(CAST(p.warranty_in_month AS char),' MONTH LOCAL WARRANTY. ',pc.detail_desc), pc.detail_desc) detail_desc,
            cat.name cat_name, b.brand_name, p.name model, CONCAT('http://www.valuebasket.co.nz/en_NZ/', REPLACE(REPLACE(p.name, '.', '-'), ' ', '-') ,'/mainproduct/view/', p.sku, '?AF=SPNZ') product_url, CONCAT('http://www.valuebasket.com.nz/images/product/', p.sku, '.', p.image) image_url, '0' shipment_cost, pr.price, p.mpn");
    }

    public function get_get_price_product_feed_dto($where = array(), $option = array(), $classname = "Get_price_product_feed_dto")
    {
        $this->db->from("product AS p");
        $this->db->join("price AS pr", "p.sku = pr.sku", "INNER");
        $this->db->join("category AS cat", "cat.id = p.cat_id", "INNER");
        $this->db->join("brand AS b", "b.id = p.brand_id", "INNER");
        $this->db->join("platform_biz_var AS pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
        $this->db->join("product_content AS pc", "pc.prod_sku = p.sku AND pbv.language_id = pc.lang_id", "LEFT");
        $this->db->where(array("p.status" => 2, "p.website_status" => "I", "p.website_quantity > 0" => null, "pr.platform_id" => "WEBAU", "pr.listing_status" => "L", "p.sub_cat_id in (628, 74, 567)" => null, "pr.price > 0" => null));
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, "p.sku, p.sku prod_id, p.upc, p.name prod_name,
            IF(p.warranty_in_month IS NOT NULL, CONCAT(CAST(p.warranty_in_month AS char),' MONTH LOCAL WARRANTY. ',pc.detail_desc), pc.detail_desc) detail_desc,
            cat.name cat_name, b.brand_name, p.name model, CONCAT('http://www.valuebasket.com.au/en_AU/', REPLACE(REPLACE(p.name, '.', '-'), ' ', '-') ,'/mainproduct/view/', p.sku, '?AF=GP') product_url, CONCAT('http://www.valuebasket.com.au/images/product/', p.sku, '.', p.image) image_url, '0' shipment_cost, pr.price, p.mpn");
    }

    public function get_shopbot_product_feed_dto($where = array(), $option = array(), $classname = "Shopbot_product_feed_dto")
    {
        $this->db->from("product AS p");
        $this->db->join("price AS pr", "pr.sku = p.sku", "INNER");
        $this->db->join("category AS cat", "cat.id = p.cat_id", "INNER");
        $this->db->join("brand AS b", "b.id = p.brand_id", "INNER");
        $this->db->join("platform_biz_var AS pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
        $this->db->join("product_content AS pc", "pc.prod_sku = p.sku AND pc.lang_id = pbv.language_id", "LEFT");
        $this->db->join("supplier_prod AS sp", "sp.prod_sku = p.sku AND sp.supplier_status = 'A' AND sp.order_default = 1", "INNER");
        $this->db->where(array("p.status" => 2, "p.website_status" => "I", "p.website_quantity > 0" => null, "pr.listing_status" => "L", "pr.price > 0" => null));
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, "
            p.mpn,
            p.sku,
            b.brand_name,
            cat.name cat_name,
            p.name prod_name,
            IF(p.warranty_in_month IS NOT NULL, CONCAT(CAST(p.warranty_in_month AS char), ' MONTH LOCAL WARRANTY. ',pc.detail_desc), pc.detail_desc) detail_desc,
            CONCAT('http://www.valuebasket.com.au/en_AU/', REPLACE(REPLACE(p.name, '.', '-'), ' ', '-') ,'/mainproduct/view/', p.sku, '?AF=SB', IF(pbv.platform_country_id = 'NZ', 'NZ', '')) product_url, pr.price, pr.fixed_rrp, pr.rrp_factor, 'Y' availability, CONCAT('http://www.valuebasket.com.au/images/product/', p.sku, '.', p.image) image_url");
    }

    public function get_shopbot_nz_product_feed_dto($where = array(), $option = array(), $classname = "Shopbot_product_feed_dto")
    {
        $this->db->from("product AS p");
        $this->db->join("price AS pr", "pr.sku = p.sku", "INNER");
        $this->db->join("category AS cat", "cat.id = p.cat_id", "INNER");
        $this->db->join("brand AS b", "b.id = p.brand_id", "INNER");
        $this->db->join("platform_biz_var AS pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
        $this->db->join("product_content AS pc", "pc.prod_sku = p.sku AND pc.lang_id = pbv.language_id", "LEFT");
        $this->db->join("supplier_prod AS sp", "sp.prod_sku = p.sku AND sp.supplier_status = 'A' AND sp.order_default = 1", "INNER");
        $this->db->where(array("p.status" => 2, "p.website_status in ('I', 'P')" => null, "p.website_quantity > 0" => null, "pr.listing_status" => "L", "pr.price > 0" => null));
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, "p.mpn,
            p.sku,
            b.brand_name,
            cat.name cat_name,
            p.name prod_name,
            IF(p.warranty_in_month IS NOT NULL, CONCAT(CAST(p.warranty_in_month AS char),' MONTH LOCAL WARRANTY. ',pc.detail_desc), pc.detail_desc) detail_desc,
            CONCAT('http://www.valuebasket.com/en_NZ/', REPLACE(REPLACE(p.name, '.', '-'), ' ', '-') ,'/mainproduct/view/', p.sku, '?AF=SB', IF(pbv.platform_country_id = 'NZ', 'NZ', '')) product_url, pr.price, pr.fixed_rrp, pr.rrp_factor, 'Y' availability, CONCAT('http://www.valuebasket.com/images/product/', p.sku, '.', p.image) image_url");
    }

    public function get_my_shopping_com_au_product_feed_dto($where = array(), $option = array(), $classname = "My_shopping_com_au_product_feed_dto")
    {
        $this->db->from("product AS p");
        $this->db->join("price AS pr", "pr.sku = p.sku", "INNER");
        $this->db->join("category AS cat", "cat.id = p.cat_id", "INNER");
        $this->db->join("brand AS b", "b.id = p.brand_id", "INNER");
        $this->db->join("platform_biz_var AS pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
        $this->db->join("product_content AS pc", "pc.prod_sku = p.sku AND pc.lang_id = pbv.language_id", "LEFT");
        $this->db->join("supplier_prod AS sp", "sp.prod_sku = p.sku AND (sp.supplier_status = 'A' OR sp.supplier_status = 'C') AND sp.order_default = 1", "INNER");
        $this->db->join("category_mapping map", "p.sub_cat_id = map.id AND map.ext_party = 'MY_SHOPPING_COM_AU' AND map.level = 2 AND map.status = 1", "INNER");
        $this->db->where(array("p.status" => 2, "p.website_status in ('I', 'P')" => null, "p.website_quantity > 0" => null, "pr.platform_id" => "WEBAU", "pr.listing_status" => "L", "pr.price > 0" => null));
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, "p.sku, p.name prod_name,
            IF(
                LENGTH(pc.detail_desc) > 250, CONCAT(IF(p.warranty_in_month IS NOT NULL, CONCAT(CAST(p.warranty_in_month AS char),' MONTH LOCAL WARRANTY. '), ''), substr(pc.detail_desc, 1, 250), ' ...'),
                CONCAT(IF(p.warranty_in_month IS NOT NULL, CONCAT(CAST(p.warranty_in_month AS char),' MONTH LOCAL WARRANTY. '), ''), pc.detail_desc)
            ) detail_desc,
                map.ext_name cat_name, pr.price, CONCAT('http://www.valuebasket.com.au/en_AU/', REPLACE(REPLACE(p.name, '.', '-'), ' ', '-') ,'/mainproduct/view/', p.sku, '?AF=MY') product_url, CONCAT('http://cdn.valuebasket.com/808AA1/vb/images/product/', p.sku, '.', p.image) image_url, b.brand_name, '0' shipping, if(p.website_status = 'I' OR p.website_status = 'P', 'Y', 'N') instock, CONCAT(p.warranty_in_month,' Month Local Warranty') as mpn, pr.platform_id");
    }

    public function get_tag_product_feed_dto($where = array(), $option = array(), $classname = "Tag_product_feed_dto")
    {
        $this->db->from("product AS p");
        $this->db->join("price AS pr", "pr.sku = p.sku", "INNER");
        $this->db->join("category AS cat", "cat.id = p.cat_id", "INNER");
        $this->db->join("category AS sc", "sc.id = p.sub_cat_id", "INNER");
        $this->db->join("brand AS b", "b.id = p.brand_id", "INNER");
        $this->db->join("platform_biz_var AS pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
        $this->db->join("product_content AS pc", "pc.prod_sku = p.sku AND pc.lang_id = pbv.language_id", "LEFT");
        $this->db->join("supplier_prod AS sp", "sp.prod_sku = p.sku AND sp.supplier_status = 'A' AND sp.order_default = 1", "INNER");
        $this->db->where(array("p.status" => 2, "p.website_status" => "I", "p.website_quantity > 0" => null, "pr.listing_status" => "L", "pr.price > 0" => null));
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, "pr.platform_id, p.sku, p.cat_id, p.name prod_name, CONCAT_WS(' > ', cat.name, sc.name) cat_name, pr.price, CONCAT('/', REPLACE(REPLACE(p.name, '.', '-'), ' ', '-'), '/mainproduct/view/', p.sku, '?AF=TAG', pbv.platform_country_id) product_url, CONCAT('/images/product/', p.sku, '.', p.image) image_url, b.brand_name,  IF(LENGTH(pc.detail_desc) > 250, CONCAT(substr(pc.detail_desc, 1, 250), ' ...'), pc.detail_desc) detail_desc, p.ean");
        // echo "<pre>"; var_dump($data); echo "</pre>"; die(); return $data;
    }

    public function get_price_panda_product_feed_dto($where = array(), $option = array(), $classname = "Price_panda_product_feed_dto")
    {
        /*
        if ($where["pr.platform_id"] == "WEBSG")
        {
            $c_id = 'SG';
        }else{
            $c_id = 'MY';
        }
        */
        $c_id = $where['c_id'];
        unset($where['c_id']);
        $this->db->from("product AS p");
        $this->db->join("price AS pr", "pr.sku = p.sku", "INNER");
        $this->db->join("delivery_time AS dt", "dt.scenarioid = pr.delivery_scenarioid AND dt.country_id = '{$c_id}' AND dt.status = 1", "INNER");
        $this->db->join("category AS cat", "cat.id = p.cat_id", "INNER");
        $this->db->join("category AS sc", "sc.id = p.sub_cat_id", "INNER");
        $this->db->join("brand AS b", "b.id = p.brand_id", "INNER");
        $this->db->join("platform_biz_var AS pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
        $this->db->join("product_content AS pc", "pc.prod_sku = p.sku AND pc.lang_id = pbv.language_id", "LEFT");
        $this->db->join("supplier_prod AS sp", "sp.prod_sku = p.sku AND sp.supplier_status = 'A' AND sp.order_default = 1", "INNER");
        $this->db->where(array("p.status" => 2, "p.website_status" => "I", "p.website_quantity > 0" => null, "pr.listing_status" => "L", "pr.price > 0" => null));
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, "pr.platform_id, p.sku, p.name prod_name, CONCAT_WS(' > ', cat.name, sc.name) cat_name, pr.price, CONCAT('/', REPLACE(REPLACE(p.name, '.', '-'), ' ', '-'), '/mainproduct/view/', p.sku) product_url, CONCAT('/images/product/', p.sku, '.', p.image) image_url, b.brand_name,  IF(LENGTH(pc.detail_desc) > 250, CONCAT(substr(pc.detail_desc, 1, 250), ' ...'), pc.detail_desc) detail_desc, p.ean, p.create_on,CONCAT(dt. ship_min_day, '-', dt. ship_max_day, ' days') delivery_time");
    }

    public function get_criteo_product_feed_product_feed_dto($where = array(), $option = array(), $classname = "Criteo_product_feed_dto")
    {
        $this->db->from("product AS p");
        $this->db->join("price AS pr", "pr.sku = p.sku AND pr.listing_status = 'L' AND pr.platform_id = 'WEBGB'", "INNER");
        $this->db->join("platform_biz_var AS pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
        $this->db->join("product_content AS pc", "pc.prod_sku = p.sku AND pc.lang_id = pbv.language_id", "LEFT");
        $this->db->join("category AS cat", "cat.id = p.cat_id", "INNER");
        $this->db->join("category AS sc", "sc.id = p.sub_cat_id", "INNER");
        $this->db->where(array("p.status" => 2, "p.website_status" => "I", "p.website_quantity > 0" => null, "pr.price > 0" => null));
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, "p.sku, IFNULL(pc.prod_name, p.name) prod_name, p.image, cat.name cat_name, sc.name sub_cat_name, pc.short_desc, pr.price");
    }

    public function get_graysonline_product_feed_dto($where = array(), $option = array(), $classname = "Graysonline_product_feed_dto")
    {
        $this->db->from("product AS p");
        $this->db->join("sku_mapping AS map", "map.sku = p.sku AND map.ext_sys = 'WMS' AND map.status = 1");
        $this->db->join("price AS pr", "pr.sku = p.sku AND pr.platform_id = 'WEBAU' AND pr.listing_status = 'L'", "INNER");
        $this->db->join("platform_biz_var AS pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
        $this->db->join("product_content AS pc", "pc.prod_sku = p.sku AND pc.lang_id = pbv.language_id", "LEFT");
        $this->db->join("product_content_extend AS pcex", "pcex.prod_sku = p.sku AND pcex.lang_id = pbv.language_id", "LEFT");
        $this->db->join("category AS cat", "cat.id = p.cat_id", "INNER");
        $this->db->join("category AS sc", "sc.id = p.sub_cat_id", "INNER");
        $this->db->join("supplier_prod AS sp", "sp.prod_sku = p.sku AND order_default = 1");
        $this->db->where(array("p.status" => 2, "p.website_status" => "I", "p.website_quantity > 0" => null, "pr.price > 0" => null));
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, "p.sku, map.ext_sku, IFNULL(pc.prod_name, p.name) prod_name, pc.detail_desc, pcex.specification, cat.name cat_name, sc.name sub_cat_name, p.sub_cat_id, pr.price, CONCAT('http://www.valuebasket.com/images/product/' , p.sku, '.', p.image) image_url, sp.supplier_status, sp.lead_day, if(sp.modify_on > DATE_SUB(now(), INTERVAL 7 DAY), 1, 0) last_week_updated");
    }

    public function get_kelkoo_product_feed_dto($where = array(), $option = array(), $classname = "Kelkoo_product_feed_dto", $country_id = "FR")
    {
        switch ($country_id) {
            case "ES":
                return $this->get_kelkoo_product_feed_es_dto($where, $option, $classname);
                break;
            case "FR":
                return $this->get_kelkoo_product_feed_fr_dto($where, $option, $classname);
                break;
            case "BE":
                return $this->get_kelkoo_product_feed_be_dto($where, $option, $classname);
                break;
            case "IT":
                return $this->get_kelkoo_product_feed_it_dto($where, $option, $classname);
                break;
        }
    }

    public function get_kelkoo_product_feed_es_dto($where = array(), $option = array(), $classname = "Kelkoo_product_feed_dto")
    {
        $this->db->from("product AS p");
        $this->db->join("sku_mapping AS map", "map.sku = p.sku AND map.ext_sys = 'WMS' AND map.status = 1");
        $this->db->join("price AS pr", "pr.sku = p.sku AND pr.platform_id = 'WEBES' AND pr.listing_status = 'L'", "INNER");
        $this->db->join("delivery_time AS dt", "dt.scenarioid = pr.delivery_scenarioid AND dt.country_id = 'ES' AND dt.status = 1", "INNER");
        $this->db->join("platform_biz_var AS pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
        $this->db->join("product_content AS pc", "pc.prod_sku = p.sku AND pc.lang_id = pbv.language_id", "LEFT");
        $this->db->join("brand AS brand", "brand.id = p.brand_id", "INNER");
        $this->db->join("category AS cat", "cat.id = p.cat_id", "INNER");
        $this->db->join("category AS sc", "sc.id = p.sub_cat_id", "INNER");
        $this->db->join("supplier_prod AS sp", "sp.prod_sku = p.sku AND order_default = 1 AND supplier_status = 'A'", "INNER");
        $this->db->join("product_identifier AS pi", "pi.prod_grp_cd = p.prod_grp_cd AND pi.colour_id = p.colour_id AND pi.country_id = pbv.platform_country_id AND pi.status = 1", "INNER");
        $this->db->join("v_prod_w_platform_biz_var AS v_ppbv", "v_ppbv.sku = p.sku AND v_ppbv.platform_id = pr.platform_id", "INNER");
        # SBF#2497 - category exclusion to be removed
        $this->db->where(array("p.status" => 2, "p.website_status" => "I", "p.website_quantity > 0" => null, "pi.ean is not NULL" => null));
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, "
            p.sku,
            map.ext_sku,
            replace(replace(pc.detail_desc, '\r\n', ''), '\n', '') detail_desc,
            IFNULL(pc.prod_name, p.name) prod_name,
            CONCAT('http://www.valuebasket.es/es_ES/mainproduct/view/', p.sku, '?AF=KOES') prod_url,
            CONCAT('http://cdn.valuebasket.com/808AA1/vb/images/product/' , p.sku, '.', p.image) image_url,
            pr.price, pi.ean, CONCAT(cat.name, ' ', sc.name) category,
            pi.mpn, brand.brand_name,
            1 as availability,
            0 as delivery_cost,
            CONCAT('entre ', dt. ship_min_day, '-', dt. ship_max_day, ' días laborables') as delivery_time,
            CONCAT(p.warranty_in_month,' meses de garantía') as warranty,
            0 as `condition`,
            v_ppbv.platform_id, v_ppbv.prod_weight, v_ppbv.free_delivery_limit,
            v_ppbv.delivery_charge, v_ppbv.platform_country_id,
            v_ppbv.declared_pcent, v_ppbv.platform_commission,
            v_ppbv.duty_pcent, v_ppbv.payment_charge_percent,
            v_ppbv.forex_fee_percent, v_ppbv.vat_percent,
            v_ppbv.supplier_cost, v_ppbv.listing_fee,
            v_ppbv.sub_cat_margin, v_ppbv.admin_fee,
            pr.fixed_rrp, pr.rrp_factor");
    }

    private function get_kelkoo_product_feed_fr_dto($where = array(), $option = array(), $classname = "Kelkoo_product_feed_dto")
    {
        $this->db->from("product AS p");
        $this->db->join("sku_mapping AS map", "map.sku = p.sku AND map.ext_sys = 'WMS' AND map.status = 1");
        $this->db->join("price AS pr", "pr.sku = p.sku AND pr.platform_id = 'WEBFR' AND pr.listing_status = 'L'", "INNER");
        $this->db->join("platform_biz_var AS pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
        $this->db->join("product_content AS pc", "pc.prod_sku = p.sku AND pc.lang_id = pbv.language_id", "LEFT");
        $this->db->join("brand AS brand", "brand.id = p.brand_id", "INNER");
        $this->db->join("category AS cat", "cat.id = p.cat_id", "INNER");
        $this->db->join("category AS sc", "sc.id = p.sub_cat_id", "INNER");
        $this->db->join("supplier_prod AS sp", "sp.prod_sku = p.sku AND order_default = 1 AND supplier_status = 'A'", "INNER");
        $this->db->join("product_identifier AS pi", "pi.prod_grp_cd = p.prod_grp_cd AND pi.colour_id = p.colour_id AND pi.country_id = pbv.platform_country_id AND pi.status = 1", "LEFT");
        $this->db->join("v_prod_w_platform_biz_var AS v_ppbv", "v_ppbv.sku = p.sku AND v_ppbv.platform_id = pr.platform_id", "INNER");

        $this->db->where(array("p.status" => 2, "p.website_status" => "I", "p.website_quantity > 0" => null, "pi.ean is not NULL" => null,
            # SBF#2613 - include category 53 (remove it from the brackets)
            "p.cat_id not in (5,8)" => null));

        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, "
            p.sku,
            map.ext_sku,
            replace(replace(pc.detail_desc, '\r\n', ''), '\n', '') detail_desc,
            IFNULL(pc.prod_name, p.name) prod_name,
            CONCAT('http://www.valuebasket.fr/fr_FR/mainproduct/view/', p.sku, '?AF=KOFR') prod_url,
            CONCAT('http://cdn.valuebasket.com/808AA1/vb/images/product/' , p.sku, '.', p.image) image_url,
            pr.price, pi.ean,
            CONCAT(cat.name, ' ', sc.name) category,
            pi.mpn,
            brand.brand_name,
            1 as availability,
            0 as delivery_cost,
            'sous 6 à 9 jours ouvrables' as delivery_time,
            CONCAT(p.warranty_in_month,' mois de garantie') as warranty,
            0 as `condition`, v_ppbv.platform_id,
            v_ppbv.prod_weight, v_ppbv.free_delivery_limit, v_ppbv.delivery_charge,
            v_ppbv.platform_country_id, v_ppbv.declared_pcent,
            v_ppbv.platform_commission, v_ppbv.duty_pcent,
            v_ppbv.payment_charge_percent, v_ppbv.forex_fee_percent,
            v_ppbv.vat_percent, v_ppbv.supplier_cost, v_ppbv.listing_fee,
            v_ppbv.sub_cat_margin, v_ppbv.admin_fee,
            pr.fixed_rrp, pr.rrp_factor");
    }

    public function get_kelkoo_product_feed_be_dto($where = array(), $option = array(), $classname = "Kelkoo_product_feed_dto")
    {
        $this->db->from("product AS p");
        $this->db->join("sku_mapping AS map", "map.sku = p.sku AND map.ext_sys = 'WMS' AND map.status = 1");
        $this->db->join("price AS pr", "pr.sku = p.sku AND pr.platform_id = 'WEBBE' AND pr.listing_status = 'L'", "INNER");
        $this->db->join("delivery_time AS dt", "dt.scenarioid = pr.delivery_scenarioid AND dt.country_id = 'BE' AND dt.status = 1", "INNER");
        $this->db->join("platform_biz_var AS pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
        $this->db->join("product_content AS pc", "pc.prod_sku = p.sku AND pc.lang_id = pbv.language_id", "LEFT");
        $this->db->join("brand AS brand", "brand.id = p.brand_id", "INNER");
        $this->db->join("category AS cat", "cat.id = p.cat_id", "INNER");
        $this->db->join("category AS sc", "sc.id = p.sub_cat_id", "INNER");
        $this->db->join("supplier_prod AS sp", "sp.prod_sku = p.sku AND order_default = 1 AND supplier_status = 'A'", "INNER");
        $this->db->join("product_identifier AS pi", "pi.prod_grp_cd = p.prod_grp_cd AND pi.colour_id = p.colour_id AND pi.country_id = pbv.platform_country_id AND pi.status = 1", "LEFT");
        $this->db->join("v_prod_w_platform_biz_var AS v_ppbv", "v_ppbv.sku = p.sku AND v_ppbv.platform_id = pr.platform_id", "INNER");

        $this->db->where(array("p.status" => 2, "p.website_status" => "I", "p.website_quantity > 0" => null, "pi.ean is not NULL" => null,
            # SBF #2653 Exclude Apple (5), Computer&gaming(8)
            "p.cat_id not in (5,8)" => null));
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, "
            p.sku,
            map.ext_sku,
            replace(replace(pc.detail_desc, '\r\n', ''), '\n', '') detail_desc,
            IFNULL(pc.prod_name, p.name) prod_name,
            CONCAT('http://www.valuebasket.be/fr_BE/mainproduct/view/', p.sku, '?AF=KOBE') prod_url,
            CONCAT('http://cdn.valuebasket.com/808AA1/vb/images/product/' , p.sku, '.', p.image) image_url,
            pr.price, pi.ean, CONCAT(cat.name, ' ', sc.name) category,
            pi.mpn, brand.brand_name,
            1 as availability,
            0 as delivery_cost,
            CONCAT(dt. del_min_day, ' - ', dt. del_max_day, ' jours') AS delivery_time,
            CONCAT(p.warranty_in_month,' mois de garantie') as warranty,
            0 as `condition`,
            v_ppbv.platform_id, v_ppbv.prod_weight, v_ppbv.free_delivery_limit,
            v_ppbv.delivery_charge, v_ppbv.platform_country_id,
            v_ppbv.declared_pcent, v_ppbv.platform_commission,
            v_ppbv.duty_pcent, v_ppbv.payment_charge_percent,
            v_ppbv.forex_fee_percent, v_ppbv.vat_percent,
            v_ppbv.supplier_cost, v_ppbv.listing_fee,
            v_ppbv.sub_cat_margin, v_ppbv.admin_fee,
            pr.fixed_rrp, pr.rrp_factor");
    }

    private function get_kelkoo_product_feed_it_dto($where = array(), $option = array(), $classname = "Kelkoo_product_feed_dto")
    {
        $this->db->from("product AS p");
        $this->db->join("sku_mapping AS map", "map.sku = p.sku AND map.ext_sys = 'WMS' AND map.status = 1");
        $this->db->join("price AS pr", "pr.sku = p.sku AND pr.platform_id = 'WEBIT' AND pr.listing_status = 'L'", "INNER");
        $this->db->join("platform_biz_var AS pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
        $this->db->join("product_content AS pc", "pc.prod_sku = p.sku AND pc.lang_id = pbv.language_id", "LEFT");
        $this->db->join("brand AS brand", "brand.id = p.brand_id", "INNER");
        $this->db->join("category AS cat", "cat.id = p.cat_id", "INNER");
        $this->db->join("category AS sc", "sc.id = p.sub_cat_id", "INNER");
        $this->db->join("supplier_prod AS sp", "sp.prod_sku = p.sku AND order_default = 1 AND supplier_status = 'A'", "INNER");
        $this->db->join("product_identifier AS pi", "pi.prod_grp_cd = p.prod_grp_cd AND pi.colour_id = p.colour_id AND pi.country_id = pbv.platform_country_id AND pi.status = 1", "LEFT");
        $this->db->join("v_prod_w_platform_biz_var AS v_ppbv", "v_ppbv.sku = p.sku AND v_ppbv.platform_id = pr.platform_id", "INNER");

        $this->db->where(array("p.status" => 2, "p.website_status" => "I", "p.website_quantity > 0" => null, "pi.ean is not NULL" => null, "p.cat_id not in (5,8)" => null));

        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, "
            p.sku,
            map.ext_sku,
            replace(replace(pc.detail_desc, '\r\n', ''), '\n', '') detail_desc,
            IFNULL(pc.prod_name, p.name) prod_name,
            CONCAT('http://www.valuebasket.com/it_IT/mainproduct/view/', p.sku, '?AF=KOIT') prod_url,
            CONCAT('http://cdn.valuebasket.com/808AA1/vb/images/product/' , p.sku, '.', p.image) image_url,
            pr.price, pi.ean,
            CONCAT(cat.name, ' ', sc.name) category,
            pi.mpn,
            brand.brand_name,
            1 as availability,
            0 as delivery_cost,
            '6-9 giorni lavorativi' as delivery_time,
            CONCAT(p.warranty_in_month,' Garanzia in mesi') as warranty,
            0 as `condition`, v_ppbv.platform_id,
            v_ppbv.prod_weight, v_ppbv.free_delivery_limit, v_ppbv.delivery_charge,
            v_ppbv.platform_country_id, v_ppbv.declared_pcent,
            v_ppbv.platform_commission, v_ppbv.duty_pcent,
            v_ppbv.payment_charge_percent, v_ppbv.forex_fee_percent,
            v_ppbv.vat_percent, v_ppbv.supplier_cost, v_ppbv.listing_fee,
            v_ppbv.sub_cat_margin, v_ppbv.admin_fee,
            pr.fixed_rrp, pr.rrp_factor");
    }


    public function get_yandex_product_feed_dto($where = array(), $option = array(), $classname = "Yandex_product_feed_dto", $platform_id = "WEBRU")
    {
        switch ($platform_id) {
            case "WEBRU":
                return $this->get_yandex_product_feed_ru_dto($where, $option, $classname, $platform_id);
                break;
        }
    }

    public function get_yandex_product_feed_ru_dto($where = array(), $option = array(), $classname, $platform_id)
    {
        $rs = array();
        $this->db->from("product AS p");
        $this->db->join("sku_mapping AS map", "map.sku = p.sku AND map.ext_sys = 'WMS' AND map.status = 1");
        $this->db->join("price AS pr", "pr.sku = p.sku AND pr.platform_id = '$platform_id' AND pr.listing_status = 'L'", "INNER");
        $this->db->join("platform_biz_var AS pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
        $this->db->join("product_content AS pc", "pc.prod_sku = p.sku AND pc.lang_id = pbv.language_id", "LEFT");
        $this->db->join("brand AS brand", "brand.id = p.brand_id", "INNER");
        $this->db->join("category AS cat", "cat.id = p.cat_id", "INNER");
        $this->db->join("category AS sc", "sc.id = p.sub_cat_id", "INNER");
        $this->db->join("category_extend AS ce", "ce.cat_id = p.cat_id AND ce.lang_id = pbv.language_id", "LEFT");
        $this->db->join("category_extend AS sce", "sce.cat_id = sc.id AND sce.lang_id = pbv.language_id", "LEFT");
        $this->db->join("supplier_prod AS sp", "sp.prod_sku = p.sku AND order_default = 1 AND supplier_status = 'A'", "INNER");
        $this->db->join("product_identifier AS pi", "pi.prod_grp_cd = p.prod_grp_cd AND pi.colour_id = p.colour_id AND pi.country_id = pbv.platform_country_id AND pi.status = 1", "LEFT");
        $this->db->join("v_prod_w_platform_biz_var AS v_ppbv", "v_ppbv.sku = p.sku AND v_ppbv.platform_id = pr.platform_id", "INNER");
        $this->db->where(array("p.status" => 2, "p.website_status" => "I", "p.sourcing_status" => "A", "p.website_quantity > 0" => null
                // , "p.cat_id != 4"=>null
            )
        );
        // $this->include_dto($classname);
        $this->db->select("
            p.sku,
            map.ext_sku,
            replace(replace(pc.detail_desc, '\r\n', ' '), '\n', ' ') detail_desc,
            IFNULL(pc.prod_name, p.name) prod_name,
            CONCAT(pc.model_1, ' ', pc.model_2) AS model,
            CONCAT('http://www.valuebasket.ru/ru_RU/mainproduct/view/', p.sku, '?AF=YMRU') prod_url,
            CONCAT('http://cdn.valuebasket.com/808AA1/vb/images/product/' , p.sku, '.', p.image) image_url,
            pr.price, pi.ean,
            p.sub_cat_id,
            p.cat_id,
            p.warranty_in_month,
            IFNULL(ce.name, cat.name) category,
            IFNULL(sce.name, sc.name) sub_cat,
            pi.mpn, brand.brand_name,
            1 as availability,
            0 as delivery_cost,
            0 as delivery_time,
            0 as `condition`,
            v_ppbv.platform_id, v_ppbv.prod_weight, v_ppbv.free_delivery_limit,
            v_ppbv.delivery_charge, v_ppbv.platform_country_id,
            v_ppbv.declared_pcent, v_ppbv.platform_commission,
            v_ppbv.duty_pcent, v_ppbv.payment_charge_percent,
            v_ppbv.forex_fee_percent, v_ppbv.vat_percent,
            v_ppbv.supplier_cost, v_ppbv.listing_fee,
            v_ppbv.sub_cat_margin, v_ppbv.admin_fee,
            pr.fixed_rrp, pr.rrp_factor
            ", FALSE);

        if ($query = $this->db->get()) {
            foreach ($query->result() as $row) {
                $rs[] = $row;
            }
            return (object)$rs;
        }

        return FALSE;
    }

    public function get_ceneo_product_feed_dto($where = array(), $option = array(), $classname = "Ceneo_product_feed_dto", $platform_id = "WEBRU")
    {
        switch ($platform_id) {
            case "WEBPL":
                return $this->get_ceneo_product_feed_pl_dto($where, $option, $classname, $platform_id);
                break;
        }
    }

    public function get_ceneo_product_feed_pl_dto($where = array(), $option = array(), $classname, $platform_id)
    {
        $rs = array();
        switch ($platform_id) {
            case 'WEBPL':
                $main_url = "http://www.valuebasket.pl/pl_PL";
                $af_id = "CEPL";
                break;

            default:
                $main_url = "http://www.valuebasket.com";
                $af_id = "";
                break;
        }
        $this->db->from("product AS p");
        $this->db->join("sku_mapping AS map", "map.sku = p.sku AND map.ext_sys = 'WMS' AND map.status = 1");
        $this->db->join("price AS pr", "pr.sku = p.sku AND pr.platform_id = '$platform_id' AND pr.listing_status = 'L'", "INNER");
        $this->db->join("platform_biz_var AS pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
        $this->db->join("product_content AS pc", "pc.prod_sku = p.sku AND pc.lang_id = pbv.language_id", "LEFT");
        $this->db->join("brand AS brand", "brand.id = p.brand_id", "INNER");
        $this->db->join("category AS cat", "cat.id = p.cat_id", "INNER");
        $this->db->join("category AS sc", "sc.id = p.sub_cat_id", "INNER");
        $this->db->join("category_extend AS ce", "ce.cat_id = p.cat_id AND ce.lang_id = pbv.language_id", "LEFT");
        $this->db->join("category_extend AS sce", "sce.cat_id = sc.id AND sce.lang_id = pbv.language_id", "LEFT");
        $this->db->join("supplier_prod AS sp", "sp.prod_sku = p.sku AND order_default = 1 AND supplier_status = 'A'", "INNER");
        $this->db->join("product_identifier AS pi", "pi.prod_grp_cd = p.prod_grp_cd AND pi.colour_id = p.colour_id AND pi.country_id = pbv.platform_country_id AND pi.status = 1", "LEFT");
        $this->db->join("v_prod_w_platform_biz_var AS v_ppbv", "v_ppbv.sku = p.sku AND v_ppbv.platform_id = pr.platform_id", "INNER");
        $this->db->where(array("p.status" => 2, "p.website_status" => "I", "p.sourcing_status" => "A", "p.website_quantity > 0" => null));
        // $this->include_dto($classname);
        $this->db->select("
            p.sku,
            map.ext_sku,
            pbv.language_id,
            pr.delivery_scenarioid,
            p.website_quantity,
            replace(replace(pc.detail_desc, '\r\n', ' '), '\n', ' ') detail_desc,
            IFNULL(pc.prod_name, p.name) prod_name,
            CONCAT(pc.model_1, ' ', pc.model_2) AS model,
            CONCAT('$main_url/mainproduct/view/', p.sku, '?AF=$af_id') prod_url,
            CONCAT('http://cdn.valuebasket.com/808AA1/vb/images/product/' , p.sku, '.', p.image) image_url,
            pr.price, pi.ean,
            p.sub_cat_id,
            p.cat_id,
            p.warranty_in_month,
            IFNULL(ce.name, cat.name) category,
            IFNULL(sce.name, sc.name) sub_cat,
            IFNULL(pi.mpn, map.ext_sku) AS mpn, brand.brand_name,
            1 as availability,
            0 as delivery_cost,
            0 as delivery_time,
            0 as `condition`,
            v_ppbv.platform_id, v_ppbv.prod_weight, v_ppbv.free_delivery_limit,
            v_ppbv.delivery_charge, v_ppbv.platform_country_id,
            v_ppbv.declared_pcent, v_ppbv.platform_commission,
            v_ppbv.duty_pcent, v_ppbv.payment_charge_percent,
            v_ppbv.forex_fee_percent, v_ppbv.vat_percent,
            v_ppbv.supplier_cost, v_ppbv.listing_fee,
            v_ppbv.sub_cat_margin, v_ppbv.admin_fee,
            pr.fixed_rrp, pr.rrp_factor
            ", FALSE);

        if ($query = $this->db->get()) {
            foreach ($query->result() as $row) {
                $rs[] = $row;
            }
            return (object)$rs;
        }

        return FALSE;
    }

    public function get_skapiec_product_feed_dto($where = array(), $option = array(), $classname = "Skapiec_product_feed_dto", $platform_id = "WEBRU")
    {
        switch ($platform_id) {
            case "WEBPL":
                return $this->get_skapiec_product_feed_pl_dto($where, $option, $classname, $platform_id);
                break;
        }
    }

    public function get_skapiec_product_feed_pl_dto($where = array(), $option = array(), $classname, $platform_id)
    {
        $rs = array();
        switch ($platform_id) {
            case 'WEBPL':
                $main_url = "http://www.valuebasket.pl/pl_PL";
                $af_id = "SKAPL";
                break;

            default:
                $main_url = "http://www.valuebasket.com";
                $af_id = "";
                break;
        }
        $this->db->from("product AS p");
        $this->db->join("sku_mapping AS map", "map.sku = p.sku AND map.ext_sys = 'WMS' AND map.status = 1");
        $this->db->join("price AS pr", "pr.sku = p.sku AND pr.platform_id = '$platform_id' AND pr.listing_status = 'L'", "INNER");
        $this->db->join("platform_biz_var AS pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
        $this->db->join("product_content AS pc", "pc.prod_sku = p.sku AND pc.lang_id = pbv.language_id", "LEFT");
        $this->db->join("brand AS brand", "brand.id = p.brand_id", "INNER");
        $this->db->join("category AS cat", "cat.id = p.cat_id", "INNER");
        $this->db->join("category AS sc", "sc.id = p.sub_cat_id", "INNER");
        $this->db->join("category_extend AS ce", "ce.cat_id = p.cat_id AND ce.lang_id = pbv.language_id", "LEFT");
        $this->db->join("category_extend AS sce", "sce.cat_id = sc.id AND sce.lang_id = pbv.language_id", "LEFT");
        $this->db->join("supplier_prod AS sp", "sp.prod_sku = p.sku AND order_default = 1 AND supplier_status = 'A'", "INNER");
        $this->db->join("product_identifier AS pi", "pi.prod_grp_cd = p.prod_grp_cd AND pi.colour_id = p.colour_id AND pi.country_id = pbv.platform_country_id AND pi.status = 1", "LEFT");
        $this->db->join("v_prod_w_platform_biz_var AS v_ppbv", "v_ppbv.sku = p.sku AND v_ppbv.platform_id = pr.platform_id", "INNER");
        $this->db->where(array("p.status" => 2, "p.website_status" => "I", "p.sourcing_status" => "A", "p.website_quantity > 0" => null));
        // $this->include_dto($classname);
        $this->db->select("
            p.sku,
            map.ext_sku,
            pbv.language_id,
            pr.delivery_scenarioid,
            p.website_quantity,
            replace(replace(pc.detail_desc, '\r\n', ' '), '\n', ' ') detail_desc,
            IFNULL(pc.prod_name, p.name) prod_name,
            CONCAT(pc.model_1, ' ', pc.model_2) AS model,
            CONCAT('$main_url/mainproduct/view/', p.sku, '?AF=$af_id') prod_url,
            CONCAT('http://cdn.valuebasket.com/808AA1/vb/images/product/' , p.sku, '.', p.image) image_url,
            pr.price, pi.ean,
            p.sub_cat_id,
            p.cat_id,
            p.warranty_in_month,
            IFNULL(ce.name, cat.name) category,
            IFNULL(sce.name, sc.name) sub_cat,
            IFNULL(pi.mpn, map.ext_sku) AS mpn, brand.brand_name,
            1 as availability,
            0 as delivery_cost,
            0 as delivery_time,
            0 as `condition`,
            v_ppbv.platform_id, v_ppbv.prod_weight, v_ppbv.free_delivery_limit,
            v_ppbv.delivery_charge, v_ppbv.platform_country_id,
            v_ppbv.declared_pcent, v_ppbv.platform_commission,
            v_ppbv.duty_pcent, v_ppbv.payment_charge_percent,
            v_ppbv.forex_fee_percent, v_ppbv.vat_percent,
            v_ppbv.supplier_cost, v_ppbv.listing_fee,
            v_ppbv.sub_cat_margin, v_ppbv.admin_fee,
            pr.fixed_rrp, pr.rrp_factor
            ", FALSE);

        if ($query = $this->db->get()) {
            foreach ($query->result() as $row) {
                $rs[] = $row;
            }
            return (object)$rs;
        }

        return FALSE;
    }

    public function get_shoppydoo_product_feed_dto($where = array(), $option = array(), $country_id = "ES", $classname = "Shoppydoo_product_feed_dto")
    {
        switch ($country_id) {
            case "ES":
                return $this->get_shoppydoo_product_feed_es_dto($where, $option, $classname);
                break;
        }
    }

    public function get_shoppydoo_product_feed_es_dto($where = array(), $option = array(), $classname = "Shoppydoo_product_feed_dto")
    {
        $this->db->from("product AS p");
        $this->db->join("sku_mapping AS map", "map.sku = p.sku AND map.ext_sys = 'WMS' AND map.status = 1");
        $this->db->join("price AS pr", "pr.sku = p.sku AND pr.platform_id = 'WEBES' AND pr.listing_status = 'L'", "INNER");
        $this->db->join("delivery_time AS dt", "dt.scenarioid = pr.delivery_scenarioid AND dt.country_id = 'ES' AND dt.status = 1", "INNER");
        $this->db->join("platform_biz_var AS pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
        $this->db->join("product_content AS pc", "pc.prod_sku = p.sku AND pc.lang_id = pbv.language_id", "LEFT");
        $this->db->join("brand AS brand", "brand.id = p.brand_id", "INNER");
        $this->db->join("category AS cat", "cat.id = p.cat_id", "INNER");
        $this->db->join("category AS sc", "sc.id = p.sub_cat_id", "INNER");
        $this->db->join("supplier_prod AS sp", "sp.prod_sku = p.sku AND order_default = 1 AND supplier_status = 'A'", "INNER");
        $this->db->join("product_identifier AS pi", "pi.prod_grp_cd = p.prod_grp_cd AND pi.colour_id = p.colour_id AND pi.country_id = pbv.platform_country_id AND pi.status = 1", "INNER");
        $this->db->join("v_prod_w_platform_biz_var AS v_ppbv", "v_ppbv.sku = p.sku AND v_ppbv.platform_id = pr.platform_id", "INNER");
        $this->db->where(array("p.status" => 2, "p.website_status" => "I", "p.website_quantity > 0" => null, "pi.ean is not NULL" => null));
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, "
            p.sku,
            map.ext_sku,
            replace(replace(pc.detail_desc, '\r\n', ''), '\n', '') detail_desc,
            IFNULL(pc.prod_name, p.name) prod_name,
            CONCAT('http://www.valuebasket.es/es_ES/mainproduct/view/', p.sku, '?AF=SHOES') prod_url,
            CONCAT('http://cdn.valuebasket.com/808AA1/vb/images/product/' , p.sku, '.', p.image) image_url,
            pr.price, pi.ean, CONCAT(cat.name, ' ', sc.name) category,
            pi.mpn, brand.brand_name,
            1 as availability,
            0 as delivery_cost,
            CONCAT('entre ', dt. ship_min_day, '-', dt. ship_max_day, ' días laborables') as delivery_time,
            CONCAT(p.warranty_in_month,' meses de garantía') as warranty,
            0 as `condition`,
            v_ppbv.platform_id, v_ppbv.prod_weight, v_ppbv.free_delivery_limit,
            v_ppbv.delivery_charge, v_ppbv.platform_country_id,
            v_ppbv.declared_pcent, v_ppbv.platform_commission,
            v_ppbv.duty_pcent, v_ppbv.payment_charge_percent,
            v_ppbv.forex_fee_percent, v_ppbv.vat_percent,
            v_ppbv.supplier_cost, v_ppbv.listing_fee,
            v_ppbv.sub_cat_margin, v_ppbv.admin_fee,
            pr.fixed_rrp, pr.rrp_factor");
    }

    public function get_shopall_product_feed_dto($where = array(), $option = array(), $classname = "Shopall_product_feed_dto", $platform_id = "WEBES")
    {
        switch ($platform_id) {
            # go to function according to respective business logic,
            # and for hardcoded column translations e.g. delivery_time
            case "WEBES":
            case "WEBPT":
                return $this->get_shopall_product_feed_group1_dto($platform_id, $where, $option, $classname);
                break;

            default:
                break;
        }
    }

    public function get_shopall_product_feed_group1_dto($platform_id, $where = array(), $option = array(), $classname)
    {
        switch ($platform_id) {
            case 'WEBES':
                $main_url = 'http://www.valuebasket.es/es_ES/mainproduct/view/';
                $af_id = 'SHAES';
                break;

            case 'WEBPT':
                $main_url = 'http://www.valuebasket.pt/es_PT/mainproduct/view/';
                $af_id = 'SHAPT';
                break;

            default:
                $main_url = '';
                $af_id = '';
                break;
        }
        $this->db->from("product AS p");
        $this->db->join("sku_mapping AS map", "map.sku = p.sku AND map.ext_sys = 'WMS' AND map.status = 1");
        $this->db->join("price AS pr", "pr.sku = p.sku AND pr.platform_id = '$platform_id' AND pr.listing_status = 'L'", "INNER");
        $this->db->join("platform_biz_var AS pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
        $this->db->join("product_content AS pc", "pc.prod_sku = p.sku AND pc.lang_id = pbv.language_id", "LEFT");
        $this->db->join("brand AS brand", "brand.id = p.brand_id", "INNER");
        $this->db->join("category AS cat", "cat.id = p.cat_id", "INNER");
        $this->db->join("category AS sc", "sc.id = p.sub_cat_id", "INNER");
        $this->db->join("supplier_prod AS sp", "sp.prod_sku = p.sku AND order_default = 1 AND supplier_status = 'A'", "INNER");
        $this->db->join("product_identifier AS pi", "pi.prod_grp_cd = p.prod_grp_cd AND pi.colour_id = p.colour_id AND pi.country_id = pbv.platform_country_id AND pi.status = 1", "INNER");
        $this->db->join("v_prod_w_platform_biz_var AS v_ppbv", "v_ppbv.sku = p.sku AND v_ppbv.platform_id = pr.platform_id", "INNER");
        # SBF#2497 - category exclusion to be removed
        $this->db->where(array("p.status" => 2, "p.website_status" => "I", "p.website_quantity > 0" => null, "pi.ean is not NULL" => null));
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, "
            p.sku,
            map.ext_sku,
            replace(replace(pc.detail_desc, '\r\n', ''), '\n', '') detail_desc,
            IFNULL(pc.prod_name, p.name) prod_name,
            CONCAT('$main_url', p.sku, '?AF=$af_id') prod_url,
            CONCAT('http://cdn.valuebasket.com/808AA1/vb/images/product/' , p.sku, '.', p.image) image_url,
            pr.price, pi.ean, CONCAT(cat.name, ' ', sc.name) category,
            pi.mpn, brand.brand_name,
            1 as availability,
            0 as delivery_cost,
            'entre 6-9 días laborables' as delivery_time,
            CONCAT(p.warranty_in_month,' meses de garantía') as warranty,
            0 as `condition`,
            v_ppbv.platform_id, v_ppbv.prod_weight, v_ppbv.free_delivery_limit,
            v_ppbv.delivery_charge, v_ppbv.platform_country_id,
            v_ppbv.declared_pcent, v_ppbv.platform_commission,
            v_ppbv.duty_pcent, v_ppbv.payment_charge_percent,
            v_ppbv.forex_fee_percent, v_ppbv.vat_percent,
            v_ppbv.supplier_cost, v_ppbv.listing_fee,
            v_ppbv.sub_cat_margin, v_ppbv.admin_fee,
            pr.fixed_rrp, pr.rrp_factor");
    }

    public function get_nextag_product_feed_dto($where = array(), $option = array(), $classname = "Nextag_product_feed_dto", $platform_id = "WEBES")
    {
        switch ($platform_id) {
            # go to function according to respective business logic,
            # and for hardcoded column translations e.g. delivery_time
            case "WEBES":
                return $this->get_nextag_product_feed_group1_dto($platform_id, $where, $option, $classname);
                break;

            default:
                break;
        }
    }

    public function get_nextag_product_feed_group1_dto($platform_id, $where = array(), $option = array(), $classname)
    {
        switch ($platform_id) {
            case 'WEBES':
                $main_url = 'http://www.valuebasket.es/es_ES/mainproduct/view/';
                $af_id = 'NXES';
                break;

            default:
                $main_url = '';
                $af_id = '';
                break;
        }
        $this->db->from("product AS p");
        $this->db->join("sku_mapping AS map", "map.sku = p.sku AND map.ext_sys = 'WMS' AND map.status = 1");
        $this->db->join("price AS pr", "pr.sku = p.sku AND pr.platform_id = '$platform_id' AND pr.listing_status = 'L'", "INNER");
        $this->db->join("platform_biz_var AS pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
        $this->db->join("product_content AS pc", "pc.prod_sku = p.sku AND pc.lang_id = pbv.language_id", "LEFT");
        $this->db->join("brand AS brand", "brand.id = p.brand_id", "INNER");
        $this->db->join("category AS cat", "cat.id = p.cat_id", "INNER");
        $this->db->join("category AS sc", "sc.id = p.sub_cat_id", "INNER");
        $this->db->join("supplier_prod AS sp", "sp.prod_sku = p.sku AND order_default = 1 AND supplier_status = 'A'", "INNER");
        $this->db->join("product_identifier AS pi", "pi.prod_grp_cd = p.prod_grp_cd AND pi.colour_id = p.colour_id AND pi.country_id = pbv.platform_country_id AND pi.status = 1", "INNER");
        $this->db->join("v_prod_w_platform_biz_var AS v_ppbv", "v_ppbv.sku = p.sku AND v_ppbv.platform_id = pr.platform_id", "INNER");
        # SBF#2497 - category exclusion to be removed
        $this->db->where(array("p.status" => 2, "p.website_status" => "I", "p.website_quantity > 0" => null, "pi.ean is not NULL" => null));
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, "
            p.sku,
            map.ext_sku,
            replace(replace(pc.detail_desc, '\r\n', ''), '\n', '') detail_desc,
            IFNULL(pc.prod_name, p.name) prod_name,
            CONCAT('$main_url', p.sku, '?AF=$af_id') prod_url,
            CONCAT('http://cdn.valuebasket.com/808AA1/vb/images/product/' , p.sku, '.', p.image) image_url,
            pr.price, pi.ean, CONCAT(cat.name, ' ', sc.name) category,
            pi.mpn, brand.brand_name,
            1 as availability,
            0 as delivery_cost,
            'entre 6-9 días laborables' as delivery_time,
            CONCAT(p.warranty_in_month,' meses de garantía') as warranty,
            0 as `condition`,
            v_ppbv.platform_id, v_ppbv.prod_weight, v_ppbv.free_delivery_limit,
            v_ppbv.delivery_charge, v_ppbv.platform_country_id,
            v_ppbv.declared_pcent, v_ppbv.platform_commission,
            v_ppbv.duty_pcent, v_ppbv.payment_charge_percent,
            v_ppbv.forex_fee_percent, v_ppbv.vat_percent,
            v_ppbv.supplier_cost, v_ppbv.listing_fee,
            v_ppbv.sub_cat_margin, v_ppbv.admin_fee,
            pr.fixed_rrp, pr.rrp_factor");
    }

    public function get_comparer_product_feed_dto($where = array(), $option = array(), $classname = "Comparer_product_feed_dto", $country_id = "FR")
    {
        switch ($country_id) {
            case "FR":
                return $this->get_comparer_product_feed_fr_dto($where, $option, $classname);
                break;
            case "BE":
                return $this->get_comparer_product_feed_be_dto($where, $option, $classname);
                break;
        }
    }

    private function get_comparer_product_feed_fr_dto($where = array(), $option = array(), $classname = "Comparer_product_feed_dto")
    {
        $this->db->from("product AS p");
        $this->db->join("sku_mapping AS map", "map.sku = p.sku AND map.ext_sys = 'WMS' AND map.status = 1");
        $this->db->join("price AS pr", "pr.sku = p.sku AND pr.platform_id = 'WEBFR' AND pr.listing_status = 'L'", "INNER");
        $this->db->join("delivery_time AS dt", "dt.scenarioid = pr.delivery_scenarioid AND dt.country_id = 'FR' AND dt.status = 1", "INNER");
        $this->db->join("platform_biz_var AS pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
        $this->db->join("product_content AS pc", "pc.prod_sku = p.sku AND pc.lang_id = pbv.language_id", "LEFT");
        $this->db->join("brand AS brand", "brand.id = p.brand_id", "INNER");
        $this->db->join("category_extend AS cat", "cat.cat_id = p.cat_id AND cat.lang_id = pc.lang_id", "INNER");
        $this->db->join("category_extend AS sc", "sc.cat_id = p.sub_cat_id AND sc.lang_id = pc.lang_id", "INNER");
        $this->db->join("supplier_prod AS sp", "sp.prod_sku = p.sku AND order_default = 1 AND supplier_status = 'A'", "INNER");
        $this->db->join("product_identifier AS pi", "pi.prod_grp_cd = p.prod_grp_cd AND pi.colour_id = p.colour_id AND pi.country_id = pbv.platform_country_id AND pi.status = 1", "LEFT");
        $this->db->join("v_prod_w_platform_biz_var AS v_ppbv", "v_ppbv.sku = p.sku AND v_ppbv.platform_id = pr.platform_id", "INNER");
        $this->db->where(array("p.status" => 2, "p.website_status" => "I", "p.website_quantity > 0" => null, "pi.ean is not NULL" => null,
            #SBF #2511 Exclude Apple (5), Computer&gaming(8)
            "p.cat_id not in (5,8)" => null));
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname,
            "p.sku, map.ext_sku, p.website_quantity,
                    replace(replace(pc.detail_desc, '\r\n', ''), '\n', '') detail_desc,
                    IFNULL(pc.prod_name, p.name) prod_name,
                    CONCAT('http://www.valuebasket.fr/fr_FR/mainproduct/view/', p.sku, '?AF=CRFR') prod_url,
                    CONCAT('http://cdn.valuebasket.com/808AA1/vb/images/product/' , p.sku, '.', p.image) image_url,
                    pr.price,
                    pi.ean,
                    cat.name category,
                    sc.name sub_category,
                     pi.mpn,
                     brand.brand_name,
                     1 as availability,
                     0 as delivery_cost,
                     CONCAT(dt. del_min_day, ' - ', dt. del_max_day, ' jours') AS delivery_time,
                     CONCAT(p.warranty_in_month,' mois') as warranty,
                     0 as `condition`,
                     v_ppbv.platform_id, v_ppbv.prod_weight, v_ppbv.free_delivery_limit,
                     v_ppbv.delivery_charge, v_ppbv.platform_country_id, v_ppbv.declared_pcent,
                     v_ppbv.platform_commission, v_ppbv.duty_pcent, v_ppbv.payment_charge_percent,
                     v_ppbv.forex_fee_percent, v_ppbv.vat_percent, v_ppbv.supplier_cost,
                     v_ppbv.listing_fee, v_ppbv.sub_cat_margin, v_ppbv.admin_fee,
                     pr.fixed_rrp, pr.rrp_factor");
    }

    private function get_comparer_product_feed_be_dto($where = array(), $option = array(), $classname = "Comparer_product_feed_dto")
    {
        $this->db->from("product AS p");
        $this->db->join("sku_mapping AS map", "map.sku = p.sku AND map.ext_sys = 'WMS' AND map.status = 1");
        $this->db->join("price AS pr", "pr.sku = p.sku AND pr.platform_id = 'WEBBE' AND pr.listing_status = 'L'", "INNER");
        $this->db->join("delivery_time AS dt", "dt.scenarioid = pr.delivery_scenarioid AND dt.country_id = 'BE' AND dt.status = 1", "INNER");
        $this->db->join("platform_biz_var AS pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
        $this->db->join("product_content AS pc", "pc.prod_sku = p.sku AND pc.lang_id = pbv.language_id", "LEFT");
        $this->db->join("brand AS brand", "brand.id = p.brand_id", "INNER");
        $this->db->join("category_extend AS cat", "cat.cat_id = p.cat_id AND cat.lang_id = pc.lang_id", "INNER");
        $this->db->join("category_extend AS sc", "sc.cat_id = p.sub_cat_id AND sc.lang_id = pc.lang_id", "INNER");
        $this->db->join("supplier_prod AS sp", "sp.prod_sku = p.sku AND order_default = 1 AND supplier_status = 'A'", "INNER");
        $this->db->join("product_identifier AS pi", "pi.prod_grp_cd = p.prod_grp_cd AND pi.colour_id = p.colour_id AND pi.country_id = pbv.platform_country_id AND pi.status = 1", "LEFT");
        $this->db->join("v_prod_w_platform_biz_var AS v_ppbv", "v_ppbv.sku = p.sku AND v_ppbv.platform_id = pr.platform_id", "INNER");
        $this->db->where(array("p.status" => 2, "p.website_status" => "I", "p.website_quantity > 0" => null, "pi.ean is not NULL" => null,
            # SBF #2653 Exclude Apple (5), Computer&gaming(8)
            "p.cat_id not in (5, 8)" => null));
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname,
            "p.sku, map.ext_sku, p.website_quantity,
                    replace(replace(pc.detail_desc, '\r\n', ''), '\n', '') detail_desc,
                    IFNULL(pc.prod_name, p.name) prod_name,
                    CONCAT('http://www.valuebasket.be/fr_BE/mainproduct/view/', p.sku, '?AF=CRBE') prod_url,
                    CONCAT('http://cdn.valuebasket.com/808AA1/vb/images/product/' , p.sku, '.', p.image) image_url,
                    pr.price,
                    pi.ean,
                    cat.name category,
                    sc.name sub_category,
                     pi.mpn,
                     brand.brand_name,
                     1 as availability,
                     0 as delivery_cost,
                     CONCAT(dt. del_min_day, ' - ', dt. del_max_day, ' jours') AS delivery_time,
                     CONCAT(p.warranty_in_month,' mois') as warranty,
                     0 as `condition`,
                     v_ppbv.platform_id, v_ppbv.prod_weight, v_ppbv.free_delivery_limit,
                     v_ppbv.delivery_charge, v_ppbv.platform_country_id, v_ppbv.declared_pcent,
                     v_ppbv.platform_commission, v_ppbv.duty_pcent, v_ppbv.payment_charge_percent,
                     v_ppbv.forex_fee_percent, v_ppbv.vat_percent, v_ppbv.supplier_cost,
                     v_ppbv.listing_fee, v_ppbv.sub_cat_margin, v_ppbv.admin_fee,
                     pr.fixed_rrp, pr.rrp_factor");
    }

    public function get_omg_product_feed_dto($where = array(), $option = array(), $classname = "Omg_product_feed_dto", $country_id = "SG")
    {
        switch ($country_id) {
            case "SG":
                return $this->get_omg_product_feed_sg_dto($where, $option, $classname);
                break;
            case "MY":
                return $this->get_omg_product_feed_my_dto($where, $option, $classname);
                break;
        }
    }

    private function get_omg_product_feed_sg_dto($where = array(), $option = array(), $classname = "Omg_product_feed_dto")
    {
        $this->db->from("product AS p");
        $this->db->join("price pr", "pr.sku=p.sku AND pr.listing_status = 'L' AND pr.platform_id='WEBSG'", "INNER");
        //$this->db->join("v_prod_w_platform_biz_var AS v_ppbv", "v_ppbv.sku = p.sku AND v_ppbv.platform_id = pr.platform_id", "INNER");
        $this->db->join("delivery_time AS dt", "dt.scenarioid = pr.delivery_scenarioid AND dt.country_id = 'SG' AND dt.status = 1", "INNER");
        $this->db->join("category cat", "p.cat_id=cat.id", "INNER");
        $this->db->join("category scat", "p.cat_id=scat.id", "INNER");
        $this->db->join("brand br", "p.brand_id=br.id", "INNER");
        $this->db->join("platform_biz_var AS pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
        $this->db->join("product_identifier AS pi", "pi.prod_grp_cd = p.prod_grp_cd AND pi.colour_id = p.colour_id AND pi.country_id = pbv.platform_country_id AND pi.status = 1", "LEFT");
        $this->db->join("product_content pc", "p.sku=pc.prod_sku AND pc.lang_id='en'", "LEFT");
        $this->db->where(array("p.website_quantity > 0" => null, "p.status" => 2, "p.website_status IN ('I','P')" => null));
        $this->include_dto($classname);

        return $this->common_get_list($where, $option, $classname, "
            p.sku AS sku,
            p.`name` AS prod_name,
            LEFT (REPLACE(REPLACE (pc.detail_desc, '\r\n', ' '), '\n', ' '),500) AS detail_desc,
            CONCAT('http://www.valuebasket.com.sg/en_SG/mainproduct/view/',p.sku,'?AF=OMGSG') AS prod_url,
            CONCAT('http://cdn.valuebasket.com/808AA1/vb/images/product/', p.sku, '.', p.image) AS image_url,
            CONCAT(cat.name,'|',scat.name) AS category,
            pr.price AS price,
            br.brand_name AS brand_name,
            pi.mpn AS mpn,
            p.warranty_in_month AS warranty,
            p.website_status AS stock_status,
            'True' AS free_shipping,
            '-1' AS active,
            'SGD' AS currency,
            '0' AS can_aggregate,
            CONCAT(dt. ship_min_day, '-', dt. ship_max_day, ' days') AS delivery_time,
            '0' AS delivery_cost,
            pr.fixed_rrp, pr.rrp_factor");
    }

    private function get_omg_product_feed_my_dto($where = array(), $option = array(), $classname = "Omg_product_feed_dto")
    {
        $this->db->from("product AS p");
        $this->db->join("price pr", "pr.sku=p.sku AND pr.listing_status = 'L' AND pr.platform_id='WEBMY'", "INNER");
        $this->db->join("delivery_time AS dt", "dt.scenarioid = pr.delivery_scenarioid AND dt.country_id = 'MY' AND dt.status = 1", "INNER");
        $this->db->join("category cat", "p.cat_id=cat.id", "INNER");
        $this->db->join("category scat", "p.cat_id=scat.id", "INNER");
        $this->db->join("brand br", "p.brand_id=br.id", "INNER");
        $this->db->join("platform_biz_var AS pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
        $this->db->join("product_identifier AS pi", "pi.prod_grp_cd = p.prod_grp_cd AND pi.colour_id = p.colour_id AND pi.country_id = pbv.platform_country_id AND pi.status = 1", "LEFT");
        $this->db->join("product_content pc", "p.sku=pc.prod_sku AND pc.lang_id='en'", "LEFT");
        $this->db->where(array("p.website_quantity > 0" => null, "p.status" => 2, "p.website_status IN ('I','P')" => null));
        $this->include_dto($classname);

        return $this->common_get_list($where, $option, $classname, "
            p.sku AS sku,
            p.`name` AS prod_name,
            LEFT (REPLACE(REPLACE (pc.detail_desc, '\r\n', ' '), '\n', ' '),500) AS detail_desc,
            CONCAT('http://www.valuebasket.com/en_MY/mainproduct/view/',p.sku,'?AF=OMGMY') AS prod_url,
            pr.price AS price,
            p.warranty_in_month AS warranty,
            CONCAT('http://cdn.valuebasket.com/808AA1/vb/images/product/', p.sku, '.', p.image) AS image_url,
            CONCAT(cat.name,'|',scat.name) AS category,
            br.brand_name AS brand_name,
            pi.mpn AS mpn,
            '-1' AS active,
            'MYR' AS currency,
            '0' AS can_aggregate,
            'True' AS free_shipping,
            CONCAT(dt. ship_min_day, '-', dt. ship_max_day, ' days') AS delivery_time,
            '0' AS delivery_cost,
            p.website_status AS stock_status,
            'New' as `condition`,
            pr.fixed_rrp, pr.rrp_factor");
    }


    public function get_tradedoubler_product_feed_dto($where = array(), $option = array(), $classname = "Tradedoubler_product_feed_dto", $country = "FR")
    {
        switch ($country) {
            case "IT":
                return $this->get_tradedoubler_product_feed_it_dto($where, $option, $classname);
                break;
            case "FR":
                return $this->get_tradedoubler_product_feed_fr_dto($where, $option, $classname);
                break;
            case "ES":
                return $this->get_tradedoubler_product_feed_es_dto($where, $option, $classname);
                break;
            case 'GB':
                return $this->get_tradedoubler_product_feed_gb_dto($where, $option, $classname);
                break;
            case 'PL':
                return $this->get_tradedoubler_product_feed_pl_dto($where, $option, $classname);
                break;
            default:
                return FALSE;
                break;
        }
    }

    private function get_tradedoubler_product_feed_it_dto($where = array(), $option = array(), $classname = "Tradedoubler_product_feed_dto", $country = "IT")
    {
        # use IT specific URL

        $this->include_dto($classname);
        $this->db->from("product AS p");
        $this->db->join("sku_mapping AS map", "map.sku = p.sku AND map.ext_sys = 'WMS' AND map.status = 1");
        $this->db->join("price AS pr", "pr.sku = p.sku AND pr.platform_id = 'WEBIT' AND pr.listing_status = 'L'", "INNER");
        $this->db->join("delivery_time AS dt", "dt.scenarioid = pr.delivery_scenarioid AND dt.country_id = 'IT' AND dt.status = 1", "INNER");
        $this->db->join("platform_biz_var AS pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
        $this->db->join("product_content AS pc", "pc.prod_sku = p.sku AND pc.lang_id = pbv.language_id", "LEFT");
        $this->db->join("brand AS brand", "brand.id = p.brand_id", "INNER");
        $this->db->join("category AS cat", "cat.id = p.cat_id", "INNER");
        $this->db->join("category AS sc", "sc.id = p.sub_cat_id", "INNER");
        $this->db->join("supplier_prod AS sp", "sp.prod_sku = p.sku AND order_default = 1 AND supplier_status = 'A'", "INNER");
        $this->db->join("product_identifier AS pi", "pi.prod_grp_cd = p.prod_grp_cd AND pi.colour_id = p.colour_id AND pi.country_id = pbv.platform_country_id AND pi.status = 1", "INNER");
        $this->db->join("v_prod_w_platform_biz_var AS v_ppbv", "v_ppbv.sku = p.sku AND v_ppbv.platform_id = pr.platform_id", "INNER");
        $this->db->where(array("p.status" => 2, "p.website_status" => "I", "p.website_quantity > 0" => null, "pi.ean is not NULL" => null, "p.cat_id not in (5,8,53)" => null));
        return $this->common_get_list($where, $option, $classname, "p.sku, map.ext_sku, replace(replace(pc.detail_desc, '\r\n', ''), '\n', '') detail_desc, IFNULL(pc.prod_name, p.name) prod_name, CONCAT('http://www.valuebasket.it/it_IT/mainproduct/view/', p.sku, '?AF=TDIT') prod_url, CONCAT('http://cdn.valuebasket.com/808AA1/vb/images/product/' , p.sku, '.', p.image) image_url, '' prod_feed, pr.price, pi.ean, CONCAT(cat.name, ' ', sc.name) category, pi.mpn, brand.brand_name, p.website_status AS availability,'free' AS delivery_cost, CONCAT(dt. ship_min_day, '-', dt. ship_max_day, ' days') delivery_time,
            'new' AS `condition`, CONCAT(p.warranty_in_month,' mesi di Garanzia') AS warranty,'EUR' AS currency, v_ppbv.platform_id, v_ppbv.prod_weight, v_ppbv.free_delivery_limit, v_ppbv.delivery_charge, v_ppbv.platform_country_id, v_ppbv.declared_pcent, v_ppbv.platform_commission, v_ppbv.duty_pcent, v_ppbv.payment_charge_percent, v_ppbv.forex_fee_percent, v_ppbv.vat_percent, v_ppbv.supplier_cost, v_ppbv.listing_fee, v_ppbv.sub_cat_margin, v_ppbv.admin_fee");
    }

    private function get_tradedoubler_product_feed_fr_dto($where = array(), $option = array(), $classname = "Tradedoubler_product_feed_dto", $country = "FR")
    {
        # use FR specific URL

        $this->include_dto($classname);
        $this->db->from("product AS p");
        $this->db->join("sku_mapping AS map", "map.sku = p.sku AND map.ext_sys = 'WMS' AND map.status = 1");
        $this->db->join("price AS pr", "pr.sku = p.sku AND pr.platform_id = 'WEBFR' AND pr.listing_status = 'L'", "INNER");
        $this->db->join("delivery_time AS dt", "dt.scenarioid = pr.delivery_scenarioid AND dt.country_id = 'FR' AND dt.status = 1", "INNER");
        $this->db->join("platform_biz_var AS pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
        $this->db->join("product_content AS pc", "pc.prod_sku = p.sku AND pc.lang_id = pbv.language_id", "LEFT");
        $this->db->join("brand AS brand", "brand.id = p.brand_id", "INNER");
        $this->db->join("category_extend AS cat", "cat.cat_id = p.cat_id and cat.lang_id = 'fr'", "INNER");
        $this->db->join("category_extend AS sc", "sc.cat_id = p.sub_cat_id and sc.lang_id = 'fr'", "INNER");
        $this->db->join("supplier_prod AS sp", "sp.prod_sku = p.sku AND order_default = 1 AND supplier_status = 'A'", "INNER");
        $this->db->join("product_identifier AS pi", "pi.prod_grp_cd = p.prod_grp_cd AND pi.colour_id = p.colour_id AND pi.country_id = pbv.platform_country_id AND pi.status = 1", "LEFT");
        $this->db->join("v_prod_w_platform_biz_var AS v_ppbv", "v_ppbv.sku = p.sku AND v_ppbv.platform_id = pr.platform_id", "INNER");
        $this->db->where(array("p.status" => 2, "p.website_status" => "I", "p.website_quantity > 0" => null, "p.cat_id not in (5,8,53)" => null));
        return $this->common_get_list($where, $option, $classname, "p.sku, map.ext_sku, replace(replace(pc.detail_desc, '\r\n', ''), '\n', '') detail_desc, IFNULL(pc.prod_name, p.name) prod_name, CONCAT('http://www.valuebasket.fr/fr_FR/mainproduct/view/', p.sku, '?AF=TDFR') prod_url, CONCAT('http://cdn.valuebasket.com/808AA1/vb/images/product/' , p.sku, '.', p.image) image_url, '' prod_feed, pr.price, pi.ean, CONCAT(cat.name, ' ', sc.name) category, pi.mpn, brand.brand_name, p.website_status AS availability,'Gratuit' AS delivery_cost, CONCAT(dt. del_min_day, '-', dt. del_max_day, ' jours') delivery_time,
            'Neuf' AS `condition`, CONCAT(p.warranty_in_month,' mois de garantie') AS warranty,'EUR' AS currency, v_ppbv.platform_id, v_ppbv.prod_weight, v_ppbv.free_delivery_limit, v_ppbv.delivery_charge, v_ppbv.platform_country_id, v_ppbv.declared_pcent, v_ppbv.platform_commission, v_ppbv.duty_pcent, v_ppbv.payment_charge_percent, v_ppbv.forex_fee_percent, v_ppbv.vat_percent, v_ppbv.supplier_cost, v_ppbv.listing_fee, v_ppbv.sub_cat_margin, v_ppbv.admin_fee");
    }

    private function get_tradedoubler_product_feed_es_dto($where = array(), $option = array(), $classname = "Tradedoubler_product_feed_dto", $country = "ES")
    {
        $this->include_dto($classname);
        $this->db->from("product AS p");
        $this->db->join("sku_mapping AS map", "map.sku = p.sku AND map.ext_sys = 'WMS' AND map.status = 1");
        $this->db->join("price AS pr", "pr.sku = p.sku AND pr.platform_id = 'WEBES' AND pr.listing_status = 'L'", "INNER");
        $this->db->join("delivery_time AS dt", "dt.scenarioid = pr.delivery_scenarioid AND dt.country_id = 'ES' AND dt.status = 1", "INNER");
        $this->db->join("platform_biz_var AS pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
        $this->db->join("product_content AS pc", "pc.prod_sku = p.sku AND pc.lang_id = pbv.language_id", "LEFT");
        $this->db->join("brand AS brand", "brand.id = p.brand_id", "INNER");
        $this->db->join("category_extend AS cat", "cat.cat_id = p.cat_id AND cat.lang_id='es'", "INNER");
        $this->db->join("category_extend AS sc", "sc.cat_id = p.sub_cat_id AND sc.lang_id='es'", "INNER");
        $this->db->join("supplier_prod AS sp", "sp.prod_sku = p.sku AND order_default = 1 AND supplier_status = 'A'", "INNER");
        $this->db->join("product_identifier AS pi", "pi.prod_grp_cd = p.prod_grp_cd AND pi.colour_id = p.colour_id AND pi.country_id = pbv.platform_country_id AND pi.status = 1", "INNER");
        $this->db->join("v_prod_w_platform_biz_var AS v_ppbv", "v_ppbv.sku = p.sku AND v_ppbv.platform_id = pr.platform_id", "INNER");
        $this->db->where(array("p.status" => 2, "p.website_status" => "I", "p.website_quantity > 0" => null, "pi.ean is not NULL" => null, "p.cat_id not in (5,8,53)" => null));
        return $this->common_get_list($where, $option, $classname, "p.sku, map.ext_sku, replace(replace(pc.detail_desc, '\r\n', ''), '\n', '') detail_desc, IFNULL(pc.prod_name, p.name) prod_name, CONCAT('http://www.valuebasket.es/es_ES/mainproduct/view/', p.sku, '?AF=TDES') prod_url, CONCAT('http://cdn.valuebasket.com/808AA1/vb/images/product/' , p.sku, '.', p.image) image_url, '' prod_feed, pr.price, pi.ean, CONCAT(cat.name, ' ', sc.name) category, pi.mpn, brand.brand_name, p.website_status AS availability,'Gratuito' AS delivery_cost, CONCAT(dt. ship_min_day, '-', dt. ship_max_day, ' días laborables') delivery_time,
            'Nuevo' AS `condition`, CONCAT(p.warranty_in_month,' meses de garantía') AS warranty,'EUR' AS currency, v_ppbv.platform_id, v_ppbv.prod_weight, v_ppbv.free_delivery_limit, v_ppbv.delivery_charge, v_ppbv.platform_country_id, v_ppbv.declared_pcent, v_ppbv.platform_commission, v_ppbv.duty_pcent, v_ppbv.payment_charge_percent, v_ppbv.forex_fee_percent, v_ppbv.vat_percent, v_ppbv.supplier_cost, v_ppbv.listing_fee, v_ppbv.sub_cat_margin, v_ppbv.admin_fee");
    }

    private function get_tradedoubler_product_feed_gb_dto($where = array(), $option = array(), $classname = "Tradedoubler_product_feed_dto", $country = "GB")
    {
        $this->include_dto($classname);
        $this->db->from("product AS p");
        $this->db->join("sku_mapping AS map", "map.sku = p.sku AND map.ext_sys = 'WMS' AND map.status = 1");
        $this->db->join("price AS pr", "pr.sku = p.sku AND pr.platform_id = 'WEBGB' AND pr.listing_status = 'L'", "INNER");
        $this->db->join("delivery_time AS dt", "dt.scenarioid = pr.delivery_scenarioid AND dt.country_id = 'GB' AND dt.status = 1", "INNER");
        $this->db->join("platform_biz_var AS pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
        $this->db->join("product_content AS pc", "pc.prod_sku = p.sku AND pc.lang_id = pbv.language_id", "LEFT");
        $this->db->join("brand AS brand", "brand.id = p.brand_id", "INNER");
        $this->db->join("category_extend AS cat", "cat.cat_id = p.cat_id AND cat.lang_id='en'", "INNER");
        $this->db->join("category_extend AS sc", "sc.cat_id = p.sub_cat_id AND sc.lang_id='en'", "INNER");
        $this->db->join("supplier_prod AS sp", "sp.prod_sku = p.sku AND order_default = 1 AND supplier_status = 'A'", "INNER");
        $this->db->join("product_identifier AS pi", "pi.prod_grp_cd = p.prod_grp_cd AND pi.colour_id = p.colour_id AND pi.country_id = pbv.platform_country_id AND pi.status = 1", "LEFT");
        $this->db->join("v_prod_w_platform_biz_var AS v_ppbv", "v_ppbv.sku = p.sku AND v_ppbv.platform_id = pr.platform_id", "INNER");
        $this->db->where(array("p.status" => 2, "p.website_status" => "I", "p.website_quantity > 0" => null, "p.cat_id not in (5,8,53)" => null));
        return $this->common_get_list($where, $option, $classname, "p.sku, map.ext_sku, replace(replace(pc.detail_desc, '\r\n', ''), '\n', '') detail_desc, IFNULL(pc.prod_name, p.name) prod_name, CONCAT('http://www.valuebasket.com/en_GB/mainproduct/view/', p.sku, '?AF=TDUK') prod_url, CONCAT('http://cdn.valuebasket.com/808AA1/vb/images/product/' , p.sku, '.', p.image) image_url, '' prod_feed, pr.price, pi.ean, CONCAT(cat.name, ' ', sc.name) category, pi.mpn, brand.brand_name, p.website_status AS availability,'free' AS delivery_cost, CONCAT(dt. del_min_day, '-', dt. del_max_day, ' days') delivery_time,
            'new' AS `condition`, CONCAT(p.warranty_in_month,' month warranty') AS warranty,'GBP' AS currency, v_ppbv.platform_id, v_ppbv.prod_weight, v_ppbv.free_delivery_limit, v_ppbv.delivery_charge, v_ppbv.platform_country_id, v_ppbv.declared_pcent, v_ppbv.platform_commission, v_ppbv.duty_pcent, v_ppbv.payment_charge_percent, v_ppbv.forex_fee_percent, v_ppbv.vat_percent, v_ppbv.supplier_cost, v_ppbv.listing_fee, v_ppbv.sub_cat_margin, v_ppbv.admin_fee");
    }

    private function get_tradedoubler_product_feed_pl_dto($where = array(), $option = array(), $classname = "Tradedoubler_product_feed_dto", $country = "PL")
    {
        # use FR specific URL

        $this->include_dto($classname);
        $this->db->from("product AS p");
        $this->db->join("sku_mapping AS map", "map.sku = p.sku AND map.ext_sys = 'WMS' AND map.status = 1");
        $this->db->join("price AS pr", "pr.sku = p.sku AND pr.platform_id = 'WEBPL' AND pr.listing_status = 'L'", "INNER");
        $this->db->join("delivery_time AS dt", "dt.scenarioid = pr.delivery_scenarioid AND dt.country_id = 'PL' AND dt.status = 1", "INNER");
        $this->db->join("platform_biz_var AS pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
        $this->db->join("product_content AS pc", "pc.prod_sku = p.sku AND pc.lang_id = pbv.language_id", "LEFT");
        $this->db->join("brand AS brand", "brand.id = p.brand_id", "INNER");
        $this->db->join("category_extend AS cat", "cat.cat_id = p.cat_id and cat.lang_id = 'pl'", "INNER");
        $this->db->join("category_extend AS sc", "sc.cat_id = p.sub_cat_id and sc.lang_id = 'pl'", "INNER");
        $this->db->join("supplier_prod AS sp", "sp.prod_sku = p.sku AND order_default = 1 AND supplier_status = 'A'", "INNER");
        $this->db->join("product_identifier AS pi", "pi.prod_grp_cd = p.prod_grp_cd AND pi.colour_id = p.colour_id AND pi.country_id = pbv.platform_country_id AND pi.status = 1", "LEFT");
        $this->db->join("v_prod_w_platform_biz_var AS v_ppbv", "v_ppbv.sku = p.sku AND v_ppbv.platform_id = pr.platform_id", "INNER");
        $this->db->where(array("p.status" => 2, "p.website_status" => "I", "p.website_quantity > 0" => null, "p.cat_id not in (5,8,53)" => null));
        return $this->common_get_list($where, $option, $classname, "p.sku, map.ext_sku, replace(replace(pc.detail_desc, '\r\n', ''), '\n', '') detail_desc, IFNULL(pc.prod_name, p.name) prod_name, CONCAT('http://www.valuebasket.pl/pl_PL/mainproduct/view/', p.sku, '?AF=TDPL') prod_url, CONCAT('http://cdn.valuebasket.com/808AA1/vb/images/product/' , p.sku, '.', p.image) image_url, '' prod_feed, pr.price, pi.ean, CONCAT(cat.name, ' ', sc.name) category, pi.mpn, brand.brand_name, p.website_status AS availability,'za darmo' AS delivery_cost, CONCAT(dt. del_min_day, '-', dt. del_max_day, ' dni') delivery_time,
            'nowy' AS `condition`, CONCAT(p.warranty_in_month,' miesięczna gwarancja') AS warranty,'zloty' AS currency, v_ppbv.platform_id, v_ppbv.prod_weight, v_ppbv.free_delivery_limit, v_ppbv.delivery_charge, v_ppbv.platform_country_id, v_ppbv.declared_pcent, v_ppbv.platform_commission, v_ppbv.duty_pcent, v_ppbv.payment_charge_percent, v_ppbv.forex_fee_percent, v_ppbv.vat_percent, v_ppbv.supplier_cost, v_ppbv.listing_fee, v_ppbv.sub_cat_margin, v_ppbv.admin_fee");
    }

    public function get_tradetracker_product_feed_dto($where = array(), $option = array(), $classname = "Tradetracker_product_feed_dto", $platform_id = "WEBBE")
    {
        switch ($platform_id) {
            case "WEBBE":
                return $this->get_tradetracker_product_feed_dto_1($where, $option, $classname);
                break;
            default:
                return FALSE;
                break;
        }
    }

    private function get_tradetracker_product_feed_dto_1($where = array(), $option = array(), $classname = "Tradetracker_product_feed_dto", $platform_id = "WEBBE")
    {
        switch ($platform_id) {
            case 'WEBBE':
                $main_url = "http://www.valuebasket.be/fr_BE";
                $af_id = "TTBE";
                $workingdaystext = "Jours Ouvrables";
                $warrantytext = " Mois de Garantie Gratuite ";
                $delivery_default = "6-9";
                $conditiontext = "Neuf";
                break;

            default:
                $main_url = "http://www.valuebasket.com";
                $af_id = "";
                $workingdaystext = "Working Days";
                $warrantytext = " Months Free Warranty";
                $delivery_default = "6-9";
                $conditiontext = "New";
                break;
        }
        $this->include_dto($classname);
        $this->db->from("product AS p");
        $this->db->join("sku_mapping AS map", "map.sku = p.sku AND map.ext_sys = 'WMS' AND map.status = 1");
        $this->db->join("price AS pr", "pr.sku = p.sku AND pr.platform_id = '{$platform_id}' AND pr.listing_status = 'L'", "INNER");
        $this->db->join("platform_biz_var AS pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
        $this->db->join("delivery_time dt", "pr.delivery_scenarioid = dt.scenarioid AND pbv.platform_country_id  = dt.country_id", "LEFT");
        $this->db->join("product_content AS pc", "pc.prod_sku = p.sku AND pc.lang_id = pbv.language_id", "LEFT");
        $this->db->join("brand AS brand", "brand.id = p.brand_id", "INNER");
        $this->db->join("category AS cat", "cat.id = p.cat_id", "INNER");
        $this->db->join("category AS sc", "sc.id = p.sub_cat_id", "INNER");
        $this->db->join("supplier_prod AS sp", "sp.prod_sku = p.sku AND order_default = 1 AND supplier_status = 'A'", "INNER");
        $this->db->join("product_identifier AS pi", "pi.prod_grp_cd = p.prod_grp_cd AND pi.colour_id = p.colour_id AND pi.country_id = pbv.platform_country_id AND pi.status = 1", "INNER");
        $this->db->join("v_prod_w_platform_biz_var AS v_ppbv", "v_ppbv.sku = p.sku AND v_ppbv.platform_id = pr.platform_id", "INNER");
        $this->db->where(
            array(
                "p.status" => 2,
                "p.website_status" => "I",
                "p.sourcing_status" => "A",
                "p.website_quantity > 0" => null
            )
        );
        return $this->common_get_list($where, $option, $classname,
            "p.sku, map.ext_sku,
                            LEFT(replace(replace(pc.detail_desc, '\r\n', ' '), '\n', ' '), 1000) detail_desc,
                            IFNULL(pc.prod_name, p.name) prod_name,
                            CONCAT('{$main_url}/mainproduct/view/', p.sku, '?AF={$af_id}') prod_url,
                            CONCAT('http://cdn.valuebasket.com/808AA1/vb/images/product/' , p.sku, '.', p.image) image_url,
                            '' prod_feed,
                            pr.price,
                            CONCAT(cat.name, '/', sc.name) category,
                            pi.mpn,  pi.ean,
                            CONCAT(brand.brand_name, ' ', IFNULL(pc.prod_name, p.name)) brand_name,
                            p.website_status AS availability,
                            '0.00' AS delivery_cost,
                            (CASE WHEN dt.del_min_day IS NULL
                                    THEN CONCAT('{$delivery_default}', ' {$workingdaystext}')
                                ELSE CONCAT(dt.del_min_day, '-', dt.del_max_day, ' {$workingdaystext}')
                            END) as delivery_time,
                            '{$conditiontext}' AS `condition`,
                            CONCAT(p.warranty_in_month, '{$warrantytext}') AS warranty,
                            'EUR' AS currency,
                            v_ppbv.platform_id, v_ppbv.prod_weight, v_ppbv.free_delivery_limit, v_ppbv.delivery_charge, v_ppbv.platform_country_id,
                            v_ppbv.declared_pcent, v_ppbv.platform_commission, v_ppbv.duty_pcent, v_ppbv.payment_charge_percent, v_ppbv.forex_fee_percent,
                            v_ppbv.vat_percent, v_ppbv.supplier_cost, v_ppbv.listing_fee, v_ppbv.sub_cat_margin, v_ppbv.admin_fee");
    }

    public function get_pricespy_product_feed_dto($where = array(), $option = array(), $classname = "Pricespy_product_feed_dto", $country = "NZ")
    {
        switch ($country) {
            case "NZ":
                return $this->get_pricespy_product_feed_nz_dto($where, $option, $classname);
                break;
        }
    }

    private function get_pricespy_product_feed_nz_dto($where = array(), $option = array(), $classname = "Pricespy_product_feed_dto")
    {
        $this->include_dto($classname);
        $this->db->from("product AS p");
        $this->db->join("sku_mapping AS map", "map.sku = p.sku AND map.ext_sys = 'WMS' AND map.status = 1");
        $this->db->join("price AS pr", "pr.sku = p.sku AND pr.platform_id = 'WEBNZ' AND pr.listing_status = 'L'", "INNER");
        $this->db->join("platform_biz_var AS pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
        $this->db->join("product_content AS pc", "pc.prod_sku = p.sku AND pc.lang_id = pbv.language_id", "LEFT");
        $this->db->join("brand AS brand", "brand.id = p.brand_id", "INNER");
        $this->db->join("category AS cat", "cat.id = p.cat_id", "INNER");
        $this->db->join("category AS sc", "sc.id = p.sub_cat_id", "INNER");
        $this->db->join("supplier_prod AS sp", "sp.prod_sku = p.sku AND order_default = 1 AND supplier_status = 'A'", "INNER");
        $this->db->join("product_identifier AS pi", "pi.prod_grp_cd = p.prod_grp_cd AND pi.colour_id = p.colour_id AND pi.country_id = pbv.platform_country_id AND pi.status = 1", "LEFT");
        $this->db->join("v_prod_w_platform_biz_var AS v_ppbv", "v_ppbv.sku = p.sku AND v_ppbv.platform_id = pr.platform_id", "INNER");
        $this->db->where(array("p.status" => 2, "(p.website_status = 'I' OR p.website_status='P')" => null, "p.website_quantity > 0" => null));
        return $this->common_get_list($where, $option, $classname, "
            p.sku,
            map.ext_sku,
            replace(replace(pc.detail_desc, '\r\n', ''), '\n', '') detail_desc,
            IFNULL(pc.prod_name, p.name) prod_name,
            CONCAT('http://www.valuebasket.co.nz/en_NZ/mainproduct/view/', p.sku, '?AF=PSNZ') prod_url,
            CONCAT('http://cdn.valuebasket.com/808AA1/vb/images/product/', p.sku, '.', p.image) image_url,
            pr.price,
            sc.name category,
            pi.mpn,
            brand.brand_name,
            p.website_quantity as availability,
            '0.00' as delivery_cost,
            v_ppbv.platform_id, v_ppbv.prod_weight, v_ppbv.free_delivery_limit, v_ppbv.delivery_charge, v_ppbv.platform_country_id, v_ppbv.declared_pcent, v_ppbv.platform_commission, v_ppbv.duty_pcent, v_ppbv.payment_charge_percent, v_ppbv.forex_fee_percent, v_ppbv.vat_percent, v_ppbv.supplier_cost, v_ppbv.listing_fee, v_ppbv.sub_cat_margin, v_ppbv.admin_fee");
    }

    public function get_priceme_product_feed_dto($where = array(), $option = array(), $classname = "Priceme_product_feed_dto")
    {
        $this->db->from("product AS p");
        $this->db->join("price AS pr", "p.sku = pr.sku", "INNER");
        $this->db->join("category AS cat", "cat.id = p.cat_id", "INNER");
        $this->db->join("brand AS b", "b.id = p.brand_id", "INNER");
        $this->db->join("platform_biz_var AS pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
        $this->db->join("product_content AS pc", "pc.prod_sku = p.sku AND pbv.language_id = pc.lang_id", "LEFT");
        $this->db->where(array("p.status" => 2, "p.website_status" => "I", "p.website_quantity > 0" => null, "pr.listing_status" => "L", "pr.price > 0" => null));
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, "p.sku, p.sku prod_id, p.upc, p.name prod_name, pc.detail_desc, cat.name cat_name, b.brand_name, p.name model, CONCAT('http://www.valuebasket.com.au/en_AU/', REPLACE(REPLACE(p.name, '.', '-'), ' ', '-') ,'/mainproduct/view/', p.sku, '?AF=GP') product_url, CONCAT('http://www.valuebasket.com.au/images/product/', p.sku, '.', p.image) image_url, '0' shipment_cost, pr.price, p.mpn");
    }

    public function get_priceme_product_feed_w_country_dto($where = array(), $option = array(), $classname = "Priceme_product_feed_dto", $platform_id)
    {
        switch ($platform_id) {
            case "WEBNZ":
                $url = 'http://www.valuebasket.co.nz/en_NZ/mainproduct/view/';
                $query = 'AF=PMNZ';
                $currency = 'NZD';
                break;
            case "WEBPH":
                $url = 'http://www.valuebasket.com.ph/en_PH/mainproduct/view/';
                $query = 'AF=PMPH';
                $currency = 'PHP';
                break;
            case "WEBSG":
                $url = 'http://www.valuebasket.com.sg/en_SG/mainproduct/view/';
                $query = 'AF=PMSG';
                $currency = 'SGD';
                break;
            case "WEBMY":
                $url = 'http://www.valuebasket.com/en_MY/mainproduct/view/';
                $query = 'AF=PMMY';
                $currency = 'MYR';
                break;
        }

        $this->db->from("product AS p");
        $this->db->join("sku_mapping AS map", "map.sku = p.sku AND map.ext_sys = 'WMS' AND map.status = 1", "INNER");
        $this->db->join("price AS pr", "pr.sku = p.sku AND pr.platform_id = '$platform_id' AND pr.listing_status = 'L'", "INNER");
        $this->db->join("platform_biz_var AS pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
        $this->db->join("brand AS brand", "brand.id = p.brand_id", "INNER");
        $this->db->join("category AS cat", "cat.id = p.cat_id", "INNER");
        $this->db->join("category AS sc", "sc.id = p.sub_cat_id", "INNER");
        $this->db->join("category AS ssc", "ssc.id = p.sub_sub_cat_id", "INNER");
        $this->db->join("supplier_prod AS sp", "sp.prod_sku = p.sku AND order_default = 1 AND supplier_status = 'A'", "INNER");
        $this->db->join("product_identifier AS pi", "pi.prod_grp_cd = p.prod_grp_cd AND pi.colour_id = p.colour_id AND pi.country_id = pbv.platform_country_id AND pi.status = 1", "LEFT");
        $this->db->join("v_prod_w_platform_biz_var AS v_ppbv", "v_ppbv.sku = p.sku AND v_ppbv.platform_id = pr.platform_id", "INNER");
        $this->db->join("product_content AS pc", "pc.prod_sku = p.sku AND pc.lang_id = pbv.language_id", "LEFT");
        $this->db->where(array("p.status" => 2, "p.website_status IN ('I','P')" => null, "p.website_quantity > 0" => null));
        $this->include_dto($classname);

        return
            $this->common_get_list($where, $option, $classname, "
                    p.sku,
                    map.ext_sku,
                    LEFT(replace(replace(pc.detail_desc, '\r\n', ''), '\n', ''), 250) detail_desc,
                    IFNULL(pc.prod_name, p.name) prod_name,
                    CONCAT('$url', p.sku, '?$query') product_url,
                    CONCAT('http://cdn.valuebasket.com/808AA1/vb/images/product/', p.sku, '.', p.image) image_url,
                    pr.price,
                    CONCAT(cat.name , ' > ' , sc.name , ' > ' , ssc.name) cat_name,
                    pi.mpn,
                    pi.upc,
                    'Y' AS stock_status,
                    'New' AS `condition`,
                    'visa, mastercard, paypal' AS payment_type,
                    'Best Prices with FREE Shipping and 36 Month Warranty!' AS promo_msg,
                    '$currency' AS currency,
                    brand.brand_name,
                    p.website_quantity as availability,
                    '0.00' as shipment_cost,
                    v_ppbv.platform_id, v_ppbv.prod_weight, v_ppbv.free_delivery_limit, v_ppbv.delivery_charge, v_ppbv.platform_country_id, v_ppbv.declared_pcent, v_ppbv.platform_commission, v_ppbv.duty_pcent, v_ppbv.payment_charge_percent, v_ppbv.forex_fee_percent, v_ppbv.vat_percent, v_ppbv.supplier_cost, v_ppbv.listing_fee, v_ppbv.sub_cat_margin, v_ppbv.admin_fee");


        var_dump($this->db->last_query());
        die();
    }

    public function get_prismastar_product_feed_dto($platform_id = "WEBGB", $classname = "Prismastar_product_feed_dto")
    {
        $option = array("limit" => -1);
        $this->db->from("product p");
        $this->db->join("price pr", "pr.sku = p.sku AND pr.listing_status = 'L'", "INNER");
        $this->db->join("colour clr", "clr.id = p.colour_id", "INNER");
        $this->db->join("platform_biz_var pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
        $this->db->join("category cat", "cat.id = p.cat_id", "INNER");
        $this->db->join("category sc", "sc.id = p.sub_cat_id", "INNER");
        $this->db->join("brand br", "br.id = p.brand_id", "INNER");
        $this->db->join("product_content pc", "pc.prod_sku = p.sku AND pc.lang_id = pbv.language_id", "LEFT");
        $this->db->join("product_content_extend pcex", "pcex.prod_sku = p.sku AND pcex.lang_id = pbv.language_id", "LEFT");
        $this->db->join("freight_category fc", "fc.id = p.freight_cat_id", "LEFT");
        $this->db->join("category_mapping map", "p.sku = map.id AND map.ext_party = 'GOOGLEBASE' AND map.level = 0 AND pbv.platform_country_id = map.country_id AND map.status = 1", "LEFT");
        $this->db->join("product_identifier pi", "pi.prod_grp_cd = p.prod_grp_cd AND pi.colour_id = p.colour_id AND pi.country_id = pbv.platform_country_id  AND pi.status = 1", "LEFT");
        $this->db->where(array("p.status" => 2, "pr.listing_status" => "L", "pr.platform_id" => $platform_id, "pr.price IS NOT NULL" => null, "p.sub_cat_id IN (42, 37, 38, 39)" => null));
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, 'pr.platform_id, p.sku, p.prod_grp_cd, p.version_id, p.colour_id, clr.name colour_name, pbv.platform_country_id, pbv.language_id, IFNULL(pc.prod_name, p.name) prod_name, p.cat_id, cat.name cat_name, p.sub_cat_id, sc.name sub_cat_name, p.brand_id, br.brand_name, pi.mpn, pi.upc, pi.ean, pc.short_desc, pc.detail_desc, pc.contents, pcex.feature, fc.weight, p.image, pbv.platform_currency_id, pr.price, p.quantity, p.display_quantity, p.website_quantity, p.website_status, p.status prod_status, pr.listing_status, p.ex_demo, map.ext_name google_product_category, fc.weight prod_weight');
    }

    public function get_shopping_com_fr_product_feed_dto($where = array(), $option = array(), $classname = "Shopping_com_fr_product_feed_dto")
    {
        $this->db->from("product AS p");
        $this->db->join("sku_mapping AS map", "map.sku = p.sku AND map.ext_sys = 'WMS' AND map.status = 1");
        $this->db->join("price AS pr", "pr.sku = p.sku AND pr.platform_id = 'WEBFR' AND pr.listing_status = 'L'", "INNER");
        $this->db->join("platform_biz_var AS pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
        $this->db->join("product_content AS pc", "pc.prod_sku = p.sku AND pc.lang_id = pbv.language_id", "LEFT");
        $this->db->join("brand AS brand", "brand.id = p.brand_id", "INNER");
        $this->db->join("(SELECT cat.id, COALESCE(catex.name, cat.name) name FROM `category` cat LEFT JOIN `category_extend` catex ON cat.id = catex.cat_id AND catex.lang_id = 'fr') AS cat", "cat.id = p.cat_id", "INNER");
        $this->db->join("(SELECT cat.id, COALESCE(catex.name, cat.name) name FROM `category` cat LEFT JOIN `category_extend` catex ON cat.id = catex.cat_id AND catex.lang_id = 'fr') AS sc", "sc.id = p.sub_cat_id", "INNER");
        $this->db->join("supplier_prod AS sp", "sp.prod_sku = p.sku AND order_default = 1 AND supplier_status = 'A'", "INNER");
        $this->db->join("product_identifier AS pi", "pi.prod_grp_cd = p.prod_grp_cd AND pi.colour_id = p.colour_id AND pi.country_id = pbv.platform_country_id AND pi.status = 1", "INNER");
        $this->db->join("v_prod_w_platform_biz_var AS v_ppbv", "v_ppbv.sku = p.sku AND v_ppbv.platform_id = pr.platform_id", "INNER");
        $this->db->where(array("p.status" => 2, "p.website_status" => "I", "p.website_quantity > 0" => null, "pi.ean is not NULL" => null, "p.cat_id not in (5,8,53)" => null));
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, "p.sku, map.ext_sku, replace(replace(pc.detail_desc, '\r\n', ''), '\n', '') detail_desc, IFNULL(pc.prod_name, p.name) prod_name, CONCAT('http://www.valuebasket.fr/fr_FR/mainproduct/view/', p.sku, '?AF=SHFR') prod_url, CONCAT('http://www.valuebasket.fr/images/product/' , p.sku, '.', p.image) image_url, pr.price, pr.fixed_rrp, pr.rrp_factor, pi.ean, CONCAT(cat.name, ' ', sc.name) category, pi.mpn, brand.brand_name, 1 as availability, 0 as delivery_cost, 'sous 7 à 10 jours ouvrables' as delivery_time, 'Garantie 12 mois' as warranty, 0 as `condition`, v_ppbv.platform_id, v_ppbv.prod_weight, v_ppbv.free_delivery_limit, v_ppbv.delivery_charge, v_ppbv.platform_country_id, v_ppbv.declared_pcent, v_ppbv.platform_commission, v_ppbv.duty_pcent, v_ppbv.payment_charge_percent, v_ppbv.forex_fee_percent, v_ppbv.vat_percent, v_ppbv.supplier_cost, v_ppbv.listing_fee, v_ppbv.sub_cat_margin, v_ppbv.admin_fee");
    }

    public function get_standard_fr_product_feed_dto($where = array(), $option = array(), $classname = "Standard_fr_product_feed_dto")
    {
        $this->db->from("product AS p");
        $this->db->join("sku_mapping AS map", "map.sku = p.sku AND map.ext_sys = 'WMS' AND map.status = 1");
        $this->db->join("price AS pr", "pr.sku = p.sku AND pr.platform_id = 'WEBFR' AND pr.listing_status = 'L'", "INNER");
        $this->db->join("platform_biz_var AS pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
        $this->db->join("product_content AS pc", "pc.prod_sku = p.sku AND pc.lang_id = pbv.language_id", "LEFT");
        $this->db->join("brand AS brand", "brand.id = p.brand_id", "INNER");
        $this->db->join("(SELECT cat.id, COALESCE(catex.name, cat.name) name FROM `category` cat LEFT JOIN `category_extend` catex ON cat.id = catex.cat_id AND catex.lang_id = 'fr') AS cat", "cat.id = p.cat_id", "INNER");
        $this->db->join("(SELECT cat.id, COALESCE(catex.name, cat.name) name FROM `category` cat LEFT JOIN `category_extend` catex ON cat.id = catex.cat_id AND catex.lang_id = 'fr') AS sc", "sc.id = p.sub_cat_id", "INNER");
        $this->db->join("supplier_prod AS sp", "sp.prod_sku = p.sku AND order_default = 1 AND supplier_status = 'A'", "INNER");
        $this->db->join("product_identifier AS pi", "pi.prod_grp_cd = p.prod_grp_cd AND pi.colour_id = p.colour_id AND pi.country_id = pbv.platform_country_id AND pi.status = 1", "INNER");
        $this->db->join("v_prod_w_platform_biz_var AS v_ppbv", "v_ppbv.sku = p.sku AND v_ppbv.platform_id = pr.platform_id", "INNER");
        $this->db->where(array("p.status" => 2, "p.website_status" => "I", "p.website_quantity > 0" => null, "pi.ean is not NULL" => null, "p.cat_id not in (5,8,53)" => null));
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, "p.sku, map.ext_sku, replace(replace(pc.detail_desc, '\r\n', ''), '\n', '') detail_desc, IFNULL(pc.prod_name, p.name) prod_name, CONCAT('http://www.valuebasket.fr/fr_FR/mainproduct/view/', p.sku) prod_url, CONCAT('http://www.valuebasket.fr/images/product/' , p.sku, '.', p.image) image_url, pr.price, pr.fixed_rrp, pr.rrp_factor, pi.ean, CONCAT(cat.name, ' ', sc.name) category, pi.mpn, brand.brand_name, 1 as availability, 0 as delivery_cost, 'sous 7 ?10 jours ouvrables' as delivery_time, '' as warranty, 0 as `condition`, v_ppbv.platform_id, v_ppbv.prod_weight, v_ppbv.free_delivery_limit, v_ppbv.delivery_charge, v_ppbv.platform_country_id, v_ppbv.declared_pcent, v_ppbv.platform_commission, v_ppbv.duty_pcent, v_ppbv.payment_charge_percent, v_ppbv.forex_fee_percent, v_ppbv.vat_percent, v_ppbv.supplier_cost, v_ppbv.listing_fee, v_ppbv.sub_cat_margin, v_ppbv.admin_fee");
    }

    public function get_fnac_additem_info($where = array(), $option = array(), $classname = "Fnac_add_item_dto")
    {
        $platform_id = "FNACES";
        if (isset($where["platform_id"])) {
            $platform_id = $where["platform_id"];
            unset($where["platform_id"]);   # unset because we don't want this in actual where clause
        }
        $this->db->from("product AS p");
        $this->db->join("price AS pr", "pr.sku = p.sku AND pr.platform_id = '$platform_id'", "INNER");
        $this->db->join("price_extend AS prex", "p.sku = prex.sku AND pr.platform_id = prex.platform_id", "LEFT");
        $this->db->join("platform_biz_var AS pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
        $this->db->join("product_content pc", "pc.prod_sku = p.sku AND pc.lang_id = pbv.language_id", "LEFT");
        $this->db->join("product_identifier pi", "pi.prod_grp_cd = p.prod_grp_cd AND pi.colour_id = p.colour_id AND pi.country_id = pbv.platform_country_id AND pi.status = 1", "LEFT");
        $this->include_dto($classname);

        #sbf #5109 - use custom EAN (price_extend.ext_ref_3)
        return $this->common_get_list($where, $option, $classname, "p.sku, prex.ext_item_id, prex.ext_ref_3 AS ean, pr.price, prex.ext_qty, SUBSTRING(prex.note , 1  , 250) note, pr.listing_status");
    }

    public function get_fnac_item_list($platform_id = "FNACES", $where = array(), $option = array(), $classname = "Fnac_add_item_dto")
    {
        $this->db->from("product AS p");
        $this->db->join("price AS pr", "pr.sku = p.sku AND pr.platform_id = '$platform_id'", "INNER");
        $this->db->join("price_extend AS prex", "p.sku = prex.sku AND pr.platform_id = prex.platform_id", "LEFT");
        $this->db->join("platform_biz_var AS pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
        $this->db->join("product_content pc", "pc.prod_sku = p.sku AND pc.lang_id = pbv.language_id", "LEFT");
        $this->db->join("product_identifier pi", "pi.prod_grp_cd = p.prod_grp_cd AND pi.colour_id = p.colour_id AND pi.country_id = pbv.platform_country_id AND pi.status = 1", "LEFT");
        $this->include_dto($classname);

        #sbf #5109 - use custom EAN (price_extend.ext_ref_3)
        return $this->common_get_list($where, $option, $classname, "p.sku, prex.ext_item_id, prex.ext_ref_3 AS ean, pr.price, prex.ext_qty, SUBSTRING(prex.note , 1  , 250) note, pr.listing_status");
    }

    public function get_sub_cat_margin($where = array())
    {
        $sql = "SELECT scpv.profit_margin
                FROM product p
                INNER JOIN sub_cat_platform_var scpv
                ON scpv.sub_cat_id = p.sub_cat_id
                WHERE p.sku = ? AND scpv.platform_id = ?
                LIMIT 1";

        if ($query = $this->db->query($sql, array($where["sku"], $where["platform_id"]))) {
            foreach ($query->result() as $row) {
                $res = $row->profit_margin;
            }
            return $res;
        }
        return FALSE;
    }

    public function get_ebay_auction_info($where, $option, $classname = "Ebay_auction_dto")
    {
        $this->db->from("price AS pr");
        $this->db->join("price_extend AS prex", "pr.sku = prex.sku AND pr.platform_id = prex.platform_id", "INNER");
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, "pr.platform_id, pr.price, prex.title, prex.ext_ref_1, prex.ext_ref_2, prex.ext_ref_3, prex.ext_ref_4");
    }

    public function is_clearance($sku = "")
    {
        $sql = "SELECT clearance
                        FROM product p
                        WHERE p.sku = '$sku'";

        if ($query = $this->db->query($sql)) {
            return $query->row()->clearance;
        }
    }

    public function get_unmapped_sku()
    {
        $sql = <<<sql
            select
                p.sku, s.ext_sku
            from product p
            left join sku_mapping s on s.sku = p.sku and s.`status` = 1 and s.ext_sys = "WMS"
            where 1
            and s.ext_sku is null
            and p.status != 0
sql;

        if ($query = $this->db->query($sql)) {
            $list = null;
            foreach ($query->result() as $row) {
                $item["sku"] = $row->sku;
                $item["ext_sku"] = $row->ext_sku;

                $list[] = $item;
            }
            // var_dump($list);
            return $list;
        }
        return null;
    }

    public function map_sku($sku, $ext_sku)
    {
        $id = empty($_SESSION["user"]["id"]) ? "system" : $_SESSION["user"]["id"];

        $sql = <<<sql
            insert into sku_mapping
            set
            sku         = ?,
            ext_sku     = ?,
            modify_by   = ?,

            status      = 1,
            ext_sys     = 'WMS',
            create_by   = 'csv_upload',
            create_at   = 'localhost',
            modify_at   = 'localhost',
            create_on   = now()
sql;

        $this->db->query($sql, array($sku, $ext_sku, $id));
        $affected = $this->db->affected_rows();

        $query = "commit";
        $this->db->query($query);

        return $affected;
    }

    public function set_surplus_quantity($sku, $qty, $slow_move = "NA")
    {
        $this->db->trans_start();
        $sql =
            "
            update `product` p
            inner join `sku_mapping` m on m.status = 1 and m.ext_sys = 'wms' and m.sku = p.sku
                set surplus_quantity = ?, slow_move_7_days = ?
            where 1
            and m.ext_sku = ?;
        ";
        $query = $this->db->query($sql, array($qty, (string)$slow_move, (string)$sku));

        // $sql = "commit";
        // $query = $this->db->query($sql);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        }
        return TRUE;
    }

    public function reset_surplus_quantity()
    {
        $this->db->trans_start();
        $sql =
            "
            update `product` p
            set surplus_quantity = 0
        ";
        $query = $this->db->query($sql);

        // $sql = "commit";
        // $query = $this->db->query($sql);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        }
        return TRUE;
    }

    public function reset_slow_move()
    {
        $this->db->trans_start();
        $sql =
            "
            update `product` p
            set slow_move_7_days = ''
        ";
        $query = $this->db->query($sql);

        // $sql = "commit";
        // $query = $this->db->query($sql);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        }
        return TRUE;
    }

    public function get_product_category_list($where, $option, $classname = "Product_category_dto")
    {
        $this->db->from("product AS p");
        $this->db->join("brand AS b", "p.brand_id = b.id", "LEFT");
        $this->db->join("category AS c", "p.cat_id = c.id", "LEFT");
        $this->db->join("category AS sc", "p.sub_cat_id = sc.id", "LEFT");
        $this->db->join("category AS ssc", "p.sub_sub_cat_id = ssc.id", "LEFT");
        $this->db->join("sku_mapping AS m", "p.sku = m.sku and m.ext_sys = 'WMS' and m.status = 1", "LEFT");

        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, "m.ext_sku, p.name, b.brand_name, c.name as cat_name, sc.name as sub_cat_name, ssc.name as sub_sub_cat_name");
    }

    public function get_prod_from_master_sku($master_sku)
    {
        $this->db->from("product AS p");
        $this->db->join("sku_mapping AS sm", "sm.sku = p.sku", "inner");

        $classname = "Product_vo";
        $this->include_vo($classname);
        $where['sm.ext_sku'] = $master_sku;
        $option['limit'] = -1;

        return $this->common_get_list($where, $option, $classname, "p.*");
    }
}
/* End of file product_dao.php */
/* Location: ./system/application/libraries/dao/Product_dao.php */
