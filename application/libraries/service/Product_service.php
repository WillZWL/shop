<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Product_service extends Base_service
{
    const PRODUCT_STATUS_CHANGED = 1;
    const PRODUCT_EXPECT_DELIVERY_CHANGED = 2;
    private $pc_dao;
    private $pcext_dao;
    private $serv;
    private $old_prod_obj;
    private $new_prod_obj;
    private $map_dao;
    private $_osd_lang_list = array("NA" => 0, "FR" => 1, "ES" => 2, "RU" => 3, "PL" => 4, "IT" => 5);
    public $copyEnLang = array();
    public $forcingEnFields = array("ru" => array("prod_name")
                                    , "pl" => array("prod_name"));

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH."libraries/dao/Product_dao.php");
        $this->set_dao(new Product_dao());
        include_once(APPPATH."libraries/dao/Product_content_dao.php");
        $this->set_pc_dao(new Product_content_dao());
        include_once(APPPATH."libraries/dao/Product_content_extend_dao.php");
        $this->set_pcext_dao(new Product_content_extend_dao());
        include_once(APPPATH."libraries/dao/Product_image_dao.php");
        $this->set_pi_dao(new Product_image_dao());
        include_once(APPPATH."libraries/dao/Product_video_dao.php");
        $this->set_pv_dao(new Product_video_dao());
        include_once(APPPATH."libraries/dao/Product_banner_dao.php");
        $this->set_pb_dao(new Product_banner_dao());
        include_once(APPPATH."libraries/dao/Product_keyword_dao.php");
        $this->set_pk_dao(new Product_keyword_dao());
        include_once(APPPATH."libraries/dao/Product_type_dao.php");
        $this->set_pt_dao(new Product_type_dao());
        include_once(APPPATH."libraries/dao/Category_dao.php");
        $this->set_cat_dao(new Category_dao());
        include_once(APPPATH."libraries/dao/Bundle_dao.php");
        $this->set_bd_dao(new Bundle_dao());
        include_once(APPPATH."libraries/dao/Software_licence_dao.php");
        $this->set_licence_dao(new Software_licence_dao());
        include_once(APPPATH."libraries/dao/Sourcing_list_dao.php");
        $this->set_sl_dao(new Sourcing_list_dao());
        include_once(APPPATH . 'libraries/service/Class_factory_service.php');
        $this->set_class_factory_service(new Class_factory_service());
        include_once(APPPATH."libraries/dao/Mapping_dao.php");
        $this->set_map_dao(new Mapping_dao());
        include_once(APPPATH."libraries/dao/Sku_mapping_dao.php");
        $this->set_sku_map_dao(new Sku_mapping_dao());
        include_once(APPPATH."libraries/dao/Category_mapping_dao.php");
        $this->set_cat_map_dao(new Category_mapping_dao());
        include_once(APPPATH."libraries/dao/Colour_dao.php");
        $this->set_colour_dao(new Colour_dao());
        include_once(APPPATH."libraries/dao/Colour_extend_dao.php");
        $this->set_colour_ext_dao(new Colour_extend_dao());
        include_once(APPPATH."libraries/dao/Brand_dao.php");
        $this->set_brand_dao(new Brand_dao());
        include_once(APPPATH . 'libraries/service/Translate_service.php');
        $this->set_translate_service(new Translate_service());

    }

    public function get_pc_dao()
    {
        return $this->pc_dao;
    }

    public function set_pc_dao(Base_dao $dao)
    {
        $this->pc_dao = $dao;
    }

    public function get_pk_dao()
    {
        return $this->pk_dao;
    }

    public function set_pk_dao(Base_dao $dao)
    {
        $this->pk_dao = $dao;
    }

    public function get_pt_dao()
    {
        return $this->pt_dao;
    }

    public function set_pt_dao(Base_dao $dao)
    {
        $this->pt_dao = $dao;
    }

    public function get_pv_dao()
    {
        return $this->pv_dao;
    }

    public function set_pv_dao(Base_dao $dao)
    {
        $this->pv_dao = $dao;
    }

    public function get_pb_dao()
    {
        return $this->pb_dao;
    }

    public function set_pb_dao(Base_dao $dao)
    {
        $this->pb_dao = $dao;
    }

    public function get_pcext_dao()
    {
        return $this->pcext_dao;
    }

    public function set_pcext_dao(Base_dao $dao)
    {
        $this->pcext_dao = $dao;
    }

    public function get_pi_dao()
    {
        return $this->pi_dao;
    }

    public function set_pi_dao(Base_dao $dao)
    {
        $this->pi_dao = $dao;
    }

    public function set_class_factory_service($serv)
    {
        $this->class_factory_service = $serv;
    }

    public function get_class_factory_service()
    {
        return $this->class_factory_service;
    }

    public function set_translate_service($serv)
    {
        $this->translate_service = $serv;
    }

    public function get_translate_service()
    {
        return $this->translate_service;
    }

    public function get_cat_dao()
    {
        return $this->cat_dao;
    }

    public function set_cat_dao(Base_dao $dao)
    {
        $this->cat_dao = $dao;
    }

    public function get_map_dao()
    {
        return $this->map_dao;
    }

    public function set_map_dao(Base_dao $dao)
    {
        $this->map_dao = $dao;
    }

    public function get_cat_map_dao()
    {
        return $this->cat_map_dao;
    }

    public function set_cat_map_dao(Base_dao $dao)
    {
        $this->cat_map_dao = $dao;
    }

    public function get_sku_map_dao()
    {
        return $this->sku_map_dao;
    }

    public function set_sku_map_dao(Base_dao $dao)
    {
        $this->sku_map_dao = $dao;
    }

    public function get_sl_dao()
    {
        return $this->sl_dao;
    }

    public function set_sl_dao(Base_dao $dao)
    {
        $this->sl_dao = $dao;
    }

    public function get_licence_dao()
    {
        return $this->licence_dao;
    }

    public function set_licence_dao(Base_dao $dao)
    {
        $this->licence_dao = $dao;
    }

    public function get_bd_dao()
    {
        return $this->bd_dao;
    }

    public function set_bd_dao(Base_dao $dao)
    {
        $this->bd_dao = $dao;
    }

    public function get_brand_dao()
    {
        return $this->brand_dao;
    }

    public function set_brand_dao(Base_dao $dao)
    {
        $this->brand_dao = $dao;
    }

    public function get_colour_dao()
    {
        return $this->colour_dao;
    }

    public function set_colour_dao(Base_dao $dao)
    {
        $this->colour_dao = $dao;
    }

    public function get_colour_ext_dao()
    {
        return $this->colour_ext_dao;
    }

    public function set_colour_ext_dao(Base_dao $dao)
    {
        $this->colour_ext_dao = $dao;
    }

    public function pi_seq_next_val()
    {
        return $this->get_pi_dao()->seq_next_val();
    }

    public function pb_seq_next_val()
    {
        return $this->get_pb_dao()->seq_next_val();
    }

    public function alert_user($alert_type, $sku, $status=array(), $expected_delivery_date=array())
    {
        if ($alert_type == self::PRODUCT_STATUS_CHANGED)
        {
            $subject = "[VB] Product status change alert, " . $sku;
        }
        else if ($alert_type == self::PRODUCT_EXPECT_DELIVERY_CHANGED)
        {
            $subject = "[VB] Product expected delivery date change alert SKU-" . $sku;
        }
        $content = "SKU:" . $sku . "<br><br>";
        $content .= "Old website Status:" . $status[0] . "<br>";
        $content .= "New website Status:" . $status[1] . "<br>";
        $content .= "Old EDD:" . $expected_delivery_date[0] . "<br>";
        $content .= "New EDD:" . $expected_delivery_date[1] . "<br>";
        $headers = "From: product-alert@valuebasket.com\r\n";
        $headers .= "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type: text/html; charset=utf-8" . "\r\n";
        mail("product-status-alert@valuebasket.com", $subject, $content, $headers);
    }

    public function get_list_by_brand_cat($catid="", $brand="", $where=array(), $option=array())
    {
        if($catid == "" || $brand == "")
        {
            return NULL;
        }
        else
        {
            $catobj = $this->get_cat_dao()->get(array("id"=>$catid));
            if(empty($catobj))
            {
                return NULL;
            }

            return $this->get_dao()->get_product_brand_cat($catobj->get_level(),$catid,$brand,$where,$option);
        }
    }

    public function get_list_by_brand_cat_cnt($catid="", $brand="",$where=array())
    {
        if($catid == "" || $brand == "")
        {
            return NULL;
        }
        else
        {
            $catobj = $this->get_cat_dao()->get(array("id"=>$catid));
            if(empty($catobj))
            {
                return NULL;
            }

            return $this->get_dao()->get_product_brand_cat($catobj->get_level(),$catid,$brand,$where,array("num_row"=>1));
        }
    }

    public function get_pblist_array()
    {
        return $this->get_dao()->get_pblist();
    }

    public function get_website_list($where = array(), $option = array())
    {
        return $this->get_dao()->get_prod_list_for_website($where, $option);
    }

    public function get_website_list_cnt($where=array())
    {
        return $this->get_dao()->get_prod_list_for_website($where, array("num_row"=>1));
    }

    public function get_clearance_list($where=array(), $option=array())
    {
        return $this->get_dao()->get_clearance_list($where, $option);
    }

    public function get_clearance_list_cnt($where=array())
    {
        return $this->get_dao()->get_clearance_list($where, array("num_row"=>1));
    }

    public function get_sorucing_list($where=array(), $option=array(), $gen_csv=0)
    {
        $rs = $this->get_sl_dao()->get_sorucing_list($where, $option);
        if (!$option["num_rows"] && $rs)
        {
            $ar_rs["objlist"] = $rs;
            foreach ($rs as $obj)
            {
                unset($cur_pq);
                $pq_list = explode("||", $obj->get_platform_qty());
                foreach ($pq_list as $pq)
                {
                    list($platform, $qty) = explode("::", $pq);
                    $ar_rs["platform"][$platform] = $platform;
                    $cur_pq->$platform = $qty;
                }
                $obj->set_platform_qty($cur_pq);
            }
            if (!$gen_csv)
            {
                return $ar_rs;
            }
            else
            {
                include_once(APPPATH."libraries/service/Data_exchange_service.php");
                $dex = new Data_exchange_service();
                $mapping2["container"] = $mapping["container"] = "sourcing_list";
                $mapping2["mapping"]["MASTER_SKU"] = $mapping["mapping"]["master_sku"] = "MASTER_SKU";
                $mapping2["mapping"]["SKU"] = $mapping["mapping"]["item_sku"] = "SKU";
                $mapping2["mapping"]["Product_Name"] = $mapping["mapping"]["prod_name"] = "Product_Name";
                if ($ar_rs["platform"])
                {
                    foreach ($ar_rs["platform"] as $pf)
                    {
                        $mapping["mapping"]["platform_qty->".$pf] = $pf;
                        $mapping2["mapping"][$pf] = $pf;
                    }
                }
                $mapping2["mapping"]["CURR_ID"] = $mapping["mapping"]["supplier_curr_id"] = "CURR_ID";
                $mapping2["mapping"]["BUDGET"] = $mapping["mapping"]["budget"] = "BUDGET";
                $mapping2["mapping"]["REQ_QTY"] = $mapping["mapping"]["required_qty"] = "REQ_QTY";
                $mapping2["mapping"]["Prioritized_QTY"] = $mapping["mapping"]["prioritized_qty"] = "Prioritized_QTY";
                $mapping2["mapping"]["Clearance"] = $mapping["mapping"]["clearance"] = "Clearance";
                $mapping2["mapping"]["Comments"] = $mapping["mapping"]["comments"] = "Comments";
                if ($gen_csv == 1)
                {
                    $obj_dto = new Vo_to_xml($rs, $mapping);
                    $out_csv = new Xml_to_csv('', $mapping2);
                    echo $dex->convert($obj_dto, $out_csv);
                }
                else
                {
// gen_csv == 2 is xml
                    $xml_mapping["container"] = "items";
                    $xml_mapping["mapping"]["item_sku"] = "SKU";
                    $xml_mapping["mapping"]["master_sku"] = "MASTER_SKU";
                    $xml_mapping["mapping"]["prod_name"] = "Product_Name";
                    $xml_mapping["mapping"]["required_qty"] = "REQ_QTY";
                    $xml_mapping["mapping"]["prioritized_qty"] = "Prioritized_QTY";
                    $xml_mapping["mapping"]["clearance"] = "Clearance";
                    $obj_dto = new Vo_to_xml($rs, $xml_mapping);
                    echo $obj_dto->in_convert(true);
                }
            }
        }
        else
        {
            return $rs;
        }
    }

    public function import_sorucing_list($list_date, $csv_file)
    {
        include_once(APPPATH."libraries/service/Data_exchange_service.php");
        $dex = new Data_exchange_service();
        $obj_csv = new Csv_to_xml($csv_file, APPPATH.'data/sourcing_list_csv2xml.txt');
        $out_vo = new Xml_to_vo();
        $output = $dex->convert($obj_csv, $out_vo);
        $result = "";
        $failed = 0;
        foreach ($output as $obj)
        {
            $cur_sku = $obj->get_item_sku();
            $old_obj = $this->get_sl_dao()->get(array("list_date"=>$list_date, "item_sku"=>$cur_sku));
            $old_obj->set_sourced_qty($obj->get_sourced_qty());
            $old_obj->set_comments($obj->get_comments());
            if ($this->get_sl_dao()->update($old_obj))
            {
                $cur_status = 1;
            }
            else
            {
                $cur_status = 0;
                $failed = 1;
            }
            $result .= "{$cur_sku} -> {$cur_status}\\n";
        }
        return $failed?$result:"";
    }

    public function get_list_by_keyword($keyword, $page_no=0, $row_limit=20,
        $platform_id='WSGB', $lang_id='en',
        $classname='website_prod_search_info_dto')
    {
        $prod_result = $this->get_dao()->get_list_by_keyword($keyword, $page_no,
            $row_limit, $platform_id, $lang_id, $classname);

        if ($prod_result)
        {
            $prod_list = $prod_result['prodlist'];
            $price_serv = $this->get_class_factory_service()->get_price_service($platform_id);

            foreach ($prod_list as $dto)
            {
                $dto->set_price($price_serv->get_price($dto->get_sku()));
            }
        }

        return $prod_result;
    }

    public function update_prod_status($sku, $need_update=0)
    {
        $prod_obj = $this->get_dao->get(array("sku"=>$sku));
        $this->update_prod_obj_status("", $prod_obj, $need_update);
    }

    public function update_prod_obj_status($old_prod="", $new_prod="", $need_update=1)
    {
        $update_product = $update_bundle = 0;

        if ($new_prod)
        {
            $website_status = $new_prod->get_website_status();
            $website_qty = $new_prod->get_website_qty();
            $status = $new_prod->get_status();
        }

        if ($old_prod)
        {
            $o_website_status = $old_prod->get_website_status();
            $o_website_qty = $old_prod->get_website_qty();
            $o_status = $old_prod->get_status();
        }

        if ($website_status == "I" && $website_qty == 0)
        {
            $new_prod->set_website_status("O");
            $update_product = 1;
            $update_bundle = 1;
        }
        if ($need_update || $update_product)
        {
            $this->get_dao()->update($new_prod);
        }
        if ($update_bundle || $status == "0" || $website_status != "I")
        {
            $this->update_bundle_status($obj);
        }
    }

    public function update_bundle_status($obj)
    {
        $website_status = $obj->get_website_status();
        $status = $obj->get_status();
    }

    public function get_detail_w_name($sku, $platform_id='WSGB', $lang_id='en',
        $classname='website_prod_search_info_dto')
    {
        return $this->get_dao()->get_detail_w_name($sku, $platform_id, $lang_id, $classname);
    }

    public function get_best_seller_list_by_cat($filter_column = '',
        $cat_id = 0, $day_count = 0, $limit = 0, $platform="", $is_skype_certified = "")
    {
        return $this->get_dao()->get_best_seller_list_by_cat($filter_column,
            $cat_id, $day_count, $limit, $platform, $is_skype_certified);
    }

    public function get_pick_of_the_day_list_by_cat($filter_column = '',
        $cat_id = 0, $day_count = 0, $limit = 0, $platform="")
    {
        return $this->get_dao()->get_pick_of_the_day_list_by_cat($filter_column,
            $cat_id, $day_count, $limit, $platform);
    }

    public function get_latest_arrivals_list_by_cat($filter_column = '',
        $cat_id = 0, $day_count = 0, $limit = 0, $platform="")
    {
        return $this->get_dao()->get_latest_arrivals_list_by_cat($filter_column,
            $cat_id, $day_count, $limit, $platform);
    }

    public function get_best_selling_video_list($filter_column = '',
        $cat_id = 0, $day_count = 0, $limit = 0, $platform="", $type="", $src="")
    {
        return $this->get_pv_dao()->get_best_selling_video_list($filter_column,
            $cat_id, $day_count, $limit, $platform, $type, $src);
    }

    public function get_best_selling_video_list_by_cat($filter_column = '',
        $cat_id = 0, $day_count = 0, $limit = 0, $platform="", $type="", $src="")
    {
        return $this->get_pv_dao()->get_best_selling_video_list_by_cat($filter_column,
            $cat_id, $day_count, $limit, $platform, $type, $src);
    }

    public function get_listed_product_list($platform_id = 'WSGB', $classname='Website_prod_info_dto')
    {
        return $this->get_dao()->get_listed_product_list($platform_id, $classname);
    }

    public function get_product_w_price_info($platform_id = 'WEBGB', $sku = "", $classname='Website_prod_info_dto')
    {
        // $this->get_dao()->get_listed_product_list($platform_id);
        return $this->get_dao()->get_product_w_price_info($platform_id, $sku, $classname);
    }

    public function get_top_deal_list_by_cat($filter_column = '',
        $cat_id = 0, $limit = 0, $platform)
    {
        return $this->get_dao()->get_top_deal_list_by_cat($filter_column, $cat_id, $limit, $platform);
    }

    public function get_list_having_price($where=array(), $option=array())
    {
        return $this->get_dao()->get_list_having_price($where, $option);
    }

    public function get_listed_video_list($where=array(), $option=array())
    {
        return $this->get_dao()->get_listed_video_list($where, $option);
    }

    public function get_video_detail($where=array(), $option=array())
    {
        return $this->get_dao()->get_video_detail($where, $option);
    }

    public function get_product_w_margin_req_update($where=array(), $classname='Website_prod_info_dto')
    {
        return $this->get_dao()->get_product_w_margin_req_update($where, $classname);
    }

    public function get_new_product_for_report($start_time='',
        $end_time='', $platform_id='WSGB', $classname='Product_cost_change_dto')
    {
        return $this->get_dao()-> get_new_product_for_report($start_time,
                    $end_time, $platform_id, $classname);
    }

    public function get_product_shipping_override_info($platform_id,
        $dto_class)
    {
        return $this->get_dao()->get_product_shipping_override_info($platform_id,
            $dto_class);

    }

    public function gen_display_quantity($platform="WEBHK")
    {
        /*
            reference:
            $factor : array for getting the factor coming up with the display quantity
            price ranage: 1: 0- 69.99, 2: 70-199.99, 3: 200-499.99, 4: 500-699.99, 5: 700-99.99, 6:1000- 1999.99; 7: 2000 or above
            regardless of currency

            key of factor = category ID of the product
        */
        $factor = array("1"=>array("1"=>0.6,"2"=>0.8,"3"=>0.8,"4"=>0.6,"5"=>0.6,"6"=>0.6,"7"=>0.6),
                        "2"=>array("1"=>0.9,"2"=>0.8,"3"=>0.6,"4"=>0.4,"5"=>0.4,"6"=>0.4,"7"=>0.4),
                        "3"=>array("1"=>1,"2"=>0.8,"3"=>0.6,"4"=>0.4,"5"=>0.4,"6"=>0.4,"7"=>0.4),
                        "4"=>array("1"=>0.5,"2"=>0.7,"3"=>0.7,"4"=>0.6,"5"=>0.6,"6"=>0.6,"7"=>0.6),
                        "5"=>array("1"=>0.4,"2"=>0.4,"3"=>0.4,"4"=>0.5,"5"=>0.5,"6"=>0.5,"7"=>0.5),
                        "6"=>array("1"=>0.5,"2"=>0.6,"3"=>0.5,"4"=>0.5,"5"=>0.5,"6"=>0.5,"7"=>0.5)
                    );
        $minqty = array("1"=>20,"2"=>12,"3"=>8,"4"=>4,"5"=>3,"6"=>2,"7"=>2);
        $maxqty = array("1"=>70,"2"=>32,"3"=>22,"4"=>19,"5"=>14,"6"=>9,"7"=>5);
        $prod_list = $this->get_dao()->get_product_overview(array("website_status <> "=>'O',"platform_id"=>$platform,"website_quantity >"=>'0',"listing_status"=>"L"), array("limit"=>-1));
        foreach($prod_list as $item)
        {
            $prod_obj = $this->get_dao()->get(array("sku"=>$item->get_sku()));
            if($prod_obj && $prod_obj->get_display_quantity() == 0)
            {
                $price = $item->get_price();
                $cat_id = $prod_obj->get_cat_id();
                if($price < 70)
                {
                    $index = 1;
                }
                else if($price >= 70 && $price < 200)
                {
                    $index = 2;
                }
                else if($price >= 200 && $price < 500)
                {
                    $index = 3;
                }
                else if($price >= 500 && $price < 700)
                {
                    $index = 4;
                }
                else if($price >= 700 && $price < 1000)
                {
                    $index = 5;
                }
                else if($price >= 1000 && $price < 2000)
                {
                    $index = 6;
                }
                else
                {
                    $index = 7;
                }

                $dqty = rand($minqty[$index],$maxqty[$index]);
                $this_factor = $factor[$cat_id][$index];
                $prod_obj->set_display_quantity(round($this_factor * $dqty));
                $this->get_dao()->update($prod_obj);
            }
        }

        $bundle_list = $this->get_dao()->get_bundle_win_min_dcnt();
        foreach($bundle_list as $bundle)
        {
            $bprod = $this->get_dao()->get(array("sku"=>$bundle["sku"]));
            if($bprod)
            {
                $bprod->set_display_quantity($bundle["qty"]);
                $this->get_dao()->update($bprod);
            }
        }
        return ;
    }

    public function get_skype_page_info($clist, $platform="", $lang_id="")
    {
        $ret = array();
        if($clist)
        {
            foreach($clist as $cart)
            {
                $sku = $cart["sku"];
                if ($prod_list = $this->get_dao()->get_bundle_components_overview(array("vpi.prod_sku"=>$sku, "vpo.platform_id"=>$platform), array("cart"=>1, "language"=>$lang_id)))
                {
                    $website_status = "I";
                    $sourcing_status = "A";
                    $listing_status = "L";

                    foreach ($prod_list as $obj)
                    {
                        $d_qty = $obj->get_display_quantity();
                        $ws_qty = $obj->get_website_quantity();
                        $ws_status = $obj->get_website_status();
                        $src_status = $obj->get_sourcing_status();
                        $lst_status = $obj->get_listing_status();

                        if ($obj->get_component_order() < 1)
                        {
                            $ret[$sku] = $obj;
                        }
                        if (!isset($max_d_qty) || $d_qty < $max_d_qty)
                        {
                            $max_d_qty = $d_qty;
                        }
                        if (!isset($max_ws_qty) || $ws_qty < $max_ws_qty)
                        {
                            $max_ws_qty = $ws_qty;
                        }
                        if ($ws_status != "I")
                        {
                            $website_status = $ws_status;
                        }
                        if ($src_status == "O")
                        {
                            $sourcing_status = $src_status;
                        }
                        if ($lst_status != "L")
                        {
                            $listing_status = $lst_status;
                        }
                    }
                    $ret[$sku]->set_display_quantity($max_d_qty);
                    $ret[$sku]->set_website_quantity($max_ws_qty);
                    $ret[$sku]->set_website_status($website_status);
                    $ret[$sku]->set_sourcing_status($sourcing_status);
                    $ret[$sku]->set_listing_status($listing_status);
                }
            }
        }

        return $ret;
    }

    public function get_product_info($sku, $platform_id, $lang_id)
    {
        if ($prod_list = $this->get_dao()->get_bundle_components_overview(array("vpi.prod_sku"=>$sku, "vpo.platform_id"=>$platform_id), array("cart"=>1, "language"=>$lang_id, "orderby"=>"component_order")))
        {
            $website_status = "I";
            $sourcing_status = "A";
            $listing_status = "L";
            $subtotal = 0;

            foreach ($prod_list as $obj)
            {
                $d_qty = $obj->get_display_quantity();
                $ws_qty = $obj->get_website_quantity();
                $ws_status = $obj->get_website_status();
                $src_status = $obj->get_sourcing_status();
                $lst_status = $obj->get_listing_status();
                $subtotal += ROUND($obj->get_price() * (100 - $obj->get_discount()) / 100, 2);

                if ($obj->get_component_order() < 1)
                {
                    $ret = $obj;
                }
                if (!isset($max_d_qty) || $d_qty < $max_d_qty)
                {
                    $max_d_qty = $d_qty;
                }
                if (!isset($max_ws_qty) || $ws_qty < $max_ws_qty)
                {
                    $max_ws_qty = $ws_qty;
                }
                if ($ws_status != "I")
                {
                    $website_status = $ws_status;
                }
                if ($src_status == "O")
                {
                    $sourcing_status = $src_status;
                }
                if ($lst_status != "L")
                {
                    $listing_status = $lst_status;
                }
            }

            // $ret->get_component_order() > -1 is bundle
            if (($components = $ret->get_component_order()) > -1)
            {
                include_once "currency_service.php";
                $curr_srv = new Currency_service();
                if (!isset($round_up))
                {
                    $round_up = $curr_srv->round_up_of($ret->get_platform_currency_id());
                }
                $new_subtotal = price_round_up($subtotal, $round_up);
                $subtotal_diff = $new_subtotal - $subtotal;
                if ($subtotal_diff)
                {
                    $subtotal = $new_subtotal;
                }
            }

            $ret->set_price($subtotal);
            $ret->set_display_quantity($max_d_qty);
            $ret->set_website_quantity($max_ws_qty);
            $ret->set_website_status($website_status);
            $ret->set_sourcing_status($sourcing_status);
            $ret->set_listing_status($listing_status);

            return $ret;
        }
        return FALSE;
    }

    public function check_all_virtual($item_list = array())
    {
        $all_virtual_valid = 1;
        if ($item_list)
        {
            foreach ($item_list as $sku=>$qty)
            {
                if ($sku_list = $this->get_dao()->get_item_contain($sku))
                {
                    foreach ($sku_list as $chk_sku)
                    {
                        if (!$this->get_pt_dao()->get_num_rows(array("sku"=>$chk_sku, "type_id"=>"VIRTUAL")))
                        {
                            $all_virtual_valid = 0;
                            break;
                        }
                    }
                }

                if (!$all_virtual_valid)
                {
                    break;
                }
            }
        }
        return $all_virtual_valid;
    }

    public function check_all_trial($item_list = array())
    {
        $all_trial_valid = 1;
        if ($item_list)
        {
            foreach ($item_list as $sku=>$qty)
            {
                if ($sku_list = $this->get_dao()->get_item_contain($sku))
                {
                    foreach ($sku_list as $chk_sku)
                    {
                        if (!$this->get_pt_dao()->get_num_rows(array("sku"=>$chk_sku, "type_id"=>"TRIAL")))
                        {
                            $all_trial_valid = 0;
                            break;
                        }
                    }
                }
                if (!$all_trial_valid)
                {
                    break;
                }
            }
        }
        return $all_trial_valid;
    }

    public function get_content($sku, $lang_id = "en")
    {
        return $this->get_pc_dao()->get_content_w_default(array("p.sku"=>$sku, "l.id"=>$lang_id));
    }

    public function get_prod_image_list($where=array(), $option=array())
    {
        return $this->get_pi_dao()->get_list($where, $option);
    }

    public function get_prod_image($where=array())
    {
        return $this->get_pi_dao()->get($where);
    }

    public function update_product_image($obj)
    {
        return $this->get_pi_dao()->update($obj);
    }

    public function add_product_image($obj)
    {
        return $this->get_pi_dao()->insert($obj);
    }

    public function get_prod_banner($where=array())
    {
        return $this->get_pb_dao()->get($where);
    }

    public function get_prod_banner_list($where=array(), $option=array())
    {
        return $this->get_pb_dao()->get_list($where, $option);
    }

    public function update_product_banner($obj)
    {
        return $this->get_pb_dao()->update($obj);
    }

    public function add_product_banner($obj)
    {
        return $this->get_pb_dao()->insert($obj);
    }

    public function get_video_list($where=array(), $option=array())
    {
        return $this->get_pv_dao()->get_list($where, $option);
    }

    public function get_video_list_w_country($sku="", $country_arr=array())
    {
        return $this->get_pv_dao()->get_video_list_w_country($sku, $country_arr);
    }

    public function get_video_num_rows($where=array(), $option=array())
    {
        return $this->get_pv_dao()->get_num_rows($where, $option);
    }

    public function get_video_num_rows_w_country($sku="", $country_arr=array())
    {
        $num_rows = TRUE;
        return $this->get_pv_dao()->get_video_list_w_country($sku, $country_arr, $num_rows);
    }

    public function del_product_video($where=array(), $option=array())
    {
        return $this->get_pv_dao()->q_delete($where);
    }

    public function get_display_video_list($where=array(), $option=array())
    {
        return $this->get_pv_dao()->get_display_video_list($where, $option);
    }

    public function get_product_banner($sku="", $display_id="", $position_id="", $lang_id="en")
    {
        return $this->get_pb_dao()->get_product_banner($sku, $display_id, $position_id, $lang_id);
    }

    public function is_trial_software($sku = "")
    {
        return $this->get_dao()->is_trial_software($sku);
    }

    public function is_software($sku = "")
    {
        return $this->get_dao()->is_software($sku);
    }

    public function get_product_type_w_sku($sku = "")
    {
        return $this->get_dao()->get_product_type_w_sku($sku);
    }

    public function get_admin_product_feed_dto($where = array(), $option = array())
    {
        return $this->get_dao()->get_admin_product_feed_dto($where, array('limit'=>-1, 'platform_type'=> $option['platform_type']));
    }

    public function get_reevoo_product_feed_dto($country_id = NULL)
    {
        return $this->get_dao()->get_reevoo_product_feed_dto('Reevoo_product_feed_dto',$country_id);
    }

    public function get_googlebase_product_feed_dto($platform_id = "WEBGB", $where = array())
    {
        return $this->get_dao()->get_googlebase_product_feed_dto($platform_id, $where);
    }

    public function get_mediaforge_product_feed_dto($where, $option, $platform_id)
    {
        return $this->get_dao()->get_mediaforge_product_feed_dto($where, $option, 'Mediaforge_product_feed_dto', $platform_id);
    }

    public function get_linkshare_product_feed_2_dto($where, $option)
    {
        return $this->get_dao()->get_linkshare_product_feed_2_dto($where, $option);
    }

    public function get_linkshare_product_feed_dto($where, $option)
    {
        return $this->get_dao()->get_linkshare_product_feed_dto($where, $option);
    }

    public function get_shopping_com_product_feed_dto()
    {
        return $this->get_dao()->get_shopping_com_product_feed_dto();
    }

    public function get_sli_product_feed_dto($where = array(), $option = array())
    {
        return $this->get_dao()->get_sli_product_feed_dto($where, $option);
    }

    public function get_sli_product_feed_product_info_dto($where = array(), $option = array())
    {
        return $this->get_dao()->get_sli_product_feed_product_info_dto($where, $option);
    }

    public function get_sli_product_feed_price_info_dto($where = array(), $option = array())
    {
        return $this->get_dao()->get_sli_product_feed_price_info_dto($where, $option);
    }

    public function get_searchspring_product_feed_product_info_dto($where = array(), $option = array())
    {
        return $this->get_dao()->get_searchspring_product_feed_product_info_dto($where, $option);
    }

    public function get_searchspring_product_feed_price_info_dto($where = array(), $option = array())
    {
        return $this->get_dao()->get_searchspring_product_feed_price_info_dto($where, $option);
    }

    public function get_shopbot_product_feed_dto($where = array(), $option = array())
    {
        return $this->get_dao()->get_shopbot_product_feed_dto($where, $option);
    }

    public function get_shopbot_nz_product_feed_dto($where = array(), $option = array())
    {
        return $this->get_dao()->get_shopbot_nz_product_feed_dto($where, $option);
    }

    public function get_my_shopping_com_au_product_feed_dto($where = array(), $option = array())
    {
        return $this->get_dao()->get_my_shopping_com_au_product_feed_dto($where, $option);
    }

    public function get_price_panda_product_feed_dto($where = array(), $option = array())
    {
        return $this->get_dao()->get_price_panda_product_feed_dto($where, $option);
    }

    public function get_tag_product_feed_dto($where = array(), $option = array())
    {
        return $this->get_dao()->get_tag_product_feed_dto($where, $option);
    }

    public function get_shopprice_product_feed_dto($where = array(), $option = array())
    {
        return $this->get_dao()->get_shopprice_product_feed_dto($where, $option);
    }

    public function get_get_price_product_feed_dto($where = array(), $option = array())
    {
        return $this->get_dao()->get_get_price_product_feed_dto($where, $option);
    }

    public function get_criteo_product_feed_product_feed_dto($where = array(), $option = array())
    {
        return $this->get_dao()->get_criteo_product_feed_product_feed_dto($where, $option);
    }

    public function get_graysonline_product_feed_dto($where = array(), $option = array())
    {
        return $this->get_dao()->get_graysonline_product_feed_dto($where, $option);
    }

    public function get_kelkoo_product_feed_dto($where = array(), $option = array(), $country)
    {
        return $this->get_dao()->get_kelkoo_product_feed_dto($where, $option, "Kelkoo_product_feed_dto", $country);
    }

    public function get_yandex_product_feed_dto($where = array(), $option = array(), $platform_id)
    {
        return $this->get_dao()->get_yandex_product_feed_dto($where, $option, "Yandex_product_feed_dto", $platform_id);
    }

    public function get_ceneo_product_feed_dto($where = array(), $option = array(), $platform_id)
    {
        return $this->get_dao()->get_ceneo_product_feed_dto($where, $option, "Ceneo_product_feed_dto", $platform_id);
    }

    public function get_skapiec_product_feed_dto($where = array(), $option = array(), $platform_id)
    {
        return $this->get_dao()->get_skapiec_product_feed_dto($where, $option, "Skapiec_product_feed_dto", $platform_id);
    }

    public function get_shoppydoo_product_feed_dto($where = array(), $option = array(), $country)
    {
        return $this->get_dao()->get_shoppydoo_product_feed_dto($where, $option, $country);
    }

    public function get_shopall_product_feed_dto($where = array(), $option = array(), $platform_id)
    {
        return $this->get_dao()->get_shopall_product_feed_dto($where, $option, "Shopall_product_feed_dto", $platform_id);
    }

    public function get_nextag_product_feed_dto($where = array(), $option = array(), $platform_id)
    {
        return $this->get_dao()->get_nextag_product_feed_dto($where, $option, "Nextag_product_feed_dto", $platform_id);
    }

    public function get_comparer_product_feed_dto($where = array(), $option = array(), $country)
    {
        return $this->get_dao()->get_comparer_product_feed_dto($where, $option, "Comparer_product_feed_dto", $country);
    }

    public function get_omg_product_feed_dto($where = array(), $option = array(), $country)
    {
        return $this->get_dao()->get_omg_product_feed_dto($where, $option, "Omg_product_feed_dto", $country);
    }

    public function get_pricespy_product_feed_dto($where = array(), $option = array(), $country)
    {
        return $this->get_dao()->get_pricespy_product_feed_dto($where, $option, "Pricespy_product_feed_dto", $country);
    }

    public function get_tradedoubler_product_feed_dto($where = array(), $option = array(), $country)
    {
        return $this->get_dao()->get_tradedoubler_product_feed_dto($where, $option, "Tradedoubler_product_feed_dto", $country);
    }

    public function get_tradetracker_product_feed_dto($where = array(), $option = array(), $platform_id)
    {
        return $this->get_dao()->get_tradetracker_product_feed_dto($where, $option, "Tradetracker_product_feed_dto", $platform_id);
    }

    public function get_priceme_product_feed_dto($where = array(), $option = array())
    {
        return $this->get_dao()->get_priceme_product_feed_dto($where, $option);
    }

    public function get_priceme_product_feed_w_country_dto($where = array(), $option = array(), $platform_id)
    {
        return $this->get_dao()->get_priceme_product_feed_w_country_dto($where, $option, "Priceme_product_feed_dto", $platform_id);
    }

    public function get_prismastar_product_feed_dto($platform_id = "WEBGB")
    {
        return $this->get_dao()->get_prismastar_product_feed_dto($platform_id);
    }

    public function get_shopping_com_fr_product_feed_dto($where = array(), $option = array())
    {
        return $this->get_dao()->get_shopping_com_fr_product_feed_dto($where, $option);
    }

    public function get_standard_fr_product_feed_dto($where = array(), $option = array())
    {
        return $this->get_dao()->get_standard_fr_product_feed_dto($where, $option);
    }

    public function get_product_type($arr = '')
    {
        if (empty($arr))
        {
            return NULL;
        }

        return $this->get_pt_dao()->get($arr);
    }

    public function get_website_product_info($sku = "", $platform_id = "WEBHK", $lang_id = "en")
    {
        if(empty($sku))
        {
            return false;
        }
        return $this->get_dao()->get_website_product_info($sku, $platform_id, $lang_id);
    }

    public function get_website_cat_page_product_list($where = array(), $option = array())
    {
        return $this->get_dao()->get_website_cat_page_product_list($where, $option);
    }

    public function get_home_best_seller_grid_info($platform_id = "WEBHK")
    {
        return $this->get_dao()->get_home_best_seller_grid_info($platform_id);
    }

    public function get_home_latest_arrival_grid_info($platform_id = "WEBHK")
    {
        return $this->get_dao()->get_home_latest_arrival_grid_info($platform_id);
    }

    #SBF2682
    public function get_clearance_product_gird_info($platform_id = "WEBHK")
    {
        return $this->get_dao()->get_clearance_product_gird_info($platform_id);
    }

    public function get_product_keyword_arraylist($sku = "", $platform_id = "")
    {
        return $this->get_pk_dao()->get_product_keyword_arraylist($sku, $platform_id);
    }

    public function get_master_sku($where = array())
    {
        return $this->get_sku_map_dao()->get($where);
    }

    public function get_fnac_additem_info($where = array(), $option = array())
    {
        return $this->get_dao()->get_fnac_additem_info($where, $option);
    }

    public function get_fnac_item_list($platform_id = "FNACES", $where=array(), $option=array())
    {
        return $this->get_dao()->get_fnac_item_list($platform_id, $where, $option);
    }

    public function get_sub_cat_margin($where = array())
    {
        return $this->get_dao()->get_sub_cat_margin($where);
    }

    public function get_ebay_auction_info($platform_id = array(), $sku = array())
    {
        return $this->get_dao()->get_ebay_auction_info($platform_id, $sku);
    }

    public function translate_product_content($sku = "", $lang_id = "en", &$translated_product_name, $skipKeywords = false)
    {
        if($sku)
        {
            $forcingEnFields = array();
            $translate_arr = array();
            $pc_action = "update";
            if(!$new_pc_obj = $this->get_pc_dao()->get(array("prod_sku"=>$sku, "lang_id"=>$lang_id)))
            {
                $pc_action = "insert";
                $new_pc_obj = $this->get_pc_dao()->get();
                $new_pc_obj->set_prod_sku($sku);
                $new_pc_obj->set_lang_id($lang_id);
            }

            if($pc_obj = $this->get_pc_dao()->get(array("prod_sku"=>$sku, "lang_id"=>"en")))
            {
                if (in_array($lang_id, $this->copyEnLang))
                {
                    $pc_obj->set_lang_id("pl");
                    $this->get_pc_dao()->$pc_action($pc_obj);
                }
                else
                {
                    $translate_arr = array(
                                            "prod_name" => $pc_obj->get_prod_name(),
                                            "short_desc" => $pc_obj->get_short_desc(),
                                            "detail_desc" => $pc_obj->get_detail_desc(),
                                            "contents" => $pc_obj->get_contents(),
                                            // "keywords" => $pc_obj->get_keywords(),
                                            // "model_1" => $pc_obj->get_model_1(),
                                            // "model_2" => $pc_obj->get_model_2(),
                                            "model_3" => $pc_obj->get_model_3(),
                                            "model_4" => $pc_obj->get_model_4(),
                                            "model_5" => $pc_obj->get_model_5(),
                                            "extra_info" => $pc_obj->get_extra_info(),
                                            "website_status_short_text" => $pc_obj->get_website_status_short_text(),
                                            "website_status_long_text" => $pc_obj->get_website_status_long_text()
                                        );

                    foreach($translate_arr as $key=>$source_text)
                    {
                        if(!empty($source_text))
                        {
                            $new_lang_text = "";

                            try
                            {
                                $this->get_translate_service()->translate(nl2br($source_text), $new_lang_text, "en", $lang_id);
                            }
                            catch(Exception $ex)
                            {
                                $new_lang_text = "";
                                mail("bd_product_team@eservicesgroup.com", "Translation error sku=" . $sku . "[" . $key . "]", $ex->getMessage(), 'From: website@valuebasket.com');
                            }
                            $new_lang_text = preg_replace('/\<br(\s*)?\/?\>(\n)*/i', "\n", $new_lang_text); // convert from br to nl
                            //$new_pc_obj->{"set_{$key}"}($new_lang_text);
                            switch ($key) {
                                case 'prod_name':
                                    if ($new_pc_obj->get_prod_name_original() != 1) {
                                        if($lang_id == "pl" || $lang_id == "ru" || $lang_id == "it"){
                                            if ($new_pc_obj->{"get_{$key}"}() == null) {
                                                $new_pc_obj->{"set_{$key}"}($source_text);
                                            }
                                        } else {
                                            $new_pc_obj->{"set_{$key}"}($new_lang_text);
                                        }
                                    }
                                    break;
                                case 'detail_desc':
                                    if ($new_pc_obj->get_detail_desc_original() != 1) {
                                        $new_pc_obj->{"set_{$key}"}($new_lang_text);
                                    }
                                    break;
                                case 'contents':
                                    if ($new_pc_obj->get_contents_original() != 1) {
                                        $new_pc_obj->{"set_{$key}"}($new_lang_text);
                                    }
                                    break;

                                default:
                                    $new_pc_obj->{"set_{$key}"}($new_lang_text);
                                    break;
                            }


                            /*if (array_key_exists($lang_id, $this->forcingEnFields))
                            {
                                if (in_array($key, $this->forcingEnFields[$lang_id]))
                                {
                                    //overwrite the value
                                    $new_pc_obj->{"set_{$key}"}($source_text);
                                }
                            }
                            #SBF2701 when translate, update the googlebase product data as well
                            if($key == "prod_name") $translated_product_name = $new_pc_obj->get_prod_name();*/
                        }
                    }

                    # don't translate model_1 and model_2, but need them to be in adwords generation
                    $non_translate_arr = array(
                                            "model_1" => $pc_obj->get_model_1(),
                                            "model_2" => $pc_obj->get_model_2()
                                            );

                    foreach($non_translate_arr as $key=>$source_text)
                    {
                        if(!empty($source_text))
                        {
                            $new_pc_obj->{"set_{$key}"}($source_text);
                        }
                    }

                    if (!$skipKeywords)
                    {
                        if ($lang_id !== 'en')
                        {
                            #SBF #3041 generate list of non-English keywords according to input
                            $attributes_arr = array();
                            $attributes_arr["model_1"] = $new_pc_obj->get_model_1();
                            $attributes_arr["model_2"] = $new_pc_obj->get_model_2();
                            $attributes_arr["model_3"] = $new_pc_obj->get_model_3();
                            $attributes_arr["model_4"] = $new_pc_obj->get_model_4();
                            $attributes_arr["model_5"] = $new_pc_obj->get_model_5();
                            $attributes_arr["series"] = $new_pc_obj->get_series();

                            $product_obj = $this->get_dao()->get(array("sku"=>$sku));
                            $attributes_arr["brand_id"] = $product_obj->get_brand_id();
                            $attributes_arr["colour_id"] = $product_obj->get_colour_id();

                            $gen_ret = $this->generate_keywords($sku, $attributes_arr, $lang_id);
                            if($gen_ret["status"] === TRUE)
                            {
                                $new_pc_obj->set_keywords($gen_ret["keywords"]);
                            }
                            else
                            {
                                mail("bd_product_team@eservicesgroup.com", "Translation error sku=" . $sku, $gen_ret["error_msg"], 'From: website@valuebasket.com');
                            }
                        }
                    }
                    else
                    {
                        if ($new_pc_obj->get_model_1() == null)
                        {
                            $new_pc_obj->set_model_1(" ");
                        }
                    }
                    $this->get_pc_dao()->$pc_action($new_pc_obj);
                }
            }

            $translate_arr = array();
            $pcex_action = "update";
            if(!$new_pcex_obj = $this->get_pcext_dao()->get(array("prod_sku"=>$sku, "lang_id"=>$lang_id)))
            {
                $pcex_action = "insert";
                $new_pcex_obj = $this->get_pcext_dao()->get();
                $new_pcex_obj->set_prod_sku($sku);
                $new_pcex_obj->set_lang_id($lang_id);
            }

            if($pcex_obj = $this->get_pcext_dao()->get(array("prod_sku"=>$sku, "lang_id"=>"en")))
            {
                if (in_array($lang_id, $this->copyEnLang))
                {
                    $pcex_obj->set_lang_id("pl");
                    $this->get_pcext_dao()->$pcex_action($pcex_obj);
                }
                else
                {
                    $translate_arr = array(
                                            "feature" => $pcex_obj->get_feature(),
                                            "specification" => $pcex_obj->get_specification(),
                                            "requirement" => $pcex_obj->get_requirement(),
                                            "instruction" => $pcex_obj->get_instruction(),
                                        );

                    foreach($translate_arr as $key=>$source_text)
                    {
                        if(!empty($source_text))
                        {
                            $new_lang_text = "";
                            try
                            {
                                $this->get_translate_service()->translate(nl2br($source_text), $new_lang_text, "en", $lang_id);
                            }
                            catch(Exception $ex)
                            {
                                $new_lang_text = "";
                                mail("bd_product_team@eservicesgroup.com", "Translation error sku=" . $sku . "[" . $key . "]", $ex->getMessage(), 'From: website@valuebasket.com');
                            }
                            $new_lang_text = preg_replace('/\<br(\s*)?\/?\>(\n)*/i', "\n", $new_lang_text); // convert from br to nl
                            //$new_pcex_obj->{"set_{$key}"}($new_lang_text);

                            switch ($key) {
                                case 'feature':
                                    if ($new_pcex_obj->get_feature_original() != 1)
                                        $new_pcex_obj->{"set_{$key}"}($new_lang_text);
                                    break;
                                case 'specification':
                                    if ($new_pcex_obj->get_spec_original() != 1)
                                        $new_pcex_obj->{"set_{$key}"}($new_lang_text);
                                    break;
                                default:
                                    $new_pcex_obj->{"set_{$key}"}($new_lang_text);
                                    break;
                            }

                        }
                    }
                    $this->get_pcext_dao()->$pcex_action($new_pcex_obj);
                }
            }

            if($keyword_list = $this->get_pk_dao()->get_list(array("sku"=>$sku, "lang_id"=>"en")))
            {
                if($new_pc_obj->get_keywords_original() != 1){
                    $pk_vo = $this->get_pk_dao()->get();
                    $this->get_pk_dao()->q_delete(array("sku"=>$sku, "lang_id"=>$lang_id));

                    if($pc_lang_obj = $this->get_pc_dao()->get(array("prod_sku"=>$sku, "lang_id"=>$lang_id)))
                    {
                        # SBF#3041 keywords already assembled in product_content.keywords,
                        # we don't want to mess the algo up with bing translate
                        $keywords_arr = explode("\n", $pc_lang_obj->get_keywords());
                        foreach ($keywords_arr as $keywords)
                        {
                            $pk_obj = clone $pk_vo;
                            $pk_obj->set_sku($sku);
                            $pk_obj->set_lang_id($lang_id);
                            $pk_obj->set_keyword($keywords);
                            $pk_obj->set_type(1);
                            $this->get_pk_dao()->insert($pk_obj);
                        }
                    }
                }

                // foreach($keyword_list as $obj)
                // {
                // 	$new_lang_text = "";
                // 	$source_text = $obj->get_keyword();
                // 	try
                // 	{
                // 		$this->get_translate_service()->translate(nl2br($source_text), $new_lang_text, "en", $lang_id);
                // 	}
                // 	catch(Exception $ex)
                // 	{
                // 		$new_lang_text = "";
                // 		mail("bd_product_team@eservicesgroup.com", "Translation error sku=" . $sku . "[keyword]", $ex->getMessage(), 'From: website@valuebasket.com');
                // 	}
                // 	$new_lang_text = preg_replace('/\<br(\s*)?\/?\>(\n)*/i', "\n", $new_lang_text); // convert from br to nl

                // 	if(!$obj = $this->get_pk_dao()->get(array("sku"=>$sku, "lang_id"=>$lang_id, "keyword"=>$keyword)))
                // 	{
                // 		$pk_obj = clone $pk_vo;
                // 		$pk_obj->set_sku($sku);
                // 		$pk_obj->set_lang_id($lang_id);
                // 		$pk_obj->set_keyword($new_lang_text);
                // 		$pk_obj->set_type(1);
                // 		$this->get_pk_dao()->insert($pk_obj);
                // 	}
                // }
            }
        }
    }

    public function translate_product_enhance_content($sku = "", $lang_id = "en")
    {
//		$forcingEnFields = array("ru" => array("prod_name"));
        if($sku)
        {
            $translate_arr = array();
            $pcex_action = "update";
            if(!$new_pcex_obj = $this->get_pcext_dao()->get(array("prod_sku"=>$sku, "lang_id"=>$lang_id)))
            {
                $pcex_action = "insert";
                $new_pcex_obj = $this->get_pcext_dao()->get();
                $new_pcex_obj->set_prod_sku($sku);
                $new_pcex_obj->set_lang_id($lang_id);
            }

            if($pcex_obj = $this->get_pcext_dao()->get(array("prod_sku"=>$sku, "lang_id"=>"en")))
            {
                if (in_array($lang_id, $this->copyEnLang))
                {
                    $pcex_obj->set_lang_id("pl");
                    $this->get_pcext_dao()->$pcex_action($pcex_obj);
                }
                else
                {
                    $translate_arr = array(
                                            "enhanced_listing" => $pcex_obj->get_enhanced_listing()
                                        );

                    foreach($translate_arr as $key=>$source_text)
                    {
                        if(!empty($source_text))
                        {
                            $new_lang_text = "";

                            try
                            {
                                $this->get_translate_service()->translate(nl2br($source_text), $new_lang_text, "en", $lang_id);
                            }
                            catch(Exception $ex)
                            {
                                $new_lang_text = "";
                                mail("bd_product_team@eservicesgroup.com", "Translation error sku=" . $sku . "[" . $key . "]", $ex->getMessage(), 'From: website@valuebasket.com');
                            }
                            $new_lang_text = preg_replace('/\<br(\s*)?\/?\>(\n)*/i', "\n", $new_lang_text); // convert from br to nl

                            $new_pcex_obj->{"set_{$key}"}($new_lang_text);

                            $this->get_pcext_dao()->$pcex_action($new_pcex_obj);

                        }
                    }

                }
            }

            // $translate_arr = array();
            // $pcex_action = "update";
            // if(!$new_pcex_obj = $this->get_pcext_dao()->get(array("prod_sku"=>$sku, "lang_id"=>$lang_id)))
            // {
            // 	$pcex_action = "insert";
            // 	$new_pcex_obj = $this->get_pcext_dao()->get();
            // 	$new_pcex_obj->set_prod_sku($sku);
            // 	$new_pcex_obj->set_lang_id($lang_id);
            // }

            // if($pcex_obj = $this->get_pcext_dao()->get(array("prod_sku"=>$sku, "lang_id"=>"en")))
            // {
            // 	if (in_array($lang_id, $copyEnLang))
            // 	{
            // 		$pcex_obj->set_lang_id("pl");
            // 		$this->get_pcext_dao()->$pcex_action($pcex_obj);
            // 	}
            // 	else
            // 	{
            // 		$translate_arr = array(
            // 								"feature" => $pcex_obj->get_feature(),
            // 								"specification" => $pcex_obj->get_specification(),
            // 								"requirement" => $pcex_obj->get_requirement(),
            // 								"instruction" => $pcex_obj->get_instruction(),
            // 							);

            // 		foreach($translate_arr as $key=>$source_text)
            // 		{
            // 			if(!empty($source_text))
            // 			{
            // 				$new_lang_text = "";
            // 				try
            // 				{
            // 					$this->get_translate_service()->translate(nl2br($source_text), $new_lang_text, "en", $lang_id);
            // 				}
            // 				catch(Exception $ex)
            // 				{
            // 					$new_lang_text = "";
            // 					mail("bd_product_team@eservicesgroup.com", "Translation error sku=" . $sku . "[" . $key . "]", $ex->getMessage(), 'From: website@valuebasket.com');
            // 				}
            // 				$new_lang_text = preg_replace('/\<br(\s*)?\/?\>(\n)*/i', "\n", $new_lang_text); // convert from br to nl
            // 				$new_pcex_obj->{"set_{$key}"}($new_lang_text);
            // 			}
            // 		}
            // 		$this->get_pcext_dao()->$pcex_action($new_pcex_obj);
            // 	}
            // }

            // if($keyword_list = $this->get_pk_dao()->get_list(array("sku"=>$sku, "lang_id"=>"en")))
            // {
            // 	$pk_vo = $this->get_pk_dao()->get();
            // 	$this->get_pk_dao()->q_delete(array("sku"=>$sku, "lang_id"=>$lang_id));

            // 	if($pc_lang_obj = $this->get_pc_dao()->get(array("prod_sku"=>$sku, "lang_id"=>$lang_id)))
            // 	{
            // 		# SBF#3041 keywords already assembled in product_content.keywords,
            // 		# we don't want to mess the algo up with bing translate
            // 		$keywords_arr = explode("\n", $pc_lang_obj->get_keywords());
            // 		foreach ($keywords_arr as $keywords)
            // 		{
            // 			$pk_obj = clone $pk_vo;
            // 			$pk_obj->set_sku($sku);
            // 			$pk_obj->set_lang_id($lang_id);
            // 			$pk_obj->set_keyword($keywords);

        }
    }

    public function generate_keywords($sku, $attributes_arr=array(), $lang_id = 'en')
    {
        #SBF 3041 piece attributes together to form keywords
        $keywords = $model = array();
        $generated_keywords = $error_msg = "";
        $status = FALSE;

        if($sku && $attributes_arr["model_1"])
        {
            $model[] = trim($attributes_arr["model_1"]);
            # model_2 to model_5 optional, so put into array only if filled
            if($attributes_arr["model_2"]) $model[] = trim($attributes_arr["model_2"]);
            if($attributes_arr["model_3"]) $model[] = trim($attributes_arr["model_3"]);
            if($attributes_arr["model_4"]) $model[] = trim($attributes_arr["model_4"]);
            if($attributes_arr["model_5"]) $model[] = trim($attributes_arr["model_5"]);
            if($attributes_arr["series"]) $series = trim($attributes_arr["series"]);
            $brand_name = $this->get_brand_dao()->get(array("id"=>$attributes_arr["brand_id"]))->get_brand_name();

            $colour_name = NULL;
            if($attributes_arr['colour_id'] !== "NA")
            {
                if($lang_id == 'en')
                {
                    $colour_name = $this->get_colour_dao()->get(array("id"=>$attributes_arr['colour_id']))->get_name();
                }
                else
                {
                    if($colour_ext_obj = $this->get_colour_ext_dao()->get(array("colour_id"=>$attributes_arr['colour_id'], "lang_id"=>$lang_id)))
                    {
                        $colour_name = $colour_ext_obj->get_name();
                    }
                    else
                    {
                        $_SESSION["NOTICE"] = "WARNING: Language <$lang_id> does not have translation for colour id <{$attributes_arr['colour_id']}>. Please check and re-translate.";
                    }
                }
            }

            foreach ($model as $key => $model_name)
            {
                $keywords[] = "$brand_name $model_name";
                // $keywords[] = "[$brand_name $model_name]";
                // $keywords[] = "\"$brand_name $model_name\"";
                // $keywords[] = "+$brand_name +$model_name";

                if($series)
                {
                    $keywords[] = "$brand_name $series $model_name";
                    // $keywords[] = "[$brand_name $series $model_name]";
                    // $keywords[] = "\"$brand_name $series $model_name\"";
                    // $keywords[] = "+$brand_name +$series +$model_name";

                    if($colour_name)
                    {
                        $keywords[] = "$brand_name $series $model_name $colour_name";
                        // $keywords[] = "[$brand_name $series $model_name $colour_name]";
                        // $keywords[] = "\"$brand_name $series $model_name $colour_name\"";
                        // $keywords[] = "+$brand_name +$series +$model_name +$colour_name";
                    }
                }

                if (!$series && $colour_name)
                {
                    $keywords[] = "$brand_name $model_name $colour_name";
                    // $keywords[] = "[$brand_name $model_name $colour_name]";
                    // $keywords[] = "\"$brand_name $model_name $colour_name\"";
                    // $keywords[] = "+$brand_name +$model_name +$colour_name";
                }
            }

            if($generated_keywords = implode("\n", $keywords))
            {
                $status = TRUE;
            }
            else
            {
                $error_msg = __FILE__." - Line ".__LINE__." - Error generating Related Keywords for SKU<$sku> - lang_id <$lang_id>.";
            }
        }
        else
        {
            $error_msg = __FILE__." - Line ".__LINE__." - Error generating keywords for lang_id <$lang_id> -
                        The following cannot be empty: \nSKU: $sku \nmodel_1: {$attributes_arr['model_1']}";
        }

        $gen_ret = array("status"=>$status, "keywords"=>$generated_keywords, "error_msg"=>$error_msg);
        return $gen_ret;
    }

    public function update_series($sku, $series="")
    {
        #SBF 3041 update series in tb product_content for all languages - don't translate
        $pc_dao = $this->get_pc_dao();
        $pc_list = $pc_dao->get_list(array("prod_sku"=>$sku));
        $error_msg = "";

        foreach ($pc_list as $pc_obj)
        {
            $lang_id = $pc_obj->get_lang_id();
            $pc_obj->set_series($series);
            $ret = $pc_dao->update($pc_obj);

            if($ret === FALSE)
            {
                $error_msg = "ERROR: Cannot update series <$series> for lang_id <$lang_id>.
                                DB ERROR MSG: ".$pc_dao->db->_error_message();
            }
        }

        if($error_msg)
            return $error_msg;
        else
            return TRUE;
    }

    public function is_clearance($sku)
    {
        return $this->get_dao()->is_clearance($sku);
    }

    public function find_delayed_ebay_orders()
    {

        $query =
        "
select
    DISTINCT so.so_no, so.platform_id
from so
left join so_hold_reason sr on sr.so_no = so.so_no
inner join so_item si on si.so_no = so.so_no
inner join product p on p.sku = si.prod_sku
where 1

# only these platform IDs
and (platform_id = 'QOO10SG' or platform_id = 'TMNZ' or platform_id = 'EBAYAU' or platform_id = 'EBAYMY' or platform_id = 'EBAYSG' or platform_id = 'EBAYGB' or platform_id = 'EBAYUS' or platform_id = 'FNACES' or platform_id = 'RAKUES')

# Orders Status: Paid (Not Shipped)
and (so.status >= 2 and so.status <= 4)

#Refund request: No
and so.refund_status = 0

# Hold request: Yes and No
and (so.hold_status = 0 or so.hold_status = 1)

# Not OOS
and not (sr.reason = 'oos' and so.hold_status = 1)

and so.order_create_date >= DATE_SUB(CONCAT(CURDATE(), ' 00:00:00'), INTERVAL 8 DAY)
and so.order_create_date <= DATE_SUB(CONCAT(CURDATE(), ' 00:00:00'), INTERVAL 7 DAY)

order by so.platform_id, so.so_no
        ";

        $result = $this->db->query($query);
        if ($result)
        {
            foreach ($result->result_array() as $obj)
                $rs[] = $obj["platform_id"]." => ".$obj["so_no"];

            return $rs;
        }

        // var_dump($this->db->last_query());
        return null;
    }

    public function find_active_master_sku_from_list($sku_list)
    {
        $sku_string = implode("','", $sku_list);
        $sku_string = "'$sku_string'";

        $query =
        "
            select
                s.ext_sku
            from product p
            inner join sku_mapping s on s.sku = p.sku and s.`status` = 1 and s.ext_sys = 'WMS'
            where 1    							# find all that are
            and p.sourcing_status <> 'D'		# not discontinued
            and p.status <> 0		# not inactive
            and s.ext_sku in ($sku_string)		# and in this list
        ";

        $result = $this->db->query($query);
        foreach ($result->result_array() as $obj)
            $rs[] = $obj["ext_sku"];

        // var_dump($this->db->last_query());
        return $rs;
    }

    public function set_surplus_quantity($sku, $qty)
    {
        return $this->get_dao()->set_surplus_quantity($sku, $qty);
    }

    public function get_product_category_report($where, $option)
    {
        include_once(APPPATH."libraries/service/Data_exchange_service.php");
        $dex = new Data_exchange_service();

        $report_list = $this->get_dao()->get_product_category_list($where, $option);
        $out_xml = new Vo_to_xml($report_list, APPPATH.'data/product_category_report_vo2xml.txt');
        $out_csv = new Xml_to_csv("", APPPATH.'data/product_category_report_xml2csv.txt', TRUE, ',');

        return $dex->convert($out_xml, $out_csv);
    }

    public function get_lang_osd_list()
    {
        return $this->_osd_lang_list;
    }
}
