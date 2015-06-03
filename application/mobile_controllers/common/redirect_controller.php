<?php
defined('BASEPATH') OR exit('No direct script access allowed');

DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);


class Redirect_controller extends MOBILE_Controller
{
	public function Redirect_controller()
	{
		parent::MOBILE_Controller(array("template" => "default"));
//var_dump($this->template);
		$this->load->helper(array('url'));
		$this->load->model('website/home_model');
		$this->load->model('website/common_data_prepare_model');
//		$this->load->model('template/template_model');
		$this->load->library('service/affiliate_service');
//		$this->load->library('service/price_website_service');
	}

	public function index()
	{
		$data = $this->common_data_prepare_model->get_data_array($this);
		$data['banner_name'] = 'Mbanner_' . PLATFORMCOUNTRYID . '.jpg';

		switch (PLATFORMCOUNTRYID) {
			case 'AU' : $data['banner_url_para'] = '?q=wewood'; break;
			case 'BE' : $data['banner_url_para'] = '?q=noel2013'; break;
			case 'ES' : $data['banner_url_para'] = '?q=navidad'; break;
			case 'FR' : $data['banner_url_para'] = '?q=noel2013'; break;
			case 'IT' : $data['banner_url_para'] = '?q=natale2013'; break;
			case 'MY' : $data['banner_url_para'] = '?q=wewood'; break;
			case 'NZ' : $data['banner_url_para'] = '?q=wewood'; break;
			case 'SG' : $data['banner_url_para'] = '?q=wewood'; break;

			default : $data['banner_url_para'] = '';
		}

		$this->load_tpl('content', 'home', $data, TRUE);
	}
}

/* End of file redirect_controller.php */
/* Location: ./app/public_controllers/common/redirect_controller.php */
