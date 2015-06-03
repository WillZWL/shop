<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "landpage_video_listing_service.php";

class Best_selling_video_service extends Landpage_video_listing_service
{
	private $type = 'BV';
	//private $latest_video_service;

	public function __construct()
	{
		parent::Landpage_video_listing_service();
		include_once(APPPATH."libraries/service/Product_service.php");
		$this->product_service = new Product_service();
		include_once(APPPATH."libraries/service/Latest_video_service.php");
		$this->set_latest_video_service(new Latest_video_service());

	}

	public function set_latest_video_service($srv=NULL)
	{
		$this->latest_video_service = $srv;
	}

	public function get_latest_video_service()
	{
		return $this->latest_video_service;
	}

	public function get_count($catid="", $mode="", $platform="", $type="", $src="")
	{
		if($catid == "")
		{
			return FALSE;
		}
		else
		{
			return $this->get_dao()->get_num_rows(array("catid"=>$catid,"listing_type"=>'BV',"mode"=>$mode, "platform_id"=>$platform, "video_type"=>$type, "src"=>$src));
		}
	}

	public function get_best_selling_video($catid="", $rank="", $v_type="", $platform="", $src="")
	{
		if($catid === "")
		{
			return $this->get_dao()->get();
		}
		else
		{
			//$obj =  $this->get_dao()->get(array("catid"=>$catid,"type"=>'TV',"rank"=>$rank));
			$obj =  $this->get_dao()->get_item_by_rank($catid, "BV", $v_type, $rank, $platform, $src, "Video_list_w_name_dto");

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

	public function get_video_list($where=array(), $option=array())
	{
		return $this->product_service->get_dao()->get_video_list_w_name($where, $option);
	}

	public function get_video_list_total($where=array(),$option=array())
	{
		return $this->product_service->get_dao()->get_video_list_w_name($where, $option);
	}

	public function get_list_w_name($catid, $mode="M", $l_type="TV", $v_type="", $platform="", $src="", $rtype="object")
	{
		return $this->get_dao()->get_list_w_pname($catid, $mode, $l_type, $v_type, $platform, $src, "top_view_video_prodname_dto",$rtype);
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

	public function display_list($catid="", $type="", $src="")
	{
		if($catid == "")
		{
			return FALSE;
		}
		else
		{
			$ret = array();
			$added = array();

			if($manual = $this->get_list_w_name($catid, "M", "BV", $type, PLATFORMID, $src))
			{
				foreach($manual as $obj)
				{
					$ret[] = array("ref_id"=>$obj->get_ref_id(),"sku"=>$obj->get_sku(),"video_type"=>$obj->get_video_type(),"video_src"=>$obj->get_src(),"name"=>$obj->get_name(),"image"=>$obj->get_image(),"price"=>$obj->get_price(),"website_status"=>$obj->get_website_status(),"qty"=>$obj->get_quantity(),"web_qty"=>$obj->get_website_quantity());
					$added[] = $obj->get_ref_id();
				}
			}

			$cnt = count($added);

			if($auto = $this->get_list_w_name($catid, "A", "BV", $type, PLATFORMID, $src))
			{
				foreach($auto as $obj)
				{
					if($cnt < $this->get_limit() && !in_array($obj->get_ref_id(),$added))
					{
						$ret[] = array("ref_id"=>$obj->get_ref_id(),"sku"=>$obj->get_sku(),"video_type"=>$obj->get_video_type(),"video_src"=>$obj->get_src(),"name"=>$obj->get_name(),"image"=>$obj->get_image(),"price"=>$obj->get_price(),"website_status"=>$obj->get_website_status(),"qty"=>$obj->get_quantity(),"web_qty"=>$obj->get_website_quantity());
						$cnt++;
					}
				}
			}
			$latest_video_list = $this->get_latest_video_service()->display_list($catid, $type, $src);
			$ret = array_merge($ret, $latest_video_list);

			$vzaar_backup_list = $this->vzaar_display_list($catid, $type);
			return array_merge($ret, $vzaar_backup_list);

			//return $ret;
		}
	}

	public function vzaar_display_list($catid="", $type="")
	{
		if($catid == "")
		{
			return FALSE;
		}
		else
		{
			$ret = array();
			$added = array();

			if($manual = $this->get_list_w_name($catid, "M", "BV", $type, PLATFORMID, "V"))
			{
				foreach($manual as $obj)
				{
					$ret[] = array("ref_id"=>$obj->get_ref_id(),"sku"=>$obj->get_sku(),"video_type"=>$obj->get_video_type(),"video_src"=>$obj->get_src(),"name"=>$obj->get_name(),"image"=>$obj->get_image(),"price"=>$obj->get_price(),"website_status"=>$obj->get_website_status(),"qty"=>$obj->get_quantity(),"web_qty"=>$obj->get_website_quantity());
					$added[] = $obj->get_ref_id();
				}
			}

			$cnt = count($added);

			if($auto = $this->get_list_w_name($catid, "A", "BV", $type, PLATFORMID, "V"))
			{
				foreach($auto as $obj)
				{
					if($cnt < $this->get_limit() && !in_array($obj->get_ref_id(),$added))
					{
						$ret[] = array("ref_id"=>$obj->get_ref_id(),"sku"=>$obj->get_sku(),"video_type"=>$obj->get_video_type(),"video_src"=>$obj->get_src(),"name"=>$obj->get_name(),"image"=>$obj->get_image(),"price"=>$obj->get_price(),"website_status"=>$obj->get_website_status(),"qty"=>$obj->get_quantity(),"web_qty"=>$obj->get_website_quantity());
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
		return $this->product_service->get_best_selling_video_list_by_cat('', 0, 30, $this->get_limit(), $platform);
	}

	protected function _get_video_list($filter_column = '', $cat_id = '', $platform = '', $video_type = '')
	{
		return $this->product_service->get_best_selling_video_list_by_cat('', 0, 30, $this->get_limit(), $platform, $video_type);
	}
}

/* End of file best_selling_video_service.php */
/* Location: ./app/libraries/service/Top_view_video.php */