<?php 
namespace ESG\Panther\Service\CourierAPi;

use ESG\Panther\Dao\InterfacePendingCourierDao;
use ESG\Panther\Dao\InterfaceCourierOrderDao;
use ESG\Panther\Dao\InterfaceCourierManifestDao;
use ESG\Panther\Dao\BatchDao;

class CourierFactoryService
{

	private $batchId;
	private $courierId;
	private $formValue;
    protected $courierApiInterface;
    protected $_pengdingCourierDao;
    protected $_courierOrderDao;
    protected $_courierManifestDao;

    /**
     * New Courier。
     *
     * @param  CourierApiInterface  $courierApiInterface
     * @return void
     */
    public function __construct(CourierApiInterface $courierApiInterface,$courierId,$formValue)
    {
        $this->courierApiInterface = $courierApiInterface;
        $this->courierId=$courierId;
        $this->formValue=$formValue;
        $this->batchDao=new BatchDao();
        $this->_pengdingCourierDao=new  InterfacePendingCourierDao() ;
        $this->_courierOrderDao=new InterfaceCourierOrderDao();
        $this->_courierManifestDao=new InterfaceCourierManifestDao();
    }

	public function addCourierOrder()
	{	
		$pendingCourierDtoArr=$this->courierApiInterface->getPendingCourierData();
		$updateSoNo=array();
		if($pendingCourierDtoArr){
			if($this->courierApiInterface->getCurrentBatchId()==null){
				$remark="courier_".$this->courierId."_panther".date("YmdHis");
				//$remark="courier_Asendia_valuebasket20160420051108";
				$batchObj=$this->getNewBatchObject("courier_panther", $remark);
				$this->batchId=$batchObj->getId();
				foreach($pendingCourierDtoArr as $pendingCourierDto){
					$pendingCourierDto->setBatchId($this->batchId);
					$result=$this->createInterfacePendingCourier($pendingCourierDto);
				}
			}else{
				//update interface pengding courier data
				$this->batchId=$this->courierApiInterface->getCurrentBatchId();
				foreach($pendingCourierDtoArr as $pendingCourierDto){
					$pendingCourierDto->setBatchId($this->batch_id);
					$result=$this->updateInterfacePendingCourier($pendingCourierDto,$this->batchId);
					$updateSoNo[]=$pendingCourierDto->getSoNo();
				}
			}
		}
		if($updateSoNo){ $option["so_no"]=$updateSoNo; }
		$where=array("batch_id"=>$this->batchId,"courier_id"=>$this->courierId);
		$interfacePendingCourier=$this->_pengdingCourierDao->getConfirmPendingOrder($where,$option);
		if($interfacePendingCourier){
			$reuslt["batchId"]=$this->batchId;
			$reuslt["message"]=$this->runCourierApi("addOrder",$interfacePendingCourier);
			return $reuslt;
		}else{
			return null;
		}
	}

	public function applyCourierTracking()
	{
		$batchIds=join(',',$this->formValue);  
		$where["batch_id in($batchIds)"]=null;
		$where["status"]=1;
		$where["courier_order_status"]="success";
		$option["limit"]=-1;
		$option["orderby"]=" CAST(courier_order_id AS DECIMAL) desc";
		$courierOrderArr=$this->_courierOrderDao->getList($where,$option);
		if($courierOrderArr){
			$reuslt["message"]=$this->runCourierApi("applyTracking",$courierOrderArr);
			return $reuslt;
		}else{
			return null;
		}
	}

	public function printCourierOrder()
	{
		$action="printOrder";
		$batchIds=join(',',$this->formValue);  
		$where["batch_id in($batchIds)"]=null;
		$where["status"]=1;
		//$where["courier_order_status"]="success";
		$option["limit"]=-1;
		$option["orderby"]=" CAST(courier_order_id AS DECIMAL) desc";
		$courierOrderArr=$this->_courierOrderDao->getList($where,$option);

		$requestUrl=$this->courierApiInterface->getCourierApiUrl($action);
		$courierRequestData=$this->courierApiInterface->getCourierRequestData($action,$courierOrderArr);
		$this->saveDataToFile($courierRequestData,$action,$this->courierApiInterface);
		$courierReturnContent=$this->curlPostDataToApi($requestUrl,$courierRequestData);
		if($courierReturnContent){
			//update print nums and last print time
			$this->updateInterfaceCourierOrderPrintNums();
			$filename=$this->courierApiInterface->getCourierId()."-courier-order.pdf";
			header("Content-type: application/octet-stream");
				header("Content-disposition: attachment;filename=".$filename);
			echo $courierReturnContent; 
			exit();
		}
	}

