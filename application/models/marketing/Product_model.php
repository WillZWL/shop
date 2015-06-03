<?php
class Product_model extends CI_Model
{


	public function __construct()
	{
		parent::__construct();
		$this->load->library('service/bundle_service');
		$this->load->library('service/product_service');
		$this->load->library('service/supplier_service');
		$this->load->library('service/brand_service');
		$this->load->library('service/colour_service');
		$this->load->library('service/freight_cat_service');
		$this->load->library('service/category_service');
		$this->load->library('service/price_service');
		$this->load->library('service/inventory_service');
		$this->load->library('service/product_feed_service');
		$this->load->library('service/version_service');
		$this->load->library('service/language_service');
		$this->load->library('service/country_service');
		$this->load->library('service/product_spec_service');
		$this->load->library('service/func_option_service');
		$this->load->library('service/delivery_service');
		$this->load->library('service/graphic_service');
		$this->load->library('service/external_category_service');
		$this->load->library('service/category_mapping_service');
		$this->load->library('service/custom_classification_mapping_service');
		$this->load->library('service/product_custom_classification_service');
		$this->load->library('service/warranty_service');
	}

	public function get_valid_warrant_period_list()
	{
		return array(0, 6, 12, 18, 24, 36);
	}

	public function get_product_list($where=array(), $option=array())
	{
		return $this->product_service->get_dao()->get_list_w_name($where, $option, "Product_list_w_name_dto");
	}

	public function get_product_list_total($where=array())
	{
		return $this->product_service->get_dao()->get_list_w_name($where, array("num_rows"=>1));
	}

	public function get_list($service, $where=array(), $option=array())
	{
		$service = $service."_service";
		return $this->$service->get_list($where, $option);
	}

	public function get_bundle_list($where=array(), $option=array())
	{
		return $this->bundle_service->get_dao()->get_bundle_list($where, $option, "Bundle_list_dto");
	}

	public function get($service, $where=array())
	{
		$service = $service."_service";
		return $this->$service->get_dao()->get($where);
	}

	public function get_webpage($service,$where=array())
	{
		return $this->product_service->get_dao()->get_website_prod($where);
	}

	public function get_supplier_prod($where=array())
	{
		return $this->supplier_service->get_sp_dao()->get($where);
	}

	public function get_product_custom_classification($where=array())
	{
		return $this->product_custom_classification_service->get($where);
	}

	public function get_product_content($where=array())
	{
		return $this->product_service->get_pc_dao()->get($where);
	}

	public function get_product_content_extend($where=array())
	{
		return $this->product_service->get_pcext_dao()->get($where);
	}

	public function get_category_mapping($where=array())
	{
		return $this->product_service->get_cat_map_dao()->get($where);
	}

	public function get_product_content_list($where=array(),$option=array())
	{
		return $this->product_service->get_pc_dao()->get_list($where, $option);
	}

	public function get_product_content_extend_list($where=array(), $option=array())
	{
		return $this->product_service->get_pcext_dao()->get_list($where, $option);
	}

	public function get_category_mapping_list($where=array(),$option=array())
	{
		return $this->product_service->get_cat_map_dao()->get_list($where, $option);
	}

	public function get_product_keyword($where=array())
	{
		return $this->product_service->get_pk_dao()->get($where);
	}

	public function get_product_keyword_list($where=array(),$option=array())
	{
		return $this->product_service->get_pk_dao()->get_list($where, $option);
	}

	public function get_product_keyword_arraylist($sku = "", $platform_id = "")
	{
		return $this->product_service->get_product_keyword_arraylist($sku, $platform_id);
	}

	public function get_product_type($where=array())
	{
		return $this->product_service->get_pt_dao()->get($where);
	}

	public function get_product_type_list($where=array(),$option=array())
	{
		return $this->product_service->get_pt_dao()->get_list($where, $option);
	}

	public function update($service, $obj)
	{
		$service = $service."_service";
		return $this->$service->update($obj);
	}

	public function update_product_content($pc="")
	{
		return $this->product_service->get_pc_dao()->update($pc);
	}

	public function del_product_content($where=array())
	{
		return $this->product_service->get_pc_dao()->q_delete($where);
	}

