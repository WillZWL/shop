<?php
class Delivery extends MY_Controller
{

	private $app_id="MST0013";
	private $lang_id="en";

	private $default_delivery;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('mastercfg/delivery_model');
		$this->load->helper(array('url', 'notice', 'object'));
		$this->load->library('service/context_config_service');
		$this->default_delivery = $this->context_config_service->value_of("default_delivery_type");
	}

	public function index()
	{
		$sub_app_id = $this->_get_app_id()."00";

		if($this->input->post('posted'))
		{
			$vo["func_opt"] = $this->delivery_service->get_func_opt_srv()->get();
			$data["func_opt_list"] = unserialize($_SESSION["func_opt_list"]);
			$this->delivery_model->check_serialize('func_opt_list', $data);

			$vo["del_opt"] = $this->delivery_service->get_del_opt_srv()->get();
			$data["del_opt_list"] = unserialize($_SESSION["del_opt_list"]);
			$this->delivery_model->check_serialize('del_opt_list', $data);

			if ($this->delivery_model->update_content($vo, $data))
			{
				unset($_SESSION["func_opt_list"]);
				unset($_SESSION["del_opt_list"]);
				redirect($this->_get_ru());
			}
		}

		$this->delivery_model->check_serialize('func_opt_list', $data);
		$this->delivery_model->check_serialize('del_opt_list', $data);


		$data["lang_list"] = $this->language_service->get_list(array("status"=>1), array("limit"=>-1));
		$data["delivery_type_list"] = $this->delivery_service->get_delivery_type_list();
		include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
		$data["lang"] = $lang;
		$data["notice"] = notice($lang);
		$this->load->view('mastercfg/delivery/delivery_index_v',$data);
	}

	public function region()
	{
		$sub_app_id = $this->_get_app_id()."01";

		if($this->input->post('posted'))
		{
			$vo = $this->delivery_service->get();
			$data["delivery_list"] = unserialize($_SESSION["delivery_list"]);
			$this->delivery_model->check_serialize('delivery_list', $data);
			if ($this->delivery_model->update_delivery($vo, $data))
			{
				unset($_SESSION["delivery_list"]);
				redirect($this->_get_ru());
			}
		}


		$this->delivery_model->check_serialize('delivery_list', $data);

		$data["delivery_type_list"] = $this->delivery_service->get_delivery_type_list();
		$data["country_list"] = $this->country_service->get_country_name_list_w_key(array(), array("limit"=>-1));
		$data["default_delivery"] = $this->default_delivery;
		include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
		$data["lang"] = $lang;
		$data["notice"] = notice($lang);
		$this->load->view('mastercfg/delivery/delivery_region_v',$data);
	}

	public function _get_app_id()
	{
		return $this->app_id;
	}

	public function _get_lang_id()
	{
		return $this->lang_id;
	}
}

/* End of file freight.php */
/* Location: ./system/application/controllers/freight.php */
