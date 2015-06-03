<?php
class Website_bank_transfer extends MY_Controller
{

	private $app_id="ORD0027";
	private $lang_id="en";
	private $so_bank_transfer_obj;


	public function __construct()
	{
		parent::__construct();
		$this->load->model('order/website_bank_transfer_model');
		$this->load->helper(array('url', 'notice', 'object', 'operator'));
		$this->load->library('service/so_service');
		$this->load->library('service/pmgw');
		$this->load->library('service/website_bank_transfer_service');
		$this->load->library('dao/so_bank_transfer_dao');
		$this->load->library('service/pagination_service');
		$this->load->library('service/event_service');
		$this->load->library('service/delivery_option_service');
		$this->load->library('service/context_config_service');
		$this->load->library('service/platform_biz_var_service');
		$this->load->library('service/flex_service');
		$this->load->library('encrypt');
	}

	public function index($pagetype = "not_full")
	{
		$sub_app_id = $this->_get_app_id()."00";

		$_SESSION["BTLISTPAGE"] = base_url()."order/website_bank_transfer/".($pagetype?"index/".$pagetype:"")."?".$_SERVER['QUERY_STRING'];
		$_SESSION["BT_QSTRING"] = $_SERVER['QUERY_STRING'];

		$where = array();
		$option = array();
		$so_no_arr = array();
		$type = $amt_search = "";
		$sort = $this->input->get("sort");
		$order = $this->input->get("order");

		if ($this->input->get("so_no") != "")
		{
			$where["so.so_no LIKE "] = "%".$this->input->get("so_no")."%";
		}

		if ($this->input->get("platform_order_id") != "")
		{
			$where["so.platform_order_id LIKE "] = "%".$this->input->get("platform_order_id")."%";
		}

		if ($this->input->get("ext_ref_no") != "")
		{
			$where["sbt.ext_ref_no"] = $this->input->get("ext_ref_no");
		}

		if ($this->input->get("amount") != "")
		{
			fetch_operator($where, "amount", $this->input->get("amount"));
		}

		if ($this->input->get("currency_id") != "")
		{
			$where["so.currency_id"] = $this->input->get("currency_id");
		}

		if (($net_diff_status = $this->input->get("net_diff_status")) != "")
		{
			if($net_diff_status == 0)
			{
				# unpaid orders will not have record in so_bank_transfer db table
				$where["sbt.ext_ref_no IS NULL"] = NULL;
				$where["so.status"] = 1;
			}
			else
			{
				$where["sbt.net_diff_status"] = $net_diff_status;
			}
		}
		else
		{
			if($pagetype == "not_full")
			{
				# unpaid, underpaid
				$where["(sbt.net_diff_status IN (2,3) OR sbt.ext_ref_no IS NULL)"] = NULL;
			}
		}

		if(($hold_status = $this->input->get("hold_status")))
		{
			# if user chose "ON-HOLD" on Net Difference Status dropdown
			$where["so.hold_status"] = $hold_status;
		}

		$sohr_reason = "";
		if(($hold_reason = $this->input->get("hold_reason")))
		{
			if($hold_reason == 1)
				$sohr_reason = "unpaid_web_bank_transfer";
			else if($hold_reason == 2)
				$sohr_reason = "unpaid_web_bank_transfer_aft_grace_period";

			if($sohr_reason)
				$where["sohr.reason"] = $sohr_reason;
		}

		if($this->input->get("received_amt") != "")
		{
			$rec_amt = $this->input->get("received_amt");
			$amt_search = 1;
		}

		$limit = '20';

		$pconfig['base_url'] = $_SESSION["BTLISTPAGE"];
		$option["limit"] = $pconfig['per_page'] = $limit;

		if ($option["limit"])
		{
			$option["offset"] = $this->input->get("per_page");
		}

		if (empty($sort))
		{
			if($pagetype == "unknown")
				$sort = "sbt.received_date_localtime";
			else
				$sort = "so.so_no";
		}

		if (empty($order))
		{
			$order = "desc";
		}

		$option["orderby"] = $sort." ".$order;
		$option["limit"] = -1;

		if($pagetype == "all")
		{
			$type = "all_and_hold";
		}

		if($pagetype !== "unknown")
		{
			$data["objlist"] = $this->so_bank_transfer_dao->get_so_bank_transfer_list($where, $option, $type, $rec_amt);
			// echo "<pre>"; var_dump($data["objlist"] );die();
			$data["total"] = $this->so_bank_transfer_dao->get_so_bank_transfer_list($where, array("num_rows"=>1), $type);
			// echo "<pre>"; var_dump($data["total"] );die();
			$data["del_opt_list"] = end($this->delivery_option_service->get_list_w_key(array("lang_id"=>"en")));

			if($amt_search == 1 && $data["objlist"])
			{
				# if user search for single transaction amt, this loop will collate
				# all the other transactions in the so_no that contains the searched amt
				foreach ($data["objlist"] as $bt_obj)
				{
					$so_no_arr[] = $bt_obj->get_so_no();
				}
				if($so_no_arr)
				{
					$so_no_str = implode(',', $so_no_arr);
					$where["so.so_no IN ($so_no_str)"] = NULL;
					$data["objlist"] = $this->so_bank_transfer_dao->get_so_bank_transfer_list($where, $option, $type);
				}
			}
		}
		else
		{
			# the page with all unknown bank transfer records without so_no
			$data["objlist"] = $this->so_bank_transfer_dao->get_unknown_bank_transfer_list($where, $option, $type, $rec_amt);
			$data["total"] = $this->so_bank_transfer_dao->get_unknown_bank_transfer_list($where, array("num_rows"=>1), $type);
			$data["del_opt_list"] = array();
		}

		if($this->input->post('posted'))
		{
			switch ($this->input->post('type'))
			{
				case 'add':
					# Add new bank transfer record for specified SO_NO
					$result = $this->process_payment();
					break;

				case 'update_so':
					# ADD SO_NO for previously unknown transfers
					$result = $this->update_so();
					break;

				case 'add_unknown':
					# Add a bank transfer record without stated SO_NO
					$result = $this->add_unknown();
					break;

				default:
					$result["status"] = FALSE;
					$_SESSION["NOTICE"] = "form's <input name=\"type\"> not recognised.";
					break;
			}

			if($result["status"] === FALSE)
			{
				# if saving fails, keep variables showing on frontend so no need retype
				foreach ($_POST as $key=>$value)
				{
					$data[$key] = $value;
				}

				$_SESSION["NOTICE"] = $result["error_msg"];
			}
			else
			{
				$_SESSION["NOTICE"] = "Save success! \n".$this->status_summary;
			}
			redirect($_SESSION["BTLISTPAGE"]);
		}

		include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
		$data["lang"] = $lang;

		if($data['total'] !== 0)
		{
			$pconfig['total_rows'] = $data['total'];
			$this->pagination_service->set_show_count_tag(TRUE);
			$this->pagination_service->initialize($pconfig);
		}

		$data["notice"] = notice($lang);

		$data["pagetype"] = $pagetype;
		$data["sortimg"][$sort] = "<img src='".base_url()."images/".$order.".gif'>";
		$data["xsort"][$sort] = $order=="asc"?"desc":"asc";
		$data["searchdisplay"] = "";
		$data["bank_acc_list"] = $this->website_bank_transfer_service->get_list(array("status"=>1));

		$this->load->view('order/website_bank_transfer/website_bank_transfer_index_v', $data);
	}

