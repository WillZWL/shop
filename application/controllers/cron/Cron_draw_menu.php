<?php

class Cron_draw_menu extends MY_Controller
{
    private $appId = 'CRN0008';

    public function __construct()
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
        $this->load->library('service/category_service');
        $this->load->helper('url');
    }

    public function cron_multilanguage_menu()
    {
        $eol = "\n";
        $tab = "\t";
        $platform_list = $this->selling_platform_model->get_list(array('type' => 'WEBSITE', 'status' => 1), array('limit' => -1));
        $language_list = $this->language_model->get_list(array('status' => 1), array('limit' => -1));
        foreach ($language_list as $lang_obj) {
            $lang_id = $lang_obj->get_lang_id();
            foreach ($platform_list as $platform_obj) {
                $platform_id = $platform_obj->get_selling_platform_id();
                if ($pbv_obj = $this->platform_biz_var_service->get_platform_biz_var($platform_id)) {
                    $country_id = $pbv_obj->get_platform_country_id();
                    $base_url = '';
                    $cat_id_css_map = array(1 => 'camera', 2 => 'lens', 3 => 'camcorders', 4 => 'phones', 5 => 'apple', 6 => 'tablets', 7 => 'headphones', 8 => 'computing', 9 => 'audio', 10 => 'warranty');

                    $cat_list = $this->category_model->get_cat_menu_list(array('lang_id' => $lang_id), array('limit' => -1, 'orderby' => 'c.sponsored desc, ce.name'));
                    if ($cat_list) {
                        $cat_name_map = array();
                        foreach ($cat_list as $obj) {
                            $cat_name_map[$obj->get_cat_id()] = $obj->get_name();
                        }
                    }
                    $main_cat_array = array();
                    $cat_arr = $cat_tree = $cat_tree_v2 = array();
                    $cat_arr = $this->category_model->get_listed_cat($platform_id);

                    foreach ($cat_arr as $val) {
                        if ($val['cat_id'] == 10) {
                            if ($cat_name_map[$val['sub_sub_cat_id']]) {
                                $cat_tree_v2[$val['sub_cat_id']][$val['sub_sub_cat_id']] = null;
                            }
                        } else {
                            if ($val['sub_sub_cat_id']) {
                                $cat_tree_v2[$val['cat_id']][$val['sub_cat_id']][$val['sub_sub_cat_id']] = null;
                            } else {
                                $cat_tree_v2[$val['cat_id']][$val['sub_cat_id']] = null;
                            }
                        }
                    }

                    foreach ($cat_id_css_map as $cat_id => $css_name) {
                        if ($cat_ext_list = $this->category_model->get_cat_menu_list(array('cat_id' => $cat_id, 'lang_id' => $lang_id), array('limit' => -1, 'orderby' => 'c.sponsored desc, ce.name'))) {
                            foreach ($cat_ext_list as $cat_obj) {
                                $cat_url = htmlentities($this->sc['Category']->getCatUrl($cat_obj->get_cat_id()));
                                $no_right_margin = ($cat_id == 10) ? " class='no_right_margin'" : '';
                                if ($cat_obj->get_cat_id() == 10) {
                                    if ($cat_tree_v2) {
                                        foreach ($cat_tree_v2 as $cat_id => $sub_cat) {
                                            $cat_url = htmlentities($this->sc['Category']->getCatUrl($cat_id));
                                            $main_cat_array[$noofmaincat]['id'] = $cat_id;
                                            $main_cat_array[$noofmaincat]['name'] = $cat_name_map[$cat_id];
                                            $main_cat_array[$noofmaincat]['url'] = $cat_url;
                                            $main_cat_array[$noofmaincat]['sponsored'] = $this->category_service->get_dao()->get(array('id' => $cat_id))->get_sponsored(); //$cat_obj->get_sponsored();

                                        $subcate_v2 = '';
                                            $subcate_array = array();
                                            $subcat_number = 0;
                                            foreach ($sub_cat as $sub_cat_id => $sub_sub_cat) {
                                                $sub_cat_url = htmlentities($this->sc['Category']->getCatUrl($sub_cat_id));
                                                if (!$cat_name_map[$sub_cat_id]) {
                                                    $empty_name[$lang_id][] = $sub_cat_id;
                                                }
                                                $subcate_array[$subcat_number]['name'] = $cat_name_map[$sub_cat_id];
                                                $subcate_array[$subcat_number]['url'] = $sub_cat_url;
                                                $subcate_array[$subcat_number]['sponsored'] = 0;

                                                $subcate_v2 .= $cat_name_map[$sub_cat_id];

                                                $subsubcate_v2 = '';
                                                $subsubcate_array = array();
                                                $subsubcat_number = 0;
                                                if ($sub_sub_cat) {
                                                    foreach ($sub_sub_cat as $sub_sub_cat_id => $val) {
                                                        if ($sub_sub_cat_id > 0) {
                                                            if (!$cat_name_map[$sub_sub_cat_id]) {
                                                                $empty_name[$lang_id][] = $sub_sub_cat_id;
                                                            }
                                                            $sub_sub_cat_url = htmlentities($this->sc['Category']->getCatUrl($sub_sub_cat_id));
                                                            if ($cat_name_map[$sub_sub_cat_id]) {
                                                                $subsubcate_array[$subsubcat_number]['name'] = $cat_name_map[$sub_sub_cat_id];
                                                                $subsubcate_array[$subsubcat_number]['url'] = $sub_sub_cat_url;
                                                                $subsubcate_array[$subsubcat_number]['sponsored'] = 0;
                                                                ++$subsubcat_number;
                                                                $subsubcate_v2 .= $cat_name_map[$sub_sub_cat_id];
                                                            }
                                                        }
                                                    }
                                                    uasort($subsubcate_array, array($this, 'cmp'));
                                                    $subcate_array[$subcat_number]['subsub'] = $subsubcate_array;
                                                }
                                                ++$subcat_number;
                                            }
                                            uasort($subcate_array, array($this, 'cmp'));
                                            $main_cat_array[$noofmaincat]['subcat'] = $subcate_array;

                                            ++$noofmaincat;
                                        }
                                    }
                                }
                            }
                        }
                    }

                    $sponsor = array();
                    $names = array();
                    foreach ($main_cat_array as $key => $row) {
                        $sponsor[$key] = $row['sponsored'];
                        $names[$key] = $row['name'];
                    }
                    array_multisort($sponsor, SORT_DESC, $names, SORT_ASC, $main_cat_array);

                    $CategoriesTitle = '<?= _("Categories") ?>';
                    $content_v2 =
                             '<div class="col-lg-3 col-sm-3 col-md-3 hidden-xs hidden-sm top-verticalmenu">
								<div class="menu-heading d-heading">
								   <h4>
									  '.$CategoriesTitle.'<span class="fa fa-angle-down pull-right"></span>
								   </h4>
								</div>';
                    $content_v2 .= '<div id="pav-verticalmenu" class="pav-verticalmenu">
									<div class="menu-content d-content">
										<div class="pav-verticalmenu fix-top hidden-xs hidden-sm">
											<div class="navbar navbar-verticalmenu">
												<div class="verticalmenu" role="navigation">
													<div class="navbar-header">
														<a href="javascript:;" data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle">
															<span class="icon-bar"></span>
															<span class="icon-bar"></span>
															<span class="icon-bar"></span>
														</a>
														<div class="collapse navbar-collapse navbar-ex1-collapse">
															<ul class="nav navbar-nav verticalmenu">';

                    $mobileContent = '<div class="collapse navbar-collapse" id="bs-megamenu">
                                    <div class="row">
                                        <div class="quick-access">
                                            <div id="search" class="input-group pull-right" style="margin-top: 4px;height: 24px !important;">
                                                <input type="text" name="search" value="" placeholder="Search" class="form-control"  style="height: 24px !important;" />
                                                <span class="input-group-btn">
                                                    <button type="button" class="button-search" style="height: 24px !important;"><i class="fa fa-search"></i></button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
									<ul class="nav navbar-nav megamenu">';

                    $end_sponsored = false;

                    foreach ($main_cat_array as $value) {
                        if ($end_sponsored == false && $value['sponsored'] == 0) {
                            $end_sponsored = true;
                            $content_v2 .= "<li class='nav-divider'></li>".$eol;
                            $mobileContent .= "<li class='nav-divider'></li>".$eol;
                        }

                        $content_v2 .= '<li class="bg1 topdropdow parent dropdown " ><a href="'.$value['url'].'" class="dropdown-toggle" data-toggle="dropdown"><i class=""></i><span class="menu-title">'.$value['name'].'</span><b class="caret"></b></a>';

                        $mobileContent .= '<li class="parent dropdown home aligned-left" >
											<a class="dropdown-toggle linkcat" data-toggle="dropdown" href="'.$value['url'].'">
												<span class="menu-title">'.$value['name'].'</span><b class="caret"></b>
											</a>';

                        if ($value['subcat']) {
                            $totallinks = 1;
                            $noofsubcategory = count($value['subcat']);
                            $eachcol = ceil($noofsubcategory / 3);
                            $content_v2 .= '<div class="dropdown-menu"  style="width:840px" >
                                          <div class="dropdown-menu-inner">
                                             <div class="row">';

                            $mobileContent .= '<div class="dropdown-menu"  style="width:540px" >
										     <div class="dropdown-menu-inner">
											   <div class="row">';

                            foreach ($value['subcat'] as $subcategory) {
                                if ($totallinks == 1) {
                                    $content_v2 .= '<div class="mega-col col-md-4 " >
                                                   <div class="mega-col-inner">
                                                      <div class="pavo-widget">
                                                         <div class="pavo-widget">';
                                }

                                $mobileContent .= '<div class="mega-col col-xs-12 col-sm-12 col-md-4 " >
													 <div class="mega-col-inner">
														<div class="pavo-widget" id="pavowid-52">
															<div class="pavo-widget" id="pavowid-747136749">';

                                $sub_cat_link = htmlentities($this->sc['Category']->getCatUrl($sub_cat_id));

                                $content_v2 .= '<h4 class="widget-heading title">
												<a class="linksub" href=\''.$subcategory['url'].'\'>
												   <span>'.$subcategory['name'].'</span>
												</a>
											</h4>
											<div class="">';
                                $mobileContent .= ' <h4 class="widget-heading title">
													<a  class="linksub" href=\''.$subcategory['url'].'\'>
													   <span>'.$subcategory['name'].'</span>
													</a>
												</h4>
												<div class="">';

                                if ($subcategory['subsub']) {
                                    $content_v2 .= '<ul class="content list-unstyled">';
                                    $mobileContent .= '<ul class="content">';
                                    foreach ($subcategory['subsub'] as $subsubcategory) {
                                        $content_v2 .= '<li><a href="'.$subsubcategory['url'].'">'.$subsubcategory['name'].'</a></li>';
                                        $mobileContent .= "<li><a class='linkcat' href=\"".$subsubcategory['url'].'">'.$subsubcategory['name'].'</a></li>';
                                    }
                                    $content_v2 .= '</ul>
											 </div>';
                                    $mobileContent .= '</ul>
                                                 </div>';
                                } else {
                                    $content_v2 .= '</div>';
                                    $mobileContent .= '</div>';
                                }

                                if ($noofsubcategory == $totallinks) {
                                    $content_v2 .= '
										  </div>
									   </div>
									</div>
								 </div>';

                                    $mobileContent .= '
										  </div>
									   </div>
									</div>
								  </div>';
                                } else {
                                    if ($totallinks % $eachcol == 0) {
                                        $content_v2 .= '
                                              </div>
                                           </div>
                                        </div>
                                     </div>';

                                        $content_v2 .= '<div class="mega-col col-md-4 " >
													   <div class="mega-col-inner">
                                                         <div class="pavo-widget">
                                                            <div class="pavo-widget">
                                                               ';
                                    }

                                    $mobileContent .= '
											 </div>
										  </div>
									   </div>
									</div>';
                                }
                                ++$totallinks;
                            }

                            $content_v2 .= '    </div>
                                         </div>
                                      </div>';

                            $mobileContent .= ' </div>
                                         </div>
                                      </div>';
                        }

                        $content_v2 .= '</li>'.$eol;
                        $mobileContent .= '</li>'.$eol;
                    }
                    $content_v2 .= '							</ul>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="hidden-lg hidden-md col-sm-12 col-xs-12">
								<div id="pav-mainnav" class="hidden-xs hidden-sm pull-left">
									<nav id="pav-megamenu" class="navbar">
										<div class="navbar-header">
											<button data-toggle="offcanvas" class="btn btn-primary canvas-menu hidden-lg hidden-md" type="button"><span class="fa fa-bars">Menu</span></button>
											<div class="canvas-menu hidden-lg hidden-md">
											'.$mobileContent.'
											</div>
										</div>
									</nav>
								</div>
							</div>'.$eol;

                    $mobileContent .= '</ul>
								</div>'.$eol;

                    $menu_path = APPPATH.'views/template/menu/'.$lang_id;
                    $menu_file = $menu_path.'/menu_'.strtolower($platform_id).'.html';
                    if (!file_exists($menu_path)) {
                        mkdir($menu_path, 0755, true);
                    }
                    $small_menu = $content_v2;

                    file_put_contents($menu_file, $small_menu);

                    $menu_file = APPPATH.'views/template/menu/'.$lang_id.'/menu_big_'.strtolower($platform_id).'.html';
                    $normal_menu = $content_v2;
                    file_put_contents($menu_file, $normal_menu);
                }
            }
        }
    }

    public function cmp($a, $b)
    {
        return strnatcmp($a['name'], $b['name']);
    }

    public function index()
    {
        $platform_list = $this->selling_platform_model->get_list(array('type' => 'WEBSITE', 'status' => 1), array());

        foreach ($platform_list as $p_key => $p_obj) {
            $pbv_obj = $this->platform_biz_var_service->get_platform_biz_var($p_obj->get_id());
            $lang_id = $pbv_obj->get_language_id();
            $platform_id = $p_obj->get_id();

            // initialize data
            $cat_width = $cat_length = $sum_lenght = $total_cat = null;
            $adj_width_str = '';
            $n_adj_ie_str = '';
            $str = '';
            $fav = array();
            $search = $replace = null;

            // using only en for now
            $menudata = $this->menu_model->get_menu_list_w_platform_id('en', $platform_id);

            $menulist = $menudata['list'];
            $allcatlist = $menudata['allcat'];

            $str = '<link rel="stylesheet" href="/css/menu_style_'.$p_obj->get_id().'.css" type="text/css" media="all"/>
            <!--[if lte IE 6]>
                <link rel="stylesheet" href="/css/menu_style_ie6_'.$p_obj->get_id().'.css" type="text/css" media="all"/>
            <![endif]-->
            <div class="menu_wrapper" id="sitenav">
                <ul class="nav-container">';

            //$total_cat = $this->context_config_service->value_of("menu_total_cat");
            $total_cat = sizeof($allcatlist[0]);
            $total_subcat = $this->context_config_service->value_of('menu_total_subcat');
            $total_col1 = ceil($total_subcat / 2);
            $total_subsubcat = $this->context_config_service->value_of('menu_total_subsubcat');
            $padding = 0;

            $menu_width = $this->context_config_service->value_of('menu_width');

            $menulinks_width = 200;
            $search[] = '[:menulinks_width:]';
            $replace[] = $menulinks_width;

            $cat_count = 0;
            $length = array();

            // adjustment for home icon's width
            $cat_length[0] = 2;

            if ($menulist[1][0]) {
                foreach ($menulist[1][0] as $cat_obj) {
                    // foreach category

                    ++$cat_count;

                    $cur_name = $cat_obj->get_name();
                    if (($rows = count($ar_name = explode(' ', $cur_name))) > 1) {
                        // check category word length

                        if ($rows > 2) {
                            $i = 2;
                            $new_name_f = $ar_name[0];
                            $new_name_l = $ar_name[$rows - 1];
                            $check_first = 1;
                            $check_last = $rows - 2;

                            while ($i < $rows) {
                                if (strlen($new_name_f) < strlen($new_name_l)) {
                                    $new_name_f .= ' '.$ar_name[$check_first];
                                    ++$check_first;
                                } else {
                                    $new_name_l = $ar_name[$check_last].' '.$new_name_l;
                                    --$check_last;
                                }
                                ++$i;
                            }
                            $cat_obj->set_name($new_name_f.'<br />'.$new_name_l);
                            $cat_length[$cat_count] = $this->_getMaxLen(array(str_replace(' ', '  ', $new_name_f), str_replace(' ', '  ', $new_name_l)));
                        } else {
                            $cat_obj->set_name(str_replace(' ', '<br />', $cur_name));
                            $cat_length[$cat_count] = $this->_getMaxLen(str_replace(' ', '  ', $ar_name));
                        }
                    } else {
                        $cat_length[$cat_count] = strlen($cur_name);
                    }
                }
            }

            // calculate the width for category menu
            $sum_lenght = array_sum($cat_length) + 5 * ($total_cat + 1);
            $max_col = 0;
            $max_width = 0;

            foreach ($cat_length as $rskey => $len) {
                $cat_width[$rskey] = ($cur_width = floor(($len + 5) / $sum_lenght * $menu_width) - $padding);
                if ($cur_width > $max_col) {
                    $max_col = $rskey;
                    $max_width = $cur_width;
                }
            }
            $adj_width = $menu_width - array_sum($cat_width);
            $cat_width[$max_col] = $cat_width[$max_col] + $adj_width - $padding * ($total_cat + 1);

            // define the width for each cell
            for ($i = 0; $i < $total_cat + 1; ++$i) {
                $adj_width_str .= '
    #sitenav ul.nav-container #n'.$i.'
    {
    width:'.$cat_width[$i].'px;
    }';
            }
            $search[] = '[:adj_width:]';
            $replace[] = $adj_width_str;

            $col_width = 200;
            $search[] = '[:col_width:]';
            $replace[] = $col_width;
            $search[] = '[:a_width:]';
            $replace[] = $col_width - 17;

            $half_menulinks_width = floor($menulinks_width / 2);
            $left_width = 0;
            for ($i = 0; $i < $total_cat + 1; ++$i) {
                $cur_half_width = floor($cat_width[$i] / 2);
                $left_adj_width = $half_menulinks_width - $cur_half_width;
                $left_adj_width_ie = $left_width - $half_menulinks_width + $cur_half_width;

                if ($left_width < $left_adj_width) {
                    $left_adj_width = $left_width;
                }

                if ($left_adj_width_ie < 0) {
                    $left_adj_width_ie = 0;
                }

                if (($right_remain = ($menu_width - $left_width - $cur_half_width)) < $half_menulinks_width) {
                    $left_adj_width += $half_menulinks_width - $right_remain + $padding;
                    $left_adj_width_ie -= $half_menulinks_width - $right_remain + $padding;
                }

                $left_width += $cat_width[$i] + $padding;
                if ($i > 0) {
                    if ($i != $total_cat) {
                        $pxx = 0;
                    } else {
                        $offset = $col_width - $cat_width[$total_cat];
                        $n_adj_str .= ' #sitenav #n'.$i.':hover .menu { left:-'.$offset.'px; }
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

            $css = file_get_contents('css/menu_style_tpl.css');
            $css_file = '../public_html/css/menu_style_'.$p_obj->get_id().'.css';

            file_put_contents($css_file, str_replace($search, $replace, $css));
            chown($css_file, 'apache');
            chgrp($css_file, 'users');
            chmod($css_file, 0664);

            $css = file_get_contents('css/menu_style_ie6_tpl.css');
            $css_file = '../public_html/css/menu_style_ie6_'.$p_obj->get_id().'.css';

            file_put_contents($css_file, str_replace($search_ie, $replace_ie, $css));
            chown($css_file, 'apache');
            chgrp($css_file, 'users');
            chmod($css_file, 0664);

            $n_search = array('  ', ' ');
            $n_replace = array(' ', '-');

            $home_padding_right = $cat_width[0] - 8;

            $str .= '
                <li id="n0" class="nav">
                    <h1 align="center">
                    <img style="display:block" src="/images/seperator_left.gif" class="seperator">
                    <img style="display:block;margin-left:auto;margin-left:'.$home_padding_right.'px" src="/images/seperator_right.gif" class="seperator">
                    <a href="/" class="cat"><span><img border="0px" src="/images/home_icon.gif" />&nbsp;</span></a>
                    </h1>
                </li>';

            $cat_count = 0;
            //Sub Cat
            $i = 0;
            if ($menulist[1][0]) {
                foreach ($menulist[1][0] as $cat_obj) {
                    ++$i;
                    $padding_right = $cat_width[$i] - 8; // 8 is the size of the seperator_right.gif
                    ++$cat_count;
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
                    if ($allcatlist[$cat_obj->get_id()]) {
                        $str .= '
                            <td class="col3" valign=top><ul>';
                        foreach ($allcatlist[$cat_obj->get_id()] as $subcat_obj) {
                            if ($this->product_service->get_num_rows(array('sub_cat_id' => $subcat_obj->get_id()))) {
                                $str .= '
                                    <li><h3><a href="/search/?from=c&catid='.$subcat_obj->get_id().'">'.$subcat_obj->get_name().'</a></h3></li>';
                            }
                        }
                        $str .= '
                            </ul></td>';
                    }

                    $str .= '
                        </tr></table>
                    </td></tr></table>
                    <iframe frameborder="0"></iframe>
                </li>';
                    if ($cat_count == $total_cat) {
                        break;
                    }
                }
            }

            $str .= '
        </ul>
    </div>';

            // temp fix for no sales data before launch will change back later - 20110317 Steven
            $platform_list = $this->selling_platform_model->get_platform_by_lang(array('sp.type' => 'SKYPE', 'sp.status' => '1', 'pbv.language_id' => $lang_id));
            foreach ($platform_list as $obj) {
                $fav[$obj->get_id()][$lang_id][] = $this->category_model->category_service->get_best_selling_cat($obj->get_id(), $lang_id);
            }
            $fav_cat_obj = serialize($fav);

            $str .= '<?php $fav_cat_obj = \''.$fav_cat_obj.'\'; ?>';

            $menu_file = APPPATH.'public_views/menu_'.$platform_id.'.php';
            file_put_contents($menu_file, $str);
            chown($menu_file, 'apache');
            chgrp($menu_file, 'users');
            chmod($menu_file, 0664);
        }

        // Generate footer menu
        $this->home_model->gen_footer_cat_menu();
        $this->home_model->gen_select_country_grid();
    }

    private function _getMaxLen($arr)
    {
        foreach ($arr as $arr_str) {
            $len[] = strlen($arr_str);
        }

        return max($len);
    }

    public function getAppId()
    {
        return $this->appId;
    }
}
