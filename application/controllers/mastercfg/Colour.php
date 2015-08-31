<?php
use AtomV2\Service\LanguageService;
use AtomV2\Models\Mastercfg\ColourModel;

class Colour extends MY_Controller
{
    private $appId = "MST0010";

    public function __construct()
    {
        parent::__construct();
        $this->languageService = new LanguageService;
        $this->colourModel = new ColourModel;
        // $this->load->model('mastercfg/colour_model');
        // $this->load->model('marketing/product_model');
        // $this->load->helper(array('url', 'notice'));
        // $this->load->library('service/pagination_service');
        // $this->load->library('service/colour_extend_service');
        // include_once(APPPATH . 'libraries/service/translate_service.php');
        // $this->set_translate_service(new Translate_service());
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function set_translate_service($serv)
    {
        $this->translate_service = $serv;
    }

    public function add()
    {
        if ($this->input->post('posted')) {
            if ($this->input->post('action') == 'translate') {
                # use bing to translate EN colour name
                $source_name = $data["name"] = ucfirst(strtolower($this->input->post("name")));
                $translated_arr = $this->translate_colour_name($source_name, 'en', $data["lang_list"]);
                $data["colour_id"] = $this->input->post("id");

                if ($translated_arr) {
                    foreach ($translated_arr as $lang_id => $value) {
                        $data["translate"][$lang_id] = $value["text"];
                        $error_msg .= $value["error"];
                    }

                    if ($error_msg) {
                        echo "<script type=\"text/javascript\">alert('$error_msg');</script>";
                    }
                } else {
                    $error_msg = __LINE__ . 'Could not translate. $source_name and $to_lang cannot be empty.';
                }
            }

            if ($this->input->post('action') == "add") {
                $obj = $this->colour_model->get();
                $obj->set_id($this->input->post("id"));
                $obj->set_name($this->input->post("name"));
                $obj->set_status($this->input->post("status"));

                $ret = $this->colour_model->insert($obj);

                $name_translate = $this->input->post("name_translate");
                $error_msg = "";

                foreach ($name_translate as $lang_id => $value) {
                    $colour_ext_obj = $colour_ext_dao->get();
                    $colour_ext_obj->set_colour_id($this->input->post("id"));
                    $colour_ext_obj->set_lang_id($lang_id);
                    $colour_ext_obj->set_name(ucfirst(strtolower($value)));

                    $ret_translate = $colour_ext_dao->insert($colour_ext_obj);

                    if ($ret_translate === FALSE) {
                        $error_msg .= "\r\nTranslated name <$value> cannot be updated for language <$lang_id>.
                                        DB error_msg: {$this->db->_error_message()}";
                    }

                }
            }

            if ($this->input->post('action') == "edit") {
                $obj = $this->colour_model->get($this->input->post('id'));
                $obj->set_name($this->input->post("name"));
                $obj->set_status($this->input->post("status"));
                $ret = $this->colour_model->update($obj);

                $name_translate = $this->input->post("name_translate");
                $error_msg = "";

                foreach ($name_translate as $lang_id => $value) {
                    $colour_ext_obj = $colour_ext_dao->get(array("colour_id" => $this->input->post('id'), "lang_id" => $lang_id));
                    $colour_ext_obj->set_name(ucfirst(strtolower($value)));

                    $ret_translate = $colour_ext_dao->update($colour_ext_obj);
                    if ($ret_translate === FALSE) {
                        $error_msg .= "\r\nTranslated name <$value> cannot be updated for language <$lang_id>.
                                        DB error_msg: {$colour_ext_dao->db->_error_message()}";
                    }
                }
            }

            if ($ret === FALSE || $error_msg !== "") {
                $_SESSION["NOTICE"] = __LINE__ . " : " . $this->db->_error_message() . $error_msg;
            }
        }
    }

    public function search()
    {
        $where = array();
        $option = array();

        $_SESSION["MC_QUERY"] = base_url() . "mastercfg/colour/?" . $_SERVER["QUERY_STRING"];

        if ($this->input->get("id") != "") {
            $where["id LIKE"] = '%' . $this->input->get("id") . '%';
        }

        if ($this->input->get("name") != "") {
            $where["name LIKE"] = '%' . $this->input->get('name') . '%';
        }

        if ($this->input->get("status") != "") {
            $where["status"] = $this->input->get("status");
        }

        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        $limit = '20';

        $pconfig['base_url'] = $_SESSION["LISTPAGE"];
        $option["limit"] = $pconfig['per_page'] = $limit;
        if ($option["limit"]) {
            $option["offset"] = $this->input->get("per_page");
        }

        if (empty($sort))
            $sort = "id";

        if (empty($order))
            $order = "asc";

        $option["orderby"] = $sort . " " . $order;
    }

    public function index()
    {
        $subAppId = $this->getAppId() . '00';
        $data['langList'] = $this->languageService->getList(['status' => 1], ['orderby' => 'lang_id ASC']);

        $data['colourList'] = $this->colourModel->getList(['status' => 1], ['limit' => -1]);

        // $data['colourListWithLang'] = $this->colourModel->getListWithLang(['c.status' => 1], ['limit' => -1]);

        // foreach ($data["list"] as $key => $obj) {
        //     $colour_id = $obj->get_id();
        //     $data["list_translate"][$colour_id] = $colour_ext_dao->get_list(array("colour_id" => $colour_id));
        // }

        //var_dump($this->db->last_query()." ".$this->db->_error_message());
        // $data["total"] = $this->colour_model->get_list($where, array("num_row" => 1));

        include_once(APPPATH . "language/" . $subAppId . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;

        // $pconfig['total_rows'] = $data['total'];
        // $this->pagination_service->set_show_count_tag(TRUE);
        // $this->pagination_service->initialize($pconfig);

        // $data["notice"] = notice($lang);
                $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        // $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
        $data["searchdisplay"] = "";
        $this->load->view('mastercfg/colour/colour_index_v', $data);


    }

    // public function index_back()
    // {
    //     $subAppId = $this->getAppId() . "00";
    //     $_SESSION["LISTPAGE"] = ($prod_grp_cd == "" ? base_url() . "mastercfg/colour/?" : current_url()) . $_SERVER['QUERY_STRING'];
    //     $colour_ext_dao = $this->colour_extend_service->get_dao();
    //     $data["lang_list"] = $this->product_model->get_list("language", array("status" => 1), array("orderby" => "id ASC"));

    //     if ($this->input->post('posted')) {
    //         if ($this->input->post('action') == 'translate') {
    //             # use bing to translate EN colour name
    //             $source_name = $data["name"] = ucfirst(strtolower($this->input->post("name")));
    //             $translated_arr = $this->translate_colour_name($source_name, 'en', $data["lang_list"]);
    //             $data["colour_id"] = $this->input->post("id");

    //             if ($translated_arr) {
    //                 foreach ($translated_arr as $lang_id => $value) {
    //                     $data["translate"][$lang_id] = $value["text"];
    //                     $error_msg .= $value["error"];
    //                 }

    //                 if ($error_msg) {
    //                     echo "<script type=\"text/javascript\">alert('$error_msg');</script>";
    //                 }
    //             } else {
    //                 $error_msg = __LINE__ . 'Could not translate. $source_name and $to_lang cannot be empty.';
    //             }
    //         }

    //         if ($this->input->post('action') == "add") {
    //             $obj = $this->colour_model->get();
    //             $obj->set_id($this->input->post("id"));
    //             $obj->set_name($this->input->post("name"));
    //             $obj->set_status($this->input->post("status"));

    //             $ret = $this->colour_model->insert($obj);

    //           // SBF #3071
    //             $name_translate = $this->input->post("name_translate");
    //             $error_msg = "";

    //             foreach ($name_translate as $lang_id => $value) {
    //                 $colour_ext_obj = $colour_ext_dao->get();
    //                 $colour_ext_obj->set_colour_id($this->input->post("id"));
    //                 $colour_ext_obj->set_lang_id($lang_id);
    //                 $colour_ext_obj->set_name(ucfirst(strtolower($value)));

    //                 $ret_translate = $colour_ext_dao->insert($colour_ext_obj);

    //                 if ($ret_translate === FALSE) {
    //                     $error_msg .= "\r\nTranslated name <$value> cannot be updated for language <$lang_id>.
    //                                     DB error_msg: {$this->db->_error_message()}";
    //                 }

    //             }
    //         }

    //         if ($this->input->post('action') == "edit") {
    //             $obj = $this->colour_model->get($this->input->post('id'));
    //             $obj->set_name($this->input->post("name"));
    //             $obj->set_status($this->input->post("status"));
    //             $ret = $this->colour_model->update($obj);

    //           // SBF #3071
    //             $name_translate = $this->input->post("name_translate");
    //             $error_msg = "";

    //             foreach ($name_translate as $lang_id => $value) {
    //                 $colour_ext_obj = $colour_ext_dao->get(array("colour_id" => $this->input->post('id'), "lang_id" => $lang_id));
    //                 $colour_ext_obj->set_name(ucfirst(strtolower($value)));

    //                 $ret_translate = $colour_ext_dao->update($colour_ext_obj);
    //                 if ($ret_translate === FALSE) {
    //                     $error_msg .= "\r\nTranslated name <$value> cannot be updated for language <$lang_id>.
    //                                     DB error_msg: {$colour_ext_dao->db->_error_message()}";
    //                 }
    //             }
    //         }

    //         if ($ret === FALSE || $error_msg !== "") {
    //             $_SESSION["NOTICE"] = __LINE__ . " : " . $this->db->_error_message() . $error_msg;
    //         }
    //     }

    //     $where = array();
    //     $option = array();

    //     $_SESSION["MC_QUERY"] = base_url() . "mastercfg/colour/?" . $_SERVER["QUERY_STRING"];

    //     if ($this->input->get("id") != "") {
    //         $where["id LIKE"] = '%' . $this->input->get("id") . '%';
    //     }

    //     if ($this->input->get("name") != "") {
    //         $where["name LIKE"] = '%' . $this->input->get('name') . '%';
    //     }

    //     if ($this->input->get("status") != "") {
    //         $where["status"] = $this->input->get("status");
    //     }

    //     $sort = $this->input->get("sort");
    //     $order = $this->input->get("order");

    //     $limit = '20';

    //     $pconfig['base_url'] = $_SESSION["LISTPAGE"];
    //     $option["limit"] = $pconfig['per_page'] = $limit;
    //     if ($option["limit"]) {
    //         $option["offset"] = $this->input->get("per_page");
    //     }

    //     if (empty($sort))
    //         $sort = "id";

    //     if (empty($order))
    //         $order = "asc";

    //     $option["orderby"] = $sort . " " . $order;

    //     $data["list"] = $this->colour_model->get_list($where, $option);

    //     foreach ($data["list"] as $key => $obj) {
    //         $colour_id = $obj->get_id();
    //         $data["list_translate"][$colour_id] = $colour_ext_dao->get_list(array("colour_id" => $colour_id));
    //     }

    //     //var_dump($this->db->last_query()." ".$this->db->_error_message());
    //     $data["total"] = $this->colour_model->get_list($where, array("num_row" => 1));

    //     include_once(APPPATH . "language/" . $subAppId . "_" . $this->_get_lang_id() . ".php");
    //     $data["lang"] = $lang;

    //     $pconfig['total_rows'] = $data['total'];
    //     $this->pagination_service->set_show_count_tag(TRUE);
    //     $this->pagination_service->initialize($pconfig);

    //     $data["notice"] = notice($lang);

    //     $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
    //     $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
    //  // $data["searchdisplay"] = ($where["brand_name"]=="" && $where["regions"]=="")?'style="display:none"':"";
    //     $data["searchdisplay"] = "";
    //     $this->load->view('mastercfg/colour/colour_index_v', $data);
    // }

    public function translate_colour_name($source_name = "", $from_lang = "en", $to_lang = array())
    {
        if ($source_name && $from_lang && $to_lang) {
            foreach ($to_lang as $to_lang_obj) {
                $error_msg = "";
                $lang_id = $to_lang_obj->get_id();
                try {
                    $this->get_translate_service()->translate(nl2br($source_name), $new_lang_text, $from_lang, $lang_id);
                } catch (Exception $ex) {
                    $new_lang_text = "";
                    $error_msg = "Translation error on colour name <$source_name> for language <{$to_lang_obj->get_name()}>";
                }

                $translated[$lang_id]["text"] = $new_lang_text;
                $translated[$lang_id]["error"] = $error_msg;
                // var_dump($lang_id);
            }

            return $translated;
        } else
            return FALSE;
    }

    public function get_translate_service()
    {
        return $this->translate_service;
    }
}
