<?php
namespace ESG\Panther\Service;

use PHPMailer;

class CourierFeedService extends BaseService
{

	public function __construct()
	{
		parent::__construct();
		$this->soService = new SoService;
		$this->dataExchangeService = new DataExchangeService;
		$this->exchangeRateService = new ExchangeRateService;
		$this->voToXml = new VoToXml;
		$this->xmlToCsv = new XmlToCsv;
	}

	public function getGenerateCourierFile($id)
	{
		if ($obj = $this->getDao('CourierFeed')->get(["id" => $id])) {
			$so_no_list = json_decode($obj->getSoNoStr());
			$mawb = $obj->getMawb();
			$courier = $obj->getCourierId();
			$ret = $this->generateCourierFile($so_no_list, $courier, $mawb);
			$obj->setExec(1);
			$name = $obj->getCreateBy();
			$this->getDao('CourierFeed')->update($obj);

			$file_path = $this->getDao('Config')->valueOf('courier_path') . $ret;

			$bodytext = "";
			if ($user_obj = $this->getDao('User')->get(["id" => $name])) {
				$email_addr = $user_obj->getEmail();

			} else {
				$email_addr = "nero@eservicesgroup.com";

				$bodytext .= "user email not found <br>";
			}

			foreach ($so_no_list as $o) {
				$bodytext .= $o . "<br/>";
			}

			$phpmail = new PHPMailer;
			$phpmail->CharSet = "UTF-8";
			$phpmail->IsSMTP();
			if ($smtphost = $this->getDao('Config')->valueOf("smtp_host")) {
				$phpmail->Host = $smtphost;
				$phpmail->SMTPAuth = $this->getDao('Config')->valueOf("smtp_auth");
				$phpmail->Username = $this->getDao('Config')->valueOf("smtp_user");
				$phpmail->Password = $this->getDao('Config')->valueOf("smtp_pass");
			}
			$phpmail->From = "courier_feed@eservicesgroup.com";
			$phpmail->AddBCC("brave.liu@eservicesgroup.com");
			$phpmail->FromName = "Panther Courier Feed";
			$phpmail->AddAddress($email_addr);
			$phpmail->IsHTML(true);
			$phpmail->Subject = "Courier feed: $ret";
			if (file_exists($file_path)) {
				$phpmail->AddAttachment($file_path);
			} else {
				$bodytext = "courier file can not be found<br />" . $bodytext;
			}
			$phpmail->Body = $bodytext;

			$phpmail->Send();

			return $ret;
		}
	}

