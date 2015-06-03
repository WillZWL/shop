<?php
require_once(BASEPATH . "plugins/gshoppingcontent/GShoppingContent.php");
include_once(APPPATH."hooks/country_selection.php");

class Ext_category_mapping extends MY_Controller
{
	private $app_id = "MKT0074";
	private $lang_id = "en";

	public function __construct()
	{
		parent::MY_controller();
		$this->load->model('marketing/ext_category_mapping_model');
		$this->load->library('service/adwords_service');
		$this->load->library('service/category_mapping_service');
		$this->load->library('service/platform_biz_var_service');
		$this->load->library('service/product_service');
		//$this->load->library('service/price_service');
		$this->load->model('marketing/product_model');
		$this->load->library('service/google_shopping_service');
	}

	public function index()
	{
		$sub_capp_id = $this->_get_app_id()."00";
		$where = $option = array();
		$where["status"] = 1;
		$option["limit"] = -1;
		$cat_details_list = $this->ext_category_mapping_model->process_cat_detail($where, $option);
		$data['cat_details_list'] = $cat_details_list;

		$country_list = $this->ext_category_mapping_model->get_country_list();

		$data['country_list'] = $country_list;

		if(!empty($_GET))
		{
			if(isset($_GET["gcat"]))
			{
				$gcat_name = $_GET["gcat"];
				if($_GET["gcat_wildtype"] == "begin")
					$gcat_where["ext_name LIKE '$gcat_name%'"] = null;
				elseif($_GET["gcat_wildtype"] == "end")
					$gcat_where["ext_name LIKE '%$gcat_name'"] = null;
				else
					$gcat_where["ext_name LIKE '%$gcat_name%'"] = null;
			}
			if(isset($_GET["gcat_country"]))
				$gcat_where["country_id"] = $_GET["gcat_country"];
		}
		$google_category_list = $this->ext_category_mapping_model->get_google_category_list($gcat_where);
		$data['google_category_list'] = $google_category_list;

		$data["google_datafeed_account"] = array();
		$data["google_datafeed_account"][] = array("account_name"=>"ValueBasket.SG", "account_id"=>8384686, "country"=>array("SG"), "language"=>array("en"));
		$data["google_datafeed_account"][] = array("account_name"=>"ValueBasket.it", "account_id"=>9674225, "country"=>array("IT"), "language"=>array("it"));
		$data["google_datafeed_account"][] = array("account_name"=>"ValueBasket.fi", "account_id"=>11038072, "country"=>array("FI"), "language"=>array("en"));
		$data["google_datafeed_account"][] = array("account_name"=>"ValueBasket.ch", "account_id"=>11328624, "country"=>array("CH"), "language"=>array("en"));
		$data["google_datafeed_account"][] = array("account_name"=>"ValueBasket.fr", "account_id"=>7852736, "country"=>array("FR"), "language"=>array("fr"));
		$data["google_datafeed_account"][] = array("account_name"=>"ValueBasket.com.au", "account_id"=>8113126, "country"=>array("AU"), "language"=>array("en"));
		$data["google_datafeed_account"][] = array("account_name"=>"Valuebasket.be", "account_id"=>8121966, "country"=>array("BE"), "language"=>array("fr"));
		$data["google_datafeed_account"][] = array("account_name"=>"ValueBasket.com", "account_id"=>8551995, "country"=>array("GB", "CH"), "language"=>array("en"));
		$data["google_datafeed_account"][] = array("account_name"=>"ValueBasket.es", "account_id"=>15241301, "country"=>array("ES"), "language"=>array("es"));
		$data["google_datafeed_account"][] = array("account_name"=>"ValueBasket.pl", "account_id"=>100892246, "country"=>array("PL"), "language"=>array("pl"));
		$data["google_datafeed_account"][] = array("account_name"=>"ValueBasket.com", "account_id"=>101019203, "country"=>array("US"), "language"=>array("en"));


		/*
		$where = $option = array();
		$option['limit'] = -1;
		$where['ext_c.country_id in ("AU", "GB")'] = null;
		//var_dumP($this->db->last_query());die();
		$container = array();
		if($s = $this->ext_category_mapping_model->get_google_category_mapping_list($where, $option))
		{
			//var_dumP($s);die();
			foreach($s as $d)
			{
				$container[$d->get_country_id()][$d->get_category_id()] =  $d->get_google_category_name();
			}
		}

		//var_dump($container['SG']);
		//var_dump($this->db->last_query());die();
		$data['existsing_mapping'] = array();


		if($country_list)
		{
			foreach($country_list as $c)
			{
				//$t[$c] = @$container[$c];
				$data['existsing_mapping']["$c"] = @$container[$c];
			}
		}


		//var_dump($data['existsing_mapping']);die();

		$category_id_w_name = array();
		$where = $option = array();
		$option['limit'] = -1;

		if($category_combination = $this->ext_category_mapping_model->get_category_combination($where,$option ))
		{
		//var_dump($this->db->last_query());die();
			foreach($category_combination as $c)
			{
				//echo $c->get_id();
				//echo preg_replace("/Base->/", '', $c->get_name());
				//echo "<br>";
				$category_name = preg_replace("/Base->/", '', $c->get_name());

				$i = strpos($category_name,'->');
				$i = $i? $i:"100";

				$first_category_level = substr($category_name, 0, $i); //this is category name


				$category_classification[$first_category_level][$c->get_id()] = $category_name;

				//$category_id_w_name[$c->get_id()] = preg_replace("/Base->/", '', $c->get_name());
			}
		}
		$data['category_classification'] = $category_classification;
		*/

		$this->load->view('marketing/ext_category_mapping/ext_category_mapping_index', $data);
	}

