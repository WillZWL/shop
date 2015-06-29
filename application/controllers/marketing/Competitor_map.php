<?php
class Competitor_map extends MY_Controller
{

    private $app_id = 'MKT0075';
    private $lang_id = 'en';

    //must set to public for view
    public $default_platform_id;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url','notice','image'));
        $this->load->library('input');
        $this->load->model('marketing/competitor_map_model');
        $this->load->model('marketing/product_model');
        $this->load->model('marketing/competitor_model');
        $this->load->library('service/pagination_service');
        $this->load->library('service/context_config_service');
        $this->load->library('service/sku_mapping_service');
        $this->load->library('service/product_service');
        $this->default_platform_id = $this->context_config_service->value_of("default_platform_id");
    }

    public function index($country_id="")
    {
        $data = array();
        include_once APPPATH."language/".$this->_get_app_id()."00_".$this->_get_lang_id().".php";
        $data["lang"] = $lang;
        $data["country_list"] = $this->competitor_map_model->get_sell_country_list();
        if ($country_id)
        {
            $data["country_id"] = $country_id;
        }
        foreach ($data["country_list"] as $country_obj)
        {
            if($country_id == $country_obj->get_id())
            {
                $data["currency"] = $country_obj->get_currency_id();
                break;
            }
        }
        $this->load->view("marketing/competitor_map/competitor_map_index",$data);
    }

    public function plist()
    {
        # product list on left sidebar
        $where = array();
        $option = array();
        $sub_app_id = $this->_get_app_id()."02";
        include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
        $data["lang"] = $lang;

        $sku = $this->input->get("sku");
        $prod_name = $this->input->get("name");
        $master_sku = $this->input->get("master_sku");
        $country_id = $this->input->get("country_id");

        if ($sku != "" || $prod_name != "" || $master_sku != "")
        {
            $data["search"] = 1;
            if ($sku != "")
            {
                $where["sku"] = $sku;
            }

            if ($master_sku != "")
            {
                $where['master_sku'] = $master_sku;
            }

            if ($prod_name != "")
            {
                $where["name"] = $prod_name;
            }

            $sort = $this->input->get("sort");
            $order = $this->input->get("order");

            $limit = '20';

            $pconfig['base_url'] = current_url()."?".$_SERVER['QUERY_STRING'];
            $option["limit"] = $pconfig['per_page'] = $limit;

            if ($option["limit"])
            {
                $option["offset"] = $this->input->get("per_page");
            }

            if (empty($sort))
                $sort = "sku";

            if (empty($order))
                $order = "asc";

            $option["orderby"] = $sort." ".$order;

            $option["exclude_bundle"] = 1;
            $data["objlist"] = $this->competitor_map_model->get_product_list($where, $option);
            $data["total"] = $this->competitor_map_model->get_product_list_total($where, $option);

            if($data["total"] == 1)
            {
                foreach ($data["objlist"] as $obj)
                {
                    $data["sku"] = $obj->get_sku();
                }
            }

            $data["country_id"] = $country_id;
            $pconfig['total_rows'] = $data['total'];
            $this->pagination_service->set_show_count_tag(TRUE);
            $this->pagination_service->msg_br = TRUE;
            $this->pagination_service->initialize($pconfig);

            $data["notice"] = notice($lang);

            $data["sortimg"][$sort] = "<img src='".base_url()."images/".$order.".gif'>";
            $data["xsort"][$sort] = $order=="asc"?"desc":"asc";
        }

        $this->load->view('marketing/competitor_map/competitor_map_list', $data);
    }

    public function view($country_id="", $sku="")
    {
        # main iframe
        if($sku == "" || $country_id == "")
        {
            exit;
        }

        $data = array();
        $data["prompt_notice"] = 0;
        $data["country_id"] = $country_id;
        $data["website_link"] = $this->context_config_service->value_of("website_domain");
        define('IMG_PH', $this->context_config_service->value_of("prod_img_path"));

        if($this->input->post('posted'))
        {
            $this->competitor_map_model->__autoload_competitor_map_vo();
            $master_sku = $this->sku_mapping_service->get_master_sku(array("sku"=>$sku, 'ext_sys'=>'WMS', 'status'=>1));
            if($master_sku)
            {
                # update existing competitors' info
                $comp_obj = $this->input->post('comp');
                if($comp_obj)
                {
                    foreach ($comp_obj as $key => $val)
                    {
                        $currprice = $val["price"];
                        if(!$currprice)
                        {
                            $currprice = 0;
                        }
                        $currurl = $val["url"];
                        $currnote_1 = $val["note_1"];
                        $currnote_2 = $val["note_2"];
                        $currshipcharge = $val["comp_ship_charge"];
                        $currstatus = $val["status"];
                        $currmatch = $val["match"];

                        if($val["reprice_min_margin"] == "")
                            $val["reprice_min_margin"] = "9.00";
                        $currrepricemargin = number_format($val["reprice_min_margin"], 2, '.', '');

                        if($val["reprice_value"] == "")
                            $val["reprice_value"] = "0.00";
                        $currrepricevalue = number_format($val["reprice_value"], 2, '.', '');

                        $id = $val["competitor_id"];
                        $competitor_map_obj = $this->competitor_map_model->get_competitor_map_obj(array("ext_sku"=>$master_sku, "competitor_id"=>$id));
                        if($competitor_map_obj)
                        {
                            $action = "update";

                            if ($competitor_map_obj->get_now_price() != $currprice ||
                                $competitor_map_obj->get_product_url() != $currurl ||
                                $competitor_map_obj->get_note_1() != $currnote_1 ||
                                $competitor_map_obj->get_note_2() != $currnote_2 ||
                                $competitor_map_obj->get_comp_ship_charge() != $currshipcharge ||
                                $competitor_map_obj->get_status() != $currstatus ||
                                $competitor_map_obj->get_status() == 0  ||
                                $competitor_map_obj->get_match() != $currmatch ||
                                $competitor_map_obj->get_reprice_min_margin() != $currrepricemargin ||
                                $competitor_map_obj->get_reprice_value() != $currrepricevalue
                                )
                            {
                                $competitor_map_obj->set_now_price($currprice);
                                $competitor_map_obj->set_product_url($currurl);
                                $competitor_map_obj->set_note_1($currnote_1);
                                $competitor_map_obj->set_note_2($currnote_2);
                                $competitor_map_obj->set_comp_ship_charge($currshipcharge);
                                if($currstatus != "")
                                {
                                    $competitor_map_obj->set_status($currstatus);
                                }

                                if($currstatus == 0)
                                {
                                    # change match to Ignore if status changed to Inactive
                                    $currmatch = 0;
                                }

                                $competitor_map_obj->set_match($currmatch);
                                $competitor_map_obj->set_reprice_min_margin($currrepricemargin);
                                $competitor_map_obj->set_reprice_value($currrepricevalue);

                                $ret = $this->competitor_map_model->$action($competitor_map_obj);
                                if($ret === FALSE)
                                {
                                    $_SESSION["NOTICE"] = "{$action}_failed ".$this->db->_error_message();
                                }
                                else
                                {
                                    unset($_SESSION["competitor_map_obj"][$id]);
                                    if($this->input->post('target') != "")
                                    {
                                        $data["prompt_notice"] = 1;
                                    }

                                    $_SESSION["NOTICE"] = "$action competitor Success!";
                                }
                            }
                            else
                            {
                                $_SESSION["NOTICE"] = "$action competitor Success!";
                            }
                        }
                        else
                        {
                            $_SESSION["NOTICE"] = "{$action}_failed - ".$this->db->_error_message();
                        }
                    }
                }

                if($add_obj = $_POST["add"])
                {
                    #insert new competitors

                    $action = "insert";
                    $this->competitor_model->__autoload_competitor_vo();

                    foreach ($add_obj as $key => $var)
                    {
                        if($name = $var["name"])
                        {
                            #db competitor table
                            if($competitor_obj = $this->competitor_model->get_competitor_obj(array("competitor_name"=>$name, "country_id"=>$country_id)))
                            {
                                #if competitor already exists
                                $competitor_id = $competitor_obj->get_id();
                            }
                            else
                            {
                                #if does not exist, create then get id
                                $competitor_obj = $this->competitor_model->get_competitor_obj();
                                $competitor_obj->set_competitor_name($name);
                                $competitor_obj->set_country_id($country_id);
                                $competitor_obj->set_status(1);

                                $ret = $this->competitor_model->$action($competitor_obj);
                                if($ret === FALSE)
                                {
                                    $_SESSION["NOTICE"] = "{$action}_failed ".$this->db->_error_message();
                                }
                                else
                                {
                                    # after created successfully, get the competitor id
                                    if($competitor_obj = $this->competitor_model->get_competitor_obj(array("competitor_name"=>$name)))
                                    {
                                        $competitor_id = $competitor_obj->get_id();
                                    }
                                    else
                                    {
                                        $_SESSION["NOTICE"] = "{$action}_failed ".$this->db->_error_message();
                                    }
                                }
                            }

                            if($competitor_id)
                            {
                                $addprice = $var["price"];
                                if(!$addprice)
                                {
                                    $addprice = 0;
                                }
                                $addurl = $var["url"];
                                $addnote_1 = $var["note_1"];
                                $addnote_2 = $var["note_2"];
                                $comp_ship_charge = $var["comp_ship_charge"];
                                $addstatus = $var["status"];
                                $addmatch = $var["match"];

                                if($var["reprice_min_margin"] == "")
                                    $var["reprice_min_margin"] = "9.00";
                                $addrepricemargin = number_format($var["reprice_min_margin"], 2, '.', '');

                                if($var["reprice_value"] == "")
                                    $var["reprice_value"] = 0;
                                $addrepricevalue = number_format($var["reprice_value"], 2, '.', '');

                                if($addstatus == 0)
                                {
                                    # Change Match to Ignore if Status set to Inactive
                                    $addmatch = 0;
                                }

                                $competitor_map_obj = $this->competitor_map_model->get_competitor_map_obj();
                                $competitor_map_obj->set_ext_sku($master_sku);
                                $competitor_map_obj->set_competitor_id($competitor_id);
                                $competitor_map_obj->set_last_price(0);
                                $competitor_map_obj->set_now_price($addprice);
                                $competitor_map_obj->set_product_url($addurl);
                                $competitor_map_obj->set_note_1($addnote_1);
                                $competitor_map_obj->set_note_2($addnote_2);
                                $competitor_map_obj->set_comp_ship_charge($comp_ship_charge);
                                $competitor_map_obj->set_status($addstatus);
                                $competitor_map_obj->set_match($addmatch);
                                $competitor_map_obj->set_reprice_min_margin($addrepricemargin);
                                $competitor_map_obj->set_reprice_value($addrepricevalue);


                                $ret = $this->competitor_map_model->$action($competitor_map_obj);
                                if($ret === FALSE)
                                {
                                    $_SESSION["NOTICE"] = "{$action}_failed ".$this->db->_error_message();
                                }
                                else
                                {
                                    unset($_SESSION["competitor_map_obj"][$id]);

                                    if($this->input->post('target') != "")
                                    {
                                        $data["prompt_notice"] = 1;
                                    }

                                    $_SESSION["NOTICE"] = "$action new competitor Success!";
                                }
                            }
                        }
                        else
                        {
                            unset($_SESSION["competitor_map_obj"][$id]);
                        }
                    }
                }
            }
            else
            {
                echo "<script language='javascript'>alert('No master_sku found - SKU unmapped.')</script>";
            }

            if($data["prompt_notice"])
            {
                $_SESSION["NOTICE"] = "Update successful!";
            }

            Redirect(base_url()."marketing/competitor_map/view/$country_id/$sku");
        }

        include_once APPPATH."language/".$this->_get_app_id()."01_".$this->_get_lang_id().".php";
        $data["lang"] = $lang;
        $data["canedit"] = 1;
        $data["sku"] = $sku;
        $data["target"] = $this->input->get('target');
        $data["notice"] = notice($lang);

        if($sku != "")
        {
            $master_sku = $this->sku_mapping_service->get_master_sku(array("sku"=>$sku));
            if($master_sku)
            {
                # get info from active competitors mapped to product (tb competitor & competitor_map)
                if($active_mapped_competitor_obj = $this->competitor_map_model->competitor_map_service->get_active_mapped_list($country_id, $master_sku))
                {
                    foreach ($active_mapped_competitor_obj as $active_competitor_list)
                    {
                        $objcount++;
                        $competitor_id = $active_competitor_list["competitor_id"];

                        # get info from competitor table
                        $competitor_obj  = $this->competitor_model->get_competitor_obj(array("id"=>$competitor_id));
                        $active_competitor_list["name"] = $competitor_obj->get_competitor_name();
                        $active_competitor_list["country_id"] = $competitor_obj->get_country_id();

                        // $data["active_competitor_list"][] = $active_competitor_list;

                        if($active_competitor_list["status"] == 1)
                        {
                            # we arrange this array using comp id so that it can be sorted by total_price later
                            $data["active_compmap_list"][$competitor_id] = $active_competitor_list;

                            $active_competitor_list["comp_ship_charge"] = number_format($active_competitor_list["comp_ship_charge"], '2', '.', '');
                            if(empty($active_competitor_list["comp_ship_charge"]))
                                $active_competitor_list["comp_ship_charge"] = 0;

                            $data["total_price_arr"][$competitor_id] = number_format(($active_competitor_list["now_price"] + $active_competitor_list["comp_ship_charge"]), 2, '.', '');
                        }
                        else
                        {
                            # this is the list of competitor_map that has cmap.status = 0 (put them at end of list)
                            $data["inactive_compmap_list"][] = $active_competitor_list;
                        }

                        $_SESSION["competitor_map_obj"][$competitor_id] = serialize($competitor_obj);
                    }

                    if($data["total_price_arr"])
                    {
                        asort($data["total_price_arr"]);
                    }
                }

                # get all active competitors in the country but unmapped to SKU - for dropdown to add new comp
                if($active_competitor_obj = $this->competitor_model->competitor_service->get_list(array("country_id"=>$country_id, "status"=>1)))
                {
                    $objcount2 = 0;
                    foreach ($active_competitor_obj as $active_competitor_list)
                    {
                        $objcount2++;
                        $competitor_id = $active_competitor_list->get_id();

                        # check if this competitor is already mapped in competitor_map
                        $competitor_map_obj  = $this->competitor_map_model->get_competitor_map_obj(array("competitor_id"=>$competitor_id, "ext_sku"=>$master_sku));
                        if(empty($competitor_map_obj))
                        {
                            $data["unmapped_competitor_list"][] = $active_competitor_list->get_competitor_name();
                        }
                    }

                    $objcount2 = $objcount2-$objcount;
                }

                # get all previously mapped SKUs with disabled competitor
                if($inactive_competitor_list = $this->competitor_model->competitor_service->get_list(array("country_id"=>$country_id, "status"=>0)))
                {
                    foreach ($inactive_competitor_list as $inactive_competitor_obj)
                    {
                        $competitor_id = $inactive_competitor_obj->get_id();

                        # get info from competitor map table
                        $competitor_map_obj  = $this->competitor_map_model->get_competitor_map_obj(array("competitor_id"=>$competitor_id, "ext_sku"=>$master_sku));
                        if ($competitor_map_obj)
                        {
                            $inactive_comp_list["id"] = $inactive_competitor_obj->get_id();
                            $inactive_comp_list["name"] = $inactive_competitor_obj->get_competitor_name();
                            $inactive_comp_list["price"] = $competitor_map_obj->get_now_price();
                            $inactive_comp_list["url"] = $competitor_map_obj->get_product_url();
                            $data["inactive_mapped_list"][] = $inactive_comp_list;
                        }
                    }
                }

                $data["country_list"] = $this->competitor_map_model->get_sell_country_list();
                $data["objcount"] = $objcount;
                $data["objcount2"] = $objcount2;
                $data["sku"] = $sku;
                $prod_obj = $this->competitor_map_model->get_prod($sku);
            }
            else
            {
                echo "<script language='javascript'>alert('No master_sku found - SKU unmapped.')</script>";
            }
        }

        $data["prod_obj"] = $prod_obj;
        $data['master_sku'] = $master_sku;
        $_SESSION["prod_obj"] = serialize($prod_obj);

        $this->load->view("marketing/competitor_map/competitor_map_view",$data);

    }

    public function _get_app_id()
    {
        return $this->app_id;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }
}

?>