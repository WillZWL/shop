<?php
DEFINE("PLATFORM_TYPE", "WEBSITE");

class pricing_tools extends MY_Controller
{
    public $tool_path;
    public $default_platform_id;

    private $appId = 'MKT0043';
    private $lang_id = 'en';

    public function __construct()
    {
        parent::__construct();
        $this->tool_path = "marketing/pricing_tools";
        $this->default_platform_id = $this->sc['ContextConfig']->valueOf("default_platform_id");
    }

    public function index()
    {
        $data = [];
        include_once APPPATH . "language/" . $this->getAppId() . "00_" . $this->getLangId() . ".php";
        $data["lang"] = $lang;
        // $data["platform_type"] = ['EBAY', 'FNAC', 'QOO10', 'RAKUTEN', 'WEBSITE'];
        $data["platform_type"] = ['EBAY', 'WEBSITE'];

        $this->load->view($this->tool_path . "/pricing_tool_index", $data);
    }

    public function plist($offset = 0)
    {
        $where = $option = [];
        $sub_app_id = $this->getAppId() . "02";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;
        $sku = $this->input->get("sku");
        $prod_name = $this->input->get("name");
        $master_sku = $this->input->get("master_sku");
        $platform_type = $this->input->get("platform_type");

        if ($sku != "" || $prod_name != "" || $master_sku != "") {
            $data["search"] = 1;
            if ($sku != "") {
                $where["sku"] = $sku;
            }

            if ($master_sku != "") {
                $where['master_sku'] = $master_sku;
            }

            if ($prod_name != "") {
                $where["name"] = $prod_name;
            }

            $option['limit'] = ($this->input->get('limit') != '') ? $this->input->get('limit') : '20';
            $option['offset'] = ($this->input->get('per_page') != '') ? $this->input->get('per_page') : '';

            $sort = $this->input->get("sort");
            $order = $this->input->get("order");
            if (empty($sort)) {
                $sort = "sku";
            }
            if (empty($order)) {
                $order = "asc";
            }

            $option["orderby"] = $sort . " " . $order;
            $option["exclude_bundle"] = 1;
            $data["objlist"] = $this->sc['Product']->getDao('Product')->getListWithName($where, $option);
            $data["total"] = $this->sc['Product']->getDao('Product')->getListWithName($where, array_merge(['num_rows'=>1], $option));

            $config['base_url'] = base_url($this->tool_path . '/plist');
            $config['total_rows'] = 1000;
            $config['page_query_string'] = true;
            $config['reuse_query_string'] = true;
            $config['per_page'] = $option['limit'];
            $this->pagination->initialize($config);
            $data['links'] = $this->pagination->create_links();

            $data["notice"] = notice($lang);
            $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
            $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
            $data["platform_type"] = $platform_type;
        }

        $this->load->view($this->tool_path . '/pricing_tool_list', $data);
    }

    public function view($platform_type = "", $sku = "")
    {
        include_once(APPPATH . "helpers/simple_log_helper.php");

        if ($sku == "") {
            exit;
        }

        $no_of_valid_supplier = $this->sc['Supplier']->checkValidSupplierCost($sku);
        if ($no_of_valid_supplier == 1) {
            $data = [];
            $data['canedit'] = 1;
            $data["prompt_notice"] = 0;
            $data["valid_supplier"] = 1;

            $this->processPostedData($platform_type, $sku);

            $data["sku"] = $sku;
            $data["target"] = $this->input->get('target');
            $data["platform_type"] = $platform_type;
            include_once APPPATH . "language/" . $this->getAppId() . "01_" . $this->getLangId() . ".php";
            $data["lang"] = $lang;
            $data['app_id'] = $this->getAppId();
            $data["notice"] = notice($lang);
            if ($data["prod_obj"]  = $prod_obj = $this->sc['Product']->getDao('Product')->get(['sku'=>$sku])) {
                $_SESSION["prod_obj"] = serialize($prod_obj);
                $data["inv"] = $this->sc['WmsInventory']->getInventoryList(["sku" => $sku]);
                $data['master_sku'] = $this->sc['SkuMapping']->getMasterSku(['sku' => $sku, 'ext_sys' => 'WMS', 'status' => 1]);
                $data["supplier"] = $this->sc['Product']->getDao('Product')->getCurrentSupplier($sku);
                $data["mkt_note_obj"] = $this->sc['Product']->getDao('ProductNote')->getNoteWithAuthorName("WEBGB", $sku, "M");
                $data["src_note_obj"] = $this->sc['Product']->getDao('ProductNote')->getNoteWithAuthorName(null, $sku, "S");
                $data["num_of_supplier"] = $this->sc['Product']->getDao('Product')->getTotalDefaultSupplier($sku);
                $data["freight_cat"] = $this->sc['PricingTool']->getFreightCatById($data["prod_obj"]->getFreightCatId());
                $data["qty_in_orders"] = $this->sc['PricingTool']->getQtyInOrders($sku);

                $data["website_product_url"] = $this->sc['PricingTool']->getProductUrl($sku);

                $princing_data = $this->sc['PricingTool']->getPricingToolPanel($sku, $platform_type, $data['master_sku'], $data["prod_obj"]);
                if ($princing_data) {
                    $data = array_merge($princing_data, $data);
                }

            }

            $_SESSION["prod_obj"] = serialize($prod_obj);
        }

        $this->load->view($this->tool_path . "/pricing_tool_view", $data);
    }

