<?php
defined('BASEPATH') OR exit('No direct script access allowed');

DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);

class Search extends PUB_Controller
{
	public function Search()
	{
		parent::PUB_Controller();
		$this->load->library('template');
		$this->load->helper(array('url','directory','tbswrapper'));
		$this->load->model("marketing/product_model");
		$this->load->model("marketing/category_model");
		$this->load->model("marketing/search_model");
		$this->load->model('website/news_subscription_model');
		$this->load->library('service/pagination_service');
		$this->load->library('service/affiliate_service');
		$this->load->library('service/price_website_service');
	}

	public function index()
	{
		$keyword = $this->input->get('w');
		switch(get_lang_id())
		{
			case "fr":
				$listing_status = array("I"=>"en stock", "O"=>"Rupture de Stock", "P"=>"Pr&eacute;-Commande", "A"=>"Arriv&eacute;e");
				$heading = "Désolé, aucuns résultats trouvés";
				$message = "<br>Aucuns résultats trouvés pour la recherche \"" . $keyword . "\", vous avez peut être saisi des mots incorrectement, ou êtes trop spécifique dans votre recherche.<br>
				Merci d’essayer d’utiliser des termes plus généraux. ";
				break;
			case "es":
				$listing_status = array("I"=>"en stock", "O"=>"sin stock", "P"=>"Reserva ahora", "A"=>"Disponible Próximamente");
				$heading = "Lo sentimos, no se ha encontrado ningún resultado";
				$message = "La búsqueda no ha encontrado ningún resultado para \"" . $keyword . "\", tal vez la búsqueda contenga errores o es muy específica.<br>
				Por favor inténtalo de nuevo utilizando un criterio menos restrictivo. ";
				break;
			case "ru":
				$listing_status = array("I"=>"в наличии", "O"=>"нет в наличии", "P"=>"предзаказ", "A"=>"скоро на складе");
				$heading = "К сожалению, нет ничего не найдено";
				$message = "К сожалению, мы не нашли соответствий \"" . $keyword . "\", Возможно слово пишется иначе, или Вы вводите слишком конректное описание.<br>
				Пожалуйста, попробуйте ввести альтернативный запрос, используя более широкое название, если возможно. ";
				break;
			case "en":
			default:
				$listing_status = array("I"=>"in stock", "O"=>"Out Of Stock", "P"=>"Pre-Order", "A"=>"Arriving");
				$heading = "Sorry, No Results Were Found";
				$message = "<br>Search was unable to find any results for \"" . $keyword . "\",you may have typed your word incorrectly, or are being too specific.<br>
Please try using a broader search phrase. ";
		}

		$this->affiliate_service->add_af_cookie($_GET);

		if(empty($keyword))
		{
			show_error($message, $heading);
		}

		$where['keyword'] = $data['keyword'] = $this->input->get('w');
		$where['lang_id'] = get_lang_id();
		$where['platform_id'] = $_SESSION['domain_platform']['platform_id'];

		if($this->input->get('brand_id'))
		{
			$where['brand_id'] = $this->input->get('brand_id');
		}

		if(!$data['sort'] = $sort = $this->input->get('sort'))
		{
			$data['sort'] = 'price_asc';
		}

		switch($this->input->get('sort'))
		{
			case 'price_asc':
				$option["orderby"] = "b.price ASC";
				break;
			case 'price_desc':
				$option["orderby"] = "b.price DESC";
				break;
			case 'latest_asc':
				$option["orderby"] = "b.create_on ASC";
				break;
			case 'latest_desc':
				$option["orderby"] = "b.create_on DESC";
				break;
			default:
				$option["orderby"] = "b.price ASC";
				break;
		}

		if(!$rpp = $this->input->get('rpp'))
		{
			$rpp = 12;
		}

		if(!$page = $this->input->get('page'))
		{
			$page = 1;
		}

		$where['limit'] = $rpp;
		$where['offset'] = $rpp * ($page-1);

		if($search_list = $this->search_model->get_product_search_list($where, $option))
		{
			foreach($search_list as $search_obj)
			{
				$sku_list[] = $search_obj->get_sku();
			}
			$obj_list = $this->price_service->get_listing_info_list($sku_list, PLATFORMID, get_lang_id());

			$option["num_rows"] = 1;
			$total = $this->search_model->get_product_search_list($where, $option);
		}

		$data["show_discount_text"] = $this->price_website_service->is_display_saving_message();

		if($obj_list)
		{
			foreach($obj_list AS $key=>$obj)
			{
				if($obj)
				{
					$product_list[$key]["sku"] = $obj->get_sku();
					$product_list[$key]["prod_name"] = $obj->get_prod_name();
					$product_list[$key]["listing_status"] = $obj->get_status();
					$product_list[$key]["stock_status"] =  $obj->get_status() == 'I'?$obj->get_qty()." ".$listing_status[$obj->get_status()]:$listing_status[$obj->get_status()];
					$product_list[$key]["price"] = $obj->get_price();
					$product_list[$key]["rrp_price"] = $obj->get_rrp_price();
					$product_list[$key]["discount"] = number_format(($obj->get_rrp_price() == 0 ? 0 : ($obj->get_rrp_price() - $obj->get_price()) / $obj->get_rrp_price() * 100), 0);
					$product_list[$key]["prod_url"] = $this->category_model->get_prod_url($obj->get_sku());
					$product_list[$key]["short_desc"] = $obj->get_short_desc();
					$product_list[$key]["image_ext"] = $obj->get_image_ext();
				}
			}
		}
		else
		{
			show_error($message, $heading);
		}

		$menu_info =  $this->gen_select_menu_items();
		$data['brand_result'] = $this->gen_select_menu_items();
		$data['product_list'] = $product_list;

		// pagination variable
		$data['total_result'] = $total;
		$data['curr_page'] = $page;
		$data['total_page'] = (int)ceil($total / $rpp);
		$data['rpp'] = $rpp;

		// meta info

		$this->template->add_title('Search Results for "' . $this->input->get('w') . '" | ValueBasket');
		$this->template->add_meta(array('name'=>'description','content'=>"ValueBasket is a global consumer electronics retailer bringing trusted and high quality brands and products to your doorstep at great prices."));
		$this->template->add_meta(array('name'=>'keywords','content'=>'DSLR, internchangeable-lens camera, bridge camera, compact digital camera, waterproof camera, flash gun, tripod, flash memory, cases, accessories, battery grip, SLR lens, interchangeable-camera lens, range finder lens, extenders, binoculars, filters, lens holders, camcorder, professional camcorder, surveillance,  smartphone, duo SIM, dual SIM, tough phone, bluetooth, iphone, ipod, ipad, macbook, apple tv, tablets, kindle, NAS, webcam, controllers, keyboards, mice, steering wheel, gaming headset, eyewear, CD SACD player, DACs, integrated amplifier, surroung processors, pre amplifier, surround power amplifier, surround receiver, speakers, subwoofers, projector, AV accessories, warranty'));

		$this->load_tpl('content', 'tbs_search', $data, TRUE);
	}

