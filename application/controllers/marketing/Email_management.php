<?php

class Email_management extends MY_Controller
{

    private $app_id = "MKT0077";
    private $lang_id = "en";

    // private $

    public function __construct()
    {
        parent::__construct();
        $this->load->model('marketing/product_model');
        $this->load->helper(array('url', 'notice', 'object', 'image'));
        $this->load->library('dao/template_dao');
        $this->load->library('dao/template_by_platform_dao');

        $this->load->library('service/product_service');
        $this->load->library('service/pagination_service');
        $this->load->library('service/context_config_service');
        $this->load->library('service/subject_domain_service');
        $this->load->library('service/translate_service');
        $this->load->library('service/platform_biz_var_service');
        $this->load->library('service/product_update_followup_service');
        $this->load->library('service/adwords_service');
        $this->load->library('service/country_service');
    }

    public function index()
    {
        $data["filter_arr"] = $data["tpl_edit"] = $subject_var_arr = $html_var_arr = $alt_var_arr = array();
        $data["subject_var"] = $data["html_var"] = $data["alt_var"] = "";

        // $listpage = $_SESSION["LISTPAGE"];

        $sub_app_id = $this->_get_app_id() . "00";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;

        # get all the templates available for user edit. only those with html stored in database will be shown.
        $data["tpl_list"] = $this->get_template_list();

        if ($_POST) {
            if ($save_template = $this->save_template()) {
                $_SESSION["NOTICE"] = "Update success!";
            }
        }

        if ($_GET) {
            if ($tpl_id = $_GET["tpl"]) {
                # this will grab the table that this template id is from (template or template_by_platform)
                if ($tpl_type = $this->get_template_type($tpl_id)) {
                    $data["tpl_table"] = $tpl_type["table"];

                    $filter_type = $data["filter_type"] = $tpl_type["filter"];    # gives either "lang_id" or "platform_id"

                    $found_default = FALSE;
                    if ($selected_filter = $_GET["filter"])
                        $found_default = TRUE;

                    if ($tpl_list = $this->get_template_list($tpl_id, $tpl_type["table"])) {
                        # if no lang_id/platform+id filter selected, checks if template contains en or WEBGB and make default
                        foreach ($tpl_list as $key => $obj) {
                            if (($found_default === FALSE && ($obj->$filter_type == "en" || $obj->$filter_type == "WEBGB"))) {
                                $selected_filter = $obj->$filter_type;
                                $found_default = TRUE;
                            }
                        }

                        foreach ($tpl_list as $key => $obj) {
                            # if no filter selected and template doesn't have en or WEBGB, then make first array default
                            if ($found_default === FALSE && $key == 0) {
                                $selected_filter = $obj->$filter_type;
                                $found_default = TRUE;
                            }

                            # show available lang/country filters for the template
                            $filter = $obj->$filter_type;
                            $data["filter_arr"][] = $filter;    # for frontend dropdown list

                            # get contents of the selected template
                            if ($filter == $selected_filter) {
                                $data["tpl_edit"]["selected_filter"] = $filter;
                                $data["tpl_edit"]["id"] = $obj->id;
                                $data["tpl_edit"]["subject"] = $obj->subject;
                                $data["tpl_edit"]["message_html"] = $obj->message_html;
                                $data["tpl_edit"]["message_alt"] = $obj->message_alt;
                            }
                        }
                    }

                    # Search and construct the list of variables
                    $subject_var_arr = $this->get_variables_in_template($data["tpl_edit"]["subject"], "[:", ":]");
                    $html_var_arr = $this->get_variables_in_template($data["tpl_edit"]["message_html"], "[:", ":]");
                    $alt_var_arr = $this->get_variables_in_template($data["tpl_edit"]["message_alt"], "[:", ":]");

                    # Construct variable list and edit boxes for front end
                    $data["textarea"]["subject"] = $this->construct_textarea("subject", $data["tpl_edit"]["subject"], $subject_var_arr, FALSE);
                    $data["textarea"]["message_html"] = $this->construct_textarea("message_html", $data["tpl_edit"]["message_html"], $html_var_arr, TRUE);
                    $data["textarea"]["message_alt"] = $this->construct_textarea("message_alt", $data["tpl_edit"]["message_alt"], $alt_var_arr, FALSE);
                }
            }
        }

        $_SESSION["LISTPAGE"] = ($tpl_id == "" ? base_url() . "marketing/email_management/?" : current_url()) . "?" . $_SERVER['QUERY_STRING'];
        $data["notice"] = notice($lang);

        $this->load->view('marketing/email_management/email_management_index_v', $data);
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    private function get_template_list($template_id = "", $template_table = "")
    {
        if (empty($template_id) && empty($template_table)) {
            # get unique template IDs from both tables
            $result = array();

            $this->db->select('id');
            $this->db->distinct();
            $this->db->from("template");
            $this->db->where(array("message_html !=''" => NULL, "status" => 1));
            $this->db->order_by("id", "ASC");

            if ($query = $this->db->get()) {
                if ($query->num_rows() > 0) {
                    $obj = $query->result("object");
                    foreach ($query->result() as $row) {
                        $result[] = $row->id;
                    }
                }
            }

            $this->db->select('id');
            $this->db->distinct();
            $this->db->from("template_by_platform");
            $this->db->where(array("message_html !=''" => NULL, "status" => 1));
            $this->db->order_by("modify_on", "DESC");

            if ($query = $this->db->get()) {
                if ($query->num_rows() > 0) {
                    $obj = $query->result("object");
                    foreach ($query->result() as $row) {
                        $result[] = $row->id;
                    }
                }
            }

            return $result;
        } elseif ($template_id && $template_table) {
            # get list of templates from respective tables
            $this->db->select('*');
            $this->db->from($template_table);
            $this->db->where(array("id" => $template_id, "message_html !=''" => NULL, "status" => 1));
            $this->db->order_by("modify_on", "DESC");

            if ($query = $this->db->get()) {
                if ($query->num_rows() > 0) {
                    foreach ($query->result() as $row) {
                        $result[] = $row;
                    }
                }
            }

            return $result;
        }
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
            if ($template_obj = $template_table_dao->get(array("id" => $tpl_id, $filter_type => $selected_filter))) {
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
                    $_SESSION["NOTICE"] = "Line " . __LINE__ . ". ERROR - Cannot update template. \n DB error_msg: " . $this->db->_error_message();
                } else {
                    return TRUE;
                }

            } else {
                $_SESSION["NOTICE"] = "Line " . __LINE__ . ". ERROR - Cannot get template object. \n DB error_msg: " . $this->db->_error_message();
            }
        }