	private function process_payment()
	{
		$result["status"] = TRUE;
		$result["error_msg"] = "";
		$allowed_ps = array("N", "P", "S");
		$get_net_diff = "";

		foreach (($this->input->post('select')) as $so_no => $selected)
		{
			if($selected)
			{
				$this->so_no = $so_no;
				$check_field = $this->check_field(); # check for mandatory fields
				if($check_field['status'])
				{
					$sobt_dao = $this->so_bank_transfer_dao;

					if($sops_obj = $this->so_service->get_sops_dao()->get(array("so_no"=>$so_no)))
					{
						if($sops_obj->get_payment_gateway_id() !== "w_bank_transfer")
						{
							$result["status"] = FALSE;
							$result["error_msg"] = "so_no <$so_no> is not a bank transfer";
							return $result;
						}

						# check db so_payment_status
						if(!in_array($sops_obj->get_payment_status(), $allowed_ps))
						{
							$result["status"] = FALSE;
							$result["error_msg"] = "so_no <$so_no> payment_status is Cancel/Fail/Chargeback/Cancel Failed";
							return $result;
						}
					}
					else
					{
						$result["status"] = FALSE;
						$result["error_msg"] = "Cannot retrieve so_payment_status so_no $so_no. \nDB error msg: ".$this->db->_error_message();
						return $result;
					}

					// POST variables
					$ref_no = $_POST["ref_no"][$so_no];
					$rec_acc_no = $_POST["rec_acc_no"][$so_no];
					$rec_amt = number_format($_POST["rec_amt"][$so_no], 2, '.', '');
					$bank_charge = number_format($_POST["bank_charge"][$so_no], 2, '.', '');
					$rec_date = $_POST["rec_date"][$so_no]; #sbf #3314 currently at GMT 0 for acc_no 40-05-15 74130557
					$note = $_POST["note"][$so_no];
					$order_amt = number_format($_POST["order_amt"][$so_no], 2, '.', '');

					$prev_received = number_format($_POST["prev_received"][$so_no], 2, '.', '');
					$prev_bank_charge = number_format($_POST["prev_bank_charge"][$so_no], 2, '.', '');

					// calculate net diff between total payment received and order amt
					$total_net_received = ($rec_amt-$bank_charge) + ($prev_received-$prev_bank_charge);

					// var_dump("$rec_amt ---- $bank_charge ---- $prev_received ----  $prev_bank_charge");die();

					if($get_net_diff = $this->get_net_diff_status($order_amt, $total_net_received))
					{
						$net_diff_status = $get_net_diff["status"];
						$status_text = $get_net_diff["status_text"];
					}
					else
					{
						$net_diff_status["status"] = FALSE;
						$net_diff_status["error_msg"] = "website_bank_transfer.php Failed to get net_diff";
						return $net_diff_status;
					}

					if($sobt_list = $sobt_dao->get_list(array("so_no"=>$so_no, "sbt_status"=>1)))
					{
						# if there are prev payment for current so_no, then update all net_diff_status
						foreach ($sobt_list as $sobt_obj)
						{
							$sobt_obj->set_net_diff_status($net_diff_status);
							if($sobt_dao->update($sobt_obj) === FALSE)
							{
								$result["status"] = FALSE;
								$result["error_msg"] = "Failed to update sbt.net_diff_status\nDB error msg:".$this->db->_error_message();
								return $result;
							}
						}
					}

					# create new entry for payment
					$sobt_obj = $sobt_dao->get();
					$sobt_obj->set_so_no($so_no);
					$sobt_obj->set_sbt_status(1);
					$sobt_obj->set_ext_ref_no($ref_no);
					$sobt_obj->set_received_amt_localcurr($rec_amt);
					$sobt_obj->set_bank_account_id($rec_acc_no);
					$sobt_obj->set_bank_charge($bank_charge);
					$sobt_obj->set_notes($note);
					$sobt_obj->set_received_date_localtime($rec_date);
					$sobt_obj->set_net_diff_status($net_diff_status);

					if($sobt_dao->insert($sobt_obj)===FALSE)
					{
						$result["status"] = FALSE;
						$result["error_msg"] = "Failed to save new payment for so_no $so_no. \nDB error msg: ".$this->db->_error_message();
						return $result;
					} else {
						$this->flex_service->w_bank_transfer_to_flex_ria($sobt_obj);
					}

					$this->so_bank_transfer_obj = $sobt_obj;
					# check if so & sops needs update with net_diff_status
					$update_so_sops = $this->update_other_with_net_diff($so_no, $net_diff_status, $rec_acc_no);
					if($update_so_sops["status"] === FALSE)
					{
						return $update_so_sops;
					}

					$this->status_summary .= "SO $so_no - $status_text\n";
				}
				else
				{
					return $check_field;
				}
			}
		}

		return $result;
	}

