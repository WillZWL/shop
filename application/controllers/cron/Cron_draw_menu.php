<?php
class Cron_draw_menu extends MY_Controller
{
	private $app_id="CRN0008";
	function __construct()
	{

		// load controller parent
		parent::__construct();
		$this->load->model('website/home_model');
		$this->load->model('marketing/menu_model');
		$this->load->model('marketing/product_model');
		$this->load->model('marketing/category_model');
		$this->load->model('mastercfg/language_model');
		$this->load->model('mastercfg/selling_platform_model');
		$this->load->library('service/context_config_service');
		$this->load->library('service/platform_biz_var_service');
		$this->load->helper('url');

	}

	function cron_multilanguage_menu()
	{
		$eol = "\n";
		$tab = "\t";
		$platform_list = $this->selling_platform_model->get_list(array("type"=>"WEBSITE", "status"=>1), array("limit"=>-1));
		$language_list = $this->language_model->get_list(array("status"=>1), array("limit"=>-1));
		foreach($language_list AS $lang_obj)
		{
			$lang_id = $lang_obj->get_id();
			foreach($platform_list as $platform_obj)
			{
				$platform_id = $platform_obj->get_id();
				$pbv_obj = $this->platform_biz_var_service->get_platform_biz_var($platform_id);
				$country_id = $pbv_obj->get_platform_country_id();
				// $base_url = $this->context_config_service->value_of("default_url")."/".$lang_id."_".$country_id;
				$base_url = "/".$lang_id."_".$country_id;
				$cat_id_css_map = array(1=>"camera", 2=>"lens", 3=>"camcorders", 4=>"phones", 5=>"apple", 6=>"tablets", 7=>"headphones", 8=>"computing", 9=>"audio", 10=>"warranty");

				$cat_list = $this->category_model->get_cat_ext_list(array("lang_id"=>$lang_id), array("limit"=>-1));
				if($cat_list)
				{
					$cat_name_map = array();
					foreach($cat_list as $obj)
					{
						$cat_name_map[$obj->get_cat_id()] = $obj->get_name();
					}
				}

				$cat_arr = $cat_tree = array();
				$cat_arr = $this->category_model->get_listed_cat($platform_id);
				foreach($cat_arr as $val)
				{
					if($val['sub_sub_cat_id'])
					{
						$cat_tree[$val['cat_id']][$val['sub_cat_id']][$val['sub_sub_cat_id']] = null;
					}
					else
					{
						$cat_tree[$val['cat_id']][$val['sub_cat_id']] = null;
					}
				}

				$content = "";
				$mobileContent = "";
				//$content .= "<div id='navigation'>".$eol;
				$content .= $tab."<ul>".$eol;

				foreach($cat_id_css_map as $cat_id=>$css_name)
				{
					if($cat_ext_list = $this->category_model->get_cat_ext_list(array("cat_id"=>$cat_id, "lang_id"=>$lang_id), array("limit"=>-1)))
					{
						foreach($cat_ext_list as $cat_obj)
						{
							$cat_url = htmlentities($base_url.$this->website_service->get_cat_url($cat_obj->get_cat_id(), TRUE));
							$no_right_margin = ($cat_id == 10)?" class='no_right_margin'":"";
							$content .= $tab.$tab."<li id='nav-" . $css_name . "' " . $no_right_margin . "><a href='" . $cat_url . "' title='" . $cat_obj->get_name() ."'><ins class='fixpng'>&nbsp;</ins><span>" . $cat_obj->get_name() . "</span></a></li>".$eol;
							$mobileContent .= $tab . $tab . "<li><a href='" . $cat_url . "' title='" . $cat_obj->get_name() ."'>" . $cat_obj->get_name() . "</a>" . $eol;
							$mobileContent .= $tab . $tab . $tab . "<button><i>&nbsp;</i></button>" . $eol;
							$mobileContent .= $tab . $tab . $tab . $tab . "<ul>" . $eol;
							if($cat_tree)
							{
								foreach($cat_tree[$cat_id] as $sub_cat_id => $sub_sub_cat)
								{
									$sub_cat_link = htmlentities($base_url.$this->website_service->get_cat_url($sub_cat_id, TRUE));
									$mobileContent .= $tab . $tab . $tab . $tab . $tab . "<li><a href=\"" . $sub_cat_link . "\" title=\"" . $cat_name_map[$sub_cat_id] . "\">" . $cat_name_map[$sub_cat_id] . "</a></li>" . $eol;
								}
							}
							$mobileContent .= $tab . $tab . $tab . $tab . "</ul></li>" . $eol;
//							$mobileContent .= $tab . $tab . "<li><a href='" . $cat_url . "' title='" . $cat_obj->get_name() ."'>" . $cat_obj->get_name() . "</a></li>" . $eol;
						}
					}
				}
				$content .= $tab."</ul>".$eol;
				$content .= $tab."</div>".$eol;

				$content .= "<div id='sub-navigation' class='box-shadow-4'>".$eol;
				$content .= $tab."<div class='category'>".$eol;
				$content .= $tab.$tab."<img src='/resources/images/navigation/nav-01.png' alt='' class='fixpng' />".$eol;
				$content .= $tab.$tab."<span>" . $cat_name_map[1] . "</span>".$eol;
				$content .= $tab."</div>".$eol;
				if($cat_tree)
				{
					foreach($cat_tree as $cat_id=>$sub_cat)
					{
						$content .= $tab."<div class='nav-" . $cat_id_css_map[$cat_id] . "'>".$eol;
						$content .= $tab.$tab."<ul>".$eol;

						$i = 1;
						$content .= $tab.$tab.$tab."<li>".$eol;
						foreach($sub_cat as $sub_cat_id=>$sub_sub_cat)
						{
							$sub_cat_url = htmlentities($base_url.$this->website_service->get_cat_url($sub_cat_id, TRUE));
							if(!$cat_name_map[$sub_cat_id])
							{
								$empty_name[$lang_id][] = $sub_cat_id;
							}
							$content .= $tab.$tab.$tab.$tab."<p><a href='" . $sub_cat_url . "' title='" .  $cat_name_map[$sub_cat_id] . "'>" . $cat_name_map[$sub_cat_id] . "</a></p>".$eol;

							if($sub_sub_cat)
							{
								$content .= $tab.$tab.$tab.$tab."<dl>".$eol;
								foreach($sub_sub_cat as $sub_sub_cat_id=>$val)
								{
									if($sub_sub_cat_id > 0)
									{
										if(!$cat_name_map[$sub_sub_cat_id])
										{
											$empty_name[$lang_id][] = $sub_sub_cat_id;
										}
										$sub_sub_cat_url = htmlentities($base_url.$this->website_service->get_cat_url($sub_sub_cat_id, TRUE));
										$content .= $tab.$tab.$tab.$tab.$tab."<dd><a href='" . htmlspecialchars($sub_sub_cat_url, ENT_QUOTES) . "' title='" . $cat_name_map[$sub_sub_cat_id] . "'>" . $cat_name_map[$sub_sub_cat_id] . "</a></dd>".$eol;
									}
								}
								$content .= $tab.$tab.$tab.$tab."</dl>".$eol;
							}

							if($i % 3 == 0)
							{
								$content .= $tab.$tab.$tab."</li>".$eol;
								$content .= $tab.$tab.$tab."<li>".$eol;
							}
							$i++;
						}
						$content .= $tab.$tab.$tab."</li>".$eol;
						$content .= $tab.$tab."</ul>".$eol;
						$content .= $tab."</div>".$eol;
					}
				}
				$content .= "</div>".$eol;

				$mobile_menu_path = APPPATH . "mobile_views/template/menu/" . $lang_id;
				$mobile_menu_file = $mobile_menu_path . "/menu_" . strtolower($platform_id) . ".html";
				if (!file_exists($mobile_menu_path))
				{
					mkdir($mobile_menu_path, 0755, true);
					chown($mobile_menu_path, "apache");
					chgrp($mobile_menu_path, "users");
				}
				file_put_contents($mobile_menu_file, $mobileContent);
				chown($mobile_menu_file, "apache");
				chgrp($mobile_menu_file, "users");
				chmod($mobile_menu_file, 0664);

				$menu_path = APPPATH . "public_views/template/menu/" . $lang_id;
				$menu_file = $menu_path . "/menu_" . strtolower($platform_id) . ".html";
				if (!file_exists($menu_path))
				{
					mkdir($menu_path, 0755, true);
					chown($menu_path, "apache");
					chgrp($menu_path, "users");
				}
				$small_menu = "<div id='navigation' class='small'>".$eol.$content;

				file_put_contents($menu_file, $small_menu);
				chown($menu_file, "apache");
				chgrp($menu_file, "users");
				chmod($menu_file, 0664);

				$menu_file = APPPATH."public_views/template/menu/".$lang_id."/menu_big_" . strtolower($platform_id) . ".html";
				$normal_menu = "<div id='navigation'>".$eol.$content;
				file_put_contents($menu_file, $normal_menu);
				chown($menu_file, "apache");
				chgrp($menu_file, "users");
				chmod($menu_file, 0664);

				// generate footer
				$footer_content = "";
				if($cat_tree)
				{
					$footer_content .= "<ul>".$eol;
					foreach($cat_tree as $cat_id=>$sub_cat)
					{
						$cat_url = $base_url.htmlentities($this->website_service->get_cat_url($cat_id, TRUE));
						$footer_content .= $tab."<li>".$eol;
						$footer_content .= $tab.$tab."<p><a href='" . $cat_url . "' title='" . $cat_name_map[$cat_id] . "'>" . $cat_name_map[$cat_id] . "</a></p>".$eol;
						/*
						$footer_content .= $tab.$tab."<dl>".$eol;
						foreach($sub_cat as $sub_cat_id=>$sub_sub_cat)
						{
							$sub_cat_url = $base_url.$this->website_service->get_cat_url($sub_cat_id, TRUE);
							$footer_content .= $tab.$tab.$tab."<dd><a href='" . $sub_cat_url . "' title='" . $cat_name_map[$sub_cat_id] . "'>" . $cat_name_map[$sub_cat_id] . "</a></dd>".$eol;
						}

						$footer_content .= $tab.$tab."</dl>".$eol;
						*/
						$footer_content .= $tab."</li>".$eol;
					}
					$footer_content .= "</ul>".$eol;
				}

				$footer_menu_file = APPPATH."public_views/template/menu/".$lang_id."/footer_menu_" . strtolower($platform_id) . ".html";
				file_put_contents($footer_menu_file, $footer_content);
				chown($menu_file, "apache");
				chgrp($menu_file, "users");
				chmod($menu_file, 0664);
			}
		}
	}

