<?php 

use ESG\Panther\Models\Order\CourierFactoryModel;
use ESG\Panther\Service\CourierService;
use ESG\Panther\Service\SoService;
use ESG\Panther\Service\CustomClassService;
use ESG\Panther\Service\RptCourierTrackingnoReportService;

class Courier_order extends MY_Controller
{	
	private $appId="ORD0033";
	private $_courierFactoryModel;

	public function __construct()
	{
		parent::__construct();
		$this->_courierFactoryModel= new CourierFactoryModel();
		$this->courierService=new CourierService();
		$this->soService=new SoService();
		$this->customClassService=new CustomClassService();
		$this->rptCourierTrackingnoReportService=new RptCourierTrackingnoReportService();
	}

	public function index()
	{	
		$subAppId = $this->getAppId() . "01";
		include_once(APPPATH . "language/" . $subAppId . "_" . $this->getLangId() . ".php");
        $_SESSION["LISTPAGE"] = $_SESSION["CCLISTPAGE"] = base_url() . "order/courier_order/" . ($pagetype ? "index/" . $pagetype : "") . "?" . $_SERVER['QUERY_STRING'];
        $_SESSION["CC_QSTRING"] = $_SERVER['QUERY_STRING'];

		$data["courierList"] = $this->courierService->getDao()->getList(array('show_status'=>1,"api_enable"=>1), array('limit'=>-1));
		if($this->input->post("posted") && $_POST["auto_add_order"] && $this->input->post("check"))
		{
			$courierId=$this->input->post("current_courier_id");
			$where['sosh.courier_id']=$courierId;
			$courierObj=$this->courierService->get(array("courier_id"=>$courierId));
			$checkSoNo=$this->input->post("check");
			$tempArr = $this->soService->getDao('SoShipment')->getEnableApiCourierOrderList($where, array("option"=>-1,"orderby"=>"soal.so_no,soid.amount desc"),$checkSoNo);
			$formValue=array();$currentSoNo="";
			if($tempArr){
				$formValue=$this->getPendingCourierOrderFormValue($tempArr,$courierObj);
			}
			$result=$this->_courierFactoryModel->addCourierOrder($courierId,$formValue);
			$_SESSION["NOTICE"] = $result["message"];
			redirect('/order/courier_order/get-courier-batch-order/'.$courierId.'/'.$result["batchId"]);
		}

		//display courier order
		if($this->input->get("courier_id"))
		{
			$data["currentCourierId"]=$this->input->get("courier_id");
			$sort = $this->input->get("sort");
			$order = $this->input->get("order");
			$limit = '1000';
			$pconfig['base_url'] = $_SESSION["LISTPAGE"];
			$option["limit"] = $pconfig['per_page'] = $limit;
			if ($option["limit"]){
				$option["offset"] = $this->input->get("per_page");
			}
			if (empty($sort)){ $sortstr = "soal.so_no,soid.amount desc";}else{ $sortstr = "$sort $order";}
			if (empty($order)) $order = "asc";
			$option["orderby"] = $sortstr;

			$currentCourierObj=$this->courierService->get(array("courier_id"=>$this->input->get("courier_id")));
			$serviceType=$currentCourierObj->getServiceType();
			$data["courierService"]=explode("||", $serviceType);
			$data["declaredCurrency"]=$currentCourierObj->getApiCurrency();

			if ($this->input->get("so_no")){
				$where["so.so_no"] = $this->input->get("so_no");
			}
			if ($this->input->get("email")){
				$where["c.email"] = $this->input->get("email");
			}
			if ($this->input->get("delivery_name")){
				$where["so.delivery_name LIKE "] = "%".$this->input->get("delivery_name")."%";
			}
			if ($this->input->get("delivery_postcode")){
				$where["so.delivery_postcode"] = $this->input->get("delivery_postcode");
			}
			if ($this->input->get("delivery_country_id")){
				$where["so.delivery_country_id"] = $this->input->get("delivery_country_id");
			}
			$where['sosh.courier_id']=$this->input->get("courier_id");
			$where['so.status !="6" ']=null;	
			$tempArr = $this->soService->getDao('SoShipment')->getEnableApiCourierOrderList($where, $option);
			$counter = 1;

			$currentSoNo="";$totalOrder=0; $totalItem=0;
			if($tempArr){
				foreach ($tempArr as $row){
					if($currentSoNo==$row->getSoNo()){
						$counter++;
						$itemTotal[$row->getSoNo()] =$itemTotal[$row->getSoNo()]+1;
						$row->setDeliveryCharge(0.00);
						$row->setAmount(0.00);
						$row->setActualCost(0.00);
						$itemTotal++;
					}else{
						$currentSoNo=$row->getSoNo();
						$row->setItemNo($counter);
						$row->setActualCost(number_format($row->getAmount() - $row->getOfflineFee(),2,'.',''));
						$declaredValue=$this->getDeclaredValue($row,$currentCourierObj);
						$row->setDeclaredValue($declaredValue);
						if($row->getDeclaredDesc()==null){
							$hsDetails=$this->getSubCatHsDetails($row->getSubCatId(),$row->getDeliveryCountryId());
							$row->setDeclaredDesc($hsDetails["description"]);
							$row->setDeclaredHsCode($hsDetails["code"]);
						}
						$address= @explode("|", $row->getDeliveryAddress());
						$row->setDeliveryAddress1($address[0]);
						if (!empty($address[1])){
							$row->setDeliveryAddress2($address[1]);
						}
						if (!empty($address[2])){
							$row->setDeliveryAddress3($address[2]);
						}
						$counter=1;
						$itemTotal[$row->getSoNo()]=1;
					}
					$tempObjlist[] = $row;
					$totalItem++;
				}
			}
		}
		$data["lang"] = $lang;
		$data["objlist"] = $tempObjlist;
		$data["itemTotal"]=$itemTotal;
		$data["totalOrder"]= $totalItem;
		$data["totalItem"]= $totalItem;
		$this->load->view('order/courier/courier_index', $data);
	}

