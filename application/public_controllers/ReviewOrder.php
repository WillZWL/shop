<?php

class ReviewOrder extends PUB_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('website/cart_session_model');
        $this->load->model('website/checkout_model');
        $this->load->library('service/context_config_service');
        $this->load->library('service/complementary_acc_service');
        $this->load->library('service/affiliate_service');
        $this->load->library('service/tradedoubler_tracking_script_service');
    }

    public function index()
    {
        $data['cart_info'] = $this->cart_session_model->get_cart_info();
        var_dump($data['cart_info']);
        $this->load->view('/default/review', $data);
//        $data['data']['lang_text'] = $this->_get_language_file('', '', 'index');
//        $item_status = $this->input->get('item_status');
//        $removed_sku = $this->input->get('not_valid_sku');
//        $need_restriction_message = false;
//
//        $this->affiliate_service->add_af_cookie($_GET);
//        if (isset($item_status) && isset($removed_sku)) {
//            require_once(BASEPATH . 'plugins/My_plugin/validator/digits_validator.php');
//            $digits_validator_allow_empty = new Digits_validator(array("allow_empty" => true));
//
//            require_once(BASEPATH . 'plugins/My_plugin/validator/regex_validator.php');
//            $regex_validator = new Regex_validator(Regex_validator::REGX_SKU_FORMAT);
//
//            if ($digits_validator_allow_empty->is_valid($item_status) && $regex_validator->is_valid($removed_sku)) {
//                if (($item_status == Cart_session_service::NOT_ALLOW_PREORDER_ARRIVING_ITEM_AFTER_NORMAL_ITEM)
//                    || ($item_status == Cart_session_service::NOT_ALLOW_NORMAL_ITEM_AFTER_PREORDER_ARRIVING_ITEM)
//                    || ($item_status == Cart_session_service::DIFFERENT_PREORDER_ITEM)
//                    || ($item_status == Cart_session_service::DIFFERENT_ARRIVING_ITEM)
//                ) {
//                    $need_restriction_message = true;
//                }
//            }
//        }
//
//        if (!$need_restriction_message) {
//            $data['data']['lang_text']['restriction'] = "";
//        }
//
//        if (isset($_POST["promotion_code"])) {
//            if ($this->input->post("promotion_code")) {
//                $_SESSION["promotion_code"] = $_POST["promotion_code"];
//                $email = $this->input->post("email");
//                $_SESSION["POSTFORM"]["email"] = $email;
//                $this->cart_session_service->set_email($email);
//            } else {
//                unset($_SESSION["promotion_code"]);
//            }
//        } elseif (isset($_POST["del_country_id"])) {
//            $_SESSION["POSTFORM"] = $_POST;
//        }
//
//        $result = $this->cart_session_model->get_detail(PLATFORMID);
//        $item_total = 0;
//
//        // SBF #3249, Comment out this GST function (#2236)
//        $data["need_gst_display"] = FALSE;
//        $data["gst_order"] = FALSE;
//        $gst_total = 0;
//
//        $data["allow_bulk_sales"] = array();
//
//        $index = 0;
//        foreach ($result["cart"] AS $key => $val) {
//
//            #sbf #3746 don't show if it's a complementary accessory
//            $is_complementary_acc = $this->complementary_acc_service->check_cat($val["sku"], true);
//            if ($is_complementary_acc["status"] !== true) {
//                if ($key < 2) {
//                    $i = $key + 1;
//                    $criteo_tag .= '&i' . $i . '=' . $val["sku"] . '&p' . $i . '=' . $val["price"] . '&q' . $i . '=' . $val["qty"];
//                }
//
//                $item_total += $val["total"];
//
//
//                $data["cart"][$val["sku"]][$index]["prod_name"] = $val["name"];
//                $data["cart"][$val["sku"]][$index]["price"] = platform_curr_format(PLATFORMID, $val["price"]);
//                $data["cart"][$val["sku"]][$index]["qty"] = $val["qty"];
//
//                if ($val['promo'] != 1) {
//                    $data["cart"][$val["sku"]][$index]["increase_url"] = base_url() . "review_order/update/" . $val["sku"] . "/" . ($val["qty"] + 1);
//                    $data["cart"][$val["sku"]][$index]["remove_url"] = base_url() . "review_order/remove/" . $val["sku"];
//                }
//
//                if ($val["qty"] - 1 > 0 && ($val['promo'] != 1)) {
//                    $data["cart"][$val["sku"]][$index]["decrease_url"] = base_url() . "review_order/update/" . $val["sku"] . "/" . ($val["qty"] - 1);
//                }
//                $data["cart"][$val["sku"]][$index]["sub_total"] = platform_curr_format(PLATFORMID, $val["total"]);
//                $data["cart"][$val["sku"]][$index]["prod_url"] = $this->cart_session_model->get_prod_url($val["sku"]);
//
//                $index++;
//                #           SBF #2441 Pop-up for contact us if bulk sales criteria met
//                if ($val["qty"] >= 10 && $val["total"] >= 5000) {
//                    $data["allow_bulk_sales"][] = '1';  #meets bulk sale criteria - show popup to contact us
//                } else {
//                    $data["allow_bulk_sales"][] = '0';
//                }
//            }
//        }
//
//        $data["delivery_charge"] = platform_curr_format(PLATFORMID, $result["dc_default"]["charge"]);
//        $data["item_amount"] = platform_curr_format(PLATFORMID, $item_total);
//        $data["gst_total"] = platform_curr_format(PLATFORMID, $gst_total);
//        $total = $item_total - $result["dc_default"]["charge"] * 1;
//        if ($result['promo']['disc_amount']) {
//            $total = $item_total - $result['promo']['disc_amount'];
//        }
//        $data["total"] = platform_curr_format(PLATFORMID, $total + $gst_total);
//        $data['promo'] = $result['promo'];
//        //$cart_list = unserialize(base64_decode($_COOKIE["chk_cart"]));
//        //include_once(APPPATH."libraries/service/cart_session_service.php");
//        //$cart_srv = new Cart_session_service();
//        //$rs = $cart_srv->check_cart($cart_list, PLATFORMID, get_lang_id(), isset($_COOKIE["renew_cart"]) && $_COOKIE["renew_cart"]);
//        // var_dump($cart_list);
//        //atom tracking info
//        foreach ($result["cart"] as $key => $cart_obj) {
//            #sbf #3746 don't show if it's a complementary accessory
//            $is_complementary_acc = $this->complementary_acc_service->check_cat($cart_obj["sku"], true);
//            if ($is_complementary_acc["status"] !== true) {
//                $tracking_list["sku"] = $cart_obj["sku"];
//                $tracking_list["unit_price"] = $cart_obj["price"];
//                $tracking_list["currency"] = $cart_obj["product_cost_obj"]->get_platform_currency_id();
//                $tracking_list["product_name"] = $cart_obj["name"];
//                $tracking_list["qty"] = $cart_obj["qty"];
//                $tracking_list["total"] = $cart_obj["total"];
//                $tracking_products[] = $tracking_list;
//            }
//        }
//        $data["tracking_data"]["products"] = $tracking_products;
//        $data["tracking_data"]["total_amount"] = $total + $gst_total;
//
//#        SBF #2284 Tradedoubler variable js portion
//        $this->tradedoubler_tracking_script_service->set_country_id(PLATFORMCOUNTRYID);
//        $param_list = array();
//        foreach ($result["cart"] as $key => $cart_obj) {
//            #sbf #3746 don't show if it's a complementary accessory
//            $is_complementary_acc = $this->complementary_acc_service->check_cat($cart_obj["sku"], true);
//            if ($is_complementary_acc["status"] !== true) {
//                $param_list["id"] = $cart_obj["sku"];
//                $param_list["price"] = $cart_obj["price"];
//                $param_list["currency"] = array_shift(array_keys($_SESSION["CURRENCY"]));
//                $param_list["name"] = $cart_obj["name"];
//                $param_list["qty"] = $cart_obj["qty"];
//                $product_list[] = $param_list;
//            }
//        }
//        $td_variable_code = $this->tradedoubler_tracking_script_service->get_variable_code("basket", $product_list, "");
//
//        // meta info
//        $enable_mediaforge_country = array('GB', 'AU', 'FR', 'ES');
//        if (in_array(PLATFORMCOUNTRYID, $enable_mediaforge_country)) {
//#           mediaforge - added by SBF#1902
//            $enable_mediaforge = true;
//            if ($enable_mediaforge) {
//                if (PLATFORMCOUNTRYID == 'GB') $account_no = 1038;
//                if (PLATFORMCOUNTRYID == 'AU') $account_no = 1059;
//                if (PLATFORMCOUNTRYID == 'FR') $account_no = 1411; #SBF#2229
//                if (PLATFORMCOUNTRYID == 'ES') $account_no = 1519; #SBF#2404
//            }
//        }
//
//        $data["show_battery_message_w_amount"] = $this->cart_session_model->cart_session_service->check_battery_inside_cart_valid_or_not($total, PLATFORMID);
//        // $this->load_tpl('content', 'tbs_review_order', $data, TRUE, TRUE);
    }

    /*************************************
     *   we don't use remove.ini, because there is no template file, use index.ini directly instead
     ***************************************/
    public function remove($sku = "")
    {
        if ($sku != "") {
            $this->cart_session_model->remove($sku, PLATFORMID);
        }
        $this->index();
    }

    /*************************************
     *   we don't use update.ini, because there is no template file, use index.ini directly instead
     ***************************************/
    public function update($sku = "", $qty = "")
    {
        $allow_result = $this->cart_session_model->cart_session_service->is_allow_to_add($sku, 1, PLATFORMID);
        if ($allow_result <= Cart_session_service::DECISION_POINT) {
            if ($sku != "" && $qty != "") {
                $this->cart_session_model->update($sku, $qty, PLATFORMID);
            }
            $this->index();
        } else {
            redirect(base_url() . "review_order?item_status=" . $allow_result . "&not_valid_sku=" . $sku);
        }
    }
}
