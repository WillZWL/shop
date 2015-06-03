<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Landpage_listing_service.php";

class Top_deals_service extends Landpage_listing_service
{
	private $type = 'TD';
	private $price_magin_service;

	public function __construct()
	{
		parent::__construct();
		include_once(APPPATH."libraries/service/Product_service.php");
		$this->product_service = new Product_service();
		include_once(APPPATH."libraries/service/Price_margin_service.php");
		$this->set_price_margin_service(new Price_margin_service());
	}

	public function get_price_margin_service()
	{
		return $this->price_magin_service;
	}

	public function set_price_margin_service($srv)
	{
		$this->price_magin_service = $srv;
	}

	public function get_count($catid="", $mode="", $platform ="")
	{
		if($catid == "")
		{
			return FALSE;
		}
		else
		{
			return $this->get_dao()->get_num_rows(array("catid"=>$catid,"type"=>'TD',"mode"=>$mode, "platform_id"=>$platform));
		}
	}

	public function get_top_deals($catid="", $rank="", $platform="")
	{
		if($catid == "")
		{
			return $this->get_dao()->get();
		}
		else
		{
			//$obj =  $this->get_dao()->get(array("catid"=>$catid,"type"=>'BS',"rank"=>$rank));
			$obj =  $this->get_dao()->get_item_by_rank($catid, "TD", $rank, $platform, "Product_list_w_name_dto");
			return $obj;
		}
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

	public function get_product_list_total($where=array(),$option = array("num_rows"=>1))
	{
		return $this->product_service->get_dao()->get_list_w_name($where,$option,  "Product_list_w_name_dto");
	}

	public function get_list_w_name($catid,$mode="M",$type="TD",$platform, $rtype="object")
	{
		return $this->get_dao()->get_list_w_pname($catid,$mode, $type, $platform,"Best_seller_prodname_dto",$rtype);
	}

	public function delete_bs($where=array())
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
			$manual = $this->get_list_w_name($catid, "M", "TD");
			$auto = $this->get_list_w_name($catid, "A", "TD");

			$ret = array();
			$added = array();
			foreach($manual as $obj)
			{
				$ret[] = array("prodid"=>$obj->get_selection(),"name"=>$obj->get_name(),"image"=>$obj->get_image(),"price"=>$obj->get_price(),"website_status"=>$obj->get_website_status(),"qty"=>$obj->get_quantity(),"web_qty"=>$obj->get_website_quantity());
				$added[] = $obj->get_selection();
			}

			$cnt = count($added);

			foreach($auto as $obj)
			{
				if($cnt < $this->get_limit() && !in_array($obj->get_selection,$added))
				{
					$ret[] = array("prodid"=>$obj->get_selection(),"name"=>$obj->get_name(),"image"=>$obj->get_image(),"price"=>$obj->get_price(),"website_status"=>$obj->get_website_status(),"qty"=>$obj->get_quantity(),"web_qty"=>$obj->get_website_quantity());
					$cnt++;
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
		return $this->product_service->get_top_deal_list_by_cat('', 0, $this->get_limit(), $platform);
	}

	protected function _get_product_list($filter_column = '', $cat_id = '', $platform='')
	{
		return $this->product_service->get_top_deal_list_by_cat($filter_column, $cat_id, $this->get_limit(),$platform);
	}

	public function init_data()
	{
		$this->get_price_margin_service()->refresh_margin_for_top_deal();
	}
}
?>