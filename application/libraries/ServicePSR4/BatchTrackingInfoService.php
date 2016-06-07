<?php namespace ESG\Panther\Service;

use PHPMailer;

class BatchTrackingInfoService extends BaseBatchService
{
	public function __construct()
	{
		parent::__construct();

		$this->soService = new SoService;
		$this->validationService = new ValidationService;
		$this->dataExchangeService = new DataExchangeService;
		$this->csvToXml = new CsvToXml;
		$this->xmlToVo = new XmlToVo;

		$this->dataPath = rtrim($this->getDao('Config')->valueOf("data_path"), '/');
	}

	public function cronTrackingInfo($wh)
	{
		$this->createFolder($this->dataPath, $wh);

		$func = "tracking_info_".$wh;
		$path = rtrim($this->getDao('Config')->valueOf($func."_path"), '/').'/';

		$no_file = 0;
		if($file_list = glob($path."*"))
		{
			foreach($file_list as $filename)
			{
				if (!is_file($filename))
				{
					continue;
				}
				$filename = basename($filename);
				$_SESSION["result"] .= "<br>checking {$wh} trackingfile: {$filename}<br>";

				$tlogVo = $this->getDao('TransmissionLog')->get();
				$tlogObj = clone $tlogVo;

				$batchVo = $this->getDao('Batch')->get();

				$batchObj = $this->getDao('Batch')->get(["remark"=>$filename]);
				$success = 1;
				if(!empty($batchObj))
				{
					$tlogObj->setFuncName($func);
					$tlogObj->setMessage($filename." already_in_batch");
					$this->getDao('TransmissionLog')->insert($tlogObj);
					$_SESSION["result"] .= "<font color='red'> -> {$filename}: already_in_batch</font><br>";
					$result[$no_file] = $filename;
					if (copy($path.$filename, $path."fail/".$filename))
					{
						$result[$no_file] = ["filename"=>$filename, "reason"=>"already in batch", "success" => "0"];
						unlink($path.$filename);
					}
				}
				else
				{
					switch ($wh)
					{
						case "ams":
							$arr = $this->validationService->checkField($path.$filename,1, 18);
							break;
						default:
							$arr = $this->validationService->checkField($path.$filename,1);
							break;
					}

					if ($arr)
					{
						$rules[0]=["not_empty"];	//shipment_number
						$rules[1]=["not_empty"];	//so_number
						$rules[2]=["not_empty"];	//status
						// $rules[3]=["not_empty"];	//tracking_number
						// $rules[4]=["not_empty"];	//ship_method
						// $rules[5]=["not_empty"];	//courier_id
						// $rules[6]=["not_empty"];	//dispatch_date
						// $rules[7]=["not_empty", "is_number"];	//weight
						// $rules[8]=["not_empty"];	//consignee
						// $rules[9]=[];	//postcode
						// $rules[10]=["not_empty"];	//country
						// $rules[11]=["not_empty", "is_number","min=0"];	//amount
						// $rules[12]=["not_empty"];	//currency
						// $rules[13]=["not_empty"];	//charge_out
						// $rules[14]=["not_empty"];	//qty

						$this->validationService->setRules($rules);
						for ($i = 0; $i < count($arr); $i++)
						{
							$this->validationService->setData($arr[$i]);
							$rs = FALSE;
							try
							{
								$rs = $this->validationService->run();
							}
							catch(Exception $e)
							{
								$tlogObj->setFuncName($func);
								$tlogObj->setMessage($e->getMessage());
								$_SESSION["result"] .= "<font color='red'> -> {$filename}: ".$tlogObj->getMessage()."</font><br>";
								$this->getDao('TransmissionLog')->insert($tlogObj);

								echo $this->getDao('TransmissionLog')->db->last_query();
							}

							if (!$rs)
							{
								$success = 0;
							}
						}

						if ($success)
						{
							$batchObj = clone $batchVo;
							$batchObj->setFuncName($func);
							$batchObj->setStatus("N");
							$batchObj->setListed(1);
							$batchObj->setRemark($filename);
							$this->getDao('Batch')->insert($batchObj);
							$result[$no_file] = ["filename"=>$filename, "reason"=>"upload success", "success" => "1"];
							$_SESSION["result"] .= " -> {$filename}: checking done<br>";
						}
						else
						{
							if (copy($path.$filename, $path."fail/".$filename))
							{
								$result[$no_file] = ["filename"=>$filename, "reason"=>"file format not match the requirement", "success" => "0"];
								$_SESSION["result"] .= "<font color='red'>  -> {$filename}: file format not match the requirement</font><br>";
								unlink($path.$filename);
							}
						}
					}
					else
					{
						$tlogObj->setFuncName($func);
						$tlogObj->setMessage("Number of field not match");
						$this->getDao('TransmissionLog')->insert($tlogObj);
						$_SESSION["result"] .= "<font color='red'>  -> {$filename}: Number of field not match</font><br>";
						if (copy($path.$filename, $path."fail/".$filename))
						{
							$result[$no_file] = ["filename"=>$filename, "reason"=>"number of field not match", "success" => "0"];
							unlink($path.$filename);
						}
					}
				}
				$no_file++;
			}
			if (!$no_file)
			{
				$_SESSION["result"] .= "<br>No {$wh} trackingfile found!<br>";
			}
			$this->batchTrackingInfo($wh);
		}
		else
		{
			$_SESSION["result"] .= "<br>No {$wh} trackingfile found!<br>";
		}
	}

