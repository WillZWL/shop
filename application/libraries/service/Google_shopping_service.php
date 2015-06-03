<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";
include_once(BASEPATH . "plugins/gshoppingcontent/GShoppingContent.php");

class Google_shopping_service extends Base_service
{
	private $googlebase_product_feed_service;
	private $platform_biz_var_service;
	private $context_config_service;
	private $email_list = array();
	private $email_cc_list = array();
	private $cache_api_request_dao;
	private $config_dao;

	public function Google_shopping_service($tool_path = 'marketing/pricing_tool')
	{
		parent::__construct();

		include_once(APPPATH."libraries/dao/Google_shopping_dao.php");
		$this->set_dao(new Google_shopping_dao());

		include_once(APPPATH."libraries/dao/Cache_api_request_dao.php");
		$this->set_cache_api_request_dao(new Cache_api_request_dao());

		include_once(APPPATH."libraries/service/Platform_biz_var_service.php");
		$this->platform_biz_var_service = new Platform_biz_var_service();

		include_once(APPPATH."libraries/dao/Price_dao.php");
		$this->set_price_dao(new Price_dao());

		include_once(APPPATH . 'libraries/service/Context_config_service.php');
		$this->context_config_service = new Context_config_service();

		include_once(APPPATH . 'libraries/dao/Config_dao.php');
		$this->config_dao = new Config_dao();


		set_time_limit(0);
	}



	/* SBF #6199 - API V1 Login method, should not be in use anymore.  */
	public function shopping_api_login($account_id)
	{
		// DEFINE('S_EMAIL', 'edward@valuebasket.com');
		DEFINE('S_EMAIL', 'google-eu@valuebasket.com');
		DEFINE('S_PASSWORD', 'happycustomers888');
		DEFINE('S_APPLICATION_NAME', 'shopping content');

		$client = new GSC_Client($account_id);
		$client->clientLogin(S_EMAIL, S_PASSWORD, S_APPLICATION_NAME);
		return $client;
	}



	public function get_shopping_api_accountId($platform_id)
	{
		if($this->context_config_service->value_of('is_dev_site'))
		{
			return false;
			// return "11073443"; # test account
		}

		$this->set_email_list($platform_id);


		switch($platform_id)
		{
			case "WEBIT": return "9674225"; break;
			case "WEBFR": return "7852736";	break;
			case "WEBAU": return "8113126"; break;
			case "WEBBE": return "8121966"; break;
			case "WEBGB": return "8551995"; break;
			case "WEBCH": return "11328624"; break;
			//case "WEBFI": return "11038072"; break;
			case "WEBES": return "15241301"; break;
			case "WEBPL": return "100892246"; break;
			case "WEBUS": return "101019203"; break;
			//case "WEBSG": return "8384686"; break;
			default: return "";
		}

	}

	public function cache_api_exec_debug()
	{
		$debug = FALSE;
		if(strpos($_SERVER["HTTP_HOST"], "dev") != FALSE)
			$debug = TRUE;

		$where = $option = array();
		$where["api"] = "GSC";
		$where["exec"] = 0;
		$option["limit"] = -1;

		if($config_vo = $this->config_dao->get(array("variable"=>"google_shopping_api_at_job")))
		{
			$config_vo->set_value(0);
			$this->config_dao->update($config_vo);
		}

// $cache_api_list = $this->get_cache_api_request_dao()->get_list($where, $option); echo "<pre>"; var_dump($cache_api_list);die();
		if($cache_api_list = $this->get_cache_api_request_dao()->get_list($where, $option))
		{
			// set API jobs as being executed
			foreach($cache_api_list as $api_obj)
			{
				// pingtest
				$api_obj->set_exec(1);
				if(! $debug)
				{
					//pingtest
					$this->get_cache_api_request_dao()->update($api_obj);
				}
			}

			$i = 0;
			foreach($cache_api_list as $api_obj)
			{
				$sku = $api_obj->get_sku();
				$platform_id = $api_obj->get_platform_id();
				$stock_status = $api_obj->get_stock_update();

				if($i == 30)
					sleep(5);

				$account_id = $this->get_shopping_api_accountId($platform_id);
				if($debug)
				{
					// $account_id = 8113126;  //pingtest
				}

				if(!$account_id)
				{
					if($debug) echo "No accountID for $platform_id";
					continue;
				}
				else
				{
					$exec = false;
					if($stock_status == "PAUSED")
					{
						$platform_obj = $this->platform_biz_var_service->get(array("selling_platform_id"=>$platform_id));
						$platform_country_id = substr($platform_id,3);
						$language_id = $platform_obj->get_language_id();
						$google_ref_id = $platform_country_id.'-'.$sku;

						// Make sure your product ID is of the form channel:languageCode:countryCode:offerId.
						list($id, $country, $language) = array($google_ref_id, $platform_country_id, $language_id);
						$postdata["productid"] = "online:$language_id:$platform_country_id:$id";
						$getproduct_result = $this->shopping_api_connect('getproduct', $account_id, $debug, $postdata);

						if($getproduct_result["status"] == TRUE)
						{
							$exec = TRUE;
						}
						else
						{

							// >> [GOOGLE_ERR]call:insertproduct- 812 File: /var/www/html/Google/google_connect.php. Caught exception: Error calling POST https://www.googleapis.com/content/v2/8113126/products: (400) [description] validation/invalid_character for AffiliateNetwork,DisplayAds,Shopping,ShoppingApi: Encoding problem in attribute: description
							# if item not found, means already deleted (normal; not reall error). Else, it may be some other errors that must be reported
							if(strpos($getproduct_result["error_message"], "item not found") === FALSE)
							{
								$record_comment = $getproduct_result["error_handler"];
								$this->GSC_error_handler_record($record_comment, $sku, $platform_id);

								$result["getProduct"] .= " \r\n ----- \r\n" . __LINE__." Error from google_connect [$sku - $platform_id]: \r\n{$getproduct_result["error_message"]} \r\n";
							}
						}

						if($exec)
						{
							// Product exists, proceed to delete
							$deleteproduct_result = $this->shopping_api_connect('deleteproduct', $account_id, $debug, $postdata);
							if($deleteproduct_result["status"] == TRUE)
							{
								//if success
								$this->api_request_result_update($sku, $platform_id, 1, "");
								$data["deleted"] .= "[$sku - $platform_id]\r\n";
							}
							else
							{
								if(strpos($deleteproduct_result["error_handler"], "item not found") === FALSE)
								{
									$record_comment = $deleteproduct_result["error_handler"];
									$this->GSC_error_handler_record($record_comment, $sku, $platform_id);

									$result["deleteProduct"] .= " \r\n ----- \r\n" . __LINE__." Error from google_connect [$sku - $platform_id]: {$deleteproduct_result["error_message"]} \r\n";
								}
							}
						}
					}
					else
					{
						$gsc_product_result = $this->get_GSC_product($sku, $platform_id);
						// echo "<pre>";var_dump("GSCPRODUCT"); var_dump($gsc_product_result);

						if($gsc_product_result["status"] == TRUE)
						{
							$GSC_product = $gsc_product_result["product"];
							//insert the item, if item already exists, then it will update it
							$postdata["product"] = $GSC_product;
							$insertproduct_result = $this->shopping_api_connect('insertproduct', $account_id, $debug, $postdata);

							if($insertproduct_result)
							{
								if($insertproduct_result["status"] == TRUE)
								{
									//if success
									$this->api_request_result_update($sku, $platform_id, 1, "");
									$data["inserted"] .= "[$sku - $platform_id]\r\n";
								}
								else
								{
									$record_comment = $insertproduct_result["error_handler"];
									$this->GSC_error_handler_record($record_comment, $sku, $platform_id);
									$result["insertProduct"] .= " \r\n ----- \r\n" . __LINE__." Error from google_connect [$sku - $platform_id]: {$insertproduct_result["error_message"]} \r\n";
								}
							}
							else
							{
								$this->GSC_error_handler_record("No response detected", $sku, $platform_id);
								$result["insertProduct"] .= " \r\n ----- \r\n" . __LINE__." [$sku - $platform_id] No response detected \r\n";
							}
						}
						elseif(isset($gsc_product_result["error_message"]))
						{
							// most likely is missing MPN
							$this->GSC_error_handler_record($gsc_product_result["error_message"], $sku, $platform_id);
							$result["insertProduct"] .= " \r\n ----- \r\n" . __LINE__." [$sku - $platform_id] {$gsc_product_result["error_message"]} \r\n";
						}
					}
				}

				$i++;
			}



			if($result)
			{
				$this->mail_result($result, "CACHE_API_EXEC ERROR - google_shopping_service.php");
			}

		}
		else
		{
			$result[] = "No cache_api list";
		}

		if(isset($_GET["debug"]))
		{
			echo "<pre>";
			echo __LINE__. " DEBUG <br>cache_api_exec_debug() <br><hr></hr>Cache API list:<br>";
			var_dump($cache_api_list);
			echo " <hr></hr><br>cache_api_exec_debug() <br>Deleted Products:<br>";
			var_dump(str_replace("\r\n", "<br>", $data["deleted"]));
			echo "<br><br>Inserted Products: <br>";
			var_dump(str_replace("\r\n", "<br>", $data["inserted"]));
			echo "<br><br>Errors (if any):<br>";
			var_dump($result);
			echo "<br>COMPLETED<hr></hr> </pre>";
		}
	}


