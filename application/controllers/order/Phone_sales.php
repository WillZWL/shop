<?php
class Phone_sales extends MY_Controller
{
    private $appId = "ORD0009";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->path = "order/phone_sales";
    }

    public function index($platform_id = "")
    {
        $sub_app_id = $this->getAppId() . "00";
        $_SESSION["LISTPAGE"] = current_url() . "?" . $_SERVER['QUERY_STRING'];
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;
        $data["notice"] = notice($lang, TRUE);
        $data["platform_id"] = $platform_id;
        $data["sp_list"] = $this->sc['SellingPlatform']->getDao('SellingPlatform')->getList(["type"=>'WEBSITE'], ["orderby"=>"name", "limit"=> -1]);
        $this->load->view($this->path . '/phone_sales_index_v', $data);
    }

    public function prod_list($platform_id = "")
    {
        if ($platform_id == "") {
            show_404();
        }

        $sub_app_id = $this->getAppId() . "00";
        $_SESSION["LISTPAGE"] = current_url() . "?" . $_SERVER['QUERY_STRING'];

        $where = [];
        $option = [];
        $where["pr.platform_id"] = $platform_id;

        $submit_search = 0;

        if ($this->input->get("sku") != "") {
            $where["p.sku LIKE "] = "%" . $this->input->get("sku") . "%";
            $submit_search = 1;
        }

        if ($this->input->get("name") != "") {
            $where["p.name LIKE "] = "%" . $this->input->get("name") . "%";
            $submit_search = 1;
        }

        if ($this->input->get("cat_id") != "") {
            $where["cat_id"] = $this->input->get("cat_id");
            $submit_search = 1;
        }

        if ($this->input->get("sub_cat_id") != "") {
            $where["sub_cat_id"] = $this->input->get("sub_cat_id");
            $submit_search = 1;
        }

        if ($this->input->get("sub_sub_cat_id") != "") {
            $where["sub_sub_cat_id"] = $this->input->get("sub_sub_cat_id");
            $submit_search = 1;
        }

        if ($this->input->get("brand_id") != "") {
            $where["brand_id"] = $this->input->get("brand_id");
            $submit_search = 1;
        }

        if ($this->input->get("website_status") != "") {
            if ($this->input->get("website_status") == "I") {
                $where["website_status"] = "I";
                $where["website_quantity >"] = "0";
            } elseif ($this->input->get("website_status") == "O") {
                $where["((website_status = 'I' && website_quantity <1) OR website_status = 'O' OR listing_status <> 'L')"] = null;
            } else {
                $where["website_status"] = $this->input->get("website_status");
            }
            $submit_search = 1;
        }

        $where["p.status"] = 2;

        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        $option['limit'] = ($this->input->get('limit') != '') ? $this->input->get('limit') : '20';
        $option['offset'] = ($this->input->get('per_page') != '') ? $this->input->get('per_page') : '';

        if (empty($sort)) {
            $sort = "name";
        }

        if (empty($order)) {
            $order = "asc";
        }

        $option["orderby"] = $sort . " " . $order;

        if ($this->input->get("search")) {
            $option["show_name"] = 1;
            $data["objlist"] = $this->sc['Product']->getDao('Product')->getProductOverview($where, $option);
            $data["total"] = $this->sc['Product']->getDao('Product')->getProductOverview($where, ["num_rows"=>1]);
        }

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;
        $config['base_url'] = base_url($this->path .'/prod_list/'. $platform_id);
        $config['total_rows'] = $data["total"];
        $config['page_query_string'] = true;
        $config['reuse_query_string'] = true;
        $config['per_page'] = $option['limit'];
        $data['per_page']  =  $this->input->get('per_page') ;
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();

        $data["notice"] = notice($lang);

        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
        $data["searchdisplay"] = "";
        $data["pbv_obj"] = $this->sc['PlatformBizVar']->getdao('PlatformBizVar')->get(["selling_platform_id"=>$platform_id]);
        $data["default_curr"] = $data["pbv_obj"]->getPlatformCurrencyId();
        $this->load->view($this->path . '/phone_sales_prod_list_v', $data);
    }


    public function cart($platform_id = "", $debug = 0)
    {
        $sub_app_id = $this->getAppId() . "00";
        $_SESSION["LISTPAGE"] = current_url() . "?" . $_SERVER['QUERY_STRING'];

        if ($platform_id == "") {
            show_404();
        }

        $this->sc['PhoneSales']->checkCartByPlatform($platform_id);

        $pbv_obj = $this->sc['PlatformBizVar']->getdao('PlatformBizVar')->get(["selling_platform_id"=>$platform_id]);

        if ($this->input->post("posted")) {
            $post_data['add'] = $this->input->post("add");
            $post_data['lang'] = $this->getLangId();
            $post_data['platform'] = $platform_id;
            $post_data['currency'] = $pbv_obj->getPlatformCurrencyId();
            $post_data['qty'] = $this->input->post("qty");

            $this->sc['PhoneSales']->postDataToCart($post_data);
        }

        $data['cart']  = $this->sc['PhoneSales']->getCart();
        $data["totalcart"] = $data["cart"] ? $data["cart"]->getTotalNumberOfItems() : 0;

        $promo_disc_amount = $sub_total = $total_vat = $total = 0;
        // if ($data["promo"]["valid"] && !$data["promo"]["error"]) {
        //     $promo_disc_amount = $data["promo"]["disc_amount"];
        // }

        // if (!$data["promo"]["valid"] || $data["promo"]["error"]) {
        //     unset($_SESSION["promotion_code"]);
        // }

        $offlineFee = $this->input->post("offline_fee") ? $this->input->post("offline_fee") : "";
        if (! $offlineFee) {
            $data["offline_fee"] = '';
        } else {
            $data["offline_fee"] =  $offlineFee;
        }

        $data["allow_see_margin"] = false;
        if ($data["totalcart"]) {
            $data["allow_see_margin"] = check_app_feature_access_right($this->getAppId(), "ORD000900_cs_man_margin");
            $data["total"] = $data["cart"]->getSubtotal();
            $costprice_total = $data["cart"]->getCost();

            $data["total_cart_price"] = ($data["total"] + $data["cart"]->getDeliveryCharge()* 1 + $data["offline_fee"] * 1 - $promo_disc_amount);

            if ($data["total_cart_price"] != 0) {
                $data["cart_profit_margin"] = (100 - ($costprice_total / $data["total_cart_price"] * 100));
            } else {
                $data["cart_profit_margin"] = "*total cart price is zero*";
            }

            if ( $offlineFee < 0 && $data["cart_profit_margin"] < 7) {
                $data["offline_fee"] = '';
            }
        }

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;
        $data["notice"] = notice($lang);
        $data["pbv_obj"] = $pbv_obj;
        $data["default_curr"] = $data["pbv_obj"]->getPlatformCurrencyId();
        $data["platform_id"] = $platform_id;
        $this->load->view($this->path . '/phone_sales_cart_v', $data);
    }


    public function take_order($platform_id = "")
    {
        $sub_app_id = $this->getAppId() . "00";
        $_SESSION["LISTPAGE"] = current_url() . "?" . $_SERVER['QUERY_STRING'];

        if ($platform_id == "") {
            show_404();
        }

        if ($this->input->post("posted")) {
            if (!$this->input->post("took")) {
                $this->sc['PhoneSales']->addSoForPhoneSales($_POST, $platform_id);
            }

            if ($this->input->post("promotion_code")) {
                $_SESSION["promotion_code"] = $this->input->post("promotion_code");
            } else {
                unset($_SESSION["promotion_code"]);
            }
        }

        if ($this->input->post('clientid')) {
            $_SESSION["client"]["country_id"] = $this->input->post("country");
            $_SESSION["client"]["del_country_id"] = $this->input->post("country");
            if ($this->input->post('clientid')) {
                $client_obj = $this->sc['Client']->getDao('Client')->get(["id" => $this->input->post('clientid')]);
                $client_obj->setCountryId($_SESSION["client"]["country_id"]);
                $client_obj->setDelCountryId($_SESSION["client"]["del_country_id"]);
                $client = obj_to_query($client_obj);
            }
        }

        $pbv_obj = $this->sc['PlatformBizVar']->getdao('PlatformBizVar')->get(["selling_platform_id"=>$platform_id]);

        if ($this->input->post("country") != "") {
            $country_id = $this->input->post("country");
        } elseif ($_SESSION["client"]["country_id"]) {
            $country_id = $_SESSION["client"]["country_id"];
        } else {
            $country_id = $pbv_obj->getPlatformCountryId();
        }

        $data['cart']  = $this->sc['PhoneSales']->getCart();
        $data["totalcart"] = $data["cart"] ? $data["cart"]->getTotalNumberOfItems() : 0;
        $data["total_cart_price"] = ($data["total"] + $data["cart"]->getDeliveryCharge()* 1 + $data["offline_fee"] * 1 - $promo_disc_amount);

        // if ($data["totalcart"]) {
            $data["total"] = $data["cart"]->getSubtotal();
            $costprice_total = $data["cart"]->getCost();
        // }

        $data["country_id"] = $country_id;

        if (!$data["promo"]["valid"] || $data["promo"]["error"]) {
            unset($_SESSION["promotion_code"]);
        }

        $data["vat_exempt"] = $this->input->post("vat_exempt");
        $data["free_delivery"] = $this->input->post("free_delivery");

        // if (count($data["cart"]) == 0) {
        //     unset($_SESSION["client"]["country_id"]);
        //     unset($_SESSION["client"]["del_country_id"]);
        // }

        $data["order_reason_list"] = $this->sc['So']->getDao('OrderReason')->getList(["status" => 1, "option_in_phone" => 1], ["limit" => -1, "orderby" => "priority"]);

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
        $data["country_list"] =  $this->sc['Region']->getSellCountryList();
        $data["lang"] = $lang;
        $data["client"] = $client;
        $data["notice"] = notice($lang);
        $data["pbv_obj"] = $pbv_obj;
        $data["state_list"] = $this->sc['CountryState']->getDao('CountryState')->getList(["country_id" => "US"]);
        $data["default_curr"] = $data["pbv_obj"]->getPlatformCurrencyId();
        $this->load->view($this->path . '/phone_sales_take_order_v', $data);
    }

    public function check_email($platform_country = "")
    {
        $email = trim($this->input->get("email"));
        if ($email) {
            $sub_app_id = $this->getAppId() . "00";
            $_SESSION["LISTPAGE"] = current_url() . "?" . $_SERVER['QUERY_STRING'];
            include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
            $data["lang"] = $lang;
            $data["client"] = $this->sc['Client']->getDao('Client')->get(["email" => $email]);
            $data["country"] = $platform_country;
            $this->load->view('order/phone_sales/phone_sales_check_email_v', $data);
        } else {
            show_404();
        }
    }

    public function on_hold()
    {
        $sub_app_id = $this->getAppId() . "01";
        $_SESSION["LISTPAGE"] = current_url() . "?" . $_SERVER['QUERY_STRING'];

        if ($this->input->post("posted")) {
            $post_data['status'] = $this->input->post("status");
            $post_data['so_no'] = $this->input->post("so_no");
            $post_data['pay_to_account'] = $this->input->post("pay_to_account");
            $post_data['payment_gateway'] = $this->input->post("payment_gateway");
            $post_data['offline_fee'] = $this->input->post("offline_fee");
            $post_data['txn_id'] = $this->input->post("txn_id");

            $this->sc['PhoneSales']->processDataForOnHold($post_data);
        }


        $where = [];
        $option = [];

        if ($this->input->get("so_no") != "") {
            $where["so.so_no"] = trim($this->input->get("so_no"));
        }

        if ($this->input->get("email") != "") {
            $where["c.email LIKE "] = "%" . $this->input->get("email") . "%";
        }

        if ($this->input->get("delivery_charge")) {
            fetch_operator($where, "so.delivery_charge", $this->input->get("delivery_charge"));
        }

        if ($this->input->get("offline_fee")) {
            fetch_operator($where, "soe.offline_fee", $this->input->get("offline_fee"));
        }

        if ($this->input->get("amount")) {
            fetch_operator($where, "so.amount", $this->input->get("amount"));
        }

        $where["biz_type"] = "OFFLINE";
        $where["so.status"] = "1";
        $where["so.hold_status"] = "0";
        $option["so_item"] = "1";
        $option["hide_payment"] = "1";

        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        $option['limit'] = ($this->input->get('limit') != '') ? $this->input->get('limit') : '20';
        $option['offset'] = ($this->input->get('per_page') != '') ? $this->input->get('per_page') : '';

        if (empty($sort))
            $sort = "so_no";

        if (empty($order))
            $order = "desc";

        $option["orderby"] = $sort . " " . $order;
        $option["notes"] = TRUE;
        $option["extend"] = TRUE;

        if (isset($_GET["so_no"]) || isset($_GET["email"]) || isset($_GET["delivery_charge"]) || isset($_GET["offline_fee"]) || isset($_GET["amount"])) {
            $data["objlist"] = $this->sc['So']->getDao('So')->getListWithName($where, $option);
            $data["total"] = $this->sc['So']->getDao('So')->getListWithName($where, ["notes" => 1, "extend" => 1, "num_rows" => 1]);
        }
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;

        $config['base_url'] = base_url($this->path .'/on_hold/');
        $config['total_rows'] = $data["total"];
        $config['page_query_string'] = true;
        $config['reuse_query_string'] = true;
        $config['per_page'] = $option['limit'];
        $data['per_page']  =  $this->input->get('per_page') ;
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();
        $data["notice"] = notice($lang);

        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
        $data["searchdisplay"] = "";

        $this->load->view('order/phone_sales/phone_sales_on_hold_v', $data);
    }
    public function getAppId()
    {
        return $this->appId;
    }
}