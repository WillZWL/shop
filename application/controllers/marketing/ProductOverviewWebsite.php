<?php

DEFINE('PLATFORM_TYPE', 'WEBSITE');

class ProductOverviewWebsite extends MY_Controller
{
    private $appId = 'MKT0045';

    public function getAppId()
    {
        return $this->appId;
    }

    public function index($platform_id = '')
    {
        $sub_app_id = $this->getAppId().'00';
        include_once APPPATH.'language/'.$sub_app_id.'_'.$this->getLangId().'.php';
        $data['lang'] = $lang;

        if ($this->input->get('search')) {
            $where = [];
            $option = [];

            ($this->input->get('platform_id') != '') ? $where['pr.platform_id'] = $this->input->get('platform_id') : '';
            ($this->input->get('catid') != '') ? $where['p.cat_id'] = $this->input->get('catid') : '';
            ($this->input->get('scatid') != '') ? $where['p.sub_cat_id'] = $this->input->get('scatid') : '';
            ($this->input->get('brand') != '') ? $where['p.brand_id'] = $this->input->get('brand') : '';
            ($this->input->get('pla') != '') ? $where['pr.is_advertised'] = $this->input->get('pla') : '';
            ($this->input->get('msku') != '') ? $where['sm.ext_sku'] = $this->input->get('msku') : '';
            ($this->input->get('liststatus') != '') ? $where['pr.listing_status'] = $this->input->get('liststatus') : '';
            ($this->input->get('clear') != '') ? $where['p.clearance'] = $this->input->get('clear') : '';
            ($this->input->get('wsqty') != '') ? $where['p.website_quantity'] = $this->input->get('wsqty') : '';
            ($this->input->get('wsstatus') != '') ? $where['p.website_status'] = $this->input->get('wsstatus') : '';
            ($this->input->get('suppstatus') != '') ? $where['supplier_status'] = $this->input->get('suppstatus') : '';
            ($this->input->get('purcupdate') != '') ? $where['sp.modify_on >= '] = $this->input->get('purcupdate') : '';
            ($this->input->get('profit') != '') ? $where['pm.profit'] = $this->input->get('profit') : '';
            ($this->input->get('margin') != '') ? $where['pm.margin'] = $this->input->get('margin') : '';
            ($this->input->get('price') != '') ? $where['pr.price'] = $this->input->get('price') : '';
            ($this->input->get('limit') != '') ? $option['limit'] = $this->input->get('limit') : '';
            ($this->input->get('per_page') != '') ? $option['offset'] = $this->input->get('per_page') : '';

            $data['product_list'] = $this->sc['Product']->getProductOverview($where, $option);

            $config['base_url'] = base_url('marketing/ProductOverviewWebsite');
            $config['total_rows'] = 1000;
            $config['page_query_string'] = true;
            $config['reuse_query_string'] = true;
            $config['per_page'] = $option['limit'];
            $this->pagination->initialize($config);
            $data['links'] = $this->pagination->create_links();
        }

        if ($this->input->post('posted') && $_POST['check']) {
            $rsresult = '';
            $shownotice = 0;
            $c = 0;
            foreach ($_POST['check'] as $rssku) {
                $success = 0;
                ++$c;
                list($platform, $sku) = explode('||', $rssku);
                $total_update = count($_POST['check']);

                if (($price_obj = $this->product_overview_model->get_price(array('sku' => $sku, 'platform_id' => $platform))) !== false) {
                    if (empty($price_obj)) {
                        $price_obj = $this->product_overview_model->get_price();
                        set_value($price_obj, $_POST['price'][$platform][$sku]);
                        $price_obj->set_sku($sku);
                        $price_obj->set_platform_id($platform);
                        //$price_obj->set_listing_status('L');
                        $price_obj->set_status(1);
                        $price_obj->set_allow_express('N');
                        $price_obj->set_is_advertised('N');
                        $price_obj->set_max_order_qty(100);
                        $price_obj->set_auto_price('N');
                        if ($this->product_overview_model->add_price($price_obj)) {
                            $success = 1;
                        }
                    } else {
                        set_value($price_obj, $_POST['price'][$platform][$sku]);

                        $price_obj->set_is_advertised('N');
                        if (is_array($_POST['is_advertised'][$platform])) {
                            if (in_array($sku, $_POST['is_advertised'][$platform])) {
                                $price_obj->set_is_advertised('Y');
                            }
                        }

                        if ($this->product_overview_model->update_price($price_obj)) {
                            $success = 1;
                        }
                    }
                }

                if ($success) {
                    if ($product_obj = $this->product_overview_model->get('product', array('sku' => $sku))) {
                        $prev_webqty = $product_obj->get_website_quantity();
                        set_value($product_obj, $_POST['product'][$platform][$sku]);
                        if ($_POST['product'][$platform][$sku]['website_quantity'] != $prev_webqty) {
                            include_once APPPATH.'libraries/dao/product_dao.php';
                            $prod_dao = new Product_dao();
                            $vpo_where = array('vpo.sku' => $product_obj->get_sku());
                            $vpo_option = array('to_currency_id' => 'GBP', 'orderby' => "vpo.price > 0 DESC, vpo.platform_currency_id = 'GBP' DESC, vpo.price *  er.rate DESC", 'limit' => 1);
                            $vpo_obj = $prod_dao->get_prod_overview_wo_cost_w_rate($vpo_where, $vpo_option);
                            if ($vpo_obj = $prod_dao->get_prod_overview_wo_cost_w_rate($vpo_where, $vpo_option)) {
                                $display_qty = $this->display_qty_service->calc_display_qty($vpo_obj->get_cat_id(), $_POST['product'][$platform][$sku]['website_quantity'], $vpo_obj->get_price());
                                $product_obj->set_display_quantity($display_qty);
                            }
                        }

                        $profit = $_POST['hidden_profit'][$platform][$sku];
                        $margin = $_POST['hidden_margin'][$platform][$sku];
                        $price = $_POST['price'][$platform][$sku]['price'];

                        if ($this->product_overview_model->update('product', $product_obj)) {
                            // update price_margin tb for all platforms
                            $this->price_margin_service->insert_or_update_margin($sku, $platform, $price, $profit, $margin);

                            // Google - check if this is the first time that want to create the ad.
                            if (is_array($_POST['google_adwords'][$platform])) {
                                if (in_array($sku, $_POST['google_adwords'][$platform])) {
                                    $google_adwords_target_platform_list = array($platform => 'on');
                                    if ($google_adwords_target_platform_list) {
                                        $this->product_update_followup_service->adwords_update($sku, $google_adwords_target_platform_list, array(), false);
                                    }
                                }
                            }

                            if ($total_update == $c) {
                                $this->product_update_followup_service->google_shopping_update($sku);
                                $this->product_update_followup_service->adwords_update($sku);
                            } else {
                                // do not schedule updates here
                                $this->product_update_followup_service->google_shopping_update($sku, false);
                                $this->product_update_followup_service->adwords_update($sku, array(), array(), false);
                            }

                            $success = 1;
                        } else {
                            $success = 0;
                        }
                    } else {
                        $success = 0;
                    }
                }
                if (!$success) {
                    $shownotice = 1;
                }
                $rsresult .= "{$rssku} -> {$success}\\n";
            }

            if ($shownotice) {
                $_SESSION['NOTICE'] = $rsresult;
            }
            redirect(current_url().'?'.$_SERVER['QUERY_STRING']);
        }

        $where = [];
        $option = [];

        $submit_search = 0;

        $option['supplier_prod'] = 1;
        $option['master_sku'] = 1;
        $option['google_shopping'] = 1;

        if ($this->input->get('fil') != '') {
            $data['filter'] = $this->input->get('fil');
        }

        $sort = $this->input->get('sort');
        $order = $this->input->get('order');

        // if ($this->input->get('search')) {
        //     if ($this->input->get('csv')) {
        //         $list = $this->product_overview_model->get_product_overview_v2($where, array_merge($option));
        //         $this->generate_csv($list);
        //         die();
        //     }
        // }

        $pconfig['base_url'] = $_SESSION['LISTPAGE'];

        if (empty($sort)) {
            $sort = 'p.name';
        } else {
            if (strpos($sort, 'prod_name') !== false) {
                $sort = 'p.name';
            } elseif (strpos($sort, 'listing_status') !== false) {
                $sort = 'pr.listing_status';
            }
        }

        if (empty($order)) {
            $order = 'asc';
        }

        if ($sort == 'margin' || $sort == 'profit') {
            $option['refresh_margin'] = 1;
        }

        $option['orderby'] = $sort.' '.$order;

        // $affiliate_feed_list = null;
        // $list = $this->affiliate_sku_platform_service->get_feed_list($platform_id);
        // if ($list) {
        //     foreach ($list as $item) {
        //         $affiliate_feed_list[$item] = $item;
        //     }
        // }

        // $data['affiliate_feed_list'] = $affiliate_feed_list;

        $feed_status_list = null;
        $feed_status_list[0] = 'All';
        $feed_status_list[1] = 'Always exclude';
        $feed_status_list[2] = 'Always include';
        $data['feed_status'] = $feed_status;
        $data['feed_status_list'] = $feed_status_list;

        if (empty($data['objlist']['subtractcount'])) {
            $data['objlist']['subtractcount'] = 0;
        }

        // we process for google-related info OUTSIDE of sql, so subtract filtered items in price_website_service
        if ($data['total'] > 0) {
            $final_count = $data['total'] - $data['objlist']['subtractcount'];
        } else {
            $final_count = $data['total'];
        }

        // $pconfig['total_rows'] = $final_count;
        // $this->pagination_service->set_show_count_tag(true);
        // $this->pagination_service->initialize($pconfig);

        // $data["wms_wh"] = $this->wms_warehouse_service->get_list(array('status'=>1), array('limit'=>-1, 'orderby'=>'warehouse_id'));
        // $data['notice'] = notice($lang);
        $data['clist'] = $this->sc['PlatformBizVar']->getDao('SellingPlatform')->getList(array('type' => PLATFORM_TYPE, 'status' => 1));
        // var_dump($data['clist']);die;
        // $data['sortimg'][$sort] = "<img src='".base_url().'images/'.$order.".gif'>";
        // $data['xsort'][$sort] = $order == 'asc' ? 'desc' : 'asc';
        // $data["searchdisplay"] = ($submit_search)?"":'style="display:none"';
        $data['searchdisplay'] = '';
        $data['query_string'] = $_SERVER['QUERY_STRING'];
        $this->load->view('marketing/product_overview/product_overview_v', $data);
    }