	public function shopping_api_connect($call, $account_id, $debug = true, $postdata=array())
	{
		$ret["status"] = FALSE;

		if(!$call || !$account_id)
		{
			$ret["error_message"] = ">> shopping_api_connect() Missing parameters call or account_id";
		}
		else
		{
			$query_str = "call=$call&accountid=$account_id";
			if($debug)
				$query_str .= "&debug=1";

			$postdata = (array)$postdata;
			if($debug)
				$postdata["debug"] = 1;
			$postdata["call"] = $call;
			$postdata["accountid"] = $account_id;

			if(isset($postdata))
			{
				if($debug)
					$url = "http://219.76.190.140:4580/Google_test/google_connect.php";
				else
					$url = "http://219.76.190.140:4580/Google/google_connect.php";

				$query_str = http_build_query($postdata);

				for ($i=0; $i < 5; $i++)
				{
					$result = $this->get_google_curl($url, $query_str);

					if($result)
					{
						$data = $result['data'];
						$google_connect_result = json_decode($data, FALSE);

						// if error is not due to connection problem, then don't need to retry
						if(strpos($google_connect_result->error_message, "couldn't connect to host") === FALSE && strpos($google_connect_result->error_message, "Network is unreachable") === FALSE)
						{
							break;
						}
					}

					sleep(5);
				}

				$data = $result['data'];
				$curlinfo = $result["curlinfo"];
				$curlerror = $result["curlerror"];
				$curlerrorno = $result["curlerrorno"];
				$curlinfo_str = $this->convert_array_to_string($curlinfo);

				if($curlerror)
				{
					$ret["error_message"] = ">> shopping_api_connect($call, $account_id) cURL error. [errorno:$curlerrorno] $curlerror. \r\n Curl Info: \r\n$curlinfo_str" ;
					$ret["error_handler"] = "[curl_errorno:$curlerrorno] $curlerror";
				}
				else
				{
					if($data)
					{
						$google_connect_result = json_decode($data, FALSE);
						if($google_connect_result)
						{
							if($google_connect_result->status == TRUE)
							{
								// SUCCESS RETURN WITH DATA
								$ret["status"] = TRUE;

								if($result_vars = get_object_vars($google_connect_result))
								{
									foreach ($result_vars as $propertyname => $value)
									{
										$ret[$propertyname] = $value;
									}
								}
							}
							else
							{
								$ret["error_message"] = ">> shopping_api_connect($call, $account_id)\r\n{$google_connect_result->error_message}";

								$short_error_message = $google_connect_result->error_message;
								$short_error_message = str_replace("Caught exception: ", "", $short_error_message);

								if(strpos($short_error_message, "/products: (") != FALSE)
								{
									// EXAMPLE of usual error message:
									// Error calling POST https://www.googleapis.com/content/v2/7852736/products: (400) [description] validation/invalid_value: Invalid string value in attribute: description
									$short_error_message = str_replace("/products: ", "", strstr($short_error_message, "/products: (")) ;
								}
								$ret["error_handler"] = $short_error_message; // shorter version for database recording
							}
						}
						else
						{
							$ret["error_message"] = ">> shopping_api_connect($call, $account_id) Error decoding json: \r\n{$data}";
							$ret["error_handler"] = "Error decoding response json"; // shorter version for database recording
						}
					}
					else
					{
						// shouldn't come here
						$ret["error_message"] = ">> shopping_api_connect($call, $account_id) No data detected.";
						$ret["error_handler"] = "No data detected"; // shorter version for database recording
					}
				}

			}
			else
			{
				$ret["error_message"] = ">> shopping_api_connect($call, $account_id) postdata must be an array to build query";
				$ret["error_handler"] = "postdata incorrect format";
			}

		}

		// if($call == "deleteproductbatch")
		// // if($call != "getproduct" && $call != "listproducts")
		// // if($call == "insertproduct")
		// {
			// echo "<pre>";
			// echo "<hr></hr>";
			// var_dump(__LINE__ . ' LINE HERESSS');
			// var_dump("url:  $url");
			// var_dump($call);
			// // var_dump("Query string:   " . $query_str);
			// var_dump("Curl errono:$curlerrorno  -  curl error: $curlerror");
			// echo "<hr></hr>";
			// var_dump("raw curl_exec data from google_connect");
			// var_dump($data); // raw data frm curl

			// echo "<hr></hr>";
			// var_dump("return message");
			// var_dump($ret);
			// echo "<hr></hr>";
			// echo "<hr></hr>";
			// die();
		// }

		return $ret;
	}


	private function get_google_curl($url, $query_str, $params = array())
	{
		$ret = array();
		if($url && $query_str)
		{
			$ch = curl_init();
			$timeout = 5;
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			// curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($ch,CURLOPT_POSTFIELDS, $query_str);
			$data = curl_exec($ch);
			$curlinfo = curl_getinfo($ch);
			$curlerror = curl_error($ch);
			$curlerrorno = curl_errno($ch);
			curl_close($ch);

			# cannot connect to host
			if($curlerrorno != 7)
			{
				if($data || $curlerror != "")
				{
					$ret['data'] = $data;
					$ret["curlinfo"]  = $curlinfo;
					$ret["curlerror"] = $curlerror;
					$ret["curlerrorno"] = $curlerrorno;
				}
			}
		}
		return $ret;
	}

	private function convert_array_to_string($array=array())
	{
		$return_str = "";

		if($array)
		{
			foreach ($array as $key => $value)
			{
				if(is_array($value))
				{
					$return_str .= $this->convert_array_to_string($value);
				}
				else
				{
					$return_str .= "[\"$key\"] => $value \r\n";
				}
			}
		}

		return $return_str;
	}


	//update all platform of all product data feed, please use this function with both parameters sku and specified_platform are empty.

	//update a single product in all platform, please use this function whith the first parameter sku set to the value wanted and the
	//seconde sepcified_platform is empty.

	//update a certain product in a certain platform, please use this function with both parameters sku and sepcified_platform set to
	//corresponding value.

	//if want to update the all product in a SINGLE platform, please use funtion -- update_google_shopping_item_by_platform -- whith the first
	//parameter platform set to a corresponding value
	public function cron_update_google_shopping_feed($sku="", $specified_platform = "")
	{
		$platform_biz_obj_list = $this->platform_biz_var_service->get_selling_platform_list();

		if($specified_platform)
		{
			$this->update_google_shopping_item_by_platform($specified_platform, $sku);
		}
		else
		{
			foreach($platform_biz_obj_list as $obj)
			{
				$platform_id = $obj->get_id();
				$this->update_google_shopping_item_by_platform($platform_id, $sku);
			}
		}
	}

	public function gen_data_feed($platform_id = "", $shopping_api = TRUE, $where = array())
	{
		include_once(APPPATH."libraries/service/Googlebase_product_feed_service.php");
		$this->googlebase_product_feed_service = new Googlebase_product_feed_service();
		$result = $this->googlebase_product_feed_service->gen_data_feed($platform_id, $shopping_api, $where);

		$country_id = substr($platform_id, -2, 2);
		if($result[$country_id])
		{
			return $result[$country_id];
		}
		return array();
	}