	public function batchTrackingInfo($wh)
	{
		$func = "tracking_info_".$wh;
		$localPath = rtrim($this->getDao('Config')->valueOf($func."_path"), '/').'/';

		$objlist = $this->getDao('Batch')->getList(["func_name"=>$func, "status"=>"N"]);
		if($objlist)
		{
			foreach ($objlist as $obj)
			{
				$filename = $obj->getRemark();
				$_SESSION["result"] .= "<br>importing {$wh} trackingfile: {$filename}<br>";
				$success = 1;
				$batch_id = $obj->getId();

				switch ($wh)
				{
					default:
						$data_file = APPPATH.'data/tracking_info.txt';
						$this->csvToXml->CsvToXml($localPath.$obj->getRemark(), $data_file, TRUE, ",", TRUE);
						break;
				}
				$this->xmlToVo->XmlToVo();
				$output = $this->dataExchangeService->convert($this->csvToXml, $this->xmlToVo);
				if ($output)
				{
					foreach($output as $itinfoObj)
					{
						$this->checkItInfoObj($itinfoObj);
						$itinfoObj->setBatchId($batch_id);
						$itinfoObj->setBatchStatus("N");
						if ($this->getDao('InterfaceTrackingInfo')->insert($itinfoObj) !== FALSE)
						{
							$intinfoObjlist[] = $itinfoObj;
						}
					}
					$sosh_list = $this->getDao('SoShipment')->getTrackingInfoList(["batch_id"=>$batch_id]);
					$soshData = [];
					foreach($sosh_list as $list)
					{
						$sh_no = $list->getShNo();
						$soshData["sh_no"][$sh_no] = 1;
						$soshData["tracking_no"][$sh_no] = $list->getTrackingNo();
						$soshData["courier_id"][$sh_no] = $list->getCourierId();
					}
					$this->validationService->setExistsIn(["sh_no"=>$soshData["sh_no"]]);
					$obj->setStatus("P");
					$this->getDao('Batch')->update($obj);
					foreach($intinfoObjlist as $itinfoObj)
					{
						$rules["ShNo"] = ["existsIn=sh_no"];
						$this->validationService->setRules($rules);
						$this->validationService->setData($itinfoObj);
						$rs = FALSE;
						try
						{
							$rs = $this->validationService->run();
						}
						catch(Exception $e)
						{
							$itinfoObj->setFailedReason($e->getMessage());
							$_SESSION["result"] .= "<font color='red'>  -> {$filename} import error: ".$itinfoObj->getFailedReason()."</font><br>";
						}

						$soshDatalist = $this->getDao('SoShipment')->get(["sh_no"=>$itinfoObj->getShNo()]);
						if($rs)
						{
							$soshDatalist->setCourierId($itinfoObj->getCourierId());
							$soshDatalist->setTrackingNo($itinfoObj->getTrackingNo());
							$soshDatalist->setStatus("2");
							$this->getDao('SoShipment')->update($soshDatalist);
							$soObj = $this->getDao('So')->get(array("so_no"=>$itinfoObj->getSoNo()));

							$needUpdate = 0;

							if ($soObj->getDispatchDate() == '0000-00-00 00:00:00')
							{
								$soObj->setDispatchDate(date("Y-m-d H:i:s"));
								$needUpdate = 1;
							}

							$needToSendDispatch = FALSE;
							if ($soObj->getStatus() != 6)
							{
								$soObj->setStatus("6");
								$needUpdate = 1;
								$needToSendDispatch = TRUE;
							}

							if ($needUpdate)
							{
								$this->getDao('So')->update($soObj);
								if ($needToSendDispatch) {
									$this->soService->fireDispatch($soObj, $itinfoObj->getShNo());
								}
							}

							$soalObj = $this->getDao('SoAllocate')->get(["sh_no"=>$itinfoObj->getShNo()]);
							$soalObj->setStatus("3");
							$this->getDao('SoAllocate')->update($soalObj);
							$itinfoObj->setBatchStatus("S");

							if ($soObj->getBizType() == 'SPECIAL')
							{
								$specialOrders[] = $soObj->getSoNo();
							}

						}
						else
						{
							$itinfoObj->setBatchStatus("F");
							$success = 0;
						}
						$this->getDao('InterfaceTrackingInfo')->update($itinfoObj);
					}
					if ($success)
					{
						if ($specialOrders)
						{
							foreach ($specialOrders as $key => $soNo)
							{
								$soWithReason = $this->getDao('So')->getSoWithReason(['so.so_no' => $soNo], ['limit' => 1]);

								if ($soWithReason->getReasonId() == '34')
								{
									$apsDirectOrder[] = $soWithReason->getSoNo();
								}

							}

							$apsDirectOrders = implode(',', $apsDirectOrder);
							$where = "where so.so_no in (".$apsDirectOrders.")";
							$content = $this->getDao('So')->getApsDirectOrderCsv($where);

							$phpmail = new PHPMailer;
							$phpmail->CharSet = "UTF-8";
							$phpmail->IsSMTP();
							if ($smtphost = $this->getDao('Config')->valueOf("smtp_host")) {
								$phpmail->Host = $smtphost;
								$phpmail->SMTPAuth = $this->getDao('Config')->valueOf("smtp_auth");
								$phpmail->Username = $this->getDao('Config')->valueOf("smtp_user");
								$phpmail->Password = $this->getDao('Config')->valueOf("smtp_pass");
							}
							$phpmail->From = "do_not_reply@digitaldiscount.co.uk";
							$phpmail->FromName = "Panther APS ORDER ALERT";
							$phpmail->AddAddress("bd.platformteam@eservicesgroup.net");
							$phpmail->IsHTML(false);
							$phpmail->Subject = "DIRECT APS ORDERS";
							$phpmail->Body = "Attached: DIRECT APS ORDERS.";
							$phpmail->addStringAttachment($content, 'direct_aps_info.csv');    // Optional name
							$phpmail->Send();
						}

						if (copy($localPath.$obj->getRemark(), $localPath."success/".$obj->getRemark()))
						{
							unlink($localPath.$obj->getRemark());
						}
						$obj->setStatus("C");
						$obj->setEndTime(date("Y-m-d H:i:s"));
						$_SESSION["result"] .= "<font color='green'>  -> {$filename} import completed, click <a href='".base_url()."integration/integration/view/tracking_info/".$obj->getId()."' target='integration'>here</a> for detail</font><br>";
					}
					else
					{
						if (copy($localPath.$obj->getRemark(), $localPath."complete_with_error/".$obj->getRemark()))
						{
							unlink($localPath.$obj->getRemark());
						}
						$obj->setStatus("CE");
						$obj->setEndTime(date("Y-m-d H:i:s"));
						$_SESSION["result"] .= "<font color='blue'>  -> {$filename} import completed with error, click <a href='".base_url()."integration/integration/view/tracking_info/".$obj->getId()."' target='integration'>here</a> for detail</font><br>";
					}
					$this->getDao('Batch')->update($obj);
				}
				else
				{
					$_SESSION["result"] .= "<font color='red'>  -> {$filename} import error: ".__LINE__."</font><br>";
				}
			}
		}
	}

