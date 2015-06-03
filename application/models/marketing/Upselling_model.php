<?php
class Upselling_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('website/cart_session_model');
		$this->load->library('service/bundle_service');
		$this->load->library('service/product_service');
		$this->load->library('service/supplier_service');
		$this->load->library('service/brand_service');
		$this->load->library('service/colour_service');
		$this->load->library('service/freight_cat_service');
		$this->load->library('service/category_service');
		$this->load->library('service/ra_group_service');
		$this->load->library('service/ra_group_product_service');
		$this->load->library('service/language_service');
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
		return $this->$service->get($where);
	}

	public function get_supplier_prod($where=array())
	{
		return $this->supplier_service->get_sp_dao()->get($where);
	}

	public function get_product_content($where=array())
	{
		return $this->product_service->get_pc_dao()->get($where);
	}

	public function update($service, $obj)
	{
		$service = $service."_service";
		return $this->$service->update($obj);
	}

	public function del_product_content($where=array())
	{
		return $this->product_service->get_pc_dao()->q_delete($where);
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

	public function add_product_content($obj)
	{
		return $this->product_service->get_pc_dao()->insert($obj);
	}

	public function seq_next_val()
	{
		return $this->product_service->get_dao()->seq_next_val();
	}

	public function update_seq($new_value)
	{
		return $this->product_service->get_dao()->update_seq($new_value);
	}

	public function get_ra(&$data, $sku, $platform_id, $lang_id, $listing_status)
	{
		$data["parent_sku"] = $sku;

		$ra_list = $this->cart_session_model->get_ra_prod_list($sku, $platform_id, $lang_id);
		if ($ra_list)
		{
			if($warranty_list = $this->cart_session_model->get_warranty_list_by_sku($sku, $platform_id, $lang_id))
			{

				foreach($warranty_list as $key => $val)
				{
					$tempdata = array();
					$tempdata['id'] = $key;
					$tempdata['group_name'] = $val['group_name'];
					if($val['wlist'])
					{
						foreach($val['wlist'] as $obj){
							if ($obj)
							{

								$warranty["sku"] = $obj->get_sku();
								$warranty["prod_name"] = $obj->get_prod_name();
								$warranty["listing_status"] = $obj->get_status();
								$warranty["stock_status"] =  $obj->get_status() == 'I'?$obj->get_qty()." ".$listing_status[$obj->get_status()]:$listing_status[$obj->get_status()];
								$warranty["stock_status_id"] = $obj->get_status();
								$warranty["price"] = $obj->get_price();
								$warranty["rrp_price"] = $obj->get_rrp_price();
								$warranty["prod_url"] = $this->cart_session_model->get_prod_url($obj->get_sku());
								$warranty["short_desc"] = $obj->get_short_desc();
								$warranty["image_ext"] = $obj->get_image_ext();
								$tempdata["wlist"][] = $warranty;
							}
						}
					}
					$data['warranty'][] = $tempdata;
				}
			}

			$pc_obj = $this->cart_session_model->get_product_content(array("prod_sku"=>$sku, "lang_id"=>$lang_id));
			if(empty($pc_obj))
			{
//if no such language, use en
				$pc_obj = $this->cart_session_model->get_product_content(array("prod_sku"=>$sku, "lang_id"=>"en"));
			}
			if (($pc_obj) && (!isset($data["prod_name"])))
			{
				$data["prod_name"] = $pc_obj->get_prod_name();
			}

			if($ra_list['ra_group_list'])
			{
				$counter = 0;
				$ra_group = array();
				foreach($ra_list['ra_group_list'] AS $group_id=>$group_name)
				{
					$ra_group[$counter]['group_id'] = $group_id;
					$ra_group[$counter]['group_name'] = $group_name;
					$counter++;
				}
				$data['ra_group'] = $ra_group;
			}

			if($ra_list['ra_list'])
			{
				foreach($ra_list['ra_list'] AS $group_id=>$listing_arr)
				{
					foreach($listing_arr AS $key=>$obj)
					{
						$ra_item[$group_id][$key]["sku"] = $obj->get_sku();
						$ra_item[$group_id][$key]["prod_name"] = $obj->get_prod_name();
						$ra_item[$group_id][$key]["listing_status"] = $obj->get_status();
						$ra_item[$group_id][$key]["stock_status"] =  $obj->get_status() == 'I'?$obj->get_qty()." ".$listing_status[$obj->get_status()]:$listing_status[$obj->get_status()];
						$ra_item[$group_id][$key]["stock_status_id"] = $obj->get_status();
						$ra_item[$group_id][$key]["price"] = $obj->get_price();
						$ra_item[$group_id][$key]["rrp_price"] = $obj->get_rrp_price();
						$ra_item[$group_id][$key]["prod_url"] = $this->cart_session_model->get_prod_url($obj->get_sku());
						$ra_item[$group_id][$key]["short_desc"] = $obj->get_short_desc();
						$ra_item[$group_id][$key]["image_ext"] = $obj->get_image_ext();
					}
				}
				$data["ra_item"] = $ra_item;
			}

			if($ra_list["recommended"])
			{
				foreach($ra_list["recommended"] AS $key=>$obj)
				{
					$top_fav[$key]["sku"] = $obj->get_sku();
					$top_fav[$key]["prod_name"] = $obj->get_prod_name();
					$top_fav[$key]["listing_status"] = $obj->get_status();
					$top_fav[$key]["stock_status"] =  $obj->get_status() == 'I'?$obj->get_qty()." ".$listing_status[$obj->get_status()]:$listing_status[$obj->get_status()];
					$top_fav[$key]["stock_status_id"] = $obj->get_status();
					$top_fav[$key]["price"] = $obj->get_price();
					$top_fav[$key]["rrp_price"] = $obj->get_rrp_price();
					$top_fav[$key]["prod_url"] = $this->cart_session_model->get_prod_url($obj->get_sku());
					$top_fav[$key]["short_desc"] = $obj->get_short_desc();
					$top_fav[$key]["image_ext"] = $obj->get_image_ext();
				}
				$data["top_fav"] = $top_fav;
			}

			if ((isset($data["warranty"])) || (isset($data["top_fav"])) || (isset($data["ra_item"])))
			{
				$data["has_ra"] = TRUE;
				return TRUE;
			}
			else
			{
				$data["has_ra"] = FALSE;
				return FALSE;
			}
		}
		else
		{
			$data["has_ra"] = FALSE;
			return FALSE;
		}
	}

	public function get_ra_group_list($where=array(), $option=array())
	{
		return $this->ra_group_service->get_list($where, $option);
	}

	public function get_ra_group_list_total($where=array())
	{
		return $this->ra_group_service->get_num_rows($where);
	}

	public function get_ra_group($group_id='')
	{
		if($group_id == '')
		{
			return $this->ra_group_service->get_dao()->get();
		}
		else
		{
			return $this->ra_group_service->get_dao()->get(array("group_id"=>$group_id));
		}
	}

	public function insert_ra_group($obj)
	{
		return $this->ra_group_service->get_dao()->insert($obj);
	}

	public function update_ra_group($obj)
	{
		return $this->ra_group_service->get_dao()->update($obj);
	}

	public function get_ra_group_content($group_id='',$lang_id='en')
	{
		return $this->ra_group_service->get_rgc_dao()->get(array("group_id"=>$group_id,"lang_id"=>$lang_id));
	}

	public function insert_ra_group_content($obj)
	{
		return $this->ra_group_service->get_rgc_dao()->insert($obj);
	}

	public function update_ra_group_content($obj)
	{
		return $this->ra_group_service->get_rgc_dao()->update($obj);
	}

	public function get_ra_group_product_list_w_name($group_id)
	{
		return $this->ra_group_product_service->get_dao()->get_item_w_name($group_id, 'Ra_group_product_w_prodname_dto');
	}

	public function get_ra_group_product($where=array())
	{
		return $this->ra_group_product_service->get_dao()->get($where);
	}

	public function insert_ra_group_product($obj)
	{
		return $this->ra_group_product_service->get_dao()->insert($obj);
	}

	public function update_ra_group_product($obj)
	{
		return $this->ra_group_product_service->get_dao()->update($obj);
	}

	public function remove_ra_group_product($group_id)
	{
		return $this->ra_group_product_service->get_dao()->q_delete(array('ra_group_id'=>$group_id));
	}

	public function start_transaction()
	{
		$this->ra_group_service->get_dao()->trans_start();
	}

	public function end_transaction()
	{
		$this->ra_group_service->get_dao()->trans_complete();
	}

}

/* End of file upselling_model.php */
/* Location: ./system/application/models/upselling_model.php */