	public function batch_insert_item($account_id, $data_list = array(), $platform_id)
	{
		$prepare_insert_item_list = $process_list = array();
		$debug = $this->debug;
		$prepare_batch_error = "";
		if($data_list)
		{
			foreach($data_list as $val)
			{
				$sku = $val->get_sku();
				$process_list[$sku]["price"] = $val->get_price();
				$process_list[$sku]["platform_id"] = $val->get_platform_id(); // WEBAU

				if($this->debug)
				{
					// // pingtest
					// if($sku != "18066-AA-NA" && $sku != "12859-AA-AL")
					// 	continue;
				}

				if($item_obj = $this->get_dao()->get(array("sku"=>$sku, "platform_id"=>$platform_id, "status"=>1)))
				{
					continue;
				}

				// if($product = $this->get_GSC_product($sku, $platform_id))
				$gsc_product_result = $this->get_GSC_product($sku, $platform_id);

				if($gsc_product_result["status"] === TRUE)
				{
					$prepare_insert_item_list[] = $gsc_product_result["product"];
				}
				elseif(isset($gsc_product_result["error_message"]))
				{
					$prepare_batch_error .= " \r\n ----- \r\n" . __LINE__. " Error prepare batch: {$gsc_product_result["error_message"]} \r\n";
				}
			}

			if($prepare_batch_error)
			{
				$error["prepare_batch_error"] = $prepare_batch_error;
				$this->mail_result($result, __LINE__." ERROR: Google Shopping Insert Batch Part Fail");
			}

			if($prepare_insert_item_list)
			{
				$batch_error_array = array();
				$postdata["productbatch"] = $prepare_insert_item_list;
				$insertproduct_result = $this->shopping_api_connect('insertproductbatch', $account_id, $debug, $postdata);

				if($insertproduct_result["status"] == FALSE)
				{
					$result["insertproductbatch_error"] = $insertproduct_result["error_message"];
					$this->mail_result($result, __LINE__." ERROR: Google Shopping Insert Batch Fail");
				}
				else
				{
					if($batch_error_array = (array)$insertproduct_result["batch_error"])
					{
						foreach ($batch_error_array as $offerid => $error_message)
						{
							$thisprice = $thisplatform_id = "";
							$sku = substr($offerid, 3);
							$thisprice = $process_list[$sku]["price"];
							$thisplatform_id = $process_list[$sku]["platform_id"];

							$this->create_google_shopping_record($sku, $thisplatform_id, 0, $thisprice, $error_message);
							$result[] = "offerID: $offerid \r\n$error_message\r\n";
						}
						$this->mail_result($result, __LINE__ . " ERROR: Google Shopping Insert Batch Error Products");
					}

					if($batch_success_array = (array)$insertproduct_result["data"])
					{
						foreach ($batch_success_array as $k => $offerid)
						{
							$thisprice = $thisplatform_id = "";
							$sku = substr($offerid, 3);
							$thisprice = $process_list[$sku]["price"];
							$thisplatform_id = $process_list[$sku]["platform_id"];

							$this->create_google_shopping_record($sku, $thisplatform_id, 1, $thisprice);
							$subject = "content for google shopping success to update";
						}
					}
				}
			}
			else
			{
				$result[] =  "Empty Insert Item List; probably error creating GSC_product";
				$this->mail_result($result, __LINE__." ERROR: Google Shopping Empty Insert List");
			}
		}

		if(isset($_GET["debug"]))
		{
			echo "<pre>";
			echo __LINE__. " DEBUG <br>batch_insert_item() <br>Prepare to insert Products:<br>";
			var_dump($process_list);
			echo "<br><br>Insert batch Partial Errors (if any):<br>";
			var_dump(str_replace("\r\n", "<br>", $prepare_batch_error));
			echo "<br><br>Inserted Products: <br>";
			var_dump(str_replace("\r\n", "<br>", $batch_success_array));
			echo "<br><br>Errors (if any):<br>";
			var_dump($result);
			echo "<br>COMPLETED<hr></hr> </pre>";
		}
	}

	public function batch_delete_item($account_id, $sku, $platform_id)
	{
		$debug = FALSE;
		if(strpos($_SERVER["HTTP_HOST"], "dev") != FALSE)
			$debug = TRUE;

		$sku_list = array();
		if(is_array($sku))
		{
			$sku_list = array_merge($sku_list, $sku);
		}
		else
		{
			$sku_list[] = $sku;
		}

		$prepare_batch_item = array();

		foreach($sku_list as $val)
		{
			// ping's comment: when batch_delete_item() is called, q_delete has deleted the record in db google_shopping, so $item_obj will not exist.
			// thus, it may not go into delete batch. the following line was previously here from batch_delete_item_v1()
			// if($item_obj = $this->get_dao()->get(array("sku"=>$val, "platform_id"=>$platform_id, "status"=>1)))
			{
				if($platform_obj = $this->platform_biz_var_service->get(array("selling_platform_id"=>$platform_id)))
				{
					$platform_country_id = substr($platform_id,3);
					$language_id = $platform_obj->get_language_id();
					$google_ref_id = $platform_country_id.'-'.$val;

					// Make sure your product ID is of the form channel:languageCode:countryCode:offerId.
					list($id, $country, $language) = array($google_ref_id, $platform_country_id, $language_id);
					$postdata["productid"] = "online:$language_id:$platform_country_id:$id";
					$getproduct_result = $this->shopping_api_connect('getproduct', $account_id, $debug, $postdata);

					if($getproduct_result["status"] == TRUE)
					{
						$prepare_batch_item[] = $postdata["productid"];
					}
					else
					{
						if(strpos($getproduct_result["error_message"], "item not found") === FALSE)
						{
							$result["error"] .= " \r\n ----- \r\n" . __LINE__." Error from google_connect: {$getproduct_result["error_message"]} \n$val - $platform_id \r\n";
							$this->mail_result($result, "google shopping error: Single Item delete");
						}
						else
						{
							// if google erorr message says item not found, deactivate status on db google_shopping
							if($item_obj = $this->get_dao()->get(array("sku"=>$val, "platform_id"=>$platform_id)))
							{
								$item_obj->set_status(0);
								$this->get_dao()->update($item_obj);
							}
						}
					}
				}
			}
		}

		if($prepare_batch_item)
		{
			$postdata_deletebatch["productidbatch"] = $prepare_batch_item;
			$deletebatch_result = $this->shopping_api_connect('deleteproductbatch', $account_id, $debug, $postdata_deletebatch);

			if($deletebatch_result["status"] == TRUE)
			{
				if($deletebatch_result["batch_error"] != "")
				{
					// Send email for failed SKUs
					$result["error"] .= " \r\n ----- \r\n" . __LINE__." Error in batch from google_connect: \r\n{$deletebatch_result["batch_error"]} \r\n";
					$this->mail_result($result, "google shopping error: Single Item delete");
				}

				if($deletebatch_result["data"])
				{
					// Successful SKUs
					foreach ($deletebatch_result["data"] as $key => $productid)
					{
						list($channel, $language_id, $platform_country_id, $google_ref_id) = explode(':', $productid);
						$sku = substr($google_ref_id, 3);
						if($item_obj = $this->get_dao()->get(array("sku"=>$sku, "platform_id"=>$platform_id)))
						{
							$item_obj->set_status(0);
							$this->get_dao()->update($item_obj);
						}
					}
				}
			}
			else
			{
				$result["error"] .= " \r\n ----- \r\n" . __LINE__." Error from google_connect: {$deletebatch_result["error_message"]} \n$val - $platform_id \r\n";
				$this->mail_result($result, "google shopping error: Single Item delete");
			}

		}

		if(isset($_GET["debug"]))
		{
			echo "<pre>";
			echo __LINE__." DEBUG <br>batch_delete_item() <br>Product IDs to delete: <br>";
			var_dump($prepare_batch_item);
			echo "<br><br>Errors (if any):<br>";
			var_dump($result);
			echo "Completed <hr></hr> </pre>";
		}
	}

 	public function batch_delete_item_by_product_object($account_id, $sku, $platform_id)
	{
/* IN DEV SERVER, it will call shopping_api_connect() will call API in dryRun mode, nothing will be deleted */
		$debug = FALSE;
		if(strpos($_SERVER["HTTP_HOST"], "dev") !== FALSE)
			$debug = TRUE;


		if($sku)
		{
			$this->batch_delete_item($account_id, $sku, $platform_id);
		}
		else
		{
			// To get here -- http://admindev.valuebasket.com/marketing/ext_category_mapping/update_google_shopping_item_by_platform/WEBAU?debug

			$temp_product_list = array();

			$postdata["maxresults"] = 250;
			if($debug)
			{
				$postdata["maxresults"] = 1;
				$postdata["maxpages"] = 1; 	# number of pages to loop
			}

			$productFeed_result = $this->shopping_api_connect("listproducts", $account_id, $debug, $postdata);
			if($productFeed_result["status"] == TRUE)
			{
				if($productFeed = $productFeed_result["data"])
				{
					foreach ($productFeed as $product)
					{

						$target_country = $product->targetCountry;
						$country_id = substr($platform_id, 3, 2);
						if($target_country == $country_id)
						{
							if($debug)
							{
								// // pingtest
								// if($product->id != "online:en:AU:AU-18066-AA-NA")
								// 	continue;
							}

							$temp_product_list[] = $product->id;
						}
					}

					if($temp_product_list)
					{
						$postdata_deletebatch["productidbatch"] = $temp_product_list;
						$deletebatch_result = $this->shopping_api_connect('deleteproductbatch', $account_id, $debug, $postdata_deletebatch);
						if($deletebatch_result["status"] == TRUE)
						{
							if($deletebatch_result["batch_error"] != "")
							{
								// Send email for failed SKUs
								$result["error"] .= " \r\n ----- \r\n" . __LINE__." Error in batch from google_connect: \r\n{$deletebatch_result["batch_error"]} \r\n";
								$this->mail_result($result, "google shopping error: Platform All Items Delete");
							}
						}
						else
						{
							$result["error"] .= " \r\n ----- \r\n" . __LINE__." Error from google_connect: {$deletebatch_result["error_message"]} \n$val - $platform_id\r\n";
							$this->mail_result($result, "google shopping error: Platform All Items Delete");
						}
					}

					if($platform_items = $this->get_dao()->get_list(array("platform_id"=>$platform_id), array("limit"=>-1)))
					{
						foreach($platform_items as $obj)
						{
							$obj->set_status(0);
							$this->get_dao()->update($obj);
						}
					}
				}
			}
			else
			{
				$result["error"] = __LINE__ . " google_shopping_service.php, \r\n{$productFeed_result["error_message"]}";
				$this->mail_result($result, "google shopping error: Platform All Items Delete");
			}

			if(isset($_GET["debug"]))
			{
				echo "<pre>";
				echo __LINE__." DEBUG <br> batch_delete_item_by_product_object() <br>";
				echo "ProductIDs to delete: <br>"; var_dump($temp_product_list);
				echo "<br><br>Errors (if any):<br>";
				var_dump($result); echo "<br>Completed.<hr></hr> </pre>";
			}
		}
	}


