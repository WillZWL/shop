<?php
class Round_up extends MY_Controller
{

    private $appId = "MST0014";
    private $langId = "en";

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $sub_appId = $this->getAppId() . "00";

        $data["currency_list"] = $this->sc['Currency']->getListWithKey([], ["limit" => -1]);

        if ($this->input->post('posted')) {
            if ($this->sc['currencyModel']->updateRoundUp($data)) {
                redirect($this->getRu());
            }
        }

        include_once(APPPATH . "language/" . $sub_appId . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;
        $data["notice"] = notice($lang);
        $data["set_form_ru"] = $this->setFormRu();
        $this->load->view('mastercfg/round_up/round_up_index_v', $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function getLangId()
    {
        return $this->langId;
    }
}



