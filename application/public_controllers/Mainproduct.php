<?php
use AtomV2\Models\Website\CommonDataPrepareModel;

class Mainproduct extends \PUB_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->common_data_prepare_model = new CommonDataPrepareModel;
    }

    public function view($sku = '', $sv = false)
    {
        $data = array();
        $data['prod_info'] = $this->common_data_prepare_model->getCommonData($this, ["sku" => $sku, "type" => "web"]);
		$data['sv'] = $sv;
		$this->load->view('/default/product', $data);
    }
}