	public function get_google_category_existing_mapping()
	{
		$where = $option = array();
		$option['limit'] = -1;
		//var_dumP($this->db->last_query());die();
		$container = array();
		if($s = $this->ext_category_mapping_model->get_google_category_mapping_list($where, $option))
		{
			foreach($s as $d)
			{
				$container[$d->get_country_id()][$d->get_category_id()] =  $d->get_google_category_name();
			}
		}

		$t = array();

		if($country_list = $this->ext_category_mapping_model->get_country_list())
		{
			foreach($country_list as $c)
			{
				$t[$c] = @$container[$c];
			}
		}
	}

	public function create_mapping_rule()
	{
		$cat_id = trim($_POST["cat_id"]);
		$sub_cat_id = trim($_POST["sub_cat_id"]);
		$sub_sub_cat_id = trim($_POST["sub_sub_cat_id"]);
		$country_id =  trim($_POST["country_id"]);
		$target_google_category =  trim($_POST["target_google_category"]);

		if(($cat_id=="" && $sub_cat_id=="" && $sub_sub_cat_id=="") || $country_id=="" || $target_google_category=="")
		{
			echo "Please check you input and try again.";
		}
		else
		{
			//always mapping to the more details category_id
			$categroy_id = $sub_sub_cat_id?$sub_sub_cat_id:($sub_cat_id?$sub_cat_id:$cat_id);
			$feedback = $this->ext_category_mapping_model->create_or_update_mapping($categroy_id,$target_google_category,$country_id);
			echo $feedback;
		}
	}

	public function create_google_category()
	{
		$new_google_cat = rtrim($_POST["new_google_cat"], " > ");
		$country_list = $_POST["country_list"];
		$feedback = $this->ext_category_mapping_model->create_new_google_category($new_google_cat, $country_list);
		echo $feedback;
	}


