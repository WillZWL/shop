<?php
defined('BASEPATH') OR exit('No direct script access allowed');
DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);
$ws_array = array(NULL, 'index', 'rma', 'rma_confirm', 'rma_edit', 'rma_print', 'update_password');
if (in_array($GLOBALS["URI"]->segments[2], $ws_array)) {
    DEFINE ('PLATFORM_TYPE', 'SKYPE');
}


use ESG\Panther\Service\CountryService;

class Myaccount extends PUB_Controller
{
    private $components_list;

    public function __construct()
    {
        parent::__construct(array('require_login' => 1, 'load_header' => 1));
        $this->load->helper(array('url', 'object', 'notice', 'lang', 'price'));
        $this->load->library(array('encryption', 'encrypt'));
        if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != "on") {
            $httpsUrl = "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
            if ($_SERVER['QUERY_STRING'] != "") {
                $httpsUrl .= "?" . $_SERVER['QUERY_STRING'];
            }
            if (strpos($_SERVER["REQUEST_URI"], "notification") === false) {
                redirect($httpsUrl);
            }
        }
        $this->countryService = new CountryService;
    }

    public function profile()
    {
        $data['display_id'] = 15;
        $data["back"] = $this->input->get("back");
        $data['data']['lang_text'] = $this->getLanguageFile('', '', 'index');
        if ($this->input->post("posted")) {
            if (isset($_SESSION["client_obj"])) {
                $data["client_obj"] = unserialize($_SESSION["client_obj"]);
                if (!empty($_POST["password"])) {
                    $old_password = trim($this->input->post("old_password"));
                    $new_password = trim($this->input->post("password"));
                    $reconfirm_password = trim($this->input->post("confirm_password"));
                    $data['email'] = $_SESSION['client']['email'];
                    if ($old_password != $this->encryption->decrypt($data["client_obj"]->getPassword())) {
                        $_SESSION['NOTICE'] = 'Please Enter Correct Old Password.';
                    } elseif ($new_password != $reconfirm_password) {
                        $_SESSION['NOTICE'] = 'Confirm Password mismatch.';
                    } elseif ($old_password == $new_password) {
                        $_SESSION['NOTICE'] = 'New Password is same as Old Password.';
                    } else {
                        $update_password = $this->encryption->encrypt($new_password);
                    }
                }
                if (!$_SESSION['NOTICE']) {
                    if (empty($_POST["subscriber"])) {
                        $_POST["subscriber"] = 0;
                    }
                    unset($_POST["password"]);
                    set_value($data["client_obj"], $_POST);
                    $data["client_obj"]->setDelName($_POST["title"] . " " . $_POST["forename"] . " " . $_POST["surname"]);
                    $data["client_obj"]->setTitle($_POST["name_prefix"]);
                    $data["client_obj"]->setDelCompany($_POST["companyname"]);
                    $data["client_obj"]->setDelAddress1($_POST["address_1"]);
                    $data["client_obj"]->setDelAddress2($_POST["address_2"]);
                    $data["client_obj"]->setDelCity($_POST["city"]);
                    $data["client_obj"]->setDelState($_POST["state"]);
                    $data["client_obj"]->setDelCountryId($_POST["country_id"]);
                    $data["client_obj"]->setDelPostcode($_POST["postcode"]);
                    $data["client_obj"]->setDelTel1($_POST["tel_1"]);
                    $data["client_obj"]->setDelTel2($_POST["tel_2"]);
                    $data["client_obj"]->setDelTel3($_POST["tel_3"]);
                    $data["client_obj"]->setPartySubscriber(0);
                    $data["client_obj"]->setStatus(1);
                    if ($update_password) {
                        $data["client_obj"]->setPassword($update_password);

                        $depassword = $this->encryption->decrypt($data["client_obj"]->getPassword());
                        $encryptCode = $this->encrypt->encode($depassword);

                        $data["client_obj"]->setVerifyCode($encryptCode);
                    }
                    $email = $data["client_obj"]->getEmail();
                    $proc = $this->sc['Client']->getDao('Client')->get(array("email" => $email));
                    if (!empty($proc)) {
                        if (!$this->sc['Client']->getDao('Client')->update($data["client_obj"])) {
                            $_SESSION['NOTICE'] = 'Profile Update Failed.';
                        } else {
                            $_SESSION["NOTICE"] = 'Update Success.';
                        }
                    } else {
                        $_SESSION["NOTICE"] = 'Client does not exist.';
                    }
                }
            }
        }
        $this->index("profile");
    }

    public function index($page = "order", $rma_no = "")
    {
        $client_id = $_SESSION["client"]["Id"];
        if ($client_id) {
            $client_orders = $this->getClientOrderList($client_id);
            $data["show_bank_transfer_contact"] = $client_orders['show_bank_transfer_contact'];
            $data['show_partial_ship_text'] = $client_orders['show_partial_ship_text'];
            $data['orderlist'] = $client_orders['orderlist'];
            $unpaid_orderlist = $this->getUnpaidOrderList($client_id);
            $data['unpaid_orderlist'] = $unpaid_orderlist['unpaid_orderlist'];
            if ($unpaid_orderlist['show_bank_transfer_contact']) {
                $data['show_bank_transfer_contact'] = $unpaid_orderlist['show_bank_transfer_contact'];
            }
            // edit profile
           if (($data["client_obj"] = $this->sc['Client']->getDao('Client')->get(array("id" => $client_id))) === FALSE) {
                $_SESSION["NOTICE"] = "Error: " . __LINE__;
            } else {
                $_SESSION["client_obj"] = serialize($data["client_obj"]);
            }

            $where = array();
            $option = array();
            $where["c.country_id"] = strtoupper(PLATFORM);
            $where["l.lang_id"] = $lang_id;
            $where["c.status"] = 1;
            $where["c.allow_sell"] = 1;

            $option["limit"] = 1;

            $data["bill_to_list"] = $this->countryService->getCountryExtDao()->getCountryNameInLang($where, $option);

            //$data["bill_to_list"] = $this->sc['countryModel']->getCountryNameInLang(get_lang_id(), 1);
            // rma

            $data["rma_obj"] = unserialize($_SESSION["rma_obj"]);
            if (empty($data["rma_obj"])) {
                if (($data["rma_obj"] = $this->sc['So']->getDao("Rma")->get()) === FALSE) {
                    $_SESSION["NOTICE"] = "Error: " . __LINE__;
                } else {
                    $_SESSION["rma_vo"] = serialize($data["rma_obj"]);
                }
            }
            // rma_confirm
            $data["rma_confirm"] = 0;
            if ($page == "rma" && $rma_no) {
                if ($data["rma_obj"] = $this->sc['So']->getDao('Rma')->get(["id" => $rma_no, "client_id" => $client_id])) {
                    $data["rma_confirm"] = 1;
                }
            }
            $data["notice"] = notice();
            unset($_SESSION["NOTICE"]);
            $data['page'] = $page;
            $data['lang_text'] = $this->get_language_file('', '', 'index');
            $data['lang_id'] = get_lang_id();
            $data['title'] = array('Mr', 'Mrs', 'Miss', 'Dr');
            $show_unpaid_status = array(
                0=>"<b>Pending Payment</b><br>We have not received the payment for your order.",
                1=>"<b>Incomplete Payment</b><br>The amount received in our bank account does not correspond to the total amount of your order.");
            $this->load->view('myaccount/index.php', $data);
        } else {
            redirect(base_url());
        }
    }

    public function getUnpaidOrderList($client_id)
    {
         # SBF #3591 show unpaid/underpaid bank transfers
        $payment_gateway_arr = array("w_bank_transfer"); # determines what payment gateway will show
        $unpaid_orderlist = $this->sc['So']->getDao('So')->getUnpaidOrderHistory($client_id, $payment_gateway_arr);
        if ($unpaid_orderlist) {
            foreach ($unpaid_orderlist as $unpaid_obj) {
                $status = array();
                $status = $this->sc['soModel']->getOrderStatus($unpaid_obj);
                $data['unpaid_orderlist'][$unpaid_obj->getSoNo()]['currency_id'] = $unpaid_obj->getCurrencyId();
                $data['unpaid_orderlist'][$unpaid_obj->getSoNo()]['client_id'] = $unpaid_obj->getClientId();
                $data['unpaid_orderlist'][$unpaid_obj->getSoNo()]['order_date'] = date("Y-m-d", strtotime($unpaid_obj->getOrderCreateDate()));
                $data['unpaid_orderlist'][$unpaid_obj->getSoNo()]['delivery_name'] = $unpaid_obj->getDeliveryName();
                $data['unpaid_orderlist'][$unpaid_obj->getSoNo()]['order_status'] = $status["status"];
                $data['unpaid_orderlist'][$unpaid_obj->getSoNo()]['status_desc'] = $status["desc"];
                $data['unpaid_orderlist'][$unpaid_obj->getSoNo()]["product_name"] .= $unpaid_obj->getProdName() . "</br>";
                $total_amount += $unpaid_obj->getAmount();
                $data['unpaid_orderlist'][$unpaid_obj->getSoNo()]["total_amount"] = platform_curr_format($total_amount);
                $data['unpaid_orderlist'][$unpaid_obj->getSoNo()]["net_diff_status"] = $unpaid_obj->getNetDiffStatus();
                if ($unpaid_obj->getPaymentGatewayId() == 'w_bank_transfer') {
                    $data["show_bank_transfer_contact"] = TRUE;
                }
                if ($net_diff_status = $unpaid_obj->getNetDiffStatus()) {
                    switch ($net_diff_status) {
                        # show respective lang_text for underpaid
                        case 3:
                            $data['unpaid_orderlist'][$unpaid_obj->getSoNo()]["unpaid_status"] = 1;
                            break;
                        default:
                            break;
                    }
                } else {
                    # show respective lang_text for unpaid
                    $data['unpaid_orderlist'][$unpaid_obj->getSoNo()]["unpaid_status"] = 0;
                }
            }
        }
        return $data;
    }

    public function getClientOrderList($client_id)
    {
        $orderlist = $this->sc['So']->getDao('So')->getOrderHistory($client_id);
        $data["show_bank_transfer_contact"] = FALSE;
        $data["show_partial_ship_text"] = FALSE;
        if ($orderlist) {
            foreach ($orderlist AS $obj) {
                if(($obj->getStatus() != 2) && ($obj->getHoldStatus() != 10)){
                    $status = array();
                    $so_no = $obj->getSoNo();
                    $status = $this->sc['soModel']->getOrderStatus($obj);
                    $data['orderlist'][$obj->getSoNo()]['join_split_so_no'] = $obj->getJoinSplitSoNo();
                    $data['orderlist'][$obj->getSoNo()]['currency_id'] = $obj->getCurrencyId();
                    $data['orderlist'][$obj->getSoNo()]['client_id'] = $obj->getClientId();
                    $data['orderlist'][$obj->getSoNo()]['order_date'] = date("Y-m-d", strtotime($obj->getOrderCreateDate()));
                    $data['orderlist'][$obj->getSoNo()]['delivery_name'] = $obj->getDeliveryName();
                    $data['orderlist'][$obj->getSoNo()]['order_status'] = $status["status"];
                    $data['orderlist'][$obj->getSoNo()]['status_desc'] = $status["desc"];
                    $data['orderlist'][$obj->getSoNo()]["product_name"] .= $obj->getProdName() . "</br>";
                    $total_amount += $obj->getAmount();
                    $is_shipped = ($obj->getStatus() == 6 && $obj->getRefundStatus() == 0 && $obj->getHoldStatus() == 0) ? TRUE : FALSE;

                    $data['orderlist'][$obj->getSoNo()]["total_amount"] = platform_curr_format($total_amount);
                    if ($obj->getPaymentGatewayId() == 'w_bank_transfer') {
                        $data["show_bank_transfer_contact"] = TRUE;
                    }
                    $sosh_obj = $this->sc['So']->getShippingInfo(array("soal.so_no" => $obj->getSoNo()));
                    if ($sosh_obj)
                        $data['orderlist'][$obj->getSoNo()]['tracking_link'] = $this->sc['Courier']->get(array("id" => $sosh_obj->getCourierId()));
                    else
                        $data['orderlist'][$obj->getSoNo()]['tracking_link'] = "";

                    if (isset($status["courier_name"])) {
                        $data['orderlist'][$obj->getSoNo()]["courier_name"] = $status["courier_name"];
                    }
                    if (isset($status["tracking_url"])) {
                        $data['orderlist'][$obj->getSoNo()]["tracking_url"] = $status["tracking_url"];
                    }
                    if (isset($status["tracking_number"])) {
                        $data['orderlist'][$obj->getSoNo()]["tracking_number"] = $status["tracking_number"];
                    }
                    // show split order text
                    $split_so_group = $obj->getSplitSoGroup();
                    if (isset($split_so_group) && $split_so_group != $obj->getSoNo()) {
                        $data["show_partial_ship_text"] = TRUE;
                    }
                    if($is_shipped && (strtotime($obj->getOrderCreateDate()) > strtotime('3 months ago')))
                    {
                        $data['orderlist'][$obj->getSoNo()]["print_invoice_html"] = '<br /><a href="' . base_url() . 'myaccount/print_invoice/' . $so_no . '" target="_blank" style="font-size:10px;"><u>Print Invoice</u></a>';
                    }
                }
            }
        }
        return $data;
    }

    public function rma()
    {
        $data['display_id'] = 8;
        unset($_SESSION["NOTICE"]);
        if ($this->input->post("posted")) {
            if (isset($_SESSION["rma_vo"])) {
                $data["rma_obj"] = unserialize($_SESSION["rma_vo"]);
                set_value($data["rma_obj"], $_POST);
                $data["rma_obj"]->setClientId($_SESSION["client"]["id"]);
                $data["rma_obj"]->setStatus(0);
                $start_pos = strpos($this->input->post('so_no'), '-');
                if ($start_pos != NULL) {
                    $so_no_trim = substr($this->input->post('so_no'), $start_pos + 1);
                } else {
                    $so_no_trim = $this->input->post('so_no');
                }
                // consider cases with split order
                $start_splitpos = strpos($so_no_trim, '/');
                if ($start_splitpos != NULL) {
                    $so_no_trim_split = substr($so_no_trim, $start_pos + 1);
                } else {
                    $so_no_trim_split = $so_no_trim;
                }
                $so_no_trim_split = trim($so_no_trim_split);
                $proc = $this->sc['So']->getDao('So')->get(array("so_no" => $so_no_trim_split, "client_id" => $_SESSION["client"]["id"], "status" => 6));
                $data["rma_obj"]->setSoNo($so_no_trim_split);
                $data['data']['lang_text'] = $this->getLanguageFile('', '', 'index');
                if (empty($proc)) {
                    $_SESSION["NOTICE"] = 'This order has not been dispatched, please verify that the order number is correct, or contact our customer service team.';
                    $_SESSION["rma_obj"] = serialize($data["rma_obj"]);
                } else {
                    if ($rma_obj = $this->sc['So']->getDao('Rma')->add($data["rma_obj"])) {
                        $this->rmaConfirm($rma_obj->getId());
                        return;
                    } else {
                        $_SESSION["NOTICE"] = "Error: " . __LINE__;
                    }
                }
            }
        }
        $this->index("rma");
    }

    public function rmaConfirm($rma_no = "")
    {
        $data["display_id"] = 8;
        $rma_obj = $this->sc['So']->getDao("Rma")->get(array("id" => $rma_no, "client_id" => $_SESSION["client"]["id"]));
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
            $rma_obj = unserialize($_SESSION["rma_vo"]);
            $components_list = $_POST["components"];
            for ($i = 0; $i < 14; $i++) {
                $components[$i] = $_POST["components"][$i] ? 1 : 0;
            }
            $components[$i - 1] = $_POST["other"];
            set_value($rma_obj, $_POST);
            //$data["rma_obj"]->setClientId($_SESSION["client"]["id"]);
            //$data["rma_obj"]->setStatus(0);
            $rma_obj->setComponents(implode('|', $components));

            $proc = $this->sc['So']->getDao("So")->get(array("so_no" => $rma_obj->getSoNo(), "client_id" => $rma_obj->getClientId(), "status" => 6));
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
                if ($this->sc['So']->getDao('Rma')->update($rma_obj) !== FALSE) {
                    unset($_SESSION["NOTICE"]);
                    Redirect("/myaccount/rma_confirm/" . $rma_id);
                } else {
                    echo $this->db->last_query() . " " . $this->db->_error_message();
                    $_SESSION["NOTICE"] = $wn[get_lang_id()];
                }
            }
        }
        $rma_obj = $this->sc['So']->getDao('Rma')->get(array("id" => $rma_id, "client_id" => $_SESSION["client"]["id"]));
        if ($rma_obj) {
            $data["rma_obj"] = $rma_obj;
            $data["notice"] = $_SESSION["NOTICE"];
            $_SESSION["rma_vo"] = serialize($rma_obj);
            $data["order"] = $this->sc['So']->getDao('So')->get(array("so_no" => $rma_obj->getSoNo()));

            $data["components_list"] = $this->components_list[get_lang_id()];
            $this->load_view('myaccount/rma_edit_' . get_lang_id(), $data);
        } else {
            Redirect("/myaccount/rma");
        }
    }

    public function rma_print($rma_no = "")
    {
        $rma_obj = $this->sc['So']->getDao('Rma')->get(array("id" => $rma_no, "client_id" => $_SESSION["client"]["id"]));
        if ($rma_obj) {
            $data["rma_obj"] = $rma_obj;
            $country_obj = $this->country_model->country_service->get(array("id" => $rma_obj->get_country_id()));
            $data["country_name"] = $country_obj->get_name();
            $data["order"] = $this->sc['So']->getDao('So')->get(array("so_no" => $rma_obj->getSoNo()));
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
        if (!$so_obj = $this->sc['So']->getDao('So')->get(array("so_no" => $so_no, "client_id" => $client_id))) {
            show_404();
        }
        $html = $this->sc['So']->get_print_invoice_content(array($so_no), 1, get_lang_id());
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/MSIE/i', $u_agent)) {
            // Instead of opening the PDF in browser, prompt user to download file if it's IE.
            $att_file = $this->sc['PdfRendering']->convert_html_to_pdf($html, null, "D", "en");
        } else {
            $att_file = $this->sc['PdfRendering']->convert_html_to_pdf($html, null, "I", "en");
        }
    }

}