	private function checkItInfoObj($itinfoObj)
	{
		if (empty($itinfoObj->getQty()))
		{
			$itinfoObj->setQty(0);
		}

		if (empty($itinfoObj->getQtyShipped()))
		{
			$itinfoObj->setQtyShipped(0);
		}

		if (empty($itinfoObj->getWeight()))
		{
			$itinfoObj->setWeight(0.00);
		}

		if (empty($itinfoObj->getAmount()))
		{
			$itinfoObj->setAmount(0.00);
		}

		if (empty($itinfoObj->getShippingCost()))
		{
			$itinfoObj->setShippingCost(0.00);
		}
	}

	private function createFolder($data_path, $wh)
	{
		if (!file_exists($data_path))
		{
			mkdir($data_path, 0775);
		}

		$full_path = $data_path . "/warehouse";
		if (!file_exists($full_path))
		{
			mkdir($full_path, 0775);
		}

		$full_path = $data_path . "/warehouse/". $wh;
		if (!file_exists($full_path))
		{
			mkdir($full_path, 0775);
		}

		$full_path = $data_path . "/warehouse/". $wh. "/from_warehouse";
		if (!file_exists($full_path))
		{
			mkdir($full_path, 0775);
		}

		$full_path = $data_path . "/warehouse/". $wh. "/from_warehouse/tracking_information";
		if (!file_exists($full_path))
		{
			mkdir($full_path, 0775);
		}

		$full_path = $data_path . "/warehouse/". $wh. "/from_warehouse/tracking_information";
		if (!file_exists($full_path))
		{
			mkdir($full_path, 0775);
		}

		$full_path = $data_path . "/warehouse/". $wh. "/from_warehouse/tracking_information/success";
		if (!file_exists($full_path))
		{
			mkdir($full_path, 0775);
		}

		$full_path = $data_path . "/warehouse/". $wh. "/from_warehouse/tracking_information/fail";
		if (!file_exists($full_path))
		{
			mkdir($full_path, 0775);
		}

		$full_path = $data_path . "/warehouse/". $wh. "/from_warehouse/tracking_information/complete_with_error";
		if (!file_exists($full_path))
		{
			mkdir($full_path, 0775);
		}
	}

}