	public function generateCourierFile($checked = [], $courier = "", $mawb = "", $debug_explain = false)
	{
		$file_content = "";
		$output_path = $this->getDao('Config')->valueOf('courier_path');
		$data_out = [];
		$i = 1;
		foreach ($checked as $key => $value) {
			switch ($courier) {
				case "DHLHKD":
				case "DHL":
					$data_out[] =  $this->getDhlCourierFeed($value);

					$this->voToXml->VoToXml($data_out, '');
				$this->xmlToCsv->XmlToCsv('', APPPATH . 'data/courier_feed/courier_dhl_xml2csv.txt', FALSE, '|');
					$file_content = $this->getService('DataExchange')->convert($this->voToXml, $this->xmlToCsv);
					break;

				case "A2B":
					$data_out[] = $this->getA2BCourierFeed($value, $i);
					$this->voToXml->VoToXml($data_out, '');
					$this->xmlToCsv->XmlToCsv('', APPPATH . 'data/courier_feed/courier_a2b_xml2csv.txt', TRUE, ',');
					$file_content = $this->getService('DataExchange')->convert($this->voToXml, $this->xmlToCsv);
					break;
				case "RPX":
					$data_out[] = $this->getRPXCourierFeed($value, $i);
					$this->voToXml->VoToXml($data_out, '');
					$this->xmlToCsv->XmlToCsv('', APPPATH . 'data/courier_feed/courier_rpx_xml2csv.txt', TRUE, ',');
					$file_content = $this->getService('DataExchange')->convert($this->voToXml, $this->xmlToCsv);
					break;
				case "singapore-post":
					$data_out[] = $this->getSgpCourierFeed($value);

					$this->voToXml->VoToXml($data_out, '');
					$this->xmlToCsv->XmlToCsv('', APPPATH . 'data/courier_feed/courier_sgp_xml2csv.txt', TRUE, ',');
					$file_content = $this->getService('DataExchange')->convert($this->voToXml, $this->xmlToCsv);
					break;

				case "chronopost-france":
					$data_out[] = $this->getChronopostFranceCourierFeed($value);

					$this->voToXml->VoToXml($data_out, '');
					$this->xmlToCsv->XmlToCsv('', APPPATH . 'data/courier_feed/courier_chronopost_xml2csv.txt', TRUE, ',');
					$file_content = $this->getService('DataExchange')->convert($this->voToXml, $this->xmlToCsv);
					break;

				case 'B2C':
					$data_out[] = $this->getB2cCourierFeed($value);

					$this->voToXml->VoToXml($data_out, '');
					$this->xmlToCsv->XmlToCsv('', APPPATH . 'data/courier_feed/courier_b2c_xml2csv.txt', TRUE, ',');
					$file_content = $this->getService('DataExchange')->convert($this->voToXml, $this->xmlToCsv);
					break;

				case "DPD_NL":
					$data_out[] =  $this->getDpdNlCourierFeed($value);

					$this->voToXml->VoToXml($data_out, '');
					$this->xmlToCsv->XmlToCsv('', APPPATH . 'data/courier_feed/courier_DPD_NL_xml2csv.txt', TRUE, ',');
					$file_content = $this->getService('DataExchange')->convert($this->voToXml, $this->xmlToCsv);
					break;

				case "DPD_UK":
				case "DPD":
					$data_out[] =  $this->getDpdUkCourierFeed($value);

					$this->voToXml->VoToXml($data_out, '');
					$this->xmlToCsv->XmlToCsv('', APPPATH . 'data/courier_feed/courier_dpd_xml2csv.txt', TRUE, ',');
					$file_content = $this->getService('DataExchange')->convert($this->voToXml, $this->xmlToCsv);
					break;


				case "TOLL": // DPEX
					$data_out[] =  $this->getTollCourierFeed($value);

					$this->voToXml->VoToXml($data_out, '');
					$this->xmlToCsv->XmlToCsv('', APPPATH . 'data/courier_feed/courier_toll_xml2csv.txt', TRUE, '	');
					$file_content = $this->getService('DataExchange')->convert($this->voToXml, $this->xmlToCsv);
					break;

				case "dhl-global-mail":
					if ($arr = $this->getShipmentDeliveryInfo($value,'ShipmentInfoToCourierDhlGlobalMailDto')){

					$counter=1;
					foreach ($arr as $key=>$row){
							if($counter==1){
								$this->setDhlGlobalMailCourierFeed($row);

								$data_out[]=$row;
							}

							$counter++;
						}
					}

					$this->voToXml->VoToXml($data_out, '');
					$this->xmlToCsv->XmlToCsv('', APPPATH . 'data/courier_feed/courier_dhl_global_mail_xml2csv.txt', TRUE, ',');
					$file_content = $this->getService('DataExchange')->convert($this->voToXml, $this->xmlToCsv);
					break;

				case "FEDEX":
					if ($arr = $this->getShipmentDeliveryInfoCourier($value)) {
						foreach ($arr as $row) {
							$this->setFedexCourierFeed($row);

							$data_out[] = $row;
							$counter++;
						}
					}

					$this->voToXml->VoToXml($data_out, '');
					$this->xmlToCsv->XmlToCsv('', APPPATH . 'data/courier_feed/courier_fedex_xml2csv.txt', TRUE, '|');
					$file_content = $this->getService('DataExchange')->convert($this->voToXml, $this->xmlToCsv);
					break;

				case "DHLBBX":
					if ($arr = $this->getShipmentDeliveryInfoDhl($value)) {
						foreach ($arr as $row) {
							$ar_address = @explode("|", $row->getDeliveryAddress());
							$row->setDeliveryAddress1($ar_address[0]);
							if (empty($ar_address[1])) {
								$row->setDeliveryAddress2('NA');
							} else {
								$row->setDeliveryAddress2($ar_address[1]);
							}
							if (empty($ar_address[2])) {
								$row->setDeliveryAddress3('NA');
							} else {
								$row->setDeliveryAddress3($ar_address[2]);
							}
							$row->setQty(1);
							$row->setProdWeight($row->getProdWeight() * $row->getQty());
							$row->setPrice($row->getAmount());
							if ($row->getTel() == "") {
								$row->setTel("0");
							}
							if ($row->getDeliveryCompany() == "") {
								$row->setDeliveryCompany($row->getDeliveryName());
							}
							$prod_obj = $this->getDao('Product')->get(["sku" => $row->getProdSku()]);
							$declared_value = $this->soService->getDeclaredValue($prod_obj, $row->getDeliveryCountryId(), $row->getPrice());
							$row->setDeclaredValue(round($declared_value * $row->getRate(), 2));
							if (trim($mawb) != "") {
								$row->setMawb("MAWB#: " . $mawb);
							}
							$data_out[] = $row;
							$counter++;
						}
					}
					$this->voToXml->VoToXml($data_out, '');
					$this->xmlToCsv->XmlToCsv('', APPPATH . 'data/courier_feed/courier_dhlbbx_xml2csv.txt', FALSE, '|');
					$file_content = $this->getService('DataExchange')->convert($this->voToXml, $this->xmlToCsv);
					break;

				case "FEDEX2":
					if ($arr = $this->getShipmentDeliveryInfoCourier($value)) {
						$total_declared_value = 0;
						$total_declared_value_to_6decimals = 0;
						$this->declared_value_debug .= "8total_declared_value_to_6decimals value: $total_declared_value_to_6decimals\r\n";
						$counter = 0;
						$ts = "";
						foreach ($arr as $row) {
							$this->declared_value_debug .= "8total_declared_value_to_6decimals value: $total_declared_value_to_6decimals\r\n";
							$ar_address = @explode("|", $row->getDeliveryAddress());
							$row->setDeliveryAddress1($ar_address[0]);
							array_shift($ar_address);
							$row->setDeliveryAddress2(trim(@implode("|", $ar_address), "|"));

							$row->setProdWeight($row->getProdWeight() * $row->getQty() * 10);
							$row->setPrice($row->getAmount());

							if ($row->getDeliveryCompany() == "") {
								$row->setDeliveryCompany($row->getDeliveryName());
							}

							$cc = $row->getCurrencyId();

							switch ($cc) {
								case "GBP":
									$cc = "UKL";
									break;
								case "SGD":
									$cc = "SID";
									break;
							}

							if ($counter == 0) $total_declared_value = $row->getPrice(); else $total_declared_value += $row->getPrice();

							$prod_obj = $this->getDao('Product')->get(["sku" => $row->getProdSku()]);

							// we pass total_declared_value_to_6decimals in so that we will eventually calculate a declared value
							// of all the items in the order, e.g. SKU-A: 649, SKU-B: 399, we will calculate declared value based on 649+399
							$declared_value = $this->soService->getDeclaredValue($prod_obj, $row->getDeliveryCountryId(), $total_declared_value);
							$this->declared_value_debug .= "declared value: $declared_value\r\n";
							$this->declared_value_debug .= "1total_declared_value_to_6decimals value: $total_declared_value_to_6decimals\r\n";

							# convert to USD
							$convert_to_usd = false;
							if ($convert_to_usd) {
								$declared_value = round($declared_value * $row->getRate(), 2);
								$row->setDeclaredValue($declared_value);
							}

							$this->declared_value_debug .= "2total_declared_value_to_6decimals value: $total_declared_value_to_6decimals\r\n";

							$total_declared_value_to_6decimals = $declared_value * 1000000;
							$this->declared_value_debug .= "3total_declared_value_to_6decimals value: $total_declared_value_to_6decimals\r\n";

							if ($counter == 0) {
								$file_content .=
									"0,\"20\"\r\n" .
									"1,\"{$counter}\"\r\n" .
									"1274,\"3\"\r\n" .
									"31,\"LIVE ASSET LOGISTICS\"\r\n" .
									"11,\"{$row->getDeliveryCompany()}\"\r\n" .
									"12,\"{$row->getDeliveryName()}\"\r\n" .
									"13,\"{$row->getDeliveryAddress1()}\"\r\n" .
									"14,\"{$row->getDeliveryAddress2()}\"\r\n" .
									"16,\"{$row->getDeliveryState()}\"\r\n" .
									"15,\"{$row->getDeliveryCity()}\"\r\n" .
									"17,\"{$row->getDeliveryPostcode()}\"\r\n" .
									"50,\"{$row->getDeliveryCountryId()}\"\r\n" .
									"18,\"{$row->getTel()}\"\r\n" .
									"116,\"1\"\r\n" .
									"21,\"5\"\r\n" .
									// "119,\"{$row->getDeclaredValue()}\"\r\n".
									"79-1,\"{$row->getCcDesc()} hscode {$row->getCcCode()}\"\r\n" .
									// "79-2,\"hscode {$row->getCcCode()}\"\r\n".
									"81-1,\"{$row->getCcCode()}\"\r\n" .
									"80-1,\"JP\"\r\n" .
									// "80-2,\"JP\"\r\n".
									"25,\"{$row->getSoNo()}\"\r\n" .
									"72,\"6\"\r\n" .
									"23,\"3\"\r\n" .
									"20,\"319974954\"\r\n" .
									"68,\"" . $cc . "\"\r\n" .
									"70,\"3\"\r\n" .
									"1273,\"01\"\r\n" .
									"75,\"KGS\"\r\n" .
									"190,\"N\"\r\n" .
									"1116,\"C\"\r\n" .
									"414-1,\"PCS\"\r\n" .
									"113,\"Y\"\r\n" .
									"82-1,\"1\"\r\n";
							}
							$counter++;
						}

						$file_content .=
							"1030-1,\"$total_declared_value_to_6decimals\"\r\n" .
							"629,\"default\"\r\n" .
							"71,\"319974954\"\r\n" .
							"2806,\"Y\"\r\n" .
							"418-1,\"Package contains lithium ion batteries or cells (PI966)\"\r\n" .
							"418-2,\"Handle with care, flammability hazard if damage\"\r\n" .
							"418-3,\"Special procedures must be followed in the event the package is damaged,\"\r\n" .
							"418-4,\"to include inspection and repacking if necessary\"\r\n" .
							"418-5,\"Emergency contact no. +852 3153 2766\"\r\n" .
							"99,\"\"\r\n";
					}

					break;

				case "TNT":
					$counter = 0;
					if ($arr = $this->getShipmentDeliveryInfoCourierForTnt($value)) {

						$prev_so_no = "";
						if (is_array($arr)) {
							foreach ($arr as $row) {
								if ($row->getSoNo() != $prev_so_no) {
									$ar_address = @explode("|", $row->getDeliveryAddress());
									$row->setDeliveryAddress1($ar_address[0]);
									array_shift($ar_address);
									$row->setDeliveryAddress2(trim(@implode("|", $ar_address), "|"));
									if ($row->getDeliveryAddress2() == "") {
										$row->setDeliveryAddress2(".");
									}

									$row->setProdWeight(min(2, $row->getProdWeight()));
									$row->setPrice($row->getAmount());

									$countryObj = $this->getDao('Country')->get(["country_id" => $row->getDeliveryCountryId()]);
									$row->setCountryName($countryObj->getName());

									$row->setItemNo($counter);

									if ($row->getDeliveryCity() == "") {
										$row->setDeliveryCity('.');
									}

									$prev_so_no = $row->getSoNo();

									$data_out[] = $row;
									$counter++;
								}
							}
						}
					}

					$this->voToXml->VoToXml($data_out, '');
					$this->xmlToCsv->XmlToCsv('', APPPATH . 'data/courier_feed/courier_tnt_xml2csv.txt', TRUE, '|');
					$file_content = $this->getService('DataExchange')->convert($this->voToXml, $this->xmlToCsv);
					break;

				case "NEW_QUANTIUM":
					$counter = 0;
					if ($arr = $this->getShipmentDeliveryInfoCourier($value)) {
						$prev_so_no = "";
						foreach ($arr as $row) {
							if ($row->getSoNo() != $prev_so_no) {
								$ar_address = @explode("|", $row->getDeliveryAddress());
								$row->setDeliveryAddress1($ar_address[0]);
								array_shift($ar_address);
								$row->setDeliveryAddress2(trim(@implode("|", $ar_address), "|"));
								if ($row->getDeliveryAddress2() == "") {
									$row->setDeliveryAddress2(".");
								}

								$row->setProdWeight(min(2, $row->getProdWeight()));
								$row->setPrice($row->getAmount());

								$countryObj = $this->getDao('Country')->get(["country_id" => $row->getDeliveryCountryId()]);
								$row->setCountryName($countryObj->getName());

								$row->setItemNo($counter);

								if ($row->getDeliveryCompany() == "") {
									$row->setDeliveryCompany($row->getDeliveryName());
								}

								if ($row->getDeliveryCity() == "") {
									$row->setDeliveryCity('.');
								}

								$declared_value = $this->soService->getDeclaredValue($row, $row->getDeliveryCountryId(), $row->getPrice());
								$row->setDeclaredValue(round($declared_value * $row->getRate(), 2));

								$prev_so_no = $row->getSoNo();

								$data_out[] = $row;
								$counter++;
							}
						}
					}
					$this->voToXml->VoToXml($data_out, '');
					$this->xmlToCsv->XmlToCsv('', APPPATH . 'data/courier_feed/courier_new_quantium_xml2csv.txt', FALSE, '|');
					$file_content = $this->getService('DataExchange')->convert($this->voToXml, $this->xmlToCsv);
					break;

				case "QUANTIUM":
					$counter = 0;
					if ($arr = $this->getShipmentDeliveryInfoCourier($value)) {
						$prev_so_no = "";
						foreach ($arr as $row) {
							if ($row->getSoNo() != $prev_so_no) {
								$ar_address = @explode("|", $row->getDeliveryAddress());
								$row->setDeliveryAddress1($ar_address[0]);
								array_shift($ar_address);
								$row->setDeliveryAddress2(trim(@implode("|", $ar_address), "|"));

								$row->setProdWeight(min(2000, $row->getProdWeight() * 1000));
								$row->setPrice($row->getAmount());

								$countryObj = $this->getDao('Country')->get(["country_id" => $row->getDeliveryCountryId()]);
								$row->setCountryName($countryObj->getName());

								$row->setItemNo($counter);

								if ($row->getDeliveryCompany() == "") {
									$row->setDeliveryCompany($row->getDeliveryName());
								}

								$declared_value = $this->soService->getDeclaredValue($row, $row->getDeliveryCountryId(), $row->getPrice());
								$row->setDeclaredValue(round($declared_value * $row->getRate(), 2));

								$prev_so_no = $row->getSoNo();

								$data_out[] = $row;
								$counter++;
							}
						}
					}
					$this->voToXml->VoToXml($data_out, '');
					$this->xmlToCsv->XmlToCsv('', APPPATH . 'data/courier_feed/courier_quantium_xml2csv.txt', TRUE, ',');
					$file_content = $this->getService('DataExchange')->convert($this->voToXml, $this->xmlToCsv);
					break;

				case "IM":
				case "RMR":
					$arr = $this->getShipmentDeliveryInfo($value, 'DispatchListDto'); // Pass the SO #

					if (!$arr || ($no_of_line = count($arr)) == 0) {
						continue;  // No data is found.  It shouldn't happen.
					}

					$counter = 1;

					foreach ($arr as $row) {
						$row->setTotalItemCount($no_of_line);
						$row->setItemNo($counter);
						if (($courier == "RMR") && ($row->getDeliveryCountryId() != "US")) {
							$row->setUnitPrice(number_format($row->getUnitPrice() * 0.1, 2, '.', ''));
							$row->setDeliveryCharge(number_format($row->getDeliveryCharge() * 0.1, 2, '.', ''));
							$row->setAmount(number_format($row->getAmount() * 0.1, 2, '.', ''));
						}
						$row->setSubtotal(number_format(
							$row->getUnitPrice() * $row->getQty()
							, 2, '.', ''));
						$row->setActualCost(number_format(
							$row->getAmount() - $row->getOfflineFee()
							, 2, '.', ''));
						$row->setBillDetail('N'); // Always 'N' at the beginning.
						list($del_address_1, $del_address_2, $del_address_3) = explode("|", $row->getDeliveryAddress());
						$row->setDeliveryAddress1($del_address_1);
						$row->setDeliveryAddress2($del_address_2);
						$row->setDeliveryAddress3($del_address_3);
						if ($counter > 1) {
							$row->setShipOption('');
							$row->setDeliveryCharge(0.00);
							$row->setPromotionCode('');
							$row->setAmount(0.00);
							$row->setDeliveryTypeId('');
							$row->setActualCost(0.00);
						}
						$data_out[] = $row;
						$counter++;
					}
					$this->voToXml->VoToXml($data_out, '');
					$this->xmlToCsv->XmlToCsv('', APPPATH . 'data/courier_feed/courier_' . strtolower($courier) . '_xml2csv.txt', TRUE, chr(9));
					$file_content = $this->getService('DataExchange')->convert($this->voToXml, $this->xmlToCsv);

					// Prepare dispatch list data
					$counter = 1;
					foreach ($arr as $row) {
						$row->setTotalItemCount($no_of_line);
						$row->setItemNo($counter);
						$row->setSubtotal(number_format($row->getUnitPrice() * $row->getQty(), 2, '.', ''));
						$row->setActualCost(number_format($row->getAmount() - $row->getOfflineFee(), 2, '.', ''));

						if ($counter > 1) {
							# code
						}
						$row->setWarehouseId($courier);
						$row->setBin("STAG");
						$dispatch_data_out[] = $row;
						$counter++;
					}
					$this->voToXml->VoToXml($dispatch_data_out, '');
					if ($courier == "RMR")
						$data_file = 'data/dispatch_list_rmr_xml2csv.txt';
					else
						$data_file = 'data/dispatch_list_xml2csv.txt';
					$this->xmlToCsv->XmlToCsv('', APPPATH . $data_file, TRUE, ',');
					$dispatch_content = $this->getService('DataExchange')->convert($this->voToXml, $this->xmlToCsv);
					break;

				case "ARAMEX_COD":
					if ($arr = $this->getShipmentDeliveryInfoDhl($value)) {
						foreach ($arr as $row) {
							$ar_address = @explode("|", $row->getDeliveryAddress());
							$row->setDeliveryAddress1($ar_address[0]);
							$row->setDeliveryAddress2($ar_address[1]);
							$row->setDeliveryAddress3($ar_address[2]);
							$row->setQty($row->getQty());
							$row->setProdWeight($row->getProdWeight() * $row->getQty());
							$row->setPrice($row->getAmount());
							if ($row->getTel() == "") {
								$row->setTel("0");
							}
							if ($row->getDeliveryCompany() == "") {
								$row->setDeliveryCompany($row->getDeliveryName());
							}

							$prod_obj = $this->getDao('Product')->get(["sku" => $row->getProdSku()]);
							$declared_value = $row->getPrice();

							# convert to USD
							$convert_to_usd = false;
							if ($convert_to_usd) {
								$declared_value = round($declared_value * $row->getRate(), 2);
							}
							$row->setDeclaredValue($declared_value);

							if ($country_obj = $this->getDao('Country')->get(["country_id" => $row->getDeliveryCountryId()])) {
								$country_name = $country_obj->getName();
								$row->setDeliveryCountryId($country_name);
							}
							$data_out[] = $row;
							$counter++;
						}
					}

					$this->voToXml->VoToXml($data_out, '');
					$this->xmlToCsv->XmlToCsv('', APPPATH . 'data/courier_feed/courier_aramex_cod_xml2csv.txt', TRUE, ',');
					$file_content = $this->getService('DataExchange')->convert($this->voToXml, $this->xmlToCsv);

					break;

				case "ARAMEX":
					if ($arr = $this->getShipmentDeliveryInfoDhl($value)) {
						foreach ($arr as $row) {
							$ar_address = @explode("|", $row->getDeliveryAddress());
							$row->setDeliveryAddress1($ar_address[0]);
							$row->setDeliveryAddress2($ar_address[1]);
							$row->setDeliveryAddress3($ar_address[2]);
							$row->setQty($row->getQty());
							$row->setProdWeight($row->getProdWeight() * $row->getQty());
							$row->setPrice($row->getAmount());
							if ($row->getTel() == "") {
								$row->setTel("0");
							}
							if ($row->getDeliveryCompany() == "") {
								$row->setDeliveryCompany($row->getDeliveryName());
							}

							$prod_obj = $this->getDao('Product')->get(["sku" => $row->getProdSku()]);
							$declared_value = $this->soService->getDeclaredValue($prod_obj, $row->getDeliveryCountryId(), $row->getPrice());
							$row->setDeclaredValue(round($declared_value * $row->getRate(), 2));

							if ($country_obj = $this->getDao('Country')->get(["country_id" => $row->getDeliveryCountryId()])) {
								$country_name = $country_obj->getName();
								$row->setDeliveryCountryId($country_name);
							}
							$data_out[] = $row;
							$counter++;
						}
					}
					$this->voToXml->VoToXml($data_out, '');
					$this->xmlToCsv->XmlToCsv('', APPPATH . 'data/courier_feed/courier_aramex_xml2csv.txt', TRUE, ',');
					$file_content = $this->getService('DataExchange')->convert($this->voToXml, $this->xmlToCsv);
					break;

				case "MRW":
					if ($arr = $this->getShipmentDeliveryInfoCourier($value)) {
						$this->getDao("Sequence")->setSeqName("mrw_tracking_id");
						$tracking_id = $this->getDao("Sequence")->seqNextVal();
						$this->getDao("Sequence")->db->last_query();

						$max_tracking_id = "26931929951";

						if (strcmp($tracking_id, $max_tracking_id) == 0)
							mail("itsupport@eservicesgroup.net", "Invalid tracking id of MRW Courier", "Please note that the max value of tracking id have been used for the order sn_no " . $value, 'From: website@digitaldiscount.com');

						if (!$tracking_id)
							$tracking_id = "";

						$totalweight = 0.0;
						$totalprice = 0;
						$totalqty = 0;

						foreach ($arr as $row) {
							$totalweight += $row->getProdWeight() * $row->getQty();
							$totalprice += $row->getPrice() * $row->getQty();
							$totalqty += $row->getQty();
						}

						foreach ($arr as $row) {
							$ar_address = @explode("|", $row->getDeliveryAddress());
							$ar_address = str_replace(";", " ", $ar_address);
							$row->setDeliveryAddress(trim(@implode(" ", $ar_address)));
							$row->setShippingDate(date('dmY'));
							$tel = $row->getTel();
							if (strlen($tel) > 9)
								$tel = substr($tel, -9);

							$file_content .= "\"H\";" .
								"\"E\";" .
								"\"0001{$row->getSoNo()}\";" .
								"\"00826\";" .
								"\"\";" .
								"\"{$row->getShippingDate()}\";" .
								"\"ALMACEN 1\";" .
								"\"" . ((strlen($row->getDeliveryName()) > 30) ? substr($row->getDeliveryName(), 0, 30) : $row->getDeliveryName()) . "\";" .
								"\"\";" .
								"\"\";" .
								"\"" . ((strlen($row->getDeliveryAddress()) > 80) ? substr($row->getDeliveryAddress(), 0, 80) : $row->getDeliveryAddress()) . "\";" .
								"\"" . ((strlen($row->getDeliveryCity()) > 20) ? substr($row->getDeliveryCity(), 0, 20) : $row->getDeliveryCity()) . "\";" .
								"\"{$row->getDeliveryPostcode()}\";" .
								"\"{$tel}\";" .
								"\"{$tel}\";" .
								"\"" . ((strlen($row->getDeliveryCity()) > 20) ? substr($row->getDeliveryCity(), 0, 20) : $row->getDeliveryCity()) . "\";" .
								"\"{$row->getDeliveryCountryId()}\";" .
								"\"\";" .
								"\"{$totalweight}\";" .
								"\"\";" .
								"\"1\";" .
								"\"N\";" .
								"\"\";" .
								"\"" . ((strlen($row->getCcDesc()) > 24) ? substr($row->getCcDesc(), 0, 24) : $row->getCcDesc()) . "\";" .
								"\"0{$tracking_id}\";" .
								"\"D\";" .
								"\"\";" .
								"\"{$row->getClientEmail()}\";" .
								"\"Panther\"\r\n" .
								"\"L\";" .
								"\"0001{$row->getSoNo()}\";" .
								"\"00826BULTOPAXD\";" .
								"\"{$totalqty}\";" .
								"\"{$totalprice}\";" .
								"\"0\";" .
								"\"00826\";" .
								"\"\"\r\n";

							$this->getDao("Sequence")->updateSeq($tracking_id);
							break;
						}
					}
					break;

				default:
					$arr = $this->getShipmentDeliveryInfo($value, 'DispatchListDto'); // Pass the SO #
					if (!$arr || ($no_of_line = count($arr)) == 0) continue;

					$counter = 1;
					foreach ($arr as $row) {
						$this->setDefaultCourierFeed($row);

						$data_out[] = $row;
						$counter++;
					}

					$this->voToXml->VoToXml($data_out, '');
					$this->xmlToCsv->XmlToCsv('', APPPATH . 'data/courier_feed/courier_xml2csv.txt', TRUE, ',');
					$file_content = $this->getService('DataExchange')->convert($this->voToXml, $this->xmlToCsv);
					break;

				$i++;
			}
		}

		if ($file_content) {
			return $this->writeFile($file_content, $courier);
		}

		return;
	}

