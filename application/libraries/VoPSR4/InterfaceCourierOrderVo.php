<?php  
class InterfaceCourierOrderVo extends \BaseVo
{
	//class variable
	private $trans_id;
	private $batch_id;
	private $courier_order_id;
	private $courier_id;
	private $courier_order_status;
	private $courier_parcel_id;
	private $tracking_no;
	private $real_tracking_no;
	private $error_message;
	private $status;
	private $print_nums;
	private $last_print_on;
	

	//primary key
	protected $primary_key = array("trans_id");
	//auo increment
	protected $increment_field = "trans_id";

	//instance method
	public function getTransId()
	{
		return $this->trans_id;
	}

	public function setTransId($value)
	{
		$this->trans_id = $value;
	}

	public function getBatchId()
	{
		return $this->batch_id;
	}

	public function setBatchId($value)
	{
		$this->batch_id = $value;
	}

	public function getCourierOrderId()
	{
		return $this->courier_order_id;
	}

	public function setCourierOrderId($value)
	{
		$this->courier_order_id = $value;
	}

	public function getCourierId()
	{
		return $this->courier_id;
	}

	public function setCourierId($value)
	{
		$this->courier_id = $value;
	}

	public function getCourierOrderStatus()
	{
		return $this->courier_order_status;
	}

	public function setCourierOrderStatus($value)
	{
		$this->courier_order_status = $value;
	}

	public function getCourierParcelId()
	{
		return $this->courier_parcel_id;
	}

	public function setCourierParcelId($value)
	{
		$this->courier_parcel_id = $value;
	}

	public function getTrackingNo()
	{
		return $this->tracking_no;
	}

	public function setTrackingNo($value)
	{
		$this->tracking_no = $value;
	}

	public function getRealTrackingNo()
	{
		return $this->real_tracking_no;
	}

	public function setRealTrackingNo($value)
	{
		$this->real_tracking_no = $value;
	}
	
	public function getErrorMessage()
	{
		return $this->error_message;
	}

	public function setErrorMessage($value)
	{
		$this->error_message = $value;
	}

	public function getStatus()
	{
		return $this->status;
	}

	public function setStatus($value)
	{
		$this->status = $value;
	}

	public function getPrintNums()
	{
		return $this->print_nums;
	}

	public function setPrintNums($value)
	{
		$this->print_nums = $value;
	}

	public function getLastPrintOn()
	{
		return $this->last_print_on;
	}

	public function setLastPrintOn($value)
	{
		$this->last_print_on = $value;
	}

}
