<?php
use ESG\Panther\Models\Website\CartSessionModel;

Class Cart extends PUB_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->cartSessionModel = new CartSessionModel;
    }

    public function addItem($sku, $qty = 1)
    {
        $this->addItemQty($sku, $qty);
        redirect("/review-order");
    }

    public function ajaxAddItem()
    {
        $sku = $this->input->post_get('sku');
        $qty = $this->input->post_get('qty') ? $this->input->post_get('qty') : 1;
// will need to put validation to sku, qty
        $this->addItemQty($sku, $qty);

        $return['redirect'] = '/review-order';
        echo json_encode($return);
    }

    public function ajaxSetItem()
    {
        $sku = $this->input->post_get('sku');
        $qty = $this->input->post_get('qty') ? $this->input->post_get('qty') : 1;
// will need to put validation to sku, qty
        $this->setItemQty($sku, $qty);

        $return['redirect'] = '/review-order';
        echo json_encode($return);
    }

    public function ajaxMinusItem()
    {
        $sku = $this->input->post_get('sku');
        $qty = $this->input->post_get('qty') ? $this->input->post_get('qty') : 1;
// will need to put validation to sku, qty
        $this->minusItemQty($sku, $qty);

        $return['redirect'] = '/review-order';
        echo json_encode($return);
    }

    public function ajaxRemoveItem()
    {
        $sku = $this->input->post_get('sku');
// will need to put validation to sku, qty
        $result = $this->cartSessionModel->removeItem($sku);

        $return['redirect'] = '/review-order';
        echo json_encode($return);
    }

    public function setItemQty($sku = "", $qty = 0)
    {
        $result = $this->cartSessionModel->setItemQty($sku, $qty, $this->get_lang_id(), PLATFORM, $this->getSiteInfo()->getPlatformCurrencyId());
        return $result;
    }

    public function minusItemQty($sku = "", $qty = 0)
    {
        $result = $this->cartSessionModel->minusItemQty($sku, $qty, $this->get_lang_id(), PLATFORM, $this->getSiteInfo()->getPlatformCurrencyId());
        return $result;
    }

    public function addItemQty($sku = "", $qty = 0)
    {
        $result = $this->cartSessionModel->addItemQty($sku, $qty, $this->get_lang_id(), PLATFORM, $this->getSiteInfo()->getPlatformCurrencyId());
        return $result;
    }

    public function info()
    {
        $data['cart_info'] = $this->cartSessionModel->get_cart_info();
        $this->load->view('cart/info', $data);
    }

    public function add_item($sku = "")
    {
        $this->addItemQty($sku, 1);
    }
/*
    public function checkout_item($sku = "", $nextpage = "checkout")
    {
        if (empty($sku)) {
            $sku = $_REQUEST["sku"];
        }

        if (!empty($sku)) {
            if (!($chk_cart = $this->cartSessionModel->add($sku, 1, PLATFORM))) {
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
            if ($chk_cart = $this->cartSessionModel->add($sku, 1, PLATFORM)) {
                $success = 1;
            }
        }

        $cart_info = $this->cartSessionModel->get_detail(PLATFORM);
        $cart["total"] = $cart["item"] = 0;
        if ($cart_info["cart"]) {
            foreach ($cart_info["cart"] AS $key => $arr) {
                $cart["total"] += $arr["total"];
                $cart["item"] += $arr["qty"];
            }
        }
        $cart["total"] = platform_curr_format($cart["total"]);

        if ($success) {
            $_SESSION["ra_items"][PLATFORM][$parent_sku][$sku] = 1;
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

        if (empty($sku) || isset($_SESSION["warranty"][PLATFORM][$parent_sku])) {
            $old_warranty = $_SESSION["warranty"][PLATFORM][$parent_sku];

            if (!empty($old_warranty)) {
                $cur_cart_qty = $_SESSION["cart"][PLATFORM][$old_warranty];
                $new_qty = $cur_cart_qty * 1 - 1;
                if ($new_qty <= 0) {
                    if ($chk_cart = $this->cartSessionModel->remove($old_warranty)) {
                        $success = 1;
                    }
                } else {
                    if ($chk_cart = $this->cartSessionModel->update($old_warranty, $new_qty, PLATFORM)) {
                        $success = 1;
                    }
                }
            }
        }

        if (!empty($sku)) {
            if ($chk_cart = $this->cartSessionModel->add($sku, 1, PLATFORM)) {
                $success = 1;
            }
        }

        $cart_info = $this->cartSessionModel->get_detail(PLATFORM);
        $cart["total"] = $cart["item"] = 0;
        if ($cart_info["cart"]) {
            foreach ($cart_info["cart"] AS $key => $arr) {
                $cart["total"] += $arr["total"];
                $cart["item"] += $arr["qty"];
            }
        }
        $cart["total"] = platform_curr_format($cart["total"]);

        if ($success) {
            $_SESSION["warranty"][PLATFORM][$parent_sku] = $sku;
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
            if (is_array($_SESSION["cart"][PLATFORM][$sku])) {
                $cur_cart_qty = $_SESSION["cart"][PLATFORM][$sku]["qty"];
            } else {
                $cur_cart_qty = $_SESSION["cart"][PLATFORM][$sku];
            }
            $new_qty = $cur_cart_qty * 1 - 1;
            if ($new_qty <= 0) {
                if ($chk_cart = $this->cartSessionModel->remove($sku)) {
                    $success = 1;
                }
            } else {
                if ($chk_cart = $this->cartSessionModel->update($sku, $new_qty, PLATFORM)) {
                    $success = 1;
                }
            }
        }

        $cart_info = $this->cartSessionModel->get_detail(PLATFORM);
        $cart["total"] = $cart["item"] = 0;
        if ($cart_info["cart"]) {
            foreach ($cart_info["cart"] AS $key => $arr) {
                $cart["total"] += $arr["total"];
                $cart["item"] += $arr["qty"];
            }
        }
        $cart["total"] = platform_curr_format($cart["total"]);

        if ($success) {
            if (isset($_SESSION["ra_items"][PLATFORM][$parent_sku][$sku])) {
                unset($_SESSION["ra_items"][PLATFORM][$parent_sku][$sku]);
            }
            $remove_text = $data['data']['lang_text']['cart_remove'];
            echo $remove_text . "||" . $prod_name . "||" . base_cdn_url() . $image_url . "||" . $cart["total"] . "||" . $cart["item"];
        } else {
            if (isset($_SESSION["ra_items"][PLATFORM][$parent_sku][$sku])) {
                unset($_SESSION["ra_items"][PLATFORM][$parent_sku][$sku]);
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
            $this->addItemQty($sku[$i], $qty[$i], true);

        // bring customer to review orders page
        redirect(base_url() . "review_order");
    }
*/
}
