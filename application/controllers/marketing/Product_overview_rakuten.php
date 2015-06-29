<?php
DEFINE("PLATFORM_TYPE", "RAKUTEN");

class Product_overview_rakuten extends MY_Controller
{

    private $app_id = "MKT0080";
    private $lang_id = "en";

    //must set to public for view
    public $overview_path;
    public $default_platform_id;

    public function __construct()
    {
        parent::__construct();
        $this->overview_path = 'marketing/product_overview_'.strtolower(PLATFORM_TYPE);
        $this->load->model($this->overview_path.'_model', 'product_overview_model');
        $this->tool_path = 'marketing/pricing_tool_'.strtolower(PLATFORM_TYPE);
        $this->load->model($this->tool_path.'_model', 'pricing_tool_model');
        $this->load->model('marketing/product_model');
        $this->load->helper(array('url', 'notice', 'object', 'operator'));
        $this->load->library('service/pagination_service');
        $this->load->library('service/context_config_service');
        $this->load->library('service/display_qty_service');
        $this->load->library('service/wms_warehouse_service');
        $this->load->library('service/rakuten_service');
        $this->load->library('service/sku_mapping_service');
        $this->load->library('service/data_exchange_service');
        $this->load->library('service/product_service');
        $this->load->library('service/price_margin_service');
        $this->default_platform_id = $this->context_config_service->value_of("default_platform_id");
    }

    public function upload_sku_info()
    {
        $message = $this->process_sku_info_file($_FILES["datafile"]["tmp_name"]);
        $receipient = "bd@eservicesgroup.com";
        // $receipient = "tslau@eservicesgroup.com";
        mail ($receipient, "[VB] Rakuten Price update report from uploaded file", $message); # SPAM!!!!
    }

    public function process_sku_info_ftp($filename)
    {
        // this will pick up 1 specific file

        $filename = "/var/data/valuebasket.com/spider_upload/sku_price_update/$filename.csv";

        // this code will pick up ALL files

        // $filelist = array();
        // $files = glob("/var/data/valuebasket.com/spider_upload/sku_price_update/*.csv");
        // foreach ($files as $file)
        //  $filelist[filemtime($file)] = $file;

        // ksort($filelist);

        // foreach ($filelist as $k=>$filename)
        {
            echo "Processing $filename\r\n";
            $message = $this->process_sku_info_file($filename);
            $receipient = "bd@eservicesgroup.com";
            $receipient = "tslau@eservicesgroup.com";
            $receipient = "rod@eservicesgroup.com, edward@eservicesgroup.com, ming@eservicesgroup.com";
            mail ($receipient, "[VB] Price update report for FTP file $filename", $message); # SPAM!!!!

            // move the file into the done folder
            $path_parts = pathinfo($filename);
            $newfilename = $path_parts['dirname'] . "/done/done-" . date(DATE_ATOM) . "-" . $path_parts['basename'];

            echo "Renaming $filename to $newfilename\r\n";
            rename($filename, $newfilename);
        }
    }

