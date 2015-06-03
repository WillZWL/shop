<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_integration_service
{
	private $notification_email = "itsupport@eservicesgroup.net, celine@eservicesgroup.com";

	public function __construct()
	{
		include_once(APPPATH."libraries/service/Client_integration_service.php");
		$this->set_client_integration(new Client_integration_service());
		include_once(APPPATH."libraries/service/Validation_service.php");
		$this->set_valid(new Validation_service());
		include_once(APPPATH."libraries/service/Batch_service.php");
		$this->set_batch_srv(new Batch_service());
		include_once(APPPATH."libraries/service/Platform_biz_var_service.php");
		$this->set_pbv_srv(new Platform_biz_var_service());
		include_once(APPPATH."libraries/service/Context_config_service.php");
		$this->set_config(new Context_config_service());
		include_once (APPPATH."libraries/dao/Exchange_rate_dao.php");
		$this->set_xrate_dao(new Exchange_rate_dao());
		include_once (APPPATH."libraries/service/Product_service.php");
		$this->set_product_srv(new Product_service());
		include_once(APPPATH."libraries/service/Bundle_service.php");
		$this->set_bundle_srv(new Bundle_service());
		include_once APPPATH."libraries/service/Order_notes_service.php";
		$this->set_order_notes_srv(new Order_notes_service());
		include_once (APPPATH."libraries/dao/So_priority_score_dao.php");
		$this->set_so_priority_score_dao(new So_priority_score_dao());

		include_once (APPPATH."libraries/dao/Interface_so_dao.php");
		$this->set_iso_dao(new Interface_so_dao());
		include_once (APPPATH."libraries/dao/Interface_so_item_dao.php");
		$this->set_isoi_dao(new Interface_so_item_dao());
		include_once (APPPATH."libraries/dao/Interface_so_item_detail_dao.php");
		$this->set_isoid_dao(new Interface_so_item_detail_dao());
		include_once (APPPATH."libraries/dao/Interface_so_payment_status_dao.php");
		$this->set_isops_dao(new Interface_so_payment_status_dao());

		include_once(APPPATH."libraries/service/So_service.php");
		$this->set_so_srv(new So_service());
		include_once (APPPATH."libraries/dao/So_dao.php");
		$this->set_so_dao(new So_dao());
		include_once (APPPATH."libraries/dao/So_item_dao.php");
		$this->set_soi_dao(new So_item_dao());
		include_once (APPPATH."libraries/dao/So_item_detail_dao.php");
		$this->set_soid_dao(new So_item_detail_dao());
		include_once (APPPATH."libraries/dao/So_payment_status_dao.php");
		$this->set_sops_dao(new So_payment_status_dao());

		include_once APPPATH."libraries/service/Complementary_acc_service.php" ;
		$this->set_ca_service(new Complementary_acc_service());

		/* this parent price_service shouldn't be used or may cause incorrect profite_margin*/
		include_once APPPATH."libraries/service/Price_service.php";
		$this->set_price_srv(new Price_service());
		include_once(APPPATH."libraries/service/Supplier_service.php");
		$this->set_sup_srv(new Supplier_service());
	}

	public function get_client_integration()
	{
		return $this->client_integration;
	}

	public function set_client_integration($value)
	{
		$this->client_integration = $value;
	}

	public function get_batch_srv()
	{
		return $this->batch_srv;
	}

	public function set_batch_srv($value)
	{
		$this->batch_srv = $value;
	}

	public function get_pbv_srv()
	{
		return $this->pbv_srv;
	}

	public function set_pbv_srv($value)
	{
		$this->pbv_srv = $value;
	}

	public function get_config()
	{
		return $this->config;
	}

	public function set_config($value)
	{
		$this->config = $value;
	}

	public function get_xrate_dao()
	{
		return $this->xrate_dao;
	}

	public function set_xrate_dao(Base_dao $dao)
	{
		$this->xrate_dao = $dao;
	}

	public function get_iso_dao()
	{
		return $this->iso_dao;
	}

	public function set_iso_dao(Base_dao $value)
	{
		$this->iso_dao = $value;
	}

	public function get_isoi_dao()
	{
		return $this->isoi_dao;
	}

	public function set_isoi_dao(Base_dao $dao)
	{
		$this->isoi_dao = $dao;
	}

	public function get_isoid_dao()
	{
		return $this->isoid_dao;
	}

	public function set_isoid_dao(Base_dao $dao)
	{
		$this->isoid_dao = $dao;
	}

	public function get_isops_dao()
	{
		return $this->isops_dao;
	}

	public function set_isops_dao(Base_dao $value)
	{
		$this->isops_dao = $value;
	}

	public function get_so_dao()
	{
		return $this->so_dao;
	}

	public function set_so_dao(Base_dao $value)
	{
		$this->so_dao = $value;
	}

	public function get_soi_dao()
	{
		return $this->soi_dao;
	}

	public function set_soi_dao(Base_dao $dao)
	{
		$this->soi_dao = $dao;
	}

	public function get_soid_dao()
	{
		return $this->soid_dao;
	}

	public function set_soid_dao(Base_dao $dao)
	{
		$this->soid_dao = $dao;
	}

	public function get_sops_dao()
	{
		return $this->sops_dao;
	}

	public function set_sops_dao(Base_dao $value)
	{
		$this->sops_dao = $value;
	}

	public function get_price_srv()
	{
		return $this->price_srv;
	}

	public function set_price_srv($value)
	{
		$this->price_srv = $value;
	}

	public function get_sup_srv()
	{
		return $this->sup_srv;
	}

	public function set_sup_srv($value)
	{
		$this->sup_srv = $value;
	}

	public function get_so_srv()
	{
		return $this->so_srv;
	}

	public function set_so_srv($value)
	{
		$this->so_srv = $value;
	}

	public function get_product_srv()
	{
		return $this->product_srv;
	}

	public function set_product_srv($value)
	{
		$this->product_srv = $value;
	}

	public function get_bundle_srv()
	{
		return $this->bundle_srv;
	}

	public function set_bundle_srv($value)
	{
		$this->bundle_srv = $value;
	}

	public function get_valid()
	{
		return $this->valid;
	}

	public function set_valid($value)
	{
		$this->valid = $value;
	}

	public function get_order_notes_srv()
	{
		return $this->order_notes_srv;
	}

	public function set_order_notes_srv($value)
	{
		$this->order_notes_srv = $value;
	}

	public function get_so_priority_score_dao()
	{
		return $this->so_priority_score_dao;
	}

	public function set_so_priority_score_dao($value)
	{
		$this->so_priority_score_dao = $value;
	}

	public function get_ca_service()
	{
		return $this->ca_service;
	}

	public function set_ca_service($value)
	{
		$this->ca_service = $value;
	}

	public function get_notification_email()
	{
		return $this->notification_email;
	}

	public function set_notification_email($value)
	{
		// if not set it will default to private $notification_email
		$this->notification_email = $value;
	}

	public function create_platform_batch()
	{
		/** Function will insert info into db interface-related tables **/
		$batch_id = $this->batch_id;
		$platform_id = $this->platform_id;
		$currency_id = $this->currency_id;
		$interface_client_vo = $this->interface_client_vo;
		$paid_order_status = $this->paid_order_status;
		$txn_id = $this->txn_id;
		$platform_order_id = $this->platform_order_id;
		$last_line_no = 0;

		echo "<pre>[[ DEBUG: START OF CREATE_PLATFORM_BATCH ]]</pre>";
		echo "<pre>batch_id = '$batch_id' </pre>";

		if (!$batch_id || !$interface_client_vo || !$platform_id || !$currency_id || is_null($paid_order_status))
		{
			$error_msg = 'Function create_platform_batch() '."\nOne of the following is empty: \nBatch_id: < $batch_id >; \nPlatform_id: < $platform_id >;\nCurrency_id: < $currency_id >; \nPaid_order_status: < $paid_order_status >; \n\n**Also check that interface_client_vo is not null.";
			throw new Exception($error_msg);
		}
		else
		{
			$client_trans_id = $interface_client_vo->get_trans_id();
			$interface_so_obj = $this->get_interface_so_obj($batch_id, $platform_order_id, $txn_id);
			if (empty($interface_so_obj))
			{
				/* input various info needed to create interface_so */
				$this->set_platform_biz_var_data();

				/* interface_so */
				$interface_so_obj = $this->insert_interface_so($client_trans_id);
				$so_trans_id = $interface_so_obj->get_trans_id();

				$this->current_so_trans_id = $so_trans_id;

				/* first item added by a new so_trans_id in interface_so_item will have line_no = 1 */
				$last_line_no = 1;
			}
			else
			{
				$so_trans_id = $interface_so_obj->get_trans_id();

				//  each subsequent item created by same so_trans_id will increase line_no by 1
				$last_line_no = $this->get_last_line_no($so_trans_id);
			}

			echo "<pre>DEBUG: 1. INSERTED INTERFACE_SO. // so_trans_id = '$so_trans_id'</pre>";

			if (!$last_line_no)
			{
				$error_msg = 'Function create_platform_batch(): $last_line_no cannot be empty.';
				throw new Exception($error_msg);
			}
			else
			{
				/* interface_so_item */
				$interface_so_item_obj = $this->insert_interface_so_item($so_trans_id, $last_line_no);

				if ($interface_so_item_obj)
				{
					echo "<pre>DEBUG: 2. INSERTED INTERFACE_SO_ITEM.</pre>";

					/* interface_so_item_detail */
					$interface_so_item_detail_obj = $this->insert_interface_so_item_detail($batch_id, $last_line_no, $interface_so_obj);

					echo "<pre>DEBUG: 3. INSERTED INTERFACE_SO_ITEM_DETAIL.</pre>";

					/* interface_so_payment_status */
					if ($paid_order_status === TRUE)
					{
						$interface_so_payment_status = $this->insert_interface_so_payment_status($batch_id, $so_trans_id);

						echo "<pre>DEBUG: 4. INSERTED INTERFACE_SO_PAYMENT_STATUS.</pre>";
						echo "<pre></pre>";
						echo " [[ DEBUG: END OF CREATE_PLATFORM_BATCH ]] <pre></pre><hr></hr>";

						return TRUE;
					}
					elseif ($paid_order_status === FALSE)
					{
						$error_msg = 'Function create_platform_batch(): $paid_order_status is FALSE, cannot proceed with updating interface_so_payment_status'.
									 "\nPlease input TRUE in the function if the order has been paid to proceed.";
						throw new Exception($error_msg);
					}
					else
					{
						$error_msg = 'Function create_platform_batch(): $paid_order_status can only accept TRUE or FALSE values. Please check.';
						throw new Exception($error_msg);
					}

				}
			}
		}
	}

	public function commit_platform_batch()
	{
		/** This function creates orders into actual so-related tables **/
		$so_dao = $this->get_so_dao();
		$so_item_dao = $this->get_soi_dao();
		$so_item_detail_dao = $this->get_soid_dao();
		$so_payment_status_dao = $this->get_sops_dao();
		$soext_dao = $this->get_so_srv()->get_soext_dao();

		$interface_so_dao = $this->get_iso_dao();
		$interface_so_item_dao = $this->get_isoi_dao();
		$interface_so_item_detail_dao = $this->get_isoid_dao();
		$interface_so_payment_status_dao = $this->get_isops_dao();

		$batch_id = $this->batch_id;
		$batch_obj = $this->get_batch_srv()->get(array("id"=>$batch_id));
		$iso_list = $interface_so_dao->get_list(array("batch_id"=>$batch_id), array());
		$success_so_list = array();

		if($iso_list)
		{
			$batch_status = TRUE;

			$so_vo = $so_dao->get();
			$so_item_vo = $so_item_dao->get();
			$so_item_detail_vo = $so_item_detail_dao->get();
			$so_payment_status_vo = $so_payment_status_dao->get();
			$soext_vo = $soext_dao->get();

			foreach($iso_list AS $k => $iso_obj)
			{
				$so_trans_id = $iso_obj->get_trans_id();

				$isoi_list = $interface_so_item_dao->get_list(array("so_trans_id"=>$so_trans_id), array());

				echo "<hr></hr><pre></pre>";
				echo "<pre>[[ DEBUG: START OF COMMIT_PLATFORM_BATCH ]] </pre>";
				echo "<pre>[interface_so] so_trans_id: $so_trans_id</pre>";
				echo "<pre>[so table] platform_order_id: ".$iso_obj->get_platform_order_id()."</pre>";

				$iso_batch_status = TRUE;
				$failed_reason = "";
				foreach($isoi_list AS $isoi_obj)
				{
					$sku = $isoi_obj->get_prod_sku();

					if(!$this->get_product_srv()->get(array("sku"=>$sku)))
					{
						$iso_batch_status = FALSE;
						$failed_reason = __LINE__. ", Invalid SKU - ".$sku;
						break;
					}
				}

				//check if so record existed already
				$so_num = $this->get_so_dao()->get_num_rows(array("platform_order_id"=>$iso_obj->get_platform_order_id(), "platform_id"=>$iso_obj->get_platform_id()));

				if($so_num != 0)
				{
					$iso_batch_status = FALSE;
					$failed_reason = __LINE__. "order_integration_service, Duplicate; order already existed: \nplatform_order_id: < ".$iso_obj->get_platform_order_id()." > ignored.";
				}

				if ($iso_batch_status)
				{
					$client_intergration_srv = $this->get_client_integration();
					$ic_obj = $client_intergration_srv->get_interface_client_vo($iso_obj->get_client_trans_id(), $batch_id);

					if(!$this->get_valid()->valid_email($ic_obj->get_email()))
					{
						$iso_batch_status = FALSE;
						$failed_reason = __LINE__. ", Invalid Email";
					}
					else
					{
						/* insert into actual client db */
						$commit_client = $client_intergration_srv->commit_client($batch_id, $iso_obj);

						if ($commit_client["iso_batch_status"] === TRUE)
						{
							echo "<pre>DEBUG: 5. INSERTED CLIENT.</pre>";

							$c_dao = $commit_client["c_dao"];
							$c_obj = $commit_client["c_obj"];
							$ic_dao = $commit_client["ic_dao"];
							$ic_obj = $commit_client["ic_obj"];

							$client_id = $c_obj->get_id(); #our actual client_id generated by db

							/* update our actual client_id in interface_client */
							$update_interface_client = $client_intergration_srv->update_interface_client($c_obj, $ic_obj);
							if($update_interface_client["iso_batch_status"] === TRUE)
							{
								$ic_dao = $update_interface_client["ic_dao"];
								$ic_obj = $update_interface_client["ic_obj"];

								//so
								$insert_so = $this->insert_so($client_id, $so_vo, $iso_obj);
								if($insert_so["iso_batch_status"] === TRUE)
								{
									$so_no = $insert_so["so_no"];

									$success_so_list[$k]["so_no"] = $so_no;
									$success_so_list[$k]["txn_id"] = $insert_so["so_obj"]->get_txn_id();
									$success_so_list[$k]["platform_order_id"] = $insert_so["so_obj"]->get_platform_order_id();

									$so_obj = $insert_so["so_obj"];	#interface_so copied over to so_obj
									$so_dao = $insert_so["so_dao"];	#inserted into db
									$iso_obj = $insert_so["iso_obj"]; #interface_so now have actual so_no and client_id

									echo "<pre>DEBUG: 6. INSERTED SO. // so_no: '$so_no'</pre>";

									$so_obj_arr[] = array("so" => $so_obj, "result" => false);

									//so_payment_status
									$insert_so_payment_status = $this->insert_so_payment_status($batch_id, $so_trans_id, $so_no, $so_payment_status_vo);

									if($insert_so_payment_status["iso_batch_status"] === TRUE)
									{
										echo "<pre>DEBUG: 7. INSERTED SO_PAYMENT_STATUS.</pre>";

										$so_payment_status_obj = $insert_so_payment_status["so_payment_status_obj"]; #interface_sops copied over to sops_obj
										$so_payment_status_dao = $insert_so_payment_status["so_payment_status_dao"]; #inserted into db
										$interface_so_payment_status_obj = $insert_so_payment_status["isops_obj"]; #updated isops_obj

										$interface_so_payment_status_obj->set_batch_status('S');
										if($interface_so_payment_status_dao->update($interface_so_payment_status_obj))
										{
											//so_item
											if ($isoi_list = $interface_so_item_dao->get_list(array("batch_id"=>$batch_id, "so_trans_id"=>$so_trans_id)))
											{
												foreach($isoi_list AS $interface_so_item_obj)
												{
													$insert_so_item = $this->insert_so_item($interface_so_item_obj, $so_no);
													if($insert_so_item["iso_batch_status"] === TRUE)
													{
														echo "<pre>DEBUG: 8. INSERTED SO_ITEM.</pre>";

														$interface_so_item_obj = $insert_so_item["interface_so_item_obj"]; #interface_so_item updated with actual so_no
														$so_item_obj = $insert_so_item["so_item_obj"]; #interface_so_item copied over to so_item_obj
														$so_item_dao = $insert_so_item["so_item_dao"];	#inserted into db

														$interface_so_item_obj->set_batch_status('S');
														if (!$interface_so_item_dao->update($interface_so_item_obj))
														{
															$iso_batch_status = FALSE;
															$failed_reason = __LINE__. ", Interface_so_item: ".$interface_so_item_dao->db->_error_message();
															break;
														}
													}
													else
													{
														$iso_batch_status = FALSE;
														$failed_reason = $insert_so_item["failed_reason"];
														break;
													}
												}
											}
											if($iso_batch_status)
											{
												//so_item_detail
												if ($isoid_list = $interface_so_item_detail_dao->get_list(array("batch_id"=>$batch_id, "so_trans_id"=>$so_trans_id)))
												{
													foreach($isoid_list AS $isoid_obj)
													{
														$insert_so_item_detail = $this->insert_so_item_detail($so_no, $isoid_obj);
														if($insert_so_item_detail["iso_batch_status"] === TRUE)
														{
															echo "<pre>DEBUG: 9. INSERTED SO_ITEM_DETAIL.</pre>";

															$so_item_detail_obj = $insert_so_item_detail["so_item_detail_obj"]; #interface_so_item_detail copied over to so_item_detail_obj
															$so_item_detail_dao = $insert_so_item_detail["so_item_detail_dao"]; #inserted into db
															$isoid_obj = $insert_so_item_detail["isoid_obj"];

															$isoid_obj->set_batch_status('S');
															if ($interface_so_item_detail_dao->update($isoid_obj))
															{
																//update website quantity
																if(strpos($_SERVER["HTTP_HOST"], "admindev")===false)
																	$this->get_so_srv()->update_website_display_qty($so_obj);
															}
															else
															{
																$iso_batch_status = FALSE;
																$failed_reason = __LINE__. ", Interface_so_item_detail: ".$isoid_dao->db->_error_message();
																break;
															}
														}
														else
														{
															$iso_batch_status = FALSE;
															$failed_reason = $insert_so_item_detail["failed_reason"];
															break;
														}
													}
												}
											}

											if($iso_batch_status)
											{
												// so_extend
												$insert_soext = $this->insert_soext($so_no, $so_obj);

												// if(!$soext_dao->insert($soext_obj))
												if($insert_soext["iso_batch_status"] === FALSE)
												{
													$iso_batch_status = FALSE;
													$failed_reason = $insert_soext["failed_reason"];
													break;
												}
											}
										}
										else
										{
											$iso_batch_status = FALSE;
											$failed_reason = __LINE__. ", Interface_so_payment_status: ".$isops_dao->db->_error_message();
										}
									}
									else
									{
										$iso_batch_status = FALSE;
										$failed_reason = $insert_so_payment_status["failed_reason"];
									}
								}
								else
								{
									$iso_batch_status = FALSE;
									$failed_reason = $insert_so["failed_reason"];
								}
							}
							else
							{
								$iso_batch_status = FALSE;
								$failed_reason = $update_interface_client["failed_reason"];
							}
						}
						else
						{
							$iso_batch_status = FALSE;
							$failed_reason = $commit_client["failed_reason"];
						}

						if($iso_batch_status == FALSE)
						{
							$client_intergration_srv->execute_client_trans($c_dao, 'rollback');
						}
						else
						{
							/* update interface_so */
							$iso_obj->set_batch_status('S');
							$interface_so_dao->update($iso_obj);
							if (isset($so_obj_arr))
							{
								$stored_so_obj = $so_obj_arr[sizeof($so_obj_arr) - 1];
								if ($iso_obj->get_so_no() == $stored_so_obj["so"]->get_so_no())
								{
									$so_obj_arr[sizeof($so_obj_arr) - 1]["result"] = true;
								}
							}
						}

						$client_intergration_srv->execute_client_trans($c_dao, 'complete');
					}
				}

				if($iso_batch_status == FALSE)
				{
					$iso_obj->set_batch_status('F');
					$iso_obj->set_failed_reason($failed_reason);
					$iso_obj->set_so_no(NULL);
					$iso_obj->set_client_id(NULL);
					$interface_so_dao->update($iso_obj);
					$batch_status = FALSE;
					$email_message = "Platform: {$iso_obj->get_platform_id()}, Platfor_order_id: {$iso_obj->get_platform_order_id()}\r\norder_integration_service:$failed_reason";
					mail($this->notification_email, "[VB] Marketplace Order Integration Error", $email_message, "From: admin@valuebasket.com");
				}

				echo "<pre></pre>[[ iso_batch_status ]]: ";
				var_dump($iso_batch_status);
				echo "<pre>[[ failed_reason ]]: ";
				var_dump($failed_reason);
				echo "<pre></pre>";
				echo " ...............[[ DEBUG: END OF COMMIT_PLATFORM_BATCH ]]...............";
				echo "<pre></pre>";

			}

			if($batch_status)
			{
				$batch_obj->set_status("C");
			}
			else
			{
				$batch_obj->set_status("CE");
			}

			if($so_obj_arr)
			{
				foreach($so_obj_arr as $so_obj)
				{
					if ($so_obj["result"])
					{
						$this->so = $so_obj["so"];
						// $this->fire_success_event();
					}
				}
			}

			$batch_obj->set_end_time(date("Y-m-d H:i:s"));

			if (!$this->get_batch_srv()->update($batch_obj))
			{
				$error_msg = "Batch update error\nBatch_id:".$batch_id."\nError Message:".$this->get_batch_srv()->get_dao()->db->_error_message();
				$platform_id = $this->platform_id;
				$website = $this->website;
				$this->send_notification_email("BP", $platform_id, $website, $error_msg); #batch_update_problem
				throw new Exception($error_msg);
			}
			else
			{
				echo "<hr></hr><hr></hr><pre></pre> ======= [[ DEBUG: consolidated ]] ======= ";
				echo "<pre></pre>batch_obj: <pre>";
				var_dump($batch_obj);echo "</pre>";
				return $success_so_list;
			}
		}
		else
		{
			// no new orders to update
			$batch_obj->set_status("C");
			$batch_obj->set_end_time(date("Y-m-d H:i:s"));
			$this->get_batch_srv()->update($batch_obj);
			echo "<hr></hr> ======= [[ DEBUG: consolidated ]] ======= <pre></pre>Updated Batch - No New Orders";

		}
	}


//================================================= create_platform_batch related functions

	private function set_platform_biz_var_data()
	{
		$batch_error = 0;
		$platform_id = $this->platform_id;
		$currency_id = $this->currency_id;

		# $currency_id here refers to the one grabbed from your respective platform's (e.g. Qoo10) xml.
		if(!$platform_id || !$currency_id)
		{
			$error_msg = "Function set_platform_biz_var_data() - One of the following is empty: \nPlatform_id: < $platform_id >; \nCurrency_id: <$currency_id >";
			throw new Exception($error_msg);
		}
		else
		{
			if($platform_biz_var_obj = $this->get_pbv_srv()->get(array("selling_platform_id"=>$platform_id)))
			{
				$this->platform_currency_id = $platform_biz_var_obj->get_platform_currency_id();
				$this->lang_id 				= $platform_biz_var_obj->get_language_id();
				$this->courier 				= $platform_biz_var_obj->get_delivery_type();
				$this->vat_percent 			= $platform_biz_var_obj->get_vat_percent();
				$this->platform_country_id	= $platform_biz_var_obj->get_platform_country_id();

				if ($this->platform_currency_id <> $currency_id)
				{
					$error_msg = 'Function set_platform_biz_var_data(): platform_currency_id does not match with currency_id retrieved from xml. '.
									"\n$platform_id currency_id: $currency_id \nPlatform_currency_id: $platform_currency_id. \n";
					$batch_error = 1;
					echo "$error_msg";
				}
				$base_currency = $this->get_config()->value_of("func_curr_id");
				$rate_obj = $this->get_xrate_dao()->get(array("from_currency_id"=>($this->platform_currency_id), "to_currency_id"=>($base_currency)));
				$this->rate = $rate_obj->get_rate();
				$ref_1_obj = $this->get_xrate_dao()->get(array("from_currency_id"=>($this->platform_currency_id), "to_currency_id"=>"EUR"));
				$this->ref_1 = $ref_1_obj->get_rate();
				$this->vat_total = number_format($this->vat_percent / 100 * $this->total_amount_paid, 2, '.', '');
				return $platform_biz_var_obj;
			}
			else
			{
				continue;
				$error_msg = "Function set_platform_biz_var_data: Platform Matching Error.\nReturn Item Site: $platform_id. \n";
				$batch_error = 1;
				echo "$error_msg";
			}

			if($batch_error)
			{
				$this->send_notification_email("BE", $platform_id, $this->website, $error_msg);
			}
		}
	}

	private function get_interface_so_obj($batch_id, $platform_order_id, $txn_id="")
	{
		if (!$batch_id || !$platform_order_id)
		{
			$error_msg = "Function get_interface_so_obj() - one of the following is empty: \n Batch_id: < $batch_id >; \nPlatform_order_id: < $platform_order_id >.";
			throw new Exception($error_msg);
		}
		else
		{
			if (!$txn_id)
			{
				$interface_so_obj = $this->get_iso_dao()->get(array("batch_id"=>$batch_id, "platform_order_id"=>$platform_order_id));
				return $interface_so_obj;
			}
			else
			{
				$interface_so_obj = $this->get_iso_dao()->get(array("batch_id"=>$batch_id, "txn_id"=>$txn_id));
				return $interface_so_obj;
			}
		}
	}


	private function insert_interface_so($client_trans_id)
	{
		$batch_error = 0;
		if (!$client_trans_id)
		{
			$error_msg = 'Function insert_interface_so(): $client_trans_id cannot be empty';
			throw new Exception($error_msg);
		}
		else
		{
			$interface_so_obj = $this->get_iso_dao()->get();
			$interface_so_obj->set_client_trans_id($client_trans_id);
			$interface_so_obj->set_status('1');
			$interface_so_obj->set_cost('0');
			$interface_so_obj->set_weight('0');
			$interface_so_obj->set_order_create_date($this->create_order_time);
			$interface_so_obj->set_batch_status('N');
			$interface_so_obj->set_platform_id($this->platform_id);
			$interface_so_obj->set_platform_order_id($this->platform_order_id);
			$interface_so_obj->set_txn_id($this->txn_id);
			$interface_so_obj->set_biz_type($this->biz_type);
			$interface_so_obj->set_amount($this->total_amount_paid);
			$interface_so_obj->set_vat_percent($this->vat_percent );
			$interface_so_obj->set_rate($this->rate );
			$interface_so_obj->set_currency_id($this->platform_currency_id);
			$interface_so_obj->set_lang_id($this->lang_id);
			$interface_so_obj->set_ref_1($this->ref_1);
			$interface_so_obj->set_delivery_charge($this->delivery_charge);
			$interface_so_obj->set_delivery_type_id($this->courier);
			$interface_so_obj->set_bill_name($this->bill_name);
			$interface_so_obj->set_bill_address($this->bill_address["address"]);
			$interface_so_obj->set_bill_postcode($this->bill_address["postcode"]);
			$interface_so_obj->set_bill_city($this->bill_address["city"]);
			$interface_so_obj->set_bill_state($this->bill_address["state"]);
			$interface_so_obj->set_bill_country_id($this->bill_address["country_id"]);

			/* Get interface_client by trans_id*/
			$client_intergration_srv = $this->get_client_integration();
			$interface_client_vo = $client_intergration_srv->get_interface_client_vo($client_trans_id);
			$interface_so_obj->set_batch_id($interface_client_vo->get_batch_id());
			$interface_so_obj->set_delivery_name($interface_client_vo->get_del_name());
			$interface_so_obj->set_delivery_postcode($interface_client_vo->get_del_postcode());
			$interface_so_obj->set_delivery_city($interface_client_vo->get_del_city());
			$interface_so_obj->set_delivery_state($interface_client_vo->get_del_state());
			$interface_so_obj->set_delivery_country_id($interface_client_vo->get_del_country_id());
			if($interface_client_vo->get_del_address_2() || $interface_client_vo->get_del_address_3())
			{
				$delivery_address = $interface_client_vo->get_del_address_1()."|".$interface_client_vo->get_del_address_2()."|".$interface_client_vo->get_del_address_3();
				$interface_so_obj->set_delivery_address($delivery_address);
			}
			else
			{
				$interface_so_obj->set_delivery_address($interface_client_vo->get_del_address_1());
			}

			$interface_so_obj = $this->get_iso_dao()->insert($interface_so_obj);
			if($interface_so_obj === FALSE)
			{
				$error_msg = "Error Table: Interface_so\nError Msg: ".$this->get_iso_dao()->db->_error_message()."\nError SQL:".$this->get_iso_dao()->db->_error_message()."\n";
				$batch_error = 1;
				echo "$error_msg";
			}
			else
			{
				return $interface_so_obj;
			}

			if($batch_error)
			{
				$this->send_notification_email("BE", $this->platform_id , $this->website, $error_msg);
			}
		}
	}

	private function insert_interface_so_item($so_trans_id, $last_line_no)
	{
		$batch_error = 0;
		if (!$so_trans_id || !$last_line_no)
		{
			$error_msg = 'Function insert_interface_so_item(): $so_trans_id and $last_line_no cannot be empty.'."\nSo_trans_id: < $so_trans_id >; Last_line_no: < $last_line_no >.\n";
			throw new Exception($error_msg);
		}
		else
		{
			$interface_so_item_obj = $this->get_isoi_dao()->get();
			$interface_so_item_obj->set_batch_id($this->batch_id);
			$interface_so_item_obj->set_so_trans_id($so_trans_id);
			$interface_so_item_obj->set_line_no($last_line_no);
			$interface_so_item_obj->set_prod_sku($this->prod_sku);
			$interface_so_item_obj->set_prod_name($this->prod_name);
			$interface_so_item_obj->set_ext_item_cd($this->ext_item_cd);
			$interface_so_item_obj->set_qty($this->qty);
			$interface_so_item_obj->set_unit_price($this->unit_price);
			$interface_so_item_obj->set_vat_total($this->vat_total);
			$interface_so_item_obj->set_amount($this->unit_price * $this->qty);
			$interface_so_item_obj->set_status('0');
			$interface_so_item_obj->set_batch_status('N');

			/* insert info into db interface_so_item */
			$interface_so_item_vo = $this->get_isoi_dao()->insert($interface_so_item_obj);

			if($interface_so_item_vo === FALSE)
			{
				$error_msg = "Error Table: Interface_so_item\nError Msg: ".$this->get_isoi_dao()->db->_error_message()."\nError SQL:".$this->get_isoi_dao()->db->last_query()."\n";
				$batch_error = 1;
			}
			else
			{
				$result = $this->add_complementary_acc_isoi($so_trans_id, $last_line_no);
				if($result["status"] === false)
				{
					$error_msg .= "Cannot add CA for interface_so_item; so_trans_id <$so_trans_id>, sku <{$this->prod_sku}>. \n".$result["error_msg"];
					$batch_error = 1;
				}
				else
				{
					return $interface_so_item_vo;
				}
			}

			if($batch_error)
			{
				echo $error_msg;
				$this->send_notification_email("BE", $this->platform_id , $this->website, $error_msg);
			}
		}
	}

	private function add_complementary_acc_isoi($so_trans_id, $line_no)
	{
		#SBF #4324 - include mapped complementary accessories
		$result = array();
		$result["status"] = TRUE;
		$last_line_no = $line_no + 1;
		$where["dest_country_id"] = $this->platform_country_id;
		$where["mainprod_sku"] = $this->prod_sku;
		$mapped_ca_list = $this->get_ca_service()->get_mapped_acc_list_w_name($where, $option, true);

		if( ($mapped_ca_list = $this->get_ca_service()->get_mapped_acc_list_w_name($where, $option, true)) === FALSE)
		{
			$result["error_msg"] = "order_integration_service.php"."Line ".__LINE__." Error Table: product_complementary_acc\nError Msg: ".$this->get_ca_service()->get_complementary_acc_dao()->db->_error_message()."\nError SQL:".$this->get_ca_service()->get_complementary_acc_dao()->db->last_query()."\n";
			$result["status"] = FALSE;
		}
		else
		{
			if($mapped_ca_list !== NULL)
			{
				foreach ($mapped_ca_list as $ca_obj)
				{
					$interface_so_item_obj = $this->get_isoi_dao()->get();
					$interface_so_item_obj->set_batch_id($this->batch_id);
					$interface_so_item_obj->set_so_trans_id($so_trans_id);
					$interface_so_item_obj->set_line_no($last_line_no);
					$interface_so_item_obj->set_prod_sku($ca_obj->get_accessory_sku());
					$interface_so_item_obj->set_prod_name($ca_obj->get_name());
					// $interface_so_item_obj->set_ext_item_cd("");
					$interface_so_item_obj->set_qty($this->qty);
					$interface_so_item_obj->set_unit_price(0);
					$interface_so_item_obj->set_vat_total(0);
					$interface_so_item_obj->set_amount(0);
					$interface_so_item_obj->set_status('0');
					$interface_so_item_obj->set_batch_status('N');

					/* insert info into db interface_so_item */
					$interface_so_item_vo = $this->get_isoi_dao()->insert($interface_so_item_obj);
					echo "<pre>-- DEBUG: ISOI added CA sku <{$ca_obj->get_accessory_sku()}></pre>";

					if($interface_so_item_vo === FALSE)
					{
						$result["error_msg"] .= "order_integration_service.php"."LINE ".__LINE__." Error Table: Interface_so_item\nError Msg: ".$this->get_isoi_dao()->db->_error_message()."\nError SQL:".$this->get_isoi_dao()->db->last_query()."\n";
						$result["status"] = FALSE;
					}

					$last_line_no++;
				}
			}
		}
		return $result;

	}

	private function insert_interface_so_item_detail($batch_id, $last_line_no, $interface_so_obj)
	{
		if (!$batch_id || !$last_line_no || !$interface_so_obj)
		{
			$error_msg = 'Function insert_interface_so_item_detail(): Either of the following is empty.'."\nBatch_id: < $batch_id >; \nLast_line_no: < $last_line_no >; \n**Also check that interface_so_obj is not null**.";
			throw new Exception($error_msg);
		}
		else
		{
			$batch_error = 0;
			$cost = 0;
			$so_trans_id = $interface_so_obj->get_trans_id();

			/* check if $prod_sku is a bundle */
			$bundle_list = $this->get_bundle_srv()->get_list(array("prod_sku"=>$this->prod_sku), array("component_order"=>"ORDERBY component_order ASC", "array_list"=>1));
			if($bundle_list)
			{
				foreach ($bundle_list as $bundle_obj)
				{
					$this->prod_sku = $bundle_obj->get_component_sku(); # this gives the product bundle sku

					//NOT YET SET price in product
					$interface_so_item_detail_obj = $this->get_isoid_dao()->get();
					$interface_so_item_detail_obj->set_batch_id($batch_id);
					$interface_so_item_detail_obj->set_so_trans_id($so_trans_id);
					$interface_so_item_detail_obj->set_line_no($last_line_no);
					$interface_so_item_detail_obj->set_item_sku($this->prod_sku);
					$interface_so_item_detail_obj->set_qty($this->qty);
					$interface_so_item_detail_obj->set_outstanding_qty($this->qty);
					$interface_so_item_detail_obj->set_unit_price($this->unit_price);
					$interface_so_item_detail_obj->set_vat_total($this->vat_total);
					$interface_so_item_detail_obj->set_discount('0');
					$interface_so_item_detail_obj->set_amount(0);
					$interface_so_item_detail_obj->set_cost('0');
					$interface_so_item_detail_obj->set_profit('0');
					$interface_so_item_detail_obj->set_margin('0');
					$interface_so_item_detail_obj->set_status('0');
					$interface_so_item_detail_obj->set_batch_status('N');

					$this->update_cost_profit($interface_so_item_detail_obj, $interface_so_obj);
					$cost += $interface_so_item_detail_obj->get_cost();

					/* insert info into db interface_so_item_detail */
					$interface_so_item_detail_obj = $this->get_isoid_dao()->insert($interface_so_item_detail_obj);

					if($interface_so_item_detail_obj === FALSE)
					{
						$error_msg = "Error Table: Interface_so_item_detail\nError Msg: ".$this->get_isoid_dao()->db->_error_message()."\nError SQL:".$this->get_isoid_dao()->db->_error_message()."\n";
						$batch_error = 1;
						break;
					}
					else
					{
						# add in complementary accessories
						$result = $this->add_complementary_acc_isoid($batch_id, $last_line_no, $interface_so_obj);
						if($result["status"] === false)
						{
							$error_msg .= "Cannot add CA for interface_so_item_detail; so_trans_id <$so_trans_id>, sku <{$this->prod_sku}>. \n".$result["error_msg"];
							$batch_error = 1;
						}
					}
				}
			}
			else
			{
				$interface_so_item_detail_obj = $this->get_isoid_dao()->get();
				$interface_so_item_detail_obj->set_batch_id($batch_id);
				$interface_so_item_detail_obj->set_so_trans_id($so_trans_id);
				$interface_so_item_detail_obj->set_line_no($last_line_no);
				$interface_so_item_detail_obj->set_item_sku($this->prod_sku);
				$interface_so_item_detail_obj->set_qty($this->qty);
				$interface_so_item_detail_obj->set_outstanding_qty($this->qty);
				$interface_so_item_detail_obj->set_unit_price($this->unit_price);
				$interface_so_item_detail_obj->set_vat_total($this->vat_total);
				$interface_so_item_detail_obj->set_amount($this->unit_price * $this->qty);
				$interface_so_item_detail_obj->set_discount('0');
				$interface_so_item_detail_obj->set_status('0');
				$interface_so_item_detail_obj->set_batch_status('N');

				$this->update_cost_profit($interface_so_item_detail_obj, $interface_so_obj);
				$cost += $interface_so_item_detail_obj->get_cost();

				/* insert info into db interface_so_item_detail */
				$interface_so_item_detail_obj = $this->get_isoid_dao()->insert($interface_so_item_detail_obj);

				if($interface_so_item_detail_obj === FALSE)
				{
					$error_msg = "Error Table: Interface_so_item_detail\nError Msg: ".$this->get_isoid_dao()->db->_error_message()."\nError SQL:".$this->get_isoid_dao()->db->_error_message()."\n";
					$batch_error = 1;
				}
				else
				{
					# add in complementary accessories
					# complementary acc's costs have been calculated in main item's profit_margin_json, so don't add it on to $cost
					$result = $this->add_complementary_acc_isoid($batch_id, $last_line_no, $interface_so_obj);
					if($result["status"] === false)
					{
						$error_msg .= "Cannot add CA for interface_so_item_detail; so_trans_id <$so_trans_id>, sku <{$this->prod_sku}>. \n".$result["error_msg"];
						$batch_error = 1;
					}
				}
			}

			$interface_so_obj->set_cost($interface_so_obj->get_cost()*1 + $cost);

			if($this->get_iso_dao()->update($interface_so_obj) === FALSE)
			{
				$error_msg = "Error Table: Interface_so\nError Msg: ".$this->get_iso_dao()->db->_error_message()."\nError SQL:".$this->get_iso_dao()->db->_error_message()."\n";
				$batch_error = 1;
			}
			else
			{

				return $interface_so_item_detail_obj;
			}

			if($batch_error)
			{
				echo $error_msg;
				$this->send_notification_email("BE", $this->platform_id , $this->website, $error_msg);
			}
		}
	}

	private function add_complementary_acc_isoid($batch_id, $line_no, $interface_so_obj)
	{
		#SBF #4324 - include mapped complementary accessories
		$result = array();
		$result["status"] = TRUE;
		$last_line_no = $line_no + 1;
		$so_trans_id = $interface_so_obj->get_trans_id();

		$where["dest_country_id"] = $this->platform_country_id;
		$where["mainprod_sku"] = $this->prod_sku;
		$mapped_ca_list = $this->get_ca_service()->get_mapped_acc_list_w_name($where, $option, true);

		if( ($mapped_ca_list = $this->get_ca_service()->get_mapped_acc_list_w_name($where, $option, true)) === FALSE)
		{
			$result["error_msg"] = "order_integration_service.php"."Line ".__LINE__. " Error Table: product_complementary_acc\nError Msg: ".$this->get_ca_service()->get_complementary_acc_dao()->db->_error_message()."\nError SQL:".$this->get_ca_service()->get_complementary_acc_dao()->db->last_query()."\n";
			$result["status"] = FALSE;
		}
		else
		{
			if($mapped_ca_list !== NULL)
			{
				foreach ($mapped_ca_list as $ca_obj)
				{
					$interface_so_item_detail_obj = $this->get_isoid_dao()->get();
					$interface_so_item_detail_obj->set_batch_id($batch_id);
					$interface_so_item_detail_obj->set_so_trans_id($so_trans_id);
					$interface_so_item_detail_obj->set_line_no($last_line_no);
					$interface_so_item_detail_obj->set_item_sku($ca_obj->get_accessory_sku());
					$interface_so_item_detail_obj->set_qty($this->qty);
					$interface_so_item_detail_obj->set_outstanding_qty($this->qty);
					$interface_so_item_detail_obj->set_unit_price(0);
					$interface_so_item_detail_obj->set_vat_total(0);
					$interface_so_item_detail_obj->set_amount(0);
					$interface_so_item_detail_obj->set_discount('0');
					$interface_so_item_detail_obj->set_status('0');
					$interface_so_item_detail_obj->set_batch_status('N');

					$interface_so_item_detail_obj->set_cost(0);
					$interface_so_item_detail_obj->set_profit(0);
					$interface_so_item_detail_obj->set_margin(0);

					$this->update_cost_profit($interface_so_item_detail_obj, $interface_so_obj, true);

					/* insert info into db interface_so_item_detail */
					$interface_so_item_detail_obj = $this->get_isoid_dao()->insert($interface_so_item_detail_obj);
					echo "<pre>-- DEBUG: ISOID added CA sku <{$ca_obj->get_accessory_sku()}></pre>";

					if($interface_so_item_detail_obj === FALSE)
					{
						$result["error_msg"] .= "order_integration_service.php"."LINE ".__LINE__." Error Table: Interface_so_item_detail\nError Msg: ".$this->get_isoid_dao()->db->_error_message()."\nError SQL:".$this->get_isoid_dao()->db->_error_message()."\n";
						$result["status"] = FALSE;
					}

					$last_line_no++;
				}
			}
		}
		return $result;
	}

	private function update_cost_profit($soid_obj, $so_obj, $is_ca = false)
	{
		/*
			This functio requires respective price service files for customised values
			such as commission, vat, etc.
			Name your price service file price_[biz_type]_service.php,
			e.g. price_qoo10_service.php with class Price_[biz_type]_service
			This is to ensure that it will call correct price service to calculate
			different components used in profit_margin_json
		*/


		// echo "<pre>soid_obj: <pre>"; var_dump($soid_obj); echo "<pre>so_obj:<pre>";var_dump($so_obj);die(); #debug line
		if (!$soid_obj || !$so_obj)
		{
			$error_msg = 'Function update_cost_profit(): $soid_obj(so_item_detail) and $so_obj cannot be empty.';
			throw new Exception($error_msg);
		}
		else
		{
			if(empty($this->biz_type))
			{
				$error_msg = 'File: '.__FILE__."\nLine: ".__LINE__.' - Function update_cost_profit(): $this->biz_type cannot be empty; please set it using set_biz_type().';
				throw new Exception($error_msg);
			}
			else
			{
				$biz_type = strtolower($this->biz_type);
				$price_service_file = APPPATH."libraries/service/Price_{$biz_type}_service.php";

				$price_service_classname = "Price_{$biz_type}_service";
				if(file_exists($price_service_file) === FALSE)
				{
					$error_msg = 'File: '.__FILE__."\nLine: ".__LINE__.' - Function update_cost_profit(): file - '.$price_service_file.'.php does not exist.';
					throw new Exception($error_msg);
				}
				else
				{
					include_once $price_service_file;

					if(class_exists($price_service_classname) === FALSE)
					{
						$error_msg = 'File: '.__FILE__."\nLine: ".__LINE__.' - Function update_cost_profit(): classname -'.$price_service_classname.' in price_[biz_type]_service does not exist.';
						throw new Exception($error_msg);
					}
					else
					{
						$price_srv = new $price_service_classname();
						// $price_srv = $this->get_price_srv();
						$platform_id = $this->platform_id;
						$sku = $soid_obj->get_item_sku();
						$required_selling_price = $soid_obj->get_unit_price();
						$qty = $soid_obj->get_qty();

						if ($prod_obj = $this->get_product_srv()->get_dao()->get_product_overview(array("sku"=>$sku, "platform_id"=>$so_obj->get_platform_id()), array("limit"=>1, "skip_prod_status_checking" => 1)))
						{
							$json_data = $price_srv->get_profit_margin_json($platform_id, $sku, $required_selling_price);
							$profit_margin_arr = json_decode($json_data, true);

							// $prod_obj->set_price($required_selling_price);
							// $price_srv->calc_logistic_cost($prod_obj);
							// $price_srv->calc_cost($prod_obj);

							if($is_ca === false)
							{
								$soid_obj->set_cost($profit_margin_arr["get_cost"] * $qty);
								$soid_obj->set_profit($profit_margin_arr["get_profit"]);
								$soid_obj->set_margin($profit_margin_arr["get_margin"]);
								$this->get_so_srv()->set_profit_info($soid_obj);
								$this->get_so_srv()->set_profit_info_raw($soid_obj, $so_obj->get_platform_id());
							}
							else
							{
								# if complementary acc, just update cost as supplier cost
								$soid_obj->set_cost($profit_margin_arr["get_supplier_cost"] * $qty);
							}
						}
						else
						{
							$soid_obj->set_cost('0');
							$soid_obj->set_profit('0');
							$soid_obj->set_margin('0');
						}
					}
				}
			}
		}
	}


	private function insert_interface_so_payment_status($batch_id, $so_trans_id)
	{
		if (!$batch_id || !$so_trans_id)
		{
			$error_msg = 'Function insert_interface_so_payment_status() - One of the following is empty: '."\n Batch_id: < $batch_id >; \nSo_trans_id: < $so_trans_id >.";
			throw new Exception($error_msg);
		}
		else
		{
			$interface_so_list = $this->get_iso_dao()->get_list(array("batch_id"=>$batch_id));
			if($interface_so_list)
			{
				foreach($interface_so_list as $interface_so_obj)
				{
					$interface_so_payment_status_obj = $this->get_isops_dao()->get();
					$interface_so_payment_status_obj->set_batch_id($batch_id);
					$interface_so_payment_status_obj->set_so_trans_id($so_trans_id);
					$interface_so_payment_status_obj->set_payment_gateway_id($this->payment_gateway_id);
					$interface_so_payment_status_obj->set_remark("status:processed");
					$interface_so_payment_status_obj->set_payment_status("S");
					$interface_so_payment_status_obj->set_pay_to_account($this->pay_to_account);
					$interface_so_payment_status_obj->set_pay_date($this->pay_date);
					$interface_so_payment_status_obj->set_payer_email($this->payer_email);
					$interface_so_payment_status_obj->set_payer_ref($this->payer_id);
					$interface_so_payment_status_obj->set_risk_ref1($this->protection_eligibility);
					$interface_so_payment_status_obj->set_risk_ref2($this->protection_eligibility_type);
					$interface_so_payment_status_obj->set_risk_ref3($this->address_status);
					$interface_so_payment_status_obj->set_risk_ref4($this->payer_status);
					$interface_so_payment_status_obj->set_batch_status('N');

					$interface_so_payment_status_ret = $this->get_isops_dao()->insert($interface_so_payment_status_obj);

					if($this->need_cc === TRUE)
						$interface_so_obj->set_status(2); 	# paid; pending credit check
					else
						$interface_so_obj->set_status(3); 	# credit checked

					$this->get_iso_dao()->update($interface_so_obj);

					if ($interface_so_payment_status_ret === FALSE)
					{
						$error_msg = "Error Table: Interface_so_payment_status\nError Msg: ".$this->get_isops_dao()->db->_error_message()."\nError SQL:".$this->get_isops_dao()->db->_error_message()."\n";
						$this->send_notification_email("BE", $this->platform_id , $this->website, $error_msg);
					}
				}
			}
		}
	}


//================================================= commit_platform_batch related functions

	private function insert_so($client_id, $so_vo, $iso_obj)
	{
		if (!$client_id || !$so_vo || !$iso_obj)
		{
			$error_msg = 'Function insert_so(): $client_id, $so_vo and $iso_obj cannot be empty';
			throw new Exception($error_msg);
		}
		else
		{
			$so_dao = $this->get_so_dao();
			$so_obj = clone $so_vo;
			$seq = $so_dao->seq_next_val();
			$so_no = $seq;
			$so_dao->update_seq($seq);
			$iso_obj->set_so_no($so_no);
			$iso_obj->set_client_id($client_id);
			set_value($so_obj, $iso_obj);

			if($so_dao->insert($so_obj))
			{
				$insert_so["iso_batch_status"] = TRUE;
				$insert_so["failed_reason"] = "";
				$insert_so["so_no"] = $so_no;
				$insert_so["iso_obj"] = $iso_obj;
				$insert_so["so_obj"] = $so_obj;
				$insert_so["so_dao"] = $so_dao;
			}
			else
			{
				$insert_so["iso_batch_status"] = FALSE;
				$insert_so["failed_reason"] = "order_integration_service.php".__LINE__. ", so: ".$so_dao->db->_error_message() . ", " . $so_dao->db->last_query();
				$insert_so["so_no"] = "";
				$insert_so["iso_obj"] = "";
				$insert_so["so_obj"] = "";
				$insert_so["so_dao"] = "";
			}

			return $insert_so;
		}
	}

	private function insert_so_item($interface_so_item_obj, $so_no)
	{
		if(!$interface_so_item_obj || !$so_no)
		{
			$error_msg = 'Function insert_so_item(): $interface_so_item_obj and $so_no cannot be empty';
			throw new Exception($error_msg);
		}
		else
		{
			$so_item_dao = $this->get_soi_dao();
			$so_item_vo = $so_item_dao->get();

			#set actual so_no into interface_so_item
			$interface_so_item_obj->set_so_no($so_no);
			$so_item_obj = clone $so_item_vo;
			set_value($so_item_obj, $interface_so_item_obj);

			#set warranty and website status
			$prod_obj = $this->get_so_srv()->get_prod_srv()->get(array("sku" => $so_item_obj->get_prod_sku()));
			$so_item_obj->set_warranty_in_month($prod_obj->get_warranty_in_month());
			$so_item_obj->set_website_status($prod_obj->get_website_status());
			$so_item_obj->set_gst_total(0);

			if($so_item_dao->insert($so_item_obj))
			{
				$insert_so_item["iso_batch_status"] = TRUE;
				$insert_so_item["failed_reason"] = "";
				$insert_so_item["interface_so_item_obj"] = $interface_so_item_obj;
				$insert_so_item["so_item_obj"] = $so_item_obj;
				$insert_so_item["so_item_dao"] = $so_item_dao;
			}
			else
			{
				$insert_so_item["iso_batch_status"] = FALSE;
				$insert_so_item["failed_reason"] = "order_integration_service.php".__LINE__. ", so_item: ".$so_item_dao->db->_error_message();
				$insert_so_item["interface_so_item_obj"] = "";
				$insert_so_item["so_item_obj"] = "";
				$insert_so_item["so_item_dao"] = "";
			}

			return $insert_so_item;
		}
	}

	private function insert_so_item_detail($so_no, $isoid_obj)
	{
		if(!$so_no || !$isoid_obj)
		{
			$error_msg = 'Function insert_so_item_detail(): $so_no and $isoid_obj cannot be empty';
			throw new Exception($error_msg);
		}
		else
		{
			$so_item_detail_dao = $this->get_soid_dao();
			$soid_vo = $so_item_detail_dao->get();

			$isoid_obj->set_so_no($so_no);
			$so_item_detail_obj = clone $soid_vo;
			set_value($so_item_detail_obj, $isoid_obj);

			$so_item_detail_obj->set_item_unit_cost($this->get_sup_srv()->get_item_cost_in_hkd($isoid_obj->get_item_sku()));
			$so_item_detail_obj->set_gst_total(0);

			if($so_item_detail_dao->insert($so_item_detail_obj))
			{
				$insert_so_item_detail["iso_batch_status"] = TRUE;
				$insert_so_item_detail["failed_reason"] = "";
				$insert_so_item_detail["so_item_detail_obj"] = $so_item_detail_obj;
				$insert_so_item_detail["so_item_detail_dao"] = $so_item_detail_dao;
				$insert_so_item_detail["isoid_obj"] = $isoid_obj;
			}
			else
			{
				$insert_so_item_detail["iso_batch_status"] = FALSE;
				$insert_so_item_detail["failed_reason"] = "order_integration_service.php".__LINE__. ", so_item_detail: ".$so_item_detail_dao->db->_error_message();
				$insert_so_item_detail["so_item_detail_obj"] = "";
				$insert_so_item_detail["so_item_detail_dao"] = "";
				$insert_so_item_detail["isoid_obj"] = "";
			}
			return $insert_so_item_detail;
		}
	}

	private function insert_so_payment_status($batch_id, $so_trans_id, $so_no, $so_payment_status_vo)
	{
		if(!$batch_id ||! $so_trans_id || !$so_no || !$so_payment_status_vo)
		{
			$error_msg = "Function insert_so_payment_status() \nOne of the following is empty: \nBatch_id: < $batch_id >;
							\nSo_trans_id: < $so_trans_id >; \nSo_no: < $so_no >;
							\n\n*** Also check that so_payment_status_vo is not empty.";
			throw new Exception($error_msg);
		}
		else
		{
			$isops_dao = $this->get_isops_dao();
			$so_payment_status_dao = $this->get_sops_dao();

			$interface_so_payment_status_obj = $isops_dao->get(array("batch_id"=>$batch_id, "so_trans_id"=>$so_trans_id));
			$interface_so_payment_status_obj->set_so_no($so_no);
			$so_payment_status_obj = clone $so_payment_status_vo;
			set_value($so_payment_status_obj, $interface_so_payment_status_obj);

			if($so_payment_status_dao->insert($so_payment_status_obj))
			{
				$insert_so_payment_status["iso_batch_status"] = TRUE;
				$insert_so_payment_status["failed_reason"] = "";
				$insert_so_payment_status["isops_obj"] = $interface_so_payment_status_obj;
				$insert_so_payment_status["so_payment_status_obj"] = $so_payment_status_obj;
				$insert_so_payment_status["so_payment_status_dao"] = $so_payment_status_dao;
			}
			else
			{
				$insert_so_payment_status["iso_batch_status"] = FALSE;
				$insert_so_payment_status["failed_reason"] = "order_integration_service.php".__LINE__. ", so_payment_status: ".$isops_dao->db->_error_message();
				$insert_so_payment_status["isops_obj"] = "";
				$insert_so_payment_status["so_payment_status_obj"] = "";
				$insert_so_payment_status["so_payment_status_dao"] = "";
			}

			return $insert_so_payment_status;
		}
	}

	private function insert_soext($so_no, $so_obj)
	{
		if (!$so_no || !$so_obj)
		{
			$error_msg = 'Function insert_soext(): $so_no and $so_obj cannot be empty';
			throw new Exception($error_msg);
		}
		else
		{
			$soext_dao = $this->get_so_srv()->get_soext_dao();
			$soext_vo = $soext_dao->get();

			$soext_obj = clone $soext_vo;
			$soext_obj->set_so_no($so_no);
			$soext_obj->set_acked("N");
			$soext_obj->set_fulfilled("N");
			$entity_id = $this->get_so_srv()->get_entity_srv()->get_entity_id($so_obj->get_amount(), $so_obj->get_currency_id());
			$soext_obj->set_entity_id($entity_id);

			if(!$soext_dao->insert($soext_obj))
			{
				$insert_soext["iso_batch_status"] = FALSE;
				$insert_soext["failed_reason"] = "order_integration_service.php".__LINE__. ", so_extend: ".$soext_dao->db->_error_message();
				$insert_soext["soext_obj"] = "";
				$insert_soext["soext_dao"] = "";
			}
			else
			{
				$insert_soext["iso_batch_status"] = TRUE;
				// $insert_soext["failed_reason"] = "";
				// $insert_soext["soext_obj"] = $soext_obj;
				// $insert_soext["soext_dao"] = $soext_dao;
			}

			return $insert_soext;
		}
	}

	public function insert_order_notes($so_no, $order_notes, $platform_id, $website)
	{
		if(!$so_no || !$order_notes)
		{
			$error_msg = 'Function set_order_notes(): $so_no and $order_notes cannot be empty';
			throw new Exception($error_msg);
		}
		else
		{

			$order_notes_srv = $this->get_order_notes_srv();
			$order_notes_obj = $order_notes_srv->get_dao()->get(array("so_no"=>$so_no));

			if($order_notes_obj)
			{
				echo "<pre>order_notes existed with so ($so_no)</pre>";
				$error_msg = "order_integration_service.php".__LINE__. ", order_notes: unable to insert order note <'$order_notes'>. so_no ($so_no) already existed. \n";
				$this->send_notification_email("OE", $platform_id, $website, $error_msg); #order_notes_error
			}
			else
			{

				$order_notes_obj = $order_notes_srv->get_dao()->get();
				$order_notes_obj->set_so_no($so_no);
				$order_notes_obj->set_type('O');
				$order_notes_obj->set_note($order_notes);
				echo "<pre>Creating order_notes with so ($so_no). order_note: $order_notes</pre>";

				if($order_notes_srv->get_dao()->insert($order_notes_obj))
				{
					echo "<pre>DEBUG: INSERT_ORDER_NOTES SUCCESS: '$order_notes'. SO_NO: $so_no</pre>";

				}
				else
				{
					echo "DEBUG: FAILED INSERT_ORDER_NOTES";

					$error_msg = "order_integration_service.php".__LINE__. ", order_notes: unable to insert order note <'$order_notes'> into so_no ($so_no). \n".$order_notes_dao->db->_error_message();
					$this->send_notification_email("OE", $platform_id, $website, $error_msg); #order_notes_error
				}

				// var_dump($order_notes_srv->get_dao()->db->last_query());
			}
		}
	}

	public function insert_so_priority_score($so_no, $so_priority_score, $platform_id, $website)
	{
		if(!$so_no || !$so_priority_score)
		{
			$error_msg = "Function insert_so_priority_score(): one of the following is empty. \n so_no: < $so_no >; so_priority_score: < $so_priority_score >";
			throw new Exception($error_msg);
		}
		else
		{
			$so_priority_score_dao = $this->get_so_priority_score_dao();
			$so_priority_score_obj = $so_priority_score_dao->get(array("so_no"=>$so_no));
			if(!$so_priority_score_obj)
			{
				$so_priority_score_obj = $so_priority_score_dao->get();
				$so_priority_score_obj->set_so_no($so_no);
				$so_priority_score_obj->set_score($so_priority_score);
				if(!$so_priority_score_dao->insert($so_priority_score_obj))
				{
					$error_msg = "order_integration_service.php".__LINE__. ", so_priority_score: unable to insert so priority score <$so_priority_score> into so_no ($so_no). \n".$so_priority_score_dao->db->_error_message();
					$this->send_notification_email("PS", $platform_id, $website, $error_msg); #priority_score_error
				}
				else
				{
					echo "<pre>DEBUG: INSERT_SO_PRIORITY_SCORE SUCCESS. so_no: $so_no; so_priority_score: $so_priority_score.";
				}
			}
			else
			{
				$so_priority_score_obj->set_score($so_priority_score);
				if($so_priority_score_dao->db->_error_message())
				{
					$error_msg = "order_integration_service.php".__LINE__. ", so_priority_score: unable to update so priority score <$so_priority_score> into so_no ($so_no). \n".$so_priority_score_dao->db->_error_message();
					$this->send_notification_email("PS", $platform_id, $website, $error_msg); #priority_score_error
				}
				else
				{
					echo "<pre>DEBUG: UPDATE SUCCESS. so_no: $so_no; so_priority_score: $so_priority_score.";
				}
				// $error_msg = "order_integration_service.php".__LINE__. ", so_priority_score: unable to insert so_priority_score. so_no ($so_no) already exist." ;
				// $this->send_notification_email("PS", $platform_id, $website, $error_msg); #priority_score_error
			}
		}
	}


//============================================================================



	public function send_notification_email($pending_action, $platform_id, $website, $error_msg = "")
	{
		switch ($pending_action)
		{
			case "BE":
				$message = $error_msg;
				$title = "[$platform_id - $website] Retrieve $platform_id order problems - BATCH_ERROR";
				break;
			case "BP":
				$message = $error_msg;
				$title = "[$platform_id - $website] Retrieve $platform_id order  problems - BATCH_UPDATE_PROBLEM";
				break;
			case "OE":
				$message = $error_msg;
				$title = "[$platform_id - $website] Insert order_notes problems - ORDER_NOTES_ERROR";
				break;
			case "PS":
				$message = $error_msg;
				$title = "[$platform_id - $website] Insert so_priority_score problems - PRIORIY_SCORE_ERROR";
				break;

		}
		mail($this->notification_email, $title, $message);

	}

	private function get_last_line_no($so_trans_id)
	{
		if (!$so_trans_id)
		{
			$error_msg = 'Function get_last_line_no(): $so_trans_id cannot be empty.';
			throw new Exception($error_msg);
		}
		else
		{
			/* If items have been inserted by the same batch_id, get the last line_no inserted in interface_so_item */
			$old_interface_so_item_obj = $this->get_isoi_dao()->get_list(array("so_trans_id"=>$so_trans_id), array("limit"=>1, "orderby"=>"line_no DESC"));
			if ($old_interface_so_item_obj)
			{
				$last_line_no = (int)$old_interface_so_item_obj->get_line_no();
				$last_line_no = $last_line_no + 1;
				return ($last_line_no);
			}
			else
			{
				$last_line_no = 1;
				return $last_line_no;
			}
		}
	}


// ================================= FILL IN BILLING INFO ===================================================
	public function set_bill_name($bill_name)
	{
		# If you don't have a billing name, use client's name
		if (!$bill_name)
		{
			$error_msg = 'Function set_bill_name(): $set_bill_name cannot be empty.';
			throw new Exception($error_msg);
		}
		else
		{
			$this->bill_name = $bill_name;
		}
	}

	public function set_bill_address($country_id, $postcode, $address, $city="", $state="")
	{
		if (!$address || !$postcode || !$country_id)
		{
			$error_msg = "Function set_bill_address(): address<$address>, postcode<$postcode> and country_id<$country_id> cannot be empty";
			throw new Exception($error_msg);
		}
		else
		{
			$bill_address["address"] = $address;
			$bill_address["city"] = $city;
			$bill_address["state"] = $state;
			$bill_address["postcode"] = $postcode;
			$bill_address["country_id"] = $country_id;
			$this->bill_address = $bill_address;
		}
	}


// ================================= FILL IN ORDER-RELATED INFO ===================================================


	public function get_batch_id($batch_remark, $platform_id, $website = "valuebasket")
	{
		/* Each time you run your platform integration job, it will generate a batch_id in batch table */
		/* This function gets batch_id if already exist, else create new entry */

		$batch = $this->get_batch_srv()->get(array("remark"=>$batch_remark));

		if (empty($batch))
		{
			$batch_obj = $this->get_batch_srv()->get();
			$batch_obj->set_func_name($platform_id."_".$website);
			$batch_obj->set_status("N");
			$batch_obj->set_listed("1");
			$batch_obj->set_remark($batch_remark);
			$this->get_batch_srv()->insert($batch_obj);
		}
		else
		{
			$error_msg = "BATCH ERROR: [$website] - function get_batch_id() \nBatch_id: ".$batch->get_id()."\nRemark: ".$batch->get_remark();
			$this->send_notification_email("BE", $platform_id, $website, $error_msg); #batch_error

		}

		if($batch_obj)
		{
			// $this->batch_id = $batch_obj->get_id();
			return $batch_obj->get_id();
		}
	}

	public function set_batch_id($batch_id)
	{
		if (!$batch_id)
		{
			$error_msg = 'Function set_batch_id(): $batch_id cannot be empty.';
			throw new Exception($error_msg);
		}
		else
		{
			$this->batch_id = $batch_id;
		}
	}

	public function set_biz_type($biz_type)
	{
		if (!$biz_type)
		{
			$error_msg = 'Function set_biz_type(): $biz_type cannot be empty.';
			throw new Exception($error_msg);
		}
		else
		{
			$this->biz_type = $biz_type;
		}
	}

	public function set_platform_id($platform_id)
	{
		if (!$platform_id)
		{
			$error_msg = 'Function set_platform_id(): $platform_id cannot be empty.';
			throw new Exception($error_msg);
		}
		else
		{
			$this->platform_id = strtoupper($platform_id);
		}
	}

	public function set_website($website)
	{
		if (!$website)
		{
			$error_msg = 'Function set_website(): $website cannot be empty.';
			throw new Exception($error_msg);
		}
		else
		{
			$this->website = strtoupper($website);
		}
	}

	public function set_interface_client_vo($interface_client_vo)
	{
		if (!$interface_client_vo)
		{
			$error_msg = 'Function set_interface_client_vo(): $interface_client_vo cannot be empty.';
			throw new Exception($error_msg);
		}
		else
		{
			$this->interface_client_vo = $interface_client_vo;
		}
	}

	public function set_create_order_time($create_order_time)
	{
		if (!$create_order_time)
		{
			$error_msg = 'Function set_create_order_time(): $create_order_time cannot be empty.';
			throw new Exception($error_msg);
		}
		else
		{
			$this->create_order_time = (date("Y-m-d H:i:s", strtotime($create_order_time)));
		}
	}

	public function set_pay_date($pay_date)
	{
		if (!$pay_date)
		{
			$error_msg = 'Function set_pay_date(): $pay_date cannot be empty.';
			throw new Exception($error_msg);
		}
		else
		{
				$this->pay_date = (date("Y-m-d H:i:s", strtotime($pay_date)));
		}
	}

	public function set_currency_id($currency_id)
	{
		if (!$currency_id)
		{
			$error_msg = 'Function set_currency_id(): $currency_id cannot be empty.';
			throw new Exception($error_msg);
		}
		else
		{
			$this->currency_id = strtoupper($currency_id);
		}
	}

	public function set_qty($qty)
	{
		if (!$qty)
		{
			$error_msg = 'Function set_qty(): $qty cannot be empty.';
			throw new Exception($error_msg);
		}
		else
		{
			$this->qty = (int)$qty;
		}
	}

	public function set_unit_price($unit_price)
	{
		if (!$unit_price)
		{
			$error_msg = 'Function set_unit_price(): $unit_price cannot be empty.';
			throw new Exception($error_msg);
		}
		else
		{
			$this->unit_price = (float)$unit_price;
		}
	}

	public function set_amount($total_amount_paid)
	{
		# amount(paid per sku) * qty
		if (is_null($total_amount_paid))
		{
			$error_msg = 'Function set_amount(): $total_amount_paid cannot be null.';
			throw new Exception($error_msg);
		}
		else
		{
			$this->total_amount_paid = $total_amount_paid;
		}
	}

	// public function set_amount($total_amount_paid)
	// {
	// 	# amount(paid per sku) * qty
	// 	if (is_null($total_amount_paid))
	// 	{
	// 		$error_msg = 'Function set_amount(): $total_amount_paid cannot be null.';
	// 		throw new Exception($error_msg);
	// 	}
	// 	else
	// 	{
	// 		$this->total_amount_paid = $total_amount_paid;
	// 	}
	// }

	public function set_platform_order_id($platform_order_id)
	{
		if (!$platform_order_id)
		{
			$error_msg = 'Function set_platform_order_id(): $platform_order_id cannot be empty.';
			throw new Exception($error_msg);
		}
		else
		{
			$this->platform_order_id = $platform_order_id;
		}
	}

	public function set_delivery_charge($delivery_charge = "0")
	{
		$this->delivery_charge = $delivery_charge;
	}

	public function set_weight($weight)
	{
		if (!$weight)
		{
			$error_msg = 'Function set_weight(): $weight cannot be empty.';
			throw new Exception($error_msg);
		}
		else
		{
			$this->weight = $weight;
		}
	}

	public function set_prod_sku($prod_sku)
	{
		// if (!$prod_sku)
		// {
		// 	$error_msg = 'Function set_prod_sku(): $prod_sku cannot be empty.';
		// 	throw new Exception($error_msg);
		// }
		// else
		{
			$this->prod_sku = $prod_sku;
		}
	}

	public function set_prod_name($prod_name)
	{
		if (!$prod_name)
		{
			$error_msg = 'Function set_prod_name(): $prod_name cannot be empty.';
			throw new Exception($error_msg);
		}
		else
		{
			$this->prod_name = $prod_name;
		}
	}

	public function set_ext_item_cd($ext_item_cd)
	{
		// this the is external item ID assigned by third-party platforms
		if (!$ext_item_cd)
		{
			$error_msg = 'Function set_ext_item_cd(): $ext_item_cd cannot be empty.';
			throw new Exception($error_msg);
		}
		else
		{
			$this->ext_item_cd = (string)$ext_item_cd;
		}
	}

	public function set_txn_id($txn_id = "")
	{
		// this is mostly for paypal
		$this->txn_id = $txn_id;
	}


	public function set_payment_gateway_id($payment_gateway_id)
	{
		if (!$payment_gateway_id)
		{
			$error_msg = 'Function set_payment_gateway_id(): $payment_gateway_id cannot be empty.';
			throw new Exception($error_msg);
		}
		else
		{
			$this->payment_gateway_id = $payment_gateway_id;
		}
	}

	public function set_pay_to_account($pay_to_account)
	{
		if (!$pay_to_account)
		{
			$error_msg = 'Function set_pay_to_account(): $pay_to_account cannot be empty.';
			throw new Exception($error_msg);
		}
		else
		{
			$this->pay_to_account = $pay_to_account;
		}
	}

	public function set_order_as_paid($paid_order_status = FALSE)
	{
		if ($paid_order_status === TRUE || $paid_order_status === FALSE)
		{
			#TRUE = order has been paid
			$this->paid_order_status = $paid_order_status;
		}
		else
		{
			$error_msg = 'Function set_order_as_paid(): $paid_order_status can only accept TRUE or FALSE values. Please check.';
			throw new Exception($error_msg);
		}
	}

	public function need_credit_check($need_cc = FALSE)
	{
		if ($need_cc === TRUE || $need_cc === FALSE)
		{
			#TRUE = need credit check, so.status = 2
			$this->need_cc = $need_cc;
		}
		else
		{
			$error_msg = 'Function set_order_as_paid(): $need_cc can only accept TRUE or FALSE values. Please check.';
			throw new Exception($error_msg);
		}
	}

	public function set_payer_email($payer_email)
	{
		if (!$payer_email)
		{
			$error_msg = 'Function set_payer_email(): $payer_email cannot be empty.';
			throw new Exception($error_msg);
		}
		else
		{
			// payer's email in payment gateway
			$this->payer_email = $payer_email;
		}
	}

	public function set_payer_ref($payer_id="")
	{
		// PayPal: insert 'PAYERID' (refer to db interface_so_payment_status)
		$this->payer_id = $payer_id;
	}

	public function set_risk_ref1($protection_eligibility="")
	{
		// PayPal: insert 'PROTECTIONELIGIBILITY' / GlobalCollect: insert 'AVSRESULT' (refer to db interface_so_payment_status)
		$this->protection_eligibility = $protection_eligibility;
	}

	public function set_risk_ref2($protection_eligibility_type="")
	{
		// 'PayPal:PROTECTIONELIGIBILITYTYPE' (refer to db interface_so_payment_status)
		$this->protection_eligibility_type = $protection_eligibility_type;
	}

	public function set_risk_ref3($address_status="")
	{
		// 'PayPal:ADDRESSSTATUS' (refer to db interface_so_payment_status)
		$this->address_status = $address_status;
	}

	public function set_risk_ref4($payer_status="")
	{
		// 'PayPal:PAYERSTATUS' (refer to db interface_so_payment_status)
		$this->payer_status = $payer_status;
	}

	public function set_priority_score($score)
	{
		$this->score[ $this->current_so_trans_id ] = $score;
	}

}