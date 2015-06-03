<?php
defined('BASEPATH') OR exit('No direct script access allowed');

DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);

class Video_guide extends PUB_Controller
{

	public function Video_guide()
	{
		parent::PUB_Controller();
		$this->load->helper(array('url'));
		$this->load->model('marketing/product_model');
		$this->load->model('marketing/category_model');
		$this->load->model('marketing/best_seller_model');
		$this->load->model('marketing/latest_arrivals_model');
		$this->load->model('marketing/top_deals_model');
		$this->load->model('marketing/pick_of_the_day_model');
		$this->load->model('marketing/banner_model');
		$this->load->model('marketing/latest_video_model');
		$this->load->model('marketing/top_view_video_model');
		$this->load->model('marketing/best_selling_video_model');
		$this->load->library('service/affiliate_service');
		$this->load->helper('tbswrapper');
	}

	public function index()
	{
		$this->affiliate_service->add_af_cookie($_GET);

		if(!$this->input->get('limit'))
		{
			redirect(base_url()."video_guide/?limit=6&page=1");
		}

		if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443)
		{

			$secure_connection = true;
		}

		// determine display of YouTube or Vzaar
		//$data["src"] = $src = $secure_connection?"V":"Y";
		$data["src"] = $src = "Y";

		$where["video_src"] = $src;

		$data["cat"] = $this->category_model->get_list(array("level"=>"1", "id >"=>"0"));

		foreach($data["cat"] as $k=>$v)
		{
			$data["subcat"][$v->get_id()] = $this->category_model->get_display_list($v->get_id(),"cat","",PLATFORMID);
		}

		/*
		// ($filter_column = '',$cat_id = 0, $day_count = 0, $limit = 0, $platform, $type, $src)
		$data["best_selling_guide_video"] = (array)$this->product_model->get_best_selling_video_list_by_cat('', 0, 30, $this->input->get('limit'), PLATFORMID, 'G', $src);
		if(empty($data["best_selling_guide_video"]))
		{
			$data["best_selling_guide_video"] = $this->product_model->get_listed_video_list(
						array('product.status'=>2, 'product.website_status'=>'I', '(pr.price OR pr2.price) >'=>'0', 'pr2.listing_status'=>'L',
							'product.website_quantity >'=>0, 'platform_biz_var.selling_platform_id'=>PLATFORMID, 'product_video.type'=>'G', 'product_video.src'=>$src),
						array('orderby'=>'pv.create_on DESC', 'limit'=>'1'));
		}

		$data["best_selling_review_video"] =  (array)$this->product_model->get_best_selling_video_list_by_cat('', 0, 30, $this->input->get('limit'), PLATFORMID, 'R', $src);
		if(empty($data["best_selling_review_video"]))
		{
			$data["best_selling_review_video"] = $this->product_model->get_listed_video_list(
						array('product.status'=>2, 'product.website_status'=>'I', '(pr.price OR pr2.price) >'=>'0', 'pr2.listing_status'=>'L',
							'product.website_quantity >'=>0, 'platform_biz_var.selling_platform_id'=>PLATFORMID, 'product_video.type'=>'R', 'product_video.src'=>$src),
						array('orderby'=>'pv.create_on DESC', 'limit'=>'1'));
		}


		$data["latest_video"] = $this->product_model->get_listed_video_list(
						array('product.status'=>2, 'product.website_status'=>'I', '(pr.price OR pr2.price) >'=>'0', 'pr2.listing_status'=>'L',
							'product.website_quantity >'=>0, 'platform_biz_var.selling_platform_id'=>PLATFORMID, 'product_video.src'=>$src),
						array('GROUP BY'=>'pv.ref_id', 'orderby'=>'pv.create_on DESC'));

		*/
		$data['latest_video'] = $this->get_latest_videos("G");
		$data['best_selling_guide_video'] = $this->get_best_selling_videos('G');
		$data['best_selling_review_video'] = $this->get_best_selling_videos('R');

		if($data['best_selling_review_video'])
		{
			if(!$data['review_prod_cont_obj'] = $this->product_model->product_service->get_content($data['best_selling_review_video'][0]->get_sku(), get_lang_id()))
			{
				$data['review_prod_cont_obj'] = $this->product_model->product_service->get_content($data['best_selling_review_video'][0]->get_sku(), 'en');
			}
		}

		$where["platform_id"] = PLATFORMID;
		$where["min_price"] = $this->input->get('min');
		$where["max_price"] = $this->input->get('max');
		$where["cat_id"] = $this->input->get('cat');
		$where["sub_cat_id"] = $this->input->get('scat');
		$where["brand"] = $this->input->get('brand');

