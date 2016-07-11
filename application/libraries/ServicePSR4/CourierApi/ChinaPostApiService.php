<?php 

namespace ESG\Panther\Service\CourierApi;

use ESG\Panther\Dao\InterfaceCourierManifestDao;

class ChinaPostApiService extends CourierApiService implements CourierApiInterface 
{
	private $courierReturnData=null;
	private $courierRequestData=null;
	private $autoIncrementNum;
	private $formValue;

	public function __construct($formValue)
	{
        $this->formValue = $formValue;
        parent::__construct($formValue);
    }

	public function getCourierApiUrl($request)
	{
		$courierApiDomain="http://61.145.202.114/webservices/userapi/";
		$courierApiUrl=array(
				"addOrder"	=>"addorupdateorder",
				"applyTracking"	=>"applytracking",
				"printOrder"	=>"printorder",
				"deleteOrder"	=>"deleteorder",
				"findOrder"	=>"findorder",
				"getOrderTrackingNo"	=>"getordertrackingno",
				"getAllShipway" =>"getallshipway"
			);

		return $courierApiDomain.$courierApiUrl[$request];
	}

	public function getCourierApiHeader()
	{
		$courierApiHeader=array(
			"headers"=>array(
				"APIToken"=>"c3f168b35299cdff2d339009a0d6237e", 
				"version"=>"1.0",
				'Content-Type' =>'text/xml; charset=UTF8',
			),
		);
		return $courierApiHeader;
	}

	public function getCourierId()
	{
		return "ChinaPost";
	}

	/******************************************
	**  function getCourierId
	**  this will return courier id
	********************************************/

	public function getCourierDataType()
	{
		return "xml";
	}
	/******************************************
	**  function getCourierDataType
	**  this will return courier POST type:xml, json
	********************************************/

	 public function getCourierRequestData($action,$requestData)
	 {
	 	switch ($action) {
	 		case 'addOrder':
	 			$courierRequestData=$this->getAddOrderActionData($requestData);
	 			$xml="Orders";
	 			break;
	 		case 'applyTracking':
	 			$courierRequestData=$this->getApplyTrackingActionData($requestData);
	 			$xml="Orders";
	 			break;
	 		case 'printOrder':
	 			$courierRequestData=$this->getPrintOrderActionData($requestData);
	 			$xml="Request";
	 			break;
	 		case 'deleteOrder':
	 			$courierRequestData=$this->getDeleteOrderActionData($requestData);
	 			$xml="Orders";
	 			break;
	 		case 'findOrder':
	 			$courierRequestData=$this->getFindOrderActionData($requestData);
	 			$xml="Request";
	 			break;
	 		case 'getOrderTrackingNo':
	 			$courierRequestData=$this->_getOrderTrackingNoActionData($requestData);
	 			$xml="Request";
	 			break;
	 	}
    	if(!empty($courierRequestData)){
    		//print_r($this->array2Xml($courierRequestData,$xml));exit();
		    return $this->array2Xml($courierRequestData,$xml);
		}
    }
	/******************************************
	**  function getCourierRequestData
	**  return courier post data to add order
	********************************************/

    public function getAddOrderActionData($interfacePendingCourier)
    {
    	if($interfacePendingCourier){
    		foreach($interfacePendingCourier as $pendingData){
    				$requestData=null;
    				$requestData["Order"] = array (
				  		"SellerAccountName"=> "default",
				  		"OrderId"=>$pendingData->getSoNo(),
				  		//"SalesOrderId"=>"1231",
				  		//"BuyerId"=>"123",
				  		"ReceiverName"=>$pendingData->getDeliveryName(),
				  		"AddressLine1"=>$pendingData->getDeliveryAddress1(),
				  		"AddressLine2"=>$pendingData->getDeliveryAddress2(),
				  		"Country"=>$pendingData->getDeliveryCountryId(),
				  		"State"=>$pendingData->getDeliveryState(),
				  		"City"=>$pendingData->getDeliveryCity(),
				  		"PostCode"=>$pendingData->getDeliveryPostcode(),
				  		"PhoneNumber"=>$pendingData->getDeliveryPhone(),
				  		"Email"=>$pendingData->getEmail(),
				  		"ShipWayCode"=>$pendingData->getServiceType()
				  		//"TrackingNo"=>"",
					);
					$requestData["Order"]["OrderItems"] = array(
				  			"OrderItem" =>array(
				  				"Quantity" =>"1",
				  				"Sku" => $pendingData->getMasterSku(),
				  				"Title" => $pendingData->getDeclaredDesc(),
				  			)
				  		);
					$requestData["Order"]["OrderCustoms"] = array(
				  			"Currency" =>$pendingData->getDeclaredCurrency(),
				  			"CustomsType" =>"礼物",
				  			"OrderCustom" => array(
				  				"Quantity"=>'1',
				  				"DescriptionEn"=>$pendingData->getDeclaredDesc(),
				  				"Weight"=>$pendingData->getWeight(),
				  				"Value"=>$pendingData->getDeclaredValue(),
				  			)
				  		);
				  	$courierRequestData[]=$requestData;	
			}
			return $courierRequestData;
    	}
    }
	/******************************************
	**  function getPendingCourierData
	**  return PendingCourierData Object
	********************************************/
   
