<?php

class Mpuploader extends MY_Controller
{

	private $lang_id = 'en';
	private $app_id = 'ORD0006';

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('url', 'notice'));
		$this->load->model("order/webuploader_model");
	}

	public function index()
	{
		if($this->input->post("posted"))
		{
			$ret = $this->webuploader_model->check_input($_FILES["upload_file"], "metapack");

			if($ret["status"])
			{
				$ret2 = $this->webuploader_model->process_input($_FILES["upload_file"], "metapack");
				if($ret2 === FALSE)
				{
					$_SESSION["NOTICE"] = "error_while_processing_file";
				}
			}
			else
			{
				$_SESSION["NOTICE"] = $ret["reason"];
			}
			Redirect(base_url()."order/mpuploader");
		}

		$sub_id = $this->_get_app_id()."00_".$this->_get_lang_id();
		include_once APPPATH."language/".$sub_id.".php";

		$data["lang"] = $lang;
		$data["notice"] = notice($lang);
		$this->load->view('order/mpuploader/index',$data);
	}

	public function _get_lang_id()
	{
		return $this->lang_id;
	}

	public function _get_app_id()
	{
		return $this->app_id;
	}

}


?>