	private function update_so()
	{
		$result["status"] = TRUE;
		$result["error_msg"] = "";
		$sobt_dao = $this->so_bank_transfer_dao;
		$allowed_ps = array("N", "P", "S"); # if payment_status not any of these, don't update

		foreach (($this->input->post('select')) as $ext_ref_no => $selected)
		{
			if($selected)
			{
				$total_net_received = 0;

				if($so_no = $_POST["so_no"][$ext_ref_no])
				{
					if($sops_obj = $this->so_service->get_sops_dao()->get(array("so_no"=>$so_no)))
					{
						if($sops_obj->get_payment_gateway_id() !== "w_bank_transfer")
						{
							$result["status"] = FALSE;
							$result["error_msg"] = "so_no <$so_no> is not a bank transfer";
							return $result;
						}

						# check db so_payment_status
						if(!in_array($sops_obj->get_payment_status(), $allowed_ps))
						{
							$result["status"] = FALSE;
							$result["error_msg"] = "so_no <$so_no> payment_status is Cancel/Fail/Chargeback/Cancel Failed";
							return $result;
						}
					}
					else
					{
						$result["status"] = FALSE;
						$result["error_msg"] = "ERROR: No payment_status found with so_no <$so_no>";
						return $result;
					}

					if($so_obj = $this->so_service->get_dao()->get(array("so_no"=>$so_no)))
					{
						if($so_obj->get_hold_status != 0)
						{
							$result["status"] = FALSE;
							$result["error_msg"] = "ERROR: so_no <$so_no> is ON-HOLD.";
							return $result;
						}

						$order_amt = number_format($so_obj->get_amount(), 2, '.', '');
					}

					if($sobt_obj = $sobt_dao->get(array("ext_ref_no"=>$ext_ref_no, "sbt_status"=>1)))
					{
						$sobt_obj->set_so_no($so_no);
						if($sobt_dao->update($sobt_obj) === FALSE)
						{
							$result["status"] = FALSE;
							$result["error_msg"] = "Failed to update so_bank_transfer bank ref $ext_ref_no. \nDB error msg: ".$this->db->_error_message();
							return $result;
						}
					}

					if($sobt_list = $sobt_dao->get_list(array("so_no"=>$so_no, "sbt_status"=>1)))
					{
						# get all previous payments received with this so_no
						# then update net_diff_status, and check if need to update so and sops

						foreach ($sobt_list as $sobt_obj)
						{
							$total_net_received += number_format(($sobt_obj->get_received_amt_localcurr() - $sobt_obj->get_bank_charge()), 2,'.','');
						}

						// if($order_amt !== 0)
						// 	$net_diff = number_format((($order_amt - $net_received)/$order_amt*100),2,'.','');

						if($get_net_diff = $this->get_net_diff_status($order_amt, $total_net_received))
						{
							$net_diff_status = $get_net_diff["status"];
							$status_text = $get_net_diff["status_text"];
						}
						else
						{
							$net_diff_status["status"] = FALSE;
							$net_diff_status["error_msg"] = "website_bank_transfer.php Failed to get net_diff";
							return $net_diff_status;
						}

						foreach ($sobt_list as $sobt_obj)
						{
							# update so_bank_transfer.net_diff_status
							$sobt_obj->set_net_diff_status($net_diff_status);
							if($sobt_dao->update($sobt_obj) === FALSE)
							{
								$result["status"] = FALSE;
								$result["error_msg"] = "Failed to update so_bank_transfer bank ref $ext_ref_no. \nDB error msg: ".$this->db->_error_message();
								return $result;
							}

							$rec_acc_no = $sobt_obj->get_bank_account_id();
							$this->so_bank_transfer_obj = $sobt_obj;

							# check if so & sops needs update with net_diff_status
							$update_so_sops = $this->update_other_with_net_diff($so_no, $net_diff_status, $rec_acc_no);
							if($update_so_sops["status"] === FALSE)
							{
								return $update_so_sops;
							}
						}
					}
					$this->status_summary .= "SO $so_no - $status_text\n";

				}
				else
				{
					$result["status"] = FALSE;
					$result["error_msg"] = "Please insert so_no for Bank/Sale Ref <$ext_ref_no>";
					return $result;
				}
			}
		}
		return $result;
	}