	//update whole platform product data feed, please using this function with the second parameter sku=""
	// update_google_shopping_item_by_platform is run by cron
	public function update_google_shopping_item_by_platform($platform_id="", $sku="")
	{
		// http://admindev.valuebasket.com/marketing/ext_category_mapping/update_google_shopping_item_by_platform/WEBAU?debug
		$debug = FALSE;
		if(strpos($_SERVER["HTTP_HOST"], "dev") != FALSE)
			$debug = TRUE;

		$this->debug = $debug;

		$account_id = $this->get_shopping_api_accountId($platform_id);

		// pingtest
		if($debug)
			// $account_id = 8113126;

		if(!$account_id)
		{
			return true;
		}

		$where = $d_where = array();


		if($sku)
		{
			if(is_array($sku))
			{
				$query_str = "";
				foreach($sku as $v)
				{
					$query_str .= "'".$v."',";
				}

				$query_str = rtrim($query_str, ',');
				$where['pr.sku in ('. $query_str .')'] = null;

				$d_where['sku in ('. $query_str .')'] = null;

			}
			else
			{
				$where["pr.sku in ('". $sku ."')"] = null;

				$d_where["sku in ('". $sku ."')"] = null;
			}
		}

		if($debug)
		{
			$where["pr.sku in ('18066-AA-NA', '12859-AA-AL')"] = null;
			$d_where["sku in ('18066-AA-NA', '12859-AA-AL')"] = null;

			$where["pr.sku in ('18066-AA-NA')"] = null;
			$d_where["sku in ('18066-AA-NA')"] = null;

		}
		$data_list = $this->gen_data_feed($platform_id, $shopping_api = TRUE, $where);

		/********************
		*when run this function, update the google_shopping record too
		*if update all product by platform, then all google_shopping data in
		*that platform will be delete first, then records will be created again base on the
		*request return result.
		********************/
		$d_where["platform_id"] =  $platform_id;
		$this->get_dao()->q_delete($d_where);

		// delete everything on google first; on DEV server, it will use dryRun mode on Google
		$this->batch_delete_item_by_product_object($account_id, $sku, $platform_id);

		if($data_list)
		{
			$chunk_data_list = array_chunk($data_list, 250, false);

			foreach($chunk_data_list as $chunk_data)
			{
				$this->batch_insert_item($account_id, $chunk_data, $platform_id);
			}
		}
	}


	# 2015-03-12 - this function seems to be not in use
	public function delete_product_item($platform_id="", $sku="" , $country_id="", $language_id="")
	{
		$debug = FALSE;
		if(strpos($_SERVER["HTTP_HOST"], "dev") != FALSE)
			$debug = TRUE;

		$exec = false;
		$result = array();
		if($platform_id)
		{
			if($sku)
			{
				if($account_id = $this->get_shopping_api_accountId($platform_id))
				{
					$platform_obj = $this->platform_biz_var_service->get(array("selling_platform_id"=>$platform_id));
					$platform_country_id = substr($platform_id,3);
					$language_id = $platform_obj->get_language_id();
					$google_ref_id = $platform_country_id.'-'.$sku;

					// Make sure your product ID is of the form channel:languageCode:countryCode:offerId.
					list($id, $country, $language) = array($google_ref_id, $platform_country_id, $language_id);
					$postdata["productid"] = "online:$language_id:$platform_country_id:$id";
					$getproduct_result = $this->shopping_api_connect('getproduct', $account_id, $debug, $postdata);

					if($getproduct_result["status"] == TRUE)
					{
						$exec = TRUE;
					}
					else
					{
						$result["getProduct"] .= " \r\n ----- \r\n" . __LINE__." Error from google_connect [$sku - $platform_id]: \r\n{$getproduct_result["error_message"]} \r\n";
					}

					if($exec)
					{
						// Product exists, proceed to delete
						$deleteproduct_result = $this->shopping_api_connect('deleteproduct', $account_id, $debug, $postdata);
						if($deleteproduct_result["status"] == TRUE)
						{
							//if success
							$this->api_request_result_update($sku, $platform_id, 1, "");
							$data["deleted"] .= "[$sku - $platform_id]\r\n";
						}
						else
						{
							$result["deleteProduct"] .= " \r\n ----- \r\n" . __LINE__." Error from google_connect [$sku - $platform_id]: {$deleteproduct_result["error_message"]} \r\n";
						}
					}

				}
				else
				{
					$result['error'] = __LINE__ . " google_shopping_service.php - delete_product_item(), No accountid";
				}

			}
			else
			{
				$result['error'] = __LINE__ . " google_shopping_service.php - delete_product_item(), No sku";
			}

		}
		else
		{
			$result['error'] = __LINE__ . " google_shopping_service.php - delete_product_item(), No platform_id";
		}

		if(isset($result))
		{
			$this->mail_result($result, "content for google shopping error");
		}

		if(isset($_GET["debug"]))
		{
			echo "<pre>";
			echo __LINE__. " DEBUG <br>delete_product_item() <br>Deleted Products:<br>";
			var_dump(str_replace("\r\n", "<br>", $data["deleted"]));
			echo "<br><br>Errors (if any):<br>";
			var_dump($result);
			echo "<br>COMPLETED<hr></hr> </pre>";
		}

	}


	public function mail_result($content, $subject)
	{
		$mail_content = "";
		if(is_array($content))
		{
			foreach($content as $key=>$val)
			{
				$mail_content .= $key . ":" . $val;
			}
			$mail_content = wordwrap($mail_content, 70, "\r\n");
		}
		else
		{
			$mail_content = $content;
		}

		$email_list = $this->get_email_list();
		$email_cc_list = $this->get_email_cc_list();



		$to = $cc = "";

		$to = implode(',', $email_list);
		$cc = implode(',', $email_cc_list);

		if($cc)
		{
			$header = "Cc: " . $cc . "\r\n";
			mail($to, $subject, $mail_content, $header);
		}
		else
		{
			mail($to, $subject, $mail_content);
		}
	}

	public function get_google_shopping_content_report($platform_id="")
	{
		if(!$platform_id)
		{
			return true;
		}
		else
		{
			$debug = FALSE;
			if(strpos($_SERVER["HTTP_HOST"], "dev") !== FALSE)
				$debug = TRUE;
			$account_id = $this->get_shopping_api_accountId($platform_id);
			if($account_id)
			{
				$postdata["maxresults"] = 250;
				$productFeed_result = $this->shopping_api_connect("listproducts", $account_id, $debug, $postdata);

				if($productFeed_result["status"] == TRUE)
				{
					if($productFeed = $productFeed_result["data"])
					{
						foreach ($productFeed as $product)
						{
							$result = $this->process_item_data($product, $platform_id);
							$report .= $result;
						}
					}

					$header = "SKU| title| product_url| image_url| target_country| brand| condition| color| availability| google_category| product_type| price| currency| MPN\n";
					$filename = 'googlebase_product_feed_' . $platform_id . '_' . date('Ymdhis').'.csv';
					header("Content-type: text/csv");
					header("Cache-Control: no-store, no-cache");
					header("Content-Disposition: attachment; filename=\"$filename\"");
					echo $header.$report;
				}
				else
				{
					$error_message = __LINE__ . " google_shopping_service.php, \r\n{$productFeed_result["error_message"]}";
					$this->mail_result($result, "content for google shopping error");

					// for front end
					$error_message = str_replace("\r\n", "<br><br>", $error_message);
					echo $error_message;

				}
			}
		}
	}



