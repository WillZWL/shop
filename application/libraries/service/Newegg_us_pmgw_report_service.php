<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Pmgw_report_service.php";

class newegg_us_pmgw_report_service extends Pmgw_report_service
{

	public function newegg_us_pmgw_report_service()
	{
		parent::__construct();
	}

	protected function insert_interface($batch_id, $dto_obj)
	{
		if ($this->is_ria_record($dto_obj)) {
			$this->insert_interface_flex_ria($batch_id, 'RIA', $dto_obj);
		} elseif ($this->is_gateway_fee_record($dto_obj)) {
			if ($dto_obj->get_transaction_type() == "Merchandising Fee")
				$status = "MF";
			else
				$status = "SF";
			$this->insert_interface_flex_gateway_fee($batch_id, $status, $dto_obj);
		}
	}

	public function get_contact_email()
	{
		return 'brave.liu@eservicesgroup.com';
	}

	protected function get_pmgw()
	{
		return "newegg_us";
	}

	public function is_ria_record($dto_obj)
	{
		if ($dto_obj->get_transaction_type() == "Order") {
			return true;
		}

		return false;
	}

	public function is_gateway_fee_record($dto_obj)
	{
		if ($dto_obj->get_transaction_type()) {
			return true;
		}

		return false;
	}

	protected function insert_interface_flex_ria($batch_id, $status, $dto_obj)
	{
		$this->_set_format_data($dto_obj);

		if ($this->is_ria_include_so_fee())
			$include_so_fee = true;
		else
			$include_so_fee = false;

		$this->create_interface_flex_ria($batch_id, $status, $dto_obj, $include_so_fee);
	}

	protected function insert_interface_flex_so_fee($batch_id, $status, $dto_obj)
	{
		$this->create_interface_flex_so_fee($batch_id, $status, $dto_obj);
	}

	protected function insert_interface_flex_refund($batch_id, $status, $dto_obj)
	{

	}

	protected function insert_interface_flex_rolling_reserve($batch_id, $status, $dto_obj)
	{

	}

	protected function insert_interface_flex_gateway_fee($batch_id, $status, $dto_obj)
	{
		$this->create_interface_flex_gateway_fee($batch_id, $status, $dto_obj);
	}

	protected function after_insert_all_interface($batch_id)
	{
		return true;
	}

	public function insert_so_fee_from_refund_record($batch_id, $status, $dto_obj)
	{
	}

	public function insert_so_fee_from_ria_record($batch_id, $status, $dto_obj)
	{
	}

	public function insert_so_fee_from_rolling_reserve_record($batch_id, $status, $dto_obj)
	{
	}

	protected function create_interface_flex_ria($batch_id, $status, $dto_obj, $include_so_fee)
	{
		$ifr_dao = $this->get_ifr_dao();
		$ifr_obj = $ifr_dao->get();

		$ifr_obj->set_so_no($dto_obj->get_so_no());
		$ifr_obj->set_flex_batch_id($batch_id);
		$ifr_obj->set_gateway_id($this->get_pmgw());
		$ifr_obj->set_txn_id($dto_obj->get_txn_id());
		$ifr_obj->set_txn_time($dto_obj->get_date());
		$ifr_obj->set_currency_id($dto_obj->get_currency_id());
		$ifr_obj->set_amount($dto_obj->get_amount());
		$ifr_obj->set_status($status);
		$ifr_obj->set_batch_status("N");

		if (!$ifr_obj->get_so_no()) {
			$ifr_obj->set_so_no(" ");
			$ifr_obj->set_batch_status("F");
			$ifr_obj->set_failed_reason(Pmgw_report_service::WRONG_TRANSACTION_ID);
		}

		if ($ifr_dao->insert($ifr_obj) && $ifr_obj->get_batch_status() != "F") {
			if($include_so_fee)
				$this->insert_interface_flex_so_fee($batch_id, $status, $dto_obj);
		}

		return $ifr_obj;
	}

	protected function create_interface_flex_so_fee($batch_id, $status, $dto_obj)
	{
		$ifsf_dao = $this->get_ifsf_dao();
		$ifsf_obj = $ifsf_dao->get();
		$ifsf_obj->set_so_no($dto_obj->get_so_no());
		$ifsf_obj->set_flex_batch_id($batch_id);
		$ifsf_obj->set_gateway_id($this->get_pmgw());
		$ifsf_obj->set_txn_id($dto_obj->get_txn_id());
		$ifsf_obj->set_txn_time($dto_obj->get_date());
		$ifsf_obj->set_currency_id($dto_obj->get_currency_id());
		//by nero, remove the abs
		$ifsf_obj->set_amount(ereg_replace(",", "", $dto_obj->get_commission()));

		$ifsf_obj->set_status($status);
		$ifsf_obj->set_batch_status("N");

		if(!$ifsf_obj->get_so_no())
		{
			$ifsf_obj->set_so_no(" ");
			$ifsf_obj->set_batch_status("F");
			$ifsf_obj->set_failed_reason(Pmgw_report_service::WRONG_TRANSACTION_ID);
		}

		$ifsf_dao->insert($ifsf_obj);

		return $ifsf_obj;
	}

	protected function create_interface_flex_gateway_fee($batch_id, $status, $dto_obj)
	{
		if (!$dto_obj->get_txn_id())
			$dto_obj->set_txn_id($dto_obj->get_date()." ".$dto_obj->get_item_sku()." ".$dto_obj->get_amount());

		$date = date("Y-m-d", strtotime(str_replace('/', '-', $dto_obj->get_date())))." ".date("H:i:s");
		$dto_obj->set_date($date);

		$ifgf_dao = $this->get_ifgf_dao();
		$ifgf_obj = $ifgf_dao->get();

		$ifgf_obj->set_flex_batch_id($batch_id);
		$ifgf_obj->set_gateway_id($this->get_pmgw());
		$ifgf_obj->set_txn_id($dto_obj->get_txn_id());
		$ifgf_obj->set_txn_time($dto_obj->get_date());
		$ifgf_obj->set_currency_id($dto_obj->get_currency_id());
		$ifgf_obj->set_amount(ereg_replace(",", "", $dto_obj->get_amount()));
		$ifgf_obj->set_status($status);
		$ifgf_obj->set_batch_status("N");
		$ifgf_dao->insert($ifgf_obj);

		return $ifgf_obj;
	}

	private function _set_format_data($dto_obj)
	{
		$date = date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $dto_obj->get_date())));
		$dto_obj->set_date($date);

		if ($dto_obj->get_amount()) {
			$dto_obj->set_amount(abs(ereg_replace(",", "", $dto_obj->get_amount())));
		}

		if ($dto_obj->get_commission()) {
			$dto_obj->set_commission(ereg_replace(",", "", $dto_obj->get_commission()));
		}

		if(!$dto_obj->get_so_no() && $dto_obj->get_txn_id())
		{
			if($so_obj = $this->get_so_obj(array("txn_id"=>$dto_obj->get_txn_id())))
			{
				if (!$dto_obj->get_so_no()) {
					$dto_obj->set_so_no($so_obj->get_so_no());
				}
			}
		}
	}

	public function is_refund_record($dto_obj)
	{
		return false;
	}

	public function is_ria_include_so_fee()
	{
		return true;
	}

	public function is_refund_include_so_fee()
	{
		return false;
	}

	public function is_so_fee_record($dto_obj)
	{
		return false;
	}

	public function is_rolling_reserve_record($dto_obj)
	{
		return false;
	}

}

/* End of file newegg_us_pmgw_report_service.php */
/* Location: ./system/application/libraries/service/Newegg_us_pmgw_report_service.php */