	public function account_info()
	{
		$account_info = array();
		$account_info[] = array("accountId"=>"493-907-8910","accountName"=>"API Test Account");
		$account_info[] = array("accountId"=>"212-603-9902","accountName"=>"VB AU");
		$account_info[] = array("accountId"=>"361-241-0604","accountName"=>"VB ES");
		$account_info[] = array("accountId"=>"316-460-3467","accountName"=>"VB FR");
		$account_info[] = array("accountId"=>"899-782-9704","accountName"=>"VB IT");
		$account_info[] = array("accountId"=>"220-522-9085","accountName"=>"VB UK");
		$account_info[] = array("accountId"=>"556-933-8151","accountName"=>"VB CH");
		$account_info[] = array("accountId"=>"960-837-9622","accountName"=>"VB FI");
		$account_info[] = array("accountId"=>"933-307-6722","accountName"=>"VB MT");
		$account_info[] = array("accountId"=>"766-479-7671","accountName"=>"VB IE");
		$account_info[] = array("accountId"=>"423-123-0557","accountName"=>"VB BE");
		$account_info[] = array("accountId"=>"229-179-7402","accountName"=>"VB PT");

		$account_info[] = array("accountId"=>"182-353-3787","accountName"=>"VB NZ");
		$account_info[] = array("accountId"=>"492-329-4157","accountName"=>"VB MY");
		$account_info[] = array("accountId"=>"952-771-4151","accountName"=>"VB PH");
		$account_info[] = array("accountId"=>"383-339-9953","accountName"=>"VB SG");
		$account_info[] = array("accountId"=>"339-560-2926","accountName"=>"VB RU");
		$account_info[] = array("accountId"=>"923-383-8759","accountName"=>"CV HK");
		$account_info[] = array("accountId"=>"312-691-2272","accountName"=>"SE AU");
		$account_info[] = array("accountId"=>"958-318-2390","accountName"=>"SE UK");
		$account_info[] = array("accountId"=>"791-772-7172","accountName"=>"SE EU");

		header('Cache-Control: no-cache, must-revalidate');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Content-type: application/json');
		echo json_encode($account_info);
	}



	public function compaign_info()
	{

		$ad_accountId = trim($_POST['ad_accountId']);
		$user = $this->adwords_service->init_account($ad_accountId);

		$result = array();
		if(empty($ad_accountId))
		{
			$result[] = array("error"=>"invalid input: please select account");
			echo json_encode($result);
			exit();
		}
		$result = $this->adwords_service->compaign_info($user);
		echo json_encode($result);
	}


	public function adgroup_info()
	{
		$ad_accountId = trim($_POST['ad_accountId']);
		$user = $this->adwords_service->init_account($ad_accountId);
		$campaignId = trim($_POST['campaignId']);
		$result = array();
		if(empty($ad_accountId) || empty($campaignId))
		{
			$result[] = array("error"=>"invalid input: please try again");
			echo json_encode($result);
			exit();
		}
		$result = $this->adwords_service->adgroup_info($user, $campaignId);
		echo json_encode($result);
	}


	public function keyword_info()
	{
		$ad_accountId = trim($_POST['ad_accountId']);
		$user = $this->adwords_service->init_account($ad_accountId);
		$adGroupId = trim($_POST['adGroupId']);
		$result = array();
		if(empty($ad_accountId) || empty($adGroupId))
		{
			$result[] = array("error"=>"invalid input: please try again");
			echo json_encode($result);
			exit();
		}
		$result = $this->adwords_service->keyword_info($user, $adGroupId);
		echo json_encode($result);
	}

	public function adGroup_ad_info()
	{
		$ad_accountId = trim($_POST['ad_accountId']);
		$user = $this->adwords_service->init_account($ad_accountId);
		$adGroupId = trim($_POST['adGroupId']);
		$result = array();
		if(empty($ad_accountId) || empty($adGroupId))
		{
			$result[] = array("error"=>"invalid input: please try again");
			echo json_encode($result);
			exit();
		}
		$result = $this->adwords_service->adGroup_ad_info($user, $adGroupId);
		echo json_encode($result);
	}

	public function keyword_parameter_info()
	{
		$ad_accountId = trim($_POST['ad_accountId']);
		$keywordId = trim($_POST['keywordId']);
		$user = $this->adwords_service->init_account($ad_accountId);
		$adGroupId = trim($_POST['adGroupId']);
		$result = array();
		if(empty($ad_accountId) || empty($adGroupId) ||  empty($keywordId))
		{
			$result[] = array("error"=>"invalid input: please try again");
			echo json_encode($result);
			exit();
		}
		$feedback = '';

		$result = $this->adwords_service->keyword_parameter_info($user, $adGroupId, $keywordId);
		foreach($result as $val)
		{
			$feedback .="keyword ID: ".$val->criterionId."<br>";
			$feedback .="Attached Param: ".$val->insertionText."<br>";
			$feedback .="ParamIndex: ".$val->paramIndex."<br>";
		}
		echo $feedback;
	}



