<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Data_feed_service.php";

class Tradedoubler_product_feed_service extends Data_feed_service
{
	protected $id = "Tradedoubler Product Feed";
	private $prod_srv;
	private $price_srv;

	public function __construct()
	{
		parent::Data_feed_service();
		include_once(APPPATH . 'libraries/service/Price_service.php');
		$this->set_price_srv(New Price_service());
		$this->set_output_delimiter(",");
	}

	public function get_price_srv()
	{
		return $this->price_srv;
	}

	public function set_price_srv(Base_service $srv)
	{
		$this->price_srv = $srv;
	}


	public function gen_data_feed($country, $explain_sku = "")
	{
		define('DATAPATH', $this->get_config_srv()->value_of("data_path"));//DATAPATH = '/var/data/valuebasket.com';
		$data_feed = $this->get_data_feed(TRUE, $country, $explain_sku, $override);

		$filename = 'valuebasket_tradedoubler_' . "$country" . '.csv';
		if($data_feed)
		{
			$filename = 'valuebasket_tradedoubler_' . "$country" . '.csv';
			$remotefilename = strtolower('/valuebasket_tradedoubler_' . "$country" . '.csv');
			$fp = fopen(DATAPATH . "feeds/tradedoubler/$country/" . $filename, 'w');
			if(fwrite($fp, $data_feed))
			{
				switch ($country)
				{
					// Tradedouble logins to our server
					// As such when adding new countries,
					// please remember to update the ftp_info with the new affiliate code + country
					//
					case "IT":	$this->ftp_feeds(DATAPATH . "feeds/tradedoubler/{$country}/" . $filename, $remotefilename, $this->get_ftp_name() . "_$country");break;#SBF#3712

					case "FR":$this->ftp_feeds(DATAPATH . "feeds/tradedoubler/{$country}/" . $filename, $remotefilename, $this->get_ftp_name() . "_$country");break;
					case "ES":
						$this->ftp_feeds(DATAPATH . "feeds/tradedoubler/{$country}/" . $filename, $remotefilename, $this->get_ftp_name() . "_$country");
						break;
					case "GB":
						$this->ftp_feeds(DATAPATH . "feeds/tradedoubler/{$country}/" . $filename, $remotefilename, $this->get_ftp_name() . "_$country");
						break;
					case "PL":
						$this->ftp_feeds(DATAPATH . "feeds/tradedoubler/{$country}/" . $filename, $remotefilename, $this->get_ftp_name() . "_$country");
						break;
					default:
						break;
				}
				// if($country == "FR")
				// {
				// 	$this->ftp_feeds(DATAPATH . 'feeds/tradedoubler/FR/' . $filename, $remotefilename, $this->get_ftp_name() . "_$country");
				// }
				if ($explain_sku == "")
				{
					header("Content-type: text/csv;charset=utf-8");
					header("Cache-Control: no-store, no-cache");
					header("Content-Disposition: attachment; filename=\"$filename\"");
					echo $data_feed;
				}
			}
			else
			{
				$subject = "<DO NOT REPLY> Fails to create Tradedoubler Product Feed File";
				$message ="FILE: ".__FILE__."<br>
							 LINE: ".__LINE__;
				$this->error_handler($subject, $message);
			}
		}
	}

	protected function get_data_list_w_country($where = array(), $option = array(), $country)
	{
		return $this->get_prod_srv()->get_tradedoubler_product_feed_dto(array(), array('limit'=>-1), $country);
	}

	public function get_data_feed($first_line_headling = TRUE, $country = "FR", $explain_sku)
	{
		// common processing to be done here
		include_once(APPPATH . 'libraries/service/Affiliate_sku_platform_service.php');
		$this->affiliate_sku_platform_service = New Affiliate_sku_platform_service();
		$affiliate_id = $this->get_affiliate_id_prefix() . $country;
		$override = $this->affiliate_sku_platform_service->get_sku_feed_list($affiliate_id);
		switch ($country)
		{
			// country specific processing to be done here
			case "IT":	#SBF#3712

			case "FR":return $this->get_data_feed_margin_group_fr($first_line_headling, $country, $explain_sku, $override); break;
			case "ES": return $this->get_data_feed_margin_group_a($first_line_headling, $country, $explain_sku, $override); break;
			case "GB": return $this->get_data_feed_margin_group_uk($first_line_headling, $country, $explain_sku, $override); break;
			case "PL": return $this->get_data_feed_margin_group_pl($first_line_headling, $country, $explain_sku, $override); break;

		}
	}

	private function get_data_feed_margin_group_uk($first_line_headling = TRUE, $country, $explain_sku, $override = null)
	{
		$list = $this->get_data_list_w_country(array(), array(), $country);
		if (!$list)
		{
			return;
		}

		$new_list = array();
		foreach ($list as $row)
		{
			$this->get_price_srv()->calculate_profit($row);

			if($res = $this->process_data_row($row))
			{
				$add = false;
				$selected = " --> NOT ADDED TO FINAL OUTPUT";
				if(($res->get_price() >=100) && ($res->get_margin() >= 7) )
				{
					$add = true;
					$selected = "passed margin rules, so added";
				}

				if ($override != null)
				{
					switch($override[$row->get_platform_id()][$row->get_sku()])
					{
						case 1: # exclude
							$add = false;
							$selected = "always exclude";
							break;

						case 2: # include
							$add = true;
							$selected = "always include";
							break;
					}
				}

				if ($add)
				{
					$new_list[] = $res;
				}

				if ($explain_sku != "")
				{
					if (strtoupper($res->get_sku()) == strtoupper($explain_sku))
					{
						var_dump(strtoupper($explain_sku) . $selected);
						echo "<pre>";
						var_dump($res);
					}
				}
			}
		}
		$content = $this->convert($new_list, $first_line_headling);
		return $content;
	}