	private function add_unknown()
	{
		# this function adds unknown payments that came in without so_no

		$result["status"] = TRUE;
		$result["error_msg"] = "";

		$check_field = $this->check_field(); # check for mandatory fields
		if($check_field['status'])
		{
			$so_no = 0;
			$sobt_dao = $this->so_bank_transfer_dao;

			// POST variables
			$ref_no = $_POST["ref_no"][$so_no];
			$rec_acc_no = $_POST["rec_acc_no"][$so_no];
			$rec_amt = number_format($_POST["rec_amt"][$so_no], 2, '.', '');
			$bank_charge = number_format($_POST["bank_charge"][$so_no], 2, '.', '');
			$rec_date = $_POST["rec_date"][$so_no]; #sbf #3314 currently at GMT 0 for acc_no 40-05-15 74130557
			$note = $_POST["note"][$so_no];

			# create new entry for payment
			$sobt_obj = $sobt_dao->get();
			$sobt_obj->set_sbt_status(1);
			$sobt_obj->set_ext_ref_no($ref_no);
			$sobt_obj->set_received_amt_localcurr($rec_amt);
			$sobt_obj->set_bank_account_id($rec_acc_no);
			$sobt_obj->set_bank_charge($bank_charge);
			$sobt_obj->set_notes($note);
			$sobt_obj->set_received_date_localtime($rec_date);
			$sobt_obj->set_net_diff_status(5);

			if($sobt_dao->insert($sobt_obj)===FALSE)
			{
				$result["status"] = FALSE;
				$result["error_msg"] = "Failed to save new payment for ref_no $ref_no. \nDB error msg: ".$this->db->_error_message();
				return $result;
			}
		}
		else
		{
			return $check_field;
		}

		return $result;
	}

