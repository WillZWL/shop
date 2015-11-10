<?php
defined('BASEPATH') OR exit('No direct script access allowed');

DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);
DEFINE ('PLATFORM_TYPE', 'SKYPE');

class Product_skype extends PUB_Controller
{

    // for view should use public
    //public $promo_items = array("10238-AA-BK", "10021-AA-BK", "10048-AA-NA", "10022-AA-NA");

    public function Product_skype()
    {
        parent::PUB_Controller();
        $this->load->helper(array('url'));
        $this->load->library('service/context_config_service');
        $this->load->library('service/display_banner_service');
        if ($this->context_config_service->value_of("force_https")) {
            if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != "on") {
                $httpsurl = str_replace("http://", "https://", current_url());
                if ($_SERVER['QUERY_STRING'] != "") {
                    $httpsurl .= "?" . $_SERVER['QUERY_STRING'];
                }
                redirect($httpsurl);
            }
        }
        $this->load->model("website/checkout_model");
    }

    public function psform()
    {
        $this->checkout_model->psform_init_ajax($this);
        $data = $this->checkout_model->psform_content();
        $this->load->view("product_skype_psform_" . get_lang_id(), $data);
    }

    public function add_cart($sku = "", $qty = 1, $debug = 0, $from_url = 0)
    {
        $this->add($sku, $qty, $debug, $from_url);
    }

    public function add($sku = "", $qty = 1, $debug = 0, $from_url = 1)
    {
        $this->set_session_referer();

        if ($ar_sku = $this->input->get("sku")) {
            $chk_sku = $ar_sku;
            $chk_qty = $this->input->get("qty");
        } else {
            $chk_sku[] = $sku;
            $chk_qty[] = $qty;
        }

        if ($chk_sku) {
            for ($i = 0; $i < count($chk_sku); $i++) {
                $cur_sku = $chk_sku[$i];
                $cur_qty = $chk_qty[$i];
                if ($from_url) {
                    if (!isset($_SESSION["cart_from_url"][PLATFORMID][$cur_sku])) {
                        $_SESSION["cart_from_url"][PLATFORMID][$cur_sku] = NULL;
                    }
                }
                $this->cart_session_model->add($cur_sku, $cur_qty, PLATFORMID);
            }

            $_SESSION["add"] = $chk_sku;
            $this->index($debug, 'u');
        } else {
            $this->index($debug);
        }
    }

    protected function set_session_referer()
    {
        $referer = $this->get_external_refer('shop.skype.com');

        if (!empty($referer)) {
            $_SESSION['referer'] = $referer;
        }
    }

    protected function get_external_refer($allow_host = '')
    {
        $referer = $_SERVER['HTTP_REFERER'];
        if (!empty($allow_host)) {
            $pattern = '/^http[s]?:\/\/' . str_replace('.', '\.', strtolower($allow_host))
                . '/';
            if (preg_match($pattern, strtolower($referer))) {
                return $referer;
            }
        } else {
            $host = $_SERVER['HTTP_HOST'];

            if (empty($host)) {
                return false;
            }

            $pattern = '/^http[s]?:\/\/' . str_replace('.', '\.', strtolower($host))
                . '/';

            if (!preg_match($pattern, strtolower($referer))) {
                return $referer;
            }
        }

        return false;
    }

    public function index($debug = 0, $atype = "")
    {
        $data['type'] = $atype;
        $this->set_session_referer();
        if ($_SESSION["add"]) {
            $success = 1;
            foreach ($_SESSION["add"] as $add_sku) {
                $tmp = $this->product_model->get_product_content(array("prod_sku" => $add_sku, "lang_id" => get_lang_id()));
                $def = $this->product_model->get("product", array("sku" => $add_sku));
                if ($tmp) {
                    if ($tmp->get_prod_name() != "") {
                        $data["add_prod_name"][] = $tmp->get_prod_name();
                    } else {
                        $data["add_prod_name"][] = $def->get_name();
                    }
                } else {
                    if ($def) {
                        $data["add_prod_name"][] = $def->get_name();
                    } else {
                        $success = 0;
                    }
                }

                if (!$_SESSION["cart"][PLATFORMID][$add_sku]) {
                    $success = 0;

                }
            }

            if ($success) {
                $data['type'] = 'a';
            } else {
                $data['type'] = 'f';
            }
            unset($_SESSION["add"]);
        }

        $content_data = $this->checkout_model->index_content();
        $data = array_merge($data, $content_data);

        $data["debug"] = $debug; /* 0 = normal, 1 = debug*/
        $data["showskype"] = 1;

        $data['display_id'] = 10;
        include_once(APPPATH . "language/WEB" . str_pad($data['display_id'], 6, '0', STR_PAD_LEFT) . "_" . get_lang_id() . ".php");
        $data["lang"] = $lang;

        $data["skype_checkout_banner"] = $this->display_banner_service->get_publish_banner(10, 1, PLATFORMCOUNTRYID, get_lang_id(), "PB");

        echo '<script src="//cdn.optimizely.com/js/8554725.js"></script>';
        echo "\n";
        $this->load->view("product_skype_" . get_lang_id(), $data);
    }

    public function add_promote($sku = "", $promotion_code = "", $debug = 0)
    {
        if ($promotion_code) {
            $_POST["promotion_code"] = $promotion_code;
            if ($promo_obj = $this->cart_session_service->promo_cd_svc->get(array("code" => $promotion_code))) {
                if ($promo_obj->get_disc_type() == "FD" && $promo_obj->get_disc_level_value() != "All") {
                    $_POST["delivery"] = $promo_obj->get_disc_level_value();
                }
            }
        }
        $this->add($sku, 1, $debug);
    }

    public function update($sku = "", $qty = "", $debug = 0)
    {
        if ($sku != "" && $qty != "") {
            $this->cart_session_model->update($sku, $qty, PLATFORMID);
        }
        $this->index($debug, 'u');

    }

    public function remove($sku = "", $debug = 0)
    {
        if ($sku != "") {
            $this->cart_session_model->remove($sku, PLATFORMID);
        }

        $this->index($debug, 'u');
    }

    public function video($sku = "", $support_flash = 1)
    {
        if ($sku != "") {
            $cobj = $this->product_model->get_product_content(array("prod_sku" => $sku, "lang_id" => get_lang_id()));
            $obj = $this->product_service->get_dao()->get_bundle_components_overview(array("p.sku" => $sku, "vpi.component_order <" => 1), array("limit" => 1, "product" => 1));
            if ($obj) {
                $data["youtube_id"] = $obj->get_youtube_id();
                $data["support_flash"] = $support_flash;
                $data["product_name"] = $cobj && $cobj->get_prod_name() ? $cobj->get_prod_name() : $obj->get_name();
                $this->load->view("product_video", $data);
            }
        }
    }

    public function image($sku = "", $support_flash = 1)
    {
        if ($sku != "") {
            $cobj = $this->product_model->get_product_content(array("prod_sku" => $sku, "lang_id" => get_lang_id()));
            $prod = $this->product_model->get("product", array("sku" => $sku));
            if ($prod) {

                $data["flash"] = $prod->get_flash();
                $data["img"] = $prod->get_image();
                $data["product_name"] = $cobj && $cobj->get_prod_name() ? $cobj->get_prod_name() : $prod->get_name();

            } else {
                $data["flash"] = "";
                $data["img"] = "unavailable.jpg";
                $data["product_name"] = "";
            }
            $data["support_flash"] = $support_flash;
            $data["sku"] = $sku;
            $this->load->view("product_image", $data);
        }
    }

    public function js_credit_card($append_pmgw = TRUE)
    {
        $this->checkout_model->js_credit_card($append_pmgw);
    }

    function info($sku = "")
    {
        if ($sku != "") {
            $data["sku"] = $sku;
            if ($data["prod"] = $this->product_model->product_service->get_product_info($sku, PLATFORMID, get_lang_id())) {
                $data['display_id'] = 10;
                include_once(APPPATH . "language/WEB" . str_pad($data['display_id'], 6, '0', STR_PAD_LEFT) . "_" . get_lang_id() . ".php");
                $data["lang"] = $lang;

                $data["prod_content"] = $this->product_model->product_service->get_content($sku, get_lang_id());
                $this->load->view("product_info", $data);
            } else {
                show_404();
            }
        }
    }

    //Make ajax function start with _, restricted direct access
    public function _check_state($country_id = "", $type = "", $cur_value = "")
    {
        return $this->checkout_model->check_state($country_id, $type, $cur_value);
    }

    //Make ajax function start with _, restricted direct access
    public function _check_surcharge($values = "", $old_surcharge = 0, $amount = 0)
    {
        return $this->checkout_model->check_surcharge($values, $old_surcharge, $amount);
    }

}

?>