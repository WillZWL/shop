<?php
class Cron_update_rrp_factor extends MY_Controller
{
	private $app_id="CRN0015";

	function __construct()
	{
		parent::__construct();
		$this->load->model('marketing/pricing_tool_website_model');
	}

	public function update_rrp_factor()
	{
		$this->pricing_tool_website_model->update_rrp_factor();
	}

	public function _get_app_id()
	{
		return $this->app_id;
	}
}

/* End of file Cron_update_rrp_factor.php */
/* Location: ./app/controllers/cron_update_rrp_factor.php */