<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'data_feed_service.php';

class Searchspring_product_feed_service extends Data_feed_service
{
	protected $id = 'Searchspring Product Feed';
	protected $pbv_srv;
	protected $domain;
	protected $image_domain;
	protected $lang_id;

	public function __construct(){
		parent::Data_feed_service();
		include_once APPPATH . 'libraries/service/Price_website_service.php';
		$this->set_price_srv(new Price_website_service());
		include_once APPPATH . 'libraries/service/Product_service.php';
		$this->set_product_srv(new Product_service());
		include_once APPPATH . 'libraries/service/Price_service.php';
		$this->set_price_srv(new Price_service());
		include_once APPPATH . 'libraries/service/Platform_biz_var_service.php';
		$this->set_pbv_srv(new Platform_biz_var_service());

		include_once(APPPATH."helpers/image_helper.php");
		include_once(APPPATH."helpers/MY_url_helper.php");
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

	public function get_pbv_srv()
	{
		return $this->pbv_srv;
	}

	public function set_pbv_srv(Base_service $srv)
	{
		$this->pbv_srv = $srv;
	}

	public function set_domain($domain)
	{
		$this->domain = $domain;
	}

	public function get_domain()
	{
		return $this->domain;
	}

	public function set_image_domain($image_domain)
	{
		$this->image_domain = $image_domain;
	}

	public function get_image_domain()
	{
		return $this->image_domain;
	}

	public function set_lang_id($lang_id)
	{
		$this->lang_id = $lang_id;
	}

	public function get_lang_id()
	{
		return $this->lang_id;
	}

	private function init($platform_id)
	{
		$pbv_obj = $this->get_pbv_srv()->get_platform_biz_var($platform_id);
		$lang_id = $pbv_obj->get_language_id();
		$country_id = $pbv_obj->get_platform_country_id();

		include_once(APPPATH . 'hooks/country_selection.php');
		$this->set_domain(Country_selection::rewrite_domain_by_country('www.valuebaset.com', $country_id));
		$this->set_image_domain(base_cdn_url());
		$this->set_lang_id($lang_id);
		$this->load_language($lang_id);
	}

	public function gen_data_feed($platform_id)
	{
		$this->init($platform_id);
		define('DATAPATH', $this->get_config_srv()->value_of('data_path'));

		$lang_id = $this->get_lang_id();
		$where = array('pbv.language_id'=>$lang_id, 'pbv.selling_platform_id'=>$platform_id);
		if($filename = $this->get_data_feed($where))
		{
			copy(DATAPATH . 'feeds/searchspring/' . $lang_id. '/' . $filename, DATAPATH . 'feeds/searchspring/ftp/' . $lang_id. '/vb_searchspring_' . strtolower($platform_id) . '.xml');
		}
	}

	protected function get_data_list($where = array(), $option = array())
	{
		$this->del_dir(DATAPATH . 'feeds/searchspring/' . $where['pbv.language_id']);
		$filename = 'vb_data_feed_' . $where['pbv.selling_platform_id'] . '.xml';
		$fp = fopen(DATAPATH . 'feeds/searchspring/' . $where['pbv.language_id'] . '/'. $filename, 'w');

		set_time_limit(300);
		$num_rows = $this->get_prod_srv()->get_searchspring_product_feed_product_info_dto($where, array('num_rows'=>1));
		$offset = 0;
		$arr = array();

		if($num_rows > 0)
		{
			$total = ceil($num_rows / 5000);
		}
		$content = '';
		$content .= '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
		$content .= '<Products>' . "\n";

		for($i=0; $i < $total; $i++)
		{
			if($arr = $this->get_prod_srv()->get_searchspring_product_feed_product_info_dto($where, $option = array('orderby'=>'pr.sku', 'offset'=>$i*5000, 'limit'=>5000)))
			{
				foreach ($arr as $product_info_dto)
				{
					$price_list = $this->get_prod_srv()->get_searchspring_product_feed_price_info_dto(array_merge($where, array('p.sku'=>$product_info_dto->get_sku())), array('limit'=>-1));
					$data[$product_info_dto->get_sku()]['product_info_dto'] = $product_info_dto;
					foreach($price_list as $price_info_dto)
					{
						$arr = array();
						$product_info_dto = $this->process_data_row($product_info_dto);
						$price_info_dto = $this->process_data_row($price_info_dto);
						$data[$price_info_dto->get_sku()]['price_info_dto'][] = $price_info_dto;
					}

					$content .= $this->gen_xml($data);
					$data = array();
					if($content)
					{
						if(fwrite($fp, $content))
						{
							$content = '';
						}
						else
						{
							$subject = '<DO NOT REPLY> Fails to create SearchSpring Product Feed File';
							$message = "FILE: " . __FILE__ . "<br>
										 LINE: " . __LINE__;
							$this->error_handler($subject, $message);
						}
					}
				}
			}
		}
		$content = '</Products>' . "\n";
		fwrite($fp, $content);

		return $filename;
	}

	public function get_data_feed($where=array(), $option=array())
	{
		$filename = $this->get_data_list($where, $option);
		return $filename;
	}

	public function process_data_row($data = NULL)
	{
		if (!is_object($data))
		{
			return NULL;
		}

		$replace_domain = 'http://' . $this->get_domain();
		$image_domain = $this->get_image_domain();
		if($data instanceof Searchspring_product_feed_product_info_dto)
		{
			$data->set_add_cart_url($replace_domain . $data->get_add_cart_url());
			$data->set_image_url($image_domain . get_image_file($data->get_image(), 'm', $data->get_sku()));
			$data->set_thumb_image_url($image_domain . get_image_file($data->get_image(), 's', $data->get_sku()));
		}

		if($data instanceof Searchspring_product_feed_price_info_dto)
		{
			$data->set_product_url($replace_domain . $data->get_product_url());

			if($data->get_price() > 0)
			{
				$rrp = $this->get_price_srv()->calc_website_product_rrp($data->get_price(), $data->get_fixed_rrp(), $data->get_rrp_factor());
			}
			else
			{
				$rrp = 0;
			}
			$data->set_rrp($rrp);
		}

		return $data;
	}

	public function gen_xml($data_list = NULL)
	{
		$lang = $this->lang;
		$website_status = array('I'=>'In Stock', 'O'=>'Out of Stock', 'P'=>'Pre-Order', 'A'=>'Arriving');

		$xml_content = '';

		$prev_sku = "";
		foreach($data_list as $data)
		{
			$xml_content .= '<Product>' . "\n";
			$xml_content .= '<id>' . $data['product_info_dto']->get_sku() . '</id>' . "\n";
			$xml_content .= '<sku>' . $data['product_info_dto']->get_sku() . '</sku>' . "\n";
			$xml_content .= '<name>' . strip_invalid_xml(htmlspecialchars(trim($data['product_info_dto']->get_prod_name()))) . '</name>' . "\n";
			$xml_content .= '<brand>'. strip_invalid_xml(htmlspecialchars(trim($data['product_info_dto']->get_brand_name()))) . '</brand>' . "\n";
			$xml_content .= '<image_url>' . $data['product_info_dto']->get_image_url() . '</image_url>' . "\n";
			$xml_content .= '<thumb_image_url>' . $data['product_info_dto']->get_thumb_image_url() . '</thumb_image_url>' . "\n";
			$xml_content .= '<add_cart_url>' . $data['product_info_dto']->get_add_cart_url() . '</add_cart_url>' . "\n";
			$xml_content .= '<description>' . strip_invalid_xml(htmlspecialchars(trim($data['product_info_dto']->get_detail_desc()))) . '</description>' . "\n";
			$xml_content .= '<create_date>' . $data['product_info_dto']->get_create_date() . '</create_date>' . "\n";

			foreach($data['price_info_dto'] as $price_info_dto)
			{
				if($price_info_dto->get_website_status() == 'O')
				{
					$quantity = 0;
				}
				else
				{
					$quantity = $price_info_dto->get_quantity();
				}

				$xml_content .= '<url><![CDATA[' . strip_invalid_xml(htmlspecialchars(trim($price_info_dto->get_product_url()))) .  ']]></url>' . "\n";
				$xml_content .= '<price>' . $price_info_dto->get_price() . '</price>' . "\n";
				$xml_content .= '<msrp>' . $price_info_dto->get_rrp() . '</msrp>' . "\n";
				$xml_content .= '<discount_text>' . $lang['save'] . number_format(($price_info_dto->get_rrp() == 0 ? 0 : ($price_info_dto->get_rrp() - $price_info_dto->get_price()) / $price_info_dto->get_rrp() * 100), 0) . '%</discount_text>' . "\n";
				$xml_content .= '<quantity>' . $quantity .  '</quantity>' . "\n";
			}

			$xml_content .= '<categories>' . "\n";
			$xml_content .= '<category>' . strip_invalid_xml(htmlspecialchars(trim($data['product_info_dto']->get_cat_name()))) . '</category>' . "\n";
			if ($data['product_info_dto']->get_sub_cat_name() != '')
			{
				$xml_content .= '<category>' . strip_invalid_xml(htmlspecialchars(trim($data['product_info_dto']->get_cat_name() . '/' . $data['product_info_dto']->get_sub_cat_name()))) . '</category>' . "\n";
			}
			$xml_content .= '</categories>' . "\n";

			if($data['product_info_dto']->get_clearance() == 1)
				{
					$clearance = '111';
				}
				else
				{
					$clearance = '0';
				}



			$xml_content .= '<clearance>' . $clearance .  '</clearance>' . "\n";
			//$xml_content .= '<clearance>' . $data['product_info_dto']->get_clearance() . '</clearance>' . "\n";
			$xml_content .= '</Product>' . "\n";
		}

		return $xml_content;
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
		return 'itsupport@eservicesgroup.com';
	}

	protected function get_ftp_name()
	{
		return 'SEARCHSPRING';
	}

	protected function get_sj_id()
	{
		return 'SEARCHSPRING_PRODUCT_FEED';
	}

	protected function get_sj_name()
	{
		return 'SearchSpring Product Feed Cron Time';
	}
}

/* End of file searchspring_product_feed_service.php */
/* Location: ./system/application/libraries/service/Searchspring_product_feed_service.php */