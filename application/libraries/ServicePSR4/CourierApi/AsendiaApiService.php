<?php 

namespace ESG\Panther\Service\CourierAPi;

use ESG\Panther\Dao\InterfaceCourierManifestDao;

class AsendiaApiService extends CourierAPiService implements CourierApiInterface 
{
	private $courierReturnData=null;
	private $courierRequestData=null;
	private $autoIncrementNum;
	private $formValue;

	public function __construct($formValue)
	{
        $this->formValue =$formValue;
        parent::__construct($formValue);
        $this->_courierManifestDao =new InterfaceCourierManifestDao();
    }

	public function getCourierApiUrl($request)
	{
		$courierApiDomain="http://ishipper.asendiahk.com/openapi/user/1.2/";
		$courierApiUrl=array(
				"addOrder"	=>"addOrUpdateOrder",
				"printOrder"	=>"printOrder",
				"deleteOrder"	=>"deleteOrder",
				"getOrderTrackingNo"	=>"getOrderTrackingNo",
				"addManifest" =>"addManifest",
				"delManifest"	=>"delManifest"
			);
		return $courierApiDomain.$courierApiUrl[$request];
	}

	public function getCourierApiHeader()
	{
		$courierApiHeader=array(
			"headers"=>array(
				'Content-Type' =>'application/hal+json',
			),
		);
		return $courierApiHeader;
	}

	public function getCourierId()
	{
		return "Asendia";
	}

	public function getAsendiaPpi()
	{
		return "";
	}

	public function getApiToken()
	{
		return "b19296510fc60bc8ca8db06994ad6136";
	}

	/******************************************
	**  function getCourierId
	**  this will return courier id
	********************************************/

	public function getCourierDataType(){
		return "json";
	}
	/******************************************
	**  function getCourierDataType
	**  this will return courier POST type:xml, json
	********************************************/

	 public function getCourierRequestData($action,$requestData){
	 	switch ($action) {
	 		case 'addOrder':
	 			$courierRequestData=$this->getAddOrderActionData($requestData);
	 			break;
	 		case 'printOrder':
	 			$courierRequestData=$this->getPrintOrderActionData($requestData);
	 			break;
	 		case 'deleteOrder':
	 			$courierRequestData=$this->getDeleteOrderActionData($requestData);
	 			break;
	 		case 'getOrderTrackingNo':
	 			$courierRequestData=$this->_getOrderTrackingNoActionData($requestData);
	 			break;
	 		case 'addManifest':
	 			$courierRequestData=$this->getAddManifestActionData($requestData);
	 			break;
	 	}
    	if(!empty($courierRequestData)){
		    return json_encode($courierRequestData);;
		}
    }
	/******************************************
	**  function getCourierRequestData
	**  return courier post data to add order
	********************************************/

    public function getAddOrderActionData($interfacePendingCourier){

    	if($interfacePendingCourier){
    		foreach($interfacePendingCourier as $pendingData){
    			$courierRequestData=array("ApiToken"=>$this->getApiToken());
				$courierRequestData["OrderList"][] = array (
			  		"OrderNumber" => $pendingData->getSoNo(), 
					"ServiceType" => $pendingData->getServiceType(),
					//渠道代码 REG,PROAM,ECONAM,AU
					"Consignee" =>$pendingData->getDeliveryName(), //收件人名
					"Address1" => $pendingData->getDeliveryAddress1(),//地址第一行
					"Address2" => $pendingData->getDeliveryAddress2(), //地址第二行
					"Address3" =>$pendingData->getDeliveryAddress3(),//地址第三行
					"City" => $pendingData->getDeliveryCity(), //城市
					"State" => $pendingData->getDeliveryState(), //州
					"CountryCode" => $pendingData->getDeliveryCountryId(), // 国家简码
					"ConsigneePhone" => $pendingData->getDeliveryPhone(), //电话
					"Zip" => $pendingData->getDeliveryPostcode(), //邮编
					"Email" => $pendingData->getEmail(), //电子邮箱
					"Description" => $pendingData->getDeclaredDesc(), //申报内容
					"Value" => $pendingData->getDeclaredValue(), // 申报价值
					"CustomsType" => $pendingData->getDeclaredType(), //申报类型
					"Weight" => "0.5", //包裹实际重量
					"BoxId" => "", //专线时需要填写
					"Height" => "10", // 包裹尺寸 高度（CM）专线时需要填写
					"Width" => "10", // 包裹尺寸 宽度（CM）专线时需要填写
					"Length" => "10" // 包裹尺寸 长度（CM）专线时需要填写
				);
			}
			return $courierRequestData;
    	}
    }
	
