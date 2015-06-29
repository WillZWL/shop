<?php
class Phone_sales extends MY_Controller
{

    private $app_id="ORD0009";
    private $lang_id="en";


    public function __construct()
    {
        parent::__construct();
        $this->load->model('order/credit_check_model');
        $this->load->model('marketing/product_model');
        $this->load->model('order/so_model');
        $this->load->model('website/checkout_model');
        $this->load->helper(array('url','notice','object','image', 'operator'));
        $this->load->library('service/pagination_service');
        $this->load->library('service/context_config_service');
        $this->load->library('service/selling_platform_service');
        $this->load->library('service/platform_biz_var_service');
        $this->load->library('service/order_reason_service');
        $this->load->library('service/promotion_code_service');
        $this->load->library('service/country_state_service');
        $this->load->library('service/pmgw');
        $this->load->model('mastercfg/region_model');
        $this->load->library('encrypt');
        if(isset($_SESSION["cart"]) && $_SESSION["cart_type"] != "OFFLINE")
        {
            unset($_SESSION["cart"]);
        }
        $_SESSION["cart_type"] = "OFFLINE";
    }

    public function index($platform_id = "")
    {
        $sub_app_id = $this->_get_app_id()."00";
        $_SESSION["LISTPAGE"] = current_url()."?".$_SERVER['QUERY_STRING'];
        include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
        $data["lang"] = $lang;
        $data["notice"] = notice($lang, TRUE);
        $data["platform_id"] = $platform_id;
        $data["sp_list"] = $this->selling_platform_service->get_list(array("type"=>"WEBSITE"), array("orderby"=>"name", "limit"=> -1));
        $this->load->view('order/phone_sales/phone_sales_index_v', $data);
    }

    public function prod_list($platform_id = "")
    {
        if ($platform_id == "")
        {
            show_404();
        }

        $sub_app_id = $this->_get_app_id()."00";
        $_SESSION["LISTPAGE"] = current_url()."?".$_SERVER['QUERY_STRING'];

        $where = array();
        $option = array();
        $where["platform_id"] = $platform_id;

        $submit_search = 0;

        if ($this->input->get("sku") != "")
        {
            $where["sku LIKE "] = "%".$this->input->get("sku")."%";
            $submit_search = 1;
        }

        if ($this->input->get("name") != "")
        {
            $where["prod_name LIKE "] = "%".$this->input->get("name")."%";
            $submit_search = 1;
        }

        if ($this->input->get("cat_id") != "")
        {
            $where["cat_id"] = $this->input->get("cat_id");
            $submit_search = 1;
        }

        if ($this->input->get("sub_cat_id") != "")
        {
            $where["sub_cat_id"] = $this->input->get("sub_cat_id");
            $submit_search = 1;
        }

        if ($this->input->get("sub_sub_cat_id") != "")
        {
            $where["sub_sub_cat_id"] = $this->input->get("sub_sub_cat_id");
            $submit_search = 1;
        }

        if ($this->input->get("brand_id") != "")
        {
            $where["brand_id"] = $this->input->get("brand_id");
            $submit_search = 1;
        }

        if ($this->input->get("website_status") !="")
        {
            if ($this->input->get("website_status") == "I")
            {
                $where["website_status"] = "I";
                $where["website_quantity >"] = "0";
            }
            elseif($this->input->get("website_status") == "O")
            {
                $where["((website_status = 'I' && website_quantity <1) OR website_status = 'O' OR listing_status <> 'L')"] = null;
            }
            else
            {
                $where["website_status"] = $this->input->get("website_status");
            }
            $submit_search = 1;
        }

        $where["prod_status"] = 2;

        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        $limit = '20';

        $pconfig['base_url'] = $_SESSION["LISTPAGE"];
        $option["limit"] = $pconfig['per_page'] = $limit;
        if ($option["limit"])
        {
            $option["offset"] = $this->input->get("per_page");
        }

        if (empty($sort))
        {
            $sort = "prod_name";
        }

        if (empty($order))
        {
            $order = "asc";
        }

        $option["orderby"] = $sort." ".$order;

        if ($this->input->get("search"))
        {
            $option["show_name"] = 1;
            $data["objlist"] = $this->product_service->get_dao()->get_product_overview($where, $option);
            $data["total"] = $this->product_service->get_dao()->get_product_overview($where, array("num_rows"=>1));
        }

        include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
        $data["lang"] = $lang;

        $pconfig['total_rows'] = $data['total'];
        $this->pagination_service->set_show_count_tag(TRUE);
        $this->pagination_service->initialize($pconfig);

        $data["notice"] = notice($lang);

        $data["sortimg"][$sort] = "<img src='".base_url()."images/".$order.".gif'>";
        $data["xsort"][$sort] = $order=="asc"?"desc":"asc";
//      $data["searchdisplay"] = ($submit_search)?"":'style="display:none"';
        $data["searchdisplay"] = "";
        $data["pbv_obj"] = $this->platform_biz_var_service->get(array("selling_platform_id"=>$platform_id));
        $data["default_curr"] = $data["pbv_obj"]->get_platform_currency_id();
        $this->load->view('order/phone_sales/phone_sales_prod_list_v', $data);
    }

