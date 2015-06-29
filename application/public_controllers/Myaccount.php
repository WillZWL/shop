<?php
defined('BASEPATH') OR exit('No direct script access allowed');

DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);

$ws_array = array(NULL, 'index', 'rma', 'rma_confirm', 'rma_edit', 'rma_print', 'update_password');
if (in_array($GLOBALS["URI"]->segments[2], $ws_array)) {
    DEFINE ('PLATFORM_TYPE', 'SKYPE');
}

class Myaccount extends PUB_Controller
{
    private $components_list;

    public function Myaccount()
    {
        parent::PUB_Controller(array('require_login' => 1, 'load_header' => 1));
        $this->load->helper(array('url', 'object', 'notice', 'tbswrapper'));
        $this->load->model('website/client_model');
        $this->load->model('order/so_model');
        $this->load->model('mastercfg/courier_model');
        $this->load->model('mastercfg/country_model');
        $this->load->library('template');
        $this->load->library('encrypt');
        $this->load->library('service/region_service');
        $this->load->library('service/client_service');
        $this->load->library('service/complementary_acc_service');
        $this->load->library('service/event_service');
        $this->load->library('service/pdf_rendering_service');
        $this->load->library('service/courier_service');
        $this->template->add_js("/js/checkform.js");
//      $this->template->add_js("/myaccount/rma_addr_js");
    }

    public function profile()
    {
        $this->load->model('mastercfg/country_model');
        $this->load->library('encrypt');

        $data['display_id'] = 15;
        /*      include_once(APPPATH . "language/WEB"
                    . str_pad($data['display_id'], 6, '0', STR_PAD_LEFT)
                    . "_" . get_lang_id() . ".php");
        /       $data["lang"] = $lang;
        */
        $data["back"] = $this->input->get("back");
        $data['data']['lang_text'] = $this->_get_language_file('', '', 'index');
        if ($this->input->post("posted")) {
            if (isset($_SESSION["client_vo"])) {
                $this->client_model->client_service->get_dao()->include_vo();
                $data["client_obj"] = unserialize($_SESSION["client_obj"]);

                if (!empty($_POST["password"])) {
                    $old_password = $this->input->post("old_password");
                    $new_password = $this->input->post("password");
                    $reconfirm_password = $this->input->post("confirm_password");
                    $data['email'] = $_SESSION['client']['email'];
                    if ($this->encrypt->encode(strtolower($this->input->post("old_password"))) != $data["client_obj"]->get_password()) {
                        $_SESSION['NOTICE'] = $data['data']['lang_text']['enter_old_password_warning'];
                    } elseif ($new_password != $reconfirm_password) {
                        $_SESSION['NOTICE'] = $data['data']['lang_text']['confirm_password_mismatch_warning'];
                    } elseif ($old_password == $new_password) {
                        $_SESSION['NOTICE'] = $data['data']['lang_text']['new_password_same_old_warning'];
                    } else {
                        $update_password = $this->encrypt->encode(strtolower($this->input->post("password")));
                    }
                }

                if (!$_SESSION['NOTICE']) {
                    if (empty($_POST["subscriber"])) {
                        $_POST["subscriber"] = 0;
                    }

                    unset($_POST["password"]);
                    set_value($data["client_obj"], $_POST);
                    $data["client_obj"]->set_del_name($_POST["title"] . " " . $_POST["forename"] . " " . $_POST["surname"]);
                    $data["client_obj"]->set_title($_POST["name_prefix"]);
                    $data["client_obj"]->set_del_company($_POST["companyname"]);
                    $data["client_obj"]->set_del_address_1($_POST["address_1"]);
                    $data["client_obj"]->set_del_address_2($_POST["address_2"]);
                    $data["client_obj"]->set_del_city($_POST["city"]);
                    $data["client_obj"]->set_del_state($_POST["state"]);
                    $data["client_obj"]->set_del_country_id($_POST["country_id"]);
                    $data["client_obj"]->set_del_postcode($_POST["postcode"]);
                    $data["client_obj"]->set_del_tel_1($_POST["tel_1"]);
                    $data["client_obj"]->set_del_tel_2($_POST["tel_2"]);
                    $data["client_obj"]->set_del_tel_3($_POST["tel_3"]);
                    $data["client_obj"]->set_party_subscriber(0);
                    $data["client_obj"]->set_status(1);
                    if ($update_password) {
                        $data["client_obj"]->set_password($update_password);
                    }

                    $email = $data["client_obj"]->get_email();
                    $proc = $this->client_model->client_service->get_dao()->get(array("email" => $email));
                    if (!empty($proc)) {
                        if (!$this->client_model->client_service->get_dao()->update($data["client_obj"])) {
                            $_SESSION['NOTICE'] = $data['data']['lang_text']['profile_update_fail_warning'];
                        } else {
                            $_SESSION["NOTICE"] = $data['data']['lang_text']['update_success_message'];
                        }
                    } else {
                        $_SESSION["NOTICE"] = $data['data']['lang_text']['client_does_not_exist_warning'];
                    }
                }
            }
        }
        $this->index("profile");
    }

