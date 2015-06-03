<?php
defined('BASEPATH') OR exit('No direct script access allowed');

DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);

class Brand extends PUB_Controller
{

	public function Brand()
	{
		parent::PUB_Controller();
		$this->load->helper(array('url'));
		$this->load->model('marketing/product_model');
		$this->load->model('marketing/category_model');
		$this->load->model('marketing/best_seller_model');
		$this->load->model('marketing/banner_model');
	}

	public function view($catid="",$brand="")
	{
		if($catid == "" || $brand == "")
		{
			$data=array();
			show_404('page');
		}
		else
		{
			$data["brand_obj"] = $this->product_model->get_brand($brand);

			if(!$data["brand_obj"])
			{
				show_404('page');
			}
			else
			{
				$data["catlist"] = $catlist = $this->category_model->get_display_list($catid,"cat",$brand,PLATFORMID);
				if(count($catlist))
				{
					foreach($catlist as $obj)
					{
						$bs[$obj->get_id()] = array("list"=>$this->best_seller_model->get_display_list($obj->get_id()),"name"=>$obj->get_name());
					}
				}

				$page =  $this->input->get('page') * 1;
				if($page <= 0)
				{
						$page = 1;
				}
				$option["limit_from"] = $page - 1;


				switch($this->input->get('sort'))
				{
					case 'price':
					$option["sort"] = "pr.price";
					$option["order"] = $this->input->get('order');
					break;

					case 'colour':
					$option["sort"] = 'p.colour_id';
									$option["order"] = $this->input->get('order');
									break;

					default:
					break;
				}
				$data["prodcnt"] = $prodcnt = $this->product_model->get_list_by_brand_cat_cnt($catid,$brand,$where,$option);

				if($this->input->get('displayqty') * 1 > 0)
				{
						$option["limit"] = $this->input->get('displayqty') * 1;
				}
				else
				{
						$option["limit"] = 10;
				}

				if($page  > ceil($prodcnt / $option["limit"]))
				{
						$page = ceil($prodcnt / $option["limit"]);
						$option["limit_from"] = $page;
				}
				$data["page"] = $page;
				$data["rpp"] = $option["limit"];
				$data["prodlist"] = $catlist = $this->product_model->get_list_by_brand_cat($catid,$brand,$where,$option);
				$data["brandlist"] = $this->category_model->get_display_list($catid,"brand",$brand,PLATFORMID);
				$data["best_seller"] = $bs;

				$data["colour_list"] = $this->category_model->get_colour_code();
				$data["display_catlist"] = $this->category_model->get_display_catlist($catid);
				$data["catid"] = $catid;
				$data["brand"] = $brand;
				$data["cat_obj"] = $this->category_model->get_cat_obj($catid);

				$this->load_view('brand_index.php', $data);
			}
		}
	}

}

?>