		switch ($this->input->get('sort'))
		{
			case "pricelow":
				$option['orderby'] = 'IF(pr2.price>0, pr2.price, pr.price*ex.rate) ASC';
				break;
			case "pricehigh":
				$option['orderby'] = 'IF(pr2.price>0, pr2.price, pr.price*ex.rate) DESC';
				break;
			case "rating":
				// not yet implement
				//$option['orderby'] = '';
				break;
			case "bestselling":
				$option['orderby'] = 'a.sold_amount DESC';
				break;
			case "brand":
				$option['orderby'] = 'br.brand_name ASC';
				break;
			case "newvideos":
				$option['orderby'] = 'pv.create_on DESC';
				break;
			default:
				$option['orderby'] = 'pv.create_on DESC';
		}

		switch ($this->input->get('filter'))
		{
			case "all":
				break;
			case "skypecert":
				$where['prod_type'] = 'SC';
				break;
			case "review":
				$option['prod_video'] = 1;
				break;
			case "bundles":
				$where['with_bundle'] = 1;
				break;
			default:
		}

		if(!($where['limit'] = $data['limit'] = $this->input->get('limit')))
		{
			$where['limit'] = $data['limit'] = 6;
		}
		$data['offset'] = $where['offset'] = $this->input->get('limit')*($this->input->get('page')-1);
		$data["page"] = $this->input->get("page");

		$data["display_list"] = $this->product_model->get_display_video_list($where, $option);

		$option["num_rows"] = 1;
		$data["total_rows"] = $this->product_model->get_display_video_list($where, $option);

		$data["menu_info"] = $this->gen_select_menu_items();


		$this->load_template("tbs_video_guide.php", $data);
		//$this->load_view('video_guide.php', $data);
	}

	public function gen_select_menu_items()
	{
		$where["platform_id"] = PLATFORMID;
		$where['limit'] = NULL;
		$where['offset'] = NULL;

		if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443)
		{

			$secure_connection = true;
		}

		$where["video_src"] = "Y";
		$where["min_price"] = $this->input->get('min');
		$where["max_price"] = $this->input->get('max');

		$option['groupby'] = 'cat_name';
		$cat_list = $this->product_model->get_display_video_list($where, $option);

		if($cat_list)
		{
			foreach ($cat_list as $k=>$v)
			{
				$data['cat_list'][$v->get_cat_id()] = $v->get_count();
			}
		}

		$option['groupby'] = 'sub_cat_name';
		$sub_cat_list = $this->product_model->get_display_video_list($where, $option);

		if($sub_cat_list)
		{
			foreach ($sub_cat_list as $k=>$v)
			{
				$data['sub_cat_list'][$v->get_sub_cat_id()] = $v->get_count();
			}
		}

		$option['groupby'] = 'brand_name';
		$brand_list = $this->product_model->get_display_video_list($where, $option);

		if($brand_list)
		{
			foreach ($brand_list as $k=>$v)
			{
				$data['brand_list'][$v->get_cat_id()][$v->get_sub_cat_id()][$v->get_brand_name()] = $v->get_count();
			}
		}
		return $data;
	}

	public function get_latest_videos($video_type="")
	{
		$src = 'Y';

		if($mlist = $this->latest_video_model->get_list_w_name(0,'M','LV',$video_type,PLATFORMID,$src))
		{
			foreach($mlist as $marr)
			{
				$rlist[] = $marr->get_ref_id();
			}
		}
		$rlist = array();

		if($alist = $this->latest_video_model->get_list_w_name(0,'A','LV',$video_type,PLATFORMID,$src))
		{
			foreach($alist as $aarr)
			{
				if(!in_array($aarr->get_ref_id(),$rlist))
				{
					$mlist[] = $aarr;
					$rlist[] = $aarr->get_ref_id();
				}
				else
				{
					continue;
				}
			}
		}
		return $mlist;
	}

	public function get_best_selling_videos($video_type='')
	{
		$src = 'Y';

		$rlist = array();
		if($mlist = $this->best_selling_video_model->get_list_w_name(0,'M','BV',$video_type,PLATFORMID,$src))
		{
			foreach($mlist as $marr)
			{
				$rlist[] = $marr->get_ref_id();
			}
		}

		if($alist = $this->best_selling_video_model->get_list_w_name(0,'A','BV',$video_type,PLATFORMID,$src))
		{
			foreach($alist as $aarr)
			{
				if(!in_array($aarr->get_ref_id(),$rlist))
				{
					$mlist[] = $aarr;
					$rlist[] = $aarr->get_ref_id();
				}
				else
				{
					continue;
				}
			}
		}

		if($alist = $this->get_latest_videos($video_type))
		{
			foreach($alist as $aarr)
			{
				if(!in_array($aarr->get_ref_id(),$rlist))
				{
					$mlist[] = $aarr;
					$rlist[] = $aarr->get_ref_id();
				}
				else
				{
					continue;
				}
			}
		}
		return $mlist;
	}
}

?>
