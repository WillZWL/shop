<?php

class Product_overview_amuk extends MY_Controller
{

    private $app_id = "MKT0010";
    private $lang_id = "en";


    public function __construct()
    {
        parent::__construct();
        $this->load->model('marketing/product_overview_amuk_model');
        $this->load->helper(array('url', 'notice', 'object', 'operator'));
        $this->load->library('service/pagination_service');
        $this->load->library('service/context_config_service');
    }

    public function index($platform_id = "AMUK")
    {
        $sub_app_id = $this->_get_app_id() . "00";
        $_SESSION["LISTPAGE"] = base_url() . "marketing/product_overview_amuk/?" . $_SERVER['QUERY_STRING'];
        switch ($platform_id) {
            default:
                $price_service = "amuk_price";
                break;
        }

        if ($this->input->post("posted") && $_POST["check"]) {
            $rsresult = "";
            $shownotice = 0;
            foreach ($_POST["check"] as $rssku) {
                $success = 0;
                if (($price_obj = $this->product_overview_amuk_model->get_price($price_service, array("sku" => $rssku, "platform_id" => $platform_id))) !== FALSE) {
                    if (empty($price_obj)) {
                        $price_obj = $this->product_overview_amuk_model->get_price($price_service);
                        set_value($price_obj, $_POST["price"][$rssku]);
                        $price_obj->set_sku($rssku);
                        $price_obj->set_platform_code('');
                        $price_obj->set_platform_id($platform_id);
                        $price_obj->set_listing_status('L');
                        $price_obj->set_status(1);
                        $price_obj->set_allow_express('N');
                        $price_obj->set_is_advertised('N');
                        $price_obj->set_max_order_qty(100);
                        $price_obj->set_auto_price('N');
                        if ($this->product_overview_amuk_model->add_price($price_service, $price_obj)) {
                            $success = 1;
                        }
                    } else {
                        set_value($price_obj, $_POST["price"][$rssku]);
                        if ($this->product_overview_amuk_model->update_price($price_service, $price_obj)) {
                            $success = 1;
                        }
                    }
                }
                if ($success) {
                    if ($product_obj = $this->product_overview_amuk_model->get("product", array("sku" => $rssku))) {
                        set_value($product_obj, $_POST["product"][$rssku]);
                        if ($this->product_overview_amuk_model->update("product", $product_obj)) {
                            $success = 1;
                        } else {
                            $success = 0;
                        }
                    } else {
                        $success = 0;
                    }
                }
                if (!$success) {
                    $shownotice = 1;
                }
                $rsresult .= "{$rssku} -> {$success}\\n";
            }
            if ($shownotice) {
                $_SESSION["NOTICE"] = $rsresult;
            }
            redirect(current_url() . "?" . $_SERVER['QUERY_STRING']);
        }

        $where = array();
        $option = array();

        $submit_search = 0;

        $where["platform_id"] = $platform_id;
        $option["inventory"] = 1;

        if ($this->input->get("sku") != "") {
            $where["sku LIKE "] = "%" . $this->input->get("sku") . "%";
            $submit_search = 1;
        }

        if ($this->input->get("platform_code") != "") {
            $where["platform_code LIKE "] = "%" . $this->input->get("platform_code") . "%";
            $submit_search = 1;
        }

        if ($this->input->get("cat_id") != "") {
            $where["cat_id"] = $this->input->get("cat_id");
        }

        if ($this->input->get("sub_cat_id") != "") {
            $where["sub_cat_id"] = $this->input->get("sub_cat_id");
        }

        if ($this->input->get("sub_sub_cat_id") != "") {
            $where["sub_sub_cat_id"] = $this->input->get("sub_sub_cat_id");
        }

        if ($this->input->get("brand_id") != "") {
            $where["brand_id"] = $this->input->get("brand_id");
        }

        if ($this->input->get("supplier_id") != "") {
            $where["supplier_id"] = $this->input->get("supplier_id");
        }

        if ($this->input->get("prod_name") != "") {
            $where["prod_name LIKE "] = "%" . $this->input->get("prod_name") . "%";
            $submit_search = 1;
        }

        if ($this->input->get("clearance") != "") {
            $where["clearance"] = $this->input->get("clearance");
            $submit_search = 1;
        }

        if ($this->input->get("listing_status") != "") {
            $where["listing_status"] = $this->input->get("listing_status");
            $submit_search = 1;
        }

        if ($this->input->get("inventory") != "") {
            fetch_operator($where, "inventory", $this->input->get("inventory"));
            $submit_search = 1;
        }

        if ($this->input->get("website_quantity") != "") {
            fetch_operator($where, "website_quantity", $this->input->get("website_quantity"));
            $submit_search = 1;
        }

        if ($this->input->get("website_status") != "") {
            $where["website_status"] = $this->input->get("website_status");
            $submit_search = 1;
        }

        if ($this->input->get("sourcing_status") != "") {
            $where["sourcing_status"] = $this->input->get("sourcing_status");
            $submit_search = 1;
        }

        if ($this->input->get("shiptype_name") != "") {
            $where["shiptype_name"] = $this->input->get("shiptype_name");
            $submit_search = 1;
        }

        if ($this->input->get("latency") != "") {
            $where["latency"] = $this->input->get("latency");
            $submit_search = 1;
        }

        if ($this->input->get("auto_price") != "") {
            $where["auto_price"] = $this->input->get("auto_price");
            $submit_search = 1;
        }

        if ($this->input->get("purchaser_updated_date") != "") {
            fetch_operator($where, "purchaser_updated_date", $this->input->get("purchaser_updated_date"));
            $submit_search = 1;
        }

        if ($this->input->get("profit") != "") {
            fetch_operator($where, "profit", $this->input->get("profit"));
            $option["refresh_margin"] = 1;
            $submit_search = 1;
        }

        if ($this->input->get("margin") != "") {
            fetch_operator($where, "margin", $this->input->get("margin"));
            $option["refresh_margin"] = 1;
            $submit_search = 1;
        }

        if ($this->input->get("price") != "") {
            fetch_operator($where, "price", $this->input->get("price"));
            $submit_search = 1;
        }

        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        $limit = '20';

        $pconfig['base_url'] = $_SESSION["LISTPAGE"];
        $option["limit"] = $pconfig['per_page'] = $limit;
        if ($option["limit"]) {
            $option["offset"] = $this->input->get("per_page");
        }

        if (empty($sort))
            $sort = "prod_name";

        if ($sort == "margin" || $sort == "profit") {
            $option["refresh_margin"] = 1;
        }

        if (empty($order))
            $order = "asc";

        $option["orderby"] = $sort . " " . $order;

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;

        if ($this->input->get("search")) {
            $data["objlist"] = $this->product_overview_amuk_model->get_product_list($where, $option, $lang);
            $data["total"] = $this->product_overview_amuk_model->get_product_list_total($where, $option);
        }

        $pconfig['total_rows'] = $data['total'];
        $this->pagination_service->set_show_count_tag(TRUE);
        $this->pagination_service->initialize($pconfig);

        $data["notice"] = notice($lang);

        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
//      $data["searchdisplay"] = ($submit_search)?"":'style="display:none"';
        $data["searchdisplay"] = "";
        $this->load->view('marketing/product_overview_amuk/product_overview_v', $data);
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function js_overview()
    {
        header("Content-type: text/javascript; charset: UTF-8");
        header("Cache-Control: must-revalidate");
        $offset = 60 * 60 * 24;
        $ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
        header($ExpStr);
        $js = "function CalcProfit(sku, price)
            {
                price = (price*1).toFixed(2);
                var declared = prod[sku]['declared_rate'] * price / 100;
                declared = prod[sku]['shiptype']==2?0:declared.toFixed(2)*1;
                var freight_cost = prod[sku]['shiptype']==2?0:prod[sku]['freight_cost']*1;
                var admin_fee = prod[sku]['admin_fee'].toFixed(2);
                vat_pcent = prod[sku]['vat_percent']*1;
                var delivery_charge = prod[sku]['platform_delivery_charge']*1;
                delivery_charge = delivery_charge.toFixed(2);
                var vat = (declared + admin_fee*1) * vat_pcent/100;
                vat = prod[sku]['shiptype']==2?0:vat.toFixed(2)*1;
                var duty = prod[sku]['duty_percent'] / 100 * declared;
                duty = duty.toFixed(2)*1;
                var payment = prod[sku]['payment_charge_rate'] / 100 * price;
                payment = payment.toFixed(2)*1;
                var delivery_cost = prod[sku]['delivery_cost']*1;
                var supplier_cost = prod[sku]['supplier_cost']*1;
                var fdl = prod[sku]['free_delivery_limit']*1;
                var ddc = prod[sku]['default_delivery_charge']*1;
                var commission = prod[sku]['platform_commission'] * (price * 1 + delivery_charge * 1) / 100 * 1;
                commission = commission.toFixed(2);

                var total = price + delivery_charge;
                total = total;
                var cost =  vat*1 + duty*1 + payment*1 + admin_fee*1 + freight_cost*1 + delivery_cost*1 + supplier_cost*1 + commission * 1;
                cost = cost.toFixed(2);
                var profit = price*1 + delivery_charge*1 - cost*1;
                profit = profit.toFixed(2);
                var margin = profit / (price*1 - vat*1) * 100;
                margin = margin.toFixed(2);

                document.fm_edit.elements['cost['+ sku +']'].value = cost;
                document.getElementById('profit['+ sku + ']').innerHTML = profit;
                document.getElementById('margin['+ sku + ']').innerHTML = margin+'%';
                if (profit*1 < 0)
                {
                    AddClassName(document.getElementById('profit['+ sku + ']'), 'warn', true);
                    AddClassName(document.getElementById('margin['+ sku + ']'), 'warn', true);
                }
                else
                {
                    RemoveClassName(document.getElementById('profit['+ sku + ']'), 'warn', true);
                    RemoveClassName(document.getElementById('margin['+ sku + ']'), 'warn', true);
                }
            }";
        echo $js;
    }
}


