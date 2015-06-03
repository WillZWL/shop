<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Pmgw_report_service.php";

class Moneybookers_pmgw_report_service extends Pmgw_report_service
{
	private $_report_currency_id = "";

	public function __construct()
	{
		parent::__construct();
	}

	public function is_ria_include_so_fee()
	{
		return false;
	}

	public function is_refund_include_so_fee()
	{
		return false;
	}
/**********************************************
**	even status=cancelled, this is still an RIA record
**
***********************************************/
	public function is_ria_record($dto_obj)
	{
		if (($dto_obj->get_type() == "Receive Money")
			&& (substr($dto_obj->get_transaction_detail(), 0, 4) == "from"))
		{
			return true;
		}

		return false;
	}

	public function is_so_fee_record($dto_obj)
	{
		if ((($dto_obj->get_transaction_detail() == "Per Transaction Fee") && ($dto_obj->get_type() == "Receive Money")))
		{
			return "M_PTF";
		}
		elseif(($dto_obj->get_transaction_detail() == "Fee") && ($dto_obj->get_type() == "Receive Money"))
		{
			return "M_F";
		}
		elseif (($dto_obj->get_transaction_detail() == "Merchant Refund Fee") && ($dto_obj->get_type() == "Withdraw"))
		{
			return "M_MRF_W";
		}
		elseif (($dto_obj->get_transaction_detail() == "Merchant Refund Fee") && ($dto_obj->get_type() == "Upload"))
		{
			return "M_MRF_U";
		}
		elseif (($dto_obj->get_transaction_detail() == "Chargeback Fee") && ($dto_obj->get_type() == "Withdraw"))
		{
			return "M_CBF";
		}
		elseif (($dto_obj->get_type() == "Receive Money Cancellation"))
		{
			$transaction_details = $dto_obj->get_transaction_detail();

			if(substr($transaction_details, 0, 28) == "Return 'Per Transaction Fee'")
			{
				return "M_RMC_PTF";
			}
			elseif(substr($transaction_details, 0, 12) == "Return 'Fee'")
			{
				return "M_RMC_F";
			}
			else
			{
				return false;
			}
		}

		return false;
	}

	public function is_refund_record($dto_obj)
	{
		//var_dump($dto_obj);die();
		if (($dto_obj->get_transaction_detail() == "Merchant Refund") && ($dto_obj->get_type() == "Withdraw"))
		{
			return 'R';
		}
		elseif (($dto_obj->get_type() == "Receive Money Cancellation"))
		{
			$transaction_details = $dto_obj->get_transaction_detail();

			if(preg_match('/Cancel \'from .*@.*\'/i', $transaction_details))
			{
				return "R";
			}
			return false;
		}
		elseif (($dto_obj->get_transaction_detail() == "Merchant Refund") && ($dto_obj->get_type() == "Upload"))
		{
			return 'R';
		}
		elseif (($dto_obj->get_transaction_detail() == "Chargeback") && ($dto_obj->get_type() == "Withdraw"))
		{
			return 'CB';
		}

		return false;
	}

	public function is_gateway_fee_record($dto_obj)
	{
		if (($dto_obj->get_transaction_detail() == "Fee") && ($dto_obj->get_type() == "Withdraw"))
		{
			return 'BTU';
		}

		return false;
	}

	public function is_rolling_reserve_record($dto_obj)
	{
		return false;
	}
	public function get_file_data($filename, $delimiter = ",")
	{
//Get the currency of the file
		$currency = "";

		if($fileHandle = fopen($this->get_folder_path() . $filename, 'r'))
		{
			$line = fgets($fileHandle);
			if ($line)
			{
				$headerArray = explode(",", $line);
				$currency = $headerArray[4];
				if (strlen($currency) == 9)
				{
					$this->_report_currency_id = substr($currency, 5, 3);
				}
			}
			fclose($fileHandle);
		}

		if ($this->_report_currency_id != "")
			return parent::get_file_data($filename, $delimiter);
		else
		{
			$subject = "[" . $this->get_system_platform() . "] Flex Tools - " . $this->get_pmgw() . " No correct currency!!";
			$message = $line . "\r\n";
			$message .= "File:" . $this->get_folder_path() . $filename . "\r\n";

			mail($this->get_contact_email(), $subject, $message, "From: website@chatandvision.com\r\n");
			return array();
		}
	}
	public function get_contact_email()
	{
		return 'handy.hon@eservicesgroup.com';
	}

	protected function get_pmgw()
	{
		return "moneybookers";
	}