	/****************************************************
		sbf #3314 this function will update db so and so_payment_status if
		net_diff_status fulfills criteria for order fulfilment and send out appropriate emails

		## $receive_acc_no refers to our bank account number that received the payment
	****************************************************/
	private function update_other_with_net_diff($so_no, $net_diff_status, $receive_acc_no)
	{
		$result["status"] = FALSE;
		$result["error_msg"] = $action = "";

		if($so_no && $net_diff_status && $receive_acc_no)
		{

			# fully paid, underpaid <= 1%, overpaid --> send out success order_confirmation email
			if($net_diff_status == 1 || $net_diff_status == 2 || $net_diff_status == 4)
			{
				if($so_obj = $this->so_service->get_dao()->get(array("so_no" => $so_no)))
				{
					#update so table
					$so_obj->set_status(3);

					if($this->so_service->get_dao()->update($so_obj) === FALSE)
					{
						$result["error_msg"] = "Failed to update so.status so_no $so_no. \nDB error msg: ".$this->db->_error_message();
						return $result;
					}
				}

				if($sops_obj = $this->so_service->get_sops_dao()->get(array("so_no"=>$so_no)))
				{
					#update so_payment_status table
					$sops_obj->set_payment_status('S');
					$sops_obj->set_pay_to_account($receive_acc_no);

					$date = date("Y-m-d H:i:s");
					$sops_obj->set_pay_date($date);

					if($this->so_service->get_sops_dao()->update($sops_obj) === FALSE)
					{
						$result["error_msg"] = "Failed to update so_payment_status so_no $so_no. \nDB error msg: ".$this->db->_error_message();
						return $result;
					} else {
						$this->flex_service->platfrom_order_insert_flex_ria('w_bank_transfer', $so_no);
					}
				}

				if($so_priorityscore_obj = $this->so_service->get_so_ps_srv()->get(array("so_no"=>$so_no)))
				{
					$action = "update";
				}
				else
				{
					$so_priorityscore_obj = $this->so_service->get_so_ps_srv()->get();
					$so_priorityscore_obj->set_so_no($so_no);
					$action = "insert";
				}

				$so_priorityscore_obj->set_score(1100);

				if($this->so_service->get_so_ps_srv()->$action($so_priorityscore_obj) === FALSE)
				{
					$result["error_msg"] = "Failed to update so_priority_score so_no $so_no. \nDB error msg: ".$this->db->_error_message();
					return $result;
				}

				# fire email
				$this->pmgw->so = $so_obj;
				$this->pmgw->fire_success_event();
			}
			elseif($net_diff_status == 3)
			{
				if($so_obj = $this->so_service->get_dao()->get(array("so_no" => $so_no)))
				{
					$this->pmgw->so = $so_obj;
				}

				$this->pmgw->so_bank_transfer_obj = $this->so_bank_transfer_obj;

				# fire email
				$this->pmgw->fire_collect_payment_event("reminder_partial_payment");
			}

			$result["status"] = TRUE;
		}
		else
		{
			$result["error_msg"] ="update_other_with_net_diff() - missing one argument.";

		}

		return $result;
	}

