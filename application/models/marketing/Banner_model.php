<?php


class Banner_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('service/banner_service');
		$this->load->library('service/category_service');
	}

	public function get_list($level,$parent="")
	{
		return $this->banner_service->get_dao()->get_list_with_name($level,$parent);
	}

	public function get_banner_list($where=array(),$option=array())
	{
		return $this->banner_service->get_banner_list($where,$option);
	}

	public function get_banner_obj($cat_id="", $type="", $usage="")
	{
		if($cat_id=="" || $type =="" || $usage=="")
		{
			return $this->banner_service->get_dao()->get();
		}
		else
		{
			return $this->banner_service->get_dao()->get(array("cat_id"=>$cat_id,"type"=>$type,"usage"=>$usage));
		}
	}

	public function add_banner($obj,$action)
	{
		if($action == "insert")
		{
			return $this->banner_service->get_dao()->insert($obj);
		}
		else if($action == "update")
		{
			return  $this->banner_service->get_dao()->update($obj);
		}
		else
		{
			return FALSE;
		}
	}

	public function clear_flash($catid="", $type="", $usage="")
	{
		if($catid == "" || $type =="" || $usage == "")
		{
			return FALSE;
		}
		else
		{
			return $this->banner_service->update_flash($catid,$type,$usage);
		}
	}

	public function update_status($cat_id,$status)
	{
		return $this->banner_service->get_dao()->update_status($cat_id,$status);
	}

	public function get_cat($catid="")
	{
		if($catid == "")
		{
			return FALSE;
		}
		else
		{
			return $this->category_service->get_dao()->get(array("id"=>$catid));
		}
	}

	public function get_banner($catid,$type)
	{
		return $this->banner_service->get_banner_for_landpage($catid,$type);
	}

	public function get_banner_for_landpage($catid,$type)
	{
		$obj = $this->category_service->get_dao()->get(array("id"=>$catid));
		$image = "";
		$file = "";

		$bobj = $this->banner_service->get_dao()->get(array("cat_id"=>$catid,"type"=>$type,"usage"=>"PB"));
		if(!empty($bobj))
		{
			$check = 0;
			$flash = $bobj->get_flash_file();
			$image = $bobj->get_image_file();

			if($flash != "" && file_exists($this->config->item("banner_publish_path").$flash))
			{
				$ret["flash"] = $flash;
				$check++;
			}
			if($image != "" && file_exists($this->config->item("banner_publish_path").$image))
			{
				$ret["image"] = $image;
				$check++;
			}
			if($check)
			{
				return array("flash"=>$flash,"image"=>$image, "link"=>$bobj->get_link(),"link_type"=>$bobj->get_link_type());
			}
			else
			{
				if($obj->get_level() > 1)
				{
					return $this->get_banner_for_landpage($obj->get_parent_cat_id(),$type);
				}
				else
				{
					return array("image"=>"cat_".$type."_default.jpg","flash"=>"","link"=>"","link_type"=>"E");
				}
			}
		}
		else
		{
			if($obj->get_level() > 1)
			{
				return $this->get_banner_for_landpage($obj->get_parent_cat_id(),$type);
			}
			else
			{
				return array("image"=>"cat_".$type."_default.jpg","flash"=>"","link_type"=>"E");
			}
		}
	}
}

