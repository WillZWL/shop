<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Landpage_listing_service.php";

class Skype_promotion_service extends Landpage_listing_service
{
	private $type = 'SP';
	//private $top_deals_service;
	private $best_seller_service;
	private $latest_arrivals_service;
	private $set_ra_prod_cat_service;

	public function __construct()
	{
		parent::__construct();
		include_once(APPPATH."libraries/service/Product_service.php");
		$this->product_service = new Product_service();
		include_once(APPPATH."libraries/service/Best_seller_service.php");
		$this->set_best_seller_service(new Best_seller_service());
		include_once(APPPATH."libraries/service/Latest_arrivals_service.php");
		$this->set_latest_arrivals_service(new Latest_arrivals_service());
	}

	public function set_best_seller_service($srv=NULL)
	{
		$this->best_seller_service = $srv;
	}

	public function get_best_seller_service()
	{
		return $this->best_seller_service;
	}

	public function set_latest_arrivals_service($srv=NULL)
	{
		$this->latest_arrivals_service = $srv;
	}

	public function get_latest_arrivals_service()
	{
		return $this->latest_arrivals_service;
	}

	public function get_count($catid="", $mode="", $platform)
	{
		if($catid == "")
		{
			return FALSE;
		}
		else
		{
			return $this->get_dao()->get_num_rows(array("catid"=>$catid,"type"=>'SP',"mode"=>$mode, "platform_id"=>$platform));
		}
	}

	public function get_skype_promotion($catid="", $rank="", $platform="")
	{
		if($catid === "")
		{
			return $this->get_dao()->get();
		}
		else
		{
			$obj =  $this->get_dao()->get_item_by_rank($catid, "SP", $rank, $platform, "Product_list_w_name_dto");

			return $obj;
		}
	}

	public function get_best_seller_list_by_cat($cat_id, $platform_id, $lang_id = "en")
	{
		$where["ll.type"] = "BS";
		$where["ll.catid"] = $cat_id;
		$where["ll.platform_id"] = $platform_id;
		$where["vpo.listing_status"] = "L";
		$where["vpo.price >"] = 0;
		$option["orderby"] = "ll.mode = 'M' DESC, ll.rank";
		$option["lang_id"] = $lang_id;
		$option["array_list"] = 1;
		return $this->get_dao()->get_item_w_name_list($where, $option);
	}

	public function get_promotion_landing_prod_list_by_cat($cat_id, $platform_id, $lang_id = "en", $limit = 6)
	{
		$where["ll.type"] = 'SP';
		if (is_array($cat_id))
		{
			$in_str = implode(', ', $cat_id);
			$where["ll.catid IN ({$in_str})"] = NULL;
		}
		else
		{
			$where["ll.catid"] = $cat_id;
		}
		$where["ll.platform_id"] = $platform_id;
		$where["vpo.listing_status"] = "L";
		$where["vpo.price >"] = 0;
		//$option["prod_type_id"] = "SC";
		$option["orderby"] = "ll.type = 'SP' DESC, ll.mode = 'M' DESC, ll.rank";
		$option["lang_id"] = $lang_id;
		$option["array_list"] = 1;
		$option["limit"] = $limit;
		$option["groupby"] = "selection";
		return $this->get_dao()->get_item_w_name_list($where, $option);
	}

	public function insert($obj)
	{
		return $this->get_dao()->insert($obj);
	}

	public function update($obj)
	{
		return $this->get_dao()->update($obj);
	}

    public function get_product_list($where=array(), $option=array())
    {
		return $this->product_service->get_dao()->get_list_w_name($where, $option, "Product_list_w_name_dto");
    }

	public function get_product_list_total($where=array(),$option=array())
	{
		return $this->product_service->get_dao()->get_list_w_name($where, $option,  "Product_list_w_name_dto");
	}

	public function get_list_w_name($catid, $mode="M", $type="SP", $platform, $rtype="object")
	{
		return $this->get_dao()->get_list_w_pname($catid, $mode, $type, $platform, "Best_seller_prodname_dto",$rtype);
	}

	public function delete_sp($where=array())
	{
		return $this->get_dao()->q_delete($where);
	}

	public function trans_start()
	{
		$this->get_dao()->trans_start();
	}

	public function trans_complete()
	{
		$this->get_dao()->trans_complete();
	}

	public function display_list($catid="")
	{
		if($catid == "")
		{
			return FALSE;
		}
		else
		{
			$ret = array();
			$added = array();

			if($manual = $this->get_list_w_name($catid, "M", "SP", PLATFORMID))
			{
				foreach($manual as $obj)
				{
					$ret[] = array("prodid"=>$obj->get_selection(),"name"=>$obj->get_name(),"image"=>$obj->get_image(),"price"=>$obj->get_price(),"website_status"=>$obj->get_website_status(),"qty"=>$obj->get_quantity(),"web_qty"=>$obj->get_website_quantity());
					$added[] = $obj->get_selection();
				}
			}

			$cnt = count($added);

			if($auto = $this->get_list_w_name($catid, "A", "SP", PLATFORMID))
			{
				foreach($auto as $obj)
				{
					if($cnt < $this->get_limit() && !in_array($obj->get_selection(),$added))
					{
						$ret[] = array("prodid"=>$obj->get_selection(),"name"=>$obj->get_name(),"image"=>$obj->get_image(),"price"=>$obj->get_price(),"website_status"=>$obj->get_website_status(),"qty"=>$obj->get_quantity(),"web_qty"=>$obj->get_website_quantity());
						$added[] = $obj->get_selection();
						$cnt++;
					}
				}
			}

			return $ret;
		}
	}

	public function get_type()
	{
		return $this->type;
	}


	protected function _get_product_list_for_home($platform="")
	{
		return FALSE;
		//return $this->product_service->get_best_seller_list_by_cat('', 0, 30, $this->get_limit(), $platform);
	}

	protected function _get_product_list($filter_column = '', $cat_id = '', $platform_id="")
	{
		$bs_list = $this->product_service->get_best_seller_list_by_cat($filter_column, $cat_id, 30, $this->get_limit(), $platform_id, 1);

		$la_list = $this->product_service->get_list_having_price(
						array('product.status'=>2, 'product.website_status'=>'I',
							'product.website_quantity >'=>0, 'price.platform_id'=>"$platform_id", 'product.'.$filter_column=>$cat_id, 'product_type.type_id'=>'SC'),
						array('orderby'=>'p.create_on DESC', 'limit'=>$this->get_limit()));

		$res = $bs_list;

		$picked = 0;
		foreach ($la_list as $la)
		{
			if($picked >= 10)
			{
				return $res;
			}
			else
			{
				$update = TRUE;
				foreach ($res as $r)
				{
					if($la->get_sku() == $r->get_sku())
					{
						$update = FALSE;
					}
				}
				if($update)
				{
					$res[] = $la;
					$picked++;
				}
			}
		}

		return $res;
	}
}
