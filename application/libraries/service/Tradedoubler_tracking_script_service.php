<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Multipage_tracking_script_service.php";

#SBF#2220

class Tradedoubler_tracking_script_service extends Multipage_tracking_script_service
{
	private function test()
	{
		$a = new Tradedoubler_tracking_script_service();
		$a->set_country_id("FR");

		echo $a->get_fixed_code();
		echo "***************************************************\r\n";
		echo $a->get_variable_code("homepage");

		$param["id"] = "1234-AA-NA";
		$param["price"] = "999.87";
		$param["currency"] = "SGD";
		$param["name"] = "my phone";
		$param_list[] = $param;
		$param_list[] = $param;
		// echo $a->get_variable_code("category", $param_list);

		$param["productId"] = "1234-AA-NA";
		$param["category"] = "phone";
		$param["brand"] = "nokia";
		$param["productName"] = "my phone";
		$param["productDescription"] = "very power phone";
		$param["price"] = "998.81";
		$param["currency"] = "SGD";
		$param["url"] = "http://www.google.com";
		$param["imageUrl"] = "http://www.google.com/logo.png";
		// echo $a->get_variable_code("product", $param);

		$param_list = null;
		$param["id"] = "1234-AA-NA";
		$param["price"] = "999.87";
		$param["currency"] = "SGD";
		$param["name"] = "my phone";
		$param["qty"] = "9";
		$param_list[] = $param;
		$param_list[] = $param;
		// echo $a->get_variable_code("basket", $param_list);

		$param_list = null;
		$product_list = null;
		$param["id"] = "1234-AA-NA";
		$param["price"] = "999.87";
		$param["currency"] = "SGD";
		$param["name"] = "my phone";
		$param["qty"] = "9";
		$product_list[] = $param;
		$product_list[] = $param;
		$param_list["product_list"] = $product_list;
		$param_list["order_id"] = "5678";
		$param_list["order_value"] = "999.11";
		$param_list["currency"] = "USD";

		echo $a->get_variable_code("payment_success", $param_list);

		die();
	}

	public function get_fixed_code()
	{
		$ret_code = "";
		switch ($this->get_country_id()) {
			case "FR":
				// this portion appears in all tradedoubler javascripts
				$ret_code = <<<tradedoubler_code
					<script type="text/javascript">
					if(typeof (TDConf) != "undefined")
					{
						TDConf.sudomain = ("https:" == document.location.protocol) ? "swrap" : "wrap";
						TDConf.host = ".tradedoubler.com/wrap";
						TDConf.containerTagURL = (("https:" == document.location.protocol) ? "https://" : "http://")  + TDConf.sudomain + TDConf.host;

						if (typeof (TDConf.Config) != "undefined")
						{
					    	document.write(unescape("%3Cscript src='" + TDConf.containerTagURL  + "?id="+ TDConf.Config.containerTagId +" ' type='text/javascript'%3E%3C/script%3E"));
						}
					}
					</script>
tradedoubler_code;
				break;

			default:
				$ret_code = "";
				break;
		}
		return $ret_code;
	}

	public function get_variable_code($page_type, $product_list, $param)
	{
		$ret_code = "";
		switch($this->get_country_id())
		{
			case "FR":
				switch ($page_type)
				{
					case "homepage": 		return $this->tradedoubler_homepage_code(); 							break;
					case "category": 		return $this->tradedoubler_category_code($product_list); 				break;
					case "product": 		return $this->tradedoubler_product_code($param); 						break;
					case "basket": 			return $this->tradedoubler_basket_code($product_list); 					break;
					case "payment_success": return $this->tradedoubler_payment_success_code($product_list,$param); 	break;
				}
				break;
			default:
				return "";
		}
	}

	private function tradedoubler_encode($array)
	{
		// encode product list array into string format for javascript
		$ret_code = "";

		foreach ($array as $key=>$value)
			$ret_code .= "$key:\"$value\",";

		$ret_code = rtrim($ret_code, ",");
		return "\r\n{" . $ret_code . "}";
	}