	function process_item_data($product, $platform_id="")
	{
		$temp = array();

		/*
		// previous API version format
				$target_country = $product->getTargetCountry();

				$id = $product->getSKU();
				$title = $product->getTitle();
				$product_url = $product->getProductLink();
				$image_url = $product->getImageLink();
				$brand = $product->getBrand();
				$condition = $product->getCondition();
				$color = $product->getColor();
				$vail = $product->getAvailability();
				$google_cat = $product->getGoogleProductCategory();
				$product_type = $product->getProductType();
				$price = $product->getPrice();
				$price_unit = $product->getPriceUnit();
				$mpn = $product->getMpn();
		*/

		$target_country = $product->targetCountry;

		$id = $product->offerId;
		$title = $product->title;
		$product_url = $product->link;
		$image_url = $product->imageLink;
		$brand = $product->brand;
		$condition = $product->condition;
		$color = $product->color;
		$vail = $product->availability;
		$google_cat = $product->googleProductCategory;
		$product_type = $product->productType;
		$price = $product->price->value;
		$price_unit = $product->price->currency;
		$mpn = $product->mpn;

		$temp[] = $id;
		$temp[] = $title;
		$temp[] = $product_url;
		$temp[] = $image_url;
		$temp[] = $target_country;
		$temp[] = $brand;
		$temp[] = $condition;
		$temp[] = $color;
		$temp[] = $vail;
		$temp[] = $google_cat;
		$temp[] = $product_type;
		$temp[] = $price;
		$temp[] = $price_unit;
		$temp[] = $mpn;

		$result = implode('|', $temp);

		return $result."\n";
	}

	function set_email_list($platform_id)
	{
		$email_list = array();
		switch($platform_id)
		{
			case "WEBFR":
				$email_list[] = "google_shopping_FR@eservicesgroup.com";
				break;
			case "WEBES":
				$email_list[] = "google_shopping_ES@eservicesgroup.com";
				break;
			case "WEBIT":
				$email_list[] = "google_shopping_IT@eservicesgroup.com";
				break;
			case "WEBGB":
				$email_list[] = "google_shopping_GB@eservicesgroup.com";
				break;
			case "WEBCH":
				$email_list[] = "google_shopping_CH@eservicesgroup.com";
				break;
			case "WEBAU":
				$email_list[] = "google_shopping_AU@eservicesgroup.com";
				break;
			case "WEBPL":
				$email_list[] = "google_shopping_PL@eservicesgroup.com";
				break;
		}

		$email_list[] = "google-eu@valuebasket.com";
		$email_list[] = "itsupport@eservicesgroup.net";
		$this->email_list = $email_list;
	}

	function get_email_list()
	{
		return $this->email_list;
	}

	function get_email_cc_list()
	{
		return $this->email_cc_list;
	}

	function set_cache_api_request_dao($value)
	{
		$this->cache_api_request_dao = $value;
	}

	function get_cache_api_request_dao()
	{
		return $this->cache_api_request_dao;
	}

	function set_price_dao($value)
	{
		$this->price_dao = $value;
	}

	function get_price_dao()
	{
		return $this->price_dao;
	}

	// convert a single product into utf encoded array and compatible for google api
	public function get_GSC_product($sku, $platform_id)
	{
		$where = array();
		$where["pr.sku"] = $sku;
		$gs_obj_list = $this->gen_data_feed($platform_id, $shopping_api = TRUE, $where);

		$ret["status"] = false;
		if($gs_obj_list)
		{
			foreach($gs_obj_list as $gs_obj)
			{
				$product = $product_encoded = array();
				$needed_identifier = array();
				$ret["status"] = false;

				$title = $gs_obj->get_prod_name();
				$description = $gs_obj->get_detail_desc();
				$description = preg_replace('/[\x00-\x09\x0B\x0C\x0E-\x1F\x7F]/','', $description);
				$description = str_replace("\r", " ", $description);
				$description = str_replace("\n", " ", $description);

				if(!$description)
				{
					$ret["error_message"] = "Missing detail desc - PLA unlisted ";
					$ret["product"] = array();
					return $ret;
				}

				if(strlen($description) > 5000)
				{
					$ret["error_message"] = "Detail Desc more than 5000 char ";
					$ret["product"] = array();
					return $ret;
				}
				$google_product_category = $gs_obj->get_google_product_category();
				$product_type = $gs_obj->get_product_type();
				$product_url = $gs_obj->get_product_url();
				$image_url = $gs_obj->get_image_url();
				$condition = $gs_obj->get_condition();
				$availability = $gs_obj->get_availability();
				if($availability == "out of stock")
				{
					$availability = "in stock";
				}

				$price_w_curr = $gs_obj->get_price_w_curr();

				$brand_name = $gs_obj->get_brand_name();
				$mpn = $gs_obj->get_mpn();
				$upc = $gs_obj->get_upc();
				$ean = $gs_obj->get_ean();
				$gtin = $upc?$upc:$ean; 	# gtin has been set to "" for all platforms

				// Since GTIN is empty, brand && mpn cannot be empty, else, don't bother sending to google
				if($brand_name != "" && $brand_name != NULL)
				{
					$needed_identifier[] = $brand_name;
				}
				if($mpn != "" && $mpn != NULL)
				{
					$needed_identifier[] = $mpn;
				}
				if(count($needed_identifier) < 2)
				{
					$ret["error_message"] = "Insufficient Identifier [mpn | brand] - PLA unlisted";
					$ret["product"] = array();
					return $ret;
				}

				$item_group_id = $gs_obj->get_item_group_id();
				$colour_name = $gs_obj->get_colour_name();
				$shipping = $gs_obj->get_shipping();
				$prod_weight = $gs_obj->get_prod_weight();


				$price = $gs_obj->get_price();
				$currency = $gs_obj->get_platform_currency_id();
				$platform_country_id = $gs_obj->get_platform_country_id();

				// $product["contentLanguage"] = $lang_id;
				$product["contentLanguage"] = $gs_obj->get_language_id();
				$product["channel"] = "online";

				$google_ref_id = $platform_country_id.'-'.$sku;
				$product["offerId"] = $google_ref_id;
				$product["targetCountry"] = $platform_country_id;
				$product["imageLink"] = $image_url;
				$product["title"] = $title;
				$product["description"] = $description;
				$product["link"] = $product_url;
				$product["brand"] = $brand_name;
				$product["condition"] = $condition;
				$product["color"] = $colour_name;
				$product["availability"] = $availability;
				$product["googleProductCategory"] = $google_product_category;
				$product["productType"] = $product_type;

				$product["price"]["currency"] = $currency;
				$product["price"]["value"] = $price;

				$product["shippingWeight"]["unit"] = "kg";
				$product["shippingWeight"]["value"] = $prod_weight;

				$product["mpn"] = $mpn;


				if(($google_ref_id == "FR-16060-AA-WH") || ($google_ref_id == "FR-16060-AA-BK"))
				{
					$extra_track_parameter = "&source={ifdyn:dyn}{ifpe:pe}{ifpla:pla}&dyprodid=$google_ref_id";

					$product["adwordsRedirect"] = $product_url.$extra_track_parameter;
				}

				// Set GTIN empty for all platforms
				$product["gtin"] = "";
				$product["itemGroupId"] = $item_group_id;

				$region = '';
				$shippingPrice = '0.00';
				$service = 'Standard';

				$product["shipping"]["country"] = $platform_country_id;
				$product["shipping"]["region"] = $region;
				$product["shipping"]["price"] = $shippingPrice;
				$product["shipping"]["currency"] = $currency;
				$product["shipping"]["service"] = $service;

				// $product->addShipping($platform_country_id,$region,$shippingPrice,$currency,$service);
				$product_encoded = $this->utf_encode_array_values($product);

				$ret["status"] = TRUE;
				$ret["product"] = $product_encoded;
				return $ret;
			}
		}
		else
		{
			$ret["error_message"] = "Cannot retrieve product info for GSC product assemble";
		}

		return $ret;
	}



	private function utf_encode_array_values($array)
	{
		$return_array = array();
		if(is_array($array))
		{
			foreach ($array as $key => $value)
			{
				if(is_array($value))
				{
					$return_array[$key] = $this->utf_encode_array_values($value);
				}
				else
				{
					if(mb_detect_encoding($value) != "UTF-8")
						$return_array[$key] = utf8_encode($value);
					else
						$return_array[$key] = $value;
				}
			}
		}

		return $return_array;
	}