    public function cart($platform_id = "")
    {
        $sub_app_id = $this->_get_app_id()."00";
        $_SESSION["LISTPAGE"] = current_url()."?".$_SERVER['QUERY_STRING'];

        if ($platform_id == "")
        {
            show_404();
        }

        if ($this->input->post("posted"))
        {
            if ($this->input->post("qty"))
            {
                foreach ($this->input->post("qty") as $sku=>$qty)
                {
                    $this->cart_session_service->modify($sku, $qty, $platform_id);
                }
            }
            if ($sku=$this->input->post("add"))
            {
                $this->cart_session_service->add($sku, 1, $platform_id);
            }

            if ($this->input->post("promotion_code"))
            {
                $_SESSION["promotion_code"] = $this->input->post("promotion_code");
            }
            else
            {
                unset($_SESSION["promotion_code"]);
            }
        }

        $pbv_obj = $this->platform_biz_var_service->get(array("selling_platform_id"=>$platform_id));

        if($this->input->post("country") != "")
        {
            $country_id = $this->input->post("country");
        }
        elseif($_SESSION["client"]["country_id"])
        {
            $country_id = $_SESSION["client"]["country_id"];
        }
        else
        {
            $country_id = $pbv_obj->get_platform_country_id();
        }

        $this->cart_session_service->set_del_country_id($country_id);
        $data = $this->cart_session_service->get_detail($platform_id, 1, 0, 0, 0, $this->input->post("vat_exempt"), $this->input->post("free_delivery"));

        $promo_disc_amount = $sub_total = $total_vat = $total = 0;
        if ($data["promo"]["valid"] && !$data["promo"]["error"])
        {
            $promo_disc_amount = $data["promo"]["disc_amount"];
        }

        if (!$data["promo"]["valid"] || $data["promo"]["error"])
        {
            unset($_SESSION["promotion_code"]);
        }

        if(!$this->input->post("offline_fee"))
        {
            $data["offline_fee"] = '';
        }
        else
        {
            $data["offline_fee"] = $this->input->post("offline_fee");
        }

        $data["allow_see_margin"] = false;
        $data["totalcart"] = count($data["cart"]);
        if($data["totalcart"])
        {
            #SBF #2799 temp only allow cs_man to see cart_profit_margin
            $data["allow_see_margin"] = check_app_feature_access_right($this->_get_app_id(), "ORD000900_cs_man_margin");

            for($i=0; $i<$data["totalcart"]; $i++)
            {
                $price = $data["cart"][$i]["price"] - $data["cart"][$i]["vat_total"]/$data["cart"][$i]["qty"];
                $product_cost_obj = $data["cart"][$i]["product_cost_obj"];
                $cur_sub_total = $price*$data["cart"][$i]["qty"];
                $sub_total += $cur_sub_total;
                $total_vat += $data["cart"][$i]["vat_total"];
                $data["total"] += $data["cart"][$i]["total"];

                # SBF #2677 for pricing validation
                $costprice = $product_cost_obj->get_cost();     #cost of single item
                $costprice_qty = $costprice*$data["cart"][$i]["qty"];   #cost x qty of item
                $costprice_total += $costprice_qty;
            }

            $dc_default_courier = $data["dc_default"]["courier"];
            $data["total_cart_price"] = ($data["total"] + $data["dc"][$dc_default_courier]["charge"]*1 + $data["offline_fee"]*1 - $promo_disc_amount);

            if($data["total_cart_price"] != 0)
            {
                $data["cart_profit_margin"] = (100-($costprice_total / $data["total_cart_price"] *100));  # (100 - (total_cost_price / total_selling_price)*100)
            }
            else
            {
                $data["cart_profit_margin"] = "*total cart price is zero*";
            }

            #SBF #2978 Disable negative offline_fee if cart_profit_margin < 7
            if($this->input->post("offline_fee") < 0 && $data["cart_profit_margin"] < 7)
            {
                $data["offline_fee"] = '';
            }
        }

        include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
        $data["lang"] = $lang;
        $data["notice"] = notice($lang);
        $data["pbv_obj"] = $pbv_obj;
        $data["default_curr"] = $data["pbv_obj"]->get_platform_currency_id();
        $data["platform_id"] = $platform_id;
        $this->load->view('order/phone_sales/phone_sales_cart_v', $data);
    }