	public function printOrder()
	{
		$subAppId = $this->getAppId() . "02";
		include_once(APPPATH . "language/" . $subAppId . "_" . $this->getLangId() . ".php");
		$data["courierList"] = $this->courierService->getDao()->getList(array('show_status'=>1,"api_enable"=>1), array('limit'=>-1));

		if($this->input->post("current_courier_id") && $_POST["dispatch_type"])
		{
			$courierId=$this->input->post("current_courier_id");
			$_SESSION["NOTICE"]="";
			//print courier order
			if($_POST["dispatch_type"]=="p" && $this->input->post("check")){
				$this->_courierFactoryModel->printCourierOrder($courierId,$this->input->post("check"));
			}
			if($_POST["dispatch_type"]=="g" && $this->input->post("check")){
				$result=$this->_courierFactoryModel->getCourierTrackingNo($courierId,$this->input->post("check"));
			}
			//delete courier order
			if($_POST["dispatch_type"]=="d" && $this->input->post("courier_order_id")){
				$result=$this->_courierFactoryModel->deleteCourierOrder($courierId,$this->input->post("check"));
			}

			if($_POST["dispatch_type"]=="m" && $this->input->post("check")){
				$result=$this->_courierFactoryModel->addCourierManifest($courierId,$this->input->post("check"));
			}

			if($_POST["dispatch_type"]=="dm" && $this->input->post("check")){
				$result=$this->_courierFactoryModel->deleteCourierManifest($courierId,$this->input->post("check"));
			}

			if($_POST["dispatch_type"]=="pm" && $this->input->post("check")){
				$batchIds=join(',',$this->input->post("check"));  
				$where["courier_batch_id in($batchIds)"]=null;
				$courierManifestObj = $this->courierService->getInterfaceCourierManifestDao()->getList($where, array('limit'=>-1));
				foreach($courierManifestObj as $manifestObj){
					$manifestBags=unserialize($manifestObj->getBags());
					$data["manifestBags"][$manifestBags[0]->BagNo]=$manifestBags[0];
					$barcode=$this->_courierFactoryModel->getBarcode($manifestBags[0]->BagNo);
					$data["barcode"][$manifestBags[0]->BagNo]=$barcode;
					if(empty($data["asendiaPpi"]))
					$data["asendiaPpi"]=substr($manifestBags[0]->BagNo,0,4);
				}
				$this->load->view('order/courier/manifest_label', $data);
				return;
			}
			$_SESSION["NOTICE"] = $result["message"];
		}

		if($this->input->post("courier_id") || $courierId)
		{
			if($this->input->post("courier_id")){
				$where["ic.courier_id"]=$data["selectedCourierId"]=$this->input->post("courier_id");	
			}else{
				$where["ic.courier_id"]=$data["selectedCourierId"]=$courierId;
			}
			//$where["ic.tracking_no IS NOT NULL "]=null;
			$where["ic.status"]="1";
			if($_POST["create_year"] && $_POST["create_year"] && $_POST["create_year"]){
				$_SESSION["createDate"]=array(
					"year"=>$_POST["create_year"],
					"mon"=>$_POST["create_month"],
					"mday"=>$_POST["create_day"],
				);
			}
			$createDate=$_SESSION["createDate"]["year"]."-".$_SESSION["createDate"]["mon"]."-".$_SESSION["createDate"]["mday"];
			$where["ic.create_on >= '{$createDate} 00:00:00'"]=null;
			$where["ic.create_on <= '{$createDate} 23:59:59'"]=null;

			if($this->input->post("download")){
				$content= $this->rptCourierTrackingnoReportService->getCsv($where);
				$filename= $where["ic.courier_id"]."_trackingno_report.csv";
				header("Content-type: application/vnd.ms-excel");
				header("Content-disposition: filename=$filename");
				echo $content;
				exit();
			}

			$data["createDate"]=$_SESSION["createDate"];
			$data["objlist"]=$this->courierService->getInterfacePendingCourierDao()->getCourierOrderByBatch($where, array("limit"=>"500","orderby"=>"ic.batch_id desc"));
			$data["totalOrder"]=$this->courierService->getInterfacePendingCourierDao()->getCourierOrderByBatch($where, array("num_rows"=>1,"limit"=>"500","orderby"=>"ic.batch_id desc"));
		}
		
		if($_POST["dispatch_type"]=="s" && $this->input->post("so_no")){
			$searchWhere=null;
			$searchWhere["ic.courier_order_id"]= $this->input->post("so_no");
			$data["objlist"]=$this->courierService->getInterfacePendingCourierDao()->getCourierOrderByBatch($searchWhere, array("limit"=>"500","orderby"=>"ic.batch_id desc"));
			$data["totalOrder"]=$this->courierService->getInterfacePendingCourierDao()->getCourierOrderByBatch($searchWhere, array("num_rows"=>1,"limit"=>"500","orderby"=>"ic.batch_id desc"));
		}
		$data["lang"] = $lang;
		$this->load->view('order/courier/courier_order_detail_v', $data);
	}