	//use soft delete function
	public function deleteCourierOrder()
	{	
		$orderIds=join(',',$this->formValue);  
		$where["courier_order_id in($orderIds)"]=null;
		$where["status"]=1;
		//$where["courier_order_status"]="success";
		$courierOrderArr=$this->_courierOrderDao->getList($where,$option);
		if($courierOrderArr){
			$reuslt["message"]=$this->runCourierApi("deleteOrder",$courierOrderArr);
			return $reuslt;
		}else{
			return null;
		}
	}

    public function addCourierManifest()
    {    
		$batchIds=join(',',$this->formValue);  
		$where["ipc.batch_id in($batchIds)"]=null;
		$where["ic.status"]=1;
		//$where["ic.courier_order_status"]="success";
		$courierBatchOrderDtoArr=$this->_pengdingCourierDao->getCourierOrderByBatch($where, array("limit"=>-1));
		if($courierBatchOrderDtoArr){
			$reuslt["message"]=$this->runCourierApi("addManifest",$courierBatchOrderDtoArr);
			return $reuslt;
		}else{
			return null;
		}
    }

	public function findCourierOrder()
	{
		$action="findOrder";
		$requestData["OrderId"]="1023750";
		$requestUrl=$this->courierApiInterface->getCourierApiUrl($action);
		$courierRequestData=$this->courierApiInterface->getCourierRequestData($action,$requestData);
		//$this->saveDataToFile($courierRequestData,$action,$this->courierApiInterface);
		$courierReturnContent=$this->curlPostDataToApi($requestUrl,$courierRequestData);
		if($courierReturnContent){
			print_r($courierReturnContent);exit();
		}
	}

	public function getCourierTrackingNo()
	{
		$batchIds= is_array($this->formValue) ? join(',',$this->formValue) :$this->formValue;
		$where["batch_id"]=$batchIds;
		$where["status"]=1;
		$option["limit"]=-1;
		$courierOrderArr=$this->_courierOrderDao->getList($where,$option);
		if($courierOrderArr){
			$reuslt["message"]=$this->runCourierApi("getOrderTrackingNo",$courierOrderArr);
			return $reuslt;
		}else{
			return null;
		}
	}

	public function getAllShipway()
	{
		$requestUrl=$this->courierApiInterface->getCourierApiUrl("getAllShipway");
		$courierReturnContent=$this->curlPostDataToApi($requestUrl,$courierRequestData);
		print_r($courierReturnContent);exit();
	}

	public function courierCallback()
	{
		$callbackData=$this->courierApiInterface->callbackAcionData();
		//response to courier
		$responeData=$this->courierApiInterface->responseActionData();
		curlPostDataToApi($requestUrl,$courierRequestData);
	}
	
	public function getNewBatchObject($funcName,$remark)
	{
		$batchObj = $this->batchDao->get(array("remark"=>$remark));
        if (empty($batchObj)){
            $batchObj = $this->batchDao->get();
            $batchObj->setFuncName($funcName);
            $batchObj->setStatus("N");
            $batchObj->setListed("1");
            $batchObj->setRemark($remark);
            $this->batchDao->insert($batchObj);
        } 
        return $batchObj;
	}

	public function runCourierApi($action,$requestData)
	{
		$requestUrl=$this->courierApiInterface->getCourierApiUrl($action);
		if(empty($requestUrl)) 
			return "No this action, please contact the system manager.";
		$courierRequestData=$this->courierApiInterface->getCourierRequestData($action,$requestData);
		$this->saveDataToFile($courierRequestData,$action,$this->courierApiInterface);
		$courierReturnContent=$this->curlPostDataToApi($requestUrl,$courierRequestData);
		$this->saveDataToFile($courierReturnContent,"return",$this->courierApiInterface);
		if($action=="addManifest"){
			$courierReturnContent=array(
				"result" =>json_decode($courierReturnContent),
				"requestData" =>json_decode($courierRequestData),
			);
		}
		$courierReturnDataArr=$this->courierApiInterface->getCourierReturnData($action,$courierReturnContent);
		if($courierReturnDataArr){
			foreach ($courierReturnDataArr as $courierReturnData) {
				if($action=="addOrder"){
					$this->createInterfaceCourierOrder($courierReturnData);
				}else if($action=="deleteOrder"){
					$this->cancelInterfaceCourierOrder($courierReturnData);
				}else if($action=="addManifest"){
					$this->createInterfaceCourierManifest($courierReturnData);
				}else{
					$this->updateInterfaceCourierOrder($courierReturnData);
				}
				if($action=="addManifest"){
					$message.="Batch Id ".$courierReturnData->getCourierBatchId()." ".$action." ".$courierReturnData->getStatus() ."<br>";
				}else{
					$message.="Order ".$courierReturnData->getCourierOrderId()." ".$action." ".$courierReturnData->getCourierOrderStatus() ."<br>";
				}
			}
			return $message;
		}
	}