    public function processPostedData($platform_type, $sku, $redirect = true, $force_auto_price = false, $force_list = false) {
        if ($this->input->post('posted')) {
            $plat = $this->input->post('selling_platform');
            $splist = $this->input->post("selling_price");
            $pae = $this->input->post('allow_express');
            $pia = $this->input->post('is_advertised');
            $pft = $this->input->post('formtype');
            $pls = $this->input->post('listing_status');

            $hidden_profit = $this->input->post("hidden_profit");
            $hidden_margin = $this->input->post("hidden_margin");

            $status = $this->input->post('status');
            $ext_mapping_code = $this->input->post('ext_mapping_code');
            $max_order_qty = $this->input->post('max_order_qty');

            foreach ($plat as $platform => $val) {
                $vars = [];
                $vars['sku'] = $sku;
                $vars['platform'] = $platform;
                $vars['sp'] = trim($splist[$platform]);
                $allow_express = $pae[$platform];
                $is_advertised = $pia[$platform];
                $vars['ae'] = ($allow_express) ? 'Y' : 'N';
                $vars['ia'] = ($is_advertised)? 'Y' : 'N';

                $vars['cur_listing_status'] = $pls[$platform];
                $vars['formtype'] = $pft[$platform];
                $vars['profit'] = $hidden_profit[$platform];
                $vars['margin'] = $hidden_margin[$platform];

                $vars['special_update'] = 1;
                $vars['status'] = $status;
                $vars['ext_mapping_code'] = $ext_mapping_code;
                $vars['max_order_qty'] = $max_order_qty;

                switch ($platform_type) {
                    case 'WEBSITE':
                        $pap = $this->input->post('auto_price');
                        $pfrrp = $this->input->post('fixed_rrp');
                        $prrp_factor = $this->input->post('rrp_factor');

                        $vars['rrp_factor'] = trim($prrp_factor[$platform]);
                        $ap = (!$pap[$platform]) ? 'N' : $pap[$platform];
                        $vars['ap'] = ($force_auto_price) ? "Y" : $ap;
                        $vars['frrp'] = ($pfrrp[$platform] == 'N') ? 'N' : 'Y';
                        $vars['cur_listing_status'] = ($force_list) ? "L" : $vars['cur_listing_status'];

                        $arr = $this->sc['PricingToolWebsite']->updatePricingForWebsite($vars);
                        $this->sc['PriceMargin']->insertOrUpdateMargin($vars['sku'], $vars['platform'], $vars['sp'], $vars['profit'], $vars['margin']);
                        break;

                    case 'EBAY':
                        $price_ext = $this->input->post('price_ext');
                        $action = $this->input->post('action');
                        $reason = $this->input->post('reason');

                        $vars['ext_ref_1'] = $price_ext[$platform]['ext_ref_1'];
                        $vars['ext_ref_2'] = $price_ext[$platform]['ext_ref_2'];
                        $vars['ext_ref_3'] = $price_ext[$platform]['ext_ref_3'];
                        $vars['ext_ref_4'] = $price_ext[$platform]['ext_ref_4'];
                        $vars['ext_qty'] = $price_ext[$platform]['ext_qty'];
                        $vars['title'] = $price_ext[$platform]['title'];
                        $vars['handling_time'] = $price_ext[$platform]['handling_time'];
                        $vars['action'] = $action[$platform];
                        $vars['reason'] = $reason[$platform];

                        $arr = $this->sc['PricingToolEbay']->updatePricingForEbay($vars);

                    default:
                        # code...
                        break;
                }
            }

            $this->postDataForProduct($sku);

            if ($redirect) {
                Redirect(base_url() . $this->tool_path . "/view/" .$platform_type . "/" . $sku);
            }
        }

        return null;
    }