	public function getCourierBatchOrder($courierId,$batchId)
	{
		$subAppId = $this->getAppId() . "01";
		include_once(APPPATH . "language/" . $subAppId . "_" . $this->getLangId() . ".php");
		if($this->input->post("posted") && $_POST["dispatch_type"] && $this->input->post("check"))
		{
			if($_POST["dispatch_type"]=="s"){
				$currentCourierObj=$this->courierService->get(array("courier_id"=>$courierId));
				$checkSoNo=$this->input->post("check");
				$option["so_no"]=$checkSoNo;
				$pendingCourierDtoArr=$this->courierService->getInterfacePendingCourierDao()->getConfirmPendingOrder(array("batch_id"=>$batchId),$option);
				$formValue=array();$currentSoNo="";
				foreach ($pendingCourierDtoArr as $row){
						$formValue[]=array(
							"courier_id"=>$this->input->post("current_courier_id"),
							"so_no"=>$row->getSoNo(),
							"sh_no"=>$row->getShNo(),
							"batch_id"=>$batchId,
							"service_type"=>$row->getServiceType(),
							"delivery_name"=>$row->getDeliveryName(),
							"delivery_company"=>$row->getDeliveryCompany(),
							"delivery_address_1"=>$row->getDeliveryAddress1(),
							"delivery_address_2"=>$row->getDeliveryAddress2(),
							"delivery_address_3"=>$row->getDeliveryAddress3(),
							"delivery_city"=>$row->getDeliveryCity(),
							"delivery_state"=>$row->getDeliveryState(),
							"delivery_postcode"=>trim($row->getDeliveryPostcode()),
							"delivery_country_id"=>$row->getDeliveryCountryId(),
							"email"=>$row->getEmail(),
							"delivery_phone"=>$row->getDeliveryPhone(),
							"declared_desc"=>$row->getDeclaredDesc(),
							"declared_value"=>$row->getDeclaredValue(),
							"declared_type"=>"O", 
							"weight"=>$row->getWeight(),
						);
				}
				$result=$this->_courierFactoryModel->addCourierOrder($courierId,$formValue);	
			}
			//update so order delivery infomation
			if($_POST["dispatch_type"]=="u"){
				$result =$this->updateInterfacePendingCourier($this->input->post("order"),$batchId);
			}
			if($_POST["dispatch_type"]=="g"){
				$result =$this->_courierFactoryModel->getCourierTrackingNo($courierId,$batchId);
			}
			$_SESSION["NOTICE"] = $result["message"];
			redirect('/order/courier_order/get-courier-batch-order/'.$courierId.'/'.$batchId);
		}
		$where["ipc.batch_id"]=$batchId;
		$data["objlist"]=$this->courierService->getInterfacePendingCourierDao()->getCourierOrderByBatch($where, array("limit"=>-1));
		$data["batchId"]=$batchId;
		$data["lang"] = $lang;
			
		$this->load->view('order/courier/courier_batch_order_v', $data); 
	}