    public function query()
    {
        $sub_app_id = $this->getAppId().'00';
        include_once APPPATH.'language/'.$sub_app_id.'_'.$this->getLangId().'.php';
        $data['lang'] = $lang;

        // var_dump($data['product_list']);die;

        // $data['total'] = $this->product_overview_model->get_product_list_total_v2($where, $option);
    }

    public function exportAffiliateFeed()
    {
        if ($_POST) {
            if ($_POST['af_skulist']) {
                $prod_sku = array_map('trim', preg_split('/\r\n|\r|\n/', $_POST['af_skulist'], -1, PREG_SPLIT_NO_EMPTY));

                if (is_array($prod_sku) && count($prod_sku) > 0) {
                    $list = "('".implode("','", $prod_sku)."')";
                    $where["asp.sku IN $list"] = null;
                }
            }

            if ($_POST['platform_id']) {
                $where['asp.platform_id'] = $_POST['platform_id'];
            }
            if ($_POST['afsku_status'] && $_POST['afsku_status'] != 'NA') {
                $where['asp.status'] = $_POST['afsku_status'];
            }

            $feed_list = $this->sc['AffiliateSkuPlatform']->getAffiliateFeedListWithInfo($where, $option);

            if ($feed_list) {
                ob_end_clean();
                ob_start();
                $csv_string = '';
                $header_row[] = 'name';
                $header_row[] = 'price';
                $header_row[] = 'listing_status';
                $header_row[] = 'master_sku';
                $header_row[] = 'sku';
                $header_row[] = 'affiliate_id';
                $header_row[] = 'platform_id';
                $header_row[] = 'affiliate_sku_status';
                $header_row[] = 'new_affiliate_sku_status';

                $fp = fopen('php://output', 'w');
                fputcsv($fp, $header_row);
                foreach ($feed_list as $key => $value) {
                    fputcsv($fp, $value);
                }
                $csv_string = ob_get_clean();
                fclose($fp);

                $output_filename = date('Ymd_His').'_affiliate_sku_export.csv';
                header('Content-Type: text/csv');
                header('Content-Disposition: attachment;filename='.$output_filename);
                echo $csv_string;
                die();
            }
        }
        $url = base_url().'marketing/product_overview_website_v2/';
        redirect($url);
    }

