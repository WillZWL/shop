<?php
use AtomV2\Models\Website\CommonDataPrepareModel;

class Mainproduct extends \PUB_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->commonDataPrepareModel = new CommonDataPrepareModel;
    }

    public function view($sku = '', $sv = false)
    {
        $data = array();
        $data = $this->commonDataPrepareModel->getCommonData($this, ["sku" => $sku, "type" => "web"]);
		$data['sv'] = $sv;
		$this->load->view('/default/product', $data);
    }
}
