<?php
class Cron_generate_video_listing extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('marketing/latest_video_model');
		$this->load->model('marketing/top_view_video_model');
		$this->load->model('marketing/best_selling_video_model');
		$this->load->helper('url');
	}

	function index()
	{
		$this->gen_latest_videos();
		$this->gen_top_view_videos();
		$this->gen_best_selling_videos();
	}

	function gen_latest_videos()
	{
		$this->latest_video_model->gen_listing();
	}

	function gen_top_view_videos()
	{
		$this->top_view_video_model->gen_listing();
	}

	function gen_best_selling_videos()
	{
		$this->best_selling_video_model->gen_listing();
	}
}

/* End of file cron_generate_video_listing.php */
/* Location: ./app/controllers/cron_generate_video_listing.php */