    private function process_sku_info_file($filename)
    {
        // var_dump($_FILES);
        // array(1)
        // {
        // ["datafile"]=>
        //  array(5)
        //  {
        //      ["name"]=>
        //      string(15) "application.ini"
        //      ["type"]=>
        //      string(24) "application/octet-stream"
        //      ["tmp_name"]=>
        //      string(14) "/tmp/phpVdXdmO"
        //      ["error"]=>
        //      int(0)
        //      ["size"]=>
        //      int(2129)
        //  }
        // }
        // echo file_get_contents($_FILES["datafile"]["tmp_name"]);
        require_once(BASEPATH.'plugins/csv_parser_pi.php');
        $csvfile = new CSVFileLineIterator($filename);

        $arr = csv_parse($csvfile);

        unset($arr[0]); # remove the header

        // platform_id   sku            master_sku      prod_name                                           price
        // WEBAU        10111-AA-BK     20309-AA-NA     Canon PowerShot G1X Digital Camera (Black)          566.12

        $filename = "price_rakuten_service";
        $classname = ucfirst($filename);
        include_once APPPATH."libraries/service/{$filename}.php";
        $this->price_service = new $classname();

        echo "<a href='/'>Go back to main menu</a><hr>";
        $message = "";

        $c = count($arr);

        // $output = "<table width='800px'; cell_spacing='0'; cell_padding='0' style='font-family: Arial; font-size: 13px;'>
        //          <tr style='font-weight:bold'>
        //          <td>Status</td>
        //          <td>Item</td>
        //          <td>Reasons</td>
        //          <td style='text-align: center;'>Rows affected</td>
        //          <td>Action</td>
        //          </tr>";

        $skulist = '';
        foreach ($arr as $line)
        {
            $c--;
            set_time_limit (600);

            $platform_id            = $line[0];
            $sku                    = $line[1];
            $master_sku             = $line[2];
            $required_selling_price = $line[4];

            $mapped = null;
            $fail_reason = "";
            if ($line[0] == "" || $line[0] == null) $fail_reason .= "No platform id provided, ";

            if ($master_sku != "")
            {
                if ($sku == "") $sku = $this->sku_mapping_service->get_local_sku($master_sku);

                $jj = $this->price_service->get_profit_margin_json($platform_id, $sku, 0, -1,false);
                if ($jj === false)
                {
                    $fail_reason .= "Unable to get profit margin. Is SKU mapped?";
                }
                else
                {
                    $json = json_decode($jj, true);
                    $auto_price = $json["get_price"];

                    $json = json_decode($this->price_service->get_profit_margin_json($platform_id, $sku, $required_selling_price, -1, false), true);
                }
            }

            $margin = $json["get_margin"];
            if ($margin <= 5)                       $fail_reason .= "Margin lower than 5%, ";

            // $affected = $this->product_model->product_service->get_dao()->map_sku($line[0], $line[1]);
            if ($platform_id == "" || $platform_id == null) $fail_reason .= "No platform specified, ";
            if ($master_sku == "" || $master_sku == null) $fail_reason .= "No master SKU mapped, ";
            if ($sku == "" || $sku == null) $fail_reason .= "SKU not specified, ";
            if ($required_selling_price == "" || $required_selling_price == null || $required_selling_price < 0) $fail_reason .= "Your required selling price $required_selling_price is not acceptable, ";

            $commit = false;
            // we only commit at the last update
            if ($c <= 0) $commit = true;

            switch ($fail_reason)
            {
                case "":
                    $output = "<br>SUCCESS: {$line[0]}'s {$line[3]} {$line[1]} ({$line[2]}) to be priced at {$line[4]}, margin is {$json["get_margin"]}, recommend to sell at $auto_price<br>\r\n";
                    $affected = $this->price_service->update_sku_price($platform_id, $sku, $required_selling_price, $commit);

                    if ($affected < 1)//{
                        $output = "FAIL ($platform_id's $sku): Nothing updated. Either SKU is not listed or price was unchanged (Rows affected: $affected)<br>\r\n";

                    if($affected)
                    $skulist .= $sku.'%09%0D%0A';
                        // $output .= "<tr>
                        //  <td>FAIL</td>
                        //  <td>($platform_id's $sku)</td>
                        //  <td>Nothing updated. Either SKU is not listed or price was unchanged</td>
                        //  <td style='text-align: center;'>$affected</td>
                        //  <td></td>
                        // </tr>";
                    // }
                    break;

                default:
                    $output = "FAIL ($platform_id's $sku): $fail_reason<br>\r\n";

                    // $output .= "<tr>
                    //      <td>FAIL</td>
                    //      <td>($platform_id's $sku)</td>
                    //      <td>$fail_reason</td>
                    //      <td style='text-align: center;'>$affected</td>
                    //      <td></td>
                    //  </tr>";
                    break;
            }



            if ($commit)
            {
                $output .= "Committed to database<br>\r\n";
                $this->price_service->commit();
            }

            echo $output;
            $message = $output;
            // die();
        }

        $url = "http://admincentre.valuebasket.com/marketing/product_overview_rakuten?platform_id=$platform_id&cat_id=&brand_id=&sub_cat_id=&supplier_id=&pfid2=$platform_id&mskulist=&skulist=$skulist&filter=2&sku=&prod_name=&clearance=&listing_status=&inventory=&ext_qty=&website_status=&sourcing_status=&purchaser_updated_date=&price=&profit=&margin=&sort=&order=&search=1";
        echo '<a href="'.$url.'"><button>Actions</button></a>';

        return $message;
    }


