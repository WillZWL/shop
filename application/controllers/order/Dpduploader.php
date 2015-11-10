<?php

class Dpduploader extends MY_Controller
{

    private $lang_id = 'en';
    private $appId = 'ORD0005';

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'notice'));
        $this->load->model("order/webuploader_model");
    }

    public function index()
    {
        if ($this->input->post("posted")) {
            $ret = $this->webuploader_model->check_input($_FILES["upload_file"], "dpd");

            if ($ret["status"]) {
                $ret2 = $this->webuploader_model->process_input($_FILES["upload_file"], "dpd");
                if ($ret2 === FALSE) {
                    $_SESSION["NOTICE"] = "error_while_processing_file";
                }
            } else {
                $_SESSION["NOTICE"] = $ret["reason"];
            }
            Redirect(base_url() . "order/dpduploader");
        }

        $sub_id = $this->getAppId() . "00_" . $this->_get_lang_id();
        include_once APPPATH . "language/" . $sub_id . ".php";

        $data["lang"] = $lang;
        $data["notice"] = notice($lang);
        $this->load->view('order/dpduploader/index', $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

}


?>