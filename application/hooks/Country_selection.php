<?php 

class Country_selection
{
	private $db;

	private function connect_database($params)
	{
		if (!is_resource($this->db))
		{
			$this->db = mysql_connect($params[0], $params[1], $params[2]);
			mysql_select_db($params[3], $this->db);
		}
	}

	private function get_country_id_by_ip2country_provider($db_params, $ip)
	{
			$this->connect_database($db_params);

			$sql = "SELECT c.value FROM config c WHERE c.variable = 'ip2country_provider'";
			$rs = mysql_query($sql, $this->db);

			if ($rs)
			{
				$row = mysql_fetch_row($rs);

				$sql = "SELECT hi.server FROM http_info hi WHERE hi.name = '" . mysql_real_escape_string($row[0]) . "'";
				$rs = mysql_query($sql, $this->db);

				if ($rs)
				{
					// $row = mysql_fetch_row($rs);
					// list(, $country_id) = explode('||', trim(file_get_contents($row[0].$ip)));
					$row = mysql_fetch_row($rs);
					$country_id = "";

					# file may not exist if called from directly from server; will cause php error
//					if(file_exists($row[0].$ip))
					{
						list(, $country_id) = explode('||', trim(file_get_contents($row[0].$ip)));
					}

					if ($country_id == '')
					{
						$country_id = 'FR';
					}
				}

				return $country_id;
			}

			return FALSE;
	}

	public function define_not_welcome_visitor($db_params, $access_country)
	{
		if ($access_country == '')
			$access_country = 'unknown';

		if (isset($_SESSION['NOT_WELCOME_VISITOR'][$access_country]))
		{
			if (! defined('NOT_WELCOME_VISITOR'))
			{
				DEFINE('NOT_WELCOME_VISITOR', $_SESSION['NOT_WELCOME_VISITOR'][$access_country]);
			}

			return TRUE;
		}

		$ip_white_list = array(array('ip'=>'219.76.178.234', 'smask'=>'255.255.255.255'),   // ESG Office
		                       array('ip'=>'61.238.236.234', 'smask'=>'255.255.255.255'),   // ESG Office + VOIP
		                       array('ip'=>'219.76.190.140', 'smask'=>'255.255.255.255'),   // purelygadgets.co.uk Exchange Server
		                       array('ip'=>'112.120.70.143', 'smask'=>'255.255.255.255')    // Warehouse
		                      );
		$ip_black_list = array(array('ip'=>'116.48.152.133', 'smask'=>'255.255.255.255'),   // Requested by Twinsen ?Unknown owner
		                       array('ip'=>'188.65.116.18',  'smask'=>'255.255.255.255'),   // Requested by Twinsen ?Unknown owner
		                       array('ip'=>'221.125.64.185', 'smask'=>'255.255.255.255'),   // Requested by Twinsen ?Unknown owner
		                       array('ip'=>'173.199.115.0',  'smask'=>'255.255.255.0'),     // https://ahrefs.com/
		                       array('ip'=>'173.199.116.0',  'smask'=>'255.255.255.0'),     // https://ahrefs.com/
		                       array('ip'=>'173.199.119.0',  'smask'=>'255.255.255.0'),     // https://ahrefs.com/
		                       array('ip'=>'218.189.179.38', 'smask'=>'255.255.255.255')    // Central District, HK Hutchison Global Communications
		                      );
		$not_welcome_visitor = FALSE;

		if ((stripos($_SERVER['HTTP_HOST'], 'admindev.') === FALSE) && (stripos($_SERVER['HTTP_HOST'], 'admincentre.') === FALSE))
		{
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

			if ($ip != '')
			{
				$found_in_black_list = FALSE;
				foreach ($ip_black_list as $arr)
				{
					$black_ip = $arr['ip'];
					$smask = $arr['smask'];
					if ((ip2long($black_ip) & ip2long($smask)) == (ip2long($ip) & ip2long($smask)))
					{
						$found_in_black_list = TRUE;
						$not_welcome_visitor = TRUE;
						break;
					}
				}

				if (!$found_in_black_list)
				{
					$found_in_white_list = FALSE;
					foreach ($ip_white_list as $arr)
					{
						$white_ip = $arr['ip'];
						$smask = $arr['smask'];
						if ((ip2long($white_ip) & ip2long($smask)) == (ip2long($ip) & ip2long($smask)))
						{
							$found_in_white_list = TRUE;
							break;
						}
					}

					if (!$found_in_white_list)
					{
						$ip_country = $this->get_country_id_by_ip2country_provider($db_params, $ip);

						if ($ip_country == 'HK')
						{
							if ($access_country != 'HK')
							{
								$not_welcome_visitor = TRUE;
							}
						}
					}
				}
			}
		}

		DEFINE('NOT_WELCOME_VISITOR', $not_welcome_visitor);
		$_SESSION['NOT_WELCOME_VISITOR'][$access_country] = $not_welcome_visitor;
		return TRUE;
	}

