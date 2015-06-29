<?php
class Checkout_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/payment_gateway_service');
        $this->load->library('service/client_service');
        $this->load->library('service/so_service');
        $this->load->library('service/region_service');
        $this->load->library('service/country_credit_card_service.php');
        $this->load->library('service/promotion_code_service.php');
        $this->load->library('service/func_option_service.php');
        $this->load->library('service/delivery_option_service.php');
        $this->load->library('service/platform_biz_var_service.php');
        $this->load->model("website/cart_session_model");
        $this->load->model("marketing/product_model");
        $this->load->model('marketing/best_seller_model');
        $this->load->model("mastercfg/country_model");
        $this->load->model("mastercfg/exchange_rate_model");
    }

    public function check_promo()
    {
        if ($_SESSION["promotion_code"])
        {
            $this->promotion_code_service->promo_code = $_SESSION["promotion_code"];
            $this->promotion_code_service->country_id = $_SESSION["client"]["del_country_id"]?$_SESSION["client"]["del_country_id"]:PLATFORMCOUNTRYID;
            $this->promotion_code_service->email = $_SESSION["client"]["email"];
            return $this->promotion_code_service->check_del_country() && $this->promotion_code_service->check_email();
        }
        else
        {
            return TRUE;
        }
    }

    public function get_platform_pmgw($where = array())
    {
        $where["platform_id"] = PLATFORMID;
        $where["status"] = 1;

        $data = array();
        if ($obj_list = $this->payment_gateway_service->get_pp_dao()->get_list($where, array("limit"=>-1)))
        {
            foreach ($obj_list as $obj)
            {
                $data[$obj->get_payment_gateway_id()] = $obj;
            }
        }
        return $data;
    }

    public function js_credit_card($from_currency_id, $amount, $data = array(), $seq = 1)
    {
        $this->load->helper('js');
//      js_cache_header($this->country_credit_card_service->get_max_modify(array("payment_gateway", "platform_pmgw", "pmgw_card", "country_credit_card")), '2012-02-29 12:30:00');

        $amount = str_replace('_', '.', $amount);
        $js = "";

        if ($objlist = $this->country_credit_card_service->get_country_pmgw_card_list(array("pc.status"=>1, "ccc.status"=>1, "pg.status"=>1, "pp.platform_id"=>PLATFORMID, "pp.status"=>1, "pp.sequence"=>$seq, "order_amount"=>$amount, "include_default"=>TRUE), array("orderby"=>"priority","limit"=>-1)))
        {
            foreach ($objlist as $obj)
            {
                $country_id = $obj->get_country_id();
                $code = $obj->get_code();
                $card_name = str_replace("'", "\'", $obj->get_card_name());
                $card_image = str_replace("'", "\'", $obj->get_card_image());
                $card_id = str_replace("'", "\'", $obj->get_card_id());
                $payment_gateway_id = str_replace("'", "\'", $obj->get_payment_gateway_id());
                $ar_slist[$country_id][] = "'".$code."':['".$card_name."','".$card_image."','".$card_id."','".$payment_gateway_id."']";
            }

            foreach ($ar_slist as $cid=>$jscard)
            {
                $slist[] = "'".$cid."': {".(implode(", ", $jscard))."}";
            }

            $js = "cardlist = {".@implode(", ", $slist)."};";
        }
        else
        {
            $js = "cardlist = new Array();";
        }

        $js .= $this->js_credit_card_scriptchangecard($data);

        echo $js;
    }

    public function js_credit_card_scriptchangecard($data = array())
    {
        // this javascript will return images of credit card in a table
        // with radio buttons, suitable for form use
        return "

        function showCardExplanation(payment_gateway_id)
        {
            if ((payment_gateway_id != 'trustly') && (payment_gateway_id != 'm_bank_transfer') && (payment_gateway_id != 'w_bank_transfer'))
                document.getElementById('card_explanation').style.display = '';
            else
                document.getElementById('card_explanation').style.display = 'none';
        }

        function createCard(cur_card, code, card_id, payment_gateway_id, checked)
        {
            var out_txt = '';
            out_txt += '<div style=\"position:relative;float:left;\"><table width=\"100%\"><tr>';
            out_txt += '<td width=10 height=50><input type=\"radio\" onclick=\"showCardExplanation(\'' + payment_gateway_id + '\');\" name=\"payment_methods\" id=\"card_'+ code +'\" value=\"'+ card_id + \"%%\" + payment_gateway_id + \"%%\" + code + '\" align=\"absmiddle\"'+ checked +'></td>';
            out_txt += '<td><label for=\"card_'+ code +'\" onclick=\"document.getElementById(\'card_' + code + '\').click();showCardExplanation(\'' + payment_gateway_id + '\');\">';
            if (cur_card[1] != '')
            {
                out_txt += '<img src=\"/images/'+ cur_card[1] +'\" align=\"absmiddle\" alt=\"'+ cur_card[0] +'\" title=\"'+ cur_card[0] +'\"></label></td>';
            }
            else
            {
                out_txt += '<label for=\"card_'+ code +'\">'+ cur_card[0] +'</label></td>';
            }
            out_txt += '</td></tr></table></div>';
            return out_txt;
        }

        function ChangeCardOnePage(LangCountryPair, obj)
        {
            var displayTrustly = false;
            var trustlyCard = '';
            var displayBankTransfer = false;
            var banktransfer = '';
            var val;
            var out_txt = '';

            if (LangCountryPair.indexOf('_') != -1)
                val = LangCountryPair.split('_')[1];
            else
                val = LangCountryPair;
            if (obj)
            {
                if (val != '')
                {
                    var i = 0;
                    for (var code in cardlist[val])
                    {
//                      checked = i==0?' CHECKED':'';
// no more pre-select
                        checked = '';
                        cur_card = cardlist[val][code];
                        card_id = cardlist[val][code][2];
                        payment_gateway_id = cardlist[val][code][3];



                        if (payment_gateway_id != 'trustly')
                        {
                            if(payment_gateway_id != 'w_bank_transfer')
                            {
                                if (typeof isMobileSite != 'undefined')
                                {
                                    if (i%2 == 0)
                                        out_txt += '<div style=\"clear:both\"></div>';
                                }
                                if ((i == 0) && (code == 'paypal_AMX') && (card_id == 'AMX'))
                                {
//hard code paypal instead of AMX if there is only 1 payment option (ignore trustly and bank transfer)
                                    code = 'paypal';
                                    card_id = 'paypal';
                                    cur_card[1] = 'btn_paypal.png';
                                    cur_card[0] = 'PayPal';
                                }
                                out_txt += createCard(cur_card, code, card_id, payment_gateway_id, checked);
                            }
                            else
                            {
                                displayBankTransfer = true;
                                banktransfer = createCard(cur_card, code, card_id, payment_gateway_id, checked);
                            }
                        }
                        else
                        {
                            displayTrustly = true;
                            trustlyCard = createCard(cur_card, code, card_id, payment_gateway_id, checked);
                        }


                        i++;
                    }

                    // No payment gateway is available
                    if (i == 0)
                    {
                        out_txt += \"" . $data['lang_text']['high_value_1'] . "<br /><br />\";
                        out_txt += \"" . $data['lang_text']['high_value_2'] . "<br /><br />\";
                        out_txt += \"" . $data['lang_text']['high_value_3'] . "\";

                        document.getElementById('payment-buttons-container').style.display = 'none';
                    }
                    // SBF #4796 - only paypal payment option
                    if (i == 1) {
                        for (var code in cardlist[val]) {
                            if ('paypal' == code) {
                                // out_txt = 'aaaa';
                                out_txt = '<div style=\"position:relative;float:left;\"><table width=\"100%\"><tr><td width=10 height=50><input type=\"radio\" onclick=\"showCardExplanation(\'' + payment_gateway_id + '\');\" name=\"payment_methods\" id=\"card_'+ code +'\" value=\"'+ card_id + \"%%\" + payment_gateway_id + \"%%\" + code + '\" align=\"absmiddle\"'+ checked +'></td><td><label for=\"card_'+ code +'\" onclick=\"document.getElementById(\'card_' + code + '\').click();showCardExplanation(\'' + payment_gateway_id + '\');\"><img src=\"/images/'+ cur_card[1] +'\"style=\"display:block;float:left;\" align=\"absmiddle\" alt=\"'+ cur_card[0] +'\" title=\"'+ cur_card[0] +'\"><span style=\"display:block;margin-left:30px;float:left;margin-top:15px;text-shadow: 5px 5px 5px gray;\">PayPal accepts Visa, Mastercard and American Express</span></lable></td>';
                            }

                        }
                    }

//                  if (i <= 1)
//                  {
//                      document.getElementById('select_a_card').style.display = 'none';
//                  }
                }

                obj.innerHTML = out_txt;
                if (displayTrustly)
                {
                    document.getElementById('trustly_div').innerHTML = trustlyCard;
                    document.getElementById('trustly_container').className = 'buttons-set';
                    document.getElementById('trustly_container').style.display = '';
                }
                if (typeof isMobileSite == 'undefined')
                {
                    if(displayBankTransfer)
                    {
                        document.getElementById('bank_transfer_div').innerHTML = banktransfer;
                        document.getElementById('bank_transfer_container').className = 'buttons-set';
                        document.getElementById('bank_transfer_container').style.display = '';
                    }
                }
            }
        }

        function ChangeCard(LangCountryPair, obj)
        {
            var val;
            if (LangCountryPair.indexOf('_') != -1)
                val = LangCountryPair.split('_')[1];
            else
                val = LangCountryPair;
            if (obj)
            {
                out_txt = '<table width=\"100%\"><tr>';
                if (val != '')
                {
                    var i = 0;
                    for (var code in cardlist[val])
                    {
                        checked = i==0?' CHECKED':'';
                        cur_card = cardlist[val][code];
                        card_id = cardlist[val][code][2];
                        payment_gateway_id = cardlist[val][code][3];
                        out_txt += '<td height=50><input type=\"radio\" name=\"payment_methods\" id=\"card_'+ code +'\" value=\"'+ card_id + \"%%\" + payment_gateway_id + \"%%\" + code + '\" align=\"absmiddle\"'+ checked +'> <label for=\"card_'+ code +'\" onclick=\"document.getElementById(\'card_' + code + '\').click();\">';

                        if (cur_card[1] != '')
                        {
                            out_txt += '<img src=\"/images/'+ cur_card[1] +'\" align=\"absmiddle\" alt=\"'+ cur_card[0] +'\" title=\"'+ cur_card[0] +'\"></lable></td>';
                        }
                        else
                        {
                            out_txt += '<label for=\"card_'+ code +'\">'+ cur_card[0] +'</lable></td>';
                        }
                        if (i%2)
                        {
                            out_txt += '</tr><tr>';
                        }
                        i++;
                    }

                    // No payment gateway is available
                    if (i == 0)
                    {
                        out_txt += \"" . $data['lang_text']['high_value_1'] . "\";
                        out_txt += \"" . $data['lang_text']['high_value_2'] . "\";
                        out_txt += \"<a href=\'mailto:sales@valuebasket.com\' style=\'color:#00f\'>sales@valuebasket.com</a>, \";
                        out_txt += \"" . $data['lang_text']['high_value_3'] . "\";

                        document.getElementById('payment-buttons-container').style.display = 'none';
                    }

                    if (i%2)
                    {
                        out_txt += '<td></td>';
                    }
                }

                obj.innerHTML = out_txt+'</tr></table>';
            }
        }

        function HideCard(val, obj)
        {
            if (obj)
            {
                if (val != '')
                {
                    for (var code in cardlist[val])
                    {
                        cur_card = cardlist[val][code];
                        out_txt = '<input type=\"hidden\" name=\"payment_methods\" id=\"card_'+ code +'\" value=\"'+ code +'\">';
                        break;
                    }
                }
                obj.innerHTML = out_txt;
            }
        }

        function ChangeCardNoRadio(val, obj)
        {
            if (obj)
            {
                out_txt = '';
                if (val != '')
                {
                    var i = 0;

                    for (var code in cardlist[val])
                    {
                        cur_card = cardlist[val][code];
                        if (cur_card[1] != '')
                        {
                            out_txt += '<img width=\"60\" src=\"/images/'+ cur_card[1] +'\" align=\"absmiddle\" alt=\"'+ cur_card[0] +'\" title=\"'+ cur_card[0] +'\">&nbsp;';
                        }
                        i++;
                    }

                }
                obj.innerHTML = out_txt;
            }
        }

        function InitCard(obj)
        {
            for (var i in cardlist)
            {
                obj.options[obj.options.length]=new Option(cardlist[i][0], i);
            }
        }";
    }

    public function index_content()
    {
        if (isset($_POST["promotion_code"]))
        {
            if ($this->input->post("promotion_code"))
            {
                $_SESSION["promotion_code"] = $_POST["promotion_code"];
                $email = $this->input->post("email");
                $_SESSION["POSTFORM"]["email"] = $email;
                $this->cart_session_service->set_email($email);
            }
            else
            {
                unset($_SESSION["promotion_code"]);
            }
        }
        elseif (isset($_POST["del_country_id"]))
        {
            $_SESSION["POSTFORM"] = $_POST;
        }

        $data = array();
        $this->cart_session_service->set_delivery_mode($_POST["delivery"]);
        $this->cart_session_service->set_del_country_id($_SESSION["POSTFORM"]["del_country_id"]?$_SESSION["POSTFORM"]["del_country_id"]:PLATFORMCOUNTRYID);
        $cart = $this->cart_session_service->get_detail(PLATFORMID,1,0,0,0,0,0,0,"",1);
        $this->cart_session_service->set_del_country_id("");
        $data["promo"] = $cart["promo"];

        if (!$data["promo"]["valid"] || $data["promo"]["error"])
        {
            unset($_SESSION["promotion_code"]);
        }
        if ($data["promo"]["error"] == "FD")
        {
            if (!($data["text_delivery_display"] = $this->delivery_option_service->display_name_of($data["promo"]["error_code"], get_lang_id())))
            {
                $data["text_delivery_display"] = $this->delivery_option_service->display_name_of($data["promo"]["error_code"]);
            }
        }

        $data["default_delivery"] = $this->context_config_service->value_of("default_delivery_type");
        $data["chk_cart"] = $cart["cart"];
        $data["dc"] = $cart["dc"];
        $data["dc_default"] = $cart["dc_default"];
        $plist = $this->product_service->get_skype_page_info($data["chk_cart"],PLATFORMID, get_lang_id());
        $data["cart_item"] = $plist;
        $clist = $this->cart_session_model->get_cart(PLATFORMID);
        $data["clist"] = $clist;

        if ($data["cart_item"])
        {
            $first_item = end($data["cart_item"]);
            $sub_cat_id = $first_item->get_sub_cat_id();
            $data["ra_list"] = $this->best_seller_model->best_seller_service->get_ra_bs_list($sub_cat_id, PLATFORMID, get_lang_id());
        }
        if (!($data["text_working_days"] = $this->func_option_service->text_of('working_days', get_lang_id())))
        {
            $data["text_working_days"]= $this->func_option_service->text_of('working_days');
        }
        if ($data["dc"])
        {
            foreach ($data["dc"] as $courier_id=>$courier_detail)
            {
                $courier_id = strtolower($courier_id);
                if (!($data["text_free"][$courier_id] = $this->func_option_service->text_of('free_'.$courier_id, get_lang_id())))
                {
                    $data["text_free"][$courier_id] = $this->func_option_service->text_of('free_'.$courier_id);
                }
            }
        }

        return $data;
    }

    public function check_state($country_id="", $type="", $cur_value="")
    {
        if ($country_id)
        {
            $data["prefix"] = $prefix = $type=="del"?"del_":"";
            $target_id = "div_{$prefix}state";
            $data['display_id'] = 10;
            include_once(APPPATH . "language/WEB" . str_pad($data['display_id'], 6, '0', STR_PAD_LEFT) . "_" . get_lang_id() . ".php");
            $data["lang"] = $lang;
            $data["type"] = $type;
            $data["cur_value"] = $cur_value;
            $data["state_list"] = $this->country_service->get_country_state_srv()->get_list(array("country_id"=>$country_id, "status"=>1), array("limit"=>-1, "array_list"=>1));

            if (!$this->objResponse)
            {
                $this->load->library('xajax');
                $this->objResponse = new xajaxResponse();
            }

            /*
            load view and return to $state_html
            {$target_id}.innerHTML = '{$state_html}'
            */
            $state_html = $this->load->view('product_skype_state', $data, true);
            $this->objResponse->assign($target_id, "innerHTML", $state_html);

            /*
            run javascript
            */
            if (!$data["state_list"])
            {
                $this->objResponse->script("ChgStateLength('{$country_id}', document.fm_pmgw.{$prefix}state);");
            }

            return $this->objResponse;
        }
    }

    public function check_surcharge($values="", $old_surcharge = 0, $amount = 0)
    {

        if (!$this->objResponse)
        {
            $this->load->library('xajax');
            $this->objResponse = new xajaxResponse();
        }

        if ($values["del_state"] || $values["del_postcode"])
        {
            $this->cart_session_service->del_svc->delivery_country_id = $values["del_country_id"];
            $this->cart_session_service->del_svc->delivery_state = $values["del_state"];
            $this->cart_session_service->del_svc->delivery_postcode = $values["del_postcode"];

            $this->cart_session_service->del_svc->item_list = $_SESSION["cart"][PLATFORMID];
            if (($rs = $this->cart_session_service->del_svc->get_del_surcharge(TRUE)) && $rs["surcharge"])
            {
                $code_type = strtolower($rs["code_type"]);

                if ($rs["code_type"] == "ST")
                {
                    $code_type_o = "pc";
                    $code_lang = "state";
                    $rs_value = $values["del_state"];
                }
                else
                {
                    $code_type_o = "st";
                    $code_lang = "postcode";
                    $rs_value = $values["del_postcode"];
                }

                $data['display_id'] = 10;
                include_once(APPPATH . "language/WEB" . str_pad($data['display_id'], 6, '0', STR_PAD_LEFT) . "_" . get_lang_id() . ".php");
                $lang["delivery_surcharge"] = sprintf($lang["delivery_surcharge"], $lang[$code_lang], $rs_value);
                $lang["delivery_surcharge_continue"] = sprintf($lang["delivery_surcharge_continue"], $lang[$code_lang], $rs_value);
                $data["lang"] = $lang;
                $this->objResponse->assign("span_{$code_type}_surcharge", "innerHTML", $lang["delivery_surcharge"]);
                $this->objResponse->assign("lbl_surcharge_cnt", "innerHTML", $lang["delivery_surcharge_continue"]);
                $this->objResponse->assign("span_{$code_type_o}_surcharge", "innerHTML", "");
                $this->objResponse->script("
                                        top.document.getElementById('lbl_surcharge').innerHTML='{$lang["surcharge"]}';
                                        top.document.getElementById('span_surcharge').innerHTML='".platform_curr_format(PLATFORMID, $rs["surcharge"])."';
                                        top.document.getElementById('span_total').innerHTML='".platform_curr_format(PLATFORMID, $amount - $old_surcharge + $rs["surcharge"])."';
                                        ");
                return $this->objResponse;
            }
        }

        $this->objResponse->assign("lbl_surcharge_cnt", "innerHTML", "");
        $this->objResponse->assign("span_st_surcharge", "innerHTML", "");
        $this->objResponse->assign("span_pc_surcharge", "innerHTML", "");
        $this->objResponse->script("
                                    top.document.getElementById('span_surcharge').innerHTML='';
                                    top.document.getElementById('lbl_surcharge').innerHTML='';
                                    top.document.getElementById('span_total').innerHTML='".platform_curr_format(PLATFORMID, $amount - $old_surcharge)."';
                                    ");
        return $this->objResponse;
    }

    public function psform_init_ajax(&$ctrl, $platform_type = NULL)
    {
        /*
            Register a js xajax function
            check_state is the js function xajax_check_state
            _check_state is the function in this controller
        */
        $this->load->library('xajax');
        //debug, comment it when live
        //$this->xajax->setFlag('debug', true);

        $this->xajax->getJavascript(base_url());
        $this->xajax->register(XAJAX_FUNCTION, array('check_state', &$ctrl, '_check_state'));
        $this->xajax->register(XAJAX_FUNCTION, array('check_surcharge', &$ctrl, '_check_surcharge'));
        if ($platform_type == "WEBSITE")
        {
            $this->xajax->register(XAJAX_FUNCTION, array('check_email_exists', &$ctrl, '_check_email_exists'));
        }
        $this->xajax->processRequest();
    }

    public function get_allow_sell_country()
    {
        return $this->country_model->get_sell_to_list(get_lang_id());
    }

    public function psform_content()
    {
        include_once(BASEPATH."libraries/Encrypt.php");
        $encrypt = new CI_Encrypt();
        $data["p_enc"] = $encrypt->encode(PLATFORMID);
        $data["thiscountry"] = PLATFORMCOUNTRYID;
        //$data["sell_to_list"] = $this->country_model->get_sell_to_list();
        $data["sell_to_list"] = $this->country_model->get_country_name_in_lang(get_lang_id(), 1, PLATFORMID);
        $data["bill_to_list"] = $this->country_model->get_country_name_in_lang(get_lang_id(), 1);
        $data["all_virtual"] = $this->product_model->product_service->check_all_virtual($_SESSION["cart"][PLATFORMID]);
        $data["all_trial"] = $this->product_model->product_service->check_all_trial($_SESSION["cart"][PLATFORMID]);
        //$data["pmgw_list"] = $this->checkout_model->get_platform_pmgw();
        return $data;
    }

    public function check_email_exists($email, $submit)
    {
        if (!$this->objResponse)
        {
            $this->load->library('xajax');
            $this->objResponse = new xajaxResponse();
        }

        if ($this->client_service->get(array("email"=>$email)))
        {
            $data['display_id'] = 12;
            include(APPPATH . "language/WEB" . str_pad($data['display_id'], 6, '0', STR_PAD_LEFT) . "_" . get_lang_id() . ".php");
            $this->objResponse->alert($lang["email_exists"]);
            $this->objResponse->script("
                                        top.window.scrollTo(0, 0);
                                        top.document.fm_chk_login.email.focus();
                                        email_exists = 1;
                                    ");
        }
        elseif($submit)
        {
            $this->objResponse->script("if(CheckForm(document.fm_pmgw)){document.getElementById('a_check').onclick()}");
        }
        return $this->objResponse;
    }

    public function paypal_ipn_notification($debug)
    {
        $this->payment_gateway_service->init_pmgw_srv("paypal");
        $this->payment_gateway_service->get_pmgw_srv()->ipn_notification($debug);
    }

    public function prepare_js_credit_card_parameter(&$data)
    {
        $cart = $this->cart_session_service->get_detail(PLATFORMID,1,0,0,0,0,0,0,"",1);

        $ref_amount = 0;
        for($j=0; $j<count($cart["cart"]); $j++)
        {
            $ref_amount += $cart["cart"][$j]["price"] * $cart["cart"][$j]["qty"];
        }

        $ref_amount += $cart['dc']['STD']['charge'];

        if($_SESSION["promotion_code"])
        {
            if ($cart["promo"]["valid"] && isset($cart["promo"]["disc_amount"]))
            {
                $ref_amount -= $cart["promo"]["disc_amount"];
            }
        }

        $data["total_amount"] = str_replace('.', '_', $ref_amount);
        $data["platform_curr"] = PLATFORMCURR;
    }
}
?>