	public function del_product_keyword($where=array())
	{
		return $this->product_service->get_pk_dao()->q_delete($where);
	}

	public function del_product_type($where=array())
	{
		return $this->product_service->get_pt_dao()->q_delete($where);
	}

	public function update_product_content_extend($pc="")
	{
		return $this->product_service->get_pcext_dao()->update($pc);
	}

	public function del_product_content_extend($where=array())
	{
		return $this->product_service->get_pcext_dao()->q_delete($where);
	}

	public function include_vo($service)
	{
		$service = $service."_service";
		return $this->$service->include_vo();
	}

	public function include_pc_vo()
	{
		return $this->product_service->get_pc_dao()->include_vo();
	}

	public function include_pcext_vo()
	{
		return $this->product_service->get_pcext_dao()->include_vo();
	}

	public function include_dto($service, $dto)
	{
		$service = $service."_service";
		return $this->$service->include_dto($dto);
	}

	public function add($service, $obj)
	{
		$service = $service."_service";
		return $this->$service->insert($obj);
	}

	public function add_supplier_prod($obj)
	{
		return $this->supplier_service->get_sp_dao()->insert($obj);
	}

	public function add_product_custom_class($obj)
	{
		return $this->product_custom_classification_service->insert($obj);
	}

	public function add_product_content($obj)
	{
		return $this->product_service->get_pc_dao()->insert($obj);
	}

	public function add_product_keyword($obj)
	{
		 $this->product_service->get_pk_dao()->insert($obj);
	}

	public function add_product_type($obj)
	{
		return $this->product_service->get_pt_dao()->insert($obj);
	}

	public function add_product_content_extend($obj)
	{
		return $this->product_service->get_pcext_dao()->insert($obj);
	}

	public function add_category_mapping($obj)
	{
		return $this->product_service->get_cat_map_dao()->insert($obj);
	}

	public function seq_next_val()
	{
		return $this->product_service->get_dao()->seq_next_val();
	}

	public function pi_seq_next_val()
	{
		return $this->product_service->pi_seq_next_val();
	}

	public function pb_seq_next_val()
	{
		return $this->product_service->pb_seq_next_val();
	}

	public function update_seq($new_value)
	{
		return $this->product_service->get_dao()->update_seq($new_value);
	}

	public function get_website_price($sku,$platform_id="WSGB")
	{
		return $this->price_service->get_website_price($sku, $platform_id);
	}

	public function get_website_display_price($sku,$platform_id="WSGB")
	{
		return $this->price_service->get_website_display_price($sku, $platform_id);
	}

	public function get_website_currency($platform="")
	{
		return $this->currency_service->get_platform_currency($platform);
	}

	public function get_categories($sku)
	{
		return $this->category_service->get_dao()->get_prod_cat($sku);
	}

	public function get_list_by_brand_cat($catid, $brand, $where=array(), $option=array())
	{
        	return $this->product_service->get_list_by_brand_cat($catid, $brand, $where, $option);
    	}

	public function get_list_by_brand_cat_cnt($catid, $brand, $where=array())
	{
        	return $this->product_service->get_list_by_brand_cat_cnt($catid, $brand, $where);
    	}

	public function get_brand($brand)
	{
		return $this->brand_service->get_dao()->get(array("id"=>$brand));
	}

	public function get_prod_list($where=array(), $option=array())
	{
		return $this->product_service->get_website_list($where, $option);
	}

	public function get_prod_list_cnt($where=array())
	{
		return $this->product_service->get_website_list_cnt($where);
	}

	public function get_clearance_list($where=array(), $option=array())
	{
		return $this->product_service->get_clearance_list($where,$option);
	}

	public function get_clearance_list_cnt($where=array())
	{
		return $this->product_service->get_clearance_list_cnt($where);
	}

	public function get_list_by_keyword($keyword, $page_no=0, $row_limit=20,
		$platform_id='WSGB', $lang_id='en')
	{
		return $this->product_service->get_list_by_keyword($keyword, $page_no,
			$row_limit, $platform_id, $lang_id, 'website_prod_search_info_dto');
	}

	public function get_sli_feed($where)
	{
		return $this->product_service->get_dao()->get_sli_webfeed($where);
	}

