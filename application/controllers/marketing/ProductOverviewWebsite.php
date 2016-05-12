<?php

DEFINE('PLATFORM_TYPE', 'WEBSITE');

class ProductOverviewWebsite extends MY_Controller
{
    private $appId = 'MKT0045';

    public function getAppId()
    {
        return $this->appId;
    }

    /**
     * query product overview
     *
     * @return array;
     */
    public function query()
    {
        $where = [];
        $option = [];

        ($this->input->get('platform_id') != '') ? $where['pr.platform_id'] = $this->input->get('platform_id') : '';
        ($this->input->get('prod_name') != '') ? $where["p.name LIKE "] = "%".$this->input->get("prod_name")."%" : '';
        ($this->input->get('catid') != '') ? $where['p.cat_id'] = $this->input->get('catid') : '';
        ($this->input->get('scatid') != '') ? $where['p.sub_cat_id'] = $this->input->get('scatid') : '';
        ($this->input->get('brand') != '') ? $where['p.brand_id'] = $this->input->get('brand') : '';
        ($this->input->get('pla') != '') ? $where['pr.is_advertised'] = $this->input->get('pla') : '';
        ($this->input->get('plaapi') != '') ? $where['pr.ext_status'] = $this->input->get('plaapi') : '';
        ($this->input->get('auto_price') != '') ? $where['pr.auto_price'] = $this->input->get('auto_price') : '';
        ($this->input->get('msku') != '') ? $where['sm.ext_sku'] = $this->input->get('msku') : '';
        ($this->input->get('liststatus') != '') ? $where['pr.listing_status'] = $this->input->get('liststatus') : '';
        ($this->input->get('clear') != '') ? $where['p.clearance'] = $this->input->get('clear') : '';
        ($this->input->get('wsqty') != '') ? $where['p.website_quantity'] = $this->input->get('wsqty') : '';
        ($this->input->get('wsstatus') != '') ? $where['p.website_status'] = $this->input->get('wsstatus') : '';
        ($this->input->get('suppstatus') != '') ? $where['supplier_status'] = $this->input->get('suppstatus') : '';
        ($this->input->get('purcupdate') != '') ? $where['sp.modify_on >= '] = $this->input->get('purcupdate') : '';
        ($this->input->get('profit') != '') ? $where['pm.profit'] = $this->input->get('profit') : '';
        // ($this->input->get('margin') != '') ? $where['pm.margin'] = $this->input->get('margin') : '';
        ($this->input->get('price') != '') ? $where['pr.price'] = $this->input->get('price') : '';
        ($this->input->get('limit') != '') ? $option['limit'] = $this->input->get('limit') : '';
        ($this->input->get('per_page') != '') ? $option['offset'] = $this->input->get('per_page') : '';
        ($this->input->get('auto_restock') != '') ? $where['p.auto_restock'] = $this->input->get('auto_restock') : '';

        if ($this->input->get('margin') != '') {
            switch($this->input->get("margin_prefix")) {
                case 1:
                    $where["pm.margin > 0 and pm.margin <= {$this->input->get("margin")}"] = null;
                    break;
                case 2:
                    $where["pm.margin <= {$this->input->get("margin")}"] = null;
                    break;
                case 3:
                    $where["pm.margin >= {$this->input->get("margin")}"] = null;
                    break;
            }
        }

        if ($this->input->get("surplusqty") != "") {
            switch($this->input->get("surplusqty_prefix")) {
                case 1:
                    $where["surplus_quantity > 0 and surplus_quantity <= {$this->input->get("surplusqty")}"] = null;
                    break;
                case 2:
                    $where["surplus_quantity <= {$this->input->get("surplusqty")}"] = null;
                    break;
                case 3:
                    $where["surplus_quantity >= {$this->input->get("surplusqty")}"] = null;
                    break;
            }
        }

        if ($this->input->get('filtertype') == 2) {
            $where = [];
            $option = [];
            $ext_sku = array_map('trim', preg_split('/\r\n|\r|\n/', $this->input->get('mskulist'), -1, PREG_SPLIT_NO_EMPTY));
            $prod_sku = array_map('trim', preg_split('/\r\n|\r|\n/', $this->input->get('skulist'), -1, PREG_SPLIT_NO_EMPTY));

            ($this->input->get('platform_id2') != '') ? $where['pr.platform_id'] = $this->input->get('platform_id2') : '';
            if (is_array($ext_sku) && count($ext_sku) > 0) {
                $list = "('" . implode("','", $ext_sku) . "')";
                $where["sm.ext_sku IN $list"] = null;
            } elseif (is_array($prod_sku) && count($prod_sku) > 0) {
                $list = "('" . implode("','", $prod_sku) . "')";
                $where["p.sku IN $list"] = null;
            }
        }

        if ($this->input->get('csv') == 1) {
            $export_option = $option;
            $export_option['limit'] = -1;
            unset($export_option['offset']);

            $this->exportSkuPrice($where, $export_option);
            die;
        }

        $data['product_list'] = $this->sc['Product']->getProductOverview($where, $option);
        $option['num_rows'] = 1;
        $total_rows = $this->sc['Product']->getProductOverview($where, $option);

        $data['filtertype'] = $this->input->get('filtertype');
        $config['base_url'] = base_url('marketing/ProductOverviewWebsite');
        $config['total_rows'] = $total_rows;
        $config['page_query_string'] = true;
        $config['reuse_query_string'] = true;
        $config['per_page'] = $option['limit'];
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();

        return $data;
    }