    public function update_pricing_for_platform($platform_type)
    {
        $vars = $arr = [];
        $vars['sku'] = $this->input->post('sku');
        $vars['platform'] = $this->input->post('platform');
        $vars['sp'] = trim($this->input->post('selling_price'));
        $allow_express = $this->input->post('allow_express');
        $is_advertised = $this->input->post('is_advertised');
        $vars['ae'] = ($allow_express) ? 'Y' : 'N';
        $vars['ia'] = ($is_advertised)? 'Y' : 'N';
        $vars['cur_listing_status'] = $this->input->post('listing_status');
        $vars['formtype'] = $this->input->post('formtype');
        $vars['profit'] = $this->input->post('hidden_profit');
        $vars['margin'] = $this->input->post('hidden_margin');

        switch ($platform_type) {
            case 'WEBSITE':
                $force_auto_price = false;
                $force_list = false;
                $vars['rrp_factor'] = trim($this->input->post('rrp_factor'));
                $ap = (!$this->input->post('auto_price')) ? 'N' : $this->input->post('auto_price');
                $vars['ap'] = ($force_auto_price) ? "Y" : $ap;
                $vars['frrp'] = ($this->input->post('fixed_rrp') == 'N') ? 'N' : 'Y';
                $vars['cur_listing_status'] = ($force_list) ? "L" : $vars['cur_listing_status'];

                $arr = $this->sc['PricingToolWebsite']->updatePricingForWebsite($vars);
                $this->sc['PriceMargin']->insertOrUpdateMargin($vars['sku'], $vars['platform'], $vars['sp'], $vars['profit'], $vars['margin']);
                break;

            case 'EBAY':
                $vars['ext_ref_1'] = $this->input->post('ext_ref_1');
                $vars['ext_ref_2'] = $this->input->post('ext_ref_2');
                $vars['ext_ref_3'] = $this->input->post('ext_ref_3');
                $vars['ext_ref_4'] = $this->input->post('ext_ref_4');
                $vars['ext_qty'] = $this->input->post('ext_qty');
                $vars['title'] = $this->input->post('title');
                $vars['handling_time'] = $this->input->post('handling_time');
                $vars['action'] = $this->input->post('action');
                $vars['reason'] = $this->input->post('reason');

                $arr = $this->sc['PricingToolEbay']->updatePricingForEbay($vars);
            default:
                # code...
                break;
        }

        print json_encode($arr);
    }

    public function update_product_for_pricing_tool($platform_type, $sku, $platform_id="")
    {
        if ( (empty($platform_type) && empty($platform_id)) && empty($sku) ) {
            return false;
        }

        $this->sc['PricingTool']->updatePricingByPlatformSku([
                                    'platform_type'=>$platform_type,
                                    'platform_id'=>$platform_id,
                                    'sku' => $sku,
                                    'status' => $this->input->post('status'),
                                    'emc' => $this->input->post('ext_mapping_code'),
                                    'moq' => $this->input->post('max_order_qty')
                                 ]);

        $arr = $this->postDataForProduct($sku);

        echo json_encode($arr);
    }