    /********** END OF NEW FUNCTION ********/

    public function upload_sku_info()
    {
        $message = $this->process_sku_info_file($_FILES['datafile']['tmp_name']);
        $receipient = 'bd@eservicesgroup.com';
        // $receipient = "tslau@eservicesgroup.com";
        mail($receipient, '[VB] Price update report from uploaded file', $message); # SPAM!!!!
    }

    private function process_sku_info_file($filename)
    {
        require_once BASEPATH.'plugins/csv_parser_pi.php';
        $csvfile = new CSVFileLineIterator($filename);

        $arr = csv_parse($csvfile);

        unset($arr[0]); # remove the header

        // platform_id   sku            master_sku      prod_name                                           price
        // WEBAU        10111-AA-BK     20309-AA-NA     Canon PowerShot G1X Digital Camera (Black)          566.12

        $filename = 'price_website_service';
        $classname = ucfirst($filename);
        include_once APPPATH."libraries/service/{$filename}.php";
        $this->price_service = new $classname();

        echo "<a href='/'>Go back to main menu</a><hr>";
        $message = '';

        $c = count($arr);
        foreach ($arr as $line) {
            --$c;
            set_time_limit(600);

            $platform_id = $line[0];
            $sku = $line[1];
            $master_sku = $line[2];
            $required_selling_price = $line[4];

            $mapped = null;
            $fail_reason = '';
            if ($line[0] == '' || $line[0] == null) {
                $fail_reason .= 'No platform id provided, ';
            }

            if ($master_sku != '') {
                if ($sku == '') {
                    $sku = $this->sku_mapping_service->get_local_sku($master_sku);
                }

                $jj = $this->price_service->get_profit_margin_json($platform_id, $sku, 0, -1, false);
                if ($jj === false) {
                    $fail_reason .= 'Unable to get profit margin. Is SKU mapped?';
                } else {
                    $json = json_decode($jj, true);
                    $auto_price = $json['get_price'];

                    $json = json_decode($this->price_service->get_profit_margin_json($platform_id, $sku, $required_selling_price, -1, false), true);
                }
            }

            $margin = $json['get_margin'];
            if ($margin <= 5) {
                $fail_reason .= 'Margin lower than 5%, ';
            }

            // $affected = $this->product_model->product_service->get_dao()->map_sku($line[0], $line[1]);
            if ($platform_id == '' || $platform_id == null) {
                $fail_reason .= 'No platform specified, ';
            }
            if ($master_sku == '' || $master_sku == null) {
                $fail_reason .= 'No master SKU mapped, ';
            }
            if ($sku == '' || $sku == null) {
                $fail_reason .= 'SKU not specified, ';
            }
            if ($required_selling_price == '' || $required_selling_price == null || $required_selling_price < 0) {
                $fail_reason .= "Your required selling price $required_selling_price is not acceptable, ";
            }

            $commit = false;
            // we only commit at the last update
            if ($c <= 0) {
                $commit = true;
            }

            switch ($fail_reason) {
                case '':
                    $output = "<br>SUCCESS: {$line[0]}'s {$line[3]} {$line[1]} ({$line[2]}) to be priced at {$line[4]}, margin is {$json['get_margin']}, recommend to sell at $auto_price<br>\r\n";
                    $affected = $this->price_service->update_sku_price($platform_id, $sku, $required_selling_price, $commit);

                    if ($affected < 1) {
                        $output = "FAIL ($platform_id's $sku): Nothing updated. Either SKU is not listed or price was unchanged (Rows affected: $affected)<br>\r\n";
                    }

                    break;

                default:
                    $output = "FAIL ($platform_id's $sku): $fail_reason<br>\r\n";
                    break;
            }

            if ($commit) {
                $output .= "Committed to database<br>\r\n";
                $this->price_service->commit();
            }

            echo $output;
            $message .= $output;
            // die();
        }

        return $message;
    }