    public function index($page = "order", $rma_no = "")
    {
        // order history
        $client_id = $_SESSION["client"]["id"];
        $orderlist = $this->so_model->so_service->get_dao()->get_order_history($client_id);

        $data["show_bank_transfer_contact"] = FALSE;
        $data["show_partial_ship_text"] = FALSE;
        if ($orderlist) {
            foreach ($orderlist AS $obj) {
                $status = array();
                $status = $this->so_model->get_order_status($obj);
                $data['orderlist'][$obj->get_so_no()]['join_split_so_no'] = $obj->get_join_split_so_no();
                $data['orderlist'][$obj->get_so_no()]['currency_id'] = $obj->get_currency_id();
                $data['orderlist'][$obj->get_so_no()]['client_id'] = $obj->get_client_id();
                $data['orderlist'][$obj->get_so_no()]['order_date'] = date("Y-m-d", strtotime($obj->get_order_create_date()));
                $data['orderlist'][$obj->get_so_no()]['delivery_name'] = $obj->get_delivery_name();
                $data['orderlist'][$obj->get_so_no()]['order_status_ini'] = $status["id"] . "_status";
                $data['orderlist'][$obj->get_so_no()]['status_desc_ini'] = $status["id"] . "_desc";
                $data['orderlist'][$obj->get_so_no()]["product_name"] .= $obj->get_prod_name() . "</br>";
                $data['orderlist'][$obj->get_so_no()]["total_amount"] += $obj->get_amount();
                $data['orderlist'][$obj->get_so_no()]["is_shipped"] = ($obj->get_status() == 6 && $obj->get_refund_status() == 0 && $obj->get_hold_status() == 0) ? TRUE : FALSE;

                if ($obj->get_payment_gateway_id() == 'w_bank_transfer') {
                    $data["show_bank_transfer_contact"] = TRUE;
                }

                $sosh_obj = $this->so_model->so_service->get_shipping_info(array("soal.so_no" => $obj->get_so_no()));
                if ($sosh_obj)
                    $data['orderlist'][$obj->get_so_no()]['tracking_link'] = $this->courier_service->get(array("id" => $sosh_obj->get_courier_id()));
                else
                    $data['orderlist'][$obj->get_so_no()]['tracking_link'] = "";

                if (isset($status["courier_name"])) {
                    $data['orderlist'][$obj->get_so_no()]["courier_name"] = $status["courier_name"];
                }
                if (isset($status["tracking_url"])) {
                    $data['orderlist'][$obj->get_so_no()]["tracking_url"] = $status["tracking_url"];
                }
                if (isset($status["tracking_number"])) {
                    $data['orderlist'][$obj->get_so_no()]["tracking_number"] = $status["tracking_number"];
                }

                // show split order text
                $split_so_group = $obj->get_split_so_group();
                if (isset($split_so_group) && $split_so_group != $obj->get_so_no()) {
                    $data["show_partial_ship_text"] = TRUE;
                }
            }
        }

        # SBF #3591 show unpaid/underpaid bank transfers
        $payment_gateway_arr = array("w_bank_transfer"); # determines what payment gateway will show
        $unpaid_orderlist = $this->so_model->so_service->get_dao()->get_unpaid_order_history($client_id, $payment_gateway_arr);
        if ($unpaid_orderlist) {
            foreach ($unpaid_orderlist as $unpaid_obj) {
                $data['unpaid_orderlist'][$unpaid_obj->get_so_no()]['currency_id'] = $unpaid_obj->get_currency_id();
                $data['unpaid_orderlist'][$unpaid_obj->get_so_no()]['client_id'] = $unpaid_obj->get_client_id();
                $data['unpaid_orderlist'][$unpaid_obj->get_so_no()]['order_date'] = date("Y-m-d", strtotime($unpaid_obj->get_order_create_date()));
                $data['unpaid_orderlist'][$unpaid_obj->get_so_no()]['delivery_name'] = $unpaid_obj->get_delivery_name();
                $data['unpaid_orderlist'][$unpaid_obj->get_so_no()]['order_status_ini'] = $status["id"] . "_status";
                $data['unpaid_orderlist'][$unpaid_obj->get_so_no()]['status_desc_ini'] = $status["id"] . "_desc";
                $data['unpaid_orderlist'][$unpaid_obj->get_so_no()]["product_name"] .= $unpaid_obj->get_prod_name() . "</br>";
                $data['unpaid_orderlist'][$unpaid_obj->get_so_no()]["total_amount"] += $unpaid_obj->get_amount();
                $data['unpaid_orderlist'][$unpaid_obj->get_so_no()]["net_diff_status"] = $unpaid_obj->get_net_diff_status();
                if ($unpaid_obj->get_payment_gateway_id() == 'w_bank_transfer') {
                    $data["show_bank_transfer_contact"] = TRUE;
                }
                if ($net_diff_status = $unpaid_obj->get_net_diff_status()) {
                    switch ($net_diff_status) {
                        # show respective lang_text for underpaid
                        case 3:
                            $data['unpaid_orderlist'][$unpaid_obj->get_so_no()]["unpaid_status"] = 1;
                            break;

                        default:
                            break;
                    }
                } else {
                    # show respective lang_text for unpaid
                    $data['unpaid_orderlist'][$unpaid_obj->get_so_no()]["unpaid_status"] = 0;
                }
            }
        }

        // edit profile
        if (($data["client_obj"] = $this->client_model->client_service->get_dao()->get(array("id" => $_SESSION["client"]["id"]))) === FALSE) {
            $_SESSION["NOTICE"] = "Error: " . __LINE__;
        } else {
            $_SESSION["client_obj"] = serialize($data["client_obj"]);
        }
        $data["bill_to_list"] = $this->country_model->get_country_name_in_lang(get_lang_id(), 1);

        // rma
        $this->so_model->include_vo("rma_dao");
        $data["rma_obj"] = unserialize($_SESSION["rma_obj"]);
        if (empty($data["rma_obj"])) {
            if (($data["rma_obj"] = $this->so_model->get("rma_dao")) === FALSE) {
                $_SESSION["NOTICE"] = "Error: " . __LINE__;
            } else {
                $_SESSION["rma_vo"] = serialize($data["rma_obj"]);
            }
        }

        // rma_confirm
        $data["rma_confirm"] = 0;
        if ($page == "rma" && $rma_no) {
            if ($data["rma_obj"] = $this->so_model->get("rma_dao", array("id" => $rma_no, "client_id" => $_SESSION["client"]["id"]))) {
                $data["rma_confirm"] = 1;
            }
        }

        // notice
        $data["notice"] = notice();
        unset($_SESSION["NOTICE"]);

        $data['page'] = $page;

        $data['data']['lang_text'] = $this->_get_language_file('', '', 'index');
        $data['lang_id'] = get_lang_id();
        $this->load_tpl('content', 'tbs_myaccount', $data, TRUE, TRUE);
    }

