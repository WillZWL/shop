<?php
class Header extends PUB_Controller
{

	function Header()
	{
		// load controller parent
		parent::PUB_Controller();
		$this->load->helper('url');
	}

	function index()
	{
	}

	function lytebox($publish_key)
	{
		$data['display_id'] = 16;
		include_once(APPPATH . "language/WEB" . str_pad($data['display_id'], 6, '0', STR_PAD_LEFT) . "_" . get_lang_id() . ".php");
		$data["lang"] = $lang;

		$this->load_view('banner/lytebox_'.$publish_key, $data);
	}
}

/* End of file header.php */
/* Location: ./app/controllers/header */