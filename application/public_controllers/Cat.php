<?php
defined('BASEPATH') OR exit('No direct script access allowed');

DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);

class Cat extends PUB_Controller
{

	public function Cat()
	{
		parent::PUB_Controller();
		$this->load->library('template');
		$this->load->helper(array('url', 'tbswrapper'));
		$this->load->model('website/website_model');
		$this->load->model('marketing/product_model');
		$this->load->model('marketing/category_model');
		$this->load->model('marketing/banner_model');
		$this->load->library('service/affiliate_service');
		$this->load->library('service/price_website_service');
		$this->load->library('service/display_category_banner_service');
	}

	public function view($cat_id, $design_id = 0)
	{
		#SBF 3613 New testing designs
		$enable_disign_id = FALSE;
		if(PLATFORMID == "WEBGB" && $cat_id == 4)
		{
			if($design_id)
			{
				$enable_disign_id = $design_id;
			}
		}
$contentExperiment = <<<experiment
<!-- Google Analytics Content Experiment code -->
<script>function utmx_section(){}function utmx(){}(function(){var
k='58445089-4',d=document,l=d.location,c=d.cookie;
if(l.search.indexOf('utm_expid='+k)>0)return;
function f(n){if(c){var i=c.indexOf(n+'=');if(i>-1){var j=c.
indexOf(';',i);return escape(c.substring(i+n.length+1,j<0?c.
length:j))}}}var x=f('__utmx'),xx=f('__utmxx'),h=l.hash;d.write(
'<sc'+'ript src="'+'http'+(l.protocol=='https:'?'s://ssl':
'://www')+'.google-analytics.com/ga_exp.js?'+'utmxkey='+k+
'&utmx='+(x?x:'')+'&utmxx='+(xx?xx:'')+'&utmxtime='+new Date().
valueOf()+(h?'&utmxhash='+escape(h.substr(1)):'')+
'" type="text/javascript" charset="utf-8"><\/sc'+'ript>')})();
</script><script>utmx('url','A/B');</script>
<!-- End of Google Analytics Content Experiment code -->
experiment;
		if ((PLATFORMCOUNTRYID == "GB") && ($cat_id == 4))
			$this->template->add_js($contentExperiment, "print");

		$this->template->add_link("rel='canonical' href='".base_url()."/cat/view/$cat_id'");	# SEO

		if(!$cat_obj = $this->category_model->get_cat_info_w_lang(array("c.id"=>$cat_id, "ce.lang_id"=>get_lang_id(), "c.status"=>1), array("limit"=>1)))
		{
			$cat_obj = $this->category_model->get_cat_info_w_lang(array("c.id"=>$cat_id, "ce.lang_id"=>"en", "c.status"=>1), array("limit"=>1));
		}

		if(empty($cat_id) || !$cat_obj)
		{
			show_404('page');
		}

		$this->affiliate_service->add_af_cookie($_GET);

		$level = $cat_obj->get_level();

		$where['pr.platform_id'] = PLATFORMID;
		$where['p.status'] = 2;

		switch($level)
		{
			case 1:
				$where['p.cat_id'] = $cat_id;
				break;
			case 2:
				$where['p.sub_cat_id'] = $cat_id;
				break;
			case 3:
				$where['p.sub_sub_cat_id'] = $cat_id;
				break;
			default:
		}

		if($brand_id = $this->input->get('brand_id'))
		{
			$where['br.id'] = $brand_id;
		}

		if(!$data['sort'] = $sort = $this->input->get('sort'))
		{
			//$data['sort'] = 'pop_desc';
			//Category Page display, sort by priority of the sub_category
			$data['sort'] = 'priority_asc';

		}

		switch($data['sort'])
		{
			case 'pop_desc':
				$option["orderby"] = "pr.sales_qty desc";
				break;
			case 'price_asc':
				$option["orderby"] = "pr.price ASC";
				break;
			case 'price_desc':
				$option["orderby"] = "pr.price DESC";
				break;
			case 'latest_asc':
				$option["orderby"] = "sc.priority asc, p.create_on ASC";
				break;
			case 'latest_desc':
				$option["orderby"] = "sc.priority asc, p.create_on DESC";
				break;
			//#2580, sort by priority of the sub_category
			case 'priority_asc':
				$option["orderby"] = "sc.priority asc, pr.sales_qty desc";
				break;
			default:
				$option["orderby"] = "sc.priority asc, p.create_on DESC";
				break;
		}

		#SBF2580, push all the Arriving stock to bottom before Out of stock
		$option["orderby"] = "is_arr asc, " . $option["orderby"];

		#SBF1905, push all the Out of stock to bottom
		$option["orderby"] = "is_oos asc, " . $option["orderby"];



		if(!$rpp = $this->input->get('rpp'))
		{
			$rpp = 12;
		}

		if(!$page = $this->input->get('page'))
		{
			$page = 1;
		}

		$option['limit'] = $rpp;
		$option['offset'] = $rpp * ($page-1);

		$total = $this->category_model->get_website_cat_page_product_list($where, array("num_rows"=>1));
		if($sku_list = $this->category_model->get_website_cat_page_product_list($where, $option))
		{
			$obj_list = $this->product_model->get_listing_info_list($sku_list, PLATFORMID, get_lang_id(), array());
		}

		$data['show_discount_text'] = $this->price_website_service->is_display_saving_message();

		$show_404 = TRUE;
		if($obj_list)
		{
			$i = 1;
			// this flag is used to check against the list to make sure there is at least one available to be listed
			foreach($obj_list AS $key=>$obj)
			{
				if($obj)
				{
					$product_list[$key]["sku"] = $obj->get_sku();
					$product_list[$key]["prod_name"] = $obj->get_prod_name();
					$product_list[$key]["listing_status"] = $obj->get_status();
					$product_list[$key]["qty"] = $obj->get_qty();
					$product_list[$key]["price"] = $obj->get_price();
					$product_list[$key]["rrp_price"] = $obj->get_rrp_price();
					$product_list[$key]["discount"] = number_format(($obj->get_rrp_price() == 0 ? 0 : ($obj->get_rrp_price() - $obj->get_price()) / $obj->get_rrp_price() * 100), 0);
					$product_list[$key]["prod_url"] = $this->category_model->get_prod_url($obj->get_sku());
					$product_list[$key]["short_desc"] = $obj->get_short_desc();
					$product_list[$key]["image_ext"] = $obj->get_image_ext();
					if($i < 4)
					{
						$criteo_tag .= '&i'.$i.'='.$obj->get_sku();
						$i++;
					}
					$show_404 = FALSE;
				}
			}
		}
		if($show_404)
		{
			show_404('page');
		}

		// generate left filter menu
		unset($option['limit']);
		$option['limit'] = -1;
		$full_sku_list = $this->category_model->get_website_cat_page_product_list($where, $option);
		$data['cat_result'] = $this->get_cat_filter_grid_info($level, $full_sku_list);
		$data['brand_result'] = $this->get_brand_filter_grid_info($full_sku_list);
		$data["brand_id"] = $brand_id;

		$data['product_list'] = $product_list;
		$data['cat_obj'] = $cat_obj;
		$data['cat_name'] = $cat_obj->get_name();
		$data['level'] = $level;

		// pagination variable
		$data['total_result'] = $total;
		$data['curr_page'] = $page;
		$data['total_page'] = (int)ceil($total / $rpp);
		$data['rpp'] = $rpp;

		// url
		$parent_cat_id = $this->category_model->get_parent_cat_id($cat_id);
		$data['parent_cat_url'] = null;
		if($level > 1)
		{
			$data['parent_cat_url'] = $this->website_model->get_cat_url($parent_cat_id);
		}

		// breadcrumb
		switch(get_lang_id())
		{
			case "fr":
				$home_text = "Accueil";
				$home_text = "ValueBasket";	# SEO guru says change it all, #sbf 1572
				break;
			case "en":
			default:
				$home_text = "Home";
				$home_text = "ValueBasket";	# SEO guru says change it all, #sbf 1572
		}
		$data['breadcrumb'][0] = array($home_text=>base_url());

		switch($level)
		{
			case 3:
				if(!$cat_obj = $this->category_model->get_cat_info_w_lang(array("c.id"=>$cat_id, "ce.lang_id"=>get_lang_id(), "c.status"=>1), array("limit"=>1)))
				{
					$cat_obj = $this->category_model->get_cat_info_w_lang(array("c.id"=>$cat_id, "ce.lang_id"=>"en", "c.status"=>1), array("limit"=>1));
				}
				$cat_name['sscat'] = $cat_obj->get_name();
				$cat_url = $this->website_model->get_cat_url($cat_id);
				$data['breadcrumb'][3] = array($cat_name['sscat']=>$cat_url);
				$cat_id = $this->category_model->get_parent_cat_id($cat_id);
			case 2:
				if(!$cat_obj = $this->category_model->get_cat_info_w_lang(array("c.id"=>$cat_id, "ce.lang_id"=>get_lang_id(), "c.status"=>1), array("limit"=>1)))
				{
					$cat_obj = $this->category_model->get_cat_info_w_lang(array("c.id"=>$cat_id, "ce.lang_id"=>"en", "c.status"=>1), array("limit"=>1));
				}
				$cat_name['scat'] = $cat_obj->get_name();
				$cat_url = $this->website_model->get_cat_url($cat_id);
				$data['breadcrumb'][2] = array($cat_name['scat']=>$cat_url);
				$cat_id = $this->category_model->get_parent_cat_id($cat_id);
			case 1:
				if(!$cat_obj = $this->category_model->get_cat_info_w_lang(array("c.id"=>$cat_id, "ce.lang_id"=>get_lang_id(), "c.status"=>1), array("limit"=>1)))
				{
					$cat_obj = $this->category_model->get_cat_info_w_lang(array("c.id"=>$cat_id, "ce.lang_id"=>"en", "c.status"=>1), array("limit"=>1));
				}
				$cat_name['cat'] = $cat_obj->get_name();
				$cat_url = $this->website_model->get_cat_url($cat_id);
				$data['breadcrumb'][1] = array($cat_name['cat']=>$cat_url);
			default:
				break;
		}
		if($data['breadcrumb'])
		{
			ksort($data['breadcrumb']);
		}

		// meta tag
		$data['data']['lang_text'] = $this->_get_language_file();

		$meta_title = implode(' - ', $cat_name);
		$this->template->add_title($meta_title . ' | ValueBasket');
		$this->template->add_meta(array('name'=>'description','content'=>$cat_obj->get_description()));
		switch($level)
		{
			case 1:
				$meta_keyword = $data['data']['lang_text']['meta_keyword_1'];
				break;
			case 2:
				$meta_keyword = $data['data']['lang_text']['meta_keyword_2'];
				break;
			case 3:
				$meta_keyword = $data['data']['lang_text']['meta_keyword_3'];
				break;
		}
		$this->template->add_meta(array('name'=>'keywords','content'=>$meta_keyword));

		$data["tracking_data"] = array("category_name" => $cat_name['cat'], "category_id" => $cat_id);

		$enable_mediaforge_country = array('GB', 'AU', 'FR', 'ES');
		if(in_array(PLATFORMCOUNTRYID, $enable_mediaforge_country))
		{
#			mediaforge - added by SBF#1902
			$enable_mediaforge = true;
			if ($enable_mediaforge)
			{
				if (PLATFORMCOUNTRYID == 'GB') $account_no = 1038;
				if (PLATFORMCOUNTRYID == 'AU') $account_no = 1059 ;
				if (PLATFORMCOUNTRYID == 'FR') $account_no = 1411;  #SBF#2229
				if (PLATFORMCOUNTRYID == 'ES') $account_no = 1519; #SBF#2404

				$catid = urlencode($cat_obj->get_name());
#				function add_js($script, $type = 'import', $defer = FALSE, $position = "header")
				$this->template->add_js("//tags.mediaforge.com/js/$account_no?catID=$catid", "import", FALSE, "body");
			}

#			criteo - removed by SBF#1902
			$enable_criteo = false;
			if ($enable_criteo)
			{
				if($data['is_http'])
					$this->template->add_js("http://static.criteo.net/criteo_ld3.js");
				else
					$this->template->add_js("https://static.criteo.net/criteo_ld3.js");

				$criteo_script =
				'
					document.write(\'<div id=\"cto_se_7719983_ac\" style=\"display:none\">\');
					document.write(\'<div class=\"ctoWidgetServer\">http:\/\/valuebasketuk.widget.criteo.com\/pvx\/<\/div>\');
					document.write(\'<div class=\"ctoDataType\">sendEvent<\/div>\');
					document.write(\'<div class=\"ctoParams\">wi=7719983&pt1=3'.$criteo_tag.'<\/div>\');
					document.write(\'<\/div>\');
				';
				$this->template->add_js($criteo_script, 'embed');
			}
		}

# SBF #2284 add Tradedoubler variable js portion
		$this->tradedoubler_tracking_script_service->set_country_id(PLATFORMCOUNTRYID);
		$prod_list = array();
		foreach($obj_list AS $key=>$obj)
		{
			if ($obj)
			{
				$param_list["id"] = $obj->get_sku();
				$param_list["price"] = $obj->get_price();
				$param_list["currency"] = array_shift(array_keys($_SESSION["CURRENCY"]));
				$param_list["name"] = $obj->get_prod_name();
				$prod_list[] = $param_list;
			}
		}
		$td_variable_code = $this->tradedoubler_tracking_script_service->get_variable_code("category", $prod_list, "");
		$this->template->add_js($td_variable_code, "print");


		$category_banner = $this->display_category_banner_service->get_publish_banner($data['cat_obj']->get_cat_id(), 17, 1, PLATFORMCOUNTRYID, get_lang_id(), "PB");

		if($category_banner['graphic'][0])
		{
			$data['banner_cat_id'] = $data['cat_obj']->get_cat_id();
		}

		$data['enable_disign_id'] = $enable_disign_id;
		$this->load_tpl('content', 'tbs_cat', $data, TRUE);
	}

	public function get_cat_filter_grid_info($level, $sku_list)
	{
		$condition = "p.sku IN ('".implode("','", $sku_list)."')";
		$where[$condition] = null;
		$where['p.status'] = 2;
		$where['scex.lang_id'] = $where['sscex.lang_id'] = get_lang_id();

		switch($level)
		{
			case 1:
				$option['groupby'] = "p.sub_cat_id";
				$option['orderby'] = "sc.priority";
				break;
			case 2:
				$option['groupby'] = "p.sub_sub_cat_id";
				$option['orderby'] = "ssc.priority";
				break;
			case 3:
			default:
				return null;
		}

		if($rs = $this->category_model->get_cat_filter_grid_info($level, $where, $option))
		{
			foreach($rs as $key=>$val)
			{
				$rs[$key]['url'] = $this->website_model->get_cat_url($val['id']);
			}
		}
		return $rs;
	}

	public function get_brand_filter_grid_info($sku_list)
	{
		$condition = "p.sku IN ('".implode("','", $sku_list)."')";
		$where[$condition] = null;
		$where['p.status'] = 2;
		$option['groupby'] = "p.brand_id";
		$option['orderby'] = "br.brand_name";

		return $this->category_model->get_brand_filter_grid_info($where, $option);
	}

	function display_banner($cat_id = 0)
	{
		$category_banner = $this->display_category_banner_service->get_publish_banner($cat_id, 17, 1, PLATFORMCOUNTRYID, get_lang_id(), "PB");
		$banner = $category_banner;
		$banner_file_path = APPPATH."public_views/banner_publish/publish_".$banner["publish_key"].".php";
		if (file_exists($banner_file_path))
		{
			include APPPATH."public_views/banner_publish/publish_".$banner["publish_key"].".php";
		}
	}

}

?>
