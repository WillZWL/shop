<?php 

namespace ESG\Panther\Service\CourierAPi;

class CourierAPiService
{
	private $formValue;

	public function __construct($formValue)
	{
        $this->formValue =$formValue;
    }

    public function getPendingCourierData()
    {
		foreach($this->formValue as $courierOrder){
			$this->pendingCourierDto = new \PendingCourierDto();
            $this->pendingCourierDto->setSoNo($courierOrder["so_no"]);
          	$this->pendingCourierDto->setShNo($courierOrder["sh_no"]);
            $this->pendingCourierDto->setCourierId($courierOrder["courier_id"]);
            $this->pendingCourierDto->setServiceType($courierOrder["service_type"]);
            $this->pendingCourierDto->setDeliveryName($courierOrder["delivery_name"]);
            $this->pendingCourierDto->setDeliveryCompany($courierOrder["delivery_company"]);
            $this->pendingCourierDto->setDeliveryAddress1($courierOrder["delivery_address_1"]);
           	$this->pendingCourierDto->setDeliveryAddress2($courierOrder["delivery_address_2"]);
          	$this->pendingCourierDto->setDeliveryAddress3($courierOrder["delivery_address_3"]);
            $this->pendingCourierDto->setDeliveryCity($courierOrder["delivery_city"]);
            $this->pendingCourierDto->setDeliveryState($courierOrder["delivery_state"]);
            $this->pendingCourierDto->setDeliveryPostcode($courierOrder["delivery_postcode"]);
            $this->pendingCourierDto->setDeliveryCountryId($courierOrder["delivery_country_id"]);
            $this->pendingCourierDto->setDeliveryPhone($courierOrder["delivery_phone"]);
            $this->pendingCourierDto->setEmail($courierOrder["email"]);
            $this->pendingCourierDto->setDeclaredDesc($courierOrder["declared_desc"]);
            $this->pendingCourierDto->setDeclaredHsCode($courierOrder["declared_hs_code"]);
            $this->pendingCourierDto->setDeclaredValue($courierOrder["declared_value"]);
            $this->pendingCourierDto->setDeclaredType($courierOrder["declared_type"]);
            $this->pendingCourierDto->setDeclaredCurrency($courierOrder["declared_currency"]);
            $this->pendingCourierDto->setMasterSku($courierOrder["master_sku"]);
            $this->pendingCourierDto->setWeight("5");
            //$this->pendingCourierDto->set_box_id();
            $pendingCourierDtoArr[]=$this->pendingCourierDto; 
		}
        return   $pendingCourierDtoArr;
	}

	public function getCurrentBatchId()
	{
		foreach($this->formValue as $courierOrder){
			if($courierOrder["batch_id"]){
				return $courierOrder["batch_id"];
			}
		}
		return null;
	}

} 