	function cron_menu()
	{
		$eol = "\n";
		$tab = "\t";
		$platform_list = $this->selling_platform_model->get_selling_platform_w_lang_id(array("sp.type"=>"WEBSITE", "sp.status"=>1), array("limit"=>-1));
		foreach($platform_list as $platform_obj)
		{
			$platform_id = $platform_obj->get_id();
			$lang_id = $platform_obj->get_lang_id();
			$cat_id_css_map = array(1=>"camera", 2=>"lens", 3=>"camcorders", 4=>"phones", 5=>"apple", 6=>"tablets", 7=>"headphones", 8=>"computing", 9=>"audio", 10=>"warranty");

			$cat_list = $this->category_model->get_cat_ext_list(array("lang_id"=>$platform_obj->get_lang_id()), array("limit"=>-1));
			if($cat_list)
			{

				foreach($cat_list as $obj)
				{
					$cat_name_map[$obj->get_cat_id()] = $obj->get_name();
				}
			}

			$cat_arr = $cat_tree = array();
			$cat_arr = $this->category_model->get_listed_cat($platform_id);
			//$cat_arr = $this->category_model->get_full_cat_list();

			foreach($cat_arr as $val)
			{
				if($val['sub_sub_cat_id'])
				{
					$cat_tree[$val['cat_id']][$val['sub_cat_id']][$val['sub_sub_cat_id']] = null;
				}
				else
				{
					$cat_tree[$val['cat_id']][$val['sub_cat_id']] = null;
				}
			}

			$content = "";
			//$content .= "<div id='navigation'>".$eol;
			$content .= $tab."<ul>".$eol;

			foreach($cat_id_css_map as $cat_id=>$css_name)
			{
				if($cat_ext_list = $this->category_model->get_cat_ext_list(array("cat_id"=>$cat_id, "lang_id"=>$platform_obj->get_lang_id()), array("limit"=>-1)))
				{
					foreach($cat_ext_list as $cat_obj)
					{
						$cat_url = $this->website_service->get_cat_url($cat_obj->get_cat_id(), TRUE);
						$cat_url = htmlentities($cat_url);
						$no_right_margin = ($cat_id == 10)?" class='no_right_margin'":"";
						$content .= $tab.$tab."<li id='nav-" . $css_name . "' " . $no_right_margin . "><a href='" . $cat_url . "' title='" . $cat_obj->get_name() ."'><ins class='fixpng'>&nbsp;</ins><span>" . $cat_obj->get_name() . "</span></a></li>".$eol;
					}
				}
			}
			$content .= $tab."</ul>".$eol;
			$content .= $tab."</div>".$eol;

			$content .= "<div id='sub-navigation' class='box-shadow-4'>".$eol;
			$content .= $tab."<div class='category'>".$eol;
			$content .= $tab.$tab."<img src='/resources/images/navigation/nav-01.png' alt='' class='fixpng' />".$eol;
			$content .= $tab.$tab."<span>" . $cat_name_map[1] . "</span>".$eol;
			$content .= $tab."</div>".$eol;
			if($cat_tree)
			{
				foreach($cat_tree as $cat_id=>$sub_cat)
				{
					$content .= $tab."<div class='nav-" . $cat_id_css_map[$cat_id] . "'>".$eol;
					$content .= $tab.$tab."<ul>".$eol;

					$i = 1;
					$content .= $tab.$tab.$tab."<li>".$eol;
					foreach($sub_cat as $sub_cat_id=>$sub_sub_cat)
					{
						$sub_cat_url = $this->website_service->get_cat_url($sub_cat_id, TRUE);
						$sub_cat_url = htmlentities($sub_cat_url);
						$content .= $tab.$tab.$tab.$tab."<p><a href='" . $sub_cat_url . "' title='" .  $cat_name_map[$sub_cat_id] . "'>" . $cat_name_map[$sub_cat_id] . "</a></p>".$eol;

						if($sub_sub_cat)
						{
							$content .= $tab.$tab.$tab.$tab."<dl>".$eol;
							foreach($sub_sub_cat as $sub_sub_cat_id=>$val)
							{
								if($sub_sub_cat_id > 0)
								{
									$sub_sub_cat_url = $this->website_service->get_cat_url($sub_sub_cat_id, TRUE);
									$sub_sub_cat_url = htmlentities($sub_sub_cat_url);
									$content .= $tab.$tab.$tab.$tab.$tab."<dd><a href='" . htmlspecialchars($sub_sub_cat_url, ENT_QUOTES) . "' title='" . $cat_name_map[$sub_sub_cat_id] . "'>" . $cat_name_map[$sub_sub_cat_id] . "</a></dd>".$eol;
								}
							}
							$content .= $tab.$tab.$tab.$tab."</dl>".$eol;
						}

						if($i % 3 == 0)
						{
							$content .= $tab.$tab.$tab."</li>".$eol;
							$content .= $tab.$tab.$tab."<li>".$eol;
						}
						$i++;
					}
					$content .= $tab.$tab.$tab."</li>".$eol;
					$content .= $tab.$tab."</ul>".$eol;
					$content .= $tab."</div>".$eol;
				}
			}
			$content .= "</div>".$eol;
			//echo $content;

			$menu_file = APPPATH."public_views/template/menu_" . strtolower($platform_id) . "_en.html";
			$small_menu = "<div id='navigation' class='small'>".$eol.$content;

			file_put_contents($menu_file, $small_menu);
			chown($menu_file, "apache");
			chgrp($menu_file, "users");
			chmod($menu_file, 0664);

			$menu_file = APPPATH."public_views/template/menu_big_" . strtolower($platform_id) . "_en.html";
			$normal_menu = "<div id='navigation'>".$eol.$content;
			file_put_contents($menu_file, $normal_menu);
			chown($menu_file, "apache");
			chgrp($menu_file, "users");
			chmod($menu_file, 0664);

			// generate footer
			$footer_content = "";
			if($cat_tree)
			{
				$footer_content .= "<ul>".$eol;
				foreach($cat_tree as $cat_id=>$sub_cat)
				{
					$cat_url = $this->website_service->get_cat_url($cat_id, TRUE);
					$cat_url = htmlentities($cat_url);
					$footer_content .= $tab."<li>".$eol;
					$footer_content .= $tab.$tab."<p><a href='" . $cat_url . "' title='" . $cat_name_map[$cat_id] . "'>" . $cat_name_map[$cat_id] . "</a></p>".$eol;
					$footer_content .= $tab.$tab."<dl>".$eol;
					foreach($sub_cat as $sub_cat_id=>$sub_sub_cat)
					{
						$sub_cat_url = $this->website_service->get_cat_url($sub_cat_id, TRUE);
						$sub_cat_url = htmlentities($sub_cat_url);
						$footer_content .= $tab.$tab.$tab."<dd><a href='" . htmlspecialchars($sub_cat_url, ENT_QUOTES) . "' title='" . $cat_name_map[$sub_cat_id] . "'>" . $cat_name_map[$sub_cat_id] . "</a></dd>".$eol;
					}

					$footer_content .= $tab.$tab."</dl>".$eol;
					$footer_content .= $tab."</li>".$eol;
				}
				$footer_content .= "</ul>".$eol;
			}

			$footer_menu_file = APPPATH."public_views/template/footer_menu_" . strtolower($platform_id) . "_en.html";
			file_put_contents($footer_menu_file, $footer_content);
			chown($menu_file, "apache");
			chgrp($menu_file, "users");
			chmod($menu_file, 0664);

		}
	}

