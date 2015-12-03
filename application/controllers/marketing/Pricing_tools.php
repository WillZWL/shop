<?php
DEFINE("PLATFORM_TYPE", "WEBSITE");

class pricing_tools extends MY_Controller
{
    public $tool_path;
    public $default_platform_id;

    private $appId = 'MKT0200';
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

            $limit = '20';
            $option["limit"] = $limit;
            $option["offset"] = $offset;

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
            $config['total_rows'] = $data["total"];
            $config['per_page'] = $limit;
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
            $data["sku"] = $sku;
            $data["target"] = $this->input->get('target');
            $data["platform_type"] = $platform_type;
            include_once APPPATH . "language/" . $this->getAppId() . "01_" . $this->getLangId() . ".php";
            $data["lang"] = $lang;
            $data['app_id'] = $this->getAppId();
            $data["notice"] = notice($lang);
            if ($data["prod_obj"]  = $prod_obj = $this->sc['Product']->getDao('Product')->get(['sku'=>$sku])) {

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

                $data = array_merge($princing_data, $data);

            }

            $_SESSION["prod_obj"] = serialize($prod_obj);
        }

        $this->load->view($this->tool_path . "/pricing_tool_view", $data);
    }

    public function update_pricing_for_platform($force_auto_price = false, $force_list = false)
    {
        $sku = $this->input->post('sku');
        $platform = $this->input->post('platform');
        $sp = trim($this->input->post('selling_price'));
        $allow_express = $this->input->post('allow_express');
        $cur_listing_status = $this->input->post('listing_status');
        $is_advertised = $this->input->post('is_advertised');
        $auto_price = $this->input->post('auto_price');
        $formtype = $this->input->post('formtype');
        $fixed_rrp = $this->input->post('fixed_rrp');
        $rrp_factor = trim($this->input->post('rrp_factor'));
        $profit = $this->input->post('hidden_profit');
        $margin = $this->input->post('hidden_margin');

        $ae = ($allow_express) ? 'Y' : 'N';
        $ia = ($is_advertised)? 'Y' : 'N';
        $ap = (!$auto_price) ? 'N' : $auto_price;
        $frrp = ($fixed_rrp == 'N') ? 'N' : 'Y';

        $ap = ($force_auto_price) ? "Y" : $ap;
        $cur_listing_status = ($force_list) ? "L" : $cur_listing_status;

        $arr = [];
        $price_obj = unserialize($_SESSION["price_obj_" . $platform]);

        if ($price_obj->getPrice() * 1 != $sp * 1 ||
            $price_obj->getListingStatus() != $cur_listing_status ||
            $price_obj->getAllowExpress() != $ae ||
            $price_obj->getIsAdvertised() != $ia ||
            $price_obj->getAutoPrice() != $ap ||
            $price_obj->getFixedRrp() != $frrp ||
            (($frrp == 'N') && ($rrp_factor != '') && ($price_obj->getRrpFactor() != $rrp_factor))
        ) {
            $price_obj->setPlatformId($platform);
            $price_obj->setSku($sku);
            // $price_obj->setStatus($this->input->post('status'));
            // $price_obj->setExtMappingCode($this->input->post('ext_mapping_code'));
            $price_obj->setListingStatus($cur_listing_status);
            $price_obj->setPrice($sp);

            $price_obj->setAllowExpress($ae);
            $price_obj->setIsAdvertised($ia);
            $price_obj->setAutoPrice($ap);
            $price_obj->setFixedRrp($frrp);
            // $price_obj->setMaxOrderQty($this->input->post('max_order_qty'));

            if (($frrp == 'N') && ($rrp_factor != '')) {
                $price_obj->setRrpFactor($rrp_factor);
            }

            if (is_null($price_obj->getRrpFactor())) {
                $price_obj->setRrpFactor($this->sc['PricingTool']->getRrpFactorBySku($sku));
            }

            if ($formtype == "update") {
                $ret =  $this->sc['Price']->getDao('Price')->update($price_obj);
            } else {
                $ret =  $this->sc['Price']->getDao('Price')->insert($price_obj);
            }

            if ($ret === FALSE) {
                $arr['fail'] = true;
                $arr['fail'] = "update_failed ". $this->db->display_error();
            } else {
                $arr['success'] = true;
                $arr['price'] = $sp;
                $arr['listing_status'] = $cur_listing_status;
                $arr['margin'] = $margin ;
                unset($_SESSION["price_obj_" . $platform]);
                $_SESSION["price_obj_" . $platform] = serialize($price_obj);
            }
        } else {
            $arr['no_update'] = true;
        }

        $this->sc['PriceMargin']->insertOrUpdateMargin($sku, $platform, $sp, $profit, $margin);

        print json_encode($arr);
    }

    public function update_product_for_pricing_tool($sku = "")
    {
        if ( empty($sku) ) {
            return false;
        }
        $status = $this->input->post('status');
        $clearance = $this->input->post('clearance');
        $webqty = $this->input->post('webqty');
        $m_note = $this->input->post('m_note');
        $s_note = $this->input->post('s_note');
        $google_adwords = $this->input->post('google_adwords');
        $emc = $this->input->post('ext_mapping_code');
        $moq = $this->input->post('max_order_qty');

        $this->sc['Price']->getDao('Price')->qUpdate(['sku'=>$sku],['status'=>$status,'ext_mapping_code'=> $emc,'max_order_qty'=>$moq]);

        // $prod_obj = $this->sc['Product']->getDao('Product')->get(['sku'=>$sku]);
        $prod_obj = unserialize($_SESSION["prod_obj"]);
        $prev_webqty = $prod_obj->getWebsiteQuantity();

        $prod_obj->setClearance($clearance);
        $prod_obj->setWebsiteQuantity($webqty);

        if ($webqty && ($webqty != $prev_webqty)) {

            $vpo_where = ["p.sku" => $prod_obj->getSku()];
            $vpo_option = [
                            "to_currency_id" => "GBP",
                            "orderby" => "(prev_price > 0) DESC, (pbv.platform_currency_id = 'GBP') DESC, price DESC", "limit" => 1
                          ];
            if ($vpo_obj = $this->sc['Product']->getDao('Product')->getProdOverviewWoShiptype($vpo_where, $vpo_option)) {
                $display_qty = $this->sc['DisplayQty']->calcDisplayQty($vpo_obj->getCatId(), $vpo_obj->getWebsiteQuantity(), $vpo_obj->getPrice());
                $prod_obj->setDisplayQuantity($display_qty);
            }
        }

            if ($webqty == 0) {
                $prod_obj->setWebsiteStatus('O');
            } else {
                $prod_obj->setWebsiteStatus($status);
            }

            $ret2 = $this->sc['Product']->getDao('Product')->update($prod_obj);

            if ($ret === FALSE) {
                $_SESSION["NOTICE"] = "update_failed";
            } else {
                unset($_SESSION["prod_obj"]);
                $_SESSION["prod_obj"] = serialize($prod_obj);
            }

            if (trim($m_note) != "") {
                $note_obj = $this->sc['Product']->getDao('ProductNote')->get();
                $note_obj->setSku($sku);
                $note_obj->setType('M');
                $note_obj->setNote($m_note);
                if (!($ret = $this->sc['Product']->getDao('ProductNote')->insert($note_obj))) {
                    $_SESSION["NOTICE"] = "update_note_failed";
                }
            }

            if (trim($s_note) != "") {
                $note_obj = $this->sc['Product']->getDao('ProductNote')->get();
                $note_obj->setSku($sku);
                $note_obj->setType('S');
                $note_obj->setNote($s_note);
                if (!($ret = $this->sc['Product']->getDao('ProductNote')->insert($note_obj))) {
                    $_SESSION["NOTICE"] = "update_note_failed";
                }
            }


            // $this->product_update_followup_service->google_shopping_update($sku);
            // $google_adwords_target_platform_list = $this->input->post('google_adwords');
            // //$adGroup_status = $this->input->post('adGroup_status');
            // $this->product_update_followup_service->adwords_update($sku, $google_adwords_target_platform_list);

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

    public function get_js() {
        $this->sc['PricingTool']->print_pricing_tool_js($this->tool_path);
    }

    public function getAppId()
    {
        return $this->appId;
    }
}