        return FALSE;
    }

    private function get_template_type($template_id)
    {
        /* ======================================================================
            This function will check for an  active template_id in both template
            and template_by_platform tables. If found active in both, will throw error.
            Else, will return table name and filter (lang_id or platform_id).
        ====================================================================== */
        $result = $ret = array();

        $this->db->select('id');
        $this->db->distinct();
        $this->db->from("template");
        $this->db->where(array("status" => 1, "id" => $template_id));
        if ($query = $this->db->get()) {
            if ($query->num_rows() > 0) {
                $obj = $query->result("object");
                foreach ($query->result() as $row) {
                    $result[] = "template";
                    $ret["filter"] = "lang_id";
                }
            }
        }

        $this->db->select('id');
        $this->db->distinct();
        $this->db->from("template_by_platform");
        $this->db->where(array("status" => 1, "id" => $template_id));

        if ($query = $this->db->get()) {
            if ($query->num_rows() > 0) {
                $obj = $query->result("object");
                foreach ($query->result() as $row) {
                    $result[] = "template_by_platform";
                    $ret["filter"] = "platform_id";
                }
            }
        }

        # we find active template id in both tables and store in $result. Use this to check if template is active in both tables.
        if (count($result) == 0) {
            $_SESSION["NOTICE"] = "ERROR: Could not find Template id <$template_id> in database.";
        } elseif (count($result) > 1) {
            $_SESSION["NOTICE"] = "Line " . __LINE__ . " ERROR: Template id <$template_id> is active in both per language AND per platform.";
        } else {
            $ret["table"] = $result[0];
        }

        return $ret;
    }

    private function get_variables_in_template($template_string = "", $start_delimiter = "[:", $end_delimiter = ":]")
    {
        /* ======================================================================
            This function gets all the variables in a template string, usually
            encapsulated by [::]
            e.g. [:client_id:], [:so_no:]
        ====================================================================== */

        $var_with_count_arr = $var_arr = $search_var_start = $search_var_end = array();
        $count_of_var = array();

        if ($template_string && $start_delimiter && $end_delimiter) {
            if ($search_var_start = explode($start_delimiter, $template_string)) {
                unset($search_var_start[0]);    # the array before the first "[:" is unwanted
                foreach ($search_var_start as $key => $value) {
                    # any array without ":]" in should not be a variable
                    if (strpos($value, "$end_delimiter")) {
                        $search_var_end = explode("$end_delimiter", trim($value));
                        $var_arr[] = trim($search_var_end[0]); # anything after ":]" is unwanted
                    }
                }

                # count number of occurances for each variable
                if ($count_of_var = array_count_values($var_arr)) {
                    foreach ($count_of_var as $key => $value) {
                        $var_with_count_arr[] = "$key::$value";
                    }
                }
            }
        }

        return $var_with_count_arr;
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

    public function get_description()
    {
        /* ===================================================================
            This function is called by ajax. Will get the name and description
            of template id.
            =================================================================== */

        $description_text = "<b>Template Description</b><br>";
        if ($_GET) {
            if ($template_id = $_GET["tpl"]) {
                # get from template_by_platform
                $this->db->from("template_by_platform");
                $this->db->select("id, name, description");
                $this->db->where(array("status" => 1, "id" => $template_id));
                $this->db->order_by("modify_on", "desc");
                $this->db->limit(1);

                if ($query = $this->db->get()) {
                    if ($query->num_rows() > 0) {
                        $obj = $query->result("object");
                        foreach ($query->result() as $row) {
                            $description_text .= "<u>{$row->name}</u><br>{$row->description}";
                        }

                        echo $description_text;
                        exit(); #already got info, exit.
                    }
                }

                # if cannot find from template_by_platform, get from template
                $this->db->from("template");
                $this->db->select("id, name, description");
                $this->db->where(array("status" => 1, "id" => $template_id));
                $this->db->order_by("modify_on", "desc");
                $this->db->limit(1);

                if ($query = $this->db->get()) {
                    if ($query->num_rows() > 0) {
                        $obj = $query->result("object");
                        foreach ($query->result() as $row) {
                            $description_text .= "<u>{$row->name}</u><br>{$row->description}";
                        }

                        echo $description_text;
                        exit(); #already got info, exit.
                    }
                }

                echo "Error getting description from template id ($template_id).";
                exit();
            }
        }

        echo "No template id supplied.";
        exit();
    }

}

/* End of file email_management.php */
/* Location: ./system/application/controllers/email_management.php */