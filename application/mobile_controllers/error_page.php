<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Error_page extends MOBILE_Controller
{
	protected $data;

	public function Error_page()
	{
		parent::MOBILE_Controller(array('template'=>'default'));
		$this->data = array();
	}

	public function error_404()
	{
		$this->output->set_status_header('404');
		$this->load_tpl('content', 'errors/error_404', $data, TRUE);
	}
}
?>