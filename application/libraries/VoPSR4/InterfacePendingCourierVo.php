<?php  
class InterfacePendingCourierVo extends \BaseVo 
{

	private $trans_id;
	private $batch_id;
  	private $so_no;
  	private $sh_no;
  	private $courier_id;
  	private $service_type;
  	private $weight;
  	private $delivery_name;
  	private $delivery_company;
  	private $delivery_address_1;
  	private $delivery_address_2;
  	private $delivery_address_3;
  	private $delivery_postcode;
  	private $delivery_city;
  	private $delivery_state;
  	private $delivery_country_id;
  	private $delivery_phone;
  	private $email;
  	private $declared_desc;
  	private $declared_hs_code;
  	private $declared_value;
  	private $declared_type;
  	private $declared_currency;
  	private $master_sku;


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

	public function getSoNo()
	{
		return $this->so_no;
	}

	public function setSoNo($value)
	{
		$this->so_no = $value;
	}

	public function getShNo()
	{
		return $this->sh_no;
	}

	public function setShNo($value)
	{
		$this->sh_no = $value;
	}

	public function getCourierId()
	{
		return $this->courier_id;
	}

	public function setCourierId($value)
	{
		$this->courier_id = $value;
	}

	public function getServiceType()
	{
		return $this->service_type;
	}

	public function setServiceType($value){
		
		$this->service_type = $value;
	}

	public function getWeight(){
		
		return $this->weight;
	}

	public function setWeight($value){
		
		$this->weight = $value;
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

	public function getDeclaredValue(){
		
		return $this->declared_value;
	}

	public function setDeclaredValue($value){
		
		$this->declared_value = $value;
	}

	public function getDeclaredType(){
		
		return $this->declared_type;
	}

	public function setDeclaredType($value){
		
		$this->declared_type = $value;
	}

	public function getDeclaredCurrency(){
		
		return $this->declared_currency;
	}

	public function setDeclaredCurrency($value){
		
		$this->declared_currency = $value;
	}

	public function getMasterSku(){
		
		return $this->master_sku;
	}

	public function setMasterSku($value){
		
		$this->master_sku = $value;
	}


}

/* End of file brand_w_region_dto.php */
/* Location: ./system/application/libraries/vo/interface_pending_courier_vo.php */