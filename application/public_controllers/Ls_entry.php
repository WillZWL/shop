<?php
class Ls_entry extends PUB_Controller
{
	private $lang_id = "en";

	public function Ls_entry()
	{
		DEFINE("SKIPCUR", 1);
		parent::PUB_Controller();
		$this->load->helper('url');
	}

	public function index()
	{
		$_SESSION["origin_website"] = 13;
		//Linkshare implementation
		// Set time user entered in $time_entered variable in GMT, 24 format. (Format: yyyy-mm-dd/hh:mm:ss)
		$time_entered = gmdate('Y-m-d/H:i:s', gmmktime());
		// Set $time_entered into cookie called "LS_timeEntered".  This cookie expires in 2 years.
		setcookie("LS_timeEntered", $time_entered, time() + 60*60*24*365*2, "/");

		if ($this->input->get("siteID"))
		{
			// Get siteID query string and set it in the $siteID variable.
			$ls_siteid = $this->input->get("siteID");
			$_SESSION["LS_siteID"] = $ls_siteid;
			setcookie("LS_siteID", $ls_siteid, time() + 60*60*24*365*2, "/");
		}

		if($this->input->get("url"))
		{
			$url = $this->input->get("url");
			redirect($url);
		}
		else
		{
			redirect(base_url());
		}
	}

	public function get_lang_id()
	{
		return $this->lang_id;
	}
}

/* End of file checkout.php */
/* Location: ./app/public_controllers/checkout.php */