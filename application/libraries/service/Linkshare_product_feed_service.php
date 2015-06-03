<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Data_feed_service.php";

class Linkshare_product_feed_service extends Data_feed_service
{
	private static $total;
	private $_platform_id;

	public function __construct(){
		parent::Data_feed_service();
		$this->total = 0;
		$this->set_output_delimiter("|");
	}

	public function init($platform_id)
	{
		$this->_platform_id = $platform_id;
		switch($platform_id)
		{
			case "WEBAU":
				$this->id = "Linkshare AU Product Feed";
				$this->filename_prefix = '37893';
				$this->country_w_language = 'en_AU';
				$this->file_path = 'feeds/linkshare/product/au/';
				$this->sj_id = 'LINKSHARE_AU_PRODUCT_FEED';
				$this->sj_name = "LINKSHARE AU Product Feed Cron Time";
				$this->base_url = "http://www.valuebasket.com.au/";
				$this->ftp_name = "LINKSHARE_AU";
				return TRUE;
				break;
			case "WEBGB":
				$this->id = "Linkshare GB Product Feed";
				$this->filename_prefix = '37439';
				$this->country_w_language = 'en_GB';
				$this->file_path = 'feeds/linkshare/product/gb/';
				$this->sj_id = 'LINKSHARE_GB_PRODUCT_FEED';
				$this->sj_name = "LINKSHARE GB Product Feed Cron Time";
				$this->base_url = "http://www.valuebasket.com/";
				$this->ftp_name = "LINKSHARE";
				return TRUE;
				break;

			default:
				return FALSE;
		}
	}

	public function gen_data_feed($platform_id)
	{
		if($this->init($platform_id))
		{
			define('DATAPATH', $this->get_config_srv()->value_of("data_path"));
			$data_feed = $this->get_data_feed(false, array("pr.platform_id"=>$platform_id));
			if($data_feed)
			{
				$filename = $this->filename_prefix.'_nmerchandis.txt';
				$fp = fopen(DATAPATH . $this->file_path . $filename, 'w');

				//$header = "HDR|".$this->filename_prefix."|VBltd|".date('Y-m-d/h:i:s')."\n";
				$trailer = "TRL|".$this->total."\n";

//				$header="ProductID|ProductName|SKU|primary_cat|sec_cat|url|image_url|buy_url|short_desc|long_desc|discount|discounttype|saleprice|retailprice|begindate|enddate|brand|shipping|deleteflag|keywords|isall|mpn|man_name|shipping info|availabiliy|UPC|classID|isprodlink|isstorefront|ismerch|currency"."\n";
				$header = "";
				$content = $data_feed.$trailer;
				$content = utf8_encode($content);
				if(fwrite($fp, $content))
				{
						$this->ftp_feeds(DATAPATH . $this->file_path . $filename, "/$filename", $this->get_ftp_name());
						switch($platform_id)
						{
							case "WEBAU":
								$this->ftp_feeds(DATAPATH . $this->file_path . $filename, "/$filename", "MEDIAFORGE_AU");
								break;
							case "WEBGB":
								$this->ftp_feeds(DATAPATH . $this->file_path . $filename, "/$filename", "MEDIAFORGE_GB");
								break;
						}

				// generates "Save File" pop-up for feed in browser
				header("Cache-Control: no-store, no-cache");
				header("Content-Disposition: attachment; filename=\"$filename\"");
				echo $content;

				}
				else
				{
					$subject = "<DO NOT REPLY> Fails to create ".$this->id." File";
					$message ="FILE: ".__FILE__."<br>
								 LINE: ".__LINE__;
					$this->error_handler($subject, $message);
				}
			}
		}
	}

	protected function get_data_list($where = array(), $option = array())
	{
		switch ($this->_platform_id)
		{
			case "WEBAU": $affiliation_tag = "?AF=MFAU"; break;
			case "WEBGB": $affiliation_tag = "?AF=MFUK"; break;
			default: $affiliation_tag = ""; break;
		}

		if($this->_platform_id == 'WEBAU')
		{
			#SBF #3169 new shipping time frames
			$obj_list = $this->get_prod_srv()->get_linkshare_product_feed_2_dto($where, $option);
		}
		else
		{
			$obj_list = $this->get_prod_srv()->get_linkshare_product_feed_dto($where, $option);
		}
		foreach($obj_list AS $obj)
		{
			$obj->set_product_url($this->base_url . $this->country_w_language."/". str_replace(array(" ", "/", "."),"-",$obj->get_prod_name()).'/mainproduct/view/'.$obj->get_sku().$affiliation_tag);
		}
		return $obj_list;
	}

	public function process_data_row($data = NULL)
	{
		if (!is_object($data))
		{
			return NULL;
		}

		if(!file_exists($this->get_config_srv()->value_of("prod_img_path").basename($data->get_image_url())))
		{
			$data->set_image_url("http://www.valuebasket.com/images/product/imageunavailable.jpg");
		}

		$prod_id = $this->string_to_ascii($data->get_sku());
		$data->set_prod_id($prod_id);

		$search = array(chr(10), chr(13), chr(9), "<br>");
		$replace = array(' ', ' ', ' ', ' ');

		$short_desc = htmlentities(strip_tags($data->get_short_desc()));
		$short_desc = str_replace($search, $replace, $short_desc);
		$data->set_short_desc($short_desc);

		$detail_desc = htmlentities(strip_tags($data->get_detail_desc()));
		$detail_desc = str_replace($search, $replace, $detail_desc);
		$data->set_detail_desc($detail_desc);

		$this->total++;

		return $data;
	}

	protected function string_to_ascii($str)
	{
		for($i=0; $i < strlen($str); $i++)
		{
			$new_str .= ord($str[$i]);
		}
		return $new_str;
	}

	protected function get_default_vo2xml_mapping()
	{
		return '';
	}

	protected function get_default_xml2csv_mapping()
	{
		return '';
	}

	public function get_contact_email()
	{
		return 'itsupport@eservicesgroup.net';
	}

	protected function get_ftp_name()
	{
		return $this->ftp_name;
	}

	protected function get_sj_id()
	{
		return $this->sj_id;
	}

	protected function get_sj_name()
	{
		return $this->sj_name;
	}
}

/* End of file linkshare_product_feed_service.php */
/* Location: ./system/application/libraries/service/Linkshare_product_feed_service.php */