	public function start_point($sku, $platform_id)
	{
		$this->adwords_service->start_point($sku, $platform_id);
	}



	public function update_ad_price($sku, $platform_id)
	{
		$this->adwords_service->update_ad_price($sku, $platform_id);
	}


	public function pause_or_resume_adGroup($sku, $platform_id, $status = 'PAUSED')
	{
		//status: PAUSED, DELETED, ENABLED
		$this->adwords_service->pause_or_resume_adGroup($sku, $platform_id, $status);
	}


	function process_data($sku, $platform_id, $test = 0)
	{
		$result = $this->adwords_service->process_data($sku, $platform_id, $test = 0);
		var_dump($result);
	}


	public function get_product_item($id = "", $country = "", $language = "")
	{
		$debug = FALSE;
		if(strpos($_SERVER["HTTP_HOST"], "dev") != FALSE)
			$debug = TRUE;

		$account_id = @$_POST["account_id"];
		$country = @$_POST["country_id"];
		$language = @$_POST["language_id"];
		$sku = @$_POST["sku"];

		$exec = false;
		if($account_id && $country && $language && $sku)
		{
			$google_ref_id = $country.'-'.$sku;

			// Make sure your product ID is of the form channel:languageCode:countryCode:offerId.
			list($id, $country, $language) = array($google_ref_id, $country, $language);
			$postdata["productid"] = "online:$language:$country:$id";
			$getproduct_result = $this->google_shopping_service->shopping_api_connect('getproduct', $account_id, $debug, $postdata);

			if($getproduct_result["status"] == TRUE)
			{
				if((array)$getproduct_result["data"])
				{
					$exec = TRUE;
					$product = $getproduct_result["data"];

					$sku = $product->offerId;
					$title = $product->title;
					$condit = $product->condition;
					$avail = $product->availability;
					$brand = $product->brand;
					$gtin = $product->gtin;
					$mpn = $product->mpn;
					$price = $product->price->value;
					$currency = $product->price->currency;
					$google_categorys = $product->googleProductCategory;
					$adwords_redirect = $product->adwordsRedirect;

					$result =<<<end
						<input type="hidden" id="item_account" value="$account_id">
						<input type="hidden" id="item_country" value="$country">
						<input type="hidden" id="item_language" value="$language">
						<label class="item_label">sku</label><input class="input_box" id="item_sku" value="$sku" readonly>
						<label class="item_label">title</label><input class="input_box" id="item_title" value="$title">
						<label class="item_label">cond</label><input class="input_box" id="item_condi" value="$condit">
						<label class="item_label">price</label><input class="input_box" id="item_price" value="$price">  <br>
						<label class="item_label">brand</label><input class="input_box" id="item_brand" value="$brand">
						<label class="item_label">avail</label><input class="input_box" id="item_valid" value="$avail">
						<label class="item_label">gtin</label><input class="input_box" id="item_gtin" value="$gtin">
						<label class="item_label">mpn</label><input class="input_box" id="item_mpn" value="$mpn">  <br>
						<label class="item_label">currency</label><input class="input_box" id="item_currency" value="$currency">
						<label class="item_label">google_cat</label><input class="input_box" id="item_google_categorys" style="width:680px" value="$google_categorys">
						<br>
						<label class="item_label">adwords_redirect</label><input class="input_box" id="item_adwords_redirect" style="width:680px" value="$adwords_redirect">

end;
					echo $result;
					die();

				}
				else
				{
					// status=true but no object?
				}
			}
			else
			{
				// $result["getProduct"] .= __LINE__." Error from google_connect [$sku - $platform_id]: \r\n{$getproduct_result["error_message"]} \r\n";
			}
		}
		else
		{
			// echo "No account_id / country / language / sku found";
		}

		echo "No Result Found";
	}

/*
// API V1; obsolete
	public function get_product_item_v1($id = "", $country = "", $language = "")
	{
		$account_id = @$_POST["account_id"];
		$country = @$_POST["country_id"];
		$language = @$_POST["language_id"];
		$sku = @$_POST["sku"];

		$client = $this->google_shopping_service->shopping_api_login($account_id);

		try{
			$product = $client->getProduct($sku, $country, $language);
		}catch(Exception $e)
		{
			echo $e->getMessage();
		}

		if($product)
		{
			$sku = $product->getSku();
			$title = $product->getTitle();
			$condit = $product->getCondition();
			$price = $product->getPrice();
			$avail = $product->getAvailability();
			//$google_cat = $product->getg();
			$brand = $product->getBrand();
			$gtin = $product->getGtin();
			$mpn = $product->getMpn();
			$currency = $product->getPriceUnit();
			$google_categorys = $product->getGoogleProductCategory();
			$adwords_redirect = $product->getAdwordsRedirect();

			$result =<<<end
				<input type="hidden" id="item_account" value="$account_id">
				<input type="hidden" id="item_country" value="$country">
				<input type="hidden" id="item_language" value="$language">
				<label class="item_label">sku</label><input class="input_box" id="item_sku" value="$sku" readonly>
				<label class="item_label">title</label><input class="input_box" id="item_title" value="$title">
				<label class="item_label">cond</label><input class="input_box" id="item_condi" value="$condit">
				<label class="item_label">price</label><input class="input_box" id="item_price" value="$price">  <br>
				<label class="item_label">brand</label><input class="input_box" id="item_brand" value="$brand">
				<label class="item_label">avail</label><input class="input_box" id="item_valid" value="$avail">
				<label class="item_label">gtin</label><input class="input_box" id="item_gtin" value="$gtin">
				<label class="item_label">mpn</label><input class="input_box" id="item_mpn" value="$mpn">  <br>
				<label class="item_label">currency</label><input class="input_box" id="item_currency" value="$currency">
				<label class="item_label">google_cat</label><input class="input_box" id="item_google_categorys" style="width:680px" value="$google_categorys">
				<br>
				<label class="item_label">adwords_redirect</label><input class="input_box" id="item_adwords_redirect" style="width:680px" value="$adwords_redirect">

end;
			echo $result;
		}
		else
		{
			echo "No Result Found";
		}
	}
*/