	private function _set_format_object($dto_obj)
	{
		$date = date("Y-m-d H:i:s", strtotime($dto_obj->get_txn_time()));
		$dto_obj->set_txn_time($date);
		$dto_obj->set_date($date);

		$reference = $dto_obj->get_reference();
		if (!empty($reference)
			&& (strpos($reference, "-") !== FALSE))
		{
			$index = strpos($reference, "-") + 1;
			$dto_obj->set_so_no(substr($reference, $index, (strlen($reference) - $index)));
//			var_dump($reference);
//			var_dump(substr($reference, $index, (strlen($reference) - $index)));
		}

		if ($dto_obj->get_amount())
			$dto_obj->set_amount(ereg_replace(",", "", $dto_obj->get_amount()));


		//change the debit into negative number.
		$debit = 0 - $dto_obj->get_amount_debit();
		$dto_obj->set_amount_debit($debit);

		//var_dump($dto_obj);die();
		if(!trim($dto_obj->get_so_no()))
		{
			//original_order_txn_id from report
			//txn_id  from report
			$txn_id_list = array("original_order_txn_id", "txn_id");
			foreach($txn_id_list as $a)
			{
				$method = "get_".$a;
				if($txn_id = $dto_obj->$method())
				{
					if(($so_obj = $this->get_so_dao()->get(array("txn_id"=>$txn_id))))
					{
						$dto_obj->set_ref_txn_id($txn_id);
						$dto_obj->set_so_no($so_obj->get_so_no());
						break;
					}
				}
			}
		}


		$internal_txn_id = $dto_obj->get_txn_id();
		$dto_obj->set_internal_txn_id($internal_txn_id);

		//ref_txn_id is not allow null
		if(!$dto_obj->get_ref_txn_id())
		{
			$dto_obj->set_ref_txn_id($internal_txn_id);
		}

		if(!$dto_obj->get_currency_id())
		{
			$dto_obj->set_currency_id($this->_report_currency_id);
		}

		return $dto_obj;
	}

	protected function insert_interface_flex_ria($batch_id, $status, $dto_obj)
	{
// insert interface_flex_ria
		$dto_obj = $this->_set_format_object($dto_obj);

		$dto_obj->set_amount($dto_obj->get_amount_credit());


		$this->create_interface_flex_ria($batch_id, $status, $dto_obj, false);
	}

	protected function insert_interface_flex_so_fee($batch_id, $status, $dto_obj)
	{
		$dto_obj = $this->_set_format_object($dto_obj);

		if ($dto_obj->get_original_order_txn_id() != "")
		{
			$dto_obj->set_txn_id($dto_obj->get_original_order_txn_id());
		}
// insert interface_flex_so_fee

		$dto_obj->set_commission($dto_obj->get_amount_debit());

		//by nero, the meaning of commission and debit match perfect,
		//but no always working. in same cases, the creadit should be used as 'commission'

		if(!$dto_obj->get_commission())
		{
			if($amount_credit = $dto_obj->get_amount_credit())
			{
				$dto_obj->set_commission($amount_credit);
			}
			else
			{
				$dto_obj->set_commission(0);
			}
		}

		//var_dump($dto_obj);die();
//force it to get the so_no from so table

	//comment out by nero
		//$dto_obj->set_so_no("");
		$this->create_interface_flex_so_fee($batch_id, $status, $dto_obj);
	}

	protected function insert_interface_flex_refund($batch_id, $status, $dto_obj)
	{
		$dto_obj = $this->_set_format_object($dto_obj);


		if ($dto_obj->get_original_order_txn_id() != "")
		{
			$dto_obj->set_txn_id($dto_obj->get_original_order_txn_id());
			$dto_obj->set_ref_txn_id($dto_obj->get_original_order_txn_id());
		}


		$dto_obj->set_amount($dto_obj->get_amount_debit());
		if(!$dto_obj->get_amount())
		{
			$dto_obj->set_amount($dto_obj->get_amount_credit());
		}
		//var_dump($dto_obj);die();
		$this->create_interface_flex_refund($batch_id, $status, $dto_obj, false);
	}

	protected function insert_interface_flex_rolling_reserve($batch_id, $status, $dto_obj)
	{
		return true;
	}

	protected function insert_interface_flex_gateway_fee($batch_id, $status, $dto_obj)
	{
		$dto_obj = $this->_set_format_object($dto_obj);
		$dto_obj->set_amount($dto_obj->get_amount_debit());
		$dto_obj->set_currency_id($this->_report_currency_id);
		return $this->create_interface_flex_gateway_fee($batch_id, $status, $dto_obj);
	}

