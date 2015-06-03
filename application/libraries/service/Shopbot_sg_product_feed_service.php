<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "shopbot_with_pricepanda_format_product_feed_service.php";

class Shopbot_sg_product_feed_service extends Shopbot_with_pricepanda_format_product_feed_service
{
	protected $id = "Shopbot SG Product Feed";
	protected $platform_id = "WEBSG";

	public function __construct(){
		parent::Shopbot_with_pricepanda_format_product_feed_service();
		include_once APPPATH."libraries/service/Price_website_service.php";
		$this->set_price_srv(new Price_website_service());
		include_once APPPATH."libraries/service/Product_service.php";
		$this->set_product_srv(new Product_service());

		$this->set_output_delimiter(',');
	}


	public function gen_data_feed($platform_id = "WEBSG")
	{
		define('DATAPATH', $this->get_config_srv()->value_of("data_path"));
		$data_feed = $this->get_data_feed($platform_id);
		//echo "<pre>"; var_dump($data_feed); die();
		if($data_feed)
		{
			//$filename = 'shopbot_nz_product_feed_' . date('Ymdhis') . '.csv';
			$filename = 'ProductFeed.csv';
			$fp = fopen(DATAPATH . 'feeds/shopbot/sg/'. $filename, 'w');

			if(fwrite($fp, $data_feed))
			{
				$this->ftp_feeds(DATAPATH .  'feeds/shopbot/sg/' . $filename, "/ProductFeed.csv", $this->get_ftp_name($platform_id));
				header("Content-type: text/csv");
				header("Cache-Control: no-store, no-cache");
				header("Content-Disposition: attachment; filename=\"$filename\"");
				echo $data_feed;
			}
			else
			{
				$subject = "<DO NOT REPLY> Fails to create Shopbot SG Product Feed File";
				$message ="FILE: ".__FILE__."<br>
							 LINE: ".__LINE__;
				$this->error_handler($subject, $message);
			}
		}
	}

	public function get_affiliate_data($platform_id)
	{
		switch($platform_id)
		{
			case "WEBSG":
				$data['domain'] = "www.valuebasket.com.sg";
				$data['locale'] = "en_SG";
				$data['af'] = "SBSG";
				break;
			default:
				$data['domain'] = "www.valuebasket.com";
				$data['locale'] = "en_AU";
				$data['af'] = "SBAU";
		}
		return $data;
	}

	protected function get_ftp_name()
	{
		return 'Shopbot_SG';
	}

	protected function get_sj_id()
	{
		return "SHOPBOT_SG_PRODUCT_FEED";
	}

	protected function get_sj_name()
	{
		return "Shopbot Singapore Product Feed Cron Time";
	}

	protected function get_affiliate_id_prefix()
	{
		return "SBSG";
	}
}