	function index()
	{
		$platform_list = $this->selling_platform_model->get_list(array("type"=>"WEBSITE", "status"=>1), array());

		foreach ($platform_list as $p_key=>$p_obj)
		{
			$pbv_obj = $this->platform_biz_var_service->get_platform_biz_var($p_obj->get_id());
			$lang_id = $pbv_obj->get_language_id();
			$platform_id = $p_obj->get_id();

			// initialize data
			$cat_width = $cat_length = $sum_lenght = $total_cat = NULL;
			$adj_width_str = "";
			$n_adj_ie_str = "";
			$str = "";
			$fav = array();
			$search = $replace = NULL;

			// using only en for now
			$menudata = $this->menu_model->get_menu_list_w_platform_id('en', $platform_id);
//			if(!$menudata = $this->menu_model->get_menu_list_w_platform_id($lang_id, $platform_id))
//			{
//				$menudata = $this->menu_model->get_menu_list_w_platform_id('en', $platform_id);
//			}
			$menulist = $menudata["list"];
			$allcatlist = $menudata["allcat"];

			$str ='<link rel="stylesheet" href="/css/menu_style_'.$p_obj->get_id().'.css" type="text/css" media="all"/>
			<!--[if lte IE 6]>
				<link rel="stylesheet" href="/css/menu_style_ie6_'.$p_obj->get_id().'.css" type="text/css" media="all"/>
			<![endif]-->
			<div class="menu_wrapper" id="sitenav">
				<ul class="nav-container">';

			//$total_cat = $this->context_config_service->value_of("menu_total_cat");
			$total_cat = sizeof($allcatlist[0]);
			$total_subcat = $this->context_config_service->value_of("menu_total_subcat");
			$total_col1 = ceil($total_subcat/2);
			$total_subsubcat = $this->context_config_service->value_of("menu_total_subsubcat");
			$padding = 0;

	//		$menulinks_cats = 4;
	//		$adj_cats = $total_cat - ($half_m_cats=floor($menulinks_cats/2));

			$menu_width = $this->context_config_service->value_of("menu_width");
	//		$n0_width = 50;
	//		$cat_width = ($rs_cat_width=floor(($menu_width-$n0_width)/$total_cat)) - 2;
	//		$search[] = '[:cat_width:]';
	//		$replace[] = $cat_width;
			//$menulinks_width = $menu_width/2;
			$menulinks_width = 200;
			$search[] = '[:menulinks_width:]';
			$replace[] = $menulinks_width;

			$cat_count=0;
			$length = array();

			// adjustment for home icon's width
			$cat_length[0]=2;

			if ($menulist[1][0])
			{
				foreach ($menulist[1][0] as $cat_obj) // foreach category
				{
					$cat_count++;

					$cur_name = $cat_obj->get_name();
					if (($rows = count($ar_name = explode(" ", $cur_name)))>1)  // check category word length
					{
						if ($rows>2)
						{
							$i = 2;
							$new_name_f = $ar_name[0];
							$new_name_l = $ar_name[$rows-1];
							$check_first = 1;
							$check_last = $rows-2;

							while($i<$rows)
							{
								 if (strlen($new_name_f) < strlen($new_name_l))
								 {
								 	 $new_name_f .= " ".$ar_name[$check_first];
								 	 $check_first++;
								 }
								 else
								 {
								 	 $new_name_l = $ar_name[$check_last]." ".$new_name_l;
								 	 $check_last--;
								 }
								 $i++;
							}
							$cat_obj->set_name($new_name_f."<br />".$new_name_l);
							$cat_length[$cat_count] = $this->_getMaxLen(array(str_replace(" ", "  ", $new_name_f), str_replace(" ", "  ", $new_name_l)));
						}
						else
						{
							$cat_obj->set_name(str_replace(" ", "<br />", $cur_name));
							$cat_length[$cat_count] = $this->_getMaxLen(str_replace(" ", "  ", $ar_name));
						}
					}
					else
					{
						$cat_length[$cat_count] = strlen($cur_name);
					}

					/*
					if ($cat_count == $total_cat - 1) // leave last row for subtype
					{
						break;
					}

					// for subtype
					$cat_length[sizeof($cat_length)] = strlen($prod_type_title);
					*/
				}
			}

			// calculate the width for category menu
			$sum_lenght = array_sum($cat_length)+5*($total_cat+1);
			$max_col = 0;
			$max_width = 0;

			foreach ($cat_length as $rskey=>$len)
			{
				$cat_width[$rskey] = ($cur_width=floor(($len+5)/$sum_lenght*$menu_width)-$padding);
				if ($cur_width > $max_col)
				{
					$max_col = $rskey;
					$max_width = $cur_width;
				}
			}
			$adj_width = $menu_width - array_sum($cat_width);
			$cat_width[$max_col] = $cat_width[$max_col] + $adj_width - $padding*($total_cat+1);

			// define the width for each cell
			for ($i=0; $i<$total_cat+1; $i++)
			{
			$adj_width_str .= '
	#sitenav ul.nav-container #n'.$i.'
	{
	width:'.$cat_width[$i].'px;
	}';
			}
			$search[] = '[:adj_width:]';
			$replace[] = $adj_width_str;

	//		$n0_width = $n0_width + ($menu_width - ($rs_cat_width * $total_cat) - $n0_width) - 2;
	//		$search[] = '[:n0_width:]';
	//		$replace[] = $n0_width;
			$col_width = 200;
			$search[] = '[:col_width:]';
			$replace[] = $col_width;
			$search[] = '[:a_width:]';
			$replace[] = $col_width-17;


	/*
			$n_adj_str .= '
	#sitenav #n1:hover .menu
	{
		left:-'.($n0_width+2).'px;
	}';

			for ($i=1; $i<$adj_cats; $i++)
			{
			$n_adj_str .= '
	#sitenav #n'.($i+1).':hover .menu
	{
		left:-'.($rs_cat_width+2).'px;
	}';
			}

			for ($i=$half_m_cats-1; $i<$adj_cats; $i++)
			{
			$n_adj_ie_str .= '
	#sitenav ul #n'.($i+1).' a:hover .menu
	{
		left:'.($rs_cat_width*($i+1-$half_m_cats)+$n0_width).'px;
	}';
			}

			for ($i=$adj_cats; $i<$total_cat; $i++)
			{
			$n_adj_str .= '
	#sitenav #n'.($i+1).':hover .menu
	{
		left:-'.($rs_cat_width*($half_m_cats+($i-$adj_cats))+2).'px;
	}';

			$n_adj_ie_str .= '
	#sitenav ul #n'.($i+1).' a:hover .menu
	{
		left:'.($rs_cat_width*($total_cat-$adj_cats+1)+$n0_width).'px;
	}';

			}

			$search[] = '[:n_adj_str:]';
			$replace[] = $n_adj_str;

			$search_ie[] = '[:n_adj_ie_str:]';
			$replace_ie[] = $n_adj_ie_str;
	*/
			$half_menulinks_width = floor($menulinks_width/2);
			$left_width = 0;
			for ($i=0; $i<$total_cat+1; $i++)
			{
				$cur_half_width = floor($cat_width[$i]/2);
				$left_adj_width = $half_menulinks_width - $cur_half_width;
				$left_adj_width_ie = $left_width - $half_menulinks_width + $cur_half_width;

				if ($left_width < $left_adj_width)
				{
					$left_adj_width = $left_width;
				}

				if ($left_adj_width_ie < 0)
				{
					$left_adj_width_ie = 0;
				}

				if (($right_remain = ($menu_width - $left_width - $cur_half_width)) < $half_menulinks_width)
				{
					$left_adj_width += $half_menulinks_width - $right_remain + $padding;
					$left_adj_width_ie -= $half_menulinks_width - $right_remain + $padding;
				}

				$left_width+=$cat_width[$i]+$padding;
				if ($i > 0)
				{
					if($i!=$total_cat){ $pxx = 0; }else{
						$offset = $col_width-$cat_width[$total_cat];
						$n_adj_str .= ' #sitenav #n'.$i.':hover .menu {	left:-'.$offset.'px; }
						'; /*'.(-$left_adj_width).'      IE   left:'.($left_adj_width_ie).'px; }*/
					}
						$n_adj_ie_str .= '#sitenav ul #n'.$i.' a:hover .menu, #sitenav ul #n'.$i.' a:hover iframe {left:'.($left_adj_width_ie).'px; }

					#sitenav ul #n'.$i.' a:hover iframe
					{
						height:expression(document.getElementById(\'m'.$i.'\').offsetHeight+\'px\');
					}
					';
				}
			}

			$search[] = '[:n_adj_str:]';
			$replace[] = $n_adj_str;
			$search_ie[] = '[:n_adj_ie_str:]';
			$replace_ie[] = $n_adj_ie_str;

			$css = file_get_contents("css/menu_style_tpl.css");
			$css_file = "../public_html/css/menu_style_".$p_obj->get_id().".css";

			file_put_contents($css_file, str_replace($search, $replace, $css));
			chown($css_file, "apache");
			chgrp($css_file, "users");
			chmod($css_file, 0664);

			$css = file_get_contents("css/menu_style_ie6_tpl.css");
			$css_file = "../public_html/css/menu_style_ie6_".$p_obj->get_id().".css";

			file_put_contents($css_file, str_replace($search_ie, $replace_ie, $css));
			chown($css_file, "apache");
			chgrp($css_file, "users");
			chmod($css_file, 0664);

			$n_search = array("  ", " ");
			$n_replace = array(" ", "-");

			$home_padding_right = $cat_width[0]-8;

			$str .= '
				<li id="n0" class="nav">
					<h1 align="center">
					<img style="display:block" src="/images/seperator_left.gif" class="seperator">
					<img style="display:block;margin-left:auto;margin-left:'.$home_padding_right.'px" src="/images/seperator_right.gif" class="seperator">
					<a href="/" class="cat"><span><img border="0px" src="/images/home_icon.gif" />&nbsp;</span></a>
					</h1>
				</li>';

			$cat_count=0;
	//Sub Cat
			$i=0;
			if ($menulist[1][0])
			{
				foreach ($menulist[1][0] as $cat_obj)
				{
					$i++;
					$padding_right = $cat_width[$i] - 8; // 8 is the size of the seperator_right.gif
					//<img style="display:block" src="/images/seperator.gif" class="seperator">
					$cat_count++;
					$str .= '
				<li id="n'.$cat_count.'" class="nav">
					<h1 align="center">
						<img style="display:block" src="/images/seperator_left.gif" class="seperator">
						<img style="display:block;margin-left:auto;margin-left:'.$padding_right.'px" src="/images/seperator_right.gif" class="seperator">
					<a href="/cat/?catid='.$cat_obj->get_id().'" class="cat"><span>'.$cat_obj->get_name().'&nbsp;</span></a>
					</h1>
					<table class="menu" id="m'.$cat_count.'" cellpadding=0 cellspacing=0 border=0><tr><td>
						<table class="menu_links" cellpadding=0 cellspacing=0 border=0>
						<tr>';
						//modified by daneil 21012010
						//<td class="cat_header2">All Categories</td></tr>
						//<tr>';
	/* removed by jess
	// Sub-cat

					if ($menulist[2][$cat_obj->get_id()])
					{
						$subcat_count = 0;
						foreach ($menulist[2][$cat_obj->get_id()] as $subcat_obj)
						{
							if ($subcat_count == 0 || $subcat_count == $total_col1)
							{
								$cur_col = ($subcat_count < $total_col1)?"1":"2";
								$str .='
							<td class="col'.$cur_col.'" valign=top><ul>';
							}
							$str .='
								<li><h3><a href="/'.str_replace($n_search, $n_replace, parse_url_char(str_replace('<br />', ' ', $subcat_obj->get_name()))).'/cat/?catid='.$subcat_obj->get_id().'">'.$subcat_obj->get_name().'</a></h3></li>';

	// Sub-Sub-Cat
							if ($menulist[3][$subcat_obj->get_id()])
							{
								$str .='
								<ul class="sub">';
								$subsubcat_count = 0;
								foreach ($menulist[3][$subcat_obj->get_id()] as $subsubcat_obj)
								{
									$str .='
									<li><a href="/'.str_replace($n_search, $n_replace, parse_url_char(str_replace('<br />', ' ', $subsubcat_obj->get_name()))).'/cat/?catid='.$subsubcat_obj->get_id().'">'.$subsubcat_obj->get_name().'</a></li>';

									$subsubcat_count++;
									if ($subsubcat_count == $total_subsubcat)
									{
										break;
									}
								}
								$str.='
									<li><a href="/'.str_replace($n_search, $n_replace, parse_url_char(str_replace('<br />', ' ', $subcat_obj->get_name()))).'/cat/?catid='.$subcat_obj->get_id().'" class="see">see all</a></li>
								</ul>';
							}

							$subcat_count++;
							if ($subcat_count == $total_col1)
							{
								$cur_col = ($subcat_count < $total_col1)?"1":"2";
								$str .='
							</ul></td>';
							}
							if ($subcat_count == $total_subcat)
							{
								break;
							}
						}
						if ($subcat_count != $total_col1)
						{
							$str .='
							</ul></td>';
						}
						if ($subcat_count<=$total_col1)
						{
						$str .='
							<td class="col2" valign=top><ul>
							</ul></td>';
						}
					}
	*/
					if ($allcatlist[$cat_obj->get_id()])
					{
						$str .='
							<td class="col3" valign=top><ul>';
						foreach ($allcatlist[$cat_obj->get_id()] as $subcat_obj)
						{
							if ($this->product_service->get_num_rows(array("sub_cat_id"=>$subcat_obj->get_id())))
							{
								$str .='
									<li><h3><a href="/search/?from=c&catid='.$subcat_obj->get_id().'">'.$subcat_obj->get_name().'</a></h3></li>';
							}
						}
						$str .='
							</ul></td>';
					}

					$str .= '
						</tr></table>
					</td></tr></table>
					<iframe frameborder="0"></iframe>
				</li>';
					if ($cat_count == $total_cat)
					{
						break;
					}
				}
	/*
				// generate menu for subtype
				$cat_count++;
				$str .= '
					<li id="n'.$cat_count.'" class="nav">
						<h1><img src="/images/orangeseperator.gif" class="seperator">
						<a class="cat"><span>'.$prod_type_title.'</span>
						</h1>
						<table class="menu" id="m'.$cat_count.'" cellpadding=0 cellspacing=0 border=0><tr><td>
							<table class="menu_links" cellpadding=0 cellspacing=0 border=0>
							<tr>';

				$str .= '<td class="col3" valign=top><ul>';
				foreach ($p_list AS $p_obj)
				{
					$str .='
						<li><h3><a href="">'.$p_obj->get_subkey_value_w_lang().'</a></h3></li>';
				}
				$str .='</ul></td>';

				$str .= '
						</tr></table>
					</td></tr></table>
					<iframe frameborder="0"></iframe>
					</a>
				</li>';
				// end generate menu for subtype
	*/
			}


			$str .= '
		</ul>
	</div>';

		// temp fix for no sales data before launch will change back later - 20110317 Steven
		$platform_list = $this->selling_platform_model->get_platform_by_lang(array("sp.type"=>"SKYPE", "sp.status"=>"1", "pbv.language_id"=>$lang_id));
		foreach($platform_list AS $obj)
		{
			$fav[$obj->get_id()][$lang_id][] = $this->category_model->category_service->get_best_selling_cat($obj->get_id(), $lang_id);
		}
		$fav_cat_obj = serialize($fav);

		$str .= '<?php $fav_cat_obj = \''.$fav_cat_obj.'\'; ?>';

		$menu_file = APPPATH."public_views/menu_".$platform_id.".php";
		file_put_contents($menu_file, $str);
		chown($menu_file, "apache");
		chgrp($menu_file, "users");
		chmod($menu_file, 0664);
		}

		// Generate footer menu
		$this->home_model->gen_footer_cat_menu();
		$this->home_model->gen_select_country_grid();
	}

	private function _getMaxLen($arr)
	{
		foreach($arr as $arr_str)
		{
			$len[] = strlen($arr_str);
		}
		return max($len);
	}
	public function _get_app_id()
	{
		return $this->app_id;
	}
}

/* End of file cron_draw_menu.php */
/* Location: ./system/application/controllers/cron_draw_menu.php */
