<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Redirect_controller extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('service/authentication_service');
		$this->load->library('service/authorization_service');
		$this->load->library('service/context_config_service');
		$this->load->library('service/user_service');
	}

	public function index()
	{
		define('SITE_NAME', $this->context_config_service->value_of("site_name"));
		define('SITE_NAME_CAP', strtoupper($this->context_config_service->value_of("site_name")));
		define('LOGO_FILE_PATH', "/images/".$this->context_config_service->value_of("logo_file_name"));
		if ($this->authentication_service->check_authed()) {
			$data = array();
			$menu_name = array("master_cfg_menu" => 1
								, "order_menu" => 2
								, "compliance_menu" => 3
								, "marketing_menu" => 4
								, "integration_menu" => 5
								, "report_menu" => 6
								, "supply_menu" => 7
								, "customer_service_menu" => 8
								, "finance_menu" => 9
								, "competitor_analysis" => 10
								);
			foreach ($menu_name as $key => $value) {
				$data[$key] = "";
				$result = $this->user_service->get_menu_by_group($value);
				foreach ($result as $app_obj) {
					$data[$key] .= "<tr><td class='admin_menu'>" . "<a href='" . base_url() . $app_obj->get_url() . "' onClick=\"Pop('" . base_url() . $app_obj->get_url() . "','" . $app_obj->get_id() . "');\" target='" . $app_obj->get_id() . "' class='admin_menu'>" . $app_obj->get_app_name() . "</a>" . "</td></tr>";
				}
			}

			$this->load->view("menu.php", $data);
		} else {
			$this->load->view("login.php");
		}
	}
}