    public function take_order($platform_id = "")
    {
        $sub_app_id = $this->_get_app_id()."00";
        $_SESSION["LISTPAGE"] = current_url()."?".$_SERVER['QUERY_STRING'];

        if ($platform_id == "")
        {
            show_404();
        }

        // echo "<pre>"; #var_dump($_POST); die();

        if ($this->input->post("posted"))
        {
            if (!$this->input->post("took"))
            {
                // var_dump($_POST["client"]["id"]); die();

                unset($_SESSION["NOTICE"]);
                if (empty($_POST["client"]["id"]))
                {

                    // echo "<pre>";
                    // var_dump($_POST); die();
                    // $_POST["so_extend"]["order_reason"]

                    if (!($client_obj = $this->client_service->get(array("email"=>$_POST["client"]["email"]))))
                    {
                        $client_obj = $this->client_service->get();
                        $_POST["client"]["password"] = (trim($_POST["client"]["password"]) != ""?$this->encrypt->encode(strtolower($_POST["client"]["password"])):$this->encrypt->encode(mktime()));
                        set_value($client_obj, $_POST["client"]);
                        $client_obj->set_mobile($_POST["client"]["mtel_1"].$_POST["client"]["mtel_2"].$_POST["client"]["mtel_3"]);

                        if($_POST["billaddr"] != 1)
                        {
                            $client_obj->set_del_name($_POST["client"]["title"]." ".$_POST["client"]["forename"]." ".$_POST["client"]["surname"]);
                            $client_obj->set_del_company($_POST["client"]["companyname"]);
                            $client_obj->set_del_address_1($_POST["client"]["address_1"]);
                            $client_obj->set_del_address_2($_POST["client"]["address_2"]);
                            $client_obj->set_del_city($_POST["client"]["city"]);
                            $client_obj->set_del_state($_POST["client"]["state"]);
                            $client_obj->set_del_postcode($_POST["client"]["postcode"]);
                            $client_obj->set_del_country_id($_POST["client"]["country_id"]);
                            //#2551 promotion issue since the PLATFORMCOUNTRYID is NOT accessable in non-public controller
                            $_SESSION["client"]["del_country_id"] = $_POST["client"]["country_id"];
                            $client_obj->set_del_mobile($_POST["client"]["mtel_1"].$_POST["client"]["mtel_2"].$_POST["client"]["mtel_3"]);
                            $client_obj->set_del_name($_POST["client"]["title"]." ".$_POST["client"]["forename"]." ".$_POST["client"]["surname"]);
                        }
                        else
                        {
                            $client_obj->set_del_mobile($_POST["client"]["del_mtel_1"].$_POST["client"]["del_mtel_2"].$_POST["client"]["del_mtel_3"]);
                            $client_obj->set_del_name($_POST["client"]["del_title"]." ".$_POST["client"]["del_forename"]." ".$_POST["client"]["del_surname"]);
                            //#2551
                            $_SESSION["client"]["del_country_id"] = $_POST["client"]["del_country_id"];
                        }
                        $client_obj->set_party_subscriber(0);
                        $client_obj->set_status(1);

                        if ($client_obj = $this->client_service->insert($client_obj))
                        {
                            $_POST["client"]["id"] = $client_obj->get_id();
                        }
                        else
                        {
                            $_SESSION["NOTICE"] = "ERROR ".__LINE__." : ".$this->db->_error_message();
                        }
                    }
                    else
                    {
                        $_POST["client"]["id"] = $client_obj->get_id();
                        $_POST["client"]["password"] = (trim($_POST["client"]["password"]) != ""?$this->encrypt->encode(strtolower($_POST["client"]["password"])):$this->encrypt->encode(mktime()));
                        set_value($client_obj, $_POST["client"]);
                        $client_obj->set_mobile($_POST["client"]["mtel_1"].$_POST["client"]["mtel_2"].$_POST["client"]["mtel_3"]);
                        if($_POST["billaddr"] != 1)
                        {
                            $client_obj->set_del_name($_POST["client"]["title"]." ".$_POST["client"]["forename"]." ".$_POST["client"]["surname"]);
                            $client_obj->set_del_company($_POST["client"]["companyname"]);
                            $client_obj->set_del_address_1($_POST["client"]["address_1"]);
                            $client_obj->set_del_address_2($_POST["client"]["address_2"]);
                            $client_obj->set_del_city($_POST["client"]["city"]);
                            $client_obj->set_del_state($_POST["client"]["state"]);
                            $client_obj->set_del_postcode($_POST["client"]["postcode"]);
                            $client_obj->set_del_country_id($_POST["client"]["country_id"]);
                            $client_obj->set_del_mobile($_POST["client"]["mtel_1"].$_POST["client"]["mtel_2"].$_POST["client"]["mtel_3"]);
                            $client_obj->set_del_name($_POST["client"]["title"]." ".$_POST["client"]["forename"]." ".$_POST["client"]["surname"]);
                        }
                        else
                        {
                            $client_obj->set_del_mobile($_POST["client"]["del_mtel_1"].$_POST["client"]["del_mtel_2"].$_POST["client"]["del_mtel_3"]);
                            $client_obj->set_del_name($_POST["client"]["del_title"]." ".$_POST["client"]["del_forename"]." ".$_POST["client"]["del_surname"]);
                        }
                        if($this->client_service->update($client_obj) === FALSE)
                        {
                            $_SESSION["NOTICE"] = "ERROR ".__LINE__." : ".$this->db->_error_message();
                        }
                    }
                }
                else
                {
                    $client_obj = $this->client_service->get(array("id"=>$_POST["client"]["id"]));
                    $_POST["client"]["password"] = (trim($_POST["client"]["password"]) != ""?$this->encrypt->encode(strtolower($_POST["client"]["password"])):$this->encrypt->encode(mktime()));
                    set_value($client_obj, $_POST["client"]);
                    $client_obj->set_mobile($_POST["client"]["mtel_1"].$_POST["client"]["mtel_2"].$_POST["client"]["mtel_3"]);
                    if($_POST["billaddr"] != 1)
                    {
                        $client_obj->set_del_name($_POST["client"]["title"]." ".$_POST["client"]["forename"]." ".$_POST["client"]["surname"]);
                        $client_obj->set_del_company($_POST["client"]["companyname"]);
                        $client_obj->set_del_address_1($_POST["client"]["address_1"]);
                        $client_obj->set_del_address_2($_POST["client"]["address_2"]);
                        $client_obj->set_del_city($_POST["client"]["city"]);
                        $client_obj->set_del_state($_POST["client"]["state"]);
                        $client_obj->set_del_postcode($_POST["client"]["postcode"]);
                        $client_obj->set_del_country_id($_POST["client"]["country_id"]);
                        $client_obj->set_del_mobile($_POST["client"]["mtel_1"].$_POST["client"]["mtel_2"].$_POST["client"]["mtel_3"]);
                        $client_obj->set_del_name($_POST["client"]["title"]." ".$_POST["client"]["forename"]." ".$_POST["client"]["surname"]);
                    }
                    else
                    {
                        $client_obj->set_del_mobile($_POST["client"]["del_mtel_1"].$_POST["client"]["del_mtel_2"].$_POST["client"]["del_mtel_3"]);
                        $client_obj->set_del_name($_POST["client"]["del_title"]." ".$_POST["client"]["del_forename"]." ".$_POST["client"]["del_surname"]);
                    }
                    if($this->client_service->update($client_obj) === FALSE)
                    {
                        $_SESSION["NOTICE"] = "ERROR ".__LINE__." : ".$this->db->_error_message();
                    }
                }

                if ($_POST["so_extend"]["order_reason"] == 31)
                {
                    #SBF #2450
                    // Auto update so_extend.notes (db) if "Bulk Sales" selected on phone_sales take order
                    // This note will appear under Order Quick Search 'Order Notes'
                    $_POST["so_extend"]["notes"] = "Bulk Sales";
                }

                if (empty($_SESSION["NOTICE"]))
                {
                    $vars = $_POST;
                    $vars["client"] = $client_obj;
                    $vars["platform_id"] = $platform_id;
                    $vars["biz_type"] = "offline";

                    if($this->input->post("vat_exempt"))
                    {
                        $vars["vat_exempt"] = 1;
                    }
                    if($this->input->post("free_delivery"))
                    {
                        $vars["free_delivery"] = 1;
                    }

                    if ($so_obj = $this->so_service->cart_to_so($vars))
                    {
                        unset($_SESSION["cart"]);
                        unset($_SESSION["cart_type"]);
                        unset($_SESSION["client"]);
                        unset($_SESSION["promotion_code"]);
                        $_SESSION["DISPLAY"] = array($so_obj->get_so_no()." Created Success", "success");
                        echo "<script>top.window.location.href='".base_url()."order/phone_sales';</script>";
                        exit;
                    }
                }
            }

            if ($this->input->post("promotion_code"))
            {
                $_SESSION["promotion_code"] = $this->input->post("promotion_code");
            }
            else
            {
                unset($_SESSION["promotion_code"]);
            }

        }

        if($this->input->post('clientid'))
        {
            $_SESSION["client"]["country_id"] = $this->input->post("country");
            $_SESSION["client"]["del_country_id"] = $this->input->post("country");
            if ($this->input->post('clientid'))
            {
                $client_obj = $this->client_service->get(array("id"=>$this->input->post('clientid')));
                $client_obj->set_country_id($_SESSION["client"]["country_id"]);
                $client_obj->set_del_country_id($_SESSION["client"]["del_country_id"]);
                $client = obj_to_query($client_obj);
            }
        }

        $pbv_obj = $this->platform_biz_var_service->get(array("selling_platform_id"=>$platform_id));

        if($this->input->post("country") != "")
        {
            $country_id = $this->input->post("country");
        }
        elseif($_SESSION["client"]["country_id"])
        {
            $country_id = $_SESSION["client"]["country_id"];
        }
        else
        {
            $country_id = $pbv_obj->get_platform_country_id();
        }

        $this->cart_session_service->set_del_country_id($country_id);
        $data = $this->cart_session_service->get_detail($platform_id, 1, 0, 0, 0, $this->input->post("vat_exempt"), $this->input->post("free_delivery"));
        $data["country_id"] = $country_id;

        if (!$data["promo"]["valid"] || $data["promo"]["error"])
        {
            unset($_SESSION["promotion_code"]);
        }

        $data["vat_exempt"] = $this->input->post("vat_exempt");
        $data["free_delivery"] = $this->input->post("free_delivery");

        if(count($data["cart"]) == 0)
        {
            unset($_SESSION["client"]["country_id"]);
            unset($_SESSION["client"]["del_country_id"]);
        }
        $data["order_reason_list"] = $this->order_reason_service->get_list(array("status" => 1, "option_in_phone" => 1), array("limit" => -1, "orderby" => "priority"));
        include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
        $data["country_list"] =  $this->region_service->get_sell_country_list();
        $data["lang"] = $lang;
        $data["client"] = $client;
        $data["notice"] = notice($lang);
        $data["pbv_obj"] = $pbv_obj;
        $data["state_list"] = $this->country_state_service->get_list(array("country_id"=>"US"));
        $data["default_curr"] = $data["pbv_obj"]->get_platform_currency_id();
        $this->load->view('order/phone_sales/phone_sales_take_order_v', $data);
    }