    public function postDataForProduct($sku)
    {
        $prod_obj = unserialize($_SESSION["prod_obj"]);
        $prev_webqty = $prod_obj->getWebsiteQuantity();
        $prod_obj->setClearance($this->input->post('clearance'));
        $prod_obj->setWebsiteQuantity($this->input->post('webqty'));

        if ($this->input->post('chk')) {
           $prod_obj->setEan($this->input->post('ean'));
           $prod_obj->setMpn($this->input->post('mpn'));
           $prod_obj->setUpc($this->input->post('upc'));
        }

        if ($this->input->post('webqty') && ($this->input->post('webqty') != $prev_webqty)) {
            $display_qty = $this->sc['PricingTool']->getDisplayQty($prod_obj->getSku());
            if ($display_qty !== false) {
                $prod_obj->setDisplayQuantity($display_qty);
            }
        }

        if ($this->input->post('webqty') == 0) {
            $prod_obj->setWebsiteStatus('O');
        } else {
            $prod_obj->setWebsiteStatus($this->input->post('status'));
        }

        $ret2 = $this->sc['Product']->getDao('Product')->update($prod_obj);
        if ($ret === FALSE) {
            $_SESSION["NOTICE"] = "update_failed";
        } else {
            unset($_SESSION["prod_obj"]);
            $_SESSION["prod_obj"] = serialize($prod_obj);
        }

        if (trim($this->input->post('m_note')) != "") {
            $m_obj = $this->sc['PricingTool']->addProductNote($sku, 'M', 'WEBGB', $this->input->post('m_note'));
        }

        if (trim($this->input->post('s_note')) != "") {
            $s_obj = $this->sc['PricingTool']->addProductNote($sku, 'S', 'WEBGB', $this->input->post('s_note'));
        }
        // $this->product_update_followup_service->google_shopping_update($sku);
        // $google_adwords_target_platform_list = $this->input->post('google_adwords');
        // //$adGroup_status = $this->input->post('adGroup_status');
        // $this->product_update_followup_service->adwords_update($sku, $google_adwords_target_platform_list);

        $arr['add_m_note'] = $arr['add_s_note'] = false;
        if ($m_obj) {
            $arr['add_m_note'] = true;
            $arr['m_create_by'] = $m_obj->getCreateBy();
            $arr['m_create_on'] = $m_obj->getCreateOn();
            $arr['m_note'] = $m_obj->getNote();
        }
        if ($s_obj) {
            $arr['add_s_note'] = true;
            $arr['s_create_by'] = $s_obj->getCreateBy();
            $arr['s_create_on'] = $s_obj->getCreateOn();
            $arr['s_note'] = $s_obj->getNote();
        }

        return $arr;
    }
    public function bulk_list($platform_type)
    {
        $data = [];
        include_once APPPATH . "language/" . $this->getAppId() . "00_" . $this->getLangId() . ".php";
        $data["lang"] = $lang;
        $data['platform_type'] = $platform_type;
        $this->load->view($this->tool_path . "/pricing_tool_bulk_list", $data);
    }

    public function bulk_list_post($platform_type)
    {
        $sku_list = explode("\n", $this->input->post("sku_list"));
        // remove the extra
        $sku_list = array_filter($sku_list);

        $msg = $this->sc['PricingTool']->setAutoPricingForBulkSku($sku_list, $platform_type);

        header("Content-Type: text/html");
        echo "<html><head></head><body>";
        echo "<a href='/". $this->tool_path ."/bulk_list/". $platform_type ."'>Return to pricing tool</a><br><br>$msg";

        die();
    }

    public function get_profit_margin_json($platform_id, $sku, $required_selling_price = 0, $required_cost_price = -1, $return_json = false)
    {
        header('Content-type: application/json');

        $sku = strtoupper($sku);
        if ($sku[0] == 'M') {
            $sku = substr($sku, 1);
            $sku = $this->sc['SkuMapping']->getLocalSku($sku);
        }

        $price_list = $this->sc['Price']->getDao('Price')->getPlatformPriceList(["sku" => $sku, "platform_id" => $platform_id]);
        if ($price_list) {
            foreach ($price_list AS $price_obj) {
                $json = $this->sc['Price']->getProfitMarginJson($price_obj->getPlatformId(), $price_obj->getSku(), $required_selling_price, $required_cost_price);
                if ($return_json) {
                    return $json;
                } else {
                    echo $json;
                }
            }
        } else {
            $json = $this->sc['Price']->getProfitMarginJson($platform_id, $sku, $required_selling_price, $required_cost_price);
            if ($return_json) {
                return $json;
            }   else {
                echo $json;
            }
        }
    }

    public function manage_feed($sku, $platform_id)
    {
        $data['list'] = $this->sc['Affiliate']->getDao('AffiliateSkuPlatform')->getSkuFeedStatus($sku, $platform_id);
        $data['sku'] = $sku;
        $data['platform_id'] = $platform_id;

        $this->load->view($this->tool_path . "/v_manage_feed", $data);
    }

    public function manage_feed_platform()
    {
        $option["limit"] = -1;
        $result = $this->sc['SellingPlatform']->getDao('SellingPlatform')->getList(['status'=>1], $option);
        foreach ($result as $dbrow) {
            $data['platform_list'][] = $dbrow->getSellingPlatformId();
        }

        $data['current_mapping'] = $this->sc['Affiliate']->getDao('Affiliate')->getList([], ['limit'=>-1]);

        $this->load->view($this->tool_path . "/v_manage_feed_platform", $data);
    }

    public function set_sku_feed_status_json($affiliate_id, $sku, $platform_id, $status_id)
    {
        $this->sc['PricingTool']->setSkuFeedStatus($affiliate_id, $sku, $platform_id, $status_id);
    }

    public function set_feed_platform_json($affiliate_id, $platform_id = "")
    {
        $this->sc['PricingTool']->setFeedPlatform($affiliate_id, $platform_id);
    }

    public function getAppId()
    {
        return $this->appId;
    }
}