	public function search_by_ss()
	{
		$this->affiliate_service->add_af_cookie($_GET);

		$data = array();
		$this->load_tpl('content', 'tbs_searchspring_result', $data, TRUE);
	}

	public function ss_live_price($platform_id='')
	{
		if ($platform_id != '')
		{
			$json = $this->search_model->get_product_search_list_for_ss_live_price($platform_id, $this->input->get('sku'), TRUE);
			echo $json;
		}
	}

	public function gen_select_menu_items()
	{
		$where['keyword'] = $this->input->get('w');
		$where['cat_id'] = $this->input->get('catid');
		$where['lang_id'] = get_lang_id();
		$where['platform_id'] = $_SESSION['domain_platform']['platform_id'];
		$where['min_price'] = $this->input->get('min');
		$where['max_price'] = $this->input->get('max');

		$where['limit'] = NULL;
		$where['offset'] = NULL;

		$option['groupby'] = 'brand_name';
		$brand_list = $this->search_model->get_product_search_list($where, $option);
		if($brand_list)
		{
			foreach ($brand_list as $k=>$v)
			{
				$data[$k]['id'] = $v->get_brand_id();
				$data[$k]['name'] = $v->get_brand_name();
				$data[$k]['total'] = $v->get_num();
			}
		}

		return $data;
	}
}
?>