    public function rma()
    {
        $data['display_id'] = 8;
        $this->load->model('order/so_model');

        unset($_SESSION["NOTICE"]);
        if ($this->input->post("posted")) {
            if (isset($_SESSION["rma_vo"])) {
                $this->so_model->include_vo("rma_dao");
                $data["rma_obj"] = unserialize($_SESSION["rma_vo"]);
                set_value($data["rma_obj"], $_POST);
                $data["rma_obj"]->set_client_id($_SESSION["client"]["id"]);
                $data["rma_obj"]->set_status(0);

                $start_pos = strpos($_POST["so_no"], '-');
                if ($start_pos != NULL) {
                    $so_no_trim = substr($_POST["so_no"], $start_pos + 1);
                } else {
                    $so_no_trim = $_POST["so_no"];
                }

                // consider cases with split order
                $start_splitpos = strpos($so_no_trim, '/');
                if ($start_splitpos != NULL) {
                    $so_no_trim_split = substr($so_no_trim, $start_pos + 1);
                } else {
                    $so_no_trim_split = $so_no_trim;
                }
                $so_no_trim_split = trim($so_no_trim_split);
                $proc = $this->so_model->get("dao", array("so_no" => $so_no_trim_split, "client_id" => $_SESSION["client"]["id"], "status" => 6));
                $data["rma_obj"]->set_so_no($so_no_trim_split);

                $data['data']['lang_text'] = $this->_get_language_file('', '', 'index');
                if (empty($proc)) {
                    $_SESSION["NOTICE"] = $data['data']['lang_text']['rma_warning'];
                    $_SESSION["rma_obj"] = serialize($data["rma_obj"]);
                } else {
                    if ($rma_obj = $this->so_model->add("rma_dao", $data["rma_obj"])) {
                        $this->rma_confirm($rma_obj->get_id());
                        return;
                    } else {
                        $_SESSION["NOTICE"] = "Error: " . __LINE__;
                    }
                }
            }
        }

        $this->index("rma");
    }

