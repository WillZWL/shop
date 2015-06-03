<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "my_shopping_com_au_product_feed_service.php";

class Shop_deal_compare_com_au_product_feed_service extends My_shopping_com_au_product_feed_service
{
	protected $id = "ShopDealCompare.com.au Product Feed";

	public function __construct(){
		parent::My_shopping_com_au_product_feed_service();
	}

	public function gen_data_feed()
	{
		define('DATAPATH', $this->get_config_srv()->value_of("data_path"));

		$data_feed = $this->get_data_feed();
		if($data_feed)
		{
			$filename = 'shop_deal_compare_com_au_product_feed_' . date('Ymdhis') . '.csv';
			$fp = fopen(DATAPATH . 'feeds/shop_deal_compare_com_au/' . $filename, 'w');

			if(fwrite($fp, $data_feed))
			{
				$this->ftp_feeds(DATAPATH . 'feeds/shop_deal_compare_com_au/' . $filename, "/valuebasket.csv", $this->get_ftp_name());
			}
			else
			{
				$subject = "<DO NOT REPLY> Fails to create ShopDealCompare.com.au Product Feed File";
				$message ="FILE: ".__FILE__."<br>
							 LINE: ".__LINE__;
				$this->error_handler($subject, $message);
			}
		}
	}

	protected function get_data_list($where = array(), $option = array())
	{
		return $this->get_prod_srv()->get_my_shopping_com_au_product_feed_dto(array(), array("limit"=>-1));
	}

	public function get_data_feed($first_line_headling = TRUE)
	{
		$arr = $this->get_data_list();
		if (!$arr)
		{
			return;
		}

		$new_list = array();

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
		$content = $this->convert($new_list, $first_line_headling);

		return $content;
	}

	public function process_data_row($data = NULL)
	{
		if (!is_object($data))
		{
			return NULL;
		}

		$search = array(chr(10), chr(13));
		$replace = array( " ", " ");
		$detail_desc = str_replace($search, $replace, $data->get_detail_desc());
		$detail_desc = trim($detail_desc);
		$data->set_detail_desc($detail_desc);

		if(!$data->get_image_url() || !file_exists($this->get_config_srv()->value_of("prod_img_path").basename($data->get_image_url())))
		{
			$data->set_image_url("http://www.valuebasket.com.au/images/product/imageunavailable.jpg");
		}

		return $data;
	}

	protected function get_default_vo2xml_mapping()
	{
		return '';
	}

	public function get_contact_email()
	{
		return 'oswald@eservicesgroup.net';
	}

	protected function get_ftp_name()
	{
		return 'SHOP_DEAL_COMPARE';
	}

	protected function get_sj_id()
	{
		return "SHOP_DEAL_COMPARE_COM_AU_PRODUCT_FEED";
	}

	protected function get_sj_name()
	{
		return "ShopDealCompare.com.au Product Feed Cron Time";
	}
}

/* End of file shop_deal_compare_com_au_product_feed_service.php */
/* Location: ./system/application/libraries/service/Shop_deal_compare_au_product_feed_service.php */