	function GSC_error_handler($e, $sku, $platform_id)
	{
		$comment = "";
		foreach ($e->errors->getErrors() as $error) {
			$result[] = "SKU: ".$sku."\r\n".
					"Platform: ".$platform_id."\r\n".
					"Code: ".$error->getCode()."\r\n".
					"Domain: ".$error->getDomain()."\r\n".
					'Location: '.$error->getLocation()."\r\n".
					"Internal Reason: ".$error->getInternalReason()."\r\n";

			$comment = $error->getInternalReason();
					}


		$this->api_request_result_update($sku, $platform_id, 0, $comment);
		$this->mail_result($result, "ERROR: Google Shopping");
	}

	private function GSC_error_handler_record($comment, $sku, $platform_id)
	{
		// if due to following reasons, update price.is_advertised
		if(strpos($comment, "Insufficient Identifier") != FALSE || strpos($comment, "Missing detail desc") != FALSE)
		{
			$price_obj = $this->get_price_dao()->get(array("sku"=>$sku, "platform_id"=>$platform_id));
			if($price_obj)
			{
				$price_obj->set_is_advertised("N");
				$this->get_price_dao()->update($price_obj);
			}
		}

		// update google api request
		$this->api_request_result_update($sku, $platform_id, 0, $comment);
	}

	private function api_request_result_update($sku, $platform_id, $status = 1, $comment="")
	{
		if($google_shopping_obj = $this->get_dao()->get(array("sku"=>$sku, "platform_id"=>$platform_id)))
		{
			$google_shopping_obj->set_api_request_result($status);
			if($status == 1)
			{
				$google_shopping_obj->set_comment($comment);
			}
			else
			{
				$old_comment = $google_shopping_obj->get_comment();
				$new_comment = $old_comment?($old_comment.';'.$comment):$comment;
				$google_shopping_obj->set_comment($new_comment);
			}
			$this->get_dao()->update($google_shopping_obj);
		}
	}

	private function create_google_shopping_record($sku, $platform_id, $status, $price, $comment = '')
	{
		if($item_obj = $this->get_dao()->get(array("sku"=>$sku,"platform_id"=>$platform_id)))
		{
			$item_obj->set_status($status);
			$item_obj->set_price($price);
			$item_obj->set_api_request_result($status);
			$item_obj->set_comment($comment);
			$this->get_dao()->update($item_obj);
		}
		else
		{
			$item_obj = $this->get_dao()->get();
			$item_obj->set_sku($sku);
			$item_obj->set_platform_id($platform_id);
			$item_obj->set_status($status);
			$item_obj->set_api_request_result($status);
			$item_obj->set_price($price);
			$item_obj->set_comment($comment);
			$this->get_dao()->insert($item_obj);
		}
	}


// =========== SBF #6199 -- FUNCTIONS FROM HERE ONWARD BELONG TO API VERSION 1; HAS SUNSET ================================================================================================================== //

/*
// API V1; obsolete
	public function get_google_shopping_content_report_v1($platform_id="")
	{
		if(!$platform_id)
		{
			return true;
		}
		else
		{
			if($account_id = $this->get_shopping_api_accountId($platform_id))
			{
				$client = $this->shopping_api_login($account_id);

				try{
					$report = "";
					//maxResults is the number of item return, can not exceed 250 per request.
					$maxResutls = 200;
					//if the total item exceed 250, then a token is needed to get the rest result
					$start_token = "";

					do{
						$productFeed = $client->getProducts($maxResutls, $start_token);
						$start_token = $productFeed->getStartToken();
						$products = $productFeed->getProducts();
						foreach($products as $product)
						{
							$result = $this->process_item_data($product, $platform_id);
							$report .= $result;
						}
					}while($start_token);

					$header = "SKU| title| product_url| image_url| target_country| brand| condition| color| availability| google_category| product_type| price| currency| MPN\n";

					$filename = 'googlebase_product_feed_' . $platform_id . '_' . date('Ymdhis').'.csv';
					header("Content-type: text/csv");
					header("Cache-Control: no-store, no-cache");
					header("Content-Disposition: attachment; filename=\"$filename\"");
					echo $header.$report;
				}catch(Exception $e)
				{
					$result['error'] = $e->getMessage();
					$this->mail_result($result, "content for google shopping error");
				}
			}
		}


	}
*/

// API V1; obsolete
	// public function cache_api_exec_debug_v1()
	// {
	// 	/*
	// 		* This function catches ALL the exceptions thrown in GShoppingContent.
	// 		* Switch to use this function if cache_api_exec() produces Exceptions not caught.
	// 	*/
	// 	$where = $option = array();
	// 	$where["api"] = "GSC";
	// 	$where["exec"] = 0;
	// 	$option["limit"] = -1;

	// 	if($config_vo = $this->config_dao->get(array("variable"=>"google_shopping_api_at_job")))
	// 	{
	// 		$config_vo->set_value(0);
	// 		$this->config_dao->update($config_vo);
	// 	}
	// 	$cache_api_list = $this->get_cache_api_request_dao()->get_list($where, $option);

	// 	if($cache_api_list = $this->get_cache_api_request_dao()->get_list($where, $option))
	// 	{
	// 		foreach($cache_api_list as $api_obj)
	// 		{
	// 			$api_obj->set_exec(1);
	// 			$this->get_cache_api_request_dao()->update($api_obj);
	// 		}

	// 		$i = 0;
	// 		foreach($cache_api_list as $api_obj)
	// 		{
	// 			$sku = $api_obj->get_sku();
	// 			$platform_id = $api_obj->get_platform_id();
	// 			$stock_status = $api_obj->get_stock_update();

	// 			if($i == 30)
	// 				sleep(5);

	// 			if(!$account_id = $this->get_shopping_api_accountId($platform_id))
	// 			{
	// 				continue;
	// 			}
	// 			else
	// 			{
	// 				$client = $this->shopping_api_login($account_id);
	// 			}

	// 			if(!$client)
	// 			{
	// 				$result["login"] = __LINE__." google_shopping_service Unable to get login account $account_id";
	// 				$this->mail_result($result, "CACHE_API_EXEC ERROR LOGIN");
	// 			}
	// 			$exec = false;


	// 			if($stock_status == "PAUSED")
	// 			{
	// 				$platform_obj = $this->platform_biz_var_service->get(array("selling_platform_id"=>$platform_id));
	// 				$platform_country_id = substr($platform_id,3);
	// 				$language_id = $platform_obj->get_language_id();
	// 				$google_ref_id = $platform_country_id.'-'.$sku;

	// 				list($id, $country, $language) = array($google_ref_id, $platform_country_id, $language_id);

	// 				try{
	// 					$product = $client->getProduct($id, $country, $language);
	// 					$exec = true;
	// 				}catch(Exception $e){
	// 					$result["getProduct"] .= __LINE__." ".$e->getMessage()."\n$sku - $platform_id\n";
	// 					var_dump($result);
	// 					// $this->mail_result($result, "CACHE_API_EXEC ERROR");
	// 					// $this->GSC_error_handler($e, $sku, $platform_id);
	// 				}

	// 				if($exec)
	// 				{
	// 					try{
	// 						$feedback = $client->deleteProduct($product);
	// 						//if success
	// 						$this->api_request_result_update($sku, $platform_id, 1, "");
	// 					}catch(Exception $e){
	// 						$result["deleteProduct"] .= __LINE__." ".$e->getMessage()."\n$sku - $platform_id\n";
	// 						var_dump($result);
	// 						// $this->mail_result($result, "CACHE_API_EXEC ERROR");
	// 						// $this->GSC_error_handler($e, $sku, $platform_id);
	// 					}
	// 				}
	// 			}
	// 			else
	// 			{
	// 				//insert the item, if item already exists, then it will update it
	// 				$GSC_product = $this->get_GSC_product($sku, $platform_id);

	// 				if($GSC_product)
	// 				{
	// 					try{
	// 						$client->insertProduct($GSC_product);
	// 						//if success
	// 						$this->api_request_result_update($sku, $platform_id, 1, "");
	// 					}catch(Exception $e){
	// 						$result["insertProduct"] .= __LINE__." ".$e->getMessage()."\n$sku - $platform_id\n";
	// 						var_dump($result);
	// 						// $this->mail_result($result, "CACHE_API_EXEC ERROR");
	// 						// $this->GSC_error_handler($e, $sku, $platform_id);
	// 					}
	// 				}
	// 			}

	// 			$i++;
	// 		}
	// 		if($result)
	// 		{
	// 			$this->mail_result($result, "CACHE_API_EXEC ERROR");
	// 		}
	// 	}
	// }



// // API v1 version without debug; obsolete
	// public function cache_api_exec()
	// {
	// 	/*
	// 		This is original function that catches exceptions thrown by GSC_RequestError
	// 		If anything happens and Exceptions not caught happen, switch to cache_api_exec_debug_v1()
	// 		to view all the Exceptions thrown.
	// 	*/
	// 	$where = $option = array();
	// 	$where["api"] = "GSC";
	// 	$where["exec"] = 0;
	// 	$option["limit"] = -1;

