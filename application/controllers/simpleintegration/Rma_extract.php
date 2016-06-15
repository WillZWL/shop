<?php
class Rma_extract extends PUB_Controller
{

	public function __construct() {
	        parent::__construct();
//$this->_checkoutFormData = $formValue;
	}
	// public function Rma_extract()
	// {
	// 	// parent::PUB_Controller();
	// 	// $this->load->library('service/rma_extract_service');
	// 	// $this->load->library('dao/so_dao');
	// 	// $this->load->library('dao/refund_history_dao');
	// }

	public function index($include_cc = 0)
	{
		// $where = $option = array();
		// if ($include_cc == 1)
		// 	$where['so.status in (2, 3)'] = null;
		// else
		// 	$where['so.status ='] = 3;
		// $where['so.refund_status ='] = 0;
		// $where['so.hold_status ='] = 0;
		// $option["orderby"] = 'so.so_no desc';
		// $option['limit'] = -1;

  //       if ((isset($_GET["includeBill"])) && ($_GET["includeBill"] == 1))
  //           $option['includeBill'] = 1;

		// $feed = $this->rma_extract_service->get_sales_order($where, $option);
		// header('Content-type: text/xml');
		// print $feed;
	}

	// public function get_all_order_list($date)
	// {
	// 	$where = $option = array();
	// 	$option['limit'] = -1;
	// 	$where['so.status > 1'] = null;
	// 	$where['so.order_create_date >= '] = $date ." 00:00:00";
	// 	$where['so.order_create_date <= '] = $date ." 23:59:59";
	// 	$option["orderby"] = 'so.so_no desc';
	// 	$feed = $this->rma_extract_service->get_sales_order($where, $option);
	// 	//echo $this->clwms_service->get_dao()->db->last_query();die();
	// 	header('Content-type: text/xml');
	// 	print $feed;
	// }

	public function get_rma_order_details($so_no)
	{
		$where = $option = array();
		$option['limit'] = -1;
		$where['so_no'] = $so_no;
		$where['so.status > 1'] = null;
		// $where['so.order_create_date >= '] = $date ." 00:00:00";
		// $where['so.order_create_date <= '] = $date ." 23:59:59";
		//$option["orderby"] = 'so.so_no desc';

		$data = $this->sc["RmaExtract"]->getRmaOrderDetails($where, $option);

		//$data = $this->rma_extract_service->get_rma_order_details($where, $option);
		//echo $this->clwms_service->get_dao()->db->last_query();die();

		if($data){
			echo json_encode($data);
		}
		// header('Content-type: text/xml');
		// print $feed;
	}

	public function get_mastersku_details($mastersku)
	{
		$where = $option = array();
		$where['sk.ext_sku'] = $mastersku;
		$where['pc.lang_id'] = 'en'; 
		$where['sp.order_default'] = 1;

		$data = $this->sc["RmaExtractService"]->getMasterskuDetails($where, $option);
		//$data = $this->rma_extract_service->get_mastersku_details($where, $option);

		if($data){
			echo json_encode($data);
		}

	}

	public function get_rma_refund_status($orderno, $sku)
	{
		//check if so is in refund status
		//$this->getDao('So')->getList(array('so_no'=>$orderno))

		//if($so_obj = $this->so_dao->get(array('so_no'=>$orderno)))
		if($this->getDao('So')->getList(array('so_no'=>$orderno)))
		{
			if($so_obj->get_refund_status() == 0)
			{
				$check = 0;
			}else{
				$check = 1;
			}	
		}	 
		//$check = $this->rma_extract_service->check_refund_stat($orderno);
		if($check == 0){
			// no refund in order
			echo 0;
		}else{			
			//there is refund, so get refund details
			$where['a.so_no'] = $orderno;
			//$where['b.item_sku'] = $sku;
			$option = '';
			//$refund_obj = $this->rma_extract_service->get_refund_details($where, $option);
			//$history = $this->refund_history_dao->get_list(array('refund_id'=>$refund_obj[0]['id']));

			$refund_obj = $this->sc["RmaExtractService"]->getRefundDetails($where, $option);
			$history = $this->getDao('RefundHistory')->getList(array('refund_id'=>$refund_obj[0]['id']));	

			foreach($history as $hist){
				$row['refund_id'] = $hist->get_refund_id();
				$row['status'] = $hist->get_status();
				$row['app_status'] = $hist->get_app_status();
				$row['notes'] = $hist->get_notes();
				$row['create_on'] = $hist->get_create_on();
				$row['create_by'] = $hist->get_create_by();

				$histrow[] = $row;
			}
		
			$data['item'] = $refund_obj;
			$data['history'] = $histrow;

			echo json_encode($data);
		}

	}


}