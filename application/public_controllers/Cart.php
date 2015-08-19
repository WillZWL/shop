<?php
<<<<<<< HEAD
defined('BASEPATH') OR exit('No direct script access allowed');
=======
use AtomV2\Models\Website\CartSessionModel;
>>>>>>> 29ccc5cb624371694b2aa3dd7b3ed841fcd15669

Class Cart extends PUB_Controller
{
    public function __construct()
    {
        parent::__construct();
<<<<<<< HEAD
        $this->load->library('template');
        $this->load->helper('url');
        $this->load->model('website/cart_session_model');
        $this->load->model('marketing/upselling_model');
        $this->load->library('service/affiliate_service');
=======
        $this->cart_session_model = new CartSessionModel;
>>>>>>> 29ccc5cb624371694b2aa3dd7b3ed841fcd15669
    }

    public function ajax_add_item()
    {
        $sku = $this->input->post('sku');
        $qty = $this->input->post('qty') ? $this->input->post('qty') : 1;
        $this->add_item_qty($sku, $qty);
        $return['redirect'] = '/review_order';

        echo json_encode($return);
    }

<<<<<<< HEAD
    public function add_item_qty($sku = "", $qty = 0, $quiet_return = false)
    {
       // $data['data']['lang_text'] = $this->_get_language_file('', '', 'add_item_qty');

        $listing_status = array(
=======
    public function addItemQty($sku = "", $qty = 0, $quiet_return = false)
    {
        $listing_status = [
>>>>>>> 29ccc5cb624371694b2aa3dd7b3ed841fcd15669
            "I" => $data['data']['lang_text']['status_in_stock'],
            "O" => $data['data']['lang_text']['status_out_stock'],
            "P" => $data['data']['lang_text']['status_pre_order'],
            "A" => $data['data']['lang_text']['status_arriving']
<<<<<<< HEAD
        );

        $foo = [];

        //$this->affiliate_service->add_af_cookie($_GET);

        $allow_result = $this->cart_session_model->cart_session_service->is_allow_to_add($sku, $qty, PLATFORMID);

        if ($allow_result <= Cart_session_service::DECISION_POINT) {
            $result = $this->cart_session_model->add($sku, $qty, PLATFORMID);
=======
        ];

        $allow_result = $this->cart_session_model->cart_session_service->isAllowToAdd($sku, $qty, PLATFORM);
        if ($allow_result <= \AtomV2\Service\CartSessionService::DECISION_POINT) {
            $result = $this->cart_session_model->addItemQty($sku, $qty, PLATFORM);
>>>>>>> 29ccc5cb624371694b2aa3dd7b3ed841fcd15669
        }

        //if (($allow_result == Cart_session_service::ALLOW_AND_IS_PREORDER)
        //    || ($allow_result == Cart_session_service::ALLOW_AND_IS_ARRIVING)
        //    || ($allow_result == Cart_session_service::SAME_PREORDER_ITEM)
        //    || ($allow_result == Cart_session_service::SAME_ARRIVING_ITEM)
        //) {
        //    redirect(base_url() . "review_order");
        //}

        if ($this->upselling_model->get_ra($data, $sku, PLATFORMID, $this->get_lang_id(), $listing_status)) {
            // TODO
            //
            // $this->template->add_title($data['data']['lang_text']['meta_title'].$data["prod_name"]. ' | ValueBasket');
            // $this->template->add_meta(array('name'=>'description','content'=>$data['data']['lang_text']['meta_desc']));
            // $this->template->add_meta(array('name'=>'keywords','content'=>$data['data']['lang_text']['meta_keyword']));
            // $this->template->add_js("/js/common.js");
            // $this->template->add_js("/resources/js/jquery.gritter.js");
            // $this->template->add_css("resources/css/jquery.gritter.css");
            // $this->template->add_js("/js/upselling.js", "import", TRUE);
            // $this->load_tpl('content', 'tbs_cart', $data, TRUE);
        } else {
            // redirect(base_url()."review_order");
        }

        return $result;
    }

    public function info()
    {
        $data['cart_info'] = $this->cart_session_model->get_cart_info();
        $this->load->view('/default/cart/info', $data);
    }

    public function add_item($sku = "")
    {
        $this->add_item_qty($sku, 1);
    }

    public function checkout_item($sku = "", $nextpage = "checkout")
    {
        if (empty($sku)) {
            $sku = $_REQUEST["sku"];
        }

        if (!empty($sku)) {
<<<<<<< HEAD
            if (!($chk_cart = $this->cart_session_model->add($sku, 1, PLATFORMID))) {
=======
            if (!($chk_cart = $this->cart_session_model->add($sku, 1, PLATFORM))) {
>>>>>>> 29ccc5cb624371694b2aa3dd7b3ed841fcd15669
                show_error("Product not found", "Product not found");
            }
        } else {
            show_404();
        }
        if ($nextpage == "prod") {
            $prod_obj = $this->product_model->get("product", array("sku" => $sku));
            redirect(base_url() . str_replace(" ", "-", parse_url_char($prod_obj->get_name())) . "/" . "mainproduct/view/" . $sku);
        } else {
            redirect(base_url() . "review_order");
        }
    }

    public function ajax_add_cart($parent_sku = "", $sku = "")
    {
        $data['data']['lang_text'] = $this->_get_language_file();

        $success = 0;
        $prod_obj = $this->product_model->get("product", array("sku" => $sku));
        if (!$prod_cont_obj = $this->product_model->get_product_content(array("prod_sku" => $sku, "lang_id" => get_lang_id()))) {
            $prod_cont_obj = $this->product_model->get_product_content(array("prod_sku" => $sku, "lang_id" => "en"));
        }

        if ($prod_cont_obj) {
            $prod_name = $prod_cont_obj->get_prod_name();
        } else {
            $prod_name = $prod_obj->get_name();
        }

        $image_url = get_image_file($prod_obj->get_image(), "m", $sku);

        if (!empty($sku)) {
<<<<<<< HEAD
            if ($chk_cart = $this->cart_session_model->add($sku, 1, PLATFORMID)) {
=======
            if ($chk_cart = $this->cart_session_model->add($sku, 1, PLATFORM)) {
>>>>>>> 29ccc5cb624371694b2aa3dd7b3ed841fcd15669
                $success = 1;
            }
        }

<<<<<<< HEAD
        $cart_info = $this->cart_session_model->get_detail(PLATFORMID);
=======
        $cart_info = $this->cart_session_model->get_detail(PLATFORM);
>>>>>>> 29ccc5cb624371694b2aa3dd7b3ed841fcd15669
        $cart["total"] = $cart["item"] = 0;
        if ($cart_info["cart"]) {
            foreach ($cart_info["cart"] AS $key => $arr) {
                $cart["total"] += $arr["total"];
                $cart["item"] += $arr["qty"];
            }
        }
        $cart["total"] = platform_curr_format(PLATFORMID, $cart["total"]);

        if ($success) {
            $_SESSION["ra_items"][PLATFORMID][$parent_sku][$sku] = 1;
            $add_text = $data['data']['lang_text']['cart_add'];
            echo $add_text . "||" . $prod_name . "||" . base_cdn_url() . $image_url . "||" . $cart["total"] . "||" . $cart["item"];
        } else {
            $not_avail_text = $data['data']['lang_text']['cart_not_avail'];
            echo $not_avail_text . "||" . $prod_name . "||" . base_cdn_url() . $image_url . "||" . $cart["total"] . "||" . $cart["item"];
        }
    }

    public function ajax_update_warranty($parent_sku = "", $sku = "")
    {
        $data['data']['lang_text'] = $this->_get_language_file();

        $success = 0;
        $prod_obj = $this->product_model->get("product", array("sku" => $sku));

        if (empty($sku) || isset($_SESSION["warranty"][PLATFORMID][$parent_sku])) {
            $old_warranty = $_SESSION["warranty"][PLATFORMID][$parent_sku];

            if (!empty($old_warranty)) {
                $cur_cart_qty = $_SESSION["cart"][PLATFORMID][$old_warranty];
                $new_qty = $cur_cart_qty * 1 - 1;
                if ($new_qty <= 0) {
                    if ($chk_cart = $this->cart_session_model->remove($old_warranty)) {
                        $success = 1;
                    }
                } else {
<<<<<<< HEAD
                    if ($chk_cart = $this->cart_session_model->update($old_warranty, $new_qty, PLATFORMID)) {
=======
                    if ($chk_cart = $this->cart_session_model->update($old_warranty, $new_qty, PLATFORM)) {
>>>>>>> 29ccc5cb624371694b2aa3dd7b3ed841fcd15669
                        $success = 1;
                    }
                }
            }
        }

        if (!empty($sku)) {
<<<<<<< HEAD
            if ($chk_cart = $this->cart_session_model->add($sku, 1, PLATFORMID)) {
=======
            if ($chk_cart = $this->cart_session_model->add($sku, 1, PLATFORM)) {
>>>>>>> 29ccc5cb624371694b2aa3dd7b3ed841fcd15669
                $success = 1;
            }
        }

<<<<<<< HEAD
        $cart_info = $this->cart_session_model->get_detail(PLATFORMID);
=======
        $cart_info = $this->cart_session_model->get_detail(PLATFORM);
>>>>>>> 29ccc5cb624371694b2aa3dd7b3ed841fcd15669
        $cart["total"] = $cart["item"] = 0;
        if ($cart_info["cart"]) {
            foreach ($cart_info["cart"] AS $key => $arr) {
                $cart["total"] += $arr["total"];
                $cart["item"] += $arr["qty"];
            }
        }
        $cart["total"] = platform_curr_format(PLATFORMID, $cart["total"]);

        if ($success) {
            $_SESSION["warranty"][PLATFORMID][$parent_sku] = $sku;
            echo $sku;
            //echo $sku."||".$prod_obj->get_name();
            //echo $add_text."||".$prod_obj->get_name()."||".base_cdn_url().$image_url."||".$cart["total"]."||".$cart["item"];
        }
    }

    public function ajax_remove_cart($parent_sku = "", $sku = "")
    {
        //$data['data']['lang_text'] = $this->_get_language_file();

        //$success = 0;
        //$prod_obj = $this->product_model->get("product", array("sku" => $sku));
        //if (!$prod_cont_obj = $this->product_model->get_product_content(array("prod_sku" => $sku, "lang_id" => get_lang_id()))) {
        //    $prod_cont_obj = $this->product_model->get_product_content(array("prod_sku" => $sku, "lang_id" => "en"));
        //}
        //
        //if ($prod_cont_obj) {
        //    $prod_name = $prod_cont_obj->get_prod_name();
        //} else {
        //    $prod_name = $prod_obj->get_name();
        //}
        //$image_url = get_image_file($prod_obj->get_image(), "m", $sku);

        if (!empty($sku)) {
            if (is_array($_SESSION["cart"][PLATFORMID][$sku])) {
                $cur_cart_qty = $_SESSION["cart"][PLATFORMID][$sku]["qty"];
            } else {
                $cur_cart_qty = $_SESSION["cart"][PLATFORMID][$sku];
            }
            $new_qty = $cur_cart_qty * 1 - 1;
            if ($new_qty <= 0) {
                if ($chk_cart = $this->cart_session_model->remove($sku)) {
                    $success = 1;
                }
            } else {
<<<<<<< HEAD
                if ($chk_cart = $this->cart_session_model->update($sku, $new_qty, PLATFORMID)) {
=======
                if ($chk_cart = $this->cart_session_model->update($sku, $new_qty, PLATFORM)) {
>>>>>>> 29ccc5cb624371694b2aa3dd7b3ed841fcd15669
                    $success = 1;
                }
            }
        }

<<<<<<< HEAD
        $cart_info = $this->cart_session_model->get_detail(PLATFORMID);
=======
        $cart_info = $this->cart_session_model->get_detail(PLATFORM);
>>>>>>> 29ccc5cb624371694b2aa3dd7b3ed841fcd15669
        $cart["total"] = $cart["item"] = 0;
        if ($cart_info["cart"]) {
            foreach ($cart_info["cart"] AS $key => $arr) {
                $cart["total"] += $arr["total"];
                $cart["item"] += $arr["qty"];
            }
        }
        $cart["total"] = platform_curr_format(PLATFORMID, $cart["total"]);

        if ($success) {
            if (isset($_SESSION["ra_items"][PLATFORMID][$parent_sku][$sku])) {
                unset($_SESSION["ra_items"][PLATFORMID][$parent_sku][$sku]);
            }
            $remove_text = $data['data']['lang_text']['cart_remove'];
            echo $remove_text . "||" . $prod_name . "||" . base_cdn_url() . $image_url . "||" . $cart["total"] . "||" . $cart["item"];
        } else {
            if (isset($_SESSION["ra_items"][PLATFORMID][$parent_sku][$sku])) {
                unset($_SESSION["ra_items"][PLATFORMID][$parent_sku][$sku]);
            }
            $remove_text = $data['data']['lang_text']['cart_remove'];
            echo $remove_text . "||" . $prod_name . "||" . base_cdn_url() . $image_url . "||" . $cart["total"] . "||" . $cart["item"];
        }
    }

    #SBF2502
    public function repopulate()
    {
        // clear the cart
        $_SESSION["cart"] = NULL;

        // populate the SKUs
        // http://dev.valuebasket.com.sg/cart/repopulate?&sku[]=13726-AA-BL&qty[]=1&sku[]=13868-AA-NA&qty[]=2&sku[]=13878-AA-NA&qty[]=3
        // echo "<pre>"; var_dump($_SESSION["cart"]); var_dump($_GET["sku"]); var_dump($_GET["qty"]); die();

        if (empty($sku))
            $sku = $_REQUEST["sku"];

        if (empty($qty))
            $qty = $_REQUEST["qty"];

        for ($i = 0; $i < count($sku); $i++)
            $this->add_item_qty($sku[$i], $qty[$i], true);

        // bring customer to review orders page
        redirect(base_url() . "review_order");
    }

}