	// 	if($config_vo = $this->config_dao->get(array("variable"=>"google_shopping_api_at_job")))
	// 	{
	// 		$config_vo->set_value(0);
	// 		$this->config_dao->update($config_vo);
	// 	}

	// 	if($cache_api_list = $this->get_cache_api_request_dao()->get_list($where, $option))
	// 	{
	// 		foreach($cache_api_list as $api_obj)
	// 		{
	// 			$api_obj->set_exec(1);
	// 			$this->get_cache_api_request_dao()->update($api_obj);
	// 		}

	// 		foreach($cache_api_list as $api_obj)
	// 		{
	// 			$sku = $api_obj->get_sku();
	// 			$platform_id = $api_obj->get_platform_id();
	// 			$stock_status = $api_obj->get_stock_update();


	// 			if(!$account_id = $this->get_shopping_api_accountId($platform_id))
	// 			{
	// 				continue;
	// 			}
	// 			else
	// 			{
	// 				$client = $this->shopping_api_login($account_id);
	// 			}

	// 			$exec = false;


	// 			if($stock_status == "PAUSED")
	// 			{
	// 				$platform_obj = $this->platform_biz_var_service->get(array("selling_platform_id"=>$platform_id));
	// 				$platform_country_id = substr($platform_id,3);
	// 				$language_id = $platform_obj->get_language_id();
	// 				$google_ref_id = $platform_country_id.'-'.$sku;

	// 				list($id, $country, $language) = array($google_ref_id, $platform_country_id, $language_id);

	// 				try{
	// 					$product = $client->getProduct($id, $country, $language);
	// 					$exec = true;
	// 				}catch(GSC_RequestError $e){
	// 					$this->GSC_error_handler($e, $sku, $platform_id);
	// 				}

	// 				if($exec)
	// 				{
	// 					try{
	// 						$feedback = $client->deleteProduct($product);
	// 						//if success
	// 						$this->api_request_result_update($sku, $platform_id, 1, "");
	// 					}catch(GSC_RequestError $e){
	// 						$this->GSC_error_handler($e, $sku, $platform_id);
	// 					}
	// 				}
	// 			}
	// 			else
	// 			{
	// 				//insert the item, if item already exists, then it will update it
	// 				$GSC_product = $this->get_GSC_product($sku, $platform_id);