	private function get_net_diff_status($order_amt, $total_net_received)
	{
		# order_amount = so.cost
		# total_net_received = total payment received - total bank charges

		$net_diff = array();

		if($order_amt && $total_net_received)
		{
			$net_diff_percentage = ($order_amt - $total_net_received)/$order_amt*100;

			if($net_diff_percentage == 0)
				$net_diff_status = 1;
			elseif($net_diff_percentage >0 && $net_diff_percentage <= 1)
				$net_diff_status = 2;
			elseif($net_diff_percentage > 1)
				$net_diff_status = 3;
			elseif($net_diff_percentage <0)
				$net_diff_status = 4;

			switch ($net_diff_status)
			{
				case 1:
					$status_text = "Fully paid";
					break;
				case 2:
					$status_text = "Under paid <= 1%";
					break;
				case 3:
					$status_text = "Under paid > 1%";
					break;
				case 4:
					$status_text = "Over paid";
					break;
				default:
					$status_text = "";
					break;
			}

			$net_diff["status"] = $net_diff_status;
			$net_diff["status_text"] = $status_text;
		}

		return $net_diff;
	}

	private function check_field()
	{
		# this function loops through all input fields per so_no to check for empty fields

		# mandatory fields (insert <input name> here)
		$not_empty = array("ref_no", "rec_acc_no", "rec_amt", "bank_charges", "rec_date");

		$result = array();
		$result["status"] = TRUE;
		$so_no = $this->so_no;

		if(!$so_no)
			$so_no = 0; #in the case of adding NEW unknown bank transfer

		foreach ($_POST as $key => $value)
		{
			if(in_array($key, $not_empty) && !$_POST[$key][$so_no])
			{
				$error_msg .= "$key cannot be empty.\n";
			}
		}

		if($error_msg)
		{
			$result["status"] = FALSE;
			$result["error_msg"] = "Error: Order ID $so_no \n$error_msg";
		}

		return $result;
	}

	public function _get_app_id(){
		return $this->app_id;
	}

	public function _get_lang_id(){
		return $this->lang_id;
	}
}