	public function get_correct_country_code($params)
	{
		if (strpos(strtolower($_SERVER['REQUEST_URI']), 'ss_data') !== FALSE)
			return;
		if (strpos(strtolower($_SERVER['REQUEST_URI']), 'checkout_redirect_method/payment_notification') !== FALSE)
			return;

		$tmp = strpos($_SERVER['HTTP_HOST'], 'valuebasket');
		DEFINE('DOMAIN', substr($_SERVER['HTTP_HOST'], ($tmp ? $tmp : 0)));

		$redirect_url = '';
		$country_id = '';

		// Check any country id in URL and rebuild the url
		$url_segment = $_SERVER['REQUEST_URI'];
		$domain = $this->_get_domain($_SERVER['HTTP_HOST']);

		if (($url_segment != '') && ($url_segment != '/'))
		{
			list(, $language_country_id) = explode('/', $url_segment);
			
			if(count(explode('_', $language_country_id)) == 3)
			{
				list(,$language_id, $country_id) = explode('_', $language_country_id);
				$modify = TRUE;
			}
			else
			{
				list($language_id, $country_id) = explode('_', $language_country_id);
				$modify = FALSE;
			}
// temp code
			if($modify)
			{
				$temp_country_arr = array('AU',
									'ES',
									'FI',
									'FR',
									'GB',
									'HK',
									'IE',
									'NZ',
									'US',
									'SG',
									'MY',
									'PH',
									'RU',
									'PT',
									'NL',
									'SE',
									'BE');
				if ($country_id)
				{
					if (in_array($country_id, $temp_country_arr))
					{
						$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on")? "https://" : "http://";
						if ((strripos($_SERVER['HTTP_HOST'], 'admindev') === FALSE) && (strripos($_SERVER['HTTP_HOST'], 'admincentre') === FALSE))
						{
							$redirect_url = $protocol . $this->rewrite_domain_by_country($_SERVER['HTTP_HOST'], $language_country_id) . str_ireplace($language_country_id , $language_id.'_' . $country_id, $_SERVER['REQUEST_URI']);
	//						str_ireplace('valuebasket.com' , 'valuebasket.com/en_' . strtoupper($language_country_id), $_SERVER['HTTP_HOST']) . $_SERVER['REQUEST_URI'];
	//						var_dump($redirect_url);
	//						exit;

							header("Location: ". $redirect_url);
							exit;
						}
					}
				}
			}
// end of temp code
			if ($this->_validate_country_input($country_id))
			{
				if (strtolower($_SERVER['HTTP_HOST']) != strtolower($this->rewrite_domain_by_country($_SERVER['HTTP_HOST'], $country_id)))
				{
					$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on")? "https://" : "http://";
					$redirect_url = $protocol . $this->rewrite_domain_by_country($_SERVER['HTTP_HOST'], $country_id) . $_SERVER['REQUEST_URI'];

					header("Location: ". $redirect_url);
					exit;
				}
			}
			else
			{
				$country_id = '';
			}

			if (($_SERVER['QUERY_STRING'] != "") && (isset($_GET["ctr"])))
			{
				if ($redirect_url == '')
				{
					$redirect_url = str_ireplace('?'.$_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']);
				}
				else
				{
					$redirect_url = str_ireplace('?'.$_SERVER['QUERY_STRING'], '', $redirect_url);
				}

				$remind_query_string = preg_replace("/ctr=".$_GET["ctr"]."&?/", '', $_SERVER['QUERY_STRING']);
				if ($remind_query_string != '')
				{
					$redirect_url .= '?' . $remind_query_string;
				}
			}

			if ($redirect_url != '')
			{
				$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on")? "https://" : "http://";
				$redirect_url = $protocol . $this->rewrite_domain_by_country($_SERVER['HTTP_HOST'], $country_id) . $redirect_url;
			}
		}
		else
		{
			// Preset language ID and country ID for country domain, then make system will not do redirection (SBF #2541)
			if (strtolower($domain) != 'valuebasket.com')
			{
				switch (strtolower($domain))
				{
					case 'valuebasket.com.au' : $language_id = 'en'; $country_id = 'AU'; break;
					case 'valuebasket.co.nz' : $language_id = 'en'; $country_id = 'NZ'; break;
					case 'valuebasket.com.sg' : $language_id = 'en'; $country_id = 'SG'; break;
					case 'valuebasket.fr' : $language_id = 'fr'; $country_id = 'FR'; break;
					case 'valuebasket.be' : $language_id = 'fr'; $country_id = 'BE'; break;
					case 'valuebasket.es' : $language_id = 'es'; $country_id = 'ES'; break;
                    case 'valuebasket.se' : $language_id = 'sv'; $country_id = 'SE'; break;
					case 'valuebasket.com.mx' : $language_id = 'es'; $country_id = 'MX'; break;
					case 'valuebasket.nl' : $language_id = 'nl'; $country_id = 'NL'; break;
					case 'valuebasket.ru' : $language_id = 'ru'; $country_id = 'RU'; break;
					case 'valuebasket.pt' : $language_id = 'es'; $country_id = 'PT'; break;
//					case 'valuebasket.it' : $language_id = 'it'; $country_id = 'IT'; break;
//					case 'valuebasket.com.ph' : $language_id = 'en'; $country_id = 'PH'; break;
				}
			}
		}
		$this->connect_database($params);

		// Rules to get country id
		$cur_country_id = '';

/*
		if (isset($_POST["custom_country_id"]))
		{
			$allow_redirect = 1;
			$cur_country_id = $_POST["custom_country_id"];
		}
		elseif (isset($_COOKIE["post_country_id"]))
		{
			$cur_country_id = $_COOKIE["post_country_id"];
		}
		elseif (isset($_COOKIE["custom_country_id"]))
		{
			$cur_country_id = $_COOKIE["custom_country_id"];
		}
*/
		if ($country_id != '')
		{
			$cur_country_id = $country_id;
		}
		elseif (isset($_POST["custom_country_id"]))
		{
			$cur_country_id = $_POST["custom_country_id"];
		}
		elseif (isset($_GET["ctr"]))
		{
			$cur_country_id = $_GET["ctr"];
		}
		elseif (isset($_COOKIE["custom_country_id"]))
		{
			$cur_country_id = $_COOKIE["custom_country_id"];
		}
		elseif (!isset($_SESSION['country_id_from_hook']))
		{
			$id = $this->get_country_id_by_ip2country_provider($params, $_SERVER["REMOTE_ADDR"]);

			if ($id !== FALSE)
			{
				$cur_country_id = $id;
				if (($cur_country_id != '') && ($cur_country_id != 'ZZ'))
				{
					$this->connect_database($params);
// customer first visit, get the language from database
					$sql = "select language_id from country where id='" . $cur_country_id . "' limit 1";
					$rs2 = mysql_query($sql, $this->db);
					if(mysql_num_rows($rs2)>0)
					{
						$db_language_id = mysql_result($rs2, 0);
					}
					else
					{
						$db_language_id = 'en';
					}
					
//					print $db_language_id;
//					exit;
				}
			}
		}

		if ($cur_country_id != '')
		{
			$sql = "SELECT c.id, c.standalone FROM country c WHERE c.status = 1 AND c.url_enable = 1 AND c.id = '" . mysql_real_escape_string($cur_country_id) . "'";

			$isStandAlone = 0;
			$rs = mysql_query($sql, $this->db);
			if (mysql_num_rows($rs) > 0)
			{
				$isStandAlone = mysql_result($rs, 0, "standalone");
				if($temp = $_SESSION['domain_platform']['platform_id'])
				{
					if($_SESSION["cart"][$temp])
					{
						$chk_cart = base64_encode(serialize($_SESSION["cart"][$temp]));
	//							setcookie("chk_cart", $chk_cart, time()+86400, "/",  ".".$domain);
						$this->_setcookie("chk_cart", $chk_cart);
						$base_url = $this->get_base_url();
						$back_url = urlencode($base_url.($_SERVER["QUERY_STRING"] ? "?".$_SERVER["QUERY_STRING"] : ""));
	//							setcookie("back_url", $back_url, time()+86400, "/",  ".".$domain);
						$this->_setcookie("back_url", $back_url);
					}
				}
			}
			mysql_free_result($rs);
			$_SESSION['country_id_from_hook'] = $cur_country_id;
			$_SESSION['is_standalone_country'] = $isStandAlone;

			$domain = $this->_get_domain($this->rewrite_domain_by_country($_SERVER['HTTP_HOST'], $cur_country_id));

			if ($_POST["save_custom_country_id"])
			{
//				setcookie("custom_country_id", $cur_country_id, time()+86400, "/", ".".$domain);
				$this->_setcookie("custom_country_id", $cur_country_id);
				$_COOKIE["custom_country_id"] = $cur_country_id;
			}
			else
			{
//				setcookie("custom_country_id", $cur_country_id, 0, "/",  ".".$domain);
				$this->_setcookie("custom_country_id", $cur_country_id);
				$_COOKIE["custom_country_id"] = $cur_country_id;
			}
		}

		if ($country_id == '')
		{
//there is no lang_country, e.g. fr_FR/en_FR pair in the link, redirect it
			if ((isset($_COOKIE["lang_id"])) && ($_COOKIE["lang_id"] != 'en'))
//			if (isset($_COOKIE["lang_id"]) || (!empty($db_language_id)))
			{
				if ($cur_country_id != '')
				{
/*
					if (isset($_COOKIE["lang_id"]))
						$direct_lang = $_COOKIE["lang_id"];
					else
						$direct_lang = $db_language_id;
*/
					$direct_lang = $_COOKIE["lang_id"];
					$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on")? "https://" : "http://";
					if ((strripos($_SERVER['HTTP_HOST'], 'admindev') === FALSE) && (strripos($_SERVER['HTTP_HOST'], 'admincentre') === FALSE))
					{
						//$redirect_url = $protocol . str_ireplace('valuebasket.com', 'valuebasket.com/' . strtolower($direct_lang) . "_" . strtoupper($cur_country_id), $_SERVER['HTTP_HOST']) . $_SERVER['REQUEST_URI'];
						$redirect_url = $protocol . $this->rewrite_domain_by_country($_SERVER['HTTP_HOST'], $cur_country_id) . '/' . strtolower($direct_lang) . "_" . strtoupper($cur_country_id) . $_SERVER['REQUEST_URI'];
					}
//					print $redirect_url;
				}
			}
		}
		if (is_resource($this->db))
		{
			mysql_close($this->db);
		}
//		print $_SERVER['REQUEST_URI'];
//		exit;
// Redirect to the correct URL if country id is in the URL
//escape redirection for payment gateway
// Mobile user agent detection, SBF #3024
		$escape = FALSE;
		$escape_detection_url = array('checkout_redirect_method/trustly_payment_notification', 'checkout_redirect_method/payment_response', 'checkout_redirect_method/payment_notification', 'checkout_onepage/response', 'checkout_redirect_method/payment_form', 'checkout_redirect_method/payment_redirect_form', 'paypal_ipn/index', 'ss_data');

		foreach ($escape_detection_url as $url)
		{
			if (strpos(strtolower($_SERVER['REQUEST_URI']), $url) !== FALSE)
			{
				$escape = TRUE;
			}
		}

		if (($redirect_url != "") && ($escape))
		{
			$redirect_url = "";
		}

		if ($redirect_url != '')
		{
			header("Location: ".$redirect_url);
			exit;
		}
		elseif ($cur_country_id != '')
		{
			if (strtolower($_SERVER['HTTP_HOST']) != strtolower($this->rewrite_domain_by_country($_SERVER['HTTP_HOST'], $cur_country_id)))
			{
				$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on")? "https://" : "http://";
				$redirect_url = $protocol . $this->rewrite_domain_by_country($_SERVER['HTTP_HOST'], $cur_country_id) . $_SERVER['REQUEST_URI'];

				header("Location: ". $redirect_url);
				exit;
			}
		}

		// Mobile user agent detection, SBF #3024

		if ($escape === FALSE)
		{
			$http_host = strtolower($_SERVER['HTTP_HOST']);
			if (strpos($http_host, array('admincentre.', 'admindev.')) === FALSE)
			{
				$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on")? "https://" : "http://";
				$show_mobile_site = FALSE;

				$_SESSION['user_agent'] = '';

				if (preg_match('/(alcatel|amoi|android|avantgo|blackberry|benq|cell|cricket|docomo|elaine|htc|iemobile|iphone|ipaq|ipod|j2me|java|midp|mini|mmp|mobi|motorola|nec-|nokia|palm|panasonic|philips|phone|playbook|sagem|sharp|sie-|silk|smartphone|sony|symbian|t-mobile|telus|up\.browser|up\.link|vodafone|wap|webos|wireless|xda|xoom|zte)/i', $_SERVER['HTTP_USER_AGENT']))
				{
					// Exclude list
					// Some cases that iPad still fall into mobile user agent, so we need to double scan and ensure we assigned mobile user agent to correct device
					// Here is the example from one of the iPad user, "mobi" is matched, HTTP_USER_AGENT --> Mozilla/5.0 (iPad; CPU OS 7_0_4 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) GSA/3.2.0.25255 Mobile/11B554a Safari/8536.25
                    if (preg_match('/(ipad)/i', $_SERVER['HTTP_USER_AGENT']) === 0)
					{
						$show_mobile_site = TRUE;
						$_SESSION['user_agent'] = 'mobile';
					}
				}

				if (isset($_SESSION['site_mode']))
				{
					if (strtolower($_SESSION['site_mode']) == 'mobile')
					{
						$show_mobile_site = TRUE;
					}

					if (strtolower($_SESSION['site_mode']) == 'full')
					{
						$show_mobile_site = FALSE;
					}

				}
				elseif (isset($_COOKIE['site_mode']))
				{
					if (strtolower($_COOKIE['site_mode']) == 'mobile')
					{
						$show_mobile_site = TRUE;
					}

					if (strtolower($_COOKIE['site_mode']) == 'full')
					{
						$show_mobile_site = FALSE;
					}

					$_SESSION['init_site_mode_session'] = TRUE;
				}

				if (isset($_GET['site_mode']))
				{
					if (strtolower($_GET['site_mode']) == 'mobile')
					{
						$show_mobile_site = TRUE;
					}

					if (strtolower($_GET['site_mode']) == 'full')
					{
						$show_mobile_site = FALSE;
					}

					$_SESSION['init_site_mode_session'] = TRUE;
				}

				if ((strstr(strtolower($_SERVER['HTTP_USER_AGENT']), "googlebot") !== FALSE)
					|| (isset($_GET["d"]) && ($_GET["d"] == 1)))
				{
					$show_mobile_site = FALSE;
				}
//temp changes to disable mobile site
				//$show_mobile_site = FALSE;
				if ($show_mobile_site)
				{
					$_SESSION['user_agent'] = 'mobile';  // Assume user is using mobile device no matter it is forced to show mobile site or not

				}
//temp code to direct mobile drone to desktop
				if (strpos(strtolower($_SERVER['REQUEST_URI']), 'promotions/drone') !== FALSE)
				{
					$show_mobile_site = FALSE;
					$_SESSION['user_agent'] = '';
					$_COOKIE['site_mode'] = 'full';
				}
				if (strpos($http_host, 'dev.') === FALSE)
				{
					if (($show_mobile_site) && (preg_match('/^m\./', $http_host) == FALSE))  // no matter not found or error
					{
						$this->_setcookie('site_mode', 'mobile', 2592000);
						$redirect_url = $protocol . 'm.valuebasket.com' . $_SERVER['REQUEST_URI'];

						header("Location: ". $redirect_url);
						exit;
					}
					elseif ((!$show_mobile_site) && (preg_match('/^www\./', $http_host) == FALSE))  // no matter not found or error
					{
						$this->_setcookie('site_mode', 'full', 2592000);
						$redirect_url = $protocol . 'www.' . $this->_get_domain($_SERVER['HTTP_HOST']) . $_SERVER['REQUEST_URI'];

						header("Location: ". $redirect_url);
						exit;
					}
				}
				else
				{
					if (($show_mobile_site) && (preg_match('/^mdev\./', $http_host) == FALSE))  // no matter not found or error
					{
						$this->_setcookie('site_mode', 'mobile', 2592000);
						$redirect_url = $protocol . 'mdev.valuebasket.com' . $_SERVER['REQUEST_URI'];

						header("Location: ". $redirect_url);
						exit;
					}
					elseif ((!$show_mobile_site) && (preg_match('/^dev\./', $http_host) == FALSE))  // no matter not found or error
					{
						$this->_setcookie('site_mode', 'full', 2592000);
						$redirect_url = $protocol . 'dev.' . $this->_get_domain($_SERVER['HTTP_HOST']) . $_SERVER['REQUEST_URI'];

						header("Location: ". $redirect_url);
						exit;
					}
				}

				if ($show_mobile_site)
				{
					$_SESSION['site_mode'] = 'mobile';
					$this->_setcookie('site_mode', 'mobile', 2592000);
				}
				else
				{
					$_SESSION['site_mode'] = 'full';
					$this->_setcookie('site_mode', 'full', 2592000);
				}
			}
		}
		// End of mobile user agent detection, SBF #3024

		//$this->define_not_welcome_visitor($params, ($country_id == '' ? ($cur_country_id == '' ? $_SESSION['country_id_from_hook'] : $cur_country_id) : $country_id));  // should we welcome the visitor?
	}

	public function get_base_url()
	{
		$base_url = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
		$base_url .= "://".$_SERVER['HTTP_HOST'];
		$base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);

		return $base_url;
	}
	