	private function tradedoubler_homepage_code()
	{
		$ret_code = <<<javascript
				<script type="text/javascript">
				var TDConf = TDConf || {};
				TDConf.Config =
				{
					protocol : document.location.protocol,
					containerTagId : "6211"
				};
			</script>
javascript;
		return $ret_code;
	}

	#test url http://dev.valuebasket.fr/fr_FR/Canon-Lenses/cat/view/146
	private function tradedoubler_category_code($product_list)
	{
			// $param_list["id"] = "";
			// $param_list["price"] = "";
			// $param_list["currency"] = "";
			// $param_list["name"] = "";
			// $prod_list[] = $param_list;

		foreach ($product_list as $key=>$value)
		{
			$json[] = $this->json_encode_no_quote($value);
		}

		$product_list = implode(",", $json);

			// {id: "[product-id1]", price:"[price1]", currency:"[currency1]", name:"[product-name1]"},
			// {id: "[product-id2]", price:"[price2]", currency:"[currency2]", name:"[product-name2]"}

		// this is meant for our category page located at
		// http://dev.valuebasket.fr/fr_FR/Mobile-Phones/cat/view/4
		$ret_code = <<<javascript
			<script type="text/javascript">
				var TDConf = TDConf || {};
				TDConf.Config =
				{
				  	products:[ $product_list
				  	],
					containerTagId : "6212"
				};
			</script>
javascript;
		return $ret_code;
	}

	private function tradedoubler_product_code($param)
	{
		$ret_code = <<<javascript
			<script type="text/javascript">

				var TDConf = TDConf || {};
				TDConf.Config = {
					productId: "{$param["sku"]}",
					category: "{$param["category"]}",
					brand: "{$param["brand"]}",
					productName: "{$param["product_name"]}",
					productDescription: "{$param["product_description"]}",
					price: "{$param["price"]}",
					currency: "{$param["currency"]}",
					url: "{$param["url"]}",
					imageUrl: "{$param["image_url"]}",
					containerTagId : "6213"
				};
			</script>
javascript;
		return $ret_code;
	}

	private function tradedoubler_basket_code($product_list)
	{
		// $param["id"] = "";
		// $param["price"] = "";
		// $param["currency"] = "";
		// $param["name"] = "";
		// $param["qty"] = "";
		// $param_list[] = $param;
		if($product_list)
		{
			foreach ($product_list as $key=>$value)
			{
				$json[] = $this->json_encode_no_quote($value);
			}
			$product_list = implode(",", $json);
		}
		else {$product_list = "";}

		$ret_code = <<<javascript
			<script type="text/javascript">

				var TDConf = TDConf || {};
				TDConf.Config = {
				  	products:[ $product_list
				  	],
					containerTagId : "6214"
				};
			</script>
javascript;
		return $ret_code;
	}

	#test url https://dev.valuebasket.fr/fr_FR/checkout/payment_result/1/133578?debug=1
	private function tradedoubler_payment_success_code($product_list, $param)
	{
		// product list js format:
		// {id: "[product-id1]", price:"[price1]", currency:"[currency1]", name:"[product-name1]", qty:"[quantity1]"},
		// {id: "[product-id2]", price:"[price2]", currency:"[currency2]", name:"[product-name1]", qty:"[quantity2]"},

		foreach ($product_list as $key=>$value)
		{
			$json[] = $this->json_encode_no_quote($value);
		}
		$product_list = implode(",", $json);

		$ret_code = <<<javascript
			<script type="text/javascript">

				var TDConf = TDConf || {};
				TDConf.Config = {
				  	products:[ $product_list
				  	],
				  	orderId: 	"{$param["order_id"]}",
				  	orderValue: "{$param["order_value"]}",
				  	currency: 	"{$param["currency"]}",
				  	containerTagId : "6215"
				};
			</script>
javascript;
		return $ret_code;
	}

}