	public function update_product_item()
	{
		//"item_sku, item_title, item_condi,item_price,item_brand,item_valid, item_mpn
		//"item_gtin, item_currency, item_country,item_account,item_language,item_google_categorys

		$_POST["item_sku"] = "AU-18066-AA-NA";
		$_POST["item_title"] = "Orbotix Sphero 2.0 Revealed Robot (Limited Edition)";
		$_POST["item_google_categorys"] = "Toys & Games > Toys";
		$_POST["item_mpn"] = "S003AP";
		$_POST["item_account"] = 8113126;
		$_POST["item_country"] = "AU";
		$_POST["item_language"] = "en";

		foreach($_POST as $key=>$val)
		{
			$$key = $val;
		}

		$debug = FALSE;
		if(strpos($_SERVER["HTTP_HOST"], "dev") != FALSE)
			$debug = TRUE;

		$account_id = $item_account;
		$google_ref_id = $item_sku;  # e.g. AU-18066-AA-NA
		$country = $item_country;
		$language = $item_language;

		if($account_id && $google_ref_id && $country &&  $language)
		{
			$sku = substr($item_sku, 3);
			$platform_id = "WEB".$country;
			$category_service = $this->category_mapping_service;
			$obj = $category_service->get_dao()->get(array("ext_party"=>"GOOGLEBASE", "id" =>$sku, "country_id"=>$item_country));
			// var_dump($category_service->get_dao()->db->last_query());die();

			// update new title into db first
			if($obj)
			{
				$obj->set_product_name($item_title);
				$category_service->get_dao()->update($obj);
			}

			// Make sure your product ID is of the form channel:languageCode:countryCode:offerId.
			list($id, $country, $language) = array($google_ref_id, $country, $language);
			$postdata["productid"] = "online:$language:$country:$id";
			$getproduct_result = $this->google_shopping_service->shopping_api_connect('getproduct', $account_id, $debug, $postdata);

			if($getproduct_result["status"] == TRUE)
			{
				if((array)$getproduct_result["data"])
				{
					$exec = TRUE;

					$GSC_product = $this->google_shopping_service->get_GSC_product($sku, $platform_id);
					if($GSC_product)
					{
						//insert the item, if item already exists, then it will update it
						$postdata["product"] = json_encode($GSC_product);
						$insertproduct_result = $this->google_shopping_service->shopping_api_connect('insertproduct', $account_id, $debug, $postdata);

						if($insertproduct_result)
						{
							if($insertproduct_result["status"] == TRUE)
							{
								//if success
								$result = "update successfully";
							}
							else
							{
								$result = __LINE__." Error from google_connect [$sku - $platform_id]: {$insertproduct_result["error_message"]}";
							}
						}
						else
						{
							$result = __LINE__." [$sku - $platform_id] No response detected";
						}
					}
				}
				else
				{
					$result = __LINE__." [$sku - $platform_id] No product data returned";
				}
			}
			else
			{
				$result = __LINE__." Error from google_connect [$sku - $platform_id]: \r\n{$getproduct_result["error_message"]} \r\n";
			}

		}
		else
		{
			$result = "Missing account_id, google sku, country or language";
		}

		echo $result;
	}

/*
// API V1; obsolete
	public function update_product_item_v1()
	{
		//"item_sku, item_title, item_condi,item_price,item_brand,item_valid, item_mpn
		//"item_gtin, item_currency, item_country,item_account,item_language,item_google_categorys
		foreach($_POST as $key=>$val)
		{
			$$key = $val;
		}
		$account_id = $item_account;
		$client = $this->google_shopping_service->shopping_api_login($account_id);

		try{
			$product = $client->getProduct($item_sku, $item_country, $item_language);
		}catch(Exception $e){
			echo $e->getMessage();
		}

		if($product)
		{
			try{
				$product->setTitle($item_title);
				//$product->setCondition($item_condi);
				//$item_price = number_format ($item_price, 2, '.', ',');
				//$product->setPrice($item_price, $item_currency);
				//$product->setAvailability($item_valid);
				//$product->setBrand($item_brand);
				//$product->setGtin($item_gtin);
				//$product->setMpn($item_mpn);
				//$product->setGoogleProductCategory($item_google_categorys);

				$client->updateProduct($product, true, false);

				$category_service = $this->category_mapping_service;
				$sku = substr($item_sku, 3);
				$obj = $category_service->get_dao()->get(array("ext_party"=>"GOOGLEBASE", "id" =>$sku, "country_id"=>$item_country));
				//var_dump($category_service->get_dao()->db->last_query());die();

				if($obj = $category_service->get_dao()->get(array("ext_party"=>"GOOGLEBASE", "id" =>$sku, "country_id"=>$item_country)))
				{
					$obj->set_product_name($item_title);
					$category_service->get_dao()->update($obj);
				}

				//echo "$item_price";die();
				$warnings = $product->getWarnings();
				if($warnings->length == 0)
				{
					$result = "update successfully";
				}
				else
				{
					$result = "";
					for($index = 0; $index < $warnings->length; $index++) {
						$warning = $warnings->item($index);
						$result .='Code: ' . $product->getWarningCode($warning) . "\n";
						$result .='Domain: ' . $product->getWarningDomain($warning) . "\n";
						$result .='Location: ' . $product->getWarningLocation($warning) . "\n";
						$result .='Message: ' . $product->getWarningMessage($warning) . "\n";
					}
				}
				echo $result;

			}catch(Exception $e)
			{
				echo $e->getMessage();
			}
		}
	}
*/