    public function rma_confirm($rma_no = "")
    {
        $data["display_id"] = 8;
        $rma_obj = $this->so_model->get("rma_dao", array("id" => $rma_no, "client_id" => $_SESSION["client"]["id"]));
        if ($rma_obj) {
            $this->index("rma", $rma_no);
            unset($_SESSION['rma_obj']);
        } else {
            Redirect("/myaccount/rma");
        }
    }

    /*
    no one use rma_edit, so do not change to single template for all language.
    */
    public function rma_edit($rma_id = "")
    {
        $data["display_id"] = 8;
        if ($this->input->post("update")) {
            $this->so_model->include_vo("rma_dao");
            $rma_obj = unserialize($_SESSION["rma_vo"]);
            $components_list = $_POST["components"];
            for ($i = 0; $i < 14; $i++) {
                $components[$i] = $_POST["components"][$i] ? 1 : 0;
            }
            $components[$i - 1] = $_POST["other"];
            set_value($rma_obj, $_POST);
            //$data["rma_obj"]->set_client_id($_SESSION["client"]["id"]);
            //$data["rma_obj"]->set_status(0);
            $rma_obj->set_components(implode('|', $components));

            $proc = $this->so_model->get("dao", array("so_no" => $rma_obj->get_so_no(), "client_id" => $rma_obj->get_client_id(), "status" => 6));
            $wn = array("en" => "Update failed. Please try again or contact customer support if this repeats.",
                "de" => "Update gescheitert. Bitte versuchen Sie es erneut oder kontaktieren Sie unseren Kundendienst.",
                "fr" => "La mise ? jour a échoué. Merci d'essayer de nouveau ou contacter notre service clientèle si cela se produit de nouveau.",
                "es" => "Actualización fallida. Por favor, inténtelo de nuevo o póngase en contacto con atención al cliente si esto se repite.",
                "pt" => "Update failed. Please try again or contact customer support if this repeats.",
                "nl" => "Update failed. Please try again or contact customer support if this repeats.",
                "ja" => "Update failed. Please try again or contact customer support if this repeats.",
                "pl" => "Update failed. Please try again or contact customer support if this repeats.",
                "it" => "Update failed. Please try again or contact customer support if this repeats.",
                "da" => "Update failed. Please try again or contact customer support if this repeats.",
                "ko" => "Update failed. Please try again or contact customer support if this repeats.",
                "tr" => "Update failed. Please try again or contact customer support if this repeats.",
                "sv" => "Update failed. Please try again or contact customer support if this repeats.",
                "no" => "Update failed. Please try again or contact customer support if this repeats.",
                "pt-br" => "Update failed. Please try again or contact customer support if this repeats.",
                "ru" => "Update failed. Please try again or contact customer support if this repeats.");
            if (empty($proc)) {

                $_SESSION["NOTICE"] = $wn[get_lang_id()];
            } else {
                if ($this->so_model->update("rma_dao", $rma_obj) !== FALSE) {
                    unset($_SESSION["NOTICE"]);
                    Redirect("/myaccount/rma_confirm/" . $rma_id);
                } else {
                    echo $this->db->last_query() . " " . $this->db->_error_message();
                    $_SESSION["NOTICE"] = $wn[get_lang_id()];
                }
            }
        }
        $rma_obj = $this->so_model->get("rma_dao", array("id" => $rma_id, "client_id" => $_SESSION["client"]["id"]));
        if ($rma_obj) {
            $data["rma_obj"] = $rma_obj;
            $data["notice"] = $_SESSION["NOTICE"];
            $_SESSION["rma_vo"] = serialize($rma_obj);
            $data["order"] = $this->so_model->get("dao", array("so_no" => $rma_obj->get_so_no()));

            $data["components_list"] = $this->components_list[get_lang_id()];
            $this->load_view('myaccount/rma_edit_' . get_lang_id(), $data);
        } else {
            Redirect("/myaccount/rma");
        }
    }

