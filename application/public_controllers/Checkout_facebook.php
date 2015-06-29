<?php
$ws_array = array(NULL, 'index');
if (in_array($GLOBALS["URI"]->segments[2], $ws_array))
{
    DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);
}

require_once(APPPATH.'public_controllers/checkout.php');

class Checkout_facebook extends Checkout
{
    public $screen_width = 520;
    public $screen_height = 250;

    public function Checkout_facebook()
    {
        parent::Checkout();
        $this->load->helper(array('url'));
        $this->load->library('service/context_config_service');
        $this->load->library('service/display_banner_service');
        $this->load->library('service/affiliate_service');

        if ($this->context_config_service->value_of("force_https"))
        {
            if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != "on")
            {
                $httpsurl = str_replace("http://", "https://", current_url());
                if ($_SERVER['QUERY_STRING'] != "")
                {
                    $httpsurl .= "?".$_SERVER['QUERY_STRING'];
                }
                redirect ($httpsurl);
            }
        }
        $this->load->model('website/checkout_facebook_model');
    }

    public function index($debug=1, $init_process=0, $sku='', $promotion_code='', $qty=1)
    {
        if ($init_process)
        {
            if (empty($sku))
            {
                echo 'Please input product SKU.';
                exit;
            }

            $this->cart_session_model->cart_session_service->empty_cart();
            $chk_cart = $this->cart_session_model->add($sku, $qty, PLATFORMID);

            $arr_allow_promotion_code = array('skypePLX25');
            if ($promotion_code != '')
            {
                if (!in_array($promotion_code, $arr_allow_promotion_code))
                {
                    $promotion_code = '';
                }
            }
            $_POST["promotion_code"] = $promotion_code;
        }

        $data = $this->checkout_facebook_model->index_content();
        $this->checkout_facebook_model->payment_gateway_facebook_service->init_pmgw_srv("google");

        $data["debug"] = $debug;
        $data["step"] = 1;
        $data["notice"] = $_SESSION["NOTICE"];
        $data["message"] = $_SESSION["pmgw_message"];

        $data['display_id'] = 12;
        include_once(APPPATH . "language/WEB" . str_pad($data['display_id'], 6, '0', STR_PAD_LEFT) . "_" . get_lang_id() . ".php");
        $data["lang"] = $lang;

        unset($_SESSION["NOTICE"]);

        // Disable LP
        $data['no_lp'] = 1;
        $data['iframe_width'] = $this->screen_width - 5;
        $data['iframe_height'] = $this->screen_height - 5;

        $this->load_view('checkout/checkout_facebook', $data);
    }

    public function psform()
    {
        if ($_SESSION["POSTFORM"]["del_first_name"] == ""  && $_SESSION["client"]["logged_in"])
        {
            $_SESSION["POSTFORM"] = $_SESSION["client"];
            $space_pos = strrpos($_SESSION["client"]["del_name"], ' ');
            $_SESSION["POSTFORM"]["del_first_name"] = substr($_SESSION["client"]["del_name"], 0, $space_pos);
            $_SESSION["POSTFORM"]["del_last_name"] = substr($_SESSION["client"]["del_name"], $space_pos + 1);
        }

        $this->checkout_facebook_model->psform_init_ajax($this, "WEBSITE");
        $data = $this->checkout_facebook_model->psform_content();
        $data['pmgw_frame_width'] = $this->screen_width - 25;
        $data['pmgw_frame_height'] = $this->screen_height - 130;
        $this->load_view('checkout/psform_facebook', $data);
    }

    public function process_checkout($card_code="", $debug=1)
    {
        $_SESSION["POSTFORM"] = $vars = $_POST;
        if (isset($_SESSION["POSTFORM"]["p_enc"]))
        {
            include_once(BASEPATH."libraries/Encrypt.php");
            $encrypt = new CI_Encrypt();
            $platform_id = $encrypt->decode($_SESSION["POSTFORM"]["p_enc"]);
            if ($this->so_service->get_pbv_srv()->selling_platform_dao->get(array("id"=>$platform_id)))
            {
                $vars["platform_id"] = $platform_id;
            }
            else
            {
                $this->payment_result(0);
            }
        }

        if (!isset($vars["platform_id"]))
        {
            $vars["platform_id"] = PLATFORMID;
        }

        if ($card_code == "paypal")
        {
            $pmgw = "paypal";
        }
        else
        {
            if ($pc_obj = $this->country_credit_card_service->get_pmgw_card_dao()->get(array("code"=>$vars["payment_methods"])))
            {
                $pmgw = $pc_obj->get_payment_gateway_id();
                $vars["payment_methods"] = $pc_obj->get_card_id();
            }
            else
            {
                $pmgw = $card_code;
            }
        }

        if ($pmgw == 'global_collect')
        {
            $pmgw = 'global_collect_iframe_mode';
        }

        // Inverse the tick-box meaning due to this controller is extended from other and cannot change the tick-box meaning
        if ($vars['billaddr'])
        {
            $vars['billaddr'] = 0;
        }
        else
        {
            $vars['billaddr'] = 1;
        }

        $_COOKIE["af"] = 'skype_facebook';
        $vars["payment_gateway"] = $pmgw;
        switch ($pmgw)
        {
            case "bibit":
                if ($this->context_config_service->value_of("bibit_model") == "redirect")
                {
                    if ($this->check_login("checkout_facebook/index/{$debug}?".$_SERVER['QUERY_STRING']))
                    {
                        $_SESSION["review"] = $this->input->post("review");
                        $this->checkout_facebook_model->payment_gateway_facebook_service->checkout($pmgw, $vars, $debug);
                    }
                }
                else
                {
                    $this->checkout_facebook_model->payment_gateway_facebook_service->checkout($pmgw, $vars, $debug);
                }
                break;
            case "moneybookers":
            case "global_collect":
            case "global_collect_iframe_mode":
            case "paypal":
                if ($_SESSION["client"]["logged_in"] && !$vars["email"])
                {
                    $vars["email"] = $_SESSION["client"]["email"];
                }
                if ($this->client_service->check_email_login($vars))
                {
                    if ($this->checkout_facebook_model->check_promo())
                    {
                        $this->checkout_facebook_model->payment_gateway_facebook_service->checkout($pmgw, $vars, $debug);
                    }
                    else
                    {
                        unset($_SESSION["promotion_code"]);
                        echo "
                            <script>
                                window.parent.ChgPromoMsg(0, 1);
                            </script>
                            ";
                        exit;
                    }
                }
                elseif($debug)
                {
                    var_dump("Error ".__LINE__." : ".$this->db->_error_message()." -- ".$this->db->last_query());
                }
                else
                {
                    $browser = get_browser(null, true);
                    $url = base_url()."checkout_facebook/payment_result/0";
                    if ($browser["javascript"])
                    {
                        echo "<script>parent.document.location.href='$url';</script>";
                        exit;
                    }
                    else
                    {
                        redirect($url);
                    }
                }
                break;
        }
    }

    public function response($pmgw, $debug=0)
    {
        if ($pmgw == "bibit" && $this->context_config_service->value_of("bibit_model") == "redirect")
        {
            $vars["orderKey"] = $this->input->get("orderKey");
            $vars["paymentStatus"] = $this->input->get("paymentStatus");
            $vars["paymentAmount"] = $this->input->get("paymentAmount");
            $vars["paymentCurrency"] = $this->input->get("paymentCurrency");
            $vars["mac"] = $this->input->get("mac");
        }
        else
        {
            $vars = $_POST;
        }

        $this->checkout_facebook_model->payment_gateway_facebook_service->response($pmgw, $vars, $debug);
    }

    //Make ajax function start with _, restricted direct access
    public function _check_state($country_id="", $type="", $cur_value="")
    {
        return $this->checkout_facebook_model->check_state($country_id, $type, $cur_value);
    }

    //Make ajax function start with _, restricted direct access
    public function _check_surcharge($values="", $old_surcharge = 0, $amount = 0)
    {
        return $this->checkout_facebook_model->check_surcharge($values, $old_surcharge, $amount);
    }

    public function js_credit_card($append_pmgw=TRUE)
    {
        $this->checkout_facebook_model->js_credit_card($append_pmgw);
    }
}

/* End of file Checkout_facebook.php */
/* Location: ./app/public_controllers/Checkout_facebook.php */