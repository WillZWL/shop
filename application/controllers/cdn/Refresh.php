<?php
class Refresh extends MY_Controller
{
	private $app_id = "MKT0081";
	private $lang_id = "en";
	private $cdn_url_prefix = "http://cdn.valuebasket.com/808AA1/vb/";
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('url'));
		$this->load->helper(array('notice'));
		$this->authorization_service->check_access_rights($this->_get_app_id(), "");
	}

	public function index()
	{
		include_once APPPATH.'language/'.$this->_get_app_id().'00_'.$this->_get_lang_id().'.php';
		$data["lang"] = $lang;
		$data["title"] = "Refresh CDN Image";
		$data["header"] = "Please Input the image path you want to Refresh";

		if($this->input->post('posted'))
		{
			$path = $this->input->post("path");
			if($path != '')
			{
				if(cdn_purge($path))
				{
					$_SESSION["NOTICE"] = 'Success';
				}
				else
				{
					$_SESSION["NOTICE"] = 'Fail';
					mail('will.zhang@eservicesgroup.net', "[VBCDN] $path refresh", "$path refresh fail\r\n");
				}
			}
			else
			{
				$_SESSION["NOTICE"] = "Please enter the url you want yo refresh";
			}


		}
		$data["notice"] = notice($lang);
		$this->load->view('cdn/refresh_index_v',$data);
	}

	public function _get_cdn_url_prefix()
	{
		return $this->cdn_url_prefix;
	}

	public function _get_app_id()
	{
		return $this->app_id;
	}

	public function _get_lang_id()
	{
		return $this->lang_id;
	}
}

?>