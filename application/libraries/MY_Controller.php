<?php
abstract class MY_Controller extends CI_Controller
{

	public function __construct($check_access_rights=TRUE)
	{
		parent::__construct();
		$this->load->library($this->_get_service());
		$this->load->helper("url");
		$_SESSION["CURRPAGE"] = $_SERVER['REQUEST_URI'];
		$currsign = array("GBP"=>"£", "EUR"=>"€");
		$this->add_preload_data(array("currsign"=>$currsign));
		if ($this->config->item('uri_protocol') != "CLI") {
			$this->_check_authed();
			$this->load->library('service/authorization_service');

			if ($check_access_rights) {
				$this->authorization_service->check_access_rights($this->_get_app_id(), "");
				$feature_list = $this->authorization_service->set_application_feature_right($this->_get_app_id(), "");
			}
		}
	}

	abstract public function _get_app_id();

	private function _check_authed()
	{
		if (!$this->authentication_service->check_authed()) {
			$data["fail_msg"] = $this->_get_fail_msg();
			redirect($this->_get_login_page());
		}
	}

	function _get_login_page()
	{
		return "?back=".urlencode($_SESSION["CURRPAGE"]);
	}

	function _get_fail_msg()
	{
		return "Please login to the system first!";
	}

	function _get_service()
	{
		return "service/Authentication_service";
	}

	function _get_ru()
	{
		$ru = $_SESSION["CURRPAGE"];
		if ($pru = $this->input->post("ru")) {
			$this->load->library("encrypt");
			$ru = $this->encrypt->decode($pru);
		}
		return $ru;
	}
}

function _form_ru()
{
	include_once(BASEPATH."libraries/Encrypt.php");
	$encrypt = new CI_Encrypt();
	return "<input type='hidden' name='ru' value='".$encrypt->encode($_SESSION["CURRPAGE"])."'>";
}
