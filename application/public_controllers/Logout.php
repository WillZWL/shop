<?php
class Logout extends PUB_Controller
{
	private $lang_id = "en";

	public function Logout()
	{
		DEFINE("SKIPCUR", 1);
		parent::PUB_Controller();
		$this->load->helper('url');
	}

	public function index()
	{
		unset($_SESSION["client"]);
		unset($_SESSION["NOTICE"]);
		//redirect($this->input->get("back")?urldecode($this->input->get("back")):base_url());
		Redirect(base_url()."login");
	}

	public function get_lang_id()
	{
		return $this->lang_id;
	}
}

/* End of file checkout.php */
/* Location: ./app/public_controllers/checkout.php */