	public function getDhlCourierFeed($value)
	{
		if ($arr = $this->getShipmentDeliveryInfoDhl($value))
		{
			foreach ($arr as $row) {
				$ar_address = @explode("|", $row->getDeliveryAddress());
				$row->setDeliveryAddress1($ar_address[0]);
				if (empty($ar_address[1]) && empty($ar_address[2])) {
					$row->setDeliveryAddress2('NA');
				} else {
					$row->setDeliveryAddress2(implode(" ", array($ar_address[1], $ar_address[2])));
				}
				if (!$row->getDeliveryCity()) {
					$row->setDeliveryAddress3('NA');
				} else {
					$row->setDeliveryAddress3($row->getDeliveryCity());
				}
				$row->setQty(1);
				$row->setProdWeight($row->getProdWeight() * $row->getQty());
				$row->setPrice($row->getAmount());
				if ($row->getTel() == "") {
					$row->setTel("0");
				}
				if ($row->getDeliveryCompany() == "") {
					$row->setDeliveryCompany($row->getDeliveryName());
				}
				$prod_obj = $this->getDao('Product')->get(["sku" => $row->getProdSku()]);
				$declared_value = $this->soService->getDeclaredValue($prod_obj, $row->getDeliveryCountryId(), $row->getAmount());
				$declared_value = $declared_value * $row->getRateToHkd();
				$declared_value = number_format($declared_value, 2, '.', '');
				$row->setDeclaredValue($declared_value);

				$data_out = $row;
				$counter++;
			}

			return $data_out;
		}
	}

