<?php
namespace ESG\Panther\Dao;

class ProductDao extends BaseDao
{
    private $tableName = 'product';
    private $voClassName = 'ProductVo';

    public function getVoClassName()
    {
        return $this->voClassName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function __construct()
    {
        parent::__construct();
    }

    public function getCartDataDetail($where = [], $option = [], $className = "CartItemDto")
    {
        $this->db->from("product AS p");
        $this->db->join("product_content AS pc", "pc.prod_sku=p.sku", 'LEFT');
        $this->db->join("price AS pr", "p.sku=pr.sku", 'INNER');
        $this->db->join("platform_biz_var pbv", "pbv.selling_platform_id=pr.platform_id", 'INNER');
        $this->db->join("supplier_prod AS sp", "sp.prod_sku=p.sku and sp.order_default=1", 'LEFT');
        $this->db->join("exchange_rate er", "er.from_currency_id=sp.currency_id and er.to_currency_id=pbv.platform_currency_id", 'INNER');
        $this->db->join("freight_category fc", "p.freight_cat_id=fc.id", 'LEFT');

        $select = "pbv.dec_place as decPlace, fc.weight as unitWeight, pbv.vat_percent as vatPercent, pbv.admin_fee
                , ROUND((pbv.vat_percent * er.rate * pr.price), pbv.dec_place) as vatTotal
                , er.rate as supplierProdExRate, pbv.platform_currency_id as platformCurrency
                , p.sku, p.name, pc.prod_name as nameInLang
                , pr.price, pr.listing_status as listingStatus
                , p.website_status as websiteStatus, sp.currency_id
                , p.warranty_in_month as warrantyInMonth
                , ROUND((sp.cost * er.rate), pbv.dec_place) as unitCost
                , sp.cost as supplierUnitCost
                , sp.pricehkd as supplierUnitCostInHkd
                , sp.supplier_status as sourcingStatus";

        if (isset($option["productImage"]))
        {
            $this->db->join("product_image pi", "pi.sku=p.sku", 'LEFT');
            $select .= ", pi.alt_text as image";
        }
        return $this->commonGetList($className, $where, $option, $select);
    }

    public function getCartDataLite($where = [], $option = [], $className = "CartItemDto")
    {
//        $option = ["limit" => 1];
        $this->db->from("product AS p");
        $this->db->join("product_content AS pc", "pc.prod_sku=p.sku", 'LEFT');
        $this->db->join("price AS pr", "p.sku=pr.sku", 'INNER');
        $this->db->join("product_image pi", "pi.sku=p.sku", 'LEFT');

        $select = "p.sku, p.name, pc.prod_name as nameInLang, pr.price, pr.listing_status as listingStatus, p.website_status as websiteStatus, pi.alt_text as image";

        return $this->commonGetList($className, $where, $option, $select);
    }

    public function getNewSku()
    {
        return $this->db->query("SELECT next_value('sku') as sku")->row('sku');
    }

    public function getNewProductGroup()
    {
        return $this->db->query("SELECT next_value('prod_grp_cd') as prod_grp_cd")->row('prod_grp_cd');
    }

    public function getHomeProduct($where = [], $option = [], $className = 'SimpleProductDto')
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

        $obj = $this->commonGetList($className, [], $option, 'pd.sku');

		// print $this->db->last_query();
		return $obj;
    }

    public function getBundleComponentsOverview($where = [], $option = [], $className = "ProductCostDto")
    {
        $option = ['limit' => 1];
        $this->db->from('v_prod_items AS vpi');
        $this->db->join('product AS p', 'vpi.prod_sku = p.sku', 'INNER');
        $this->db->join('v_prod_overview_wo_cost AS vpo', 'vpi.item_sku = vpo.sku', 'INNER');

        return $this->commonGetList($className, $where, $option, 'vpo.*, p.expected_delivery_date, p.warranty_in_month');
    }