    public function check_email($email="", $platform_country="")
    {
        if ($email)
        {
            $sub_app_id = $this->_get_app_id()."00";
            $_SESSION["LISTPAGE"] = current_url()."?".$_SERVER['QUERY_STRING'];
            include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
            $data["lang"] = $lang;
            $data["client"] = $this->client_service->get(array("email"=>$email));
            $data["country"] = $platform_country;
            $this->load->view('order/phone_sales/phone_sales_check_email_v', $data);
        }
        else
        {
            show_404();
        }
    }

    public function on_hold()
    {
        $sub_app_id = $this->_get_app_id()."01";
        $_SESSION["LISTPAGE"] = current_url()."?".$_SERVER['QUERY_STRING'];

        if ($this->input->post("posted"))
        {
            if ($so_obj = $this->so_service->get(array("so_no"=>$this->input->post("so_no"))))
            {
                $status = $this->input->post("status");

                if ($status == 2)
                {
                    $platform_obj = $this->so_service->get_pbv_srv()->get_dao()->get(array("selling_platform_id"=>$so_obj->get_platform_id()));
                    $so_obj->set_expect_delivery_date(date("Y-m-d H:i:s", time()+$platform_obj->get_latency_in_stock()*86400));

                    $action = "update";
                    $socc_obj = $this->so_service->get_socc_dao()->get(array("so_no"=>$this->input->post("so_no")));
                    if(!$socc)
                    {
                        $socc_obj = $this->so_service->get_socc_dao()->get();
                        $action = "insert";
                        $socc_obj->set_so_no($this->input->post("so_no"));
                        $socc_obj->set_fd_proc_status(0);
                        $socc_obj->set_fd_status(0);
                    }
                    $socc_obj->set_t3m_is_sent('N');
                    $socc_obj->set_t3m_in_file('');
                    $socc_obj->set_t3m_result('');

                    $sops_dao = $this->so_service->get_sops_dao();
                    $sops_obj = $sops_dao->get(array("so_no"=>$this->input->post("so_no")));
                    if(!$sops_obj)
                    {
                        $sops_obj = $sops_dao->get();
                        $sops_obj->set_so_no($this->input->post("so_no"));
                        $sops_action = 'insert_sops';
                    }
                    else
                    {
                        $sops_action = 'update_sops';
                    }
                    $sops_obj->set_pay_to_account($this->input->post("pay_to_account"));
                    $sops_obj->set_payment_gateway_id($this->input->post("payment_gateway"));
                    $sops_obj->set_payment_status("S");
                    $sops_obj->set_pay_date(date("Y-m-d H:i:s"));
                    // var_dump($sops_obj);die();
                    $this->so_service->$sops_action($sops_obj);

                    //#2713 add offline_fee, update offline fee when a value is provide
                    if($this->input->post("offline_fee")!=="" && is_numeric($this->input->post("offline_fee")))
                    {
                        $soext_obj = $this->so_service->get_soext_dao()->get(array("so_no"=>$this->input->post("so_no")));
                        if($soext_obj)
                        {
                            $old_offline_fee = $soext_obj->get_offline_fee();
                            $new_offline_fee = trim($this->input->post("offline_fee"));

                            $soext_obj->set_offline_fee($new_offline_fee);
                            $this->so_service->get_soext_dao()->update($soext_obj);
                            //insert order note record and update the amount field in so

                            //first get the original order amount
                            $original_amount = $so_obj->get_amount();
                            //set the new amount = original_amount + offline_fee
                            $offline_fee_changed = $new_offline_fee - $old_offline_fee;

                            $new_amount = $original_amount + $offline_fee_changed;
                            $so_obj->set_amount($new_amount);

                            $son_obj = $this->so_service->get_son_dao()->get();
                            $son_obj->set_so_no($this->input->post("so_no"));

                            $new_amount = number_format($new_amount, 2, '.', '');
                            $note = "original amount: $original_amount, amend amount: {$new_amount}";
                            $son_obj->set_note($note);
                            $son_obj = $this->so_service->get_son_dao()->insert($son_obj);
                        }
                    }
                }
                else
                {
                    $this->so_service->get_socc_dao()->q_delete(array("so_no"=>$this->input->post("so_no")));
                }
                $so_obj->set_txn_id($this->input->post("txn_id"));
                $so_obj->set_status($status);

                if (!$this->so_service->update($so_obj))
                {
                    $_SESSION["NOTICE"] = "ERROR ".__LINE__." : ".$this->db->_error_message();
                }
                else
                {
                    if ($status == 2)
                    {
//send order confirmation email
                        $this->pmgw->so = $so_obj;
                        $this->pmgw->fire_success_event();
                        mail("compliance@valuebasket.com", '[VB] phone sales order move to cc so_no - ' . $so_obj->get_so_no(), $so_obj->get_so_no(), 'From: website@valuebasket.com');
                    }
                    if($action != "" && !$this->so_service->get_socc_dao()->$action($socc_obj))
                    {
                        $_SESSION["NOTICE"] = "ERROR ".__LINE__." : ".$this->db->_error_message();
                    }
                    else
                    {
                        redirect($_SESSION["LISTPAGE"]);
                    }
                }
            }
            else
            {
                $_SESSION["NOTICE"] = "ERROR ".__LINE__." : ".$this->db->_error_message();
            }
        }


        $where = array();
        $option = array();

        if ($this->input->get("so_no") != "")
        {
            $where["so.so_no"] = trim($this->input->get("so_no"));
        }

        if ($this->input->get("email") != "")
        {
            $where["c.email LIKE "] = "%".$this->input->get("email")."%";
        }

        if ($this->input->get("delivery_charge"))
        {
            fetch_operator($where, "so.delivery_charge", $this->input->get("delivery_charge"));
        }

        if ($this->input->get("offline_fee"))
        {
            fetch_operator($where, "soe.offline_fee", $this->input->get("offline_fee"));
        }

        if ($this->input->get("amount"))
        {
            fetch_operator($where, "so.amount", $this->input->get("amount"));
        }

        $where["biz_type"] = "OFFLINE";
        $where["so.status"] = "1";
        $where["so.hold_status"] = "0";
        $option["so_item"] = "1";
        $option["hide_payment"] = "1";

        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        $limit = '20';

        $pconfig['base_url'] = $_SESSION["LISTPAGE"];
        $option["limit"] = $pconfig['per_page'] = $limit;
        if ($option["limit"])
        {
            $option["offset"] = $this->input->get("per_page");
        }

        if (empty($sort))
            $sort = "so_no";

        if (empty($order))
            $order = "desc";

        $option["orderby"] = $sort." ".$order;
        $option["notes"] = TRUE;
        $option["extend"] = TRUE;

        if (isset($_GET["so_no"]) || isset($_GET["email"]) || isset($_GET["delivery_charge"]) || isset($_GET["offline_fee"]) || isset($_GET["amount"]))
        {
            $data["objlist"] = $this->so_service->get_dao()->get_list_w_name($where, $option);
            $data["total"] = $this->so_service->get_dao()->get_list_w_name($where, array("notes"=>1, "extend"=>1, "num_rows"=>1));
        }
        include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
        $data["lang"] = $lang;

        $pconfig['total_rows'] = $data['total'];
        $this->pagination_service->set_show_count_tag(TRUE);
        $this->pagination_service->initialize($pconfig);

        $data["notice"] = notice($lang);

        $data["sortimg"][$sort] = "<img src='".base_url()."images/".$order.".gif'>";
        $data["xsort"][$sort] = $order=="asc"?"desc":"asc";
        $data["searchdisplay"] = "";

        $this->load->view('order/phone_sales/phone_sales_on_hold_v', $data);
    }