 	public function  delete_product_item($platform_id="", $sku="" , $country_id="", $language_id="")
	{
		$this->google_shopping_service->delete_product_item($platform_id, $sku, $country_id, $language_id);
	}


	public function gen_data_feed($platform_id = "WEBSG")
	{
		$result = $this->google_shopping_service->gen_data_feed($platform_id);
	}

	//confusing function of update_google_shopping_item_by_platform and cron_update_google_shopping_feed
	//
	public function update_google_shopping_item_by_platform($platform_id = "WEBSG", $sku = "")
	{
		$this->google_shopping_service->update_google_shopping_item_by_platform($platform_id, $sku);
	}

	public function cron_update_google_shopping_feed($sku="", $specified_platform="")
	{
		$this->google_shopping_service->cron_update_google_shopping_feed($sku, $specified_platform);
	}

	public function get_google_shopping_content_report($platform_id="")
	{
		$return = $this->google_shopping_service->get_google_shopping_content_report($platform_id);
	}

	public function update_adGroup_keyword_price_paramter($sku="", $platform_id="", $price="")
	{
		$this->adwords_service->update_adGroup_keyword_price_paramter($sku, $platform_id, $price);
	}

	public function create_adGroup_by_platform_list($google_adwords_target_platform_list = "", $sku = "")
	{
		$this->adwords_service->create_adGroup_by_platform_list($google_adwords_target_platform_list, $sku);
	}

