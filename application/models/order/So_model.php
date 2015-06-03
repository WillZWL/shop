<?php
class So_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('service/so_service');
		include_once(APPPATH. 'libraries/service/Courier_service.php');
		$this->set_courier_service(new Courier_service());
		include_once(APPPATH. 'libraries/service/Refund_service.php');
		$this->set_refund_srv(new Refund_service());
		include_once(APPPATH. 'libraries/service/Product_service.php');
		$this->set_product_srv(new Product_service());
		include_once(APPPATH. 'libraries/service/Cps_allocated_so_service.php');
		$this->set_cas_srv(new Cps_allocated_so_service());
		include_once(APPPATH. 'libraries/service/Wms_inventory_service.php');
		$this->set_wmsi_srv(new Wms_inventory_service());
	}

	public function get_valid_website_status_list()
	{
		return array("I"=>"In-stock", "P"=>"Pre-order");
	}

	public function get_list($dao="dao", $where=array(), $option=array())
	{
		$dao = "get_".$dao;
		return $this->so_service->$dao()->get_list($where, $option);
	}

	public function get_num_rows($dao="dao", $where=array())
	{
		$dao = "get_".$dao;
		return $this->so_service->$dao()->get_num_rows($where);
	}

	public function get($dao="dao", $where=array())
	{
		$dao = "get_".$dao;
		return $this->so_service->$dao()->get($where);
	}

	public function update($dao="dao", $obj)
	{
		$dao = "get_".$dao;
		return $this->so_service->$dao()->update($obj);
	}

	public function add($dao="dao", $obj)
	{
		$dao = "get_".$dao;
		return $this->so_service->$dao()->insert($obj);
	}

	public function include_vo($dao)
	{
		$dao = "get_".$dao;
		return $this->so_service->$dao()->include_vo();
	}

	public function generate_courier_file($checked=array(), $courier='', $mawb='', $debug_explain = false)
	{
		return $this->so_service->generate_courier_file($checked, $courier, $mawb, $debug_explain);
	}

	public function generate_allocate_file()
	{
		return $this->so_service->generate_allocate_file();
	}

	public function error_in_allocate_file()
	{
		return $this->so_service->error_in_allocate_file();
	}

	public function get_rma_vo()
	{
		return $this->so_service->get_rma_vo();
	}

	public function insert_rma($obj)
	{
		return $this->so_service->insert_rma($obj);
	}

	public function get_rma_notes_vo()
	{
		return $this->so_service->get_rma_notes_vo();
	}

	public function insert_rma_notes($obj)
	{
		return $this->so_service->insert_rma_notes($obj);
	}

	public function get_rma_history_vo()
	{
		return $this->so_service->get_rma_history_vo();
	}

	public function insert_rma_history($obj)
	{
		return $this->so_service->insert_rma_history($obj);
	}

	public function get_order_status($so_obj)
	{
		$status_details_arr = array(
		"payment_check"=>array("id"=>"payment_check", "status"=>"Checking Payment Details", "desc"=>"Your payment details are being verified."),
		"payment_validated"=>array("id"=>"payment_validated", "status"=>"Payment Details Validated", "desc"=>"Your payment details has been validated"),
		"order_check"=>array("id"=>"order_check", "status"=>"Checking Order Details", "desc"=>"Your order details are being checked"),
		"order_approved"=>array("id"=>"order_approved", "status"=>"Order Details Approved", "desc"=>"Your order has been approved"),
		"order_handling"=>array("id"=>"order_handling", "status"=>"Order Handling", "desc"=>"Your order is being made ready"),
		"order_picking"=>array("id"=>"order_picking", "status"=>"Order Picking", "desc"=>"Your order is in queue for dispatch"),
		"order_in_queue"=>array("id"=>"order_in_queue", "status"=>"Order in Queue", "desc"=>"We are experiencing slight backlog at this time but anticipate dispatch in the coming days. Thank you for your patience."),
		"order_delay"=>array("id"=>"order_delay", "status"=>"Order Delay", "desc"=>"Apologies for the inconvenience caused. We have set your order as priority and look forward to dispatching it ASAP. Your continued patience is much appreciated."),
		"arranging_stock"=>array("id"=>"arranging_stock", "status"=>"Arranging Stock", "desc"=>"We are arranging stock for your order. Dispatch can be expected within a few days."),
		"allocated"=>array("id"=>"allocated", "status"=>"Allocated", "desc"=>"Stock has been allocated and will ship within 1-4 days time."),
		"shipped"=>array("id"=>"shipped", "status"=>"Shipped", "desc"=>"Your order was picked up by our couirier and will be with you soon"),
		"order_hold"=>array("id"=>"order_hold", "status"=>"Order Held", "desc"=>"You will need to contact us for more details. Kindly refer to our Contact us page."),
		"order_refund_pending"=>array("id"=>"order_refund_pending", "status"=>"Order Refund Pending", "desc"=>"Your refund is in progress"),
		"order_refunded"=>array("id"=>"order_refunded", "status"=>"Order Refunded", "desc"=>"Your order has been refunded"),
		"cancel_received"=>array("id"=>"cancel_received", "status"=>"Cancellation Request Received", "desc"=>"Your cancellation request has been received."),
		"cancel_to_delivery"=>array("id"=>"cancel_to_delivery", "status"=>"Order Allocated - Cancellation Request Sent", "desc"=>"Your order is in our dispatch line. We have contacted our warehouse to stop its dispatch."),
		"cancel_confirmed"=>array("id"=>"cancel_confirmed", "status"=>"Cancellation Request Confirmed", "desc"=>"Your cancellation request has been confirmed."),
		"refund_in_process"=>array("id"=>"refund_in_process", "status"=>"Refund Request in Process", "desc"=>"Your refund request was received and will be processed."),
		"refund_confirmed"=>array("id"=>"refund_confirmed", "status"=>"Refund Request Initiated", "desc"=>"Your refund has been processed on our side. It should be with you soon."),
		"refund_on_pmgw"=>array("id"=>"refund_on_pmgw", "status"=>"Refund Submitted to Payment Gateway", "desc"=>"Your refund has been processed on our side. Please allow a few working days for your bank to credit the funds back."),

		"refund_submitted"=>array("id"=>"refund_submitted", "status"=>"Refund Request Submitted", "desc"=>"Your order has been submitted for refund. An email notification will be sent to you when it is completed."),
		"refund_in_priority"=>array("id"=>"refund_in_priority", "status"=>"Refund Request on Priority", "desc"=>"Your refund request has been approved and is being processed by our payment partners. We are currently working with the payment gateway to make sure you get your refund sooner."),
		"refund_escalated"=>array("id"=>"refund_escalated", "status"=>"Refund Request Escalated", "desc"=>"Apologies for any inconvenience caused. Your refund has been escalated and is being treated with high priority. A refund confirmation email can be expected upon completion."),

		"refunded"=>array("id"=>"refunded", "status"=>"Refunded", "desc"=>"Refund Done.")
		);
		$now_time = mktime();
		$status = $working_days = "";
		$time_diff = $now_time - strtotime($so_obj->get_order_create_date());
		$order_status = $so_obj->get_status();
		$hold_status = $so_obj->get_hold_status();
		$refund_status = $so_obj->get_refund_status();

		# hold_status = 15 refers to parent of split orders. It will have refund status but no refund history
		if($refund_status > 0 && $hold_status != 15)
		{
			$refund_obj = $this->get_refund_srv()->get(array("so_no"=>$so_obj->get_so_no()));
			$refund_time_diff = $now_time - strtotime($refund_obj->get_create_on());
			$refund_working_days = $this->so_service->get_working_days(strtotime($refund_obj->get_create_on()), $now_time);
			if($refund_time_diff < 43200)
			{
				$status = 'cancel_received';
			}
			else
			{
				switch($refund_status)
				{
					case 1:
					case 2:
					case 3:
						if($refund_item_obj = $this->get_refund_srv()->get_refund_item(array("refund_id"=>$refund_obj->get_id(), "status"=>"N")))
						{
							$status = 'cancel_to_delivery';
						}
						else
						{
							if($refund_working_days < 3)
							{
								$status = 'cancel_confirmed';
							}
							elseif ($refund_working_days < 5)
							{
								$status = 'refund_submitted';
							}
							elseif ($refund_working_days < 7)
							{
								$status = 'refund_in_process';
							}
							elseif ($refund_working_days < 9)
							{
								$status = 'refund_in_priority';
							}
							else
							{
								$status = 'refund_escalated';
							}
						}
						break;
					case 4:
						if($complete_refund_obj = $this->get_refund_srv()->get_refund_history(array("refund_id"=>$refund_obj->get_id(), "status"=>"C")))
						{
							$refunded_date = $complete_refund_obj->get_create_on();
							$refunded_working_days = $this->so_service->get_working_days(strtotime($refunded_date), $now_time);
							if($refunded_working_days < 2)
							{
								$status = 'refund_confirmed';
							}
							elseif($refunded_working_days < 3)
							{
								$status = 'refund_on_pmgw';
							}
							else
							{
								$status = 'refunded';
							}
						}
						else
						{
							$status = 'refunded';
						}
						break;
				}
			}
		}
		elseif($hold_status > 0)
		{
			$status = 'order_hold';
		}
		else
		{
			switch($order_status)
			{
				case 2:
					if($time_diff < 43200)
					{
						$status = 'payment_check';
					}
					elseif($time_diff < 86400)
					{
						$status = 'payment_validated';
					}
					else
					{
						$status = 'order_check';
					}
					break;
				case 3:
					$working_days = $this->so_service->get_working_days(strtotime($so_obj->get_order_create_date()), $now_time);
					if($working_days < 5)
					{
						$status = 'order_approved';
					}
					elseif ($working_days < 7)
					{
						$status = 'order_handling';
					}
					elseif ($working_days < 9)
					{
						$status = 'order_picking';
					}
					elseif ($working_days < 11)
					{
						$status = 'order_in_queue';
					}
					else
					{
						$status = 'order_delay';
					}
					break;
				case 4:
				case 5:
			//		$soal = $this->so_service->check_if_packed($so_no = $so_obj->get_so_no());
			//		if($soal)
			//		{
			//			foreach ($soal as $soal_obj)
			//			{
			//				$working_days = $this->so_service->get_working_days(strtotime($soal_obj->get_modify_on()), $now_time);
			//			}
			//		}
					// if ($working_days < 2)
					// {

					// 	$status = 'arranging_stock';
					// }
					// else
					// {
						$status = 'allocated';
					// }
					break;
				case 6:
					$status = 'shipped';
					if($so_obj->get_dispatch_date())
					{
						$working_days = $this->so_service->get_working_days(strtotime($so_obj->get_dispatch_date()), $now_time);
						//if($working_days < 30)
						// {
						// 	if($shipment_obj = $this->so_service->get_shipping_info(array("soal.so_no"=>$so_obj->get_so_no(), "soal.status"=>3)))
						// 	{
						// 		if($courier_obj = $this->get_courier_service()->get(array("id"=>$shipment_obj->get_courier_id())))
						// 		{
						// 			if($shipment_obj->get_tracking_no() && $courier_obj->get_tracking_link())
						// 			{
						// 				$status = 'shipped_w_tracking_1';
						// 				$status_details_arr[$status]['id'] = $status;
						// 				//$status_details_arr[$status]['status'] = 'Shipped';
						// 				//$status_details_arr[$status]['desc'] = 'Your order was shipped using '.$courier_obj->get_courier_name().'. The tracking number is <a href="'.$courier_obj->get_tracking_link().$shipment_obj->get_tracking_no().'" target="_cnv">'.$shipment_obj->get_tracking_no().'</a>';
						// 				$status_details_arr[$status]['courier_name'] = $courier_obj->get_courier_name();
						// 				$status_details_arr[$status]['tracking_url'] = $courier_obj->get_tracking_link();
						// 				$status_details_arr[$status]['tracking_number'] = $shipment_obj->get_tracking_no();
						// 			}
						// 			else
						// 			{
						// 				$status = 'shipped_w_tracking_1';
						// 				$status_details_arr[$status]['id'] = $status;
						// 				//$status_details_arr[$status]['status'] = 'Shipped';
						// 				//$status_details_arr[$status]['desc'] = 'Your order was shipped using '.$courier_obj->get_courier_name().'. You can expect delivery within a couple of days.';
						// 				$status_details_arr[$status]['courier_name'] = $courier_obj->get_courier_name();
						// 			}
						// 		}
						// 	}
						// }

						//SBF #5275 dynamic status based on aftership status
						$shipment_obj = $this->so_service->get_soext_dao()->get(array("so_no" => $so_obj->get_so_no()));
						$aftership = $shipment_obj->get_aftership_status();

						if($aftership == ''){
							if($working_days < 1)
							{
								$status = 'shipped_w_tracking_1';
							}
							elseif($working_days < 3)
							{
								$status = 'shipped_w_tracking_2';
							}
							elseif($working_days < 5)
							{
								$status = 'shipped_w_tracking_3';
							}
							else
							{
								$status = 'shipped_w_tracking_3';
							}
						}
						else
						{
							if((($aftership == 1) || ($aftership == 2)) && ($working_days < 1)){
								$status = 'shipped_w_tracking_1';
							}
							if((($aftership == 1) || ($aftership == 2)) && ($working_days < 3)){
								$status = 'shipped_w_tracking_2';
							}
							if((($aftership == 1) || ($aftership == 2)) && ($working_days < 5)){
								$status = 'shipped_w_tracking_3';
							}
							elseif(($aftership == 3) && ($working_days < 9)){
								$status = 'shipped_w_tracking_4';
							}
							elseif(($aftership == 3) && ($working_days >= 9)){
								$status = 'shipped_w_tracking_4_postal';
							}
							elseif($aftership == 4){
								$status = 'shipped_w_tracking_5';
							}
							elseif($aftership == 5){
								$status = 'shipped_w_tracking_7';
							}
							elseif($aftership == 6){
								$status = 'shipped_w_tracking_6';
							}
							elseif($aftership == 7){
								$status = 'shipped_w_tracking_8';
							}
							elseif($aftership == 8){
								$status = 'shipped_w_tracking_9';
							}
							else
							{
								$status = 'shipped_w_tracking_3';
							}
						}

						$status_details_arr[$status]['id'] = $status;



							// this if statement NEEDS the courier_id to have an entry in courier table.
						 	if($shipment_obj2 = $this->so_service->get_shipping_info(array("soal.so_no"=>$so_obj->get_so_no(), "soal.status"=>3)))
						 	{
						 		if($courier_obj = $this->get_courier_service()->get(array("id"=>$shipment_obj2->get_courier_id())))
						 		{
						 			$status_details_arr[$status]['courier_name'] = $shipment_obj2->get_courier_id();

									if($shipment_obj2->get_tracking_no() && $courier_obj->get_tracking_link())
						 			{
						 				$status_details_arr[$status]['courier_name'] = $courier_obj->get_courier_name();
						 				$status_details_arr[$status]['tracking_url'] = $courier_obj->get_tracking_link();
						 				$status_details_arr[$status]['tracking_number'] = $shipment_obj2->get_tracking_no();
						 			}
						 		}

						 	}












					}

					break;
			}
		}
		//echo '<pre>'; var_dump($status); die();
		return $status_details_arr[$status];
	}

	public function get_so_priority_score_info($so_no_array)
	{
		return $this->so_service->get_so_priority_score_info($so_no_array);
	}

	public function get_priority_score($so_no)
	{
		return $this->so_service->get_priority_score($so_no);
	}

	public function get_courier_service()
	{
		return $this->courier_service;
	}

	public function set_courier_service(Base_service $srv)
	{
		$this->courier_service = $srv;
	}

	public function get_refund_srv()
	{
		return $this->refund_srv;
	}

	public function set_refund_srv(Base_service $srv)
	{
		$this->refund_srv = $srv;
	}

	public function get_product_srv()
	{
		return $this->product_srv;
	}

	public function set_product_srv(Base_service $srv)
	{
		$this->product_srv = $srv;
	}

	public function get_product_clearance($prod_sku)
	{
		return $this->get_product_srv()->is_clearance($prod_sku);
	}

	public function get_cas_srv()
	{
		return $this->cas_srv;
	}

	public function set_cas_srv(Base_service $srv)
	{
		$this->cas_srv = $srv;
	}

	public function get_wmsi_srv()
	{
		return $this->wmsi_srv;
	}

	public function set_wmsi_srv(Base_service $srv)
	{
		$this->wmsi_srv = $srv;
	}

	public function get_allocation_plan_order($where = array(), $option = array())
	{
		return $this->get_cas_srv()->get_list($where, $option);
	}

	public function get_wms_allocation_plan_order()
	{
		return $this->get_wmsi_srv()->get_wms_so_no_list();
	}
}

/* End of file so_model.php */
/* Location: ./system/application/models/so_model.php */
?>