	public function getPrintOrderActionData($interfaceCourierDtoArr)
	{	
		if($interfaceCourierDtoArr){
			$courierRequestData=array(
				"ApiToken"=>$this->getApiToken(),
				"LabelFormat"=>"Label_100x100",  //打印格式
				"OutPutFormat"=>"pdf",  //输出方式 pdf/html
			);
			foreach($interfaceCourierDtoArr as $interfaceCourierDto){
				$courierRequestData["OrderList"][]=array("OrderId" =>$interfaceCourierDto->getCourierOrderId());
			}
		}
    	return $courierRequestData;
	}

	public function getDeleteOrderActionData($orderId)
	{
		$courierRequestData["OrderId"] = $orderId;
    	return $courierRequestData;
	}

	public function _getOrderTrackingNoActionData($orderId)
	{
		$courierRequestData= array (
			"OrderId" => $orderId,
			"SalesOrderId"=> "SalesOrderId"
    	);
    	return $courierRequestData;
	}

	public function getAddManifestActionData($courierBatchOrderDtoArr)
	{
		if($courierBatchOrderDtoArr){
			$courierRequestData=array(
				"ApiToken"=>$this->getApiToken(),
			);
			$maxIncrementNum=$this->_courierManifestDao->getMaxIncrementNum();
			if($maxIncrementNum) { 
				$this->setAutoIncrementNum($maxIncrementNum); 
			}
			$i = $this->getAutoIncrementNum() ? $this->getAutoIncrementNum() : 0;
			$totalOrderNum=count((array)$courierBatchOrderDtoArr);
			foreach($courierBatchOrderDtoArr as $key=> $courierBatchOrderDto){
				$courierRequestData["Parcels"][]=array(
					"TrackingNo"=>$courierBatchOrderDto->getTrackingNo(), // 跟踪号码
					"OrderNumber"=>$courierBatchOrderDto->getSoNo(), //客户订单编号（跟踪号码有的话此项不需填写）
					"Weight"=>$courierBatchOrderDto->getWeight(),
				);
				$counturyCode[]=$courierBatchOrderDto->getDeliveryCountryId();
			}
			$bagCounturyCode=$totalOrderNum > 1 ? "MIX": $counturyCode[0];
			$i++;
			$num= sprintf("%'03d", $i);
			$bagNo=$this->getAsendiaPpi().$bagCounturyCode.date("Ymd").$num;
			$courierRequestData["Bags"][]=array(
				"BagNo" =>$bagNo,
				"CountryCode"=>$bagCounturyCode, 
				"Weight"=>$totalOrderNum * 5, 
				"Qty"=>$totalOrderNum, 
				"ServiceType"=>"HYBRID",
			);
		}
		$this->setAutoIncrementNum($i);
		return $courierRequestData;
	}
	
	public function getCourierReturnData($action,$courierReturnContent)
	{	
		$courierOrderAction=array("addOrder","printOrder","deleteOrder","getOrderTrackingNo");
		$courierManifestAction=array("addManifest");
		if(in_array($action, $courierOrderAction))
		{
			$courierReturnContent= json_decode($courierReturnContent);
			if($courierReturnContent->Status=="success"){
				foreach($courierReturnContent->Result as $courierOrder){
						$this->courierOrderDto = new  \CourierOrderDto();
						$this->courierOrderDto->setCourierOrderId($courierOrder->OrderId);
						$this->courierOrderDto->setCourierId($this->getCourierId());
						if($courierOrder->Status)
						$this->courierOrderDto->setCourierOrderStatus($courierOrder->Status);
						if($courierOrder->TrackingNo)
						$this->courierOrderDto->setTrackingNo($courierOrder->TrackingNo);
						if($courierOrder->TrackingNo)
						$this->courierOrderDto->setErrorMessage($courierOrder->Error); 
						if($courierOrder->RefId)
					 	$this->courierOrderDto->setRealTrackingNo($courierOrder->RefId);
					 	$courierReturnData[]=$this->courierOrderDto;
					}
				return   $courierReturnData;
			}else{
				return null;
			}
		}else if(in_array($action, $courierManifestAction))
		{
			foreach($this->formValue as $batchId){
				$courierManifestdDto=new \CourierManifestDto();
				$courierManifestdDto->setCourierBatchId($batchId);
				$courierManifestdDto->setManifestId($courierReturnContent['result']->ManifestId);
				$courierManifestdDto->setMaxIncrementNum($this->getAutoIncrementNum());
				$courierManifestdDto->setBags(serialize($courierReturnContent['requestData']->Bags));
				if($return_result->ErrorMessage)
				$courierManifestdDto->setErrorMessage(serialize($courierReturnContent['result']->ErrorMessage));
				$courierManifestdDto->setStatus($courierReturnContent['result']->Status);
				$courierReturnData[]=$courierManifestdDto;
			}
			return   $courierReturnData;
		}
	}

	public function getAutoIncrementNum()
	{
		return $this->autoIncrementNum;
	}

	public function setAutoIncrementNum($Value)
	{
		$this->autoIncrementNum=$Value;
	}
 
} 