	private function getConditionDeclaredValue($row,$courierObj)
	{	
		$declaredValue=null;$insideCategory=false;
		$amount=round($this->soService->convertCurrency($row->getCurrencyId(), $courierObj->getApiCurrency(), $row->getAmount()), 2);
		$ruleCategory=array("1","6","5","3","2");
		$ruleSubCategory=array("567");
		if(in_array($row->getCatId(),$ruleCategory) || in_array($row->getSubCatId(),$ruleSubCategory))
		{
			$insideCategory=true;
		}
		if($amount >= 1000 && $insideCategory && $row->getWeight() >=2){
			$declaredValue=70;
		}else{
			$declaredValue = round($this->soService->convertCurrency("HKD", $courierObj->getApiCurrency(), $row->getDeclaredValue()), 2);
			$maxDeclaredValue=$courierObj->getMaxDeclaredValue();
			$minDeclaredValue=$courierObj->getMinDeclaredValue();
			if($declaredValue >  $maxDeclaredValue ){
				$declaredValue = $maxDeclaredValue;
			}else if($declaredValue < $minDeclaredValue){
				$declaredValue = rand($minDeclaredValue, $maxDeclaredValue);
			}
		}
		return $declaredValue;
	}

	private function getDeclaredValue($row,$courierObj)
	{
		if($row->getOldDeclaredValue()){
			$declaredValue=$row->getOldDeclaredValue();
		}else{
			$declaredValue=$this->getConditionDeclaredValue($row,$courierObj);
		}
		return $declaredValue;
	}

	private function getPostDeclaredValue($row,$courierObj)
	{
		//$rate need to comfirmed
		$declaredValue="";
		if($_POST["declared_value"][$row->getSoNo()]){
			$declaredValue=$_POST["declared_value"][$row->getSoNo()];
		}else if($row->getOldDeclaredValue()){
			$declaredValue=$row->getOldDeclaredValue();
		}else{
			$declaredValue=$this->getConditionDeclaredValue($row,$courierObj);
		}
		return $declaredValue;
	}


	private function updateInterfacePendingCourier($formValue,$batchId)
	{
        foreach($formValue as $soNo=> $orderInfo){
            $object=$this->courierService->getInterfacePendingCourierDao()->get(array("so_no"=>$soNo,"batch_id"=>$batchId));
            $needUpdate=false;
            if($orderInfo["delivery_name"]){
                $object->setDeliveryName($orderInfo["delivery_name"]);
                $needUpdate=true;
            }
            if($orderInfo["delivery_address_1"]){
                $object->setDeliveryAddress1($orderInfo["delivery_address_1"]);
                $needUpdate=true;
            }
             if($orderInfo["delivery_address_2"]){
                $object->setDeliveryAddress2($orderInfo["delivery_address_2"]);
                $needUpdate=true;
            }
             if($orderInfo["delivery_address_3"]){
                $object->setDeliveryAddress3($orderInfo["delivery_address_3"]);
                $needUpdate=true;
            }
            if($orderInfo["delivery_city"]){
                $object->setDeliveryCity($orderInfo["delivery_city"]);
                $needUpdate=true;
            }
            if($orderInfo["delivery_state"]){
                $object->setDeliveryState($orderInfo["delivery_state"]);
                $needUpdate=true;
            }
            if($orderInfo["delivery_postcode"]){
                $object->setDeliveryPostcode($orderInfo["delivery_postcode"]);
                $needUpdate=true;
            }
            if($orderInfo["delivery_country_id"]){

                $object->setDeliveryCountryId($orderInfo["delivery_country_id"]);
                $needUpdate=true;
            }
            if($orderInfo["declared_value"]){
                $object->setDeclaredValue($orderInfo["declared_value"]);
                $needUpdate=true;
            }
            if($needUpdate){
               $resultUpdate=$this->courierService->getInterfacePendingCourierDao()->update($object);
               if($resultUpdate){
               	$result["message"] .="order No : ".$object->getSoNo()." update success.\r\n";
               }
            }
        }
        return $result;    
    }

