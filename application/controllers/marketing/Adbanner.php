<?php

class Adbanner extends MY_Controller
{

    private $appId = "MKT0001";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'directory'));
        $this->setting = $this->config->item('adbanner');
        $this->load->model("marketing/adbanner_model");
        $this->option_list = directory_map($this->setting["upload_path"], TRUE);

    }

    public function index()
    {
        $data = array();
        $data["update"] = 0;
        if ($this->input->post('posted')) {
            $errcnt = 0;
            $id_arr = $this->input->post('id');
            foreach ($id_arr as $value) {

                $bimage = "bimage" . $value;
                $blink = "link" . $value;
                $obj = $this->adbanner_model->get_adbanner($value);
                $obj->set_bannerfile($this->input->post($bimage));
                $obj->set_bannerlink($this->input->post($blink));

                $ret = $this->adbanner_model->edit_adbanner($obj);
                if ($ret === FALSE) {
                    $errcnt++;
                }
            }

            if ($errcnt) {
                $_SESSION["notice"] = "Updating records failed.";
            } else {
                $_SESSION["notice"] = "";
                $data["update"] = 1;
            }
        }
        $data['option_list'] = $this->option_list;
        $data['adbanner_list'] = $this->adbanner_model->get_banner_list();
        include_once APPPATH . "language/" . $this->getAppId() . "01_" . $this->_get_lang_id() . ".php";
        $data["lang"] = $lang;
        $this->load->view("marketing/adbanner/adbanner_index", $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function view()
    {

        $this->load->view("marketing/adbanner/adbanner_view");
    }

    public function add()
    {

    }

    public function upload()
    {
        $data = array();
        $data["image"] = "";
        $data["image_location"] = "";
        if (!empty($_POST)) {
            if ($this->input->post('preview')) {
                $this->setting["upload_path"] .= "temp/";
            }
            $this->load->library('upload', $this->setting);
            if ($_SESSION["imagename"] != "") {
                @copy($this->setting["upload_path"] . "temp/" . $_SESSION["imagename"], $this->setting["upload_path"] . $_SESSION["imagename"]);
                @unlink($this->setting["upload_path"] . "temp/" . $_SESSION["imagename"]);
                $_SESSION["imagename"] = "";
            } else {
                $ret = $this->upload->do_upload("imagefile");
                if ($ret === FALSE) {
                    $_SESSION["notice"] = $this->upload->display_errors();
                    $_SESSION["imagename"] = "";
                    //echo $this->upload->display_errors();
                } else {
                    $upload = $this->upload->data();
                    if ($this->input->get_post('preview', TRUE)) {
                        $data["image"] = base_url() . "images/adbanner/temp/" . $upload["file_name"];
                        $_SESSION["imagename"] = $upload["file_name"];
                    } else {
                        @unlink($upload["file_path"] . "temp/" . $upload["file_name"]);
                        $_SESSION["imagename"] = "";
                    }
                }
            }
        }
        $data["max_height"] = $this->setting["max_height"];
        $data["max_width"] = $this->setting["max_width"];
        $data["type"] = "." . str_replace("|", ", .", $this->setting["allowed_types"]);
        include_once APPPATH . "language/" . $this->getAppId() . "04_" . $this->_get_lang_id() . ".php";
        $data["lang"] = $lang;
        $this->load->view("marketing/adbanner/adbanner_upload", $data);
    }

}

?>