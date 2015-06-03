<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Data_feed_service.php";

class Competitor_price_mapping_service
{
	private $notification_email = "itsupport@eservicesgroup.net";

	public function __construct()
	{
		// parent::Data_feed_service();
		include_once(APPPATH . 'libraries/service/Price_service.php');
		$this->set_price_srv(New Price_service());
		include_once(APPPATH."libraries/service/Context_config_service.php");
		$this->set_config_srv(new Context_config_service());
		include_once(APPPATH . 'libraries/service/Competitor_map_service.php');
		$this->set_competitor_map_service(New Competitor_map_service());
		include_once(APPPATH . 'libraries/service/Competitor_service.php');
		$this->set_competitor_service(New Competitor_service());
		include_once (APPPATH."libraries/dao/Competitor_map_dao.php");
		$this->set_competitor_map_dao(new Competitor_map_dao());
	}

	public function get_price_srv()
	{
		return $this->price_srv;
	}

	public function set_price_srv(Base_service $srv)
	{
		$this->price_srv = $srv;
	}

	public function get_config_srv()
	{
		return $this->config_srv;
	}

	public function set_config_srv(Base_service $srv)
	{
		$this->config_srv = $srv;
	}

	public function get_competitor_map_service()
	{
		return $this->competitor_map_service;
	}

	public function set_competitor_map_service(Base_service $srv)
	{
		$this->competitor_map_service = $srv;
	}

	public function get_competitor_service()
	{
		return $this->competitor_service;
	}

	public function set_competitor_service(Base_service $srv)
	{
		$this->competitor_service = $srv;
	}

	public function get_competitor_map_dao()
	{
		return $this->competitor_map_dao;
	}

	public function set_competitor_map_dao(Base_dao $value)
	{
		$this->competitor_map_dao = $value;
	}


