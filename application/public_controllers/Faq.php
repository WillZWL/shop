<?php
defined('BASEPATH') OR exit('No direct script access allowed');

DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);

class Faq extends PUB_Controller
{
	public function Faq()
	{
		parent::PUB_Controller();
		$this->load->library('template');
		$this->load->library('service/kayako_service');
		$this->load->model('cs/faqadmin_model');
		$this->load->helper(array("url", "tbswrapper"));
	}

	public function index()
	{
//		$data = $this->faqadmin_model->get_content(PLATFORMID);
		$this->load_tpl('content', 'tbs_ws_faq', "", TRUE);
	}

	public function access_kayako_knowledgebase()
	{
		echo $this->kayako_service->access_help_desk_website('/Knowledgebase/List');
	}
}
?>