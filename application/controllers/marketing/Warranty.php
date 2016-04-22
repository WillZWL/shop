<?php

class Warranty extends MY_Controller
{
    private $appId = "MKT0069";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('marketing/warranty_model');
        $this->load->model('marketing/product_model');
        $this->load->helper(array('url', 'notice', 'object', 'image'));
        $this->load->library('service/pagination_service');
        $this->load->library('service/context_config_service');
        $this->load->library('service/subject_domain_service');
        $this->load->library('service/translate_service');
        $this->load->library('service/country_service');
        $this->load->library('service/selling_platform_service');
    }

    public function index($prod_grp_cd = "")
    {
        if ($this->input->get('update')) {
            if ($selected_sku = $this->input->get('warranty_check')) {
                $warranty_value_array = $this->input->get('warranty_value');
                foreach ($selected_sku as $sku => $value) {
                    $result = $this->warranty_model->update_warranty($sku, $warranty_value_array[$sku]);

                    // SBF 4402 warranty for different countries
                    // for receiving the changes of list
                    $warranty_country_counter = 0;

                    $tmp = $this->input->get('warranty_country_' . $warranty_country_counter);
                    $tmp2 = $this->input->get('warranty_in_month_' . $warranty_country_counter);
                    while ($tmp[$sku]) {
                        //$country_id = $this->input->get('warranty_country_'.$warranty_country_counter.'[$sku]');
                        //$warranty_in_month = $this->input->get('warranty_in_month_'.$warranty_country_counter.'[$sku]');
                        $warranty_platform_id = $tmp[$sku];
                        $warranty_in_month = $tmp2[$sku];

                        if ($warranty_in_month === "") {
                            if ($old_obj = $this->warranty_model->get_product_warranty_dao()->get(array('platform_id' => $warranty_platform_id, 'sku' => $sku))) {
                                $this->warranty_model->get_product_warranty_dao()->delete($old_obj);
                            }
                        } else {
                            if (!$this->warranty_model->update_country_warranty($sku, $warranty_platform_id, $warranty_in_month)) {
                                $country_warranty_obj = $this->warranty_model->get_country_warranty();
                                $country_warranty_obj->set_sku($sku);
                                $country_warranty_obj->set_platform_id($warranty_platform_id);
                                $country_warranty_obj->set_warranty_in_month($warranty_in_month);
                                $this->warranty_model->add_country_warranty($country_warranty_obj);
                            }
                        }
                        $warranty_country_counter++;
                        $tmp = $this->input->get('warranty_country_' . $warranty_country_counter);
                        $tmp2 = $this->input->get('warranty_in_month_' . $warranty_country_counter);
                    }
                }
            }

        }

        $sub_app_id = $this->getAppId() . "00";
        $_SESSION["LISTPAGE"] = ($prod_grp_cd == "" ? base_url() . "marketing/warranty/?" : current_url()) . $_SERVER['QUERY_STRING'];

        $where = array();
        $option = array();

        $submit_search = 0;

        if ($prod_grp_cd != "") {
            $where["prod_grp_cd"] = $prod_grp_cd;
        }

        if ($this->input->get("sku") != "") {
            $where["sku"] = $this->input->get("sku");
            $submit_search = 1;
        }

        if ($this->input->get("name") != "") {
            $where["name"] = $this->input->get("name");
            $submit_search = 1;
        }
        /*
                if ($this->input->get("colour") != "")
                {
                    $where["colour"] = $this->input->get("colour");
                    $submit_search = 1;
                }
        */
        if ($this->input->get("cat_id") != "") {
            $where["cat_id"] = $this->input->get("cat_id");
            $submit_search = 1;
        }

        if ($this->input->get("sub_cat_id") != "") {
            $where["sub_cat_id"] = $this->input->get("sub_cat_id");
            $submit_search = 1;
        }

        if ($this->input->get("sub_sub_cat_id") != "") {
            $where["sub_sub_cat_id"] = $this->input->get("sub_sub_cat_id");
            $submit_search = 1;
        }

        if ($this->input->get("brand") != "") {
            $where["brand"] = $this->input->get("brand");
            $submit_search = 1;
        }

        if ($this->input->get("status") != "") {
            $where["status"] = $this->input->get("status");
            $submit_search = 1;
        }

        if ($this->input->get("warranty_in_month") !== "") {
            $where["warranty_in_month"] = $this->input->get("warranty_in_month");
            $submit_search = 1;
        }


        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        $limit = '20';

        $pconfig['base_url'] = $_SESSION["LISTPAGE"];
        $option["limit"] = $pconfig['per_page'] = $limit;
        if ($option["limit"]) {
            $option["offset"] = $this->input->get("per_page");
        }

        if (empty($sort))
            $sort = "p.create_on";

        if (empty($order))
            $order = "desc";

        $option["orderby"] = $sort . " " . $order;
        $option["exclude_bundle"] = 1;

        if ($this->input->get("search") || $prod_grp_cd != "") {
            $data["objlist"] = $this->warranty_model->get_product_list($where, $option);
            $data["total"] = $this->warranty_model->get_product_list_total($where);
        }

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;

        $pconfig['total_rows'] = $data['total'];
        $this->pagination_service->set_show_count_tag(TRUE);
        $this->pagination_service->initialize($pconfig);

        $data["notice"] = notice($lang);
        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
//      $data["searchdisplay"] = ($submit_search)?"":'style="display:none"';
        $data["searchdisplay"] = "";
        $data["prod_grp_cd"] = $prod_grp_cd;
        $data["warranty_list"] = $this->product_model->get_valid_warrant_period_list();
        $data['selling_platform_list'] = $this->sc['SellingPlatform']->getPlatformListWithAllowSellCountry("WEBSITE");
        $this->load->view('marketing/warranty/warranty_index_v', $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function js_catlist()
    {
        header("Content-type: text/javascript; charset: UTF-8");
        header("Cache-Control: must-revalidate");
        $offset = 60 * 60 * 24;
        $ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
        header($ExpStr);
        $cat_list = $this->warranty_model->get_list("category", array("id >" => "0"), array("orderby" => "parent_cat_id ASC", "limit" => "-1"));
        foreach ($cat_list as $cat) {
            $pid = $cat->get_parent_cat_id();
            $cat_id = str_replace("'", "\'", $cat->get_id());
            $cat_name = str_replace("'", "\'", $cat->get_name());
            $jscatlist[$pid][] = "'" . $cat_id . "':'" . $cat_name . "'";
        }
        foreach ($jscatlist as $jspid => $jssub) {
            $jscat[] = "'" . $jspid . "': {" . (implode(", ", $jssub)) . "}";
        }
        $js = "catlist = {" . implode(", ", $jscat) . "};";
        $js .= "
            function ChangeCat(val, obj, obj2)
            {
                obj.length = 1;
                if (obj2)
                {
                    obj2.length = 1;
                }
                for (var i in catlist[val]){
                    obj.options[obj.options.length]=new Option(catlist[val][i], i);
                }
            }";
        echo $js;
    }

    public function js_feedcatlist()
    {
        header("Content-type: text/javascript; charset: UTF-8");
        header("Cache-Control: must-revalidate");
        $offset = 60 * 60 * 24;
        $ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
        header($ExpStr);
        $cat_list = $this->product_feed_service->get_pfc_dao()->get_list(array(), array("orderby" => "feeder, cat, sub_cat, sub_sub_cat"));
        foreach ($cat_list as $feedcat) {
            $feeder = str_replace("'", "\'", $feedcat->get_feeder());
            $cat = str_replace("'", "\'", $feedcat->get_cat());
            $sub_cat = str_replace("'", "\'", $feedcat->get_sub_cat());
            $sub_sub_cat = str_replace("'", "\'", $feedcat->get_sub_sub_cat());
            $jsfeedcatlist[$feeder][$cat][$sub_cat][] = "'" . $sub_sub_cat . "':''";
        }
        foreach ($jsfeedcatlist as $jsfeed => $jscat) {
            $jscats = array();
            foreach ($jscat as $jspcat => $jssub) {
                $jssubcats = array();
                foreach ($jssub as $jspsubcat => $jssubsub) {
                    $jssubcats[] = "'" . $jspsubcat . "': {" . (implode(", ", $jssubsub)) . "}";
                }
                $jscats[] = "'" . $jspcat . "': {" . (implode(", ", $jssubcats)) . "}";
            }
            $jsfeeds[] = "'" . $jsfeed . "': {" . (implode(", ", $jscats)) . "}";
        }
        $js = "feedlist = {" . implode(", ", $jsfeeds) . "};";
        $js .= "
            function ChangeFeedCat(feeder, obj, obj2, cat, sub_cat)
            {
                obj.length = 1;
                if (obj2)
                {
                    obj2.length = 1;
                }
                if (feeder != undefined)
                {
                    if (feedlist[feeder] != undefined)
                    {
                        val = feedlist[feeder];

                        if (cat != undefined)
                        {
                            if (feedlist[feeder][cat] != undefined)
                            {
                                val = feedlist[feeder][cat];

                                if (sub_cat != undefined)
                                {
                                    if (feedlist[feeder][cat][sub_cat] != undefined)
                                    {
                                        val = feedlist[feeder][cat][sub_cat];
                                    }
                                    else
                                    {
                                        return false;
                                    }
                                }
                            }
                            else
                            {
                                return false;
                            }
                        }
                    }
                    else
                    {
                        return false;
                    }
                }
                for (var i in val)
                {
                    obj.options[obj.options.length]=new Option(i, i);
                }
            }";
        echo $js;
    }
}