	// 				if($GSC_product)
	// 				{
	// 					try{
	// 						$client->insertProduct($GSC_product);
	// 						//if success
	// 						$this->api_request_result_update($sku, $platform_id, 1, "");
	// 					}catch(GSC_RequestError $e){
	// 						$this->GSC_error_handler($e, $sku, $platform_id);
	// 					}
	// 				}
	// 			}
	// 		}
	// 	}
	// }



/*
// API V1; obsolete
	public function batch_insert_item_v1($client, $data_list = array(), $platform_id)
	{
		$prepare_insert_item_list = array();

		foreach($data_list as $val)
		{
			$sku = $val->get_sku();

			if($item_obj = $this->get_dao()->get(array("sku"=>$sku, "platform_id"=>$platform_id, "status"=>1)))
			{
				continue;
			}

			$title = $val->get_prod_name();
			$description = $val->get_detail_desc();
			$description = preg_replace('/[\x00-\x09\x0B\x0C\x0E-\x1F\x7F]/','', $description);

			$google_product_category = $val->get_google_product_category();
			$product_type = $val->get_product_type();
			$product_url = $val->get_product_url();
			$image_url = $val->get_image_url();
			$condition = $val->get_condition();
			$availability = $val->get_availability();

			if($availability == "out of stock")
			{
				$availability = "in stock";
			}

			$price_w_curr = $val->get_price_w_curr();
			$brand_name = $val->get_brand_name();
			$mpn = $val->get_mpn();
			$upc = $val->get_upc();
			$ean = $val->get_ean();

			$item_group_id = $val->get_item_group_id();
			$colour_name = $val->get_colour_name();
			$shipping = $val->get_shipping();
			$prod_weight = $val->get_prod_weight();

			$lang_id = $val->get_language_id();
			$price = $val->get_price();
			$currency = $val->get_platform_currency_id();
			$platform_country_id = $val->get_platform_country_id();

			$product = new GSC_Product();
			$product->setTitle($title);


			$product->setDescription($description);

			$product->setProductLink($product_url);
			$google_ref_id = $platform_country_id.'-'.$sku;
			$product->setSKU($google_ref_id);
			$product->setImageLink($image_url);
			$product->setTargetCountry($platform_country_id);
			$product->setContentLanguage($lang_id);
			$product->setBrand($brand_name);
			$product->setCondition($condition);
			$product->setColor($colour_name);
			$product->setAvailability ($availability);
			$product->setGoogleProductCategory($google_product_category);
			$product->setProductType($product_type);
			$product->setPrice($price,$currency);
			$product->setShippingWeight($prod_weight,'kg');
			$product->setMpn($mpn);

			//set empty as GTIN for all platform
			$product->setGtin("");

			$product->setItemGroupId($item_group_id);
			$region = '';
			$shippingPrice = '0.00';
			$service = 'Standard';
			$product->addShipping($platform_country_id,$region,$shippingPrice,$currency,$service);


			$prepare_insert_item_list[] = $product;
			//var_dump($val);die();
		}


		$n = 0;
		try{
			$insertedFeed = $client->insertProducts($prepare_insert_item_list, true, false);
			$insertedProducts = $insertedFeed->getProducts();
			$result = array();

			$interrupted = FALSE;
			$interrupted_result = array();
			$is_fail = FALSE;
			foreach ($insertedProducts as $product)
			{
				//assume the insertProducts' order is the same of those product selected from our system.
				//this is use when error occur
				$sku = $data_list[$n]->get_sku();
				$title = $data_list[$n]->get_prod_name();

				if($feedback = $product->getBatchInterruptedAttribute('reason'))
				{
					$interrupted = TRUE;
					$interrupted_result[] = $feedback;
				}
				if($feedback = $product->getBatchInterruptedAttribute('success'))
				{
					$interrupted = TRUE;
					$interrupted_result[] = $feedback;
				}
				if($feedback = $product->getBatchInterruptedAttribute('failures'))
				{
					$interrupted = TRUE;
					$interrupted_result[] = $feedback;
				}
				if($feedback = $product->getBatchInterruptedAttribute('parsed'))
				{
					$interrupted = TRUE;
					$interrupted_result[] = $feedback;
				}

				if($interrupted && $interrupted_result)
				{
					foreach($data_list as $item_in_batch)
					{
						$interrupted_result[] = $item_in_batch->get_sku();
					}
					$this->mail_result($interrupted_result, "content for google shopping interrupted");
				}


				if($product->getBatchStatus() != '200' && $product->getBatchStatus() != '201')
				{
					if($errors = $product->getErrorsFromBatch())
					{
						$errorArray = $errors->getErrors();
						foreach ($errorArray as $error) {

							$result[] = "SKU: ".$sku."\r\n".
										"TITLE: ".$title."\r\n".
							 			"Code: ".$error->getCode()."\r\n".
										"Domain: ".$error->getDomain()."\r\n".
										'Location: '.$error->getLocation()."\r\n".
										"Internal Reason: ".$error->getInternalReason()."\r\n";
						}

						$is_fail = TRUE;

						$platform_id = $data_list[$n]->get_platform_id();
						$price = $data_list[$n]->get_price();
						$this->create_google_shopping_record($sku, $platform_id, 0, $price, $error->getInternalReason());
					}
				}
				else
				{
					if($product->getBatchStatus() == '200' || $product->getBatchStatus() == '201')
					{
						$temp_sku = $product->getSKU();

						$sku = substr($temp_sku,3);
						$price = $product->getPrice();
						$this->create_google_shopping_record($sku, $platform_id, 1, $price);
						$subject = "content for google shopping success to update";


						// if($item_obj = $this->get_dao()->get(array("sku"=>substr($temp_sku,3),"platform_id"=>$platform_id)))
						// {
						// 	$item_obj->set_status(1);
						// 	$this->get_dao()->update($item_obj);
						// 	$subject = "content for google shopping success to update";
						// }
						// else
						// {
						// 	$item_obj = $this->get_dao()->get();
						// 	$item_obj->set_sku(substr($product->getSKU(),3));
						// 	$item_obj->set_platform_id($platform_id);
						// 	$item_obj->set_status(1);
						// 	$this->get_dao()->insert($item_obj);
						// }


					}
				}

				$n++;
			}
			if($is_fail)
			{
				$this->mail_result($result, "ERROR: Google Shopping");
			}

		}catch(Exception $e){
			$result[] = $e->getMessage();
			$this->mail_result($result, "content for google shopping error: Insert Operation");
		}
	}
*/


/*
// API V1; obsolete
	public function batch_delete_item_v1($client, $sku, $platform_id)
	{
		$sku_list = array();
		if(is_array($sku))
		{
			$sku_list = array_merge($sku_list, $sku);
		}
		else
		{
			$sku_list[] = $sku;
		}

		$prepare_batch_item = array();

		foreach($sku_list as $val)
		{
			if($item_obj = $this->get_dao()->get(array("sku"=>$val, "platform_id"=>$platform_id, "status"=>1)))
			{
				if($platform_obj = $this->platform_biz_var_service->get(array("selling_platform_id"=>$platform_id)))
				{
					$platform_country_id = substr($platform_id,3);
					$language_id = $platform_obj->get_language_id();
					$google_ref_id = $platform_country_id.'-'.$val;

					try
					{
						//get the item first then delete.
						//var_dump($platform_country_id);var_dump($language_id);var_dump($google_ref_id);die();

						$product = $client->getProduct($google_ref_id, $platform_country_id , $language_id);

						if($product)
						{
							$prepare_batch_item[] = $product;
						}

					}catch(Exception $e){
						$result['error'] = $e->getMessage();
						$this->mail_result($result, "google shopping error: Single Item delete");
					}
				}
			}
		}

		try
		{

			if($prepare_batch_item)
			{
				$deletedFeed = $client->deleteProducts($prepare_batch_item);
				$deletedProducts = $deletedFeed->getProducts();
				$result = array();

				foreach($prepare_batch_item as $item)
				{
					if($item_obj = $this->get_dao()->get(array("sku"=>substr($item->getSKU(),3), "platform_id"=>$platform_id)))
					{
						$item_obj->set_status(0);
						$this->get_dao()->update($item_obj);
					}
				}
			}
		}catch(Exception $e){
			$result['error'] = $e->getMessage();
			if(count($sku_list) == 1)
			{
				$temp_sku = $sku_list[0];
				$result["SKU"] = $temp_sku;
			}
			$this->mail_result($result, "ERROR: Google Shopping Batch Delete Item");
		}
	}
*/


/*
// API V1; obsolete
	public function batch_delete_item_by_product_object_v1($client, $sku, $platform_id)
	{
		if($sku)
		{
			$this->batch_delete_item($client, $sku, $platform_id);
		}
		else
		{
			$start_token = "";
			try
			{
				do{
					$productFeed = $client->getProducts(200, $start_token);
					$start_token = $productFeed->getStartToken();
					$products = $productFeed->getProducts();

					$temp_product_list = array();
					foreach($products as $product)
					{
						$target_country = $product->getTargetCountry();
						$country_id = substr($platform_id, 3, 2);
						if($target_country == $country_id)
						{
							$temp_product_list[] = $product;
						}
					}

					$deletedFeed = $client->deleteProducts($temp_product_list);
				}while($start_token);

				if($platform_items = $this->get_dao()->get_list(array("platform_id"=>$platform_id), array("limit"=>-1)))
				{
					foreach($platform_items as $obj)
					{
						$obj->set_status(0);
						$this->get_dao()->update($obj);
					}
				}
			}catch(Exception $e){
				$result['error'] = $e->getMessage();
				$this->mail_result($result, "google shopping error: Platform All Items Delete");
			}
		}
	}
*/


/*
// API V1; obsolete
	public function delete_product_item_v1($platform_id="", $sku="" , $country_id="", $language_id="")
	{

		if($account_id = $this->get_shopping_api_accountId($platform_id))
		{
			$client = $this->shopping_api_login($account_id);

			$google_ref_id =  $country_id.'-'.$sku;

			try{
				if($product = $client->getProduct($google_ref_id, $country_id, $language_id))
				{
					$client->deleteProduct($product);
				}
			}catch(Exception $e)
			{
				$result['error'] = $e->getMessage();
				$this->mail_result($result, "content for google shopping error");
			}
		}


	}
*/

/*
// API v1
	function get_GSC_product($sku, $platform_id)
	{
		foreach($gs_obj_list as $gs_obj)
		{
			$title = $gs_obj->get_prod_name();

			$description = $gs_obj->get_detail_desc();
			$description = preg_replace('/[\x00-\x09\x0B\x0C\x0E-\x1F\x7F]/','', $description);

			$google_product_category = $gs_obj->get_google_product_category();
			$product_type = $gs_obj->get_product_type();
			$product_url = $gs_obj->get_product_url();
			$image_url = $gs_obj->get_image_url();
			$condition = $gs_obj->get_condition();
			$availability = $gs_obj->get_availability();
			if($availability == "out of stock")
			{
				$availability = "in stock";
			}

			$price_w_curr = $gs_obj->get_price_w_curr();
			$brand_name = $gs_obj->get_brand_name();
			$mpn = $gs_obj->get_mpn();

			$upc = $gs_obj->get_upc();
			$ean = $gs_obj->get_ean();

			$gitn = $upc?$upc:$ean;
			$item_group_id = $gs_obj->get_item_group_id();
			$colour_name = $gs_obj->get_colour_name();
			$shipping = $gs_obj->get_shipping();
			$prod_weight = $gs_obj->get_prod_weight();

			$lang_id = $gs_obj->get_language_id();
			$price = $gs_obj->get_price();
			$currency = $gs_obj->get_platform_currency_id();
			$platform_country_id = $gs_obj->get_platform_country_id();

			$product = new GSC_Product();
			$product->setTitle($title);
			$product->setDescription($description);
			$product->setProductLink($product_url);
			$google_ref_id = $platform_country_id.'-'.$sku;
			$product->setSKU($google_ref_id);
			$product->setImageLink($image_url);
			$product->setTargetCountry($platform_country_id);
			$product->setContentLanguage($lang_id);
			$product->setBrand($brand_name);
			$product->setCondition($condition);
			$product->setColor($colour_name);
			$product->setAvailability ($availability);
			$product->setGoogleProductCategory($google_product_category);
			$product->setProductType($product_type);
			$product->setPrice($price,$currency);
			$product->setShippingWeight($prod_weight,'kg');
			$product->setMpn($mpn);

			if(($google_ref_id == "FR-16060-AA-WH") || ($google_ref_id == "FR-16060-AA-BK"))
			{
				$extra_track_parameter = "&source={ifdyn:dyn}{ifpe:pe}{ifpla:pla}&dyprodid=$google_ref_id";
				$product->setAdwordsRedirect($product_url.$extra_track_parameter);
			}


			//set empty as GTIN for all platform
			//if( $platform_id == "WEBES")
			//{
				$product->setGtin("");
			//}
			//else
			//{
			//	$product->setGtin($gitn);
			//}

			$product->setItemGroupId($item_group_id);

			$region = '';
			$shippingPrice = '0.00';
			$service = 'Standard';
			$product->addShipping($platform_country_id,$region,$shippingPrice,$currency,$service);
			return $product;
		}
	}
*/

/*
// API V1; obsolete
	public function update_google_shopping_item_by_platform_v1($platform_id="", $sku="")
	{
		if(!$account_id = $this->get_shopping_api_accountId($platform_id))
		{
			return true;
		}

		$where = $d_where = array();

		if($sku)
		{
			if(is_array($sku))
			{
				$query_str = "";
				foreach($sku as $v)
				{
					$query_str .= "'".$v."',";
				}

				$query_str = rtrim($query_str, ',');
				$where['pr.sku in ('. $query_str .')'] = null;

				$d_where['sku in ('. $query_str .')'] = null;

			}
			else
			{
				$where["pr.sku in ('". $sku ."')"] = null;

				$d_where["sku in ('". $sku ."')"] = null;
			}
		}
		$data_list = $this->gen_data_feed($platform_id, $shopping_api = TRUE, $where);


		// *******************
		// *when run this function, update the google_shopping record too
		// *if update all product by platform, then all google_shopping data in
		// *that platform will be delete first, then records will be created again base on the
		// *request return result.
		// *******************
		$d_where["platform_id"] =  $platform_id;
		$this->get_dao()->q_delete($d_where);

		$client = $this->shopping_api_login($account_id);


		try{
			$this->batch_delete_item_by_product_object($client, $sku, $platform_id);
			//die();
		}catch(Exception $e)
		{
			$result['error'] = $e->getMessage();
			$this->mail_result($result, "content for google shopping error");
		}


		if($data_list)
		{
			$chunk_data_list = array_chunk($data_list, 200, false);

			foreach($chunk_data_list as $chunk_data)
			{
				try{
					$this->batch_insert_item($client, $chunk_data, $platform_id);
				}catch(Exception $e){
					$result['error'] = $e->getMessage();
					$this->mail_result($result, "google shopping fail to insert");
				}
			}
		}
	}

*/
// =========== SBF #6199 -- END OF API VERSION 1 FUNCTIONS ============================================================================================================================ //

}