    public function rma_print($rma_no = "")
    {
        $rma_obj = $this->so_model->get("rma_dao", array("id" => $rma_no, "client_id" => $_SESSION["client"]["id"]));
        if ($rma_obj) {
            $data["rma_obj"] = $rma_obj;
            $country_obj = $this->country_model->country_service->get(array("id" => $rma_obj->get_country_id()));
            $data["country_name"] = $country_obj->get_name();
            $data["order"] = $this->so_model->get("dao", array("so_no" => $rma_obj->get_so_no()));
            $data["components_list"] = $this->components_list[get_lang_id()];
            $data['lang_text'] = $this->_get_language_file();
            $this->load_view('myaccount/rma_print', $data);
        } else {
            show_404();
        }
    }

    public function rma_addr_js()
    {
        header("Content-type: text/javascript; charset: UTF-8");
        header("Cache-Control: must-revalidate");
        $offset = 60 * 60 * 24;
        $ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
        header($ExpStr);

        //$sell_to_list = $this->country_model->get_sell_to_list(get_lang_id());
        $sell_to_list = $this->country_model->get_rma_fc_list(get_lang_id());
        $carr = array();
        foreach ($sell_to_list as $country) {
            $carr[] = "'" . $country->get_id() . "':['" . strtolower(substr($country->get_fc_id(), 0, 2)) . "','" . addslashes($country->get_name()) . "']";
        }

        $js = "var rmacountrylist = {" . implode(",", $carr) . "}";
        unset($carr);
        $js .= "

                function get_content(country_id)
                {

                    t = country_id?rmacountrylist[country_id][0]:'';
                    switch(t)
                    {
                        case 'us':
                        addr = '<b>RMR Holdings</b><br />100 Executive Boulevard, Suite 101, <br />Ossining, New York, 10562<br />USA';
                        break;

                        case 'sg':
                        addr = '<b>71 Ubi Crescent</b><br />Unit 04-09<br />Postal Code 408571<br />Singapore';
                        break;

                        case 'nz':
                        addr = '<b>Plaza Level</b><br/>41 Shortland St<br>Auckland, 1010<br>New Zealand';
                        break;

                        case 'uk':
                        addr = '<b>Dorchester House</b><br/Station Road<br>Letchworth,<br>SG6 3AW<br>United Kingdom';
                        break;

                        case 'au':
                        addr = '<b>Level 5</b><br/>11 Queens Road<br/>Melbourne<br/>VIC 3004';
                        break;

                        case 'hk':
                        addr = '<b>32/F Tower 1</b><br/>Millennium City 1<br/>388 Kwun Tong Road<br/>Kwun Tong<br/>Kowloon';
                        break;

                        default:
                        addr = '<i>Please select your country first</i>';
                        break;
                    }

                    if(addr)
                    {
                        $('#rmaaddr').html(addr);
                    }
                }

                function draw_cl(select)
                {
                    document.write(\"<option value=''></option>\");
                    var selected = '';
                    for(var i in rmacountrylist)
                    {
                        selected = select == i?'SELECTED':'';
                        document.write('<option value='+i+' '+selected+'>'+rmacountrylist[i][1]+'</option>');
                    }
                }
                ";

        echo $js;
    }

    function print_invoice($so_no = "")
    {
        $client_id = $_SESSION["client"]["id"];
        if (!$so_obj = $this->so_model->so_service->get(array("so_no" => $so_no, "client_id" => $client_id))) {
            show_404();
        }
        $html = $this->so_service->get_print_invoice_content(array($so_no), 1, get_lang_id());
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/MSIE/i', $u_agent)) {
            // Instead of opening the PDF in browser, prompt user to download file if it's IE.
            $att_file = $this->pdf_rendering_service->convert_html_to_pdf($html, null, "D", "en");
        } else {
            $att_file = $this->pdf_rendering_service->convert_html_to_pdf($html, null, "I", "en");
        }
    }

}