	public function getSgpCourierFeed($value)
	{
		if ($arr = $this->getShipmentDeliveryInfoDhl($value)) {
			foreach ($arr as $row) {
				$ar_address = @explode("|", $row->getDeliveryAddress());
				$row->setDeliveryAddress1($ar_address[0]);
				$row->setDeliveryAddress2($ar_address[1]);
				$row->setDeliveryAddress3($ar_address[2]);
				$delivery_city = $row->getDeliveryCity();
				$delivery_city = str_replace(array("\n","\r\n","\r",",")," ", $delivery_city);
				$row->setDeliveryCity($delivery_city);
				$amount = $row->getSumItemAmount() + $row->getDeliveryCharge() + $row->getVat() - $row->getDiscount();
				$row->setTotalAmount($amount);
				$data_out = $row;
			}

			return $data_out;
		}
	}

	public function getChronopostFranceCourierFeed($value)
	{
		if ($arr = $this->getShipmentDeliveryInfoDhl($value)) {
			foreach ($arr as $row) {
				$ar_address = @explode("|", $row->getDeliveryAddress());
				$row->setDeliveryAddress1($ar_address[0]);
				$row->setDeliveryAddress2($ar_address[1]);
				if ($row->getDeliveryAddress2() == "") {
					$row->setDeliveryAddress2(".");
				}

				$prod_obj = $this->getDao('Product')->get(["sku" => $row->getProdSku()]);
				$declared_value = $this->soService->getDeclaredValue($prod_obj, $row->getDeliveryCountryId(), $row->getPrice());

				$row->setDeclaredValue(round($declared_value*$row->getRate(), 2));
				$data_out = $row;
				$counter++;
			}

			return $data_out;
		}
	}

