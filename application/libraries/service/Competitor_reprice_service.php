<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Competitor_reprice_service
{
	private $notification_email = "itsupport@eservicesgroup.net";
	private $reprice_count;

	public function __construct()
	{
		// parent::Data_feed_service();
		include_once(APPPATH . 'libraries/service/Price_service.php');
		$this->set_price_srv(New Price_service());
		include_once(APPPATH . 'libraries/service/Product_service.php');
		$this->set_product_srv(New Product_service());
		include_once(APPPATH."libraries/service/Product_update_followup_service.php");
		$this->set_product_update_followup_service(new Product_update_followup_service());

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

	public function get_product_srv()
	{
		return $this->product_srv;
	}

	public function set_product_srv(Base_service $srv)
	{
		$this->product_srv = $srv;
	}

	public function get_product_update_followup_service()
	{
		return $this->product_update_followup_service;
	}

	public function set_product_update_followup_service(Base_service $svc)
	{
		$this->product_update_followup_service = $svc;
	}

	public function get_competitor_map_dao()
	{
		return $this->competitor_map_dao;
	}

	public function set_competitor_map_dao(Base_dao $value)
	{
		$this->competitor_map_dao = $value;
	}

	#SBF #3328
	public function reprice($platform_id, $echo_file=0, $debug_sku="")
	{
		# echo_file = 0 : will do actual reprice and send report emails
		# echo_file = 1 : debug; prompt csv report download; no reprice done
		# echo_file = 2 : debug;  echo debug msg in browser; no reprice done

		require_once(BASEPATH.'plugins/csv_parser_pi.php');

		$country_id = $this->country_id = substr($platform_id, -2);
		$competitor_map_dao = $this->get_competitor_map_dao();
		$price_srv = $this->get_price_srv();
		$product_srv = $this->get_product_srv();

		$debug_msg = "<font face=\"arial\" size='1'><br><b>Platform_id: $platform_id</b><br> (if SKU not found here, pls check price listing_status, auto price status, competitor status, competitor_map status, competitor_map match.)<br>";
		if($echo_file)
		{
			$debug_msg .= "
				<font color=\"red\">DEBUG MODE; no repricing done.</font> <br><br>
				=========================================================<br>
				REPRICE STATUS LEGEND: (SBF #3328)<br>
				<ul>
					<li><b>CURLMA:</b> competitor url mapping admin</li>
					<li><b>[[reprice_min_margin not met]]:</b> (VB selling price - reprice_value) is lower than reprice_min_margin on CURLMA. </li>
					<li><b>[[last_now_diff_fail (range +/- 2%)]]:</b> Competitor's last_price & now_price change is more than -/+2%; suspicious.</li>
					<li><b>[[Not processed; comp_last_price = 0]]:</b> Competitor last_price is 0; recently added.</li>
					<li><b>[[Failed criteria: <conditions>]]:</b> Cannot reprice due to stated failed conditions on SBF.</li>
					<li><b>::REPRICED:</b> SKU will be repriced with new_price based on competitor mentioned. new_margin will be VB profit margin based on new_price</li>
					<li><b>islowest / isNOTlowest:</b> Tells you if SKU has a lower-priced competitor after new_price.</li>
					<li><b>[[reprice_success_no_change]]:</b> Repricing value based on specified competitor is same as VB selling price, no changes made.</li>
				</ul><br>
				=========================================================<br><br>
					";
		}

		$rpt_reprice = array();
		$rpt_reprice[0] = $rpt_header = array("master_sku", "sku", "product_name", "old_selling_price", "old_profit_margin(%)", "new_selling_price", "new_profit_margin(%)", "matched_competitor", "competitor_price", "vb_islowest", "report_status");  // csv report headers

		if($comp_reprice_list = $competitor_map_dao->get_reprice_compmap_list_by_platform($platform_id, 'Y'))
		{
			/**************
				get_compmap_list_by_platform() condition:
				- price.listing_status='Listed'
				  && competitor_reprice='Reprice'
				  && competitor_map.match =1
			**************/

			$total_price_list = $comp_obj = array();
			foreach ($comp_reprice_list as $sku => $arr)
			{
				/* this portion sorts competitor's total price (selling_price + shipping) per sku
				per platform in ascending order */

				foreach ($arr as $comp_reprice_obj)
				{
					$competitor_id = $comp_reprice_obj->get_competitor_id();
					$comp_last_price = number_format(($comp_reprice_obj->get_last_price()), 2, '.', '');
					$comp_now_price = number_format(($comp_reprice_obj->get_now_price()), 2, '.', '');
					$comp_ship_charge = number_format(($comp_reprice_obj->get_comp_ship_charge()), 2, '.', '');

					# Group them by SKU
					$total_price_list[$sku][$competitor_id] = number_format(($comp_now_price + $comp_ship_charge), 2, '.', '');

					$comp_obj[$sku][$competitor_id] = $comp_reprice_obj;

				}
				asort($total_price_list[$sku]);
			}

			$reprice_count = 0;
			foreach ($total_price_list as $sku => $total_price_arr)
			{
				# loop by SKU
				$completed = $find_next = FALSE;
				$compare = array();
				$arr_count = count($total_price_arr);
				$i = 0;

				if($debug_sku){if($sku !== $debug_sku) continue;}
				foreach ($total_price_arr as $competitor_id => $total_price)
				{
					# loop through each competitor in each SKU
					$i++;
					$new_margin = $platform_margin = $new_price = "";
					$last_rpt_arr_key = count($rpt_reprice, 0) - 1; #get last array key to insert vb_islowest status
					$comp_reprice_obj = $comp_obj[$sku][$competitor_id];

					$competitor_name = $comp_reprice_obj->get_competitor_name();
					$country_id = $comp_reprice_obj->get_country_id();
					$match = $comp_reprice_obj->get_match();
					$prod_url = $comp_reprice_obj->get_product_url();
					$note_1 = $comp_reprice_obj->get_note_1();
					$note_2 = $comp_reprice_obj->get_note_2();
					$comp_stock_status = $comp_reprice_obj->get_comp_stock_status();
					$reprice_min_margin = (float)number_format($comp_reprice_obj->get_reprice_min_margin(),2,'.','');
					$reprice_value = $comp_reprice_obj->get_reprice_value();
					$ext_sku = $comp_reprice_obj->get_ext_sku();
					$comp_last_price = (float)number_format(($comp_reprice_obj->get_last_price()), 2, '.', '');
					$comp_now_price = (float)number_format(($comp_reprice_obj->get_now_price()), 2, '.', '');
					$comp_ship_charge = (float)number_format(($comp_reprice_obj->get_comp_ship_charge()), 2, '.', '');

					$platform_selling_price = $comp_reprice_obj->get_platform_selling_price();
					if($completed === TRUE)
					{
						# if sku has been repriced in previous loop, check if we are lowest and update report column 7
						if($compare["price"] > $total_price)
						{
							$debug_msg .= "|| We are NOT lowest !<br>";
							$rpt_reprice[$last_rpt_arr_key][9] = "NOTlowest";
						}
						else
						{
							$debug_msg .= "|| We are lowest !<br>";
							$rpt_reprice[$last_rpt_arr_key][9] = "islowest";
						}

						break;
					}

					if(($product_obj = $product_srv->get_dao()->get(array("sku"=>$sku))) === FALSE)
					{
						$error_msg = __FILE__.". Could not retrieve product object for platform_id <$platform_id>, SKU <$sku>. \nDB error msg: {$product_srv->db->_error_message()}";
						$this->send_notification_email('DE', $error_msg);
						$debug_msg .= "$error_msg";
						break;
					}

					if(($price_obj = $price_srv->get_website_price($sku, $platform_id)) === FALSE)
					{
						$error_msg = __FILE__.". Could not retrieve price object for platform_id <$platform_id>, SKU <$sku>. \nDB error msg: {$price_srv->db->_error_message()}";
						$this->send_notification_email('DE', $error_msg);
						$debug_msg .= "$error_msg";
						break;
					}

					$old_selling_price = $price_obj->get_price();
					$profit_margin_obj = json_decode($price_srv->get_profit_margin_json($platform_id, $sku, $old_selling_price));
					$platform_margin = $profit_margin_obj->get_margin;

					# this was initially used for debugging (if cannot reprice on lowest comp, go to next lowest)
					// if($find_next) #if not first time looping through this sku
					// 	$debug_msg .= ">>> ";
					// else
						$debug_msg .= "SKU $sku / MASTER SKU $ext_sku";

					$prod_name = $product_obj->get_name();

					if(#SBF #3328
						$product_obj->get_website_status() !== 'I' ||
						$product_obj->get_website_quantity() == 0 ||
						$product_obj->get_sourcing_status() !== 'A' ||
						$comp_stock_status == 0 ||
						$platform_margin > 0
						)
					{
						if(!empty($comp_last_price))
						{
							#sbf #3770 don't match ourselves if competitor is price comparison website
							if(strtolower($note_1) == "valuebasket")
							{
								$rpt_reprice[] = array($ext_sku, $sku, $prod_name, $old_selling_price, $platform_margin, $new_price, $new_margin, $competitor_name, $comp_now_price, "Competitor = VB", "reprice_success_no_change");
								$debug_msg .= "<br> >>> [[reprice_success_no_change]][Competitor = VB] $competitor_name [id:$competitor_id] => comp_now_price $comp_now_price || new_price $new_price || old_selling_price $old_selling_price <br>";

								break;
							}

							$last_now_diff = number_format((($comp_now_price - $comp_last_price)/$comp_last_price*100), '2', '.', '');

							# competitor's comp_last_price and comp_now_price change is reasonable
							if(-2 < $last_now_diff && $last_now_diff < 2)
							{
								$new_price = number_format(($comp_now_price + $reprice_value + $comp_ship_charge), 2, '.', '');

								$new_profit_margin_obj = json_decode($price_srv->get_profit_margin_json($platform_id, $sku, $new_price));
								$new_margin = $new_profit_margin_obj->get_margin;
								# make sure new selling_price margin is not lower than min reprice_margin set in competitor url mapping admin
								if($new_margin >= $reprice_min_margin)
								{
									# if new_price same as VB current selling price, don't update
									# db and don't include in reprice_success report
									if($new_price !== $old_selling_price)
									{
										$price_obj->set_price($new_price);
										$reprice_count++;
										# if debugging, don't do actual reprice
										if(empty($debug_sku) && empty($echo_file))
										{
											# SUCCESS. all conditions fulfilled, reprice!
											if(($price_srv->get_dao()->update($price_obj)) === FALSE)
											{
												$error_msg = __FILE__.". Update price_service failed for platform_id <$platform_id>, SKU $sku, new_price $new_price . \nDB error msg: {$price_srv->get_dao()->db->_error_message()}";
												$this->send_notification_email('UF', $error_msg);
												$debug_msg .= "<br>Update failed. <br>Error msg: $error_msg";
												break;
											}

											$this->get_product_update_followup_service()->adwords_update($sku);
											$this->get_product_update_followup_service()->google_shopping_update($sku);
										}

										$debug_new_price = $price_obj->get_price();
										$debug_msg .= "::REPRICED $competitor_name [id:$competitor_id] [comp price+ship: $total_price] => new_margin $new_margin% || new_price $new_price || old_selling_price $old_selling_price ";
										$compare["sku"] = $sku;
										$compare["ext_sku"] = $ext_sku;
										$compare["price"] = $new_price;
										$completed = TRUE;

										$rpt_reprice[] = array($ext_sku, $sku, $prod_name, $old_selling_price, $platform_margin, $new_price, $new_margin, $competitor_name, $comp_now_price, "", "reprice_success");

										# if reprice success, we go to next lowest to check if we are cheapest seller
										if($arr_count > 1 && $arr_count !== $i)
										{
											continue;
										}
										else
										{
											# if there's only one competitor or we reach end of competitor list

											if($new_price > $total_price)
											{
												# total_price = competitor price + shipping charge
												$debug_msg .= "|| We are NOT lowest !<br>";
												$rpt_reprice[$last_rpt_arr_key+1][9] = "NOTlowest";
												break;
											}
											else
											{
												$debug_msg .= " || We are lowest! <br>";
												$rpt_reprice[$last_rpt_arr_key+1][9] = "islowest";
												break;
											}
										}

									}
									else
									{
										# if new price is same as current selling price, we put in compiled report.
										$rpt_reprice[] = array($ext_sku, $sku, $prod_name, $old_selling_price, $platform_margin, $new_price, $new_margin, $competitor_name, $comp_now_price, "", "reprice_success_no_change");

										$debug_msg .= "<br> >>> [[reprice_success_no_change]] $competitor_name [id:$competitor_id] => comp_now_price $comp_now_price || new_price $new_price || old_selling_price $old_selling_price <br>";
										break;
									}
								}
								else
								{
									$debug_msg .= "<br> >>> [[reprice_min_margin not met]] $competitor_name [id:$competitor_id] => comp_now_price $comp_now_price || new_margin $new_margin% || reprice_min_margin $reprice_min_margin <br>";

									$rpt_reprice[] = array($ext_sku, $sku, $prod_name, $old_selling_price, $platform_margin, "", $new_margin, $competitor_name, $comp_now_price, "", "reprice_fail_minmargin");

									break;

									# for now, don't try to match with next lowest competitor
									// if($arr_count > 1 && $arr_count !== $i)
									// {
									// 	$find_next = TRUE;
									// 	$debug_msg .= "|| try to match next lowest. <br>";
									// 	continue;
									// }
									// else
									// {
									// 	$debug_msg .= "|| no more competitor to match.<br>";
									// 	break;
									// }
								}

							}
							else
							{
								$last_now_diff = number_format($last_now_diff, '2', '.', '');
								$debug_msg .= "::[[last_now_diff_fail (range +/- 2%)]] $competitor_name [id:$competitor_id] => comp_last_price $comp_last_price || comp_now_price $comp_now_price || last_now_diff $last_now_diff%<br>";

								$rpt_reprice[] = array($ext_sku, $sku, $prod_name, $old_selling_price, $platform_margin, "", $new_margin, $competitor_name, $comp_now_price, "", "reprice_fail_lastnowdiff");

								break;
							}
						}
						else
						{
							$debug_msg .= "::[[Not processed; comp_last_price = 0]] - $competitor_name [id:$competitor_id] || skip SKU<br>";

							$rpt_reprice[] = array($ext_sku, $sku, $prod_name, $old_selling_price, $platform_margin, "", $new_margin, $competitor_name, $comp_now_price, "", "reprice_fail_lastprice_is_0");
							break;
						}
					}
					else
					{
						$fail_condition = $fail_value = array();
						$website_status = $this->get_status_name($product_obj->get_website_status(), "website_status");
						$sourcing_status = $this->get_status_name($product_obj->get_sourcing_status(), "sourcing_status");
						$comp_stock_status = $this->get_status_name($comp_stock_status, "comp_stock_status");

						if($product_obj->get_website_status() !== 'I')
						{
							$fail_condition[] = "website_status!='In Stock'";
							$fail_value[] = "website_status $website_status";
						}

						if($product_obj->get_website_quantity() == 0)
						{
							$fail_condition[] = "website_quantity=0";
							$fail_value[] = "website_quantity {$product_obj->get_website_quantity()}";
						}

						if($product_obj->get_sourcing_status() !== 'A')
						{
							$fail_condition[] = "sourcing_status!='Readily Available'";
							$fail_value[] = "sourcing_status $sourcing_status";
						}

						if($platform_margin < 0)
						{
							$fail_condition[] = "platform_margin < 0";
							$fail_value[] = "platform_margin $platform_margin";
						}

						if($comp_stock_status == 0)
						{
							$fail_condition[] = "comp_stock_status!='In Stock";
							$fail_value[] = "comp_stock_status $comp_stock_status";
						}

						$conditions = implode(" OR ", $fail_condition);
						$values = implode(" || ", $fail_value);
						$debug_msg .= "::[[Failed criteria: $conditions]]  - $competitor_name [id:$competitor_id]  =>  $values || skip SKU<br>";

						$rpt_reprice[] = array($ext_sku, $sku, $prod_name, $old_selling_price, $platform_margin, "", $new_margin, $competitor_name, $comp_now_price, "", "reprice_fail_[wqty/wstatus/sourcestatus/platmrgn/compstockstatus]");

						break;
					}
				}
			}

			$this->reprice_count = $reprice_count;
			$debug .= "Total reprice: $reprice_count";
		}
		else
		{
			$error_msg = __FILE__.". No SKUs to reprice for platform_id <$platform_id>. \nDB error msg: {$competitor_map_dao->db->_error_message()}";
			// $this->send_notification_email('NL', $error_msg);
			$debug_msg .= "<br>No list for auto-reprice. Error msg: $error_msg <br>";
		}


		if($echo_file == 1)
		{
			$csv_string = $this->gen_and_email_csv($rpt_reprice, "echo_file", $platform_id);
		}
		else if ($echo_file == 2)
		{
			$debug .= "Total reprice: ".$this->reprice_count ." </font>";
			echo $debug_msg;
		}
		else
		{
			$rpt_reprice_success = $reprice_fail_minmargin = $reprice_fail_lastnowdiff = array();

			# recompile report data for different statuses
			foreach ($rpt_reprice as $rpt_array)
			{
				switch ($rpt_array[8])
				{
					case 'reprice_success':
						array_pop($rpt_array); # hide last column in email report
						$rpt_reprice_success[] = $rpt_array;
						break;

					case 'reprice_fail_minmargin':
						array_pop($rpt_array);
						$reprice_fail_minmargin[] = $rpt_array;
						break;

					case 'reprice_fail_lastnowdiff':
						array_pop($rpt_array);
						$reprice_fail_lastnowdiff[] = $rpt_array;
						break;

					default:
						# code...
						break;
				}

			}

			# put back report headers since the switch case abv didn't put it in the new array
			array_unshift($rpt_reprice_success, $rpt_header);
			array_unshift($reprice_fail_minmargin, $rpt_header);
			array_unshift($reprice_fail_lastnowdiff, $rpt_header);

			$this->gen_and_email_csv($rpt_reprice_success, "reprice_success", $platform_id);
			// $this->gen_and_email_csv($reprice_fail_minmargin, "reprice_fail_minmargin", $platform_id);
			// $this->gen_and_email_csv($reprice_fail_lastnowdiff, "reprice_fail_lastnowdiff", $platform_id);

			# compiled report with ALL fail statuses
			$this->gen_and_email_csv($rpt_reprice, "reprice_compiled", $platform_id);

			echo $debug_msg;
		}
	}

	private function gen_and_email_csv($data, $report_type="", $platform_id)
	{
		ob_start();
		$csv_string = "";

		if($data)
		{
			$fp = fopen('php://output', 'w');

			foreach ($data as $fields)
			{
				if(fputcsv($fp, $fields) === FALSE)
				{
					$error_fields = implode(',', $fields);
					$error_msg = __FILE__." Line " .__LINE__. "Error writing data to file. "."\nfields: $error_fields";

					$this->send_notification_email("CSV", $error_msg);
					echo "<pre>DEBUG: $error_msg</pre>";
					break;
				}
			}

			$csv_string = ob_get_clean();
		}

		if($report_type == "echo_file")
		{
			header('Content-Type: text/csv; charset=utf-8');
			header("Content-Disposition: attachment; filename=report_compiled_".date('Ymdhis').".csv");
			echo $csv_string;
		}
		else
		{
			$this->send_report_email($csv_string, $report_type, $platform_id);

		}
		// return $csv_string;
	}

	private function send_report_email($csv_string, $report_type, $platform_id)
	{
		$title ="" ;
		$filename = "{$platform_id}_report_{$report_type}_".date('Ymdhis').".csv";
		include_once(BASEPATH . "plugins/phpmailer/phpmailer_pi.php");
		$phpmail = new phpmailer();
		$phpmail->IsSMTP();
		$phpmail->From = "Admin <admin@valuebasket.net>";

		switch ($report_type)
		{
			case 'reprice_success':
				$title = "[$platform_id-Competitor Reprice Report] Reprice Success";

				if($this->reprice_count)
				{
					$reprice_msg = "\nTotal repriced: {$this->reprice_count}";
				}
				else
				{
					$reprice_msg = "\nTotal repriced: 0";
				}
				break;

			// case 'reprice_fail_minmargin':
			// 	$title = "[$platform_id-Competitor Reprice Report] Reprice Fail: Below reprice_min_margin";
			// 	break;

			// case 'reprice_fail_lastnowdiff':
			// 	$title = "[$platform_id-Competitor Reprice Report] Reprice Fail: last_now_diff range > +/-2%";
			// 	break;

			case 'reprice_compiled':
				$title = "[$platform_id-Competitor Reprice Report] COMPILED for debug";

				if($this->reprice_count)
				{
					$reprice_msg = "\nTotal repriced: {$this->reprice_count}";
				}
				else
				{
					$reprice_msg = "\nTotal repriced: 0";
				}

				break;

			default:
				# code...
				break;
		}

		if($report_type == 'reprice_compiled')
		{
			// $phpmail->AddAddress("edward@eservicesgroup.com");
			// $phpmail->AddAddress("ping@eservicesgroup.com");
		}

		if($email_arr = $this->get_email_arr($report_type, $platform_id))
		{
			foreach ($email_arr as $email)
			{
				$phpmail->AddAddress($email);
			}
		}

		$phpmail->Subject = $title;
		$phpmail->IsHTML(false);
		$phpmail->Body = $title.$reprice_msg."\r\n[type: Competitor_reprice_service]";
		$phpmail->AddStringAttachment($csv_string, $filename, 'base64', 'text/csv');

		// $phpmail->SMTPDebug  = 1;

		$result = $phpmail->Send();
		// var_dump($phpmail);

	}

	private function get_email_arr($report_type, $platform_id)
	{
		$email_arr = array();

		switch ($platform_id)
		{
			case 'WEBES':
			case 'WEBPT':
				$email_arr = array("edward@eservicesgroup.com","gonzalo@eservicesgroup.com","paula.garcia@eservicesgroup.com");
				break;

			case 'WEBIT':
				$email_arr = array("edward@eservicesgroup.com","davide.pecoraro@eservicesgroup.com", "paula.garcia@eservicesgroup.com", "gonzalo@eservicesgroup.com");
				break;

			case 'WEBGB':
				//$email_arr = array("edward@eservicesgroup.com","ming@eservicesgroup.com","jonathan@eservicesgroup.com");
				$email_arr = array("Spiderman-uk@valuebasket.com");
				break;

			case 'WEBBE':
				$email_arr = array("edward@eservicesgroup.com","aymeric@eservicesgroup.com","eiffel@eservicesgroup.com","celine@eservicesgroup.com","romuald@eservicesgroup.com");
				break;

			case 'WEBFR':
				//$email_arr = array("edward@eservicesgroup.com","aymeric@eservicesgroup.com","eiffel@eservicesgroup.com","celine@eservicesgroup.com","romuald@eservicesgroup.com");
				$email_arr = array("Spiderman-fr@valuebasket.com");
				break;

			case 'WEBAU':
				$email_arr = array("edward@eservicesgroup.com","alex@eservicesgroup.com","louis@eservicesgroup.com");
				break;

			case 'WEBNZ':
				$email_arr = array("edward@eservicesgroup.com","alex@eservicesgroup.com","louis@eservicesgroup.com","lester.chan@eservicesgroup.com");
				break;

			default:
				$email_arr = array("edward@eservicesgroup.com");

				break;
		}

		return $email_arr;
	}

	private function get_status_name($value="", $col_name)
	{
		if($value && $col_name)
		{
			if($col_name == 'website_status')
			{
				switch ($value)
				{
					case 'I':
						$status = "Instock";
						break;
					case 'O':
						$status = "Outstock";
						break;
					case 'P':
						$status = "Pre-Order";
						break;
					case 'A':
						$status = "Arriving";
						break;
					default:
						$status = "";
						break;
				}
				return $status;
			}

			if($col_name == 'sourcing_status')
			{
				switch ($value)
				{
					case 'A':
						$status = "Readily_Available";
						break;
					case 'O':
						$status = "Temp_Out-of_Stock";
						break;
					case 'C':
						$status = "Limited_Stock";
						break;
					case 'L':
						$status = "Last_Lot";
						break;
					case 'D':
						$status = "Discontinued";
						break;
					default:
						$status = "";
						break;
				}
				return $status;
			}

			if($col_name == 'comp_stock_status')
			{
				switch ($value)
				{
					case '0':
						$status = "Instock";
						break;
					case '1':
						$status = "Outstock";
						break;
					case '2':
						$status = "Pre-Order";
						break;
					case '3':
						$status = "Arriving";
						break;
					default:
						$status = "";
						break;
				}
				return $status;
			}
		}

		return;
	}

	private function send_notification_email($error_type, $error_msg="")
	{
		include_once(BASEPATH . "plugins/phpmailer/phpmailer_pi.php");
		$phpmail = new phpmailer();
		$phpmail->IsSMTP();
		$phpmail->From = "Admin <admin@valuebasket.net>";
		$it_email = $this->notification_email;

		$country_id = $this->country_id;
		if($country_id)
		{
			switch ($error_type)
			{
				case "DE":
					$message = $error_msg."\r\n[type: Competitor_reprice_service]";
					$title = "WARNING - Competitor Reprice [$country_id] Data Retrieval Error";
					break;

				case "NL":
					$message = $error_msg."\r\n[type: Competitor_reprice_service]";
					$title = "WARNING - Competitor Reprice [$country_id] No List to Reprice";
					break;

				case "UF":
					$message = $error_msg."\r\n[type: Competitor_reprice_service]";
					$title = "WARNING - Competitor Reprice [$country_id] Update Price Failed";
					break;

				case "CSV":
					$message = $error_msg."\r\n[type: Competitor_reprice_service]";
					$title = "WARNING - Competitor Reprice [$country_id] CSV Error";
					break;
			}

			$phpmail->AddAddress("edward@valuebasket.com");
			$phpmail->AddAddress("itsupport@eservicesgroup.net");
			$phpmail->AddAddress($it_email);
			// mail($this->notification_email, $title, $message);

			$phpmail->Subject = "$title";
			$phpmail->IsHTML(false);
			$phpmail->Body = $message;

			$result = $phpmail->Send();
		}
	}

}