	public function get_skype_feed($sku,$lang_id,$platform,$currency)
	{
		return $this->product_service->get_dao()->get_skype_feed($sku,$lang_id,$platform,$currency,'TRIAL');
	}

	public function get_skype_page_info($clist, $platform="", $lang_id="")
	{
		if($platform == "")
		{
			return FALSE;
		}
		return $this->product_service->get_skype_page_info($clist,$platform,$lang_id);
	}

	public function get_main_sku($sku="")
	{
		if($sku == "")
		{
			return FALSE;
		}
		else
		{
			return $this->bundle_service->get_main_sku($sku);
		}
	}

	public function get_prod_inventory($where)
	{
		return $this->inventory_service->get_vpi_dao()->get($where);
	}

	public function get_prod_master_sku($where)
	{
		return $this->product_service->get_sku_map_dao()->get($where);
	}

	public function get_remain_colour_list($prod_grp_cd)
	{
		return $this->colour_service->get_dao()->get_remain_colour_list($prod_grp_cd);
	}

	public function get_remain_version_list($prod_grp_cd)
	{
		return $this->version_service->get_dao()->get_remain_version_list($prod_grp_cd);
	}

	public function get_existing_proplist($type="",$where=array())
	{
		if($type == "")
		{
			return false;
		}
		else
		{
			if($type == "version")
			{
				return $this->product_service->get_dao()->get_existing_version($where);
			}
			else if ($type == "colour")
			{
				return $this->product_service->get_dao()->get_existing_colour($where);
			}
			else
			{
				return false;
			}
		}
	}

	public function get_sign($platform_id = "")
	{
		return $this->currency_service->get_dao()->get_sign($platform_id);
	}

	public function get_prod_image_list($where=array(), $option=array())
	{
		return $this->product_service->get_prod_image_list($where, $option);
	}

	public function get_prod_image($where=array())
	{
		return $this->product_service->get_prod_image($where);
	}

	public function update_product_image($obj)
	{
		return $this->product_service->update_product_image($obj);
	}

	public function add_product_image($obj)
	{
		return $this->product_service->add_product_image($obj);
	}

	public function get_prod_banner($where=array())
	{
		return $this->product_service->get_prod_banner($where);
	}

	public function get_prod_banner_list($where=array(), $option=array())
	{
		return $this->product_service->get_prod_banner_list($where, $option);
	}

	public function update_product_banner($obj)
	{
		return $this->product_service->update_product_banner($obj);
	}

	public function add_product_banner($obj)
	{
		return $this->product_service->add_product_banner($obj);
	}

	public function get_listed_video_list($where=array(), $option=array())
	{
		return $this->product_service->get_listed_video_list($where, $option);
	}

	public function get_best_selling_video_list($filter_column = '',
		$cat_id = 0, $day_count = 0, $limit = 0, $platform="", $type="", $src="")
	{
		return $this->product_service->get_best_selling_video_list($filter_column,
			$cat_id, $day_count, $limit, $platform, $type, $src);
	}

	public function get_best_selling_video_list_by_cat($filter_column = '',
		$cat_id = 0, $day_count = 0, $limit = 0, $platform="", $type="", $src="")
	{
		return $this->product_service->get_best_selling_video_list_by_cat($filter_column,
			$cat_id, $day_count, $limit, $platform, $type, $src);
	}

	public function get_display_video_list($where=array(), $option=array())
	{
		return $this->product_service->get_display_video_list($where, $option);
	}

	public function get_video_detail($where=array(), $option=array())
	{
		return $this->product_service->get_video_detail($where, $option);
	}

	public function get_product_spec_with_sku($sku, $lang_id)
	{
		return $this->product_spec_service->get_product_spec_with_sku($sku, $lang_id);
	}

	public function get_text_of($func_id, $lang_id)
	{
		return $this->func_option_service->text_of($func_id, $lang_id);
	}

	public function get_full_psd_w_lang($sub_cat_id, $sku, $lang_id)
	{
		return $this->product_spec_service->get_full_psd_w_lang($sub_cat_id, $sku, $lang_id);
	}

	public function update_response_psd_list($sku, $sub_cat_id, $response_psd_list)
	{
		return $this->product_spec_service->update_response_psd_list($sku, $sub_cat_id, $response_psd_list);
	}

