<?php defined('BASEPATH') OR exit('No direct script access allowed');

load_class('MY_Datagrid', FALSE);
require_once("chargeback_admin_grid.php");

// ALTER TABLE `template`
// ADD COLUMN `message_html`  text NOT NULL AFTER `subject`,
// ADD COLUMN `message_alt`  text NOT NULL AFTER `message_html`;

class Chargeback_admin extends MY_Controller
{
    protected $app_id = "ORD0028";
    private $lang_id = "en";
    private $model;
    private $export_filename;

    private $gridcontent = "";
    private $s;

    public function __construct()
    {
        parent::__construct();
        // $this->load->model('report/sales_report_model');
        $this->load->helper(array('html_dom'));
        $this->load->library('service/so_service');
        $this->load->library('service/template_service');
        // $this->load->library('service/country_service');
        // $this->load->library('service/payment_gateway_service');
        // $this->load->library('service/so_shipment_service');
        // $this->_set_model($this->sales_report_model);
        // $this->_set_export_filename('sales_report.csv');
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }

    public function _set_app_id($value)
    {
        $this->app_id = $value;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    function index()
    {
        // if (!empty($_POST))
        {
            $s = new Chargeback_admin_grid();
            // $where = $this->create_criteria_from_post();
            if ($where != "") {
                $s->set_where($where);
                // var_dump("Setting {$_SESSION['where']}");
            }

            $this->gridheader = $s->get_DG_Header();
            $this->gridcontent = $s->index();
        }
        // elseump
        // {
        //  $_SESSION["where"] = "";
        // }

        // echo "<pre>"; var_dump($_POST); echo "</pre>";
        echo $this->get_index_html();   // must be last
        die();
    }

    function get_index_html()
    {
        $h = $this->get_unprocessed_index_html();
        return $h;
    }

    private function get_unprocessed_index_html()
    {
        return <<<HTML

    <!DOCTYPE HTML>
    <html lang="en-US">
    <head>

        <title></title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />



        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="http://www.tfaforms.com/form-builder/4.1.0/css/wforms-layout.css" rel="stylesheet" type="text/css" />
        <!--[if IE 8]>
        <link href="http://www.tfaforms.com/form-builder/4.1.0/css/wforms-layout-ie8.css" rel="stylesheet" type="text/css" />
        <![endif]-->
        <!--[if IE 7]>
        <link href="http://www.tfaforms.com/form-builder/4.1.0/css/wforms-layout-ie7.css" rel="stylesheet" type="text/css" />
        <![endif]-->
        <!--[if IE 6]>
        <link href="http://www.tfaforms.com/form-builder/4.1.0/css/wforms-layout-ie6.css" rel="stylesheet" type="text/css" />
        <![endif]-->

        <link href="http://www.tfaforms.com/themes/get/17252" rel="stylesheet" type="text/css" />
        <link href="http://www.tfaforms.com/form-builder/4.1.0/css/wforms-jsonly.css" rel="alternate stylesheet" title="This stylesheet activated by javascript" type="text/css" />
        <script type="text/javascript" src="http://www.tfaforms.com/wForms/3.7/js/wforms.js"></script>
        <script type="text/javascript">
            if(wFORMS.behaviors.prefill) wFORMS.behaviors.prefill.skip = true;
        </script>
        <link rel="stylesheet" type="text/css" href="http://www.tfaforms.com/form-builder/4.1.0/css/wforms_calendar.css" />
        <script type="text/javascript" src="http://www.tfaforms.com/js/yui/yui-min.js" ></script>
        <script type="text/javascript" src="http://www.tfaforms.com/wForms/3.7/js/wforms_calendar.js"></script>
        <script type="text/javascript" src="http://www.tfaforms.com/wForms/3.7/js/localization-en_US.js"></script>

        <!-- for grid -->
        <script src='/js/dgscripts.js' type="text/javascript" language='javascript'></script>

        {$this->gridheader}
    </head>
    <body class="default wFormWebPage">
        <div id="tfaContent">
            <div class="wFormContainer" style="width: 1100px;" >
                <div class="">
                    {$this->gridcontent}
                    <div class="wForm" id="tfa_0-WRPR" dir="ltr">
                        <div class="codesection" id="code-tfa_0">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>
HTML;

        // <table width=100%>
        //     <tr>
        //         <td>Platform</td>
        //         <td>Competitor</td>
        //         <td>Shipping Cost</td>
        //         <td>Currency</td>
        //         <td>Competitor Price</td>
        //         <td>Our Price</td>
        //         <td>Difference</td>
        //         <td>Margin</td>
        //         <td>Listing Status</td>
        //         <td>Online Orders</td>
        //         <td>Offline Orders</td>
        //     </tr>
        // </table>

    }

    function create_criteria_from_post()
    {
        $query = "";
        foreach ($_POST as $k => $v) {
            if (!empty($v)) {
                $kk = substr($k, 4);
                switch ($kk) {
                    case 6:
                        $query .= " and si.prod_sku = '$v' ";
                        break;

                    case 8:
                        $v = str_replace(" ", "%", $v);
                        $query .= " and si.prod_name like '%$v%' ";
                        break;
                    case 9:
                        $query .= " and pp.clearance = '$v' ";
                        break;

                    // master sku
                    case 39:
                        $query .= " and sm.ext_sku = '$v' ";
                        break;
                    case 51:
                        $d = date_parse($v);
                        $dd = "{$d["year"]}-{$d["month"]}-{$d["day"]}";
                        $query .= " and so.create_on >= '$dd' ";
                        break;
                    case 52:
                        $d = date_parse($v);
                        $dd = "{$d["year"]}-{$d["month"]}-{$d["day"]}";
                        $query .= " and so.create_on <= '$dd' ";
                        break;
                }
            }
        }

        return $query;
    }

    function update_email_template()
    {
        $query = "";
        foreach ($_POST as $k => $v) {
            if (!empty($v)) {
                $kk = substr($k, 4);
                switch ($kk) {
                    // subject
                    case 8:
                        // case 14:
                        // case 20:
                        $subject = $v;
                        break;

                    // message
                    case 1: // es
                        // case 15:
                        // case 21:
                        $message_alt = $v;
                        break;

                    // local sku
                    case 2:
                        $lang_id = $v;  // record this for use when redirecting
                        $where["lang_id"] = $this->convert_option_to_lang($v);
                        break;

                    case 998:
                        $so_no = $v;
                        break;
                    case 999:
                        $where["id"] = $v;
                        break;
                }
            }
        }

        $t = $this->template_service->get($where);
        $t->set_subject($subject);
        $t->set_message_alt($message_alt);

        $ret = $this->template_service->get_dao()->update($t, $where);
        redirect(base_url() . "order/chargeback_admin/record_email_template_click/{$t->get_id()}/{$so_no}?tfa_2=$lang_id");

        if ($ret)
            echo "UPDATE OK";
        else
            echo "UPDATE FAILED";

        $r = $this->template_service->get($where);

        echo "<PRE>";
        var_dump($r);
        $set = trim($set, ",");
        echo "<PRE>";
        var_dump($_POST);
    }

    private function convert_option_to_lang($option)
    {
        $lang_option["tfa_3"] = "en";
        $lang_option["tfa_4"] = "es";
        $lang_option["tfa_5"] = "fr";

        if (isset($lang_option["$option"])) return $lang_option["$option"];

        foreach ($lang_option as $k => $v)
            return $v;
    }

    public function record_email_template_click($template_name, $so_no)
    {
        $s = new Chargeback_admin_grid();
        $s->record_click("$template_name clicked", $so_no);

        $url = base_url() . "order/chargeback_admin/email_template/{$template_name}/{$so_no}";
        redirect($url);
    }

    function email_template($template_name, $so_no = "")
    {
        // use SQL to load the email templates from db
        $where["id"] = $template_name;
        $templates = $this->template_service->get_tpl_list($where);

        foreach ($templates as $t) {
            $message = $t->get_message_alt();
            $info = $this->so_service->get_dao()->get_chargeback_info($so_no);
            $variable_list = "";
            $info[0]["from"] = $_SESSION['user']['username'];

            $message = str_ireplace('\r', "\r", $message);
            $message = str_ireplace('\n', "\n", $message);

            // replace all the variables
            foreach ($info[0] as $k => $v) {
                $var = "[:{$k}:]";
                $message = str_ireplace($var, $v, $message);
                $variable_list .= "$var, ";
            }

            $tt = str_ireplace('\r', "\r", $t->get_message_alt());
            $tt = str_ireplace('\n', "\n", $tt);

            $template[$t->get_lang_id()]["subject"] = $t->get_subject();
            $template[$t->get_lang_id()]["message"] = $tt;
            $template[$t->get_lang_id()]["template"] = $message;
            $template["variable_list"] = trim($variable_list, ", ");
        }

        if (!isset($_GET["tfa_2"]))
            $_GET["tfa_2"] = $this->convert_lang_to_option($info[0]["lang_id"]);

        echo $this->get_edit_template_html($template_name, $template, $so_no);
        die();
    }

    private function convert_lang_to_option($lang)
    {
        $lang_option["tfa_3"] = "en";
        $lang_option["tfa_4"] = "es";
        $lang_option["tfa_5"] = "fr";

        foreach ($lang_option as $k => $v)
            if ($lang == $v) return $k;

        foreach ($lang_option as $k => $v) {
            // var_dump($k); die();
            return $k;
        }
    }

    function get_edit_template_html($template_name, $template, $so_no = "")
    {
        $h = $this->get_unprocessed_edit_template_html($template_name, $template, $so_no);
        $html = str_get_html($h);

        if (!empty($_GET)) {
            foreach ($_GET as $k => $v) {
                $kk = substr($k, 4);
                switch ($kk) {
                    default:

                        // translate $_GET onto the dropdowns
                        $t = $html->find("option[id=$v]", 0);
                        if ($t != null) {
                            $t->setAttribute("selected", "");
                            break;
                        }

                        // translate $_GET anything that starts with input
                        $t = $html->find("[id=$k]", 0);
                        if ($t != null) {
                            switch ($t->getAttribute("type")) {
                                case "text":
                                    $html->find("input[id=$k]", 0)->setAttribute("value", $_GET[$k]);
                                    break;
                            }
                            break;
                        }
                        break;
                }
            }
        }
        return $html;
    }

    private function get_unprocessed_edit_template_html($template_name, $template, $so_no = "")
    {

        return <<<HTML

    <!DOCTYPE HTML>
    <html lang="en-US">
    <head>
        <title>Template emails for chargebacks</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="http://www.tfaforms.com/form-builder/4.1.0/css/wforms-layout.css" rel="stylesheet" type="text/css" />
        <!--[if IE 8]>
        <link href="http://www.tfaforms.com/form-builder/4.1.0/css/wforms-layout-ie8.css" rel="stylesheet" type="text/css" />
        <![endif]-->
        <!--[if IE 7]>
        <link href="http://www.tfaforms.com/form-builder/4.1.0/css/wforms-layout-ie7.css" rel="stylesheet" type="text/css" />
        <![endif]-->
        <!--[if IE 6]>
        <link href="http://www.tfaforms.com/form-builder/4.1.0/css/wforms-layout-ie6.css" rel="stylesheet" type="text/css" />
        <![endif]-->

        <link href="http://www.tfaforms.com/themes/get/17252" rel="stylesheet" type="text/css" />
        <link href="http://www.tfaforms.com/form-builder/4.1.0/css/wforms-jsonly.css" rel="alternate stylesheet" title="This stylesheet activated by javascript" type="text/css" />
        <script type="text/javascript" src="http://www.tfaforms.com/wForms/3.7/js/wforms.js"></script>
        <script type="text/javascript">
            if(wFORMS.behaviors.prefill) wFORMS.behaviors.prefill.skip = true;
        </script>
        <script type="text/javascript" src="http://www.tfaforms.com/wForms/3.7/js/localization-en_US.js"></script>
    </head>
    <body class="default wFormWebPage">


        <div id="tfaContent">
            <div class="wFormContainer"  >

                <div class=""><div class="wForm" id="tfa_0-WRPR" dir="ltr">
                    <div class="codesection" id="code-tfa_0"></div>
                    <h3 class="wFormTitle" id="tfa_0-T">Template emails for chargebacks</h3>
                    <form method="post" action="/order/chargeback_admin/update_email_template" class="hintsBelow labelsAbove" id="tfa_0">
                        <div id="tfa_2-D" class="oneField   labelsRightAligned ">
                            <label id="tfa_2-L" for="tfa_2" class="label preField " style="width: 80px; min-width:0">Language</label>
                            <div class="inputWrapper">
                                <select id="tfa_2" name="tfa_2" class="">
                                    <option value="">Please select...</option>
                                    <option value="tfa_3" id="tfa_3" data-conditionals="#tfa_9" class="">English</option>
                                    <option value="tfa_4" id="tfa_4" data-conditionals="#tfa_13" class="">Spanish</option>
                                    <option value="tfa_5" id="tfa_5" data-conditionals="#tfa_19" class="">French</option>
                                </select>
                            </div>
                        </div>
                        <fieldset id="tfa_9" class="section" data-condition="`#tfa_3`">
                            <legend id="tfa_9-L">English</legend>
                            <div id="tfa_20-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_20-L" for="tfa_20" class="label preField " style="width: 70px; min-width:0">Output</label><div class="inputWrapper"><textarea style="width: 600px; height: 250px" id="tfa_20" name="tfa_20" class="">{$template["en"]["template"]}</textarea></div>
                            </div>
                            <div id="tfa_8-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_8-L" for="tfa_8" class="label preField " style="width: 70px; min-width:0">Variables</label><div class="inputWrapper">{$template["variable_list"]}</div>
                            </div>
                            <div id="tfa_8-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_8-L" for="tfa_8" class="label preField " style="width: 70px; min-width:0">Subject</label><div class="inputWrapper"><input type="text" id="tfa_8" name="tfa_8" value="{$template["en"]["subject"]}" style="width: 600px" placeholder="" default="" class=""></div>
                            </div>
                            <div id="tfa_1-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_1-L" for="tfa_1" class="label preField " style="width: 70px; min-width:0">Message</label><div class="inputWrapper"><textarea style="width: 600px; height: 250px" id="tfa_1" name="tfa_1" class="">{$template["en"]["message"]}</textarea></div>
                            </div>
                        </fieldset>
                        <fieldset id="tfa_13" class="section" data-condition="`#tfa_4`">
                            <legend id="tfa_13-L">Spanish</legend>
                            <div id="tfa_20-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_20-L" for="tfa_20" class="label preField " style="width: 70px; min-width:0">Output</label><div class="inputWrapper"><textarea style="width: 600px; height: 250px" id="tfa_20" name="tfa_20" class="">{$template["es"]["template"]}</textarea></div>
                            </div>
                            <div id="tfa_8-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_8-L" for="tfa_8" class="label preField " style="width: 70px; min-width:0">Variables</label><div class="inputWrapper">{$template["variable_list"]}</div>
                            </div>
                            <div id="tfa_14-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_14-L" for="tfa_14" class="label preField " style="width: 70px; min-width:0">Subject</label><div class="inputWrapper"><input type="text" id="tfa_8" name="tfa_8" value="{$template["es"]["subject"]}" style="width: 600px" placeholder="" default="" class=""></div>
                            </div>
                            <div id="tfa_15-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_15-L" for="tfa_15" class="label preField " style="width: 70px; min-width:0">Message</label><div class="inputWrapper"><textarea style="width: 600px; height: 250px" id="tfa_1" name="tfa_1" class="">{$template["es"]["message"]}</textarea></div>
                            </div>
                        </fieldset>
                        <fieldset id="tfa_19" class="section" data-condition="`#tfa_5`">
                            <legend id="tfa_19-L">French</legend>
                            <div id="tfa_20-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_20-L" for="tfa_20" class="label preField " style="width: 70px; min-width:0">Output</label><div class="inputWrapper"><textarea style="width: 600px; height: 250px" id="tfa_20" name="tfa_20" class="">{$template["fr"]["template"]}</textarea></div>
                            </div>
                            <div id="tfa_8-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_8-L" for="tfa_8" class="label preField " style="width: 70px; min-width:0">Variables</label><div class="inputWrapper">{$template["variable_list"]}</div>
                            </div>
                            <div id="tfa_8-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_8-L" for="tfa_8" class="label preField " style="width: 70px; min-width:0">Subject</label><div class="inputWrapper"><input type="text" id="tfa_8" name="tfa_8" value="{$template["fr"]["subject"]}" style="width: 600px" placeholder="" default="" class=""></div>
                            </div>
                            <div id="tfa_21-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_21-L" for="tfa_21" class="label preField " style="width: 70px; min-width:0">Message</label><div class="inputWrapper"><textarea style="width: 600px; height: 250px" id="tfa_1" name="tfa_1" class="">{$template["fr"]["message"]}</textarea></div>
                            </div>
                        </fieldset>
                        <div class="actions" id="tfa_0-A"><input type="submit" class="primaryAction" value="Submit"></div>
                        <div style="clear:both"></div>
                        <input type="hidden" value="{$so_no}" name="tfa_998" id="tfa_998" autocomplete="off">
                        <input type="hidden" value="{$template_name}" name="tfa_999" id="tfa_999" autocomplete="off">
                        <input type="hidden" value="318629" name="tfa_dbFormId" id="tfa_dbFormId">
                        <input type="hidden" value="" name="tfa_dbResponseId" id="tfa_dbResponseId">
                        <input type="hidden" value="a683c204fc0711c55de11ae98ccb47d5" name="tfa_dbControl" id="tfa_dbControl">
                        <input type="hidden" value="1392625600" name="tfa_dbTimeStarted" id="tfa_dbTimeStarted" autocomplete="off">
                        <input type="hidden" value="3" name="tfa_dbVersionId" id="tfa_dbVersionId">
                        <input type="hidden" value="" name="tfa_switchedoff" id="tfa_switchedoff">
                    </form>
                </div></div>
            </div>
        </div>
    </body>
    </html>
HTML;
    }
}