    public function getProductOverview($where = [], $option = [], $className = "ProductOverviewDto")
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
                foreach ($query->result($className) as $obj) {
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

    public function getListedProductList($platform_id = 'WEBGB', $className = 'WebsiteProdInfoDto')
    {
        $sql = "SELECT * FROM v_prod_overview_wo_shiptype vpo
                WHERE vpo.platform_id = ?
                    AND vpo.listing_status = 'L'";

        $result = $this->db->query($sql, array('platform_id' => $platform_id));

        $result_arr = [];

        if ($result) {
            foreach ($result->result($className) as $obj) {
                $result_arr[] = $obj;
            }
        }

        return $result_arr;
    }


    public function searchByProductName($where, $option, $className = "ProductSearchListDto")
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
                    ON (pc.prod_sku = p.sku AND p.status = 2 AND pc.lang_id = '" . $where['lang_id'] . "')
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
        if ($option['split_keyword']) {
            $uf_arr = $where['skey']['unformated'];
            foreach ($uf_arr AS $key) {
                $reg_arr[] = "pc.prod_name REGEXP '" . $key . "'";
            }
            $reg_script = implode(" OR ", $reg_arr);

            $sql .= "WHERE ({$reg_script})";
        } else {
            $sql .= "WHERE (pc.prod_name like '%" . $where['keyword'] . "%')";
        }

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

            $rs = [];

            if ($query = $this->db->query($sql)) {
                if ($this->debug == 1) {
                    echo "<br>First Level Search<br>";
                    echo $this->db->last_query();
                    echo "<br>";
                }
                foreach ($query->result($className) as $obj) {
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

    public function getProductWPriceInfo($platform_id = 'WEBGB', $sku = "", $className = 'WebsiteProdInfoDto')
    {

        $sql = "SELECT * FROM v_prod_overview_wo_shiptype vpo WHERE vpo.platform_id = ? AND sku = ?";
        $result = $this->db->query($sql, array($platform_id, $sku));

        $result_arr = [];

        if ($result->num_rows() > 0) {
            foreach ($result->result($className) as $obj) {
                $result_arr[$obj->get_sku()] = $obj;
            }
        }

        return $result_arr;
    }


    public function getProductWMarginReqUpdate($where = [], $className = 'WebsiteProdInfoDto')
    {
        $table_alias = [
                'v_prod_overview_w_update_time' => 'vpo',
                'supplier_cost_history' => 'sch', 'price_margin' => 'pm'
            ];
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

        $value_list = [];

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

        $result_arr = [];

        foreach ($result->result($className) as $obj) {
            $result_arr[] = $obj;
        }

        return $result_arr;
    }

    public function getWebsiteCatPageProductList($where = [], $option = [], $className = 'CatProductListDto')
    {
        $this->db->from('product AS p');
        $this->db->join('price AS pr', 'p.sku = pr.sku AND pr.listing_status = "L" AND p.status = "2"', 'INNER');
        $this->db->join('category AS cat', 'cat.id = p.cat_id AND cat.status = 1', 'INNER');
        $this->db->join('category AS sc', 'sc.id = p.sub_cat_id AND sc.status = 1', 'INNER');
        $this->db->join('category AS ssc', 'ssc.id = p.sub_sub_cat_id AND ssc.status = 1', 'LEFT');
        $this->db->join('brand AS br', 'br.id = p.brand_id AND br.status = 1', 'INNER');
        $this->db->where($where);

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

            $this->db->select("*, if(p.website_status = 'O','1','0') is_oos, if(p.website_status = 'A','1','0') is_arr");

            if ($query = $this->db->get()) {
                foreach ($query->result($this->getVoClassName()) as $obj) {
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

    public function getSearchspringProductFeed($where = [], $option = [], $className = "SearchspringProductFeedProductPriceDto")
    {
        $this->db->from("product p");
        $this->db->join("price pr", "p.sku = pr.sku", "INNER");
        $this->db->join("platform_biz_var pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
        $this->db->join("selling_platform sp", "pbv.selling_platform_id = sp.selling_platform_id AND sp.type = 'WEBSITE'", "INNER");
        $this->db->join("category_extend cat", "p.cat_id = cat.cat_id and pbv.language_id = cat.lang_id", "LEFT");
        $this->db->join("category_extend sc", "p.sub_cat_id = sc.cat_id and pbv.language_id = sc.lang_id", "LEFT");
        $this->db->join("brand br", "p.brand_id = br.id", "INNER");
        $this->db->join("product_content pc", "p.sku = pc.prod_sku AND pc.lang_id = pbv.language_id", "LEFT");
        $this->db->join("product_content default_pc", "p.sku = default_pc.prod_sku AND default_pc.lang_id = 'en'", "LEFT");
        $this->db->where(array("p.status" => 2, "pr.listing_status" => "L"));

        $prod_url = 'CONCAT(REPLACE(REPLACE(cat.name, ".", "-"), " ", "-"), "/", REPLACE(REPLACE(sc.name, ".", "-"), " ", "-"), "/", REPLACE(REPLACE(p.name, ".", "-"), " ", "-") ,"/product/", p.sku) product_url';

        return $this->commonGetList($className, $where, $option, 'pr.platform_id, pbv.platform_country_id country_id, p.sku, ' . $prod_url.  ', pbv.platform_currency_id currency_id, pr.price, pr.fixed_rrp, pr.rrp_factor, p.website_status, if(p.display_quantity < p.website_quantity, p.display_quantity, p.website_quantity) quantity, coalesce(pc.prod_name, default_pc.prod_name) prod_name, coalesce(pc.short_desc, default_pc.short_desc) short_desc, coalesce(pc.detail_desc, default_pc.detail_desc) detail_desc, p.image, CONCAT("/cart/add-item/", p.sku) add_cart_url, cat.name cat_name, sc.name sub_cat_name, br.brand_name, p.mpn, p.ean, p.upc, p.clearance, p.create_on create_date');
    }
/**********************************
**  searchspring ajax Price
**  getSearchspringProductFeedPriceInfo
***********************************/
    public function getSearchspringProductFeedPriceInfo($where = array(), $option = array(), $className = "SearchspringProductFeedPriceInfoDto")
    {
        $this->db->from("product p");
        $this->db->join("price pr", "p.sku = pr.sku", "INNER");
        $this->db->join("platform_biz_var pbv", "pbv.selling_platform_id = pr.platform_id", "INNER");
        $this->db->join("selling_platform sp", "pbv.selling_platform_id = sp.selling_platform_id AND sp.type = 'WEBSITE'", "INNER");
        //$this->db->join("category_extend cat", "p.cat_id = cat.cat_id and pbv.language_id = cat.lang_id", "LEFT");
        //$this->db->join("category_extend sc", "p.sub_cat_id = sc.cat_id and pbv.language_id = sc.lang_id", "LEFT");
        //$this->db->join("brand br", "p.brand_id = br.id", "INNER");
        //$this->db->join("product_content pc", "p.sku = pc.prod_sku AND pc.lang_id = pbv.language_id", "LEFT");
        $this->db->where(array("p.status" => 2, "pr.listing_status" => "L"));
        $this->db->where($where);

        return $this->commonGetList($className, $where, $option, 'pr.platform_id, pbv.platform_country_id country_id, p.sku, pbv.platform_currency_id currency_id, pr.price, pr.fixed_rrp, pr.rrp_factor, p.website_status, if(p.display_quantity < p.website_quantity, p.display_quantity, p.website_quantity) quantity');
    }

    public function getRaProductOverview($sku = "", $platform_id = "", $className = "ProductCostDto")
    {
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

        $rs = [];
        if ($query = $this->db->query($sql, [$sku, $sku, $platform_id])) {
            foreach ($query->result($className) as $obj) {
                $rs[] = $obj;
            }
            return (object)$rs;
        } else {
            return FALSE;
        }
    }

    public function getComponentsWithName($where = [], $option = [], $className = "ProductCostDto")
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

        $this->db->select('p.*');

        if ($query = $this->db->get()) {
            $rs = [];
            foreach ($query->result($className) as $obj) {
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

    public function getProductWithPrice($sku,$site='WSGB',$classname='ProductPriceDto')
    {
        $where = $option = [];
        $where['p.sku'] = $sku;
        $where['pr.platform_id'] = $site;
        $option = 1;

        $this->db->from("product AS p");
        $this->db->join("bundle AS b", "b.prod_sku=p.sku", 'LEFT');
        $this->db->join("product AS pd", "b.component_sku=pd.sku", 'LEFT');
        $this->db->join("price pr", "coalesce(pd.sku, p.sku)=pr.sku", 'LEFT');
        $this->db->group_by('p.sku, pr.platform_id');
        $select = "p.sku AS sku,
                   p.name AS name,
                   p.image AS image,
                   p.brand_id AS brand_id,
                   p.cat_id AS cat_id,
                   p.sub_cat_id AS sub_cat_id,
                   p.sub_sub_cat_id AS sub_sub_cat_id,
                   p.website_status AS website_status,
                   p.status AS status,
                   p.website_quantity AS website_quantity,
                   sum(round(((pr.price * (100 - coalesce(p.discount,0))) / 100),2)) AS price
                   ";

        return $this->commonGetList($className, $where, $option, $select);
    }

    public function isClearance($sku = "")
    {
        $sql = "SELECT clearance FROM product p WHERE p.sku = '$sku'";

        if ($query = $this->db->query($sql)) {
            return $query->row()->clearance;
        }
    }


    // TODO
    // will remove
    public function isTrialSoftware($sku = "")
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
    public function isSoftware($sku = "")
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
    public function getProductTypeWithSku($sku = "")
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
}
