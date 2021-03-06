<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once(BASEPATH . 'plugins/Datagrid.php');

require_once("Chargeback_admin_grid.php");

class Chargeback_admin extends MY_Controller
{
    protected $appId="ORD0028";
    private $lang_id="en";
    private $model;
    private $export_filename;

    private $gridcontent = "";
    private $s;

    public function getAppId()
    {
        return $this->appId;
    }

    public function getLangId()
    {
        return $this->lang_id;
    }

    public function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        $s = new Chargeback_admin_grid();

        $this->gridheader = $s->get_DG_Header();
        $this->gridcontent = $s->index();

        echo $this->get_index_html();   // must be last
        die();
    }

    function create_criteria_from_post()
    {
        $query = "";
        foreach ($_POST as $k=>$v)
        {
            if (!empty($v))
            {
                $kk = substr($k, 4);
                switch ($kk)
                {
                    case 6:     $query .= " and si.prod_sku = '$v' ";               break;

                    case 8:     $v = str_replace(" ", "%", $v);
                                $query .= " and si.prod_name like '%$v%' ";         break;
                    case 9:     $query .= " and pp.clearance = '$v' ";              break;

                    // master sku
                    case 39:    $query .= " and sm.ext_sku = '$v' ";                break;
                    case 51:
                                $d = date_parse($v);
                                $dd = "{$d["year"]}-{$d["month"]}-{$d["day"]}";
                                $query .= " and so.create_on >= '$dd' ";                break;
                    case 52:
                                $d = date_parse($v);
                                $dd = "{$d["year"]}-{$d["month"]}-{$d["day"]}";
                                $query .= " and so.create_on <= '$dd' ";                break;
                }
            }
        }

        return $query;
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

    }

    function update_email_template()
    {
        $query = "";
        foreach ($_POST as $k=>$v)
        {
            if (!empty($v))
            {
                $v = mysql_escape_string($v);
                $kk = substr($k, 4);
                switch ($kk)
                {
                    // subject
                    case 8:
                        $subject = $v;      break;

                    // message
                    case 1: // es
                        $message_alt = $v;      break;

                    // local sku
                    case 2:
                        $platform_id = $this->convert_option_to_platform($v); break;

                    case 998:
                        $so_no = $v;    break;
                    case 999:
                        $tpl_id = $v;  break;
                }
            }
        }
        $where = [];
        $where["platform_id"] = $platform_id;
        $where["tpl_id"] = $tpl_id;
        if ($t = $this->sc['Template']->getDao('Template')->get($where)) {
            $t->setSubject($subject);
            $t->setMessageAlt($message_alt);

            $ret = $this->sc['Template']->getDao('Template')->update($t, $where);
        } else {
            $t = $this->sc['Template']->getDao('Template')->get();
            $t->setPlatformId($platform_id);
            $t->setTplId($tpl_id);
            $t->setTplName($tpl_id);
            $t->setSubject($subject);
            $t->setMessageAlt($message_alt);

            $ret = $this->sc['Template']->getDao('Template')->insert($t);
        }
        redirect(base_url()."order/chargeback_admin/record_email_template_click/{$t->getTplId()}/{$so_no}?tfa_2=$platform_id");

        if ($ret)
            echo "UPDATE OK";
        else
            echo "UPDATE FAILED";

        $r = $this->sc['Template']->getDao('Template')->get($where);

        echo "<PRE>";var_dump($r);
        $set = trim($set,",");
        echo "<PRE>";var_dump($_POST);
    }

    public function record_email_template_click($template_name, $so_no)
    {
        $s = new Chargeback_admin_grid();
        $s->record_click("$template_name clicked", $so_no);

        $url = base_url()."order/chargeback_admin/email_template/{$template_name}/{$so_no}";
        redirect($url);
    }

    private function convert_platform_to_option($platform)
    {
        $platform_option["tfa_3"] = "WEBAU";
        $platform_option["tfa_4"] = "WEBES";
        $platform_option["tfa_5"] = "WEBFR";
        $platform_option["tfa_6"] = "WEBBE";
        $platform_option["tfa_7"] = "WEBNL";
        $platform_option["tfa_10"] = "WEBPL";
        $platform_option["tfa_11"] = "WEBGB";
        $platform_option["tfa_12"] = "WEBIT";
        $platform_option["tfa_16"] = "WEBNZ";
        foreach ($platform_option as $k=>$v)
            if ($platform == $v) return $k;

        foreach ($platform_option as $k=>$v)
        {
            return $k;
        }
    }

    private function convert_option_to_platform($option)
    {
        $platform_option["tfa_3"] = "WEBAU";
        $platform_option["tfa_4"] = "WEBES";
        $platform_option["tfa_5"] = "WEBFR";
        $platform_option["tfa_6"] = "WEBBE";
        $platform_option["tfa_7"] = "WEBNL";
        $platform_option["tfa_10"] = "WEBPL";
        $platform_option["tfa_11"] = "WEBGB";
        $platform_option["tfa_12"] = "WEBIT";
        $platform_option["tfa_16"] = "WEBNZ";
        if (isset($platform_option["$option"])) return $platform_option["$option"];

        foreach ($platform_option as $k=>$v)
            return $v;
    }

    function email_template($template_name, $so_no = "")
    {
        // use SQL to load the email templates from db
        $where["tpl_id"] = $template_name;
        if ($templates = $this->sc['Template']->getTplList($where)) {
            foreach ($templates as $t)
            {
                $message = $t->getMessageAlt();
                $info = $this->sc['So']->getDao('So')->getChargebackInfo($so_no);
                $variable_list = "";
                $info[0]["from"] = $_SESSION['user']['username'];

                $message = str_ireplace('\r', "\r", $message);
                $message = str_ireplace('\n', "\n", $message);

                // replace all the variables
                foreach ($info[0] as $k=>$v)
                {
                    $var = "[:{$k}:]";
                    $message = str_ireplace($var, $v, $message);
                    $variable_list .= "$var, ";
                }

                $tt = str_ireplace('\r', "\r", $t->getMessageAlt());
                $tt = str_ireplace('\n', "\n", $tt);

                $template[$t->getPlatformId()]["subject"] = $t->getSubject();
                $template[$t->getPlatformId()]["message"] = $tt;
                $template[$t->getPlatformId()]["template"] = $message;
                $template["variable_list"] = trim($variable_list, ", ");
            }
        }


        if (!isset($_GET["tfa_2"]))
            $_GET["tfa_2"] = $this->convert_platform_to_option($info[0]["platform_id"]);

        echo $this->get_edit_template_html($template_name, $template, $so_no);
        die();
    }

    function get_edit_template_html($template_name, $template, $so_no = "")
    {
        $h = $this->get_unprocessed_edit_template_html($template_name, $template, $so_no);
        $html = str_get_html($h);

        if (!empty($_GET))
        {
            foreach ($_GET as $k=>$v)
            {
                $kk = substr($k, 4);
                switch ($kk)
                {
                    default:

                        // translate $_GET onto the dropdowns
                        $t = $html->find("option[id=$v]", 0);
                        if ($t != null)
                        {
                            $t->setAttribute("selected", "");
                            break;
                        }

                        // translate $_GET anything that starts with input
                        $t = $html->find("[id=$k]", 0);
                        if ($t != null)
                        {
                            switch ($t->getAttribute("type"))
                            {
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
                            <label id="tfa_2-L" for="tfa_2" class="label preField " style="width: 80px; min-width:0">PlatformId</label>
                            <div class="inputWrapper">
                                <select id="tfa_2" name="tfa_2" class="">
                                    <option value="">Please select...</option>
                                    <option value="tfa_3" id="tfa_3" data-conditionals="#tfa_9" class="">WEBAU</option>
                                    <option value="tfa_4" id="tfa_4" data-conditionals="#tfa_13" class="">WEBES</option>
                                    <option value="tfa_5" id="tfa_5" data-conditionals="#tfa_19" class="">WEBFR</option>
                                    <option value="tfa_6" id="tfa_6" data-conditionals="#tfa_24" class="">WEBBE</option>
                                    <option value="tfa_7" id="tfa_7" data-conditionals="#tfa_25" class="">WEBNL</option>
                                    <option value="tfa_10" id="tfa_10" data-conditionals="#tfa_26" class="">WEBPL</option>
                                    <option value="tfa_11" id="tfa_11" data-conditionals="#tfa_27" class="">WEBGB</option>
                                    <option value="tfa_12" id="tfa_12" data-conditionals="#tfa_28" class="">WEBIT</option>
                                    <option value="tfa_16" id="tfa_16" data-conditionals="#tfa_29" class="">WEBNZ</option>
                                </select>
                            </div>
                        </div>
                        <fieldset id="tfa_9" class="section" data-condition="`#tfa_3`">
                            <legend id="tfa_9-L">WEBAU</legend>
                            <div id="tfa_20-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_20-L" for="tfa_20" class="label preField " style="width: 70px; min-width:0">Output</label><div class="inputWrapper"><textarea style="width: 600px; height: 250px" id="tfa_20" name="tfa_20" class="">{$template["WEBAU"]["template"]}</textarea></div>
                            </div>
                            <div id="tfa_8-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_8-L" for="tfa_8" class="label preField " style="width: 70px; min-width:0">Variables</label><div class="inputWrapper">{$template["variable_list"]}</div>
                            </div>
                            <div id="tfa_8-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_8-L" for="tfa_8" class="label preField " style="width: 70px; min-width:0">Subject</label><div class="inputWrapper"><input type="text" id="tfa_8" name="tfa_8" value="{$template["WEBAU"]["subject"]}" style="width: 600px" placeholder="" default="" class=""></div>
                            </div>
                            <div id="tfa_1-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_1-L" for="tfa_1" class="label preField " style="width: 70px; min-width:0">Message</label><div class="inputWrapper"><textarea style="width: 600px; height: 250px" id="tfa_1" name="tfa_1" class="">{$template["WEBAU"]["message"]}</textarea></div>
                            </div>
                        </fieldset>
                        <fieldset id="tfa_13" class="section" data-condition="`#tfa_4`">
                            <legend id="tfa_13-L">WEBES</legend>
                            <div id="tfa_20-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_20-L" for="tfa_20" class="label preField " style="width: 70px; min-width:0">Output</label><div class="inputWrapper"><textarea style="width: 600px; height: 250px" id="tfa_20" name="tfa_20" class="">{$template["WEBES"]["template"]}</textarea></div>
                            </div>
                            <div id="tfa_8-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_8-L" for="tfa_8" class="label preField " style="width: 70px; min-width:0">Variables</label><div class="inputWrapper">{$template["variable_list"]}</div>
                            </div>
                            <div id="tfa_14-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_14-L" for="tfa_14" class="label preField " style="width: 70px; min-width:0">Subject</label><div class="inputWrapper"><input type="text" id="tfa_8" name="tfa_8" value="{$template["WEBES"]["subject"]}" style="width: 600px" placeholder="" default="" class=""></div>
                            </div>
                            <div id="tfa_15-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_15-L" for="tfa_15" class="label preField " style="width: 70px; min-width:0">Message</label><div class="inputWrapper"><textarea style="width: 600px; height: 250px" id="tfa_1" name="tfa_1" class="">{$template["WEBES"]["message"]}</textarea></div>
                            </div>
                        </fieldset>
                        <fieldset id="tfa_19" class="section" data-condition="`#tfa_5`">
                            <legend id="tfa_19-L">WEBFR</legend>
                            <div id="tfa_20-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_20-L" for="tfa_20" class="label preField " style="width: 70px; min-width:0">Output</label><div class="inputWrapper"><textarea style="width: 600px; height: 250px" id="tfa_20" name="tfa_20" class="">{$template["WEBFR"]["template"]}</textarea></div>
                            </div>
                            <div id="tfa_8-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_8-L" for="tfa_8" class="label preField " style="width: 70px; min-width:0">Variables</label><div class="inputWrapper">{$template["variable_list"]}</div>
                            </div>
                            <div id="tfa_8-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_8-L" for="tfa_8" class="label preField " style="width: 70px; min-width:0">Subject</label><div class="inputWrapper"><input type="text" id="tfa_8" name="tfa_8" value="{$template["WEBFR"]["subject"]}" style="width: 600px" placeholder="" default="" class=""></div>
                            </div>
                            <div id="tfa_21-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_21-L" for="tfa_21" class="label preField " style="width: 70px; min-width:0">Message</label><div class="inputWrapper"><textarea style="width: 600px; height: 250px" id="tfa_1" name="tfa_1" class="">{$template["WEBFR"]["message"]}</textarea></div>
                            </div>
                        </fieldset>
                        <fieldset id="tfa_24" class="section" data-condition="`#tfa_6`">
                            <legend id="tfa_24-L">WEBBE</legend>
                            <div id="tfa_20-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_20-L" for="tfa_20" class="label preField " style="width: 70px; min-width:0">Output</label><div class="inputWrapper"><textarea style="width: 600px; height: 250px" id="tfa_20" name="tfa_20" class="">{$template["WEBBE"]["template"]}</textarea></div>
                            </div>
                            <div id="tfa_8-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_8-L" for="tfa_8" class="label preField " style="width: 70px; min-width:0">Variables</label><div class="inputWrapper">{$template["variable_list"]}</div>
                            </div>
                            <div id="tfa_8-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_8-L" for="tfa_8" class="label preField " style="width: 70px; min-width:0">Subject</label><div class="inputWrapper"><input type="text" id="tfa_8" name="tfa_8" value="{$template["WEBBE"]["subject"]}" style="width: 600px" placeholder="" default="" class=""></div>
                            </div>
                            <div id="tfa_21-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_21-L" for="tfa_21" class="label preField " style="width: 70px; min-width:0">Message</label><div class="inputWrapper"><textarea style="width: 600px; height: 250px" id="tfa_1" name="tfa_1" class="">{$template["WEBBE"]["message"]}</textarea></div>
                            </div>
                        </fieldset>
                        <fieldset id="tfa_25" class="section" data-condition="`#tfa_7`">
                            <legend id="tfa_25-L">WEBNL</legend>
                            <div id="tfa_20-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_20-L" for="tfa_20" class="label preField " style="width: 70px; min-width:0">Output</label><div class="inputWrapper"><textarea style="width: 600px; height: 250px" id="tfa_20" name="tfa_20" class="">{$template["WEBNL"]["template"]}</textarea></div>
                            </div>
                            <div id="tfa_8-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_8-L" for="tfa_8" class="label preField " style="width: 70px; min-width:0">Variables</label><div class="inputWrapper">{$template["variable_list"]}</div>
                            </div>
                            <div id="tfa_8-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_8-L" for="tfa_8" class="label preField " style="width: 70px; min-width:0">Subject</label><div class="inputWrapper"><input type="text" id="tfa_8" name="tfa_8" value="{$template["WEBNL"]["subject"]}" style="width: 600px" placeholder="" default="" class=""></div>
                            </div>
                            <div id="tfa_21-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_21-L" for="tfa_21" class="label preField " style="width: 70px; min-width:0">Message</label><div class="inputWrapper"><textarea style="width: 600px; height: 250px" id="tfa_1" name="tfa_1" class="">{$template["WEBNL"]["message"]}</textarea></div>
                            </div>
                        </fieldset>
                        <fieldset id="tfa_26" class="section" data-condition="`#tfa_10`">
                            <legend id="tfa_26-L">WEBPL</legend>
                            <div id="tfa_20-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_20-L" for="tfa_20" class="label preField " style="width: 70px; min-width:0">Output</label><div class="inputWrapper"><textarea style="width: 600px; height: 250px" id="tfa_20" name="tfa_20" class="">{$template["WEBPL"]["template"]}</textarea></div>
                            </div>
                            <div id="tfa_8-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_8-L" for="tfa_8" class="label preField " style="width: 70px; min-width:0">Variables</label><div class="inputWrapper">{$template["variable_list"]}</div>
                            </div>
                            <div id="tfa_8-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_8-L" for="tfa_8" class="label preField " style="width: 70px; min-width:0">Subject</label><div class="inputWrapper"><input type="text" id="tfa_8" name="tfa_8" value="{$template["WEBPL"]["subject"]}" style="width: 600px" placeholder="" default="" class=""></div>
                            </div>
                            <div id="tfa_21-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_21-L" for="tfa_21" class="label preField " style="width: 70px; min-width:0">Message</label><div class="inputWrapper"><textarea style="width: 600px; height: 250px" id="tfa_1" name="tfa_1" class="">{$template["WEBPL"]["message"]}</textarea></div>
                            </div>
                        </fieldset>
                        <fieldset id="tfa_27" class="section" data-condition="`#tfa_11`">
                            <legend id="tfa_27-L">WEBGB</legend>
                            <div id="tfa_20-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_20-L" for="tfa_20" class="label preField " style="width: 70px; min-width:0">Output</label><div class="inputWrapper"><textarea style="width: 600px; height: 250px" id="tfa_20" name="tfa_20" class="">{$template["WEBGB"]["template"]}</textarea></div>
                            </div>
                            <div id="tfa_8-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_8-L" for="tfa_8" class="label preField " style="width: 70px; min-width:0">Variables</label><div class="inputWrapper">{$template["variable_list"]}</div>
                            </div>
                            <div id="tfa_8-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_8-L" for="tfa_8" class="label preField " style="width: 70px; min-width:0">Subject</label><div class="inputWrapper"><input type="text" id="tfa_8" name="tfa_8" value="{$template["WEBGB"]["subject"]}" style="width: 600px" placeholder="" default="" class=""></div>
                            </div>
                            <div id="tfa_21-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_21-L" for="tfa_21" class="label preField " style="width: 70px; min-width:0">Message</label><div class="inputWrapper"><textarea style="width: 600px; height: 250px" id="tfa_1" name="tfa_1" class="">{$template["WEBGB"]["message"]}</textarea></div>
                            </div>
                        </fieldset>
                        <fieldset id="tfa_28" class="section" data-condition="`#tfa_12`">
                            <legend id="tfa_28-L">WEBIT</legend>
                            <div id="tfa_20-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_20-L" for="tfa_20" class="label preField " style="width: 70px; min-width:0">Output</label><div class="inputWrapper"><textarea style="width: 600px; height: 250px" id="tfa_20" name="tfa_20" class="">{$template["WEBIT"]["template"]}</textarea></div>
                            </div>
                            <div id="tfa_8-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_8-L" for="tfa_8" class="label preField " style="width: 70px; min-width:0">Variables</label><div class="inputWrapper">{$template["variable_list"]}</div>
                            </div>
                            <div id="tfa_8-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_8-L" for="tfa_8" class="label preField " style="width: 70px; min-width:0">Subject</label><div class="inputWrapper"><input type="text" id="tfa_8" name="tfa_8" value="{$template["WEBIT"]["subject"]}" style="width: 600px" placeholder="" default="" class=""></div>
                            </div>
                            <div id="tfa_21-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_21-L" for="tfa_21" class="label preField " style="width: 70px; min-width:0">Message</label><div class="inputWrapper"><textarea style="width: 600px; height: 250px" id="tfa_1" name="tfa_1" class="">{$template["WEBIT"]["message"]}</textarea></div>
                            </div>
                        </fieldset>
                        <fieldset id="tfa_29" class="section" data-condition="`#tfa_16`">
                            <legend id="tfa_29-L">WEBNZ</legend>
                            <div id="tfa_20-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_20-L" for="tfa_20" class="label preField " style="width: 70px; min-width:0">Output</label><div class="inputWrapper"><textarea style="width: 600px; height: 250px" id="tfa_20" name="tfa_20" class="">{$template["WEBNZ"]["template"]}</textarea></div>
                            </div>
                            <div id="tfa_8-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_8-L" for="tfa_8" class="label preField " style="width: 70px; min-width:0">Variables</label><div class="inputWrapper">{$template["variable_list"]}</div>
                            </div>
                            <div id="tfa_8-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_8-L" for="tfa_8" class="label preField " style="width: 70px; min-width:0">Subject</label><div class="inputWrapper"><input type="text" id="tfa_8" name="tfa_8" value="{$template["WEBNZ"]["subject"]}" style="width: 600px" placeholder="" default="" class=""></div>
                            </div>
                            <div id="tfa_21-D" class="oneField   labelsRightAligned ">
                                <label id="tfa_21-L" for="tfa_21" class="label preField " style="width: 70px; min-width:0">Message</label><div class="inputWrapper"><textarea style="width: 600px; height: 250px" id="tfa_1" name="tfa_1" class="">{$template["WEBNZ"]["message"]}</textarea></div>
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