    public function upload_clearance_sku_info()
    {
        $_SESSION['LISTPAGE'] = base_url().$this->overview_path_v2.'/?'.$_SERVER['QUERY_STRING'];
        $new_file = $this->upload_clearance_sku_info_file($_FILES['clearance_datafile']['tmp_name']);

        $fail_str = '';
        $success_str = '';

        if (file_exists($new_file)) {
            require_once BASEPATH.'plugins/csv_parser_pi.php';
            $csvfile = new CSVFileLineIterator($new_file);

            $arr = csv_parse($csvfile);
            if (is_array($arr)) {
                unset($arr[0]);
                $n = 0;
                foreach ($arr as $line) {
                    ++$n;
                    $master_sku = $line[0];
                    $website_quantity = $line[2];
                    $prod_obj_shell = $this->product_service->get_dao()->get();

                    if ($obj_list = $this->product_service->get_dao()->get_prod_from_master_sku($master_sku)) {
                        $prod_vo = clone $prod_obj_shell;
                        $multiple_mapping = '';
                        foreach ($obj_list as $o) {
                            $total_mapping_number = count($obj_list);

                            if ($total_mapping_number > 1) {
                                $multiple_mapping = "NOTICE: this sku have $total_mapping_number mapping";
                            }

                            set_value($prod_vo, $o);

                            if ($website_quantity > 20) {
                                $website_quantity = 20;
                            } elseif ($website_quantity <= 0) {
                                $fail_str .= 'FAIL: quantity invalid, At Line => '.$n.'<br />';
                                continue;
                            }

                            $prod_vo->set_website_quantity($website_quantity);
                            $prod_vo->set_clearance(1);
                            $prod_vo->set_website_status('I');

                            if ($this->product_service->get_dao()->update($prod_vo)) {
                                $success_str .= "SUCCESS: Master Sku=>$master_sku, Sku=>{$prod_vo->get_sku()},
                                                Website Quantity=>{$prod_vo->get_website_quantity()}, Clearance=>{$prod_vo->get_clearance()},
                                    At Line => ".$n."  $multiple_mapping <br />";
                            } else {
                                $fail_str .= 'FAIL: At Line => '.$n.'<br />';
                            }
                        }
                    } else {
                        $fail_str .= "FAIL: No Sku found through Master sku : {$master_sku} At Line => ".$n.'<br />';
                    }
                }
            } else {
                $fail_str = 'CSV file can not be parsed!';
            }
        } else {
            $fail_str = 'CSV file can not be uploaded!';
        }

        $receipient = 'nero@eservicesgroup.com, brave.liu@eservicesgroup.com, celine@eservicesgroup.com, bd_product_team@eservicesgroup.com, perry.leung@eservicesgroup.com';

        $message = $success_str.'<hr>'.$fail_str;
        mail($receipient, '[VB] Clearance Update in Bulk', $message);
        redirect($_SESSION['LISTPAGE']);
    }

