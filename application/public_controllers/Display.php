<?php
defined('BASEPATH') OR exit('No direct script access allowed');

DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);

class Display extends PUB_Controller
{
	public function Display()
	{
		parent::PUB_Controller();
		$this->load->library('template');
		$this->load->helper(array('url','directory','datetime','tbswrapper'));
		$this->load->model("website/home_model");
		$this->load->library('service/affiliate_service');
		$this->load->library('service/ip2country_service');
		$this->load->library('service/deliverytime_service');
	}

	private function _is_special_promotion($page)
	{
		if (($page != "audio-visual")
			&& ($page != "drone"))
			return false;
		else
			return true;
	}

	public function promotions($page = '')
	{
		if (!$this->_is_special_promotion($page))
		{
			show_404();
		}
		if ($this->_is_special_promotion($page))
		{
			$this->template->add_js('/resources/js/jquery.cookie.js');
			$this->template->add_js('/resources/js/tree.jquery.js');
			$this->template->add_js('/resources/js/jquery.nivo.slider.js');
			$this->template->add_css('resources/css/jqtree.css');
			$this->template->add_css('resources/css/slider-theme/default.css');
			$this->template->add_css('resources/css/nivo-slider.css');
		}

		$data["page"] = $page;
		if ($page == "drone")
			$data["page"] = $page . "_" . strtolower(PLATFORMCOUNTRYID);
		$this->load_tpl('content', 'tbs_promotions', $data, TRUE);
	}

