<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Data_feed_service.php";

class Pricespy_product_feed_service extends Data_feed_service
{
	protected $id = "Pricespy Product Feed";
	private $prod_srv;
	private $price_srv;

	public function __construct()
	{
		parent::Data_feed_service();
		include_once(APPPATH . 'libraries/service/Price_service.php');
		$this->set_price_srv(New Price_service());

		$this->set_output_delimiter("\t");
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
		define('DATAPATH', $this->get_config_srv()->value_of("data_path"));
		$data_feed = $this->get_data_feed(TRUE, $country, $explain_sku);
		if($data_feed)
		{
			$this->del_dir(DATAPATH . 'feeds/pricespy/' . "$country");
			$this->del_dir(DATAPATH . 'feeds/pricespy/ftp/' . "$country");
			#hide date-stamped files from ftp folder
			$filename = 'valuebasket_pricespy_' . "$country" . '_' . date('Ymdhis') . '.txt';
			$fp_wdate = fopen(DATAPATH . 'feeds/pricespy/' . "$country/" . $filename, 'w');
			$remotefilename = strtolower('/valuebasket_pricespy_' . "$country" . '.txt');
			$fp_nodate = fopen(DATAPATH . 'feeds/pricespy/ftp/' . "$country/" . $remotefilename, 'w');

			if(fwrite($fp_nodate, $data_feed) AND fwrite($fp_wdate, $data_feed))
			{
				// {
				// 	$this->ftp_feeds(DATAPATH . 'feeds/pricespy/' . $filename, $remotefilename, $this->get_ftp_name() . "_$country");
				// }
				if ($explain_sku == "")
				{
					header("Cache-Control: no-store, no-cache");
					header("Content-Disposition: attachment; filename=\"$filename\"");
					echo $data_feed;
				}
			}
			else
			{
				$subject = "<DO NOT REPLY> Fails to create Pricespy Product Feed File";
				$message ="FILE: ".__FILE__."<br>
							 LINE: ".__LINE__;
				$this->error_handler($subject, $message);
			}
		}
	}

	public function get_data_list_w_country($where = array(), $option = array(),  $country = "NZ")
	{
		return $this->get_prod_srv()->get_pricespy_product_feed_dto(array(), array('limit'=>-1), $country);
	}

	public function get_data_feed($first_line_headling = TRUE, $country = "NZ", $explain_sku)
	{
		// common processing to be done here
		switch ($country)
		{
			// country specific processing to be done here
			case "NZ": return $this->get_data_feed_nz($first_line_headling, $country, $explain_sku);
		}
	}

	private function get_data_feed_nz($first_line_headling = TRUE, $country, $explain_sku)
	{
		$list = $this->get_data_list_w_country(array(), array(), "NZ");

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
				$selected = " --> NOT ADDED TO OUTPUT";
				if ($res->get_margin() >= 7)
				{
					$selected = " --> ADDED TO OUTPUT";
					$new_list[] = $res;
				}

				if ($explain_sku != "")
				{
					// var_dump($res);die();
					if (strtoupper($res->get_sku()) == strtoupper($explain_sku))
					{
						var_dump("$explain_sku $selected");
						echo "<pre>";
						var_dump($res);
					}
				}
			}
		}

		$content = $this->convert($new_list, $first_line_headling);
		return $content;
	}

	protected function get_data_list($where = array(), $option = array(),  $country = "NZ")
	{
		return '';
	}

	protected function get_default_vo2xml_mapping()
	{
		return '';
	}

	protected function get_default_xml2csv_mapping()
	{
		return APPPATH . 'data/pricespy_product_feed_xml2csv.txt';
	}

	public function get_contact_email()
	{
		return 'itsupport@eservicesgroup.net';
	}

	// protected function get_ftp_name()
	// {
	// 	return 'PRICESPY';
	// }

	protected function get_sj_id()
	{
		return "PRICESPY_PRODUCT_FEED";
	}

	protected function get_sj_name()
	{
		return "Pricespy Product Feed Cron Time";
	}
}