	public function getB2cCourierFeed($value)
	{
		if ($arr = $this->getShipmentDeliveryInfoDhl($value)) {
			foreach ($arr as $row) {
				$ar_address = @explode("|", $row->getDeliveryAddress());
				$weight = $row->getProdWeight();
				$weight = $weight*1000;
				$row->setProdWeight($weight);
				$row->setDeliveryAddress1($ar_address[0]);
				$row->setDeliveryAddress2($ar_address[1]);
				$barcode = 'PT'.$row->getSoNo();
				$row->setBarcode($barcode);
				$amount = round($this->convertCurrency($row->getCurrencyId(), 'EUR', $row->getAmount()), 2);
				$row->setAmount($amount);
				$data_out = $row;
				$counter++;
			}

			return $data_out;
		}
	}

	public function getDpdNlCourierFeed($value)
	{
		if ($arr = $this->getShipmentDeliveryInfoDhl($value)) {
			foreach ($arr as $row) {
				$ar_address = @explode("|", $row->getDeliveryAddress());
				if ($ar_address[0] == '') {
					$ar_address[0] = '.';
				}
				if ($ar_address[1] == '') {
					$ar_address[1] = '.';
				}
				$row->setDeliveryAddress2($ar_address[1]);
				$row->setDeliveryAddress1($ar_address[0]);
				$row->setShippingDate(date('d.m.Y'));

				$row->setProdWeight($row->getProdWeight() * $row->getQty());

				if ($row->getTel() == "") {
					$row->setTel(".");
				}

				if ($row->getDeliveryPostcode() == "") {
					$row->setDeliveryPostcode(".");
				}

				$delivery_country_id = $row->getDeliveryCountryId();

				if (!in_array($delivery_country_id, array('FR', 'NL'))) {
					$row->setDeliveryCountryId2('EN');
				} else {
					$row->setDeliveryCountryId2($delivery_country_id);
				}

				$data_out = $row;
				$counter++;
			}

			return $data_out;
		}
	}

