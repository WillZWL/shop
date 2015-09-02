<?php
use AtomV2\Models\Mastercfg\DeliveryModel;
use AtomV2\Service\ContextConfigService;

class Delivery extends MY_Controller
{

    private $appId = "MST0013";
    private $lang_id = "en";

    private $default_delivery;

    public function __construct()
    {
        parent::__construct();
        $this->deliveryModel = new DeliveryModel;
        $this->contextConfigService = new ContextConfigService;
        $this->default_delivery = $this->contextConfigService->valueOf("default_delivery_type");
    }

    public function index()
    {
        $sub_app_id = $this->getAppId() . "00";

        if ($this->input->post('posted')) {
            $vo["func_opt"] = $this->deliveryModel->deliveryService->funcOptionService->get();
            $data["func_opt_list"] = unserialize($_SESSION["func_opt_list"]);
            $this->deliveryModel->checkSerialize('func_opt_list', $data);

            $vo["del_opt"] = $this->deliveryModel->deliveryService->deliveryOptionService->get();
            $data["del_opt_list"] = unserialize($_SESSION["del_opt_list"]);
            $this->deliveryModel->checkSerialize('del_opt_list', $data);

            if ($this->deliveryModel->updateContent($vo, $data)) {
                unset($_SESSION["func_opt_list"]);
                unset($_SESSION["del_opt_list"]);
                redirect($this->getRu());
            }
        }

        $this->deliveryModel->checkSerialize('func_opt_list', $data);
        $this->deliveryModel->checkSerialize('del_opt_list', $data);


        $data["lang_list"] = $this->deliveryModel->languageService->getList(["status" => 1], ["limit" => -1]);
        $data["delivery_type_list"] = $this->deliveryModel->deliveryService->getDeliveryTypeList();
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
            $vo = $this->deliveryModel->deliveryService->get();
            $data["delivery_list"] = unserialize($_SESSION["delivery_list"]);
            $this->deliveryModel->checkSerialize('delivery_list', $data);
            if ($this->deliveryModel->updateDelivery($vo, $data)) {
                unset($_SESSION["delivery_list"]);
                redirect($this->getRu());
            }
        }


        $this->deliveryModel->checkSerialize('delivery_list', $data);

        $data["delivery_type_list"] = $this->deliveryModel->deliveryService->getDeliveryTypeList();
        $data["country_list"] = $this->deliveryModel->countryService->getCountryNameListWithKey([], ["limit" => -1]);
        $data["default_delivery"] = $this->default_delivery;
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;
        $data["notice"] = notice($lang);
        $data["set_form_ru"] = $this->setFormRu();
        $this->load->view('mastercfg/delivery/delivery_region_v', $data);
    }
}