	public function view($page = '')
	{
		{ # SBF#3114
			$ip = '';
			if ($_SERVER['REMOTE_ADDR'] AND $_SERVER['HTTP_CLIENT_IP'])
			{
				$ip = $_SERVER['HTTP_CLIENT_IP'];
			}
			elseif ($_SERVER['REMOTE_ADDR'])
			{
				$ip = $_SERVER['REMOTE_ADDR'];
			}
			elseif ($_SERVER['HTTP_CLIENT_IP'])
			{
				$ip = $_SERVER['HTTP_CLIENT_IP'];
			}
			elseif ($_SERVER['HTTP_X_FORWARDED_FOR'])
			{
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			}

			$ret = $this->ip2country_service->get_info_by_ip($ip);

			$data["actual_country_id"] = "";
			if ($ret !== false)
				$data["actual_country_id"] = $ret["country_id"];
		}

		if(empty($page))
		{
			show_404();
		}

		$this->_init_meta_content($page);
		$this->template->add_link("rel='canonical' href='".base_url()."/display/view/$page'");	# SEO

		#SBF #2441
		if ($page == 'bulk_sales')
		{
			$bulk_sales_country = 'AU|BE|FI|FR|GB|HK|IE|MY|NZ|PH|SG|ES|US|MT|CH|';
			if (strpos($bulk_sales_country, PLATFORMCOUNTRYID) === FALSE)
			{
				redirect(base_url());
			}
		}

		// echo '<pre>Dumping $_POST<br>'; var_dump($_POST); echo "</pre>";
		#var_dump(PLATFORMCOUNTRYID);

		if ($page == "newsletter_thank_you")
		{
			if (isset($_POST['subscribe-email']))
			{
				$email = urlencode($_POST['subscribe-email']);

				$currency = PLATFORMCURR;
				$url = "";
				switch (PLATFORMCOUNTRYID)
				{
					case "ES": # SBF#2119
						$url = "http://p6trc.emv2.com/D2UTF8?emv_tag=1651E8080005CD12&emv_ref=EdX7CqkmjTao8SA9MOPvpMvWLkl7aaXD8jjde6xFLMHbKxw&EMAIL_FIELD=$email&SOURCE_FIELD=WEBFORM&LANGUAGE_ID_FIELD=ES&CURRENCY_FIELD=EUR";
						break;

					case "FR":	# SBF#1896
						$url = "http://p6trc.emv2.com/D2UTF8?emv_tag=C8E78020000F53B6&emv_ref=EdX7CqkmjTKa8SA9MOPvpMukIDl9FK3B-jjde98zW7LfK9w&EMAIL_FIELD=$email&SOURCE_FIELD=WEBFORM&LANGUAGE_ID_FIELD=FR&CURRENCY_FIELD=EUR";
						break;

					case "IT": # SBF#2871
						$url = "http://p6trc.emv2.com/D2UTF8?emv_tag=4AA1EE2BA080804A&emv_ref=EdX7CqkmjSAq8SA9MOPvpMvTWT17adjBiEndc6k-WMSoKFo&EMAIL_FIELD=$email&SOURCE_FIELD=WEBFORM&LANGUAGE_ID_FIELD=IT&CURRENCY_FIELD=EUR";
						break;

					case "PH":	# SBF#2454
						$url = "http://p6trc.emv2.com/D2UTF8?emv_tag=1F8080007E045FAB&emv_ref=EdX7CqkmjT1R8SA9MOPvpMvWXkR6FK3D-j-oe60zLrGrK7w&EMAIL_FIELD=$email&SOURCE_FIELD=WEBFORM&LANGUAGE_ID_FIELD=EN&COUNTRY_ID_FIELD=PH&CURRENCY_FIELD=PHP";
						break;

					case "SG":	# SBF#2882
						$url = "http://p6trc.emv2.com/D2UTF8?emv_tag=7F64C97504047F64&emv_ref=EdX7CqkmjSA68SA9MOPvpMvQXkp-b6TE_zjZe60xLsbdKEc&EMAIL_FIELD=$email&SOURCE_FIELD=WEBFORM&LANGUAGE_ID_FIELD=EN&COUNTRY_ID_FIELD=SG&CURRENCY_FIELD=SGD";
						break;

					case "AU":	# SBF#4744
						$url = "http://p6trc.emv2.com/D2UTF8?emv_tag=3AE81585D2004003&emv_ref=EdX7CqkmjS_R8SA9MOPvpMvUWTlyHajL_0zfe6kyWMDaKy4&EMAIL_FIELD=$email&SOURCE_FIELD=WEBFORM&LANGUAGE_ID_FIELD=EN&CURRENCY_FIELD=AUD&COUNTRY_ID_FIELD=AU";
						break;

					case "RU":	# SBF#3627
						$url = "http://p6trc.emv2.com/D2UTF8?emv_tag=135E3505C8802000&emv_ref=EdX7CqkmjSfd8SA9MOPvpMvWK0kPH6jD_0vVc6k0WMDZK3M&EMAIL_FIELD=$email&SOURCE_FIELD=WEBFORM&LANGUAGE_ID_FIELD=RU&CURRENCY_FIELD=RUB";
						break;

					case "NZ":
						$url = "http://p6trc.emv2.com/D2UTF8?emv_tag=100004C6A4DBB440&emv_ref=EdX7CqkmjSad8SA9MOPvpMvWKEx6HKmw_EnZD9tEXMTZK-8&EMAIL_FIELD=$email&SOURCE_FIELD=WEBFORM&LANGUAGE_ID_FIELD=EN&CURRENCY_FIELD=NZD";
						break;

					case "MY":
						$url = "http://p6trc.emv2.com/D2UTF8?emv_tag=20080005E7F27DDA&emv_ref=EdX7CqkmjSac8SA9MOPvpMvVKExyHK3D_03aDasxLLSoK_4&EMAIL_FIELD=$email&SOURCE_FIELD=WEBFORM&LANGUAGE_ID_FIELD=EN&CURRENCY_FIELD=MYR";
						break;

					case "PL":
						$url = "http://p6trc.emv2.com/D2UTF8?emv_tag=8583C6A048010858&emv_ref=EdX7CqkmjS6x8SA9MOPvpMvfLUR5b6uy-jzVe6g2UMXRK8o&EMAIL_FIELD=$email&SOURCE_FIELD=WEBFORM&LANGUAGE_ID_FIELD=PL&CURRENCY_FIELD=PLN";
						break;

					case "US":
						$url = "http://p6trc.emv2.com/D2UTF8?emv_tag=BD772C9602000028&emv_ref=EdX7CqkmjRWR8SA9MOPvpMulXEt9Ht7K_Djfe6k2WMLRK-U&EMAIL_FIELD=$email&SOURCE_FIELD=WEBFORM&LANGUAGE_ID_FIELD=US&CURRENCY_FIELD=USD";
						break;

					default:	# SBF#1740
						$url = "http://p6trc.emv2.com/D2UTF8?emv_tag=49DFB38B9D808004&emv_ref=EdX7CqkmjTPl8SA9MOPvpMvTITgMbq7LiDGpc6k-WMDdKwg&EMAIL_FIELD=$email&SOURCE_FIELD=WEBFORM&LANGUAGE_ID_FIELD=EN&CURRENCY_FIELD=$currency";
						break;
				}

				$use_curl = true;
				if ($use_curl)
				{
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
					curl_exec($ch);
					curl_close($ch);
				}
				else
					file_get_contents($url);

// echo '<pre>Dumping EV URL<br>'; var_dump($url); echo "</pre>";
				// var_dump($url);
#				header("HTTP/1.1 301 Moved Permanently");
#				header("Location: /display/view/$page");
#				die();
			}
		}
		elseif ($page == "bulk_sales")
		{
			$this->template->add_js('/js/checkform.js');
		}

		#SBF #4020 - get time frames for default scenario
		$data["ship_days"] = $data["del_days"] = "";
		if($delivery_obj = $this->deliverytime_service->get_deliverytime_obj(PLATFORMCOUNTRYID, 1))
		{
			$data["ship_days"] = $delivery_obj->get_ship_min_day()." - ".$delivery_obj->get_ship_max_day();
			$data["del_days"] = $delivery_obj->get_del_min_day()." - ".$delivery_obj->get_del_max_day();
		}

		// if cannot get delivery obj info, go back to original hard coded days
		$data["lang_id"] = $lang_id = $_SESSION["lang_id"];
		if(!$data["ship_days"])
		{
			switch ($lang_id)
			{
				case 'en':
				case 'fr':
				case 'it':
					$data["ship_days"] = "2 - 4";
					break;

				case 'es':
					$data["ship_days"] = "3 - 6";
					break;

				case 'ru':
					$data["ship_days"] = "3 - 5";
					break;

				default:
					$data["ship_days"] = "2 - 4";
					break;
			}
		}
		if(!$data["del_days"])
		{
			switch ($lang_id)
			{
				case 'en':
				case 'es':
				case 'fr':
				case 'it':
					$data["del_days"] = "6 - 9";
					break;

				case 'ru':
					$data["del_days"] = "6 - 26";
					break;

				default:
					$data["del_days"] = "6 - 9";
					break;
			}
		}

		#4039
		$data['platform_id'] = PLATFORMCOUNTRYID;
		$this->load_tpl('content', 'tbs_' . $page, $data, TRUE);
	}