	//overwrite parent method
	public function valid_txn_id($interface_obj)
	{
		//by nero
		$i_txn_id = $interface_obj->get_txn_id();
		$i_so_no = $interface_obj->get_so_no();
		if($this->get_so_dao()->get(array("txn_id"=>$i_txn_id)))
		{
			return true;
		}
		elseif($this->get_so_dao()->get(array("so_no"=>$i_so_no)))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function after_insert_all_interface($batch_id)
	{
		//handle the internal transaction id reference case
		//get empty so_no refund record to traverse the flex_ria via txn_id
		$where["flex_batch_id"] = $batch_id;
		$where["so_no"] = " ";
		if($empty_so_no_obj_list = $this->get_ifrf_dao()->get_list($where, array("limit"=>-1)))
		{
			$related_txn_id = array("get_internal_txn_id","get_txn_id");
			foreach($empty_so_no_obj_list as $nut_obj)
			{
				foreach($related_txn_id as $method)
				{
					$txn_id = $nut_obj->$method();
					if($ria_obj = $this->get_ifr_dao()->get(array("txn_id"=>$txn_id)))
					{
						if($so_no = $ria_obj->get_so_no())
						{
							$nut_obj->set_so_no($so_no);
							$nut_obj->set_batch_status("N");
							$nut_obj->set_failed_reason("");
							$this->get_ifrf_dao()->update($nut_obj);
							continue;
						}
					}
				}
			}
		}

		//traverse the flex_refund
		//get empty so_no so_fee record to traverse the flex_refund/ria via txn_id
		unset($where);
		$where["flex_batch_id"] = $batch_id;
		$where["so_no"] = " ";
		if($empty_so_no_obj_list = $this->get_ifsf_dao()->get_list($where, array("limit"=>-1)))
		{
			foreach($empty_so_no_obj_list as $nut_obj)
			{
				$txn_id = $nut_obj->get_txn_id();
				$search_fields = array("txn_id", "internal_txn_id");
				foreach($search_fields as $field)
				{
					if($refund_obj = $this->get_ifrf_dao()->get(array($field=>$txn_id, "gateway_id"=>$this->get_pmgw())))
					{
						if($so_no = $refund_obj->get_so_no())
						{
							$nut_obj->set_so_no($so_no);
							$nut_obj->set_batch_status("N");
							$nut_obj->set_failed_reason("");
							$this->get_ifsf_dao()->update($nut_obj);
							continue;
						}
					}
				}
			}
		}




		//move those NULL so no so_fee record to gateway_fee table
		unset($where);
		$where["flex_batch_id"] = $batch_id;
		$where["so_no"] = " ";
		$where["failed_reason"] = Pmgw_report_service::WRONG_TRANSACTION_ID;

		if($empty_so_no_obj_list = $this->get_ifsf_dao()->get_list($where, array("limit"=>-1)))
		{
			foreach($empty_so_no_obj_list as $nut_obj)
			{
				$txn_id = $nut_obj->get_txn_id();
				//use this txn_id to try to get the so no from so table, if success, then skip
				if($so_obj = $this->get_so_dao()->get(array("txn_id"=>$txn_id, "gateway_id"=>$this->get_pmgw())))
				{
					$nut_obj->set_so_no($so_obj->get_so_no());
					$nut_obj->set_batch_status("N");
					$nut_obj->set_failed_reason("");
					$this->get_ifsf_dao()->update($nut_obj);
					continue;
				}

				//$dd = $this->get_ifrr_dao()->get_list(array("flex_batch_id"=>$batch_id, "internal_txn_id"=>$txn_id), array("limit"=>1));
				//var_dump($this->get_ifrr_dao()->db->last_query());die();

				$ifgf_dao = $this->get_ifgf_dao();
				$ifgf_obj = $ifgf_dao->get();

				$ifgf_obj->set_flex_batch_id($batch_id);
				$ifgf_obj->set_gateway_id($this->get_pmgw());
				$ifgf_obj->set_txn_id($nut_obj->get_txn_id());
				$ifgf_obj->set_txn_time($nut_obj->get_txn_time());
				$ifgf_obj->set_currency_id($nut_obj->get_currency_id());
				$ifgf_obj->set_amount($nut_obj->get_amount());
				$ifgf_obj->set_status($nut_obj->get_status());
				$ifgf_obj->set_batch_status("N");

				if($ifgf_dao = $this->get_ifgf_dao()->insert($ifgf_obj))
				{
					//update the interface_flex_so_fee
					if($ifsf_obj = $this->get_ifsf_dao()->get(array("txn_id"=>$nut_obj->get_txn_id(), "gateway_id"=>$this->get_pmgw(), "status"=>$nut_obj->get_status())))
					{
						$ifsf_obj->set_batch_status("S");
						$ifsf_obj->set_failed_reason("move from interface_so_fee to interface_gateway_fee");
						$this->get_ifsf_dao()->update($ifsf_obj);
					}
				}
			}
		}
		return false;
	}

	public function insert_so_fee_from_ria_record($batch_id, $status, $dto_obj)
	{
		return false;
	}

	public function insert_so_fee_from_rolling_reserve_record($batch_id, $status, $dto_obj)
	{
		return false;
	}

	public function insert_so_fee_from_refund_record($batch_id, $status, $dto_obj)
	{
		return false;
	}
}

/* End of file paypal_hk_pmgw_report_service.php */
/* Location: ./system/application/libraries/service/Paypal_hk_pmgw_report_service.php */