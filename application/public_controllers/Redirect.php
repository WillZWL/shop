<?php
class Redirect extends PUB_Controller
{
	public function Redirect()
	{
		DEFINE("SKIPCUR", 1);
		parent::PUB_Controller();
		$this->load->helper('url');
	}

	public function index()
	{
		redirect($this->input->get("url"));
	}
}

/* End of file redirect.php */
/* Location: ./app/public_controllers/redirect.php */