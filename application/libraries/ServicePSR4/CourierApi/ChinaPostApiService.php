<?php 

namespace ESG\Panther\Service\CourierAPi;

use Illuminate\Http\Request;
use Esgwe\Src\XmlArray;

class ChinaPostApiService implements CourierApiInterface 
{
	private $courierReturnData=null;
	private $courierRequestData=null;
	private $autoIncrementNum;
	private $formValue;
	private $courier_id;

	public function __construct(Request $request){

        $this->formValue = $request->get('formValue');
        $this->courier_id=$this->getCourierId();
    }

	public function getCourierApiUrl($request){

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

	public function getCourierApiHeader(){

		$courierApiHeader=array(
			"headers"=>array(
				"APIToken"=>"c3f168b35299cdff2d339009a0d6237e", 
				"version"=>"1.0",
				'Content-Type' =>'text/xml; charset=UTF8',
			),
		);
		return $courierApiHeader;
	}

	public function getCourierId(){

		return \DB::table('courier')
					->where("api_class","ChinaPostApiService")
					->first()
					->id;
	}

	/******************************************
	**  function getCourierId
	**  this will return courier id
	********************************************/

	public function getCourierDataType(){
		return "xml";
	}
	/******************************************
	**  function getCourierDataType
	**  this will return courier POST type:xml, json
	********************************************/

	 public function getCourierRequestData($action,$requestData){
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
		    return $this->array2Xml($courierRequestData,$xml);
		}
    }
	/******************************************
	**  function getCourierRequestData
	**  return courier post data to add order
	********************************************/

    public function getAddOrderActionData($interfacePendingCourier){

    	if($interfacePendingCourier){
    		foreach($interfacePendingCourier as $pendingData){
    				$courierRequestData["Order"] = array (
				  		"SellerAccountName"=> "default",
				  		"OrderId"=>$pendingData->so_no,
				  		//"SalesOrderId"=>"1231",
				  		//"BuyerId"=>"123",
				  		"ReceiverName"=>$pendingData->delivery_name,
				  		"AddressLine1"=>$pendingData->delivery_address_1,
				  		"AddressLine2"=>$pendingData->delivery_address_2,
				  		"Country"=>$pendingData->delivery_country_id,
				  		"State"=>$pendingData->delivery_state,
				  		"City"=>$pendingData->delivery_city,
				  		"PostCode"=>$pendingData->delivery_postcode,
				  		"PhoneNumber"=>$pendingData->delivery_phone,
				  		"Email"=>$pendingData->email,
				  		"ShipWayCode"=>"002",
				  		//"TrackingNo"=>"",
					);
					$courierRequestData["Order"]["OrderItems"] = array(
				  			"OrderItem" =>array(
				  				"Quantity" =>"1",
				  				"Sku" => $pendingData->master_sku,
				  				"Title" => "test",
				  			)
				  		);
					$courierRequestData["Order"]["OrderCustoms"] = array(
				  			"Currency" =>$pendingData->declared_currency,
				  			"CustomsType" =>"礼物",
				  			"OrderCustom" => array(
				  				"Quantity"=>'1',
				  				"DescriptionEn"=>$pendingData->declared_desc_en,
				  				"DescriptionCn"=>$pendingData->declared_desc_cn,
				  				"Weight"=>$pendingData->weight,
				  				"Value"=>$pendingData->declared_value,
				  			)
				  		);
					return $courierRequestData;
			}
    	}
    }
	/******************************************
	**  function getPendingCourierData
	**  return PendingCourierData Object
	********************************************/
   
	public function getApplyTrackingActionData($orderId){
		$courierRequestData["Order"] = array (
			"OrderId" => $orderId,
			"EubPrintProductFormat"=> "itemtitle"
    	);
    	return $courierRequestData;
	}

	public function getPrintOrderActionData($orderId){
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
    	$courierRequestData["Orders"] = array(
			"Order"=>array(
					"OrderId"=>$orderId
				)
    	);
    	return $courierRequestData;
	}

	public function getDeleteOrderActionData($orderId){
	
		$courierRequestData["OrderId"] = $orderId;
    	return $courierRequestData;
	}

	public function getFindOrderActionData($requestData){
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

	public function _getOrderTrackingNoActionData($orderId){
		$courierRequestData= array (
			"OrderId" => $orderId,
			"SalesOrderId"=> "SalesOrderId"
    	);
    	return $courierRequestData;
	}

	public function _getAllWarehouseActionData(){

    	
	}

	public function _getOrderStatusActionData(){
		
	}

	public function callbackAcionData(){
		$callbackDataXml = file_get_contents("php://input");
		$callbackData = simplexml_load_string($callbackDataXml, 'SimpleXMLElement', LIBXML_NOCDATA);
    	return $callbackData->Orders;
	}


	public function getCourierReturnData($action,$courierReturnContent){
		// 把 xml 数据转换成 PHP 的对象
		$singleOrderArray=array("applyTracking","printOrder","deleteOrder");
		$courierReturnContent=strtolower($courierReturnContent);
		$courierReturnDataArr="";
		$courierReturnObj = simplexml_load_string($courierReturnContent, 'SimpleXMLElement', LIBXML_NOCDATA);
		foreach ($courierReturnObj->children() as $returnData) {
			if(in_array($action, $singleOrderArray)){
				$courierReturnDataArr[]=$this->hydrateReturnData($action,$returnData);
			}else{
				foreach ($returnData->children() as $order) {
					$courierReturnDataArr[]=$this->hydrateReturnData($action,$order);
				}
			}
		}
		return $courierReturnDataArr;
	}

	/******************************************
	**  function array2Xml
	**  return  xml data
	********************************************/

	public function array2Xml($arrayRequestData,$xml){
		$array2XML = new XmlArray($arrayRequestData);
        $result = $array2XML->createXmlFromArray($xml);
        return $result;
	}
	
	public function hydrateReturnData($action,$returnData){

		$courierReturnData="";
		$courierReturnData["batch_id"]="1";
		$courierReturnData["courier_order_id"]=(string)$returnData->orderid;
		$courierReturnData["courier_id"]=$this->courier_id;
		if($action=="printOrder"){
			$courierReturnData["print_nums"]=1;
			$courierReturnData["last_print_on"]=date("Y-m-d H:i:s");
		}else if($action=="deleteOrder"){
			$courierReturnData["courier_order_status"]='D';
		}else{
			if((string)$returnData->trackingno) 
			$courierReturnData["real_tracking_no"]=(string)$returnData->trackingno;
			if((string)$returnData->parcelid)
			$courierReturnData["courier_parcel_id"]=(string)$returnData->parcelid;
			if((string)$returnData->error)
			$courierReturnData["error"]=(string)$returnData->error;
			if((string)$returnData->error_message)
			$courierReturnData["error_message"]=(string)$returnData->errormsg;
			if((string)$returnData->status)
			$courierReturnData["status"]=(string)$returnData->status;
		}
		return $courierReturnData;
	}	
	 
} 