	public function _validate_country_input($countryId)
	{
		if (!preg_match("/^[A-Za-z]{2}$/", $countryId))
			return false;
		else
			return $countryId;
	}

	public function _filter_country_input($countryId)
	{
/*
		$text = $this->_common_filter($countryId);
		return ($text == "") ? "en" : $text;
*/
	}

	public function _common_filter($input)
	{
/*
		if (preg_match("/[A-z]{2}$/", $input))
			return $input;
		else
			return "";
*/
	}	

	public static function rewrite_domain_by_country($domain, $country_id = '', $force_sub_domain = FALSE)
	{
		if ($force_sub_domain)
		{
			$tmp = strpos($domain, 'valuebasket');
			if (($tmp === FALSE) || ($tmp == 0))
			{
				$sub_domain = '';
			}
			else
			{
				$sub_domain = substr($domain, 0, $tmp);
			}
		}
		else
		{
			if (stripos($domain, 'dev.') === FALSE)
			{
				if (stripos($domain, 'admincentre.') !== FALSE)
				{
					$sub_domain = 'admincentre.';
				}
				else
				{
					if (defined('ENTRYPOINT'))
					{
						if (ENTRYPOINT == 'MOBILE')
						{
							$sub_domain = 'm.';
							return $sub_domain . 'valuebasket.com';
						}
						else
						{
							$sub_domain = 'www.';
						}
					}
					else
					{
						$sub_domain = 'www.';
					}
				}
			}
			else
			{
				if (stripos($domain, 'admindev.') !== FALSE)
				{
					$sub_domain = 'admindev.';
				}
				else
				{
					if (defined('ENTRYPOINT'))
					{
						if (ENTRYPOINT == 'MOBILE')
						{
							$sub_domain = 'mdev.';
							return $sub_domain . 'valuebasket.com';
						}
						else
						{
							$sub_domain = 'dev.';
						}
					}
					else
					{
						$sub_domain = 'dev.';
					}
				}
			}
		}

		$country_id = strtoupper($country_id);
		if ($country_id == 'AU')
		{
			$domain = $sub_domain . 'valuebasket.com.au';
		}
		elseif ($country_id == 'NZ')
		{
			$domain = $sub_domain . 'valuebasket.co.nz';
		}
		elseif ($country_id == 'SG')
		{
			$domain = $sub_domain . 'valuebasket.com.sg';
		}
		elseif ($country_id == 'FR')
		{
			$domain = $sub_domain . 'valuebasket.fr';
		}
		elseif ($country_id == 'ES')
		{
			$domain = $sub_domain . 'valuebasket.es';
		}
		elseif ($country_id == 'MX')
		{
			$domain = $sub_domain . 'valuebasket.com.mx';
		}
		elseif ($country_id == 'NL')
		{
			$domain = $sub_domain . 'valuebasket.nl';
		}
/*
		elseif ($country_id == 'PH')
		{
			$domain = $sub_domain . 'valuebasket.com.ph';
		}
*/
		elseif ($country_id == 'BE')
		{
			$domain = $sub_domain . 'valuebasket.be';
		}
/*
		elseif ($country_id == 'IT')
		{
			$domain = $sub_domain . 'valuebasket.it';
		}
*/
		elseif ($country_id == 'RU')
		{
			$domain = $sub_domain . 'valuebasket.ru';
		}
		elseif ($country_id == 'PT')
		{
			$domain = $sub_domain . 'valuebasket.pt';
		}
		elseif ($country_id == 'PL')
		{
			$domain = $sub_domain . 'valuebasket.pl';
		}
		elseif ($country_id == 'SE')
		{
			$domain = $sub_domain . 'valuebasket.se';
		}
		else
		{
			if (strtolower(substr($domain, -15)) != 'valuebasket.com')
			{
				$domain = $sub_domain . 'valuebasket.com';
			}
		}

		return $domain;
	}