	public function getDpdUkCourierFeed($value)
	{
		if ($arr = $this->getShipmentDeliveryInfoDhl($value)) {
			foreach ($arr as $row) {
				$ar_address = @explode("|", $row->getDeliveryAddress());
				$row->setDeliveryAddress1($ar_address[0]);

				if (empty($ar_address[1]) && empty($ar_address[2])) {
					$row->setDeliveryAddress2('.');
				} else {
					$row->setDeliveryAddress2(implode(" ", array($ar_address[1], $ar_address[2])));
				}

				if (!$row->getDeliveryCity()) {
					$row->setDeliveryAddress3('.');
				} else {
					$row->setDeliveryAddress3($row->getDeliveryCity());
				}

				$row->setQty($row->getQty());
				$row->setProdWeight($row->getProdWeight() * $row->getQty());
				$row->setPrice($row->getAmount());

				if ($row->getTel() == "") {
					$row->setTel("0");
				}

				if ($row->getDeliveryCompany() == "") {
					$row->setDeliveryCompany($row->getDeliveryName());
				}

				if ($row->getDeliveryCountryId() == 'AU') {
					$valid_city_arr = array("brisbane", "melbourne", "perth", "sydney");
					if (trim($row->getDeliveryCity()) == "" || !in_array(trim(strtolower($row->getDeliveryCity())), $valid_city_arr)) {
						$row->setDeliveryCity('Australia Other');
					}
				}

				$prod_obj = $this->getDao('Product')->get(["sku" => $row->getProdSku()]);
				$declared_value = $this->soService->getDeclaredValue($prod_obj, $row->getDeliveryCountryId(), $row->getPrice());
				$row->setDeclaredValue(round($declared_value * $row->getRate(), 2));

				$data_out = $row;
				$counter++;
			}

			return $data_out;
		}
	}