	public function curlPostDataToApi($requestUrl,$pendingCourierData)
	{
		$courierApiHeader=$this->courierApiInterface->getCourierApiHeader();
		$client = new \GuzzleHttp\Client($courierApiHeader);
		$response=$client->request('POST', $requestUrl, ['body' => $pendingCourierData]);
		$courierReturnContent=$response->getBody()->getContents();
		return $courierReturnContent;
	}

    public function saveDataToFile($data,$fileName,$courierApiInterface)
    {
    	$filePath=APPPATH."/data/courier/".$courierApiInterface->getCourierId()."/". $fileName ."/" . date("Y"). "/" .date("m");
    	if (!file_exists($filePath)) { mkdir($filePath, 0755, true);}
    	$file=$filePath. "/" .date("Y-m-d-H-i").".".$courierApiInterface->getCourierDataType();
    	//write json data into data.json file
	   if(file_put_contents($file, $data)) {
	       //echo 'Data successfully saved';
		   return $data;
	   	}
	   	return false;
    }

    function createInterfacePendingCourier($pendingCourierDto)
    {	
    	$object = $this->_pengdingCourierDao->get();
		$object->setBatchId($pendingCourierDto->getBatchId());
    	$object->setSoNo($pendingCourierDto->getSoNo());
    	$object->setShNo($pendingCourierDto->getShNo());
    	$object->setCourierId($pendingCourierDto->getCourierId());
    	$object->setServiceType($pendingCourierDto->getServiceType());
    	$object->setWeight($pendingCourierDto->getWeight());
    	$object->setDeliveryName($pendingCourierDto->getDeliveryName());
    	$object->setDeliveryCompany($pendingCourierDto->getDeliveryCompany());
    	$object->setDeliveryAddress1($pendingCourierDto->getDeliveryAddress1());
    	$object->setDeliveryAddress2($pendingCourierDto->getDeliveryAddress2());
    	$object->setDeliveryAddress3($pendingCourierDto->getDeliveryAddress3());
    	$object->setDeliveryPostcode($pendingCourierDto->getDeliveryPostcode());
    	$object->setDeliveryCity($pendingCourierDto->getDeliveryCity());
    	$object->setDeliveryState($pendingCourierDto->getDeliveryState());
    	$object->setDeliveryCountryId($pendingCourierDto->getDeliveryCountryId());
    	$object->setDeliveryPhone($pendingCourierDto->getDeliveryPhone());
    	$object->setEmail($pendingCourierDto->getEmail());
    	$object->setDeclaredDesc($pendingCourierDto->getDeclaredDesc());
    	$object->setDeclaredValue($pendingCourierDto->getDeclaredValue());
    	$object->setDeclaredType($pendingCourierDto->getDeclaredType());
    	$object->setDeclaredCurrency($pendingCourierDto->getDeclaredCurrency());
    	$object->setMasterSku($pendingCourierDto->getMasterSku());

    	$object->setCreateOn(date("Y-m-d H:i:s")); 
    	$result=$this->_pengdingCourierDao->insert($object);
    }

    function updateInterfacePendingCourier($pendingCourierDto,$batchId)
    {
    	$where["batch_id"]=$batchId;
    	$where["so_no"]=$pendingCourierDto->getSoNo();
    	$oldObject = $this->_pengdingCourierDao->get($where);
    	if($oldObject){
    		$oldObject->setDeliveryName($pendingCourierDto->getDeliveryName());
	    	$oldObject->setDeliveryCompany($pendingCourierDto->getDeliveryCompany());
	    	$oldObject->setDeliveryAddress1($pendingCourierDto->getDeliveryAddress1());
	    	$oldObject->setDeliveryAddress2($pendingCourierDto->getDeliveryAddress2());
	    	$oldObject->setDeliveryAddress3($pendingCourierDto->getDeliveryAddress3());
	    	$oldObject->setDeliveryPostcode($pendingCourierDto->getDeliveryPostcode());
	    	$oldObject->setDeliveryCity($pendingCourierDto->getDeliveryCity());
	    	$oldObject->setDeliveryState($pendingCourierDto->getDeliveryState());
	    	$oldObject->setDeliveryCountryId($pendingCourierDto->getDeliveryCountryId());
	    	$oldObject->setDeliveryPhone($pendingCourierDto->getDeliveryPhone());
	    	$oldObject->setEmail($pendingCourierDto->getEmail());
	    	$oldObject->setDeclaredValue($pendingCourierDto->getDeclaredValue());

	    	$result=$this->_pengdingCourierDao->update($oldObject);
    	}
    }