	public function process_mapping_file($country_id = "GB", $debug_filename = "")
	{
		# to echo file output, put filename in question sans file extension into $debug_filename
		$this->country_id = $country_id;
		$report_content = array();
		$competitor_map_srv = $this->get_competitor_map_service();
		$competitor_srv = $this->get_competitor_service();
		$competitor_map_dao = $this->get_competitor_map_dao();

		# first copy all now_price on competitor_map tb to last_price so we can track and compare
		if($update_last_price = $this->get_competitor_map_service()->update_last_price($country_id))
		{
			echo "<pre>DEBUG: <$country_id> Update last price success</pre>";
			define('DATAPATH', $this->get_config_srv()->value_of("competitor_mapping_path"));
			require_once(BASEPATH.'plugins/csv_parser_pi.php');

			# sort files in the directory by earliest date modified first, in case duplicated files cause overwriting of latest info
			if($sorted_list = $this->list_files_by_date())
			{
				foreach ($sorted_list as $key => $filename)
				{
					$info = explode(',', $key);
					$srcfile_timestamp = date('Y-m-d H:i:s', $info[0]+28800); #file upload time

					#headers for each file
					$report_content[] = array("master_sku", "product_name", "competitor_url", "last_price", "now_price", "diff(%)", "stock_status");
					echo "<pre>DEBUG: Processing file - $filename</pre>";

					# first copy each file to archive with timestamp
					if($archive = $this->copy_file_to_archive($filename))
					{
						echo "<pre>DEBUG: Copy to archive success</pre>";
						$csvfile = new CSVFileLineIterator($filename["path"]);

						if($arr = csv_parse($csvfile))
						{
							$change = 0;

							// prod_name				prod_url	 														price 		ext_name
							// Nikon D5100 Body Only 	http://www.eglobaldigitalstore.co.uk/nikon-d5100-body-only.html		278.99 		sferaufficio
							foreach ($arr as $col)
							{
								$prod_name 	= $col[0];
								$prod_url 	= $col[1];
								$price 		= $col[2];

								// if competitor is price comparison site, e.g. trovaprezzi_IT,
								// then col D will have the name of competitor with lowest price
								$ext_name 	= $col[3];

								# SBF#3110
								$stock_status = $col[4];
								if($stock_status == "") $stock_status = 0;

								switch ($stock_status)
								{
									case 0:
										$status = "In Stock";
										break;

									case 1:
										$status = "Out of Stock";
										break;

									case 2:
										$status = "Pre-order";
										break;

									case 3:
										$status = "Arriving";
										break;

									default:
										break;
								}

								// we map uploaded file and competitor_map by their product URLs,
								// ignore product if no match
								if($list_to_update = $competitor_map_dao->get_list_by_url($prod_url, $country_id))
								{

									foreach ($list_to_update as $key => $competitor_map)
									{
										echo "<hr></hr><pre>DEBUG: competitor_map exists. ext_sku = {$competitor_map->get_ext_sku()}; competitor_map.id = {$competitor_map->get_id()}; </pre><pre>prod_url = '$prod_url'; </pre>";
										echo "<pre>last_price = {$competitor_map->get_last_price()} || now_price = $price. </pre>";

										# if current now_price and the new uploaded price different, then update to database
										if($price !== $competitor_map->get_now_price() || $ext_name !== $competitor_map->get_note_1())
										{
											$competitor_map->set_now_price($price);
											$competitor_map->set_note_1($ext_name);
											$competitor_map->set_comp_stock_status($stock_status);

										}

										if($srcfile_timestamp !== FALSE)
										{
											echo "Uploaded time: $srcfile_timestamp";
											$competitor_map->set_sourcefile_timestamp($srcfile_timestamp);
										}

										if(($competitor_map_dao->update($competitor_map))===FALSE)
										{
											$error_msg = __FILE__." Line " .__LINE__. ". \nError updating now_price/note_1 with uploaded info in competitor_map tb. \nDB Error msg: " . $competitor_map_dao->db->_error_message() . "\next_sku: {$competitor_map->get_ext_sku()}; competitor_id: {$competitor_map->get_competitor_id()}; uploaded price: $price";

											echo "<pre>### DEBUG: Failure to update competitor_map ###</pre>";
											$this->send_notification_email("UNP", $error_msg);
											continue;
										}
										else
										{
											echo "<pre>competitor_map updated </pre>";
										}

										$master_sku = $competitor_map->get_ext_sku();
										$last_price = $competitor_map->get_last_price();

										if($last_price == 0 || $last_price == "")
										{
											$diff = "";
										}
										else
										{
											$diff = number_format(((($price-$last_price)/$last_price)*100), 2);

											if($diff != 0)
												$change++;
										}

										echo "<pre>DEBUG DIFF% $diff</pre>";

										$search = array("\r\n", "\n", "\r", ",");
										$prod_name = str_replace($search, "", $prod_name);
										$prod_url = str_replace($search, "", $prod_url);

										# store each data row for report output
										$report_content[] = array($master_sku, $prod_name, $prod_url, $last_price, $price, $diff, $status);
									}
								}
								else
								{
									echo "<hr></hr><pre>DEBUG: competitor_map does NOT exist for prod_url = '$prod_url'. </pre>";
								}
							}

							# write all data into report
							$name = substr($filename["filename"], 0, -4); # remove file extension
							$report_name = "report_{$name}_" .date('Ymdhis'). '.csv';
							$this->change = $change;
							echo "<hr></hr><pre>DEBUG: total changes: $change</pre>";

							$fp = fopen(DATAPATH. "archive/$country_id/$report_name", 'w');

							foreach ($report_content as $fields)
							{
								if(fputcsv($fp, $fields) === FALSE)
								{
									$error_fields = implode(',', $fields);
									$error_msg = __FILE__." Line " .__LINE__. "Error writing data to file. \nFile: ".DATAPATH. "archive/$country_id/{$report_name}"."\nfields: $error_fields";

									$this->send_notification_email("CSV", $error_msg);
									echo "<pre>DEBUG: $error_msg</pre>";
									break;
								}
							}
							unset($report_content);
							fclose($fp);

							# checks if report exists before send email
							$written_report = DATAPATH. "archive/$country_id/$report_name";
							if (file_exists($written_report))
							{
								if($debug_filename == $name)
								{
									$content = file_get_contents($written_report);
									header("Cache-Control: no-store, no-cache");
									header("Content-Disposition: attachment; filename=\"$report_name\"");
									echo $content;
								}
								else
								{
									$this->send_report_email($written_report, $name);
								}
							}
						}
						else
						{
							$error_msg = __FILE__." Line " .__LINE__. "Error parsing csv file. \nFile: " . $filename["path"];

							$this->send_notification_email("CSV", $error_msg);
							echo "<pre>DEBUG: $error_msg</pre>";
							continue;
						}
					}
					else
					{
						$error_msg = __FILE__." Line " .__LINE__. "Error copying file to archive. \nSource file: " . $filename["path"];

						$this->send_notification_email("CSV", $error_msg);
						echo "<pre>DEBUG: $error_msg</pre>";
						continue;
					}
				}
			}
			else
			{
				// if no files found in the country folder
				$this->send_report_email("","",1);
			}
		}
	}