	public function getApplyTrackingActionData($interfaceCourierDtoArr)
	{
		$courierRequestData=null;
		foreach($interfaceCourierDtoArr as $interfaceCourierDto){
			$courierRequestData[]["Order"] = array(
					"OrderId" => $interfaceCourierDto->getCourierOrderId(),
					"EubPrintProductFormat"=> "itemtitle"
    		);	
		}
    	return $courierRequestData;
	}

	public function getPrintOrderActionData($interfaceCourierDtoArr)
	{
		$courierRequestData = array (
			"LableFormat" => "A4_3",
			"StartPlace"=> "1",
			"OutPutFormat"=> "pdf",
			"PrintBuyer"=> "true",
			"PrintOrderId"=> "true",
			"PrintSellerAccount" => "true",
			"PrintRemark"=> "true",
			"PrintCustoms"=> "false",
			"PrintProductImg" => "false",
			"PrintProductFormat"=> "{sku}",
			//{sku},{sku}({itemtitle}),{sku}({productname})
			"PrintProductPosition"=> "1",
    	);
    	foreach($interfaceCourierDtoArr as $interfaceCourierDto){
			$courierRequestData["Orders"][]["Order"]= array(
				"OrderId" => $interfaceCourierDto->getCourierOrderId(),
    		);	
		}
    	return $courierRequestData;
	}

	public function getDeleteOrderActionData($interfaceCourierDtoArr)
	{
		foreach($interfaceCourierDtoArr as $interfaceCourierDto){
			$courierRequestData[]["OrderId"] = $interfaceCourierDto->getCourierOrderId();
		}
    	return $courierRequestData;
	}

	public function getFindOrderActionData($requestData)
	{
		$courierRequestData= array (
			"OrderId" => $requestData["OrderId"],
			"SalesOrderId"=> $requestData["SalesOrderId"],
			"SellerAccount" => $requestData["SellerAccount"],
			"ShipWayCode" => $requestData["ShipWayCode"],
			"WarehouseId" => $requestData["WarehouseId"],
			"Status" => $requestData["Status"],
			"OnlineStatus" => $requestData["OnlineStatus"],
			"Keyword" => $requestData["Keyword"],
			"KeywordType" => $requestData["KeywordType"],
			"ImportTimeFrom" => $requestData["ImportTimeFrom"],
			"ImportTimeTo" => $requestData["ImportTimeTo"],
			"PickupTimeFrom" => $requestData["PickupTimeFrom"],
			"PickupTimeTo" => $requestData["PickupTimeTo"],
			"SubmitTimeFrom" => $requestData["SubmitTimeFrom"],
			"SubmitTimeTo" => $requestData["SubmitTimeTo"],
			"PageSize" => $requestData["PageSize"],
			"Page"=>$requestData["Page"]
    	);
    	return $courierRequestData;
	}

	public function _getOrderTrackingNoActionData($interfaceCourierDtoArr)
	{
    	foreach($interfaceCourierDtoArr as $interfaceCourierDto){
    		$courierRequestData["Order"][]=array(
    			"OrderId" =>$interfaceCourierDto->getCourierOrderId(),
    			"SalesOrderId" => "SalesOrderId"
    			);
    	}
    	return $courierRequestData;
	}

	public function _getAllWarehouseActionData()
	{
	
	}

	public function _getOrderStatusActionData()
	{
		
	}

	public function callbackAcionData()
	{
		$callbackDataXml = file_get_contents("php://input");
		$callbackData = simplexml_load_string($callbackDataXml, 'SimpleXMLElement', LIBXML_NOCDATA);
    	return $callbackData->Orders;
	}


	public function getCourierReturnData($action,$courierReturnContent)
	{
		// 把 xml 数据转换成 PHP 的对象
		$singleOrderArray=array("applyTracking","printOrder","deleteOrder");
		$courierReturnContent=strtolower($courierReturnContent);
		$courierReturnData="";
		$courierReturnObj = simplexml_load_string($courierReturnContent, 'SimpleXMLElement', LIBXML_NOCDATA);
		foreach ($courierReturnObj->children() as $returnData) {
			if(in_array($action, $singleOrderArray)){
				$courierReturnData[]=$this->hydrateReturnData($action,$returnData);
			}else{
				foreach ($returnData->children() as $courierOrder) {
					$courierReturnData[]=$this->hydrateReturnData($action,$courierOrder);
				}
			}
		}
		return $courierReturnData;
	}

	/******************************************
	**  function array2Xml
	**  return  xml data
	********************************************/

	public function array2Xml($arrayRequestData,$xml)
	{	
		include_once(BASEPATH . "plugins/XmlArray.php");
		$array2XML = new \XmlArray($arrayRequestData);
        $result = $array2XML->createXmlFromArray($xml);
        return $result;
	}
	
	public function hydrateReturnData($action,$returnData)
	{
		$this->courierOrderDto = new  \CourierOrderDto();
		$this->courierOrderDto->setCourierOrderId((string)$returnData->orderid);
		$this->courierOrderDto->setCourierId($this->getCourierId());
		if((string)$returnData->trackingno){
		$this->courierOrderDto->setRealTrackingNo((string)$returnData->trackingno);
		}
		if((string)$returnData->parcelid)
		$this->courierOrderDto->setCourierParcelId((string)$returnData->parcelid);
		if((string)$returnData->errormsg)
		$this->courierOrderDto->setErrorMessage((string)$returnData->errormsg);
		if((string)$returnData->status)
		$this->courierOrderDto->setCourierOrderStatus((string)$returnData->status);
		return $this->courierOrderDto;
	}	
	 
} 