    public function pending()
    {
//SBF#1855, compliance don't use pending area any more, order will go to CC page directly instead
        exit;
        $sub_app_id = $this->_get_app_id()."02";
        $_SESSION["LISTPAGE"] = current_url()."?".$_SERVER['QUERY_STRING'];

        if ($this->input->post("posted"))
        {
            if ($type = $this->input->post("type"))
            {
                $this->so_service->get_dao()->trans_start();
                if ($so_obj = $this->so_service->get(array("so_no"=>$this->input->post("so_no"))))
                {
                    $extends = 1;
                    switch ($type)
                    {
                        case "b":
                            $so_obj->set_status('1');
                            $this->so_service->get_socc_dao()->q_delete(array("so_no"=>$this->input->post("so_no")));
                            break;
                        case "c":
                            switch($this->input->post('hold_reason'))
                            {
                                case 'cscc':
                                case 'csvv':
                                    $this->credit_check_model->fire_cs_request($this->input->post("so_no"),$this->input->post("hold_reason"));
                                    $sohr_obj = $this->so_service->get_sohr_dao()->get();
                                    $sohr_obj->set_so_no($this->input->post("so_no"));
                                    $sohr_obj->set_reason($this->input->post("hold_reason"));
                                    $extends = $this->so_service->get_sohr_dao()->insert($sohr_obj);
                                    $so_obj->set_hold_status('1');
                                    break;
                                case 'confirm_fraud':
                                    $socc_obj = $this->so_service->get_socc_dao()->get(array("so_no"=>$this->input->post("so_no")));
                                    $socc_obj->set_fd_status(2);
                                    $extends = $this->so_service->get_socc_dao()->update($socc_obj);
                                    $sohr_obj = $this->so_service->get_sohr_dao()->get();
                                    $sohr_obj->set_so_no($this->input->post("so_no"));
                                    $sohr_obj->set_reason($this->input->post("hold_reason"));
                                    $extends = $extends && $this->so_service->get_sohr_dao()->insert($sohr_obj);
                                    $so_obj->set_status(0);
                                    break;
                            }
                            break;
                        case "p":
                        case "pe":
                            $so_obj->set_status('3');
                            if (($promo_code = $so_obj->get_promotion_code()) != "")
                            {
                                $this->promotion_code_service->promo_code = $promo_code;
                                $this->promotion_code_service->update_no_taken();
                            }
                            break;
                    }
                    if (!$this->so_service->update($so_obj) || !$extends)
                    {
                        $_SESSION["NOTICE"] = "ERROR ".__LINE__." : ".$this->db->_error_message();
                        $this->so_service->get_dao()->trans_complete();
                    }
                    else
                    {
                        $this->so_service->get_dao()->trans_complete();
                        if($type == "pe")
                        {
                            $this->pmgw->so = $so_obj;
                            $this->pmgw->fire_success_event();
                        }

                        redirect($_SESSION["LISTPAGE"]);
                    }
                }
                else
                {
                    $_SESSION["NOTICE"] = "ERROR ".__LINE__." : ".$this->db->_error_message();
                }
            }
        }


        $where = array();
        $option = array();

        if ($this->input->get("so_no") != "")
        {
            $where["so.so_no LIKE "] = "%".$this->input->get("so_no")."%";
        }

        if ($this->input->get("email") != "")
        {
            $where["c.email LIKE "] = "%".$this->input->get("email")."%";
        }

        if ($this->input->get("delivery_charge"))
        {
            fetch_operator($where, "so.delivery_charge", $this->input->get("delivery_charge"));
        }

        if ($this->input->get("offline_fee"))
        {
            fetch_operator($where, "soe.offline_fee", $this->input->get("offline_fee"));
        }

        if ($this->input->get("amount"))
        {
            fetch_operator($where, "so.amount", $this->input->get("amount"));
        }

        $where["biz_type"] = "OFFLINE";
        $where["so.status"] = "2";
        $where["so.hold_status"] = "0";
        $option["so_item"] = "1";
        $option["hide_payment"] = "1";

        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        $limit = '20';

        $pconfig['base_url'] = $_SESSION["LISTPAGE"];
        $option["limit"] = $pconfig['per_page'] = $limit;
        if ($option["limit"])
        {
            $option["offset"] = $this->input->get("per_page");
        }

        if (empty($sort))
            $sort = "so_no";

        if (empty($order))
            $order = "desc";

        $option["orderby"] = $sort." ".$order;
        $option["notes"] = TRUE;
        $option["extend"] = TRUE;
        $option["credit_chk"] = TRUE;
        $option["hide_client"] = FALSE;

        $data["objlist"] = $this->so_service->get_dao()->get_list_w_name($where, $option);
        $data["total"] = $this->so_service->get_dao()->get_list_w_name($where, array("notes"=>1, "extend"=>1, "num_rows"=>1, "credit_chk"=>1));

        include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
        $data["lang"] = $lang;

        $pconfig['total_rows'] = $data['total'];
        $this->pagination_service->set_show_count_tag(TRUE);
        $this->pagination_service->initialize($pconfig);

        $data["notice"] = notice($lang);

        $data["sortimg"][$sort] = "<img src='".base_url()."images/".$order.".gif'>";
        $data["xsort"][$sort] = $order=="asc"?"desc":"asc";
        $data["searchdisplay"] = "";

        $this->load->view('order/phone_sales/phone_sales_pending_v', $data);
    }

    public function _get_app_id(){
        return $this->app_id;
    }

    public function _get_lang_id(){
        return $this->lang_id;
    }
}

/* End of file product.php */
/* Location: ./system/application/controllers/product.php */