	public function update_adGroup_status_by_stock_status($sku = "", $platform_id ="", $status = "")
	{
		$this->adwords_service->update_adGroup_status_by_stock_status($sku, $platform_id, $status);
	}

	public function gsc_cache_api_exec()
	{
		// debuggin line
		 $this->google_shopping_service->cache_api_exec_debug();

		// original line
		// $this->google_shopping_service->cache_api_exec();
	}

	public function ad_cache_api_exec()
	{
		$this->adwords_service->cache_api_exec();
	}

	public function _get_app_id()
	{
		return $this->app_id;
	}

	public function _get_language_id()
	{
		return $this->lang_id;
	}

	public function get_country_google_category_mapping()
	{
		$country_id = $_POST["country_id"];

		$where = $option = array();
		$option['limit'] = -1;
		$where['ext_c.country_id'] = $country_id;
		//var_dumP($this->db->last_query());die();

		$container = array();
		if($s = $this->ext_category_mapping_model->get_google_category_mapping_list($where, $option))
		{
			//var_dumP($s);die();
			foreach($s as $d)
			{
				$container[$d->get_country_id()][$d->get_category_id()] =  $d->get_google_category_name();
			}
		}

		//var_dump($container['SG']);
		//var_dump($this->db->last_query());die();
		$data['existsing_mapping'] = array();


		if($country_list)
		{
			foreach($country_list as $c)
			{
				//$t[$c] = @$container[$c];
				$data['existsing_mapping']["$c"] = @$container[$c];
			}
		}


		//var_dump($data['existsing_mapping']);die();

		$category_id_w_name = array();
		$where = $option = array();
		$option['limit'] = -1;
		//$category_combination = $this->ext_category_mapping_model->get_category_combination($where,$option);

		//var_dump($category_combination);

		if($category_combination = $this->ext_category_mapping_model->get_category_combination($where,$option))
		{
		//var_dump($this->db->last_query());die();
			foreach($category_combination as $c)
			{
				//echo $c->get_id();
				//echo preg_replace("/Base->/", '', $c->get_name());
				//echo "<br>";

				$category_name = preg_replace("/Base->/", '', $c->name);

				$i = strpos($category_name,'->');
				$i = $i? $i:"100";

				$first_category_level = substr($category_name, 0, $i); //this is category name


				$category_classification[$first_category_level][$c->id] = $category_name;

				//$category_id_w_name[$c->get_id()] = preg_replace("/Base->/", '', $c->get_name());
			}
		}

		$s = "";
	    if($category_classification)
		{
			foreach($category_classification as $first_category_level=>$sub_list)
			{

				$s .="<div class='sub_accordion'>	<h3>{$first_category_level}</h3> <div> <p>";

				foreach($sub_list as $category_id=>$combination_category_name)
				{
					//$container[$d->get_country_id()][$d->get_category_id()]
					$google_category_name = $container[$country_id][$category_id];
					if(!$google_category_name)
					{
						$status = 'invalid_google_cat';
					}
					else
					{
						$status = 'valid_google_cat';
					}
					$s .= '<input type="text"  class="system_cat '.$status.'" value="'.$combination_category_name.'" readonly><input type="text" class="google_cat '.$status.'" value="'.$google_category_name.'" readonly> <br>';
				}
				$s .="</p></div></div>";
			}
		}

		//$data['category_classification'] = $category_classification;
		echo $s;
	}
}
?>