	public function getTollCourierFeed($value)
	{
		if ($arr = $this->getShipmentDeliveryInfoDhl($value)) {
			foreach ($arr as $row) {
				$ar_address = @explode("|", $row->getDeliveryAddress());
				$row->setDeliveryAddress1($ar_address[0]);
				if (empty($ar_address[1]) && empty($ar_address[2])) {
					$row->setDeliveryAddress2('NA');
				} else {
					$row->setDeliveryAddress2(implode(" ", array($ar_address[1], $ar_address[2])));
				}
				if (!$row->getDeliveryCity()) {
					$row->setDeliveryAddress3('NA');
				} else {
					$row->setDeliveryAddress3($row->getDeliveryCity());
				}
				$row->setQty($row->getQty());
				$row->setProdWeight($row->getProdWeight() * $row->getQty());
				$row->setPrice($row->getAmount());
				if ($row->getTel() == "") {
					$row->setTel("0");
				}
				if ($row->getDeliveryCompany() == "") {
					$row->setDeliveryCompany($row->getDeliveryName());
				}
				if ($row->getDeliveryCountryId() == 'AU') {
					$valid_city_arr = array("brisbane", "melbourne", "perth", "sydney");
					if (trim($row->getDeliveryCity()) == "" || !in_array(trim(strtolower($row->getDeliveryCity())), $valid_city_arr)) {
						$row->setDeliveryCity('Australia Other');
					}
				}

				$prod_obj = $this->getDao('Product')->get(["sku" => $row->getProdSku()]);
				$declared_value = $this->soService->getDeclaredValue($prod_obj, $row->getDeliveryCountryId(), $row->getPrice());
				$row->setDeclaredValue(round($declared_value * $row->getRate(), 2));

				if ($country_obj = $this->getDao('Country')->get(["country_id" => $row->getDeliveryCountryId()])) {
					$country_name = $country_obj->getName();
					$row->setDeliveryCountryId($country_name);
				}
				$data_out = $row;
				$counter++;
			}

			return $data_out;
		}
	}

	public function setDhlGlobalMailCourierFeed($row)
	{
		$ar_address = @explode("|", $row->getDeliveryAddress());
		$row->setDeliveryAddress1($ar_address[0]);
		if (!empty($ar_address[1]))
		{
			$row->setDeliveryAddress2($ar_address[1]);
		}
		if (!empty($ar_address[2]))
		{
			$row->setDeliveryAddress3($ar_address[2]);
		}

		$row->setProdWeight($row->getProdWeight() * $row->getQty());

		$amount = round($this->convertCurrency($row->getCurrencyId(), 'USD', $row->getAmount()), 2);
		$row->setAmount($amount);
		$row->setQty(1);
		$row->setPtSoNo("PT".$row->getSoNo());

		if($amount>20){
			$row->setDeclaredValue(19.01);
		}else{
			$row->setDeclaredValue($amount);
		}
	}

	public function getA2BCourierFeed($value, $i = 1)
	{
		if ($arr = $this->getShipmentDeliveryInfoDhl($value)) {
			foreach ($arr as $row) {
				$row->setItemNo($i);
				$ar_address = @explode("|", $row->getDeliveryAddress());
				$row->setDeliveryAddress1($ar_address[0]);
				$row->setDeliveryAddress2($ar_address[1]);
				$barcode = 'ABESGPT'.$row->getSoNo();
				$row->setBarcode($barcode);
				$prod_obj = $this->getDao('Product')->get(["sku" => $row->getProdSku()]);
				$declared_value = $this->soService->getDeclaredValue($prod_obj, $row->getDeliveryCountryId(), $row->getPrice());
				$declared_value = $declared_value * $row->getRate();
				if ($declared_value < 60 or $declared_value > 80) {
					$declared_value = rand(6000, 8000)/100;
				}
				$row->setDeclaredValue(round($declared_value, 2));
				$row->setCategoryName('Refurbished mobile phone');
				$data_out = $row;
				$counter++;
			}
			return $data_out;
		}
	}