	private function _init_meta_content($page)
	{
		$data['data']['lang_text'] = $this->_get_language_file();

		switch($page)
		{
			case 'newsletter_thank_you':
				$meta_title = $data['data']['lang_text']['meta_title_newsletter'].' | ValueBasket';
				#$meta_desc = $data['data']['lang_text']['meta_description_default'];
				#$meta_keyword = $data['data']['lang_text']['meta_keyword_shipping'];
				break;
			case 'shipping':
				$meta_title = $data['data']['lang_text']['meta_title_shipping'].' | ValueBasket';
				$meta_desc = $data['data']['lang_text']['meta_description_shipping'];
				$meta_keyword = $data['data']['lang_text']['meta_keyword_shipping'];
				break;
			case 'about_us':
				$meta_title = $data['data']['lang_text']['meta_title_about_us'].' | ValueBasket';
				$meta_desc = $data['data']['lang_text']['meta_description_about_us'];
				$meta_keyword = $data['data']['lang_text']['meta_keyword_about_us'];
				break;
			case 'conditions_of_use':
				$meta_title = $data['data']['lang_text']['meta_title_condition'].' | ValueBasket';
				$meta_desc = $data['data']['lang_text']['meta_description_condition'];
				$meta_keyword = $data['data']['lang_text']['meta_keyword_condition'];
				break;
			case 'privacy_policy':
				$meta_title = $data['data']['lang_text']['meta_title_privacy'].' | ValueBasket';
				$meta_desc = $data['data']['lang_text']['meta_description_privacy'];
				$meta_keyword = $data['data']['lang_text']['meta_keyword_privacy'];
				break;
			case 'faq':
				$meta_title = $data['data']['lang_text']['meta_title_faq'].' | ValueBasket';
				$meta_desc = $data['data']['lang_text']['meta_description_faq'];
				$meta_keyword = $data['data']['lang_text']['meta_keyword_faq'];
				break;
			default:
				return false;
		}
		$this->template->add_title($meta_title);
		$this->template->add_meta(array('name'=>'description','content'=>$meta_desc));
		$this->template->add_meta(array('name'=>'keywords','content'=>$meta_keyword));
	}
}
?>