	public function graphic_seq_next_val()
	{
		return $this->graphic_service->seq_next_val();
	}

	public function update_graphic_seq($value)
	{
		return $this->graphic_service->update_seq($value);
	}

	public function insert_graphic($obj)
	{
		return $this->graphic_service->insert($obj);
	}

	public function update_graphic($obj)
	{
		return $this->graphic_service->update($obj);
	}

	public function get_graphic($where = array())
	{
		return $this->graphic_service->get($where);
	}

	public function is_trial_software($sku = "")
	{
		return $this->product_service->is_trial_software($sku);
	}

	public function get_product_type_w_sku($sku = "")
	{
		return $this->product_service->get_product_type_w_sku($sku);
	}

	public function populate_to_all_lang($ps_id, $sub_cat_id, $sku, $lang_id)
	{
		return $this->product_spec_service->populate_to_all_lang($ps_id, $sub_cat_id, $sku, $lang_id);
	}

	public function get_googlebase_cat_list_by_country()
	{
		$ext_cat_list = $this->external_category_service->get_list(array("ext_party"=>"GOOGLEBASE", "status"=>1), array("limit"=>-1));
		$result = array();
		if($ext_cat_list)
		{
			foreach($ext_cat_list as $ext_cat_obj)
			{
				$rs[$ext_cat_obj->get_country_id()][] = $ext_cat_obj;
			}
		}
		return $rs;
	}

	public function get_googlebase_cat_list_w_country($sku = "")
	{
		$where = $option = array();
		$where['cm.id'] = $sku;
		$where['cm.ext_party'] = "GOOGLEBASE";
		$where['cm.status'] = 1;
		$option['limit'] = -1;
		$ext_cat_list = $this->category_mapping_service->get_googlebase_cat_list_w_country($where, $option);

		//var_dump($ext_cat_list);
		//var_dump($this->category_mapping_service->get_dao()->db->last_query());
		$result = array();
		if($ext_cat_list)
		{
			foreach($ext_cat_list as $ext_cat_obj)
			{
				$rs[$ext_cat_obj->get_country_id()] = $ext_cat_obj;
			}
		}
		return $rs;
	}

	public function get_googlebase_cat($where=array())
	{
		return $this->external_category_service->get($where);
	}

	public function get_cat_map($where = array())
	{
		return $this->category_mapping_service->get_cat_map($where);
	}

	public function insert_cat_map($obj)
	{
		return $this->category_mapping_service->insert_cat_map($obj);
	}

	public function update_cat_map($obj)
	{
		return $this->category_mapping_service->update_cat_map($obj);
	}

	public function get_country_list($where = array(), $option = array())
	{
		return $this->country_service->get_list($where, $option);
	}

	public function get_custom_class_mapping_by_sub_cat_id($sub_cat_id = "")
	{
		return $this->custom_classification_mapping_service->get_custom_class_mapping_by_sub_cat_id($sub_cat_id);
	}

	public function get_website_product_info($sku = "", $platform_id = "WEBHK", $lang_id = "en")
	{
		return $this->product_service->get_website_product_info($sku, $platform_id, $lang_id);
	}

	public function get_listing_info($sku = "", $platform_id = "", $lang_id = "en", $option = array())
	{
		return $this->price_service->get_listing_info($sku, $platform_id, $lang_id, $option);
	}

	public function get_listing_info_list($sku_arr = array(), $platform_id = "", $lang_id = 'en', $option = array())
	{
		return $this->price_service->get_listing_info_list($sku_arr, $platform_id, $lang_id, $option);
	}

	public function get_warranty_by_sku($sku = "", $platform = "", $lang_id = "en")
	{
		return $this->warranty_service->get_warranty_by_sku($sku, $platform, $lang_id);
	}

	public function get_product_category_report()
	{
		$where = array();
		$where['m.ext_sku is not null'] = NULL;

		$option = array();
		$option['limit'] = -1;
		$option['orderby'] = 'm.ext_sku';

		$result = array();
		$result['filename'] = 'product_category_report.csv';
		$result['output'] = $this->product_service->get_product_category_report($where, $option);

		return $result;
	}
}
/* End of file product_model.php */
/* Location: ./system/application/models/product_model.php */