	public function getRPXCourierFeed($value, $i = 1)
	{
		if ($arr = $this->getShipmentDeliveryInfoDhl($value)) {
			foreach ($arr as $row) {
				$row->setItemNo($i);
				$ar_address = @explode("|", $row->getDeliveryAddress());
				$row->setDeliveryAddress1($ar_address[0]);
				$row->setDeliveryAddress2($ar_address[1]);
				$barcode = 'PT'.$row->getSoNo();
				$row->setBarcode($barcode);
				$prod_obj = $this->getDao('Product')->get(["sku" => $row->getProdSku()]);
				$declared_value = $this->soService->getDeclaredValue($prod_obj, $row->getDeliveryCountryId(), $row->getPrice());
				$declared_value = $declared_value * $row->getRate();
				$declared_value1 = $row->getAmount() * $row->getRef1();
				$category_name = $row->getCategoryName();
				$weight = $row->getWeight();
				if (in_array($category_name, array('Lenses', 'Laptops', 'Tablets', 'Digital Cameras', 'Electronic toys')) && $declared_value > 1000 && $weight > 2) {
					$declared_value = 70;
				} else {
					if ($declared_value < 40 or $declared_value > 49) {
						$declared_value = rand(4000, 4900)/100;
					}
				}
				$row->setDeclaredValue(round($declared_value, 2));
				$data_out = $row;
				$counter++;
			}

			return $data_out;
		}
	}

	public function setFedexCourierFeed($row)
	{
		$ar_address = @explode("|", $row->getDeliveryAddress());
		$row->setDeliveryAddress1($ar_address[0]);
		$row->setDeliveryAddress2($ar_address[1]);
	}

	public function setDefaultCourierFeed($row)
	{
		$row->setTotalItemCount($no_of_line);
		$row->setItemNo($counter);
		$row->setSubtotal(number_format($row->getUnitPrice() * $row->getQty(), 2, '.', ''));
		$row->setActualCost(number_format($row->getAmount() - $row->getOfflineFee(), 2, '.', ''));
		$row->setBillDetail('N'); // Always 'N' at the beginning.

		if ($counter > 1) {
			$row->setShipOption('');
			$row->setDeliveryCharge(0.00);
			$row->setPromotionCode('');
			$row->setAmount(0.00);
			$row->setDeliveryTypeId('');
			$row->setActualCost(0.00);
		}
	}

	public function convertCurrency($original_currency, $new_currency, $original_value)
	{
		$rate = $this->exchangeRateService->getExchangeRate($original_currency, $new_currency)->getRate();
		return $rate * $original_value;
	}

	public function writeFile($file_content, $courier)
	{
		if ( !empty($file_content) ) {
			$filename = "so_delivery_{$courier}_" . date("YmdHis");

			$output_path = $this->getDao('Config')->valueOf('courier_path');
			$path = $output_path;

			//create file for dispatch list import
			if ($courier == "IM" || $courier == "RMR") {
				$dispatch_path = $this->getDao('Config')->valueOf('dispath_list_path');
				$this->createFolder($dispatch_path, date('Y'), date('F'));
				$dispatch_filename = $courier . "_" . $filename . ".csv";
				$dispatch_path = $dispatch_path . date('Y') . "/" . date('F') . "/" . $courier . "/";
				if ($fp = @fopen($dispatch_path . $dispatch_filename, 'w')) //              if ($fp = @fopen($path . $dispatch_filename, 'w'))
				{
					@fwrite($fp, $dispatch_content);
					@fclose($fp);
				}
			}

			if ($courier == "MRW") {
				$filename = "IADI_00826_1528_";
				$filename .= date('YmdHis');
				$filename .= ".csv";
			}
			elseif ( in_array($courier, array("dhl-global-mail", "chronopost-france", 'RPX', 'A2B')) )
			{
				$filename .= ".csv";
			}
			else
			{
				$filename .= ".txt";
			}

			if ($fp = @fopen($path . $filename, 'w')) {
				@fwrite($fp, $file_content);
				@fclose($fp);

				return $filename;
			}
		}
	}

	public function getShipmentDeliveryInfoDhl($so_no)
	{
		return $this->getDao('So')->getShipmentDeliveryInfoDhl($so_no);
	}

	public function getShipmentDeliveryInfoCourier($so_no)
	{
		return $this->getDao('So')->getShipmentDeliveryInfoCourier($so_no);
	}

	public function getShipmentDeliveryInfoCourierForTnt($so_no)
	{
		return $this->getDao('So')->getShipmentDeliveryInfoCourierForTnt($so_no);
	}

	public function getShipmentDeliveryInfo($so_no = 'SO000001', $classname = 'ShipmentInfoToCourierDto')
	{
		return $this->getDao('So')->getShipmentDeliveryInfo($so_no, $classname);
	}

	private function createFolder($upload_path, $this_year, $this_month)
	{
		$full_path = $upload_path . $this_year;
		if (!file_exists($upload_path))
		{
			mkdir($upload_path, 0775);
		}
		if (!file_exists($full_path))
		{
			mkdir($full_path, 0775);
		}
		$full_path = $upload_path . $this_year . "/" . $this_month;
		if (!file_exists($full_path))
		{
			mkdir($full_path, 0775);
		}
		$full_path = $upload_path . $this_year . "/" . $this_month . "/AMS" ;
		if (!file_exists($full_path))
		{
			mkdir($full_path, 0775);
		}
		$full_path = $upload_path . $this_year . "/" . $this_month . "/ILG" ;
		if (!file_exists($full_path))
		{
			mkdir($full_path, 0775);
		}
		$full_path = $upload_path . $this_year . "/" . $this_month . "/IM" ;
		if (!file_exists($full_path))
		{
			mkdir($full_path, 0775);
		}
		$full_path = $upload_path . $this_year . "/" . $this_month . "/RMR" ;
		if (!file_exists($full_path))
		{
			mkdir($full_path, 0775);
		}
	}
}