    public function upload_clearance_sku_info_file($temp_name)
    {
        if ($_FILES['clearance_datafile']['error'] > 0) {
            return 'Error:<br>Return Code: '.$_FILES['clearance_datafile']['error'].'<br>';
        } else {
            $time_stamp = date('ymd_H_i_s');
            $new_filename = $time_stamp.'_'.$_FILES['clearance_datafile']['name'];

            $clearance_upload_path = "/var/data/valuebasket.com/clearance_upload/$new_filename.csv";

            if (move_uploaded_file($temp_name, $clearance_upload_path)) {
                return $clearance_upload_path;
            } else {
                return false;
            }
        }
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
            $receipient = 'bd@eservicesgroup.com';
            $receipient = 'tslau@eservicesgroup.com';
            $receipient = 'rod@eservicesgroup.com, edward@eservicesgroup.com, ming@eservicesgroup.com';
            mail($receipient, "[VB] Price update report for FTP file $filename", $message); # SPAM!!!!

            // move the file into the done folder
            $path_parts = pathinfo($filename);
            $newfilename = $path_parts['dirname'].'/done/done-'.date(DATE_ATOM).'-'.$path_parts['basename'];

            echo "Renaming $filename to $newfilename\r\n";
            rename($filename, $newfilename);
        }
    }

    public function generate_csv($list)
    {
        header('Content-type: text/csv');
        header('Cache-Control: no-store, no-cache');
        header('Content-Disposition: attachment; filename="export_sku.csv"');

        echo "platform_id, sku, master_sku, prod_name, price, margin\r\n";

        if ($list) {
            foreach ($list as $line) {
                // var_dump($line); die();
                echo "{$line->get_platform_id()},{$line->get_sku()},{$line->get_master_sku()},{$line->get_prod_name()},{$line->get_price()},{$line->get_margin()}\r\n";
            }
        }

        return;

        echo "sku, ext_sku\r\n";
        foreach ($list as $item) {
            echo "{$item['sku']},{$item['ext_sku']}\r\n";
        }
    }

    // public function js_overview()
    // {
    //     $this->product_overview_model->print_overview_js();
    // }
}
