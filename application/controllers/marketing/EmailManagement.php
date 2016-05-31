<?php
class EmailManagement extends MY_Controller
{
    private $appId = "MKT0077";

    public function __construct()
    {
        parent::__construct();
    }

    public function resendOrderConfirmation($soNo = null)
    {
        if ($soNo) {
            if ($soObj = $this->sc["SoFactory"]->getDao("So")->get(["so_no" => $soNo])) {
                $this->sc["PaymentGatewayRedirectCybersource"]->sendConfirmationEmail($soObj);
                print "Order confirmation for order:" . $soNo . " has been sent successfully!";
            } else {
                print "Not a valid order number.";
            }
        } else {
            print "Please input a valid order number.";
        }
    }

    public function index()
    {
        $sub_app_id = $this->getAppId() . "00";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
        $data['lang'] = $lang;

        $where = ['status' => 1];
        $option = ['limit' => -1, 'groupby' => 'tpl_id'];

        $data['tpl_list'] = $this->sc['Email']->getDao('Template')->getList($where, $option);
        $data['platform_list'] = $this->sc['SellingPlatform']->getDao('SellingPlatform')->getList();

        if (isset($_GET['tpl_id'])) {
            $where['tpl_id'] = $_GET['tpl_id'];
            unset($option['groupby']);
        }

        if (isset($_GET['platform'])) {
            $where['platform_id'] = $_GET['platform'];
        }
       
        if ($tpl_obj = $this->sc['Email']->getDao('Template')->get($where)) {
            $data['tpl_edit']['subject'] = $tpl_obj->getSubject();
            $data['tpl_edit']['message_html'] = $tpl_obj->getMessageHtml();
            $data['tpl_edit']['message_alt'] = $tpl_obj->getMessageAlt();

            $subject_vars = $this->sc['Template']->getVariablesInTemplate($data["tpl_edit"]["subject"], "[:", ":]");
            $html_vars = $this->sc['Template']->getVariablesInTemplate($data["tpl_edit"]["message_html"], "[:", ":]");
            $alt_vars = $this->sc['Template']->getVariablesInTemplate($data["tpl_edit"]["message_alt"], "[:", ":]");
            $data["textarea"]["subject"] = $this->construct_textarea("subject", $data["tpl_edit"]["subject"], $subject_vars, false);
            $data["textarea"]["message_html"] = $this->construct_textarea("message_html", $data["tpl_edit"]["message_html"], $html_vars, true);
            $data["textarea"]["message_alt"] = $this->construct_textarea("message_alt", $data["tpl_edit"]["message_alt"], $alt_vars, false);
        }

        $data["notice"] = notice($lang);
        $this->load->view('marketing/email_management/email_management_index_v', $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function previewHtml(){

        if($_POST["message_html"]){

            //add email logo

            //$siteConfigObj=$this->sc["LoadSiteParameter"]->initSite();
            $siteConfigObj=$this->sc["LoadSiteParameter"]->loadSiteByPlatform($_POST["platform"]);
           
            $replace["logo"]="http://".$siteConfigObj->getDomain()."/images/logo/" . $siteConfigObj->getLogo();
            $replace["site_name"]=$siteConfigObj->getSiteName();
            $replace["site_url"]="http://".$siteConfigObj->getDomain();
        
            if (!empty($replace)) {
                foreach ($replace as $key => $value) {
                    $search[] = '[:' . $key . ':]';
                    $replace_value[] = $value;
                }

                $results=str_replace($search, $replace_value, $_POST["message_html"]);
            }
            echo $results;
        }
        
    }

    public function saveTemplate()
    {
        $variable_arr = $content_arr = $not_table_field = array();

        if ($_POST) {
            $where = [
                'tpl_id' => $_POST['tpl_id'],
                'platform_id' => $_POST['platform']
            ];

            $tpl_obj = $this->sc['Email']->getDao('Template')->get($where);
            if ($tpl_obj) {
                $tpl_obj->setSubject($_POST['subject']);
                $tpl_obj->setMessageHtml($_POST['message_html']);
                $tpl_obj->setMessageAlt($_POST['message_alt']);

                $this->sc['Email']->getDao('Template')->update($tpl_obj);
            }
        }

        redirect(site_url().'/marketing/emailManagement?tpl_id='.$_POST['tpl_id']."&platform=".$_POST['platform']);
    }

    private function construct_textarea($type = "", $template_string = "", $variable_arr = array(), $enable_preview = false)
    {
        if (!$type || !$template_string) {
            return false;
        }

        $title = ucfirst($type);
        $var_to_display = $var_with_count = "";

        if ($variable_arr) {
            foreach ($variable_arr as $key => $value) {
                # $value = "variable::count"

                $explode = explode("::", $value);
                $var_to_display .= "[:{$explode[0]}:], ";                   # to display: [:var1:],[:var2:],[:var3:]
                $var_with_count .= "[:{$explode[0]}:]::{$explode[1]},";     # hidden input: [:var1:]::count,[:var2:]::count,[:var3:]::count
            }
        }
        $var_with_count = trim($var_with_count, ",");
        $var_to_display = trim($var_to_display, ", ");


        # if enable_preview, it will construct the preview button to open up template in new window
        if ($enable_preview) {
            $preview_button = <<<HTML
                <div >
                    <br>
                    <button type="button" name="preview" id="preview" onclick="postform('preview', '$type')" style="padding:5px 20px; float:right;clear:both; margin-right:80px;">Preview HTML</button>
                    <br><br>
                </div>
HTML;
        }

        if (strtolower($type) == "subject") {
            $textarea = <<<HTML
                <div>
                    <b>Variables</b>
                    <div style="color:#3D3D3D;padding: 5px 15px;" >$var_to_display<br><br></div>
                    <label id="{$type}_label" style="width:100px; min-width:0px; display:inline; float:left; font-weight:bold;" for="$type">$title</label>
                    <input name="$type" id="$type" style="width:500px;" value="$template_string">
                    <input name="{$type}_variable" id="{$type}_variable" type="hidden" value="$var_with_count">
                </div>
                <br><br>
                <HR width="70%">

HTML;
        } else {
            $textarea = <<<HTML
                <div>
                    <b>Variables</b>
                    <div style="color:#3D3D3D;padding: 5px 15px;" >$var_to_display<br><br></div>
                    <label id="{$type}_label" style="width:100px; min-width:0px; display:inline; float:left; font-weight:bold;" for="$type">$title</label>
                    <textarea name="$type" id="$type" form="save" style="width:500px;height:350px;">$template_string</textarea>
                    $preview_button
                    <input name="{$type}_variable" id="{$type}_variable" type="hidden" value="$var_with_count">
                </div>
                <br><br>
                <HR width="70%">

HTML;

        }

        return $textarea;
    }
}
