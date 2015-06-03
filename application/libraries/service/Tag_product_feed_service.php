<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Data_feed_service.php";

class Tag_product_feed_service extends Data_feed_service
{
	protected $id = "Tag_product_feed";

	public function __construct(){
		parent::Data_feed_service();
		include_once APPPATH."libraries/service/Price_website_service.php";
		$this->set_price_srv(new Price_website_service());
		include_once APPPATH."libraries/service/Product_service.php";
		$this->set_product_srv(new Product_service());
		$this->set_output_delimiter(',');
	}

	public function get_price_srv()
	{
		return $this->price_srv;
	}

	public function set_price_srv(Base_service $srv)
	{
		$this->price_srv = $srv;
	}

	public function get_product_srv()
	{
		return $this->product_srv;
	}

	public function set_product_srv(Base_service $srv)
	{
		$this->product_srv = $srv;
	}

	public function gen_data_feed($platform_id = "WEBSG")
	{
		define('DATAPATH', $this->get_config_srv()->value_of("data_path"));
		$data_feed = $this->get_data_feed($platform_id);

		if($data_feed)
		{
			$filename = 'tag_product_feed_' . date('Ymdhis') . '.txt';
			$fp = fopen(DATAPATH . "feeds/tag/{$platform_id}/" . $filename, 'w');

			if(fwrite($fp, $data_feed))
			{
				$this->ftp_feeds(DATAPATH . "feeds/tag/{$platform_id}/" . $filename, "/valuebasket.txt", $this->get_ftp_name($platform_id));
			}
			else
			{
				$subject = "<DO NOT REPLY> Fails to create Tag Product Feed File";
				$message ="FILE: ".__FILE__."<br>
							 LINE: ".__LINE__;
				$this->error_handler($subject, $message);
			}

			header("Content-type: text/csv");
			header("Content-Disposition: attachment; filename=tag_feed-$platform_id.csv");
			header("Pragma: no-cache");
			header("Expires: 0");

			echo $data_feed;
		}
	}

	protected function get_data_list($where = array(), $option = array())
	{
		return $this->get_prod_srv()->get_tag_product_feed_dto($where, array("limit"=>-1));
	}

	public function get_data_feed($platform_id, $first_line_headling = TRUE)
	{
		$arr = $this->get_data_list(array("pr.platform_id"=>$platform_id));
		if (!$arr) return;
		$new_list = array();

		// echo "<pre>"; foreach ($arr as $row) var_dump($row); die();
		if (1 == 10)
		{
			foreach ($arr as $row)
			{
				$price_srv = $this->get_price_srv();
				if ($prod_obj = $this->get_product_srv()->get_dao()->get_product_overview(array("sku"=>$row->get_sku(), "platform_id"=>"WEBAU"), array("limit"=>1)))
				{
					$price_srv->calc_logistic_cost($prod_obj);
					$price_srv->calculate_profit($prod_obj);
					if($prod_obj->get_margin() >= 5)
					{
						$new_list[] = $this->process_data_row($row);
					}
				}
			}
		}
		else
		{
			foreach ($arr as $row)
			{
				$new_list[] = $this->process_data_row($row);
			}
		}
		$content = $this->convert($new_list, $first_line_headling);

		// echo "<pre>"; var_dump($new_list); die();
		// echo $content; die();

		return $content;
	}

	public function process_data_row($data = NULL)
	{
		if (!is_object($data))
		{
			return NULL;
		}

		$search = array(chr(10), chr(13), chr(9));
		$replace = array(" ", " ", " ");
		$detail_desc = str_replace($search, $replace, $data->get_detail_desc());
		$detail_desc = trim($detail_desc);
		$data->set_detail_desc($detail_desc);

		switch($data->get_platform_id())
		{
			case "WEBSG":
				$domain = "www.valuebasket.com.sg";
				$locale = "en_SG";
				$aftag = "TAGSG";
				break;

			case "WEBMY":
			default:
				$domain = "www.valuebasket.com";
				$locale = "en_MY";
				$aftag = "TAGMY";
				break;
		}

		if(!$data->get_image_url() || !file_exists($this->get_config_srv()->value_of("prod_img_path").basename($data->get_image_url())))
		{
			$data->set_image_url("http://{$domain}/images/product/imageunavailable.jpg");
		}
		else
		{
			$data->set_image_url("http://{$domain}" . $data->get_image_url());
		}

		$data->set_product_url("http://{$domain}/{$locale}" . $data->get_product_url());

		return $data;
	}

	public function convert($list = array(), $first_line_headling = TRUE)
	{
		$out_xml = new Vo_to_xml($list, $this->get_vo2xml_mapping());
		$out_csv = new Xml_to_csv("", $this->get_xml2csv_mapping(), $first_line_headling, $this->get_output_delimiter(), FALSE);

		return $this->get_dex_srv()->convert($out_xml, $out_csv);
	}

	protected function get_default_vo2xml_mapping()
	{
		return '';
	}

	protected function get_default_xml2csv_mapping()
	{
		return APPPATH . 'data/tag_product_feed_xml2csv.txt';
	}

	public function get_contact_email()
	{
		return 'itsupport@eservicesgroup.net';
	}

	protected function get_ftp_name($platform_id)
	{
		return "";
		switch($platform_id)
		{
			case "WEBSG":
				return 'PRICE_PANDA_SG';
				break;
			case "WEBMY":
				return 'PRICE_PANDA_MY';
				break;
		}
		return false;
	}

	protected function get_sj_id()
	{
		return "TAG_PRODUCT_FEED";
	}

	protected function get_sj_name()
	{
		return "Tag Product Feed Cron Time";
	}
}

/* End of file price_panda_product_feed_service.php */
/* Location: ./system/application/libraries/service/Price_panda_product_feed_service.php */