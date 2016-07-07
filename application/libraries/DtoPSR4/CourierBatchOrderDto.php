<?php

class CourierBatchOrderDto 
{
	//class variable
	private $batch_id;
	private $so_no;
	private $courier_id;
	private $service_type;
	private $delivery_name;
	private $delivery_company;
	private $delivery_address;
	private $delivery_address_1;
	private $delivery_address_2;
	private $delivery_address_3;
	private $delivery_city;
	private $delivery_state;
  	private $delivery_postcode;
  	private $delivery_country_id;
  	private $delivery_phone;
  	private $declared_value;
  	private $email;
  	private $weight;
  	private $prod_weight;
  	private $master_sku;
  	private $declared_currency;
  	private $manifest_id;
 

  	private $courier_order_status;
  	private $courier_parcel_id;
  	private $tracking_no;
  	private $real_tracking_no;
  	private $error_message;
  	private $status;

  	private $sh_no;
  	private $declared_desc;
  	private $declared_hs_code;
  	private $declared_type;
  	private $print_nums;
  	private $last_print_on;
  	private $create_date;
	//instance method

	public function getBatchId()
	{
		return $this->batch_id;
	}

	public function setBatchId($value){
		
		$this->batch_id = $value;
	}

	public function getSoNo()
	{
		return $this->so_no;
	}

	public function setSoNo($value){
		
		$this->so_no = $value;
	}

	public function setCourierId($value){
		
		$this->courier_id = $value;
	}

	public function getCourierId(){
		
		return $this->courier_id;
	}

	public function getServiceType(){
		
		return $this->service_type;
	}

	public function setServiceType($value){
		
		$this->service_type = $value;
	}

	public function getDeliveryName(){
		
		return $this->delivery_name;
	}

	public function setDeliveryName($value){
		
		$this->delivery_name = $value;
	}

	public function getDeliveryCompany(){
		
		return $this->delivery_company;
	}

	public function setDeliveryCompany($value){
		
		$this->delivery_company = $value;
	}

	public function getDeliveryAddress(){
		
		return $this->delivery_address;
	}

	public function setDeliveryAddress($value){
		
		$this->delivery_address = $value;
	}

	public function getDeliveryAddress1(){
		
		return $this->delivery_address_1;
	}

	public function setDeliveryAddress1($value){
		
		$this->delivery_address_1 = $value;
	}

	public function getDeliveryAddress2(){
		
		return $this->delivery_address_2;
	}

	public function setDeliveryAddress2($value){
		
		$this->delivery_address_2 = $value;
	}

	public function getDeliveryAddress3(){
		
		return $this->delivery_address_3;
	}

	public function setDeliveryAddress3($value){
		
		$this->delivery_address_3 = $value;
	}

	public function getDeliveryPostcode(){
		
		return $this->delivery_postcode;
	}

	public function setDeliveryPostcode($value){
		
		$this->delivery_postcode = $value;
	}

	public function getDeliveryCity(){
		
		return $this->delivery_city;
	}

	public function setDeliveryCity($value){
		
		$this->delivery_city = $value;
	}

	public function getDeliveryState(){
		
		return $this->delivery_state;
	}

	public function setDeliveryState($value){
		
		$this->delivery_state = $value;
	}

	public function getDeliveryCountryId(){
		
		return $this->delivery_country_id;
	}

	public function setDeliveryCountryId($value){
		
		$this->delivery_country_id = $value;
	}

	public function getDeliveryPhone(){
		
		return $this->delivery_phone;
	}

	public function setDeliveryPhone($value){
		
		$this->delivery_phone = $value;
	}

	public function getEmail(){
		
		return $this->email;
	}

	public function setEmail($value){
		
		$this->email = $value;
	}

	public function getWeight(){
		
		return $this->weight;
	}

	public function setWeight($value){
		
		$this->weight = $value;
	}

	public function getProdWeight(){
		
		return $this->prod_weight;
	}

	public function setProdWeight($value){
		
		$this->prod_weight = $value;
	}

	public function getDeclaredValue(){

		return $this->declared_value;
	}

	public function setDeclaredValue($value){
		
		$this->declared_value = $value;
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

	public function getStatus(){

		return $this->status;
	}

	public function setStatus($value){
		
		$this->status = $value;
	}

	public function getShNo(){

		return $this->sh_no;
	}

	public function setShNo($value){
		
		$this->sh_no = $value;
	}

	public function getDeclaredDesc(){

		return $this->declared_desc;
	}

	public function setDeclaredDesc($value){
		
		$this->declared_desc = $value;
	}

	public function getDeclaredHsCode(){

		return $this->declared_hs_code;
	}

	public function setDeclaredHsCode($value){
		
		$this->declared_hs_code = $value;
	}

	public function getDeclaredType(){

		return $this->declared_type;
	}

	public function setDeclaredType($value){
		
		$this->declared_type = $value;
	}

	public function getPrintNums(){

		return $this->print_nums;
	}

	public function setPrintNums($value){
		
		$this->print_nums = $value;
	}

	public function getLastPrintOn(){

		return $this->last_print_on;
	}

	public function setLastPrintOn($value){
		
		$this->last_print_on = $value;
	}

	public function getCreateDate(){

		return $this->create_date;
	}

	public function setCreateDate($value){
		
		$this->create_date = $value;
	}

	public function getMasterSku(){

		return $this->master_sku;
	}

	public function setMasterSku($value){
		
		$this->master_sku = $value;
	}

	public function getDeclaredCurrency(){

		return $this->declared_currency;
	}

	public function setDeclaredCurrency($value){
		
		$this->declared_currency = $value;
	}

	public function getManifestId(){

		return $this->manifest_id;
	}

	public function setManifestId($value){
		
		$this->manifest_id = $value;
	}

}