    private function updateProductOverview()
    {
        foreach ($_POST['check'] as $rssku) {
            list($sku, $platform_id) = explode('||', $rssku);

            $price_obj = $this->sc['Price']->getDao('Price')->get(['sku' => $sku, 'platform_id' => $platform_id]);
            if (!$price_obj) {
                continue;
            }

            $price = floatval($_POST['price'][$sku][$platform_id]['price']);
            $price_type = $_POST['price'][$sku][$platform_id]['auto_price'];

            // only price type is manual and price more than 0 can udpate price.
            if ($price > 0 && $price_type == 'N') {
                $price_obj->setPrice($price);
            }
            $price_obj->setAutoPrice($price_type);
            $price_obj->setListingStatus($_POST['price'][$sku][$platform_id]['listing_status']);

            if (isset($_POST['price'][$sku][$platform_id]['is_advertised'])) {
                $price_obj->setIsAdvertised('Y');
            } else {
                $price_obj->setIsAdvertised('N');
            }

            // transaction start
            $this->sc['Price']->getDao('Price')->db->trans_start();
            $this->sc['Price']->getDao('Price')->update($price_obj);
            $this->sc['PriceMargin']->refreshProfitAndMargin($platform_id, $sku);
            $result = $this->sc['Price']->getDao('Price')->db->trans_complete();
            // transaction end;

            if ($result) {
                $googleSku[$platform_id][] = $sku;
            }

            $product_obj = $this->sc['Product']->get(['sku' => $sku]);
            if (!$product_obj) {
                continue;
            }

            $product_obj->setClearance($_POST['product'][$sku]['clearance']);
            $product_obj->setWebsiteQuantity($_POST['product'][$sku]['website_quantity']);
            $product_obj->setWebsiteStatus($_POST['product'][$sku]['website_status']);
            $product_obj->setAutoRestock($_POST['product'][$sku]['auto_restock']);
            $this->sc['Product']->getDao('Product')->update($product_obj);
        }

        foreach ($googleSku as $platform_id => $sku_collection) {
            $this->sc["PriceUpdateTrigger"]->triggerGoogleApi($sku_collection, $platform_id);
        }
    }

    public function index()
    {
        $sub_app_id = $this->getAppId().'00';
        include_once APPPATH.'language/'.$sub_app_id.'_'.$this->getLangId().'.php';

        if ($this->input->post('upload-sku-price')) {
            $this->importSkuPrice();
        }

        if ($this->input->post('posted') && $_POST['check']) {
            $this->updateProductOverview();

        }

        if ($this->input->get('search')) {
            $data = $this->query();
        }

        $data['clist'] = $this->sc['PlatformBizVar']->getDao('SellingPlatform')->getList(array('type' => PLATFORM_TYPE, 'status' => 1));
        $data['lang'] = $lang;
        $data['query_string'] = $_SERVER['QUERY_STRING'];
        $this->load->view('marketing/product_overview/product_overview_v', $data);
    }

    public function exportSkuPrice($where, $option)
    {
        $this->sc['BatchExportImport']->exportSkuPrice($where, $option);
    }

    public function importSkuPrice()
    {
        return $this->sc['BatchExportImport']->importSkuPrice($_FILES["datafile"]["tmp_name"]);
    }

    public function uploadClearanceSku()
    {
        $this->sc['BatchExportImport']->uploadClearanceSku($_FILES['clearance_datafile']['tmp_name']);
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