    function createInterfaceCourierOrder($courierOrderDto)
    {
    	$where["batch_id"]=$this->batchId;
		$where["courier_order_id"]=$courierOrderDto->getCourierOrderId();
		$where["status"]="1";
		$oldObject = $this->_courierOrderDao->get($where);
    	if($oldObject){
    		$oldObject->setStatus("0");
    		$this->_courierOrderDao->update($oldObject);
    	}
    	//create active courier order
    	$object = $this->_courierOrderDao->get();
    	$object->setBatchId($this->batchId);
    	$object->setCourierOrderId($courierOrderDto->getCourierOrderId());
    	$object->setCourierId($courierOrderDto->getCourierId());
    	$object->setCourierOrderStatus($courierOrderDto->getCourierOrderStatus());
    	$object->setCourierParcelId($courierOrderDto->getCourierParcelId());
    	$object->setTrackingNo($courierOrderDto->getTrackingNo());
    	$object->setErrorMessage($courierOrderDto->getErrorMessage());
    	$object->setStatus("1");
    	$object->setCreateOn(date("Y-m-d H:i:s"));
    	$result=$this->_courierOrderDao->insert($object);

    	//$this->_courierOrderDao->db->trans_commit();
    }

    function updateInterfaceCourierOrder($courierOrderDto)
    {
    	//update inactive courier old status.
		$where["courier_order_id"]=$courierOrderDto->getCourierOrderId();
		$where["status"]="1";
		//$where["courier_order_status"]="success";
		$oldObject = $this->_courierOrderDao->get($where);
    	if($oldObject){
    		if($oldObject->getRealTrackingNo()==null && $courierOrderDto->getRealTrackingNo()!=null){
    			$oldObject->setRealTrackingNo($courierOrderDto->getRealTrackingNo());
    		}
  	
    		if($oldObject->getTrackingNo()==null && $courierOrderDto->getTrackingNo()!=null){
    			$oldObject->setTrackingNo($courierOrderDto->getTrackingNo());	
    		}

    		$oldObject->setCourierOrderStatus("success");
    		$this->_courierOrderDao->update($oldObject);
    	}
    }

    public function cancelInterfaceCourierOrder($courierOrderDto)
    {
    	//update inactive courier old status.
		$where["courier_order_id"]=$courierOrderDto->getCourierOrderId();
		$where["status"]="1";
		$where["courier_order_status"]="success";
		$oldObject = $this->_courierOrderDao->get($where);
    	if($oldObject){
    		$oldObject->setStatus("2");
    		$this->_courierOrderDao->update($oldObject);
    	}
    }

    public function updateInterfaceCourierOrderPrintNums()
    {
		$batchIds=join(',',$this->formValue);  
		$where["batch_id in($batchIds)"]=null;
		$where["status"]=1;
		$where["courier_order_status"]="success";
		$option["limit"]=-1;
		$oldObjectArr= $this->_courierOrderDao->getList($where,$option);
    	if($oldObjectArr){
    		foreach($oldObjectArr as $oldObject){
    			$printNums=$oldObject->getPrintNums()+1;
    			$oldObject->setPrintNums($printNums);
    			$oldObject->setLastPrintOn(date("Y-m-d H:i:s"));
    			$this->_courierOrderDao->update($oldObject);
    		}
    	}
	}

    function  createInterfaceCourierManifest($courierManifestDto)
    {
    	$where["courier_batch_id"]=$courierManifestDto->getCourierBatchId();
		$oldObject = $this->_courierManifestDao->get($where);
    	if($oldObject){
    		if(!$oldObject->getManifestId()){
    			$oldObject->setManifestId($courierManifestDto->getManifestId());
	    		$oldObject->setMaxIncrementNum($courierManifestDto->getMaxIncrementNum());
	    		$oldObject->setErrorMessage($courierManifestDto->getErrorMessage());
	    		$oldObject->setStatus($courierManifestDto->getStatus());
	    		$oldObject->setBags($courierManifestDto->getBags());
    			$this->_courierManifestDao->update($oldObject);
    		}
    	}else{
	    	$object = $this->_courierManifestDao->get();
	    	$object->setCourierBatchId($courierManifestDto->getCourierBatchId());
	    	$object->setManifestId($courierManifestDto->getManifestId());
	    	$object->setMaxIncrementNum($courierManifestDto->getMaxIncrementNum());
	    	$object->setErrorMessage($courierManifestDto->getErrorMessage());
	    	$object->setStatus($courierManifestDto->getStatus());
	    	$object->setBags($courierManifestDto->getBags());
	    	$object->setCreateOn(date("Y-m-d H:i:s"));
	    	$result=$this->_courierManifestDao->insert($object);

	    	//$this->_courierManifestDao->db->trans_commit();
    	}
    }

}