	public static function rewrite_site_name($domain)
	{
		$domain_arr = explode(".", strtolower($domain));
		$final_array = $domain_arr;
		if ($domain_arr[1] == "valuebasket")
		{
			array_shift($final_array);
		}

		return str_replace("valuebasket", "ValueBasket", implode(".", $final_array));
	}

	public static function get_template_require_text($platform_lang_id, $platform_country_id)
	{
		$replace = array();
		$replace["default_url"] = DOMAIN;
		$replace["lang_country_pair"] = $platform_lang_id . "_" . strtoupper($platform_country_id);
		$replace["site_url"] = Country_selection::rewrite_domain_by_country(SITE_URL, $platform_country_id);
		$replace["site_name"] = Country_selection::rewrite_site_name($replace["site_url"]);
		return $replace;
	}

	private function _get_domain($domain)
	{
		$tmp = strpos($domain, 'valuebasket');
		return substr($domain, ($tmp ? $tmp : 0));
	}

	public function set_cart_cookie($value)
	{
		$this->_setcookie("chk_cart", $value);
	}

	private function _setcookie($cookie_name, $value, $alive=86400)
	{
		setcookie($cookie_name, $value, time()+$alive, "/",  ".valuebasket.com");
		setcookie($cookie_name, $value, time()+$alive, "/",  ".valuebasket.com.au");
		setcookie($cookie_name, $value, time()+$alive, "/",  ".valuebasket.co.nz");
		setcookie($cookie_name, $value, time()+$alive, "/",  ".valuebasket.com.sg");
		setcookie($cookie_name, $value, time()+$alive, "/",  ".valuebasket.fr");
		setcookie($cookie_name, $value, time()+$alive, "/",  ".valuebasket.es");
        setcookie($cookie_name, $value, time()+$alive, "/",  ".valuebasket.se");
		setcookie($cookie_name, $value, time()+$alive, "/",  ".valuebasket.com.mx");
		setcookie($cookie_name, $value, time()+$alive, "/",  ".valuebasket.nl");
		setcookie($cookie_name, $value, time()+$alive, "/",  ".valuebasket.ru");
		setcookie($cookie_name, $value, time()+$alive, "/",  ".valuebasket.pt");
//		setcookie($cookie_name, $value, time()+$alive, "/",  ".valuebasket.com.ph");
		setcookie($cookie_name, $value, time()+$alive, "/",  ".valuebasket.be");
//		setcookie($cookie_name, $value, time()+$alive, "/",  ".valuebasket.it");
		setcookie($cookie_name, $value, time()+$alive, "/",  ".valuebasket.pl");
	}
}