    public function index($platform_id = "")
    {
        $sub_app_id = $this->_get_app_id()."00";
        $_SESSION["LISTPAGE"] = base_url().$this->overview_path."/?".$_SERVER['QUERY_STRING'];

        ini_set("memory_limit", "256M");
        if ($this->input->post("posted") && $_POST["check"])
        {
            $rsresult = "";
            $shownotice = 0;

            //r_dump($_POST["check"]); die();
            foreach ($_POST["check"] as $rssku)
            {
                $success = 0;
                list($platform,$sku) = explode("||",$rssku);
                $country_id = substr($platform, -2);
                $profit = $_POST["hidden_profit"][$platform][$sku];
                $margin = $_POST["hidden_margin"][$platform][$sku];
                $price = $_POST["price"][$platform][$sku]["price"];

                if (($price_obj = $this->product_overview_model->get_price(array("sku"=>$sku, "platform_id"=>$platform)))!==FALSE)
                {
                    if (empty($price_obj))
                    {
                        $price_obj = $this->product_overview_model->get_price();
                        set_value($price_obj, $_POST["price"][$platform][$rssku]);
                        $price_obj->set_sku($sku);
                        $price_obj->set_platform_id($platform);
                        //$price_obj->set_listing_status('L');
                        $price_obj->set_status(1);
                        $price_obj->set_allow_express('N');
                        $price_obj->set_is_advertised('N');
                        $price_obj->set_max_order_qty(100);
                        $price_obj->set_auto_price('N');
                        if ($this->product_overview_model->add_price($price_obj))
                        {
                            $success = 1;

                            // update price_margin tb for all platforms
                            $this->price_margin_service->insert_or_update_margin($sku, $platform, $price, $profit, $margin);
                        }
                    }
                    else
                    {
                        set_value($price_obj, $_POST["price"][$platform][$sku]);
                        if ($this->product_overview_model->update_price($price_obj))
                        {
                            $success = 1;

                            // update price_margin tb for all platforms
                            $this->price_margin_service->insert_or_update_margin($sku, $platform, $price, $profit, $margin);
                        }
                    }
                }

                if($price_ext_obj = $this->pricing_tool_model->price_service->get_price_ext_dao()->get(array("platform_id"=>$platform, "sku"=>$sku)))
                {
                    $price_ext_obj_need_update = false;


                    if($_POST["action"][$platform][$sku])
                    {
                        $price_ext_obj_need_update = TRUE;
                    }

                    if($price_ext_obj->get_ext_qty() != $_POST["product"][$platform][$sku]['ext_qty'])
                    {
                        if($_POST["product"][$platform][$sku]['ext_qty'] == "")
                        {
                            $prod_obj = $this->product_model->get('product', array('sku'=>$sku));

                            if($prod_obj->get_website_quantity() != "")
                                $_POST["product"][$platform][$sku]['ext_qty'] = $prod_obj->get_website_quantity();
                            else
                                $_POST["product"][$platform][$sku]['ext_qty'] = -1;
                        }

                        $price_ext_obj->set_ext_qty($_POST["product"][$platform][$sku]['ext_qty']);
                        $price_ext_obj_need_update = TRUE;
                    }

                    if($price_ext_obj->get_handling_time() != $_POST["handling_time"][$platform][$sku])
                    {
                        $price_ext_obj->set_handling_time($_POST["handling_time"][$platform][$sku]);
                        $price_ext_obj_need_update = TRUE;
                    }

                    if($_POST["action"][$platform][$sku] == "R")    #R: Re-additem
                    {
                        $price_ext_obj->set_action("P");
                        $price_ext_obj->set_remark(NULL);
                        $price_ext_obj->set_ext_item_id(NULL);
                        $price_ext_obj->set_ext_status(NULL);

                    }
                    elseif($_POST["action"][$platform][$sku] == "E")    #E: end item listing
                    {
                        $price_ext_obj->set_action("E");
                        $price_ext_obj->set_remark($_POST["reason"][$platform][$sku]);
                    }

                    if($price_ext_obj_need_update)
                    {
                        if($this->pricing_tool_model->price_service->get_price_ext_dao()->update($price_ext_obj)===false)
                        {
                            $success = 0;
                        }
                        else
                        {
                            if ($_POST["action"][$platform][$sku] == "E")
                            {
                                $res = $this->rakuten_service->end_item($country_id, $sku);
                                if($res["response"])
                                {
                                    // update price and price extend if sucessfully ended ebay listing
                                    $price_obj->set_listing_status("N");
                                    if($this->pricing_tool_model->update($price_obj) === FALSE)
                                    {
                                        $success = 0;
                                        $_SESSION["NOTICE"] = __LINE__." ".$this->db->_error_message();
                                    }

                                    $price_ext_obj->set_action("E");
                                    $price_ext_obj->set_remark(null);
                                    $price_ext_obj->set_ext_item_id(NULL);
                                    $price_ext_obj->set_ext_status("E");
                                    if($this->pricing_tool_model->price_service->get_price_ext_dao()->update($price_ext_obj) === FALSE)
                                    {
                                        $success = 0;
                                        $_SESSION["NOTICE"] = __LINE__." ".$this->db->_error_message();
                                    }
                                }
                                $_SESSION["NOTICE"] .= $res["message"];
                            }
                            elseif ($_POST["action"][$platform][$sku] == "RE")
                            {
                                $res = $this->rakuten_service->update_item($country_id, $sku, "update_prodoverview");
                                $_SESSION["NOTICE"] .= $res["message"] . "\n";
                            }
                        }
                    }
                }
                else
                {
                    # not listed. we insert price_extend with ext_qty for add_items cron job to pick up
                    $price_ext_dao = $this->pricing_tool_model->price_service->get_price_ext_dao();
                    $price_ext_obj = $price_ext_dao->get();
                    $price_ext_obj->set_sku($sku);
                    $price_ext_obj->set_action("P");
                    $price_ext_obj->set_ext_status(NULL);
                    $price_ext_obj->set_ext_item_id(NULL);
                    if($_POST["product"][$platform][$sku]['ext_qty'])
                        $price_ext_obj->set_ext_qty($_POST["product"][$platform][$sku]['ext_qty']);

                    $price_ext_obj->set_platform_id($platform);

                    if($price_ext_dao->insert($price_ext_obj) === FALSE)
                    {
                        $success = 0;
                        $_SESSION["NOTICE"] = __LINE__." ".$this->db->_error_message();
                    }
                }

                if ($success)
                {
                    if ($product_obj = $this->product_overview_model->get("product", array("sku"=>$sku)))
                    {
                        if ($this->product_overview_model->update("product", $product_obj))
                        {
                            $success = 1;
                        }
                        else
                        {
                            $success = 0;
                        }
                    }
                    else
                    {
                        $success = 0;
                    }
                }
                if (!$success)
                {
                    $shownotice = 1;
                }
                $rsresult .= "{$rssku} -> {$success}\\n";
            }
            if ($shownotice)
            {
                $_SESSION["NOTICE"] = $rsresult;
            }
            redirect(current_url()."?".$_SERVER['QUERY_STRING']);
        }

        $where = array();
        $option = array();

        $submit_search = 0;

        $option["inventory"] = 1;

        if($this->input->get("filter") != "")
            $data["filter"] = $this->input->get("filter");

        if($this->input->get("filter") == 1)
        {

            if ($this->input->get("sku") != "")
            {
                $where["p.sku LIKE "] = "%".$this->input->get("sku")."%";
                $submit_search = 1;
            }

            if ($this->input->get("cat_id") != "")
            {
                $where["p.cat_id"] = $this->input->get("cat_id");
            }

            if ($this->input->get("sub_cat_id") != "")
            {
                $where["p.sub_cat_id"] = $this->input->get("sub_cat_id");
            }

            if ($this->input->get("brand_id") != "")
            {
                $where["p.brand_id"] = $this->input->get("brand_id");
            }

            if ($this->input->get("supplier_id") != "")
            {
                $where["sp.supplier_id"] = $this->input->get("supplier_id");
            }

            if ($this->input->get("surplusqty") != "")
            {
                switch($this->input->get("surplusqty_prefix"))
                {
                    case 1:
                        $where["surplus_quantity is not null and surplus_quantity > 0 and surplus_quantity <= {$this->input->get("surplusqty")}"] = null;
                        break;
                    case 2:
                        $where["surplus_quantity <= {$this->input->get("surplusqty")}"] = null;
                        break;
                    case 3:
                        $where["surplus_quantity >= {$this->input->get("surplusqty")}"] = null;
                        break;
                }
            }

        }
        elseif($this->input->get("filter") == 2)
        {

            $ext_sku = array_map('trim', preg_split('/\r\n|\r|\n/', $this->input->get('mskulist'), -1, PREG_SPLIT_NO_EMPTY));
            $prod_sku = array_map('trim', preg_split('/\r\n|\r|\n/', $this->input->get('skulist'), -1, PREG_SPLIT_NO_EMPTY));

            //var_dump($prod_sku); die();

            if(is_array($ext_sku) && count($ext_sku) > 0)
            {
                $list = "('" . implode("','", $ext_sku) . "')";
                $where["map.ext_sku IN $list"] = null;
            }
            elseif(is_array($prod_sku) && count($prod_sku) > 0)
            {
                $list = "('" . implode("','", $prod_sku) . "')";
                $where["p.sku IN $list"] = null;
            }
            else
            {
                // redirect(current_url());
            }

            // // reset any filters passed previously from multi filter search
            // $_SESSION["LISTPAGE"] = base_url().$this->overview_path_v2."/?";
            $option["limit"] = -1;
        }


        if ($this->input->get("prod_name") != "")
        {
            $where["p.name LIKE "] = "%".$this->input->get("prod_name")."%";
            $submit_search = 1;
        }

        if($this->input->get("platform_id") != "")
        {
            $where["pbv.selling_platform_id"] = $this->input->get("platform_id");
            $submit_search = 1;
        }

        if ($this->input->get("clearance") != "")
        {
            $where["p.clearance"] = $this->input->get("clearance");
            $submit_search = 1;
        }

        if ($this->input->get("listing_status") != "")
        {
            if($this->input->get("listing_status") == "N")
            {
                $where["(pr.listing_status = 'N' or pr.listing_status is null)"] = null;
            }
            else
            {
                $where["pr.listing_status"] = $this->input->get("listing_status");
            }
            $submit_search = 1;
        }

        if ($this->input->get("inventory") != "")
        {
            fetch_operator($where, "inventory", $this->input->get("inventory"));
            $submit_search = 1;
        }

        // if ($this->input->get("website_quantity") != "")
        // {
        //  fetch_operator($where, "p.website_quantity", $this->input->get("website_quantity"));
        //  $submit_search = 1;
        // }

        if ($this->input->get("ext_qty") != "")
        {
            fetch_operator($where, "prx.ext_qty", $this->input->get("ext_qty"));
            $submit_search = 1;
        }

        if ($this->input->get("website_status") != "")
        {
            $where["p.website_status"] = $this->input->get("website_status");
            $submit_search = 1;
        }

        if ($this->input->get("sourcing_status") != "")
        {
            $where["p.sourcing_status"] = $this->input->get("sourcing_status");
            $submit_search = 1;
        }

        if ($this->input->get("purchaser_updated_date") != "")
        {
            fetch_operator($where, "sp.modify_on", $this->input->get("purchaser_updated_date"));
            $submit_search = 1;
        }

        if ($this->input->get("profit") != "")
        {
            fetch_operator($where, "pm.profit", $this->input->get("profit"));
            $option["refresh_margin"] = 1;
            $submit_search = 1;
        }

        if ($this->input->get("margin") != "")
        {
            fetch_operator($where, "pm.margin", $this->input->get("margin"));
            $option["refresh_margin"] = 1;
            $submit_search = 1;
        }

        $option["master_sku"] = 1;

        if ($this->input->get("price") != "")
        {
            fetch_operator($where, "pr.price", $this->input->get("price"));
            $submit_search = 1;
        }



        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        if ($this->input->get("search"))
        {
            if ($this->input->get("csv"))
            {
                // if (isset($where["pr.listing_status"]))
                // {
                //  $where["pr.listing_status"] = $where["pr.listing_status"];
                //  unset($where["pr.listing_status"]);
                // }
                // if (isset($where["pr.auto_price"]))
                // {
                //  $where["auto_price"] = $where["pr.auto_price"];
                //  unset($where["pr.auto_price"]);
                // }
                $list = $this->product_overview_model->get_product_overview_v2($where, array_merge($option));
                $this->generate_csv($list); die();
            }
        }

        $limit = '20';

        $pconfig['base_url'] = $_SESSION["LISTPAGE"];
        $option["limit"] = $pconfig['per_page'] = $limit;
        if ($option["limit"])
        {
            $option["offset"] = $this->input->get("per_page");
        }

        if (empty($sort))
        {
            $sort = "p.name";
        }
        else
        {
            if(strpos($sort, "prod_name") !== FALSE)
                $sort = "p.name";
            elseif(strpos($sort, "listing_status") !== FALSE)
                $sort = "pr.listing_status";
        }

        if (empty($order))
            $order = "asc";

        if($sort == "margin" || $sort == "profit")
        {
            $option["refresh_margin"] = 1;
        }


        $option["orderby"] = $sort." ".$order;

        include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
        $data["lang"] = $lang;

        if ($this->input->get("search"))
        {
            $data["objlist"] = $this->product_overview_model->get_product_list_v2($where, $option, $lang);
            $data["total"] = $this->product_overview_model->get_product_list_total_v2($where, $option);
        }

        $pconfig['total_rows'] = $data['total'];
        $this->pagination_service->set_show_count_tag(TRUE);
        $this->pagination_service->initialize($pconfig);

        $wms_warehouse_where["status"] = 1;
        $wms_warehouse_where["type != 'W'"] = null;
        $data["wms_wh"] = $this->wms_warehouse_service->get_list($wms_warehouse_where, array('limit'=>-1, 'orderby'=>'warehouse_id'));

        $data["notice"] = notice($lang);
        $data["clist"] = $this->product_overview_model->price_service->get_platform_biz_var_service()->selling_platform_dao->get_list(array("type"=>PLATFORM_TYPE, "status"=>1));
        $data["sortimg"][$sort] = "<img src='".base_url()."images/".$order.".gif'>";
        $data["xsort"][$sort] = $order=="asc"?"desc":"asc";
//      $data["searchdisplay"] = ($submit_search)?"":'style="display:none"';
        $data["searchdisplay"] = "";
        $data["query_string"] = $_SERVER["QUERY_STRING"];
        $this->load->view($this->overview_path.'/product_overview_v', $data);
    }

    function generate_csv($list)
    {
        header("Content-type: text/csv");
        header("Cache-Control: no-store, no-cache");
        header("Content-Disposition: attachment; filename=\"export_rakuten_sku.csv\"");

        echo "platform_id, sku, master_sku, prod_name, price\r\n";

        if($list)
        {
            foreach ($list as $line)
            {
                // var_dump($line); die();
                echo "{$line->get_platform_id()},{$line->get_sku()},{$line->get_master_sku()},{$line->get_prod_name()},{$line->get_price()}\r\n";

            }
        }
        return ;

        echo "sku, ext_sku\r\n";
        foreach ($list as $item)
        {
            echo "{$item['sku']},{$item['ext_sku']}\r\n";
        }
    }

    public function js_overview()
    {
        $this->product_overview_model->print_overview_js();
    }

    public function _get_app_id(){
        return $this->app_id;
    }

    public function _get_lang_id(){
        return $this->lang_id;
    }
}

/* End of file product_overview_rakuten.php */
/* Location: ./system/application/controllers/product_overview_rakuten.php */