<?php 

class CourierOrderDto
{	
	private $batch_id;
	private $courier_id;
	private $courier_order_id;
	private $courier_order_status;
	private $courier_parcel_id;
	private $tracking_no;
	private $real_tracking_no;
	private $error_message;
	//instance method

	public function getBatchId(){
		
		return $this->batch_id;
	}

	public function setBatchId($value){
		
		$this->batch_id = $value;
	}

	public function setCourierId($value){
		
		$this->courier_id = $value;
	}

	public function getCourierId(){
		
		return $this->courier_id;
	}

	public function setCourierOrderId($value){
		
		$this->courier_order_id = $value;
	}

	public function getCourierOrderId(){
		
		return $this->courier_order_id;
	}

	public function getCourierOrderStatus(){
		
		return $this->courier_order_status;
	}

	public function setCourierOrderStatus($value){
		
		$this->courier_order_status = $value;
	}

	public function getCourierParcelId(){
		
		return $this->courier_parcel_id;
	}

	public function setCourierParcelId($value){
		
		$this->courier_parcel_id = $value;
	}


	public function getTrackingNo(){
		
		return $this->tracking_no;
	}

	public function setTrackingNo($value){
		
		$this->tracking_no = $value;
	}

	public function getRealTrackingNo(){
		
		return $this->real_tracking_no;
	}

	public function setRealTrackingNo($value){
		
		$this->real_tracking_no = $value;
	}

	public function getErrorMessage(){
		
		return $this->error_message;
	}

	public function setErrorMessage($value){
		
		$this->error_message = $value;
	}
	
}
