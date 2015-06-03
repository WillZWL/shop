<?php
class Cron_generate_landpage_listing extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		//$this->load->model('marketing/top_deals_model');
		$this->load->model('marketing/best_seller_model');
		$this->load->model('marketing/latest_arrivals_model');
		//$this->load->model('marketing/pick_of_the_day_model');
		//$this->load->model('marketing/skype_promotion_model');
		$this->load->helper('url');
	}

	function index()
	{
		set_time_limit(900);
		//$this->gen_top_deals();
		$this->gen_latest_arrivals();
		$this->gen_best_seller();
		//$this->gen_pick_of_the_day();
		//$this->gen_skype_promotion();
	}

	function gen_top_deals()
	{
		$this->top_deals_model->gen_listing();
	}

	function gen_best_seller()
	{
		$this->best_seller_model->gen_listing();
	}

	function gen_latest_arrivals()
	{
		$this->latest_arrivals_model->gen_listing();
	}

	function gen_pick_of_the_day()
	{
		$this->pick_of_the_day_model->gen_listing();
	}

	function gen_skype_promotion()
	{
		$this->skype_promotion_model->gen_listing();
	}
}

/* End of file cron_generate_landpage_listing.php */
/* Location: ./app/controllers/cron_generate_landpage_listing.php */