	private function send_report_email($written_report="", $competitor_name="", $noreport=0)
	{
		include_once(BASEPATH . "plugins/phpmailer/phpmailer_pi.php");
		$phpmail = new phpmailer();

		$country_id = $this->country_id;
		$phpmail->IsSMTP();
		$phpmail->From = "Admin <admin@valuebasket.net>";
		// $phpmail->AddAddress("itsupport@eservicesgroup.net");

		switch ($country_id)
		{
			case 'GB':
				/*
				$phpmail->AddAddress("edward@valuebasket.com");
				$phpmail->AddAddress("jonathan@eservicesgroup.com");
				$phpmail->AddAddress("ming@eservicesgroup.com");
				*/
				$phpmail->AddAddress("Spiderman-uk@valuebasket.com");

				break;

			case 'IT':
				/*
				$phpmail->AddAddress("davide.pecoraro@eservicesgroup.com");
				$phpmail->AddAddress("rod@eservicesgroup.com");
				$phpmail->AddAddress("edward@valuebasket.com");
				*/
				$phpmail->AddAddress("valuebasket-it@valuebasket.com");
				break;

			case 'ES':
				/*
				$phpmail->AddAddress("gonzalo@eservicesgroup.com");
				$phpmail->AddAddress("rod@eservicesgroup.com");
				$phpmail->AddAddress("edward@valuebasket.com");
				$phpmail->AddAddress("paula.garcia@eservicesgroup.com");
				$phpmail->AddAddress("ivan.pooh@eservicesgroup.com");
				$phpmail->AddAddress("jay.singh@eservicesgroup.com");
				*/
				$phpmail->AddAddress("valuebasket-es@valuebasket.com");
				break;

			case 'FR':
				/*
				$phpmail->AddAddress("aymeric@eservicesgroup.com");
				$phpmail->AddAddress("eiffel@eservicesgroup.com");
				$phpmail->AddAddress("rod@eservicesgroup.com");
				$phpmail->AddAddress("edward@valuebasket.com");
				$phpmail->AddAddress("romuald@eservicesgroup.com");
				$phpmail->AddAddress("celine@eservicesgroup.com");
				*/
				$phpmail->AddAddress("Spiderman-fr@valuebasket.com");
				break;

			case 'AU':
				$phpmail->AddAddress("alex@eservicesgroup.com");
				$phpmail->AddAddress("louis@eservicesgroup.com");
				$phpmail->AddAddress("edward@valuebasket.com");
				break;

			case 'NZ':
				$phpmail->AddAddress("alex@eservicesgroup.com");
				$phpmail->AddAddress("louis@eservicesgroup.com");
				$phpmail->AddAddress("edward@valuebasket.com");
				$phpmail->AddAddress("lester.chan@eservicesgroup.com");
				break;

			case 'RU':
				$phpmail->AddAddress("zina.rakto@eservicesgroup.com");
				$phpmail->AddAddress("edward@eservicesgroup.com");
				break;

			case 'MY':
				$phpmail->AddAddress("louis@eservicesgroup.net");
				$phpmail->AddAddress("alex@eservicesgroup.net");
				$phpmail->AddAddress("edward@eservicesgroup.net");
				$phpmail->AddAddress("lester.chan@eservicesgroup.com");
				break;

			case 'SG':
				$phpmail->AddAddress("louis@eservicesgroup.net");
				$phpmail->AddAddress("alex@eservicesgroup.net");
				$phpmail->AddAddress("edward@eservicesgroup.net");
				$phpmail->AddAddress("lester.chan@eservicesgroup.com");
				break;

			default:
				break;
		}

		if($noreport == 0)
		{
			if($written_report && $competitor_name)
			{
				$phpmail->Subject = "<$competitor_name - $country_id> Pricing Alert with {$this->change} change(s)";
				$phpmail->AddAttachment($written_report, "report_{$competitor_name}.csv");

				$raw_file = DATAPATH . "archive/{$country_id}/{$this->raw_filename}";
				$phpmail->AddAttachment($raw_file, "{$this->raw_filename}");

				$phpmail->IsHTML(false);
				$text = "Pricing Alert with {$this->change} change(s) \r\nfile path: $written_report\r\nraw_file: $report $raw_file\r\n[report type: competitor_price_mappping_service]";
				$phpmail->Body = $text;

				switch ($competitor_name)
				{
					case 'rakutenes_ES_purnima':
					case 'rakutenes_ES_pcmatica':
						$phpmail->AddAddress("rakutenalert@valuebasket.com");
						break;
					default:
						break;
				}

				$result = $phpmail->Send();
			}
			else
			{
				$error_msg = "Line " .__LINE__. "Incorrect/insufficient parameters -
								\nwritten_report path: <$written_report> \ncompetitor_name: <$competitor_name> country_id: <$country_id>";
				$this->send_notification_email("EE", $error_msg);
				echo "<pre>DEBUG: $error_msg</pre>";
				break;
			}
		}
		else
		{
			$country_id = $this->country_id;
			$phpmail->Subject = "<$country_id> No Files for Competitor Price Mapping";
			$phpmail->IsHTML(false);
			$text = "No competitor files found in <$country_id> folder to process price mapping.";
			$phpmail->Body = $text;

			$result = $phpmail->Send();
		}
	}

