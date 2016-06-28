<?php 

class EnableApiCourierOrderDto 
{
	private $platform_id;
	private $so_no;
	private $sh_no;
	private $tel;
	private $email;
	private $delivery_name;
	private $delivery_company;
	private $delivery_address;
	private $delivery_address_1;
	private $delivery_address_2;
	private $delivery_address_3;
	private $delivery_postcode;
	private $delivery_city;
	private $delivery_state;
	private $delivery_country_id;
	private $line_no;
	private $sku;
	private $prod_name;
	private $sub_cat_id;
	private $qty;
	private $currency_id;
	private $unit_price;
	private $weight;
	private $declared_value;
	private $delivery_charge;
	private $amount;
	private $old_declared_value;
	private $declared_desc;
	private $cc_desc;
	private $cc_code;
	private $courier_id;
	private $interface_tracking_no;
	private $actual_cost;
	private $offline_fee = '0.00';
	private $item_no;
	
	public function setPlatformId($value)
	{
		$this->platform_id = $value;
	}

	public function getPlatformId()
	{
		return $this->platform_id;
	}

	public function setSoNo($value)
	{
		$this->so_no = $value;
	}

	public function getSoNo()
	{
		return $this->so_no;
	}

	public function setShNo($value)
	{
		$this->sh_no = $value;
	}

	public function getShNo()
	{
		return $this->sh_no;
	}

	public function setTel($value)
	{
		$this->tel = $value;
	}

	public function getTel()
	{
		return $this->getTel1().$this->getTel2().$this->getTel3();
	}

	public function setTel1($value)
	{
		$this->tel_1 = $value;
	}

	public function getTel1()
	{
		return $this->tel_1;
	}
	public function setTel2($value)
	{
		$this->tel_2 = $value;
	}

	public function getTel2()
	{
		return $this->tel_2;
	}

	public function setTel3($value)
	{
		$this->tel_3 = $value;
	}

	public function getTel3()
	{
		return $this->tel_3;
	}

	public function setEmail($value)
	{
		$this->email = $value;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function setDeliveryName($value)
	{
		$this->delivery_name = $value;
	}

	public function getDeliveryName()
	{
		return $this->delivery_name;
	}

	public function setDeliveryCompany($value)
	{
		$this->delivery_company = $value;
	}

	public function getDeliveryCompany()
	{
		return $this->delivery_company;
	}
	public function setDeliveryAddress($value)
	{
		$this->delivery_address = $value;
	}

	public function getDeliveryAddress()
	{
		return $this->delivery_address;
	}

	public function setDeliveryAddress1($value)
	{
		$this->delivery_address_1 = $value;
	}

	public function getDeliveryAddress1()
	{
		return $this->delivery_address_1;
	}


	public function setDeliveryAddress2($value)
	{
		$this->delivery_address_2 = $value;
	}

	public function getDeliveryAddress2()
	{
		return $this->delivery_address_2;
	}

	public function setDeliveryAddress3($value)
	{
		$this->delivery_address_3 = $value;
	}

	public function getDeliveryAddress3()
	{
		return $this->delivery_address_3;
	}

	public function setDeliveryPostcode($value)
	{
		$this->delivery_postcode = $value;
	}

	public function getDeliveryPostcode()
	{
		return $this->delivery_postcode;
	}

	public function setDeliveryCity($value)
	{
		$this->delivery_city = $value;
	}

	public function getDeliveryCity()
	{
		return $this->delivery_city;
	}

	public function setDeliveryState($value)
	{
		$this->delivery_state = $value;
	}

	public function getDeliveryState()
	{
		return $this->delivery_state;
	}

	public function setDeliveryCountryId($value)
	{
		$this->delivery_country_id = $value;
	}

	public function getDeliveryCountryId()
	{
		return $this->delivery_country_id;
	}

	public function setLineNo($value)
	{
		$this->line_no = $value;
	}

	public function getLineNo()
	{
		return $this->line_no;
	}

	public function setSku($value)
	{
		$this->sku = $value;
	}

	public function getSku()
	{
		return $this->sku;
	}

	public function setProductName($value)
	{
		$this->prod_name = $value;
	}

	public function getProductName()
	{
		return $this->prod_name;
	}

	public function setSubCatId($value)
	{
		$this->sub_cat_id = $value;
	}

	public function getSubCatId()
	{
		return $this->sub_cat_id;
	}

	public function setQty($value)
	{
		$this->qty = $value;
	}

	public function getQty()
	{
		return $this->qty;
	}

	public function setCurrencyId($value)
	{
		$this->currency_id = $value;
	}

	public function getCurrencyId()
	{
		return $this->currency_id;
	}

	public function setUnitPrice($value)
	{
		$this->unit_price = $value;
	}

	public function getUnitPrice()
	{
		return $this->unit_price;
	}

	public function setWeight($value)
	{
		$this->weight = $value;
	}

	public function getWeight()
	{
		return $this->weight;
	}

	public function setDeclaredValue($value)
	{
		$this->declared_value = $value;
		return $this;
	}

	public function getDeclaredValue()
	{
		return $this->declared_value;
	}

	public function setDeliveryCharge($value)
	{
		$this->delivery_charge = $value;
	}

	public function getDeliveryCharge()
	{
		return $this->delivery_charge;
	}

	public function setAmount($value)
	{
		$this->amount = $value;
	}

	public function getAmount()
	{
		return $this->amount;
	}

	public function setOldDeclaredValue($value)
	{
		$this->old_declared_value = $value;
	}

	public function getOldDeclaredValue()
	{
		return $this->old_declared_value;
	}

	public function getDeclaredDesc()
	{
		return $this->declaredDesc;
	}

	public function setDeclaredDesc($value)
	{
		$this->declaredDesc = $value;
	}

	public function getCcDesc()
	{
		return $this->cc_desc;
	}

	public function setCcDesc($value)
	{
		$this->cc_desc = $value;
	}

	public function getCcCode()
	{
		return $this->cc_code;
	}

	public function setCcCode($value)
	{
		$this->cc_code = $value;
	}

	public function getCourierId()
	{
		return $this->courier_id;
	}

	public function setCourierId($value)
	{
		$this->courier_id = $value;
	}

	public function getInterfaceTrackingNo()
	{
		return $this->interface_tracking_no;
	}

	public function setInterfaceTrackingNo($value)
	{
		$this->interface_tracking_no = $value;
	}

	public function setActualCost($value)
	{
		$this->actual_cost = $value;
	}

	public function getActualCost()
	{
		return $this->actual_cost;
	}

	public function getOfflineFee()
	{
		return $this->offline_fee;
	}

	public function setOfflineFee($value)
	{
		$this->offline_fee = $value;
	}

	public function getItemNo()
	{
		return $this->item_no;
	}

	public function setItemNo($value)
	{
		$this->item_no = $value;
	}

}
