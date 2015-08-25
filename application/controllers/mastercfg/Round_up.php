<?php

class Round_up extends MY_Controller
{

    private $appId = "MST0014";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('mastercfg/currency_model');
        $this->load->helper(array('url', 'notice', 'object'));
    }

    public function index()
    {
        $sub_app_id = $this->getAppId() . "00";

        $data["currency_list"] = $this->currency_service->get_list_w_key(array(), array("limit" => -1));

        if ($this->input->post('posted')) {
            if ($this->currency_model->update_round_up($data)) {
                redirect($this->_get_ru());
            }
        }

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;
        $data["notice"] = notice($lang);
        $this->load->view('mastercfg/round_up/round_up_index_v', $data);
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