	private function getSubCatHsDetails($subCatId,$courtryId)
	{
		$where = array('ccm.sub_cat_id'=>$subCatId, 'ccm.country_id'=>$courtryId);
		$hsDetails = $this->customClassService->getHsBySubcatAndCountry($where);
		return $hsDetails[0];
	}

	public function getPendingCourierOrderFormValue($pendingCourierOrders,$courierObj)
	{
		$currentSoNo=null;
		foreach ($pendingCourierOrders as $row){
			if($currentSoNo!=$row->getSoNo() ){
				$currentSoNo=$row->getSoNo();
				$declaredValue=$this->getPostDeclaredValue($row,$courierObj);
				$address= @explode("|", $row->getDeliveryAddress());
				$row->setDeliveryAddress1($address[0]);
				if (!empty($address[1])){
					$row->setDeliveryAddress2($address[1]);
				}
				if (!empty($address[2])){
					$row->setDeliveryAddress3($address[2]);
				}
				$deliveryPhone=null;$deliveryPostcode=null;$declaredDesc=null;
				$deliveryPhone= $_POST["delivery_phone"][$row->getSoNo()] ? $_POST["delivery_phone"][$row->getSoNo()] : $row->getTel();
				$deliveryPostcode= $_POST["delivery_postcode"][$row->getSoNo()] ? $_POST["delivery_postcode"][$row->getSoNo()] : $row->getDeliveryPostcode();
				$declaredDesc= $_POST["declared_desc"][$row->getSoNo()] ? $_POST["declared_desc"][$row->getSoNo()] : $row->getDeclaredDesc();
				//SBF #4403 - If hs desc and code not found, get the hs desc and code from sub_cat_id of the product
				if(empty($declaredDesc) || $declaredDesc==null){
					$hsDetails=$this->getSubCatHsDetails($row->getSubCatId(),$row->getDeliveryCountryId());
					$declaredDesc=$hsDetails["description"];
					$row->setDeclaredHsCode($hsDetails["code"]);
				}
				$formValue[]=array(
					"courier_id"=>$this->input->post("current_courier_id"),
					"so_no"=>$row->getSoNo(),
					"sh_no"=>$row->getShNo(),
					"service_type"=>$this->input->post("service_type"),
					"delivery_name"=>$row->getDeliveryName(),
					"delivery_company"=>$row->getDeliveryCompany(),
					"delivery_address_1"=>$row->getDeliveryAddress1(),
					"delivery_address_2"=>$row->getDeliveryAddress2(),
					"delivery_address_3"=>$row->getDeliveryAddress3(),
					"delivery_city"=>$row->getDeliveryCity(),
					"delivery_state"=>$row->getDeliveryState(),
					"delivery_postcode"=>strtoupper(trim($deliveryPostcode)),
					"delivery_country_id"=>$row->getDeliveryCountryId(),
					"email"=>$row->getEmail(),
					"delivery_phone"=>$deliveryPhone,
					"declared_desc"=>$declaredDesc,
					"declared_hs_code"=>$row->getDeclaredHsCode(),
					"declared_value"=>floor($declaredValue),
					"declared_type"=>"O", 
					"weight"=>$row->getWeight(),
					"declared_currency"=>$courierObj->getApiCurrency(),
					"master_sku"=>$row->getSku(),
				);
			}
		}
		return $formValue;
	}

	public function getAppId()
	{
		return $this->appId;
	}

}