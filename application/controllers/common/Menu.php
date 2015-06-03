<?php
Class Menu extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->load->view("menu.php");
	}
}
/* End of file menu.php */
/* Location: ./app/controllers/common/menu.php */