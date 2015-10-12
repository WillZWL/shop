<?php
class EmailManagement extends MY_Controller
{
    private $appId = "MKT0077";

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        // $data["filter_arr"] = $data["tpl_edit"] = $subject_var_arr = $html_var_arr = $alt_var_arr = array();
        // $data["subject_var"] = $data["html_var"] = $data["alt_var"] = "";

        $sub_app_id = $this->getAppId() . "00";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;

        $where = ['status' => 1];
        $option = ['limit' => -1, 'groupby' => 'template_name'];

        if (isset($_GET['tpl_name'])) {
            $where['template_name'] = $_GET['tpl_name'];
            unset($option['groupby']);
        }

        if (isset($_GET['platform'])) {
            $where['platform_id'] = $_GET['platform'];
        }

        $data["tpl_list"] = $this->sc['EmailTemplate']->getDao('EmailTemplate')->getList($where, $option);

        if (count($data['tpl_list']) === 1) {
            $data['tpl_edit']['subject'] = $data['tpl_list'][0]->getSubject();
            $data['tpl_edit']['message_html'] = $data['tpl_list'][0]->getSubject();
            $data['tpl_edit']['message_alt'] = $data['tpl_list'][0]->getSubject();

            $subject_vars = $this->sc['EmailTemplate']->getVariablesInTemplate($data["tpl_edit"]["subject"], "[:", ":]");
            $html_vars = $this->sc['EmailTemplate']->getVariablesInTemplate($data["tpl_edit"]["message_html"], "[:", ":]");
            $alt_vars = $this->sc['EmailTemplate']->getVariablesInTemplate($data["tpl_edit"]["message_alt"], "[:", ":]");
            $data["textarea"]["subject"] = $this->construct_textarea("subject", $data["tpl_edit"]["subject"], $subject_var_arr, FALSE);
            $data["textarea"]["message_html"] = $this->construct_textarea("message_html", $data["tpl_edit"]["message_html"], $html_var_arr, TRUE);
            $data["textarea"]["message_alt"] = $this->construct_textarea("message_alt", $data["tpl_edit"]["message_alt"], $alt_var_arr, FALSE);
        }

        $data["notice"] = notice($lang);
        $this->load->view('marketing/email_management/email_management_index_v', $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    private function save_template()
    {
        $variable_arr = $content_arr = $not_table_field = array();

        if ($_POST) {
            $tpl_id = $_POST["tpl_id"];
            $table_name = $_POST["tpl_table"];
            $filter_type = $_POST["filter_type"];
            $selected_filter = $_POST["selected_filter"];
            $not_table_field = array("tpl_id", "tpl_table", "filter_type", "selected_filter");

            $template_table_dao = $this->{$table_name . "_dao"};
            if ($template_obj = $template_table_dao->get(array("template_by_platform_id" => $tpl_id, $filter_type => $selected_filter))) {
                foreach ($_POST as $key => $value) {
                    $set = "";

                    if (in_array($value, $not_table_field) === FALSE) {
                        if ($this->db->field_exists($key, $table_name)) {
                            $set = "set_$key";
                            $template_obj->$set($value);
                        } else {
                            $_SESSION["NOTICE"] = "Line " . __LINE__ . ". ERROR - Input id $key does not exist in db table <$table_name>";
                        }
                    }
                }

                if ($template_table_dao->update($template_obj) === FALSE) {
                    $_SESSION["NOTICE"] = "Line " . __LINE__ . ". ERROR - Cannot update template. \n DB error_msg: " . $this->db->display_error();
                } else {
                    return TRUE;
                }

            } else {
                $_SESSION["NOTICE"] = "Line " . __LINE__ . ". ERROR - Cannot get template object. \n DB error_msg: " . $this->db->display_error();
            }
        }

        return FALSE;
    }

    private function construct_textarea($type = "", $template_string = "", $variable_arr = array(), $enable_preview = FALSE)
    {
        if (!$type || !$template_string) {
            return FALSE;
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


