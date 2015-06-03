<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Payment_gateway_service extends Base_service
{
	private $so_srv;
	private $config;
	private $pmgw_srv;

	public function __construct()
	{
		parent::__construct();
		include_once(APPPATH."libraries/dao/Payment_gateway_dao.php");
		$this->set_dao(new Payment_gateway_dao());
		include_once(APPPATH."libraries/service/So_service.php");
		$this->set_so_srv(new So_service());
		include_once(APPPATH."libraries/service/Context_config_service.php");
		$this->set_config(new Context_config_service());
		include_once(APPPATH."libraries/dao/Platform_pmgw_dao.php");
		$this->set_pp_dao(new Platform_pmgw_dao());
	}

	public function init_pmgw_srv($payment_gateway)
	{
		switch ($payment_gateway)
		{
			case "bibit":
				if ($this->get_config()->value_of("bibit_model") == "redirect")
				{
					include_once(APPPATH."libraries/service/Pmgw_bibit_redirect_service.php");
					$this->set_pmgw_srv(new Pmgw_bibit_redirect_service());
				}
				else
				{
					include_once(APPPATH."libraries/service/Pmgw_bibit_service.php");
					$this->set_pmgw_srv(new Pmgw_bibit_service());
				}
				break;

			default:
				include_once(APPPATH."libraries/service/Pmgw_".$payment_gateway."_service.php");
				$srv_name = "Pmgw_".$payment_gateway."_service";
				$this->set_pmgw_srv(new $srv_name());
		}
	}

	public function checkout($payment_gateway, $vars="", $debug=0)
	{

		$vars["payment_gateway"] = $payment_gateway;

		if ($payment_gateway != "google")
		{
			unset($_SESSION["so_no"]);
		}

		$this->init_pmgw_srv($payment_gateway);
		$pmgw = $this->get_pmgw_srv();

		if ($pmgw->init($vars) !== FALSE)
		{
			if ($pmgw->so->get_amount())
			{
				$pmgw->checkout($debug);
			}
			else
			{

				if (!isset($vars["all_virtual"]))
				{
					$vars["all_virtual"] = $this->get_so_srv()->get_prod_srv()->check_all_virtual($_SESSION["cart"][PLATFORMID]);
				}

				if (!isset($vars["all_trial"]))
				{
					$vars["all_trial"] = $this->get_so_srv()->get_prod_srv()->check_all_trial($_SESSION["cart"][PLATFORMID]);
				}

				if ($vars["all_trial"] && $vars["all_virtual"])
				{
					$this->get_so_srv()->update_complete_order($pmgw->so);
					$pmgw->fire_success_event();
					$pmgw->unset_variable();
					$this->redirect_success($pmgw->so->get_so_no());
				}
				else
				{
					$this->redirect_fail();
				}
			}
		}
		else
		{
			$this->redirect_fail();
		}
	}

	public function response($payment_gateway, $vars="", $debug=0)
	{
		$this->init_pmgw_srv($payment_gateway);
		$pmgw = $this->get_pmgw_srv();
		return $pmgw->response($vars, $debug);
	}

	public function get_so_srv()
	{
		return $this->so_srv;
	}

	public function set_so_srv($value)
	{
		$this->so_srv = $value;
	}

	public function get_bibit_srv()
	{
		return $this->bibit_srv;
	}

	public function set_bibit_srv($value)
	{
		$this->bibit_srv = $value;
	}

	public function get_google_srv()
	{
		return $this->google_srv;
	}

	public function set_google_srv($value)
	{
		$this->google_srv = $value;
	}

	public function get_pp_dao()
	{
		return $this->pp_dao;
	}

	public function set_pp_dao($value)
	{
		$this->pp_dao = $value;
	}

	public function get_config()
	{
		return $this->config;
	}

	public function set_config($value)
	{
		$this->config = $value;
	}

	public function get_pmgw_srv()
	{
		return $this->pmgw_srv;
	}

	public function set_pmgw_srv($value)
	{
		$this->pmgw_srv = $value;
	}

	public function redirect_fail()
	{
		$browser = @get_browser(null, true);
		$url = base_url()."checkout/payment_result/0";
/*		if ($browser["javascript"])
		{
			echo "<script>top.document.location.href='$url';</script>";
		}
		else
		{*/
			redirect($url);
//		}
	}

	public function redirect_success($so_no)
	{
		echo "<script>top.document.location.href='".base_url()."checkout/payment_result/1/{$so_no}';</script>";
	}
}

/* End of file payment_gateway_service.php */
/* Location: ./system/application/libraries/service/Payment_gateway_service.php */