	private function copy_file_to_archive($file = array())
	{
		// don't copy to archive
		return TRUE;


		$country_id = $this->country_id;
		if($country_id && $file["filename"])
		{
			$srcfile_path = $file["path"];

			$destfile_name = substr($file["filename"], 0, -4); 	#remove file ext
			$this->raw_filename = "raw_{$destfile_name}_".date('Ymdhis').'.csv';
			$destfile_path = DATAPATH."archive/{$country_id}/{$this->raw_filename}";

			if(file_exists($srcfile_path))
			{
				if(copy($srcfile_path, $destfile_path))
				{
					return TRUE;
				}
			}
		}

		return FALSE;
	}

	private function list_files_by_date()
	{
		$list = array();
		$directoryPath = array(DATAPATH . $this->country_id . '/', DATAPATH . "external_spider_supplier/" . $this->country_id . '/');
		for ($i=0;$i<2;$i++)
		{
			$dir = $directoryPath[$i];
			print $dir;
			if (is_dir($dir))
			{
				if ($dh = opendir($dir))
				{
					while (($file = readdir($dh)) !== false)
					{
						if ($file != '.' and $file != '..')
						{
							$uploadtime = filectime($dir.$file) . ',' . $file;
							$list[$uploadtime] = array("filename" => $file, "path" => $directoryPath[$i] . $file);
						}
					}
					closedir($dh);
					ksort($list);
				}
			}
			else
			{
				$error_msg = "Problem accessing directory <$dir>. Csv mapping not completed.";
				$this->send_notification_email("FE", $error_msg);
				echo "<pre>DEBUG: $error_msg</pre>";
				return FALSE;
			}
		}
		return $list;
	}

	private function send_notification_email($error_type, $error_msg="")
	{
		include_once(BASEPATH . "plugins/phpmailer/phpmailer_pi.php");
		$phpmail = new phpmailer();
		$phpmail->IsSMTP();
		$phpmail->From = "Admin <admin@valuebasket.net>";
		$it_email = $this->notification_email;

		$website = 'valuebasket';
		$country_id = $this->country_id;
		if($country_id)
		{
			switch ($error_type)
			{
				case "CSV":
					$message = $error_msg."\r\n[report type: competitor_price_mappping_service]";
					$title = "CSV_PROCESS_ERROR - Competitor price csv mapping [$country_id - $website]";
					break;

				case "EE":
					$message = $error_msg."\r\n[report type: competitor_price_mappping_service]";
					$title = "EMAIL_ERROR - Competitor price csv mapping [$country_id - $website]";
					break;

				case "FE":
					$message = $error_msg."\r\n[report type: competitor_price_mappping_service]";
					$title = "GET_FILE_ERROR - Competitor price csv mapping [$country_id - $website]";
					// $phpmail->AddAddress($it_email);
					break;

				case "UNP":
					$message = $error_msg."\r\n[report type: competitor_price_mappping_service]";
					$title = "UPDATE_NOW_PRICE_ERROR - Competitor price csv mapping [$country_id - $website]";
					$phpmail->AddAddress($it_email);
					break;

				case "ULP":
					$message = $error_msg."\r\n[report type: competitor_price_mappping_service]";
					$title = "UPDATE_LAST_PRICE_ERROR - Competitor price csv mapping [$country_id - $website]";
					$phpmail->AddAddress($it_email);
					break;

			}

			$phpmail->AddAddress("edward@valuebasket.com");
			// mail($this->notification_email, $title, $message);

			$phpmail->Subject = "$title";
			$phpmail->IsHTML(false);
			$phpmail->Body = $message;

			$result = $phpmail->Send();
		}
	}

}