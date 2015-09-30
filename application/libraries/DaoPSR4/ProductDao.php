<?php
namespace ESG\Panther\Dao;

class ProductDao extends BaseDao
{
    private $tableName = 'product';
    private $voClassName = 'ProductVo';

    public function getVoClassname()
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

    public function getProductOverview($where = [], $option = [], $class_name = "ProductOverviewDto")
    {
        $option = ['limit' => 1];
        $this->db->from('v_prod_items AS vpi');
        $this->db->join('product AS p', 'vpi.prod_sku = p.sku', 'INNER');
        $this->db->join('v_prod_overview_wo_cost AS vpo', 'vpi.item_sku = vpo.sku', 'INNER');

        return $this->commonGetList($class_name, $where, $option, 'vpo.*, p.expected_delivery_date, p.warranty_in_month');
    }

    public function getListedProductList($platform_id = 'WEBGB', $classname = 'WebsiteProdInfoDto')
    {
        $sql = "SELECT * FROM v_prod_overview_wo_shiptype vpo
                WHERE vpo.platform_id = ?
                    AND vpo.listing_status = 'L'";

        $result = $this->db->query($sql, array('platform_id' => $platform_id));

        $result_arr = [];

        if ($result) {
            foreach ($result->result($classname) as $obj) {
                $result_arr[] = $obj;
            }
        }

        return $result_arr;
    }


    public function searchByProductName($where, $option, $classname = "ProductSearchListDto")
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

    public function getProductWPriceInfo($platform_id = 'WEBGB', $sku = "", $classname = 'WebsiteProdInfoDto')
    {

        $sql = "SELECT * FROM v_prod_overview_wo_shiptype vpo WHERE vpo.platform_id = ? AND sku = ?";
        $result = $this->db->query($sql, array($platform_id, $sku));

        $result_arr = [];

        if ($result->num_rows() > 0) {
            foreach ($result->result($classname) as $obj) {
                $result_arr[$obj->get_sku()] = $obj;
            }
        }

        return $result_arr;
    }


    public function getProductWMarginReqUpdate($where = [], $classname = 'WebsiteProdInfoDto')
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

        foreach ($result->result($classname) as $obj) {
            $result_arr[] = $obj;
        }

        return $result_arr;
    }

    public function getWebsiteCatPageProductList($where = [], $option = [], $classname = 'CatProductListDto')
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
                foreach ($query->result($this->getVoClassname()) as $obj) {
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

    public function getRaProductOverview($sku = "", $platform_id = "", $classname = "ProductCostDto")
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
            foreach ($query->result($classname) as $obj) {
                $rs[] = $obj;
            }
            return (object)$rs;
        } else {
            return FALSE;
        }
    }


    public function getComponentsWithName($where = [], $option = [], $classname = "ProductCostDto")
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
