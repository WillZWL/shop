<?php
class Delivery extends MY_Controller
{
    private $appId = "MST0013";
    private $lang_id = "en";

    private $default_delivery;

    public function __construct()
    {
        parent::__construct();
        $this->default_delivery = $this->container['contextConfigService']->valueOf("default_delivery_type");
    }

    public function index()
    {
        $sub_app_id = $this->getAppId() . "00";

        if ($this->input->post('posted')) {
            $vo["func_opt"] = $this->container['deliveryModel']->deliveryService->funcOptionService->get();
            $data["func_opt_list"] = unserialize($_SESSION["func_opt_list"]);
            $this->container['deliveryModel']->checkSerialize('func_opt_list', $data);

            $vo["del_opt"] = $this->container['deliveryModel']->deliveryService->deliveryOptionService->get();
            $data["del_opt_list"] = unserialize($_SESSION["del_opt_list"]);
            $this->container['deliveryModel']->checkSerialize('del_opt_list', $data);

            if ($this->container['deliveryModel']->updateContent($vo, $data)) {
                unset($_SESSION["func_opt_list"]);
                unset($_SESSION["del_opt_list"]);
                redirect($this->getRu());
            }
        }

        $this->container['deliveryModel']->checkSerialize('func_opt_list', $data);
        $this->container['deliveryModel']->checkSerialize('del_opt_list', $data);


        $data["lang_list"] = $this->container['deliveryModel']->languageService->getList(["status" => 1], ["limit" => -1]);
        $data["delivery_type_list"] = $this->container['deliveryModel']->deliveryService->getDeliveryTypeList();
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;
        $data["notice"] = notice($lang);
        $data["set_form_ru"] = $this->setFormRu();
        $this->load->view('mastercfg/delivery/delivery_index_v', $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function region()
    {
        $sub_app_id = $this->getAppId() . "01";

        if ($this->input->post('posted')) {
            $vo = $this->container['deliveryModel']->deliveryService->get();
            $data["delivery_list"] = unserialize($_SESSION["delivery_list"]);
            $this->container['deliveryModel']->checkSerialize('delivery_list', $data);
            if ($this->container['deliveryModel']->updateDelivery($vo, $data)) {
                unset($_SESSION["delivery_list"]);
                redirect($this->getRu());
            }
        }


        $this->container['deliveryModel']->checkSerialize('delivery_list', $data);

        $data["delivery_type_list"] = $this->container['deliveryModel']->deliveryService->getDeliveryTypeList();
        $data["country_list"] = $this->container['deliveryModel']->countryService->getCountryNameListWithKey([], ["limit" => -1]);
        $data["default_delivery"] = $this->default_delivery;
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;
        $data["notice"] = notice($lang);
        $data["set_form_ru"] = $this->setFormRu();
        $this->load->view('mastercfg/delivery/delivery_region_v', $data);
    }
}