	private function get_data_feed_margin_group_fr($first_line_headling = TRUE, $country, $explain_sku, $override = null)
	{
		$list = $this->get_data_list_w_country(array(), array(), $country);
		if (!$list)
		{
			return;
		}

		$new_list = array();
		foreach ($list as $row)
		{
			$this->get_price_srv()->calculate_profit($row);

			if($res = $this->process_data_row($row))
			{
				$add = false;
				$selected = " --> NOT ADDED TO FINAL OUTPUT";
				if(($res->get_price() >=100) && ($res->get_margin() >= 7) )
				{
					$add = true;
					$selected = "passed margin rules, so added";
				}

				if ($override != null)
				{
					switch($override[$row->get_platform_id()][$row->get_sku()])
					{
						case 1: # exclude
							$add = false;
							$selected = "always exclude";
							break;

						case 2: # include
							$add = true;
							$selected = "always include";
							break;
					}
				}

				if ($add)
				{
					$new_list[] = $res;
				}

				if ($explain_sku != "")
				{
					if (strtoupper($res->get_sku()) == strtoupper($explain_sku))
					{
						var_dump(strtoupper($explain_sku) . $selected);
						echo "<pre>";
						var_dump($res);
					}
				}
			}
		}
		$content = $this->convert($new_list, $first_line_headling);
		return $content;
	}
	private function get_data_feed_margin_group_pl($first_line_headling = TRUE, $country, $explain_sku, $override = null)
	{
		$list = $this->get_data_list_w_country(array(), array(), $country);
		if (!$list)
		{
			return;
		}

		$new_list = array();
		foreach ($list as $row)
		{
			$this->get_price_srv()->calculate_profit($row);

			if($res = $this->process_data_row($row))
			{
				$add = false;
				$selected = " --> NOT ADDED TO FINAL OUTPUT";
				if(($res->get_price() >=400) && ($res->get_margin() >= 7) )
				{
					$add = true;
					$selected = "passed margin rules, so added";
				}

				if ($override != null)
				{
					switch($override[$row->get_platform_id()][$row->get_sku()])
					{
						case 1: # exclude
							$add = false;
							$selected = "always exclude";
							break;

						case 2: # include
							$add = true;
							$selected = "always include";
							break;
					}
				}

				if ($add)
				{
					$new_list[] = $res;
				}

				if ($explain_sku != "")
				{
					if (strtoupper($res->get_sku()) == strtoupper($explain_sku))
					{
						var_dump(strtoupper($explain_sku) . $selected);
						echo "<pre>";
						var_dump($res);
					}
				}
			}
		}
		$content = $this->convert($new_list, $first_line_headling);
		return $content;
	}
	private function get_data_feed_margin_group_a($first_line_headling = TRUE, $country, $explain_sku, $override = null)
	{
		$list = $this->get_data_list_w_country(array(), array(), $country);
		if (!$list)
		{
			return;
		}

		$new_list = array();
		foreach ($list as $row)
		{
			$this->get_price_srv()->calculate_profit($row);

			if($res = $this->process_data_row($row))
			{
				$add = false;
				$selected = " --> NOT ADDED TO FINAL OUTPUT";
				if ((($res->get_price() >= 20) && ($res->get_price() < 200) && ($res->get_margin() >= 8)) ||
					(($res->get_price() >= 200) && ($res->get_price() < 400) && ($res->get_margin() >= 8)) ||
					(($res->get_price() >= 400) && ($res->get_price() < 800) && ($res->get_margin() >= 8)) ||
					(($res->get_price() >= 800) && ($res->get_price() < 1200) && ($res->get_margin() >= 7)) ||
					(($res->get_price() >= 1200) && ($res->get_margin() >= 6)))
				{
					$add = true;
					$selected = "passed margin rules, so added";
				}

				if ($override != null)
				{
					switch($override[$row->get_platform_id()][$row->get_sku()])
					{
						case 1: # exclude
							$add = false;
							$selected = "always exclude";
							break;

						case 2: # include
							$add = true;
							$selected = "always include";
							break;
					}
				}

				if ($add)
				{
					$new_list[] = $res;
				}

				if ($explain_sku != "")
				{
					if (strtoupper($res->get_sku()) == strtoupper($explain_sku))
					{
						var_dump(strtoupper($explain_sku) . $selected);
						echo "<pre>";
						var_dump($res);
					}
				}
			}
		}
		// set_time_limit(300);	// make sure we don't timeout for another 5 minutes

		$content = $this->convert($new_list, $first_line_headling);
		return $content;
	}

	protected function get_data_list($where = array(), $option = array())
	{
		return '';
	}

	protected function get_default_vo2xml_mapping()
	{
		return '';
	}

	protected function get_default_xml2csv_mapping()
	{
		return APPPATH . 'data/tradedoubler_product_feed_xml2csv.txt';
	}

	public function get_contact_email()
	{
		return 'itsupport@eservicesgroup.net';
	}

	protected function get_ftp_name()
	{
		return 'TRADEDOUBLER';
	}

	protected function get_sj_id()
	{
		return "TRADEDOUBLER_PRODUCT_FEED";
	}

	protected function get_sj_name()
	{
		return "Tradedoubler Product Feed Cron Time";
	}

	protected function get_affiliate_id_prefix()
	{
		// refer to affiliate table for more details
		// SOME IDs have country suffix
		// SOME don't have.. so take note when checking
		return "TD";
	}
}