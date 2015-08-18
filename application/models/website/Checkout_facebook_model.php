<?php

class Checkout_facebook_model extends Checkout_model
{
    public function __construct()
    {
        parent::Checkout_model();
        $this->load->library('service/payment_gateway_facebook_service');
        $this->load->library('service/client_service');
        $this->load->library('service/so_service');
        $this->load->library('service/region_service');
        $this->load->library('service/country_credit_card_service.php');
        $this->load->library('service/promotion_code_service.php');
        $this->load->library('service/func_option_service.php');
        $this->load->library('service/delivery_option_service.php');
        $this->load->model("website/cart_session_model");
        $this->load->model("marketing/product_model");
        $this->load->model('marketing/best_seller_model');
        $this->load->model("mastercfg/country_model");
    }

    public function js_credit_card()
    {
        $this->load->helper('js');
        js_cache_header($this->country_credit_card_service->get_max_modify(array("payment_gateway", "platform_pmgw", "pmgw_card", "country_credit_card")), '2011-06-01 04:28:00');

        $js = "";

        if ($objlist = $this->country_credit_card_service->get_country_pmgw_card_list(array("pc.status" => 1, "ccc.status" => 1, "pg.status" => 1, "pp.platform_id" => PLATFORMID, "pp.status" => 1), array("orderby" => "priority", "limit" => -1))) {
            foreach ($objlist as $obj) {
                $country_id = $obj->get_country_id();
                $code = $obj->get_code();
                $card_name = str_replace("'", "\'", $obj->get_card_name());
                $card_image = str_replace("'", "\'", $obj->get_card_image());
                $ar_slist[$country_id][] = "'" . $code . "':['" . $card_name . "','" . $card_image . "']";
            }

            foreach ($ar_slist as $cid => $jscard) {
                $slist[] = "'" . $cid . "': {" . (implode(", ", $jscard)) . "}";
            }

            $js = "cardlist = {" . @implode(", ", $slist) . "};";
        } else {
            $js = "cardlist = new Array();";
        }

        $js .= $this->js_credit_card_scriptchangecard();

        echo $js;
    }

    public function js_credit_card_scriptchangecard()
    {
        // this javascript will return images of credit card in a table
        // with radio buttons, suitable for form use
        return "
        function ChangeCard(val, obj)
        {
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
                        out_txt += '<td><input type=\"radio\" name=\"payment_methods\" id=\"card_'+ code +'\" value=\"'+ code +'\" align=\"absmiddle\"'+ checked +'> <label for=\"card_'+ code +'\" onclick=\"document.getElementById(\'card_' + code + '\').click();\">';

                        if (cur_card[1] != '')
                        {
                            out_txt += '<img src=\"/images/'+ cur_card[1] +'\" align=\"absmiddle\" alt=\"'+ cur_card[0] +'\" title=\"'+ cur_card[0] +'\" width=\"60\"></lable></td>';
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
                            out_txt += '<img width=\"45\" src=\"/images/'+ cur_card[1] +'\" align=\"absmiddle\" alt=\"'+ cur_card[0] +'\" title=\"'+ cur_card[0] +'\">&nbsp;';
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

    public function check_state($country_id = "", $type = "", $cur_value = "")
    {
        if ($country_id) {
            $data["prefix"] = $prefix = $type == "del" ? "del_" : "";
            $target_id = "div_{$prefix}state";
            $data['display_id'] = 10;
            include_once(APPPATH . "language/WEB" . str_pad($data['display_id'], 6, '0', STR_PAD_LEFT) . "_" . get_lang_id() . ".php");
            $data["lang"] = $lang;
            $data["type"] = $type;
            $data["cur_value"] = $cur_value;
            $data["state_list"] = $this->country_service->get_country_state_srv()->get_list(array("country_id" => $country_id, "status" => 1), array("limit" => -1, "array_list" => 1));

            if (!$this->objResponse) {
                $this->load->library('xajax');
                $this->objResponse = new xajaxResponse();
            }

            /*
            load view and return to $state_html
            {$target_id}.innerHTML = '{$state_html}'
            */
            $state_html = $this->load->view('product_skype_facebook_state', $data, true);
            $this->objResponse->assign($target_id, "innerHTML", $state_html);

            /*
            run javascript
            */
            if (!$data["state_list"]) {
                $this->objResponse->script("ChgStateLength('{$country_id}', document.fm_pmgw.{$prefix}state);");
            }

            return $this->objResponse;
        }
    }

    public function check_surcharge($values = "", $old_surcharge = 0, $amount = 0)
    {
        if (!$this->objResponse) {
            $this->load->library('xajax');
            $this->objResponse = new xajaxResponse();
        }

        if ($values["del_state"] || $values["del_postcode"]) {
            $this->cart_session_service->del_svc->delivery_country_id = $values["del_country_id"];
            $this->cart_session_service->del_svc->delivery_state = $values["del_state"];
            $this->cart_session_service->del_svc->delivery_postcode = $values["del_postcode"];

            $this->cart_session_service->del_svc->item_list = $_SESSION["cart"][PLATFORMID];
            if (($rs = $this->cart_session_service->del_svc->get_del_surcharge(TRUE)) && $rs["surcharge"]) {
                $code_type = strtolower($rs["code_type"]);

                if ($rs["code_type"] == "ST") {
                    $code_type_o = "pc";
                    $code_lang = "state";
                    $rs_value = $values["del_state"];
                } else {
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
                                        document.getElementById('lbl_surcharge').innerHTML='{$lang["surcharge"]}';
                                        document.getElementById('span_surcharge').innerHTML='" . platform_curr_format(PLATFORMID, $rs["surcharge"]) . "';
                                        document.getElementById('span_total_plus_surcharge').innerHTML='" . platform_curr_format(PLATFORMID, $amount - $old_surcharge + $rs["surcharge"]) . "';
                                        ");
                return $this->objResponse;
            }
        }

        $this->objResponse->assign("lbl_surcharge_cnt", "innerHTML", "");
        $this->objResponse->assign("span_st_surcharge", "innerHTML", "");
        $this->objResponse->assign("span_pc_surcharge", "innerHTML", "");
        $this->objResponse->script("
                                    document.getElementById('span_surcharge').innerHTML='';
                                    document.getElementById('lbl_surcharge').innerHTML='';
                                    document.getElementById('span_total_plus_surcharge').innerHTML='';
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
        if ($platform_type == "WEBSITE") {
            $this->xajax->register(XAJAX_FUNCTION, array('check_email_exists', &$ctrl, '_check_email_exists'));
        }
        $this->xajax->processRequest();
    }

    public function psform_content()
    {
        include_once(BASEPATH . "libraries/Encrypt.php");
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
}

?>