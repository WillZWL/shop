<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_trend_report extends MY_Controller
{
    // INSERT INTO `application` (`id`, `app_name`, `description`, `display_order`, `status`, `display_row`, `url`, `app_group_id`) VALUES ('RPT0046', 'Sales Trend Report', 'Sales Trend Report', '94', '1', '1', 'report/sales_trend_report', '10')
    // INSERT INTO `rights` (`app_id`, `status`) VALUES ('RPT0046', '1')

    // INSERT INTO `role_rights` (`role_id`, `rights_id`) VALUES ('mkt_lead', '?')
    // INSERT INTO `role_rights` (`role_id`, `rights_id`) VALUES ('mkt_staff', '?')
    // INSERT INTO `role_rights` (`role_id`, `rights_id`) VALUES ('mkt_man', '?')
    // INSERT INTO `role_rights` (`role_id`, `rights_id`) VALUES ('mkt_lead', '186')
    // INSERT INTO `role_rights` (`role_id`, `rights_id`) VALUES ('mkt_staff', '186')
    // INSERT INTO `role_rights` (`role_id`, `rights_id`) VALUES ('mkt_man', '186')

    protected $app_id = "RPT0046";
    private $lang_id = "en";
    private $model;
    private $export_filename;

    private $gridcontent = "";
    private $s;

    public function __construct()
    {
        parent::__construct();
        // $CI = & get_instance();
        // $CI->db->db_debug = true;
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }

    public function _set_app_id($value)
    {
        $this->app_id = $value;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function index()
    {
        $CI = &get_instance();
        $CI->load->view('report/sales_trend_report.php');
    }

    public function get_sales()
    {
        $CI = &get_instance();
        extract($this->_parse_input_into_sql());

        $biz_type_list = $this->get_distinct_biz_type();

        if ($use_old) {
            // Old query v1 - $where AND $on go into the WHERE clause
            $sql = "SELECT
                    sm.ext_sku,
                    si.prod_sku,
                    pp.name,
                    pp.clearance,
                    ss.name AS supplier_name,
                    pp.sourcing_status,
                    wi.inventory,
                    pp.surplus_quantity,
                    SUM(IF(so.platform_id IS NOT NULL, si.qty, 0)) AS count,
                    SUM(CASE WHEN (SUBSTR(so.platform_id, 1, 3) = 'WEB') THEN IFNULL(si.qty, 0) END) AS web,
                    SUM(CASE WHEN (SUBSTR(so.platform_id, 1, 3) != 'WEB') THEN IFNULL(si.qty, 0) END) AS nonweb
                FROM so
                INNER JOIN so_item si ON        si.so_no = so.so_no
                INNER JOIN sku_mapping sm ON    sm.sku = si.prod_sku AND sm.status = 1 AND sm.ext_sys = 'wms'
                INNER JOIN product pp ON        pp.sku = si.prod_sku
                INNER JOIN supplier_prod sp ON  sp.prod_sku = si.prod_sku AND order_default = 1
                INNER JOIN supplier ss ON       ss.id = sp.supplier_id
                LEFT JOIN wms_inventory wi ON   wi.master_sku = si.prod_sku
                WHERE so.biz_type IN ($biz_type_list) AND so.status > 2
                $on
                $where
                GROUP BY prod_sku";

            $count = "SELECT count(*) AS count FROM ( $sql ) count";
        } else {
            // query v2
            $sql = "SELECT
                        sm.ext_sku,
                        sm.sku AS prod_sku,
                        pp.name,
                        pp.clearance,
                        ss.name supplier_name,
                        pp.sourcing_status,
                        wi.inventory,
                        pp.surplus_quantity,
                        SUM(IF(so.platform_id IS NOT NULL, si.qty, 0)) AS count,
                        SUM(CASE WHEN (SUBSTR(so.platform_id, 1, 3) = 'WEB') THEN IFNULL(si.qty, 0) END) AS web,
                        SUM(CASE WHEN (SUBSTR(so.platform_id, 1, 3) != 'WEB') THEN IFNULL(si.qty, 0) END) AS nonweb
                    FROM sku_mapping sm
                    INNER JOIN product pp ON        pp.sku = sm.sku
                    INNER JOIN supplier_prod sp ON  sp.prod_sku = sm.sku AND order_default = 1
                    INNER JOIN supplier ss ON       ss.id = sp.supplier_id
                    LEFT JOIN wms_inventory wi ON   wi.master_sku = sm.sku
                    LEFT JOIN so_item si ON         sm.sku = si.prod_sku AND sm.status = 1 AND sm.ext_sys = 'wms'
                    LEFT JOIN so ON                 si.so_no = so.so_no
                    AND so.biz_type IN ($biz_type_list) AND so.status > 2 $on
                    WHERE 1 $where
                    GROUP BY sm.sku";

            // begin pagination query for query v2
            $count = "SELECT count(*) AS count
            FROM (
                SELECT 1
                FROM sku_mapping sm
                INNER JOIN price px ON          px.sku = sm.sku
                INNER JOIN product pp ON        pp.sku = sm.sku
                INNER JOIN supplier_prod sp ON  sp.prod_sku = sm.sku AND order_default = 1
                INNER JOIN supplier ss ON       ss.id = sp.supplier_id
                WHERE 1 $where
                GROUP BY sm.sku
            ) aa";
        }

        $start = $CI->input->get('start');
        $length = $CI->input->get('length');

        // add the limit
        $sql .= " LIMIT $start, $length";

        // do pagination query
        $count = $CI->db->query($count)->row()->count;

        // do full query
        $results = $CI->db->query($sql)->result_array();

        echo json_encode(array(
            'draw' => $CI->input->get('draw'),
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $results,
        ));
    }

    public function _parse_input_into_sql()
    {
        $CI = &get_instance();
        // http://stackoverflow.com/a/3997367/1097483
        // http://stackoverflow.com/a/19347006/1097483
        $ext_sku = array_map('trim', preg_split('/\r\n|\r|\n/', $CI->input->get('ext_sku'), -1, PREG_SPLIT_NO_EMPTY));
        $prod_sku = array_map('trim', preg_split('/\r\n|\r|\n/', $CI->input->get('prod_sku'), -1, PREG_SPLIT_NO_EMPTY));
        $product_name = $CI->input->get('product_name');
        $clearance = $CI->input->get('clearance');

        $order_date_from = $CI->input->get('order_date_from');
        $order_date_to = $CI->input->get('order_date_to');

        // Validation
        if (!$order_date_from || !$order_date_to) {
            echo json_encode(array(
                'draw' => $CI->input->get('draw'),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => array(),
            ));

            die;
        }


        $where = "";
        $on = " AND so.order_create_date >= '$order_date_from' AND so.order_create_date <= '$order_date_to'";

        if ($clearance === 'yes') {
            $on .= " AND pp.clearance = 1";
        }

        $use_old_query = false;

        if (is_array($ext_sku) && count($ext_sku) > 0) {
            $where .= " AND sm.ext_sku IN ('" . implode("','", $ext_sku) . "')";
        } elseif (is_array($prod_sku) && count($prod_sku) > 0) {
            $where .= " AND sm.sku IN ('" . implode("','", $prod_sku) . "')";
        } elseif ($product_name) {
            $where .= " AND pp.name LIKE '%$product_name%'";
        } else {
            $use_old_query = true;
        }

        return array(
            'use_old' => $use_old_query,
            'where' => $where,
            'on' => $on,
        );
    }

    public function get_distinct_biz_type($exclude_biz_type = array(), $default = true)
    {
        # With this function, don't have to keep adding manually whenever we have new marketplaces
        $CI = &get_instance();

        $add_where = "";
        if ($default)
            $exclude_biz_type[] = "SPECIAL";

        if (is_array($exclude_biz_type))
            $add_where .= "AND so.biz_type NOT IN ('" . implode("','", $exclude_biz_type) . "')";

        $sql = "SELECT DISTINCT(biz_type) FROM so WHERE so.status > 0 $add_where";
        $results = $CI->db->query($sql)->result_array();
        if ($results) {
            foreach ($results as $key => $value) {
                $biz_type_arr[] = "'{$value["biz_type"]}'";
            }
        }

        # if nothing, go back to original
        if (!($biz_type_list = implode(',', $biz_type_arr))) {
            $biz_type_arr = array('ONLINE', 'OFFLINE', 'MOBILE', 'QOO10', 'RAKUTEN', 'EBAY', 'MANUAL');
            if (is_array($exclude_biz_type)) {
                foreach ($exclude_biz_type as $value) {
                    if ($exclude_key = array_search(strtoupper($value), $biz_type_arr)) {
                        unset($biz_type_arr[$exclude_key]);
                    }
                }
            }

            foreach ($biz_type_arr as $key => $value) {
                $final_biz_type[] = "'$value'";
            }

            $biz_type_list = implode(',', $final_biz_type);
        }

        return $biz_type_list;
    }

    public function get_platforms()
    {
        $CI = &get_instance();
        extract($this->_parse_input_into_sql());
        $biz_type_list = $this->get_distinct_biz_type();
        $biz_type_list_online = $this->get_distinct_biz_type(array("OFFLINE"));

        // sbf #3762
        // needs a huge nested subquery for competitors to get the lowest price AND not mess up the so count
        // - otherwise it will mess with the GROUP BY clause unintentionally (no. of competitors x qty = inflated so count)
        $sql = "SELECT
                pbv.selling_platform_id AS platform_id,
                CONCAT(cm.competitor_name, ' ', ifnull(cm.note_1,'')) Competitor,
                cm.comp_ship_charge _shipping_cost,
                pbv.platform_currency_id,
                cm.now_price CompetitorPrice,
                px.price OurPrice,
                px.listing_status,
                (cm.now_price - px.price) Difference,
                SUM(if(so.biz_type IN ($biz_type_list_online), IFNULL(qty,0), 0)) OnlineOrders,
                SUM(if(so.biz_type = 'OFFLINE', IFNULL(qty,0), 0)) OfflineOrders
        FROM platform_biz_var pbv

        LEFT JOIN sku_mapping sm    ON sm.status = 1 AND sm.ext_sys = 'wms' $where
        LEFT JOIN price px          ON px.sku = sm.sku AND px.platform_id = pbv.selling_platform_id
        LEFT JOIN product pp        ON pp.sku = sm.sku

        LEFT JOIN so_item si        ON si.prod_sku = sm.sku
        LEFT JOIN so                ON pbv.selling_platform_id = so.platform_id AND si.so_no = so.so_no
        AND so.status > 2
        AND so.biz_type IN ($biz_type_list) $on

        LEFT JOIN (
            SELECT cm.comp_ship_charge, cm.note_1, cm.now_price, sm.sku, c.country_id, c.competitor_name FROM

            competitor_map cm
            INNER JOIN competitor c ON cm.competitor_id = c.id AND cm.status = 1 AND cm.match = 1
            INNER JOIN (
                SELECT
                cm.ext_sku,
                cm.competitor_id,
                MIN(cm.now_price) price,
                c.*
                FROM
                competitor_map cm
                INNER JOIN competitor c ON cm.competitor_id = c.id
                INNER JOIN sku_mapping sm ON sm.ext_sku = cm.ext_sku AND cm.status = 1 AND cm.match = 1 $where
                GROUP BY
                c.country_id,
                cm.ext_sku
            ) test ON test.price = cm.now_price AND test.country_id = c.country_id
            INNER JOIN sku_mapping sm ON sm.ext_sku = cm.ext_sku $where
            GROUP BY country_id
        ) cm ON cm.country_id = pbv.platform_country_id

        GROUP BY pbv.selling_platform_id
        ORDER BY pbv.selling_platform_id";

        $results = $CI->db->query($sql)->result_array();

        // get total count for pagination
        $total_count = count($this->get_platform_ids_from_db());

        // get supplier cost/margin
        $prod_sku = $CI->input->get('prod_sku');
        $supplier_cost = $CI->input->get('supplier_cost');
        $supplier = $this->parse_currency_string($supplier_cost);
        $results = $this->price_compare_from_db($prod_sku, $results, $supplier['value'], $supplier['currency']);

        echo json_encode(array(
            'draw' => $CI->input->get('draw'),
            'recordsTotal' => $total_count,
            'recordsFiltered' => $total_count,
            'data' => $results,
        ));
    }

    private function get_platform_ids_from_db()
    {
        $CI = &get_instance();

        $sql = "SELECT selling_platform_id FROM platform_biz_var";
        $platforms = $CI->db->query($sql)->result_array();

        $platform_ids = array();
        foreach ($platforms as $platform) {
            array_push($platform_ids, $platform['platform_id']);
        }

        return $platform_ids;
    }

    // Unused

    private function parse_currency_string($string)
    {
        $t1 = floatval($string);
        $t2 = floatval(substr($string, 3));

        if ($t1) {
            return array(
                'currency' => 'HKD',
                'value' => $t1,
            );
        } elseif ($t2) {
            return array(
                'currency' => substr(strtoupper($string), 0, 3),
                'value' => $t2,
            );
        } else {
            return array(
                'currency' => 'HKD',
                'value' => -1,
            );
        }
    }

    private function price_compare_from_db($prod_sku, $compared_prices, $supplier_cost = -1, $supplier_cost_currency = "HKD")
    {
        // http://admincentre.valuebasket.com/marketing/pricing_tool_website/get_profit_margin_json/WEBGB/11774-AA-BK/189.08/120
        include_once APPPATH . "libraries/service/price_website_service.php";
        include_once APPPATH . "libraries/service/exchange_rate_service.php";

        $price_checker = new price_website_service();

        foreach ($compared_prices as &$compared_price) {
            $platform_id = $compared_price['platform_id'];
            $own_price = ($compared_price['OurPrice']) ? $compared_price['OurPrice'] : -1;
            $tailored_supplier_cost = -1;

            if ($supplier_cost > 0) {
                $e = new exchange_rate_service();
                $rate = $e->get_exchange_rate($supplier_cost_currency, $compared_price['platform_currency_id']);
                $tailored_supplier_cost = $supplier_cost * $rate->get_rate();
            }

            $json = $price_checker->get_profit_margin_json($platform_id, $prod_sku, $own_price, $tailored_supplier_cost);

            $d = json_decode($json, true);
            $compared_price["_margin"] = $d["get_margin"];
            // $compared_price["_shipping_cost"] = $d["get_logistic_cost"];
            $compared_price["_supplier_cost"] = $d["get_supplier_cost"];
        }

        return $compared_prices;
    }

    // Unused

    public function get_platform_ids()
    {
        $platform_ids = $this->get_platform_ids_from_db();
        echo json_encode($platform_ids);
    }

    public function price_compare()
    {
        $CI = &get_instance();

        $prod_sku = $CI->input->get('prod_sku');
        $compared_prices = $CI->input->get('compared_prices');
        $supplier_cost_currency = $CI->input->get('supplier_cost_currency');
        $supplier_cost = $CI->input->get('supplier_cost');

        $results = $this->price_compare_from_db($prod_sku, $compared_prices, $supplier_cost, $supplier_cost_currency);

        echo json_encode($results);
    }
}