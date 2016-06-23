<?php

class Product extends MY_Controller
{

    private $app_id = "MKT0003";
    private $lang_id = "en";
    private $google_feed_arr = array("AU", "BE", "GB", "ES", "FR", "IT", "PL", "NL");

    public function __construct()
    {
        parent::__construct();
        $this->load->model('marketing/product_model');
        $this->load->model('marketing/warranty_model');
        $this->load->model('mastercfg/custom_class_model');
        $this->load->helper(array('url', 'notice', 'object', 'image'));
        $this->load->library('service/product_service');
        $this->load->library('service/pagination_service');
        $this->load->library('service/context_config_service');
        $this->load->library('service/subject_domain_service');
        $this->load->library('service/translate_service');
        $this->load->library('service/platform_biz_var_service');
//        $this->load->library('service/product_update_followup_service');
//        $this->load->library('service/adwords_service');
        $this->load->library('service/country_service');
        $this->load->library('service/selling_platform_service.php');
    }

    public function metadata()
    {
        $objGrid = $this->create_grid("metadata");

        $customizegrid = true;
        if ($customizegrid) {
            $table = "metadata";

            $title = "Manage Metadata";

            $objGrid->TituloGrid($title);
            $objGrid->tabla($table);
            $objGrid->liquidTable = true;
            $objGrid->AjaxChanged('#900');

            $multi_level = false;
            if ($multi_level) {
                if ($gridid == 1) {
                    $objGrid->setDetailsGrid("manageevent_1?$linkparam", "id");
                } else {
                    $parentid = $objGrid->setMasterRelation("event_id");
                    $objGrid->toolbar = false;
                    $objGrid->FormatColumn("event_id", "Event ID", "150", "200", 1, "1", "left", "text", $parentid);
                }
            }

            # allow export
            $objGrid->export(true, true, true, true, true, 'P');
            $objGrid->csvSeparator = ",";
        }

        $setupfields = true;
        if ($setupfields) {
            // $objGrid->processData = "processData";
            $objGrid->keyfield("id");
            // $objGrid->searchby("name, id, skuid, retailerid, vendorid, tags");
            // $objGrid->orderby('next_deadline, id', "desc, desc");

            $objGrid->FormatColumn("id", "Event ID", "0", "12", 1, "1", "left", "text");
            $objGrid->FormatColumn("url", "Page URL contains...", "0", "200", 0, "20", "left", "text");
            $objGrid->FormatColumn("title", "Title (64 char max)", "0", "64", 0, "10", "center", "text");
            $objGrid->FormatColumn("description", "Description (160 char max)", "0", "160", 0, "20", "center", "text");
            $objGrid->FormatColumn("keyword", "Keywords", "0", "200", 0, "20", "center", "text");
            $objGrid->FormatColumn("remarks", "Remarks", "0", "12", 0, "20", "center", "text");
        }

        $gridcontent = $objGrid->grid();

        if ($objGrid->isAjaxRequest()) {
            echo $gridcontent . $executeaftergrid;
            return;
        }

        $gridheader = set_DG_Header("/js/", "/css/", "/", $skin);
        echo <<<html
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>$title</title>
</head>
$gridheader
<body>
    <div id="main" class="clearfix">
        <div class="contentwrap">
            <div id="content" style="max-width:100%">
                <div id='description'></div>
                <div id='dg'>
                    $gridcontent
                </div>
                <br>
            </div>
        </div>
    </div>
<!-- <script type="text/javascript" src="http://bluepeneditor.com/current/program/bluepen-loader.js"></script> -->
</body>
</html>
html;
// Page Generated in {{ server.loadtime }} seconds with {{server.memory_get_usage}}. Server time is {{ server.datetime }}/UTC: {{ server.utcdatetime }}
        return;
    }

    private function create_grid($phpfile = "", $gridid = 1)
    {
        include(APPPATH . 'config/database' . EXT);
        require_once(BASEPATH . 'plugins/phpMyDataGrid.class.php');
        $objGrid = new datagrid();

        $setup_grid = true;
        if ($setup_grid) {
            #es: Incluir el archivo de la libreria
            #en: Include class file

            #es: Crear el objeto contenedor
            #en: Create object container
            #if (!isset($gridid)) $gridid = 1;
            if ($debug) var_dump($_SERVER["CONTROLLER"]);

            $phpfile = "";
            if (isset($_SERVER["CONTROLLER"])) $phpfile = $_SERVER["CONTROLLER"];

            $objGrid = new datagrid($phpfile, "$gridid");

            $skin = "lightgray";

            // call this line only after we load the class
            set_DG_Header("/js/", "/css/", "/", $skin);

            // if (function_exists("datagrid_ex_processdata"))
            $objGrid->processDataAfter = "processDataAfter";

            $objGrid->pathtoimages("/images/");
            $objGrid->skinimages($skin, '/skins/%s/icons/');
            // $objGrid->keyboard_support(true);

            $objGrid->logfile = 'grid_error.txt';   # file will be generate in public_html or where index.php is located

            #$objGrid->logfile = '../public_html/logs/phpMyDGlogError.txt';
            #$objGrid->logfile = '/var/www/html/esourcing/public_html/logs/phpMyDGlogError.log';

            #es: Realizar la conexión con la base de datos
            #en: Connect with database

            #var_dump($_dbusername);

            $_dbhostname = $db['default']['hostname'];
            $_dbusername = $db['default']['username'];
            $_dbpassword = $db['default']['password'];
            $_dbdatabase = $db['default']['database'];

            $objGrid->conectadb($_dbhostname, $_dbusername, $_dbpassword, $_dbdatabase);

            #strangely, must do here too, otherwise ajax updates track editorid
            #date_default_timezone_set('Singapore');
            $objGrid->SQL_query("set autocommit = 1");
            // $test = "SET time_zone = '{$_CONFIG['time_zone_offset']}'";
            // $objGrid->SQL_query($test);
            $test = "SET @enable_trigger = 1";
            $objGrid->SQL_query($test);
            #var_dump($test);die();

            if (isset($_SESSION['editorid'])) {
                $objGrid->SQL_query("SET @editor_id = '" . $_SESSION['editorid'] . "';");
                $thiseditorid = $_SESSION['editorid'];

                switch (strtolower($_SESSION['editorid'])) {
                    case "jesslyn":
                    case "teik":
                    case "fiona":
                    case "simon":
                    case "wayne":
                        $isadmin = true;
                        break;
                    default:
                        $isadmin = false;
                        break;
                }
            }

            #$objGrid->SQL_query("SET @thiseditorid = 'teik';");
            #$objGrid->TituloGrid($title);

            #en: Define allowed actions
            $objGrid->reload = true;
            $objGrid->toolbar = true;

            //  add, update, delete, check
            $objGrid->buttons(true, true, false, false, -1, "");

            $objGrid->strExportInline = true;
            $objGrid->strSearchInline = true;
            $objGrid->zebraLines = false;

            $objGrid->liquidTable = true;
            $objGrid->width = "100%";

            $objGrid->ajax("silent", 1);
            $objGrid->friendlyHTML = true;

            $objGrid->saveaddnew = true;
            $objGrid->useCalendar(true);
            $objGrid->closeTags(true);  #xhtml compatibility

            #$objGrid->useRightClickMenu("class/phpMyMenu.inc.php");

            #en: Define amount of records to display per page
            $objGrid->datarows(10);

            #$objGrid->setDetailsGrid("qv6_1.php", "skuid");
            #$mastersku = substr("000000" . $objGrid->setMasterRelation("skuid"),-12);

            $objGrid->sqlcharset = "utf8";
            $objGrid->charset = 'UTF-8';

            $objGrid->retcode = true;
        }
        return $objGrid;
    }

    public function translat_all_sku()
    {
        $sub_app_id = $this->getAppId() . "03";
        $data = array();
        $data["lang_list"] = $this->product_model->get_list("language", array("status" => 1), array("orderby" => "name ASC"));

        $lang_id = $this->input->post("language_id");
        $limit = $this->input->post("limit");
        $model = $this->input->post("model");
        $start_date = $this->input->post("start_date");
        $end_date = $this->input->post("end_date");
        $sku_list = $this->input->post("sku_list");

        if ($model == "first" && $lang_id && ($start_date < $end_date)) {
            $option = array();
            $where = array();
            $sort = $this->input->get("sort");
            $order = $this->input->get("order");
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
            $where["status"] = 2;
            $where["language_id"] = $lang_id;
            $where["start_date"] = $start_date;
            $where["end_date"] = $end_date;
            $objlist = $this->product_model->get_product_list($where, $option);
            $total = $this->product_model->get_product_list_total($where);
            $data["start_date"] = $start_date;
            $data["end_date"] = $end_date;
        } elseif ($sku_list && $model == "second" && $lang_id) {
            $objlist = explode(',', $sku_list);
            $data["start_date"] = $date = date('Y-m-d', strtotime(date('Y-m-d') . ' - 10 day'));
            $data["end_date"] = date('Y-m-d');
        } else {
            $data["start_date"] = $date = date('Y-m-d', strtotime(date('Y-m-d') . ' - 10 day'));
            $data["end_date"] = date('Y-m-d');
        }
        if ($objlist && $lang_id) {
            foreach ($objlist as $obj) {

                switch ($model) {
                    case 'first':
                        $sku = $obj->get_sku();
                        break;

                    case 'second':
                        $sku = $obj;
                        break;
                }
                $this->product_model->product_service->translate_product_content($sku, $lang_id, $translated_product_name);
                if ($translated_product_name) {
                    foreach ($this->google_feed_arr as $cid) {
                        $this->update_google_product_title($sku, $lang_id, $cid, $google_cat_id = null, $translated_product_name);
                    }
                }
                if ($pc_obj = $this->product_model->product_service->get_pc_dao()->get(array("prod_sku" => $sku, "lang_id" => "en"))) {
                    $google_product_name = $pc_obj->get_prod_name();
                    if ($google_product_name) {
                        foreach ($this->google_feed_arr as $cid) {
                            $this->update_google_product_title($sku, 'en', $cid, $google_cat_id = null, $google_product_name);
                        }
                    }
                }
            }
        }

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;
        $this->load->view('marketing/product/batch_translat', $data);
    }

    public function getAppId()
    {
        return $this->app_id;
    }

    private function update_google_product_title($sku, $lang_id, $cid, $google_cat_id, $google_product_name)
    {
        if ($platform_biz_var_obj = $this->country_service->get_country_dao()->get(array("language_id" => $lang_id, "id" => $cid))) {
            $google_cat_id = null;
            $this->product_model->category_mapping_service->update_or_insert_mapping($sku, $lang_id, $cid, $google_cat_id, $google_product_name);
        }
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function index($prod_grp_cd = "")
    {
        $sub_app_id = $this->getAppId() . "00";
        $_SESSION["LISTPAGE"] = ($prod_grp_cd == "" ? base_url() . "marketing/product/?" : current_url()) . $_SERVER['QUERY_STRING'];

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

        if ($this->input->get("master_sku") != "") {
            $where["master_sku"] = $this->input->get("master_sku");
            $submit_search = 1;
        }

        if ($this->input->get("name") != "") {
            $where["name"] = $this->input->get("name");
            $submit_search = 1;
        }

        if ($this->input->get("colour") != "") {
            $where["colour"] = $this->input->get("colour");
            $submit_search = 1;
        }

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
            $data["objlist"] = $this->product_model->get_product_list($where, $option);
            $data["total"] = $this->product_model->get_product_list_total($where);
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

        #http://sbf.eservicesgroup.net/issues/2707

        $this->load->view('marketing/product/product_index_v', $data);
    }

    public function download_unmapped_sku()
    {
        header("Content-type: text/csv");
        header("Cache-Control: no-store, no-cache");
        header("Content-Disposition: attachment; filename=\"unmapped_sku.csv\"");

        $list = $this->product_model->product_service->get_dao()->get_unmapped_sku();

        echo "sku, ext_sku\r\n";
        foreach ($list as $item) {
            echo "{$item['sku']},{$item['ext_sku']}\r\n";
        }
    }

    public function upload_mapped_sku()
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

        require_once(BASEPATH . 'plugins/csv_parser_pi.php');
        $csvfile = new CSVFileLineIterator($_FILES["datafile"]["tmp_name"]);

        $arr = csv_parse($csvfile);

        unset($arr[0]); # remove the header

        echo "<a href='/'>Go back to main menu</a><hr>";
        foreach ($arr as $line) {
            $line[0] = strtoupper($line[0]);
            $line[1] = strtoupper($line[1]);
            $affected = $this->product_model->product_service->get_dao()->map_sku($line[0], $line[1]);
            switch ($affected) {
                case 1:
                    echo "SUCCESS($affected): Local SKU {$line[0]} mapped to Master SKU {$line[1]}<br>\r\n";

                    $url = "http://admincentre.valuebasket.com/cps/webapi.pullskuprice.php?sku={$line[1]}";
                    // $url = "http://admindev.valuebasket.com/cps/webapi.pullskuprice.php?sku={$line[1]}";
                    echo "Update SKU by loading $url<br>\r\n";
                    $response = file_get_contents($url);
                    echo "Response was " . strlen($response) . " bytes<br>\r\n";
                    // echo "<hr>$response<hr>";
                    if (stripos($response, "Updated: 1") === false and stripos($response, "inserted: 1") === false)
                        mail("tslau@eservicesgroup.com, jesslyn@eservicesgroup.com", "[VB] CSV upload of SKU mapping, but failed to update {$line[1]}", $response);

                    break;

                default:
                    echo "FAIL($affected): Cannot map Local SKU {$line[0]} to Master SKU {$line[1]}<br>\r\n";
                    break;
            }
        }

        mail("tslau@eservicesgroup.com, jesslyn@eservicesgroup.com", "[VB] CSV upload of SKU mapping completed", $response);
    }


    public function testAdd(array $data)
    {
        $versionList = $data['joined_vlist'] ?: ['AA:All Version'];
        $data['prod_sku'] = $data['sku'] = $this->product_model->product_service->get_dao()->db->query("SELECT next_value('sku') as sku")->row('sku');
        $data['prod_grp_cd'] = $this->product_model->product_service->get_dao()->db->query("SELECT next_value('prod_grp_cd') as prod_grp_cd")->row('prod_grp_cd');
        $data['version_id'] = 'AA';
        $data['supplier_id'] = '4';
        $data['currency_id'] = 'HKD';
        $data['lead_day'] = '1';
        $data['moq'] = 1;

        $productVo = $this->sc['productVoByPost']->pick($data);
        $supplierProdVo = $this->sc['supplierProdVoByPost']->pick($data);

        $this->sc['productService']->getDao()->insert($productVo);
        $this->sc['supplierProdDao']->insert($supplierProdVo);

        return $data['prod_grp_cd'];
    }

    public function save()
    {
        $this->sc['ProductCreation']->saveByXML($xml);
    }



    public function add()
    {
        $sub_app_id = $this->getAppId() . "01";
        if (!check_app_feature_access_right($this->getAppId(), 'MKT000301_add_product')) {
            show_error("Access Denied!");
        }
        if ($this->input->post("posted")) {

            $prod_grp_cd = $this->testAdd($_POST);
            redirect(base_url() . "marketing/product/index/" . $prod_grp_cd);

            // if (isset($_SESSION["product_vo"])) {
            //     $this->product_model->include_vo("product");
            //     $data["product"] = unserialize($_SESSION["product_vo"]);
            //     $_POST["status"] = 1;
            //     $_POST["rrp"] = $_POST["archive"] = $_POST["clearance"] = 0;
            //     $_POST["website_status"] = 'I';
            //     $_POST["sourcing_status"] = 'A';
            //     $_POST["website_quantity"] = $_POST["quantity"] = 0;
            //     if ($_POST["sub_sub_cat_id"] == NULL) {
            //         $_POST["sub_sub_cat_id"] = '0';
            //     }
            //     set_value($data["product"], $_POST);

            //     $data["supp_prod"] = $this->product_model->get_supplier_prod();
            //     $prod_custom_class_vo = $this->product_model->get_product_custom_classification();

            //     $prod_grp_cd = $this->product_model->seq_next_val();

            //     $sub_cat_id = $this->input->post("sub_cat_id");
            //     $sub_cat_obj = $this->product_model->get("category", array("id" => $sub_cat_id));

            //     if (is_array($this->input->post("joined_vlist"))) {
            //         $version_list = $this->input->post("joined_vlist");
            //     } else {
            //         $version_list = array("AA::All Version");
            //     }

            //     $this->product_model->product_service->get_dao()->trans_start();

            //     foreach ($this->input->post("joined_list") as $colour) {
            //         list($colour_id, $colour_name) = explode("::", $colour);
            //         foreach ($version_list as $version) {
            //             list($version_id, $version_name) = explode("::", $version);
            //             $sku = str_pad($prod_grp_cd . "-" . $version_id . "-" . $colour_id, 11, "0", STR_PAD_LEFT);
            //             $data["product"]->set_sku($sku)->set_prod_grp_cd($prod_grp_cd)->set_colour_id($colour_id)->set_version_id($version_id)->set_proc_status('0')->set_name($_POST["name"] . (($sub_cat_obj->get_add_colour_name() && $colour_id != "NA") ? " ({$colour_name})" : ""));

            //             // default supp_id to 4
            //             $data["supp_prod"]->set_supplier_id(4);
            //             $data["supp_prod"]->set_prod_sku($sku);
            //             $data["supp_prod"]->set_cost($_POST["cost"]);
            //             $data["supp_prod"]->set_currency_id("HKD");
            //             $data["supp_prod"]->set_order_default("1");
            //             $data["supp_prod"]->set_moq("1");
            //             $data["supp_prod"]->set_supplier_status("O");

            //             if ($cc_list = $this->product_model->get_custom_class_mapping_by_sub_cat_id($sub_cat_id)) {
            //                 foreach ($cc_list as $country_id => $cc_obj) {
            //                     if (empty($cc_obj)) {
            //                         $_SESSION["NOTICE"] = "Custom Classification Code for Sub-Category '" . $sub_cat_obj->get_name() . "' is missing.";
            //                         break 2;
            //                     }
            //                     $tmp = clone $prod_custom_class_vo;
            //                     $tmp->set_sku($sku);
            //                     $tmp->set_country_id($country_id);
            //                     $tmp->set_code($cc_obj->get_code());
            //                     $tmp->set_description($cc_obj->get_description());
            //                     $tmp->set_duty_pcent($cc_obj->get_duty_pcent());
            //                     $data["prod_custom_class"][$country_id] = $tmp;
            //                 }
            //             }

            //             if ($new_obj = $this->product_model->add("product", $data["product"])) {
            //                 $this->product_model->update_seq($prod_grp_cd);
            //                 if (!($new_obj = $this->product_model->add_supplier_prod($data["supp_prod"]))) {
            //                     $_SESSION["NOTICE"] = "__FILE__:__LINE__" . $this->db->_error_message();
            //                     break;
            //                 }

            //                 foreach ($data["prod_custom_class"] as $prod_custom_class_obj) {
            //                     if (!($new_cc_obj = $this->product_model->add_product_custom_class($prod_custom_class_obj))) {
            //                         $_SESSION["NOTICE"] = __FILE__ . ":" . __LINE__ . ", " . $this->db->_error_message();
            //                         break;
            //                     }
            //                 }
            //             } else {
            //                 $_SESSION["NOTICE"] = __FILE__ . ":" . __LINE__ . ", " . $this->db->_error_message();
            //                 break;
            //             }
            //         }
            //     }

            //     // if ($this->input->post("prod_type")) {
            //     //     if ($this->product_model->get_product_type(array("sku" => $sku))) {
            //     //         $this->product_model->del_product_type(array("sku" => $sku));
            //     //     }
            //     //     foreach ($this->input->post("prod_type") as $prod_type) {
            //     //         $prod_type_obj = $this->product_model->get_product_type();
            //     //         $prod_type_obj->set_sku($sku);
            //     //         $prod_type_obj->set_type_id($prod_type);
            //     //         $prod_type_obj->set_status(1);
            //     //         if (!$this->product_model->add_product_type($prod_type_obj)) {
            //     //             $_SESSION["NOTICE"] = __FILE__ . ":" . __LINE__ . ", " . $this->db->_error_message();
            //     //         }
            //     //     }
            //     // }
            //     $this->product_model->product_service->get_dao()->trans_complete();
            //     if (empty($_SESSION["NOTICE"])) {
            //         unset($_SESSION["product_vo"]);
            //         unset($_SESSION["NOTICE"]);
            //         redirect(base_url() . "marketing/product/index/" . $prod_grp_cd);
            //     } else {
            //         $data["product"]->set_sku("");
            //         $data["product"]->set_prod_grp_cd("");
            //         $data["product"]->set_colour_id("");
            //         $data["product"]->set_proc_status('0');
            //         $data["product"]->set_name($_POST["name"]);
            //     }
            // }
        }

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;

        if (empty($data["product"])) {
            if (($data["product"] = $this->product_model->get("product")) === FALSE) {
                $_SESSION["NOTICE"] = __FILE__ . ":" . __LINE__ . ", " . $this->db->_error_message();
            } else {
                $_SESSION["product_vo"] = serialize($data["product"]);
            }
        }

        $data["brand_list"] = $this->product_model->get_list("brand", array(), array("orderby" => "brand_name ASC", "limit" => "-1"));
        if (!isset($data["supp_prod"])) {
            $data["supp_prod"] = $this->product_model->get_supplier_prod();
            $data["supp_prod"]->set_currency_id('HKD');
        }

        // $data["prod_type"] = $this->product_model->get_product_type_list(array("sku" => $sku));
        $data['colour_list'] = $this->sc['colourModel']->getList(['status' => 1], ['orderby' => 'colour_id DESC', 'limit' => '-1']);
        $data["version_list"] = $this->product_model->get_list("version", array("status" => 'A'));
        $data["type_list"] = $this->subject_domain_service->get_subj_list_w_subj_lang("MKT.PROD_TYPE.PROD_TYPE_ID", "en");
        $data["joined_list"] = array();
        $data["joined_vlist"] = array();


        // if ($this->input->post("joined_list")) {
        //     $inc_list = $this->input->post("joined_list");
        //     $data["joined_list"] = get_inclusion($data["colour_list"], $inc_list, array("id", "name"));
        //     $data["colour_list"] = get_exclusion($data["colour_list"], $inc_list, array("id", "name"));
        // }
        // if ($this->input->post("joined_vlist")) {
        //     $inc_list = $this->input->post("joined_vlist");
        //     $data["joined_vlist"] = get_inclusion($data["version_list"], $inc_list, array("id", "desc"));
        //     $data["version_list"] = get_exclusion($data["version_list"], $inc_list, array("id", "desc"));
        // }

        $data["notice"] = notice($lang);
        $data["cmd"] = "add";
        $this->load->view('marketing/product/product_detail_v', $data);
    }

    public function add_colour($prod_grp_cd = "", $colour_id = "")
    {
        $sub_app_id = $this->getAppId() . "01";

        if ($this->input->post("posted")) {
            if (isset($_SESSION["product_vo"])) {
                $this->product_model->include_vo("product");
                $data["product"] = unserialize($_SESSION["product_vo"]);
                $prod_obj = unserialize($_SESSION["product_obj"]);
                $_POST["status"] = 1;
                $_POST["rrp"] = $_POST["archive"] = $_POST["clearance"] = 0;
                $_POST["website_status"] = 'I';
                $_POST["sourcing_status"] = 'A';
                $_POST["website_quantity"] = $_POST["quantity"] = 0;
                set_value_vo($data["product"], $_POST);

                $data["supp_prod"] = $this->product_model->get_supplier_prod();
                $prod_custom_class_vo = $this->product_model->get_product_custom_classification();

                $sub_cat_id = $this->input->post("sub_cat_id");
                $sub_cat_obj = $this->product_model->get("category", array("id" => $sub_cat_id));

                //$this->product_model->product_service->get_dao()->trans_start();

                $prod_cont = $this->product_model->get_product_content_list(array("prod_sku" => $prod_obj->get_sku()));
                $prod_cont_ext = $this->product_model->get_product_content_extend_list(array("prod_sku" => $prod_obj->get_sku()));
                // $prod_type = $this->product_model->get_product_type_list(array("sku" => $prod_obj->get_sku(), "status" => 1));
                $cat_map = $this->product_model->get_category_mapping_list(array("ext_party" => "GOOGLEBASE", "level" => 0, "id" => $prod_obj->get_sku(), "status" => 1));

                $version_list = $this->product_model->get_existing_proplist("version", array("prod_grp_cd" => $prod_obj->get_prod_grp_cd()));

                foreach ($this->input->post("joined_list") as $colour) {
                    list($colour_id, $colour_name) = explode("::", $colour);
                    foreach ($version_list as $version_id) {
                        //list($version_id,$version_name) = explode("::",$version);

                        $sku = str_pad($prod_grp_cd . "-" . $version_id . "-" . $colour_id, 11, "0", STR_PAD_LEFT);
                        $name = $data["product"]->get_name();
                        $narr = explode("(", $name);
                        if (count($narr) > 1) {
                            unset($narr[count($narr) - 1]);
                        }
                        $nname = implode("(", $narr) . (($sub_cat_obj->get_add_colour_name() && $colour_id != "NA") ? " ({$colour_name})" : "");
                        $data["product"]->set_sku($sku)->set_version_id($version_id)->set_prod_grp_cd($prod_grp_cd)->set_colour_id($colour_id)->set_proc_status('0')->set_name($nname);
                        if ($_POST["sub_sub_cat_id"] == "") {
                            $_POST["sub_sub_cat_id"] = 0;
                        }
                        $data["product"]->set_sub_sub_cat_id($_POST["sub_sub_cat_id"]);
                        $data["product"]->set_status(1);
                        $data["supp_prod"]->set_supplier_id(4);
                        $data["supp_prod"]->set_prod_sku($sku);
                        $data["supp_prod"]->set_cost($_POST["cost"]);
                        $data["supp_prod"]->set_currency_id("HKD");
                        $data["supp_prod"]->set_order_default("1");
                        $data["supp_prod"]->set_moq("1");
                        $data["supp_prod"]->set_supplier_status("A");

                        if ($cc_list = $this->product_model->get_custom_class_mapping_by_sub_cat_id($sub_cat_id)) {
                            foreach ($cc_list as $country_id => $cc_obj) {
                                if (empty($cc_obj)) {
                                    $_SESSION["NOTICE"] = "Custom Classification Code for Sub-Category '" . $sub_cat_obj->get_name() . "' is missing.";
                                    break 2;
                                }
                                $tmp = clone $prod_custom_class_vo;
                                $tmp->set_sku($sku);
                                $tmp->set_country_id($country_id);
                                $tmp->set_code($cc_obj->get_code());
                                $tmp->set_description($cc_obj->get_description());
                                $tmp->set_duty_pcent($cc_obj->get_duty_pcent());
                                $data["prod_custom_class"][$country_id] = $tmp;
                            }
                        }

                        if ($new_obj = $this->product_model->add("product", $data["product"])) {
                            if ($this->product_model->add_supplier_prod($data["supp_prod"])) {
                                if ($prod_cont) {
                                    foreach ($prod_cont as $pc_obj) {
                                        //sbf5799 Some problems on VB "Translate for all languages"
                                        $pc_lang_id = $pc_obj->get_lang_id();
                                        if ($pc_lang_id != 'en') {
                                            $pc_obj->set_short_desc('');
                                            $pc_obj->set_detail_desc('');
                                            $pc_obj->set_contents('');
                                            $pc_obj->set_prod_name('');
                                        }
                                        $pc_obj->set_prod_name_original('');
                                        $pc_obj->set_keywords_original('');
                                        $pc_obj->set_contents_original('');
                                        $pc_obj->set_detail_desc_original('');
                                        $pc_obj->set_keywords('');
                                        $pc_obj->set_prod_sku($sku);
                                        if (!$this->product_model->add_product_content($pc_obj)) {
                                            $_SESSION["NOTICE"] = __FILE__ . ":" . __LINE__ . ", " . $this->db->_error_message();
                                        }
                                    }
                                }

                                if ($prod_cont_ext) {
                                    foreach ($prod_cont_ext as $pcex_obj) {
                                        //sbf5799 Some problems on VB "Translate for all languages"
                                        $pcex_lang_id = $pcex_obj->get_lang_id();
                                        if ($pcex_lang_id != 'en') {
                                            $pcex_obj->set_feature('');
                                            $pcex_obj->set_specification('');
                                        }
                                        $pcex_obj->set_feature_original('');
                                        $pcex_obj->set_spec_original('');
                                        $pcex_obj->set_prod_sku($sku);
                                        if (!$this->product_model->add_product_content_extend($pcex_obj)) {
                                            $_SESSION["NOTICE"] = __FILE__ . ":" . __LINE__ . ", " . $this->db->_error_message();
                                        }
                                    }
                                }

                                if ($prod_type) {
                                    foreach ($prod_type as $pt_obj) {
                                        $pt_obj->set_sku($sku);
                                        if (!$this->product_model->add_product_type($pt_obj)) {
                                            $_SESSION["NOTICE"] = __FILE__ . ":" . __LINE__ . ", " . $this->db->_error_message();
                                        }
                                    }
                                }

                                if ($cat_map) {
                                    foreach ($cat_map as $cm_obj) {
                                        $cm_obj->set_id($sku);
                                        if (!$this->product_model->add_category_mapping($cm_obj)) {
                                            $_SESSION["NOTICE"] = __FILE__ . ":" . __LINE__ . ", " . $this->db->_error_message();
                                        }
                                    }
                                }
                            }

                            if ($data["prod_custom_class"]) {
                                foreach ($data["prod_custom_class"] as $prod_custom_class_obj) {
                                    if (!($new_cc_obj = $this->product_model->add_product_custom_class($prod_custom_class_obj))) {
                                        $_SESSION["NOTICE"] = __FILE__ . ":" . __LINE__ . ", " . $this->db->_error_message();
                                        break;
                                    }
                                }
                            }
                        } else {
                            $_SESSION["NOTICE"] = __FILE__ . ":" . __LINE__ . ", " . $this->db->_error_message();
                            break;
                        }
                    }
                }

                //$this->product_model->product_service->get_dao()->trans_complete();

                if (empty($_SESSION["NOTICE"])) {
                    unset($_SESSION["product_vo"]);
                    redirect(base_url() . "marketing/product/index/" . $prod_grp_cd);
                } else {
                    $data["product"]->set_sku("");
                    $data["product"]->set_prod_grp_cd("");
                    $data["product"]->set_colour_id("");
                    $data["product"]->set_proc_status('0');
                    $data["product"]->set_name($_POST["name"]);
                }
            }
        }

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;

        if (empty($data["product"])) {
            $where["prod_grp_cd"] = $prod_grp_cd;
            if ($colour_id) {
                $where["colour_id"] = $colour_id;
            }
            if (($data["product"] = $this->product_model->get("product", $where)) === FALSE) {
                $_SESSION["NOTICE"] = __FILE__ . ":" . __LINE__ . ", " . $this->db->_error_message();
            } else {
                $_SESSION["product_obj"] = serialize($data["product"]);
            }

            if (($data["product_vo"] = $this->product_model->get("product")) === FALSE) {
                $_SESSION["NOTICE"] = __FILE__ . ":" . __LINE__ . ", " . $this->db->_error_message();
            } else {
                $_SESSION["product_vo"] = serialize($data["product_vo"]);
            }
        }

        $data["brand_list"] = $this->product_model->get_list("brand", array(), array("orderby" => "brand_name ASC", "limit" => "-1"));
        $data["colour_list"] = $this->product_model->get_remain_colour_list($prod_grp_cd);
        $data["joined_list"] = array();

        // if ($this->input->post("joined_list")) {
        //     $inc_list = $this->input->post("joined_list");
        //     $data["joined_list"] = get_inclusion($data["colour_list"], $inc_list, array("id", "name"));
        //     $data["colour_list"] = get_exclusion($data["colour_list"], $inc_list, array("id", "name"));
        // }

        $data["notice"] = notice($lang);
        $data["cmd"] = "add";
        $data["prod_grp_cd"] = $prod_grp_cd;
        $data["colour_id"] = $colour_id;
        $data["add_type"] = "colour";

        $this->load->view('marketing/product/product_detail_v', $data);
    }

    public function add_version($prod_grp_cd = "", $version_id = "")
    {
        $sub_app_id = $this->getAppId() . "01";

        if ($this->input->post("posted")) {
            if (isset($_SESSION["product_vo"])) {
                $this->product_model->include_vo("product");
                $data["product"] = unserialize($_SESSION["product_vo"]);
                $prod_obj = unserialize($_SESSION["product_obj"]);

                $_POST["status"] = 1;
                $_POST["rrp"] = $_POST["archive"] = $_POST["clearance"] = 0;
                $_POST["website_status"] = 'I';
                $_POST["sourcing_status"] = 'A';
                $_POST["website_quantity"] = $_POST["quantity"] = 0;
                set_value_vo($data["product"], $_POST);

                $data["supp_prod"] = $this->product_model->get_supplier_prod();
                $prod_custom_class_vo = $this->product_model->get_product_custom_classification();

                $sub_cat_id = $this->input->post("sub_cat_id");
                $sub_cat_obj = $this->product_model->get("category", array("id" => $sub_cat_id));

                //$this->product_model->product_service->get_dao()->trans_start();

                $prod_cont = $this->product_model->get_product_content_list(array("prod_sku" => $prod_obj->get_sku()));
                $prod_cont_ext = $this->product_model->get_product_content_extend_list(array("prod_sku" => $prod_obj->get_sku()));
                // $prod_type = $this->product_model->get_product_type_list(array("sku" => $prod_obj->get_sku(), "status" => 1));
                $cat_map = $this->product_model->get_category_mapping_list(array("ext_party" => "GOOGLEBASE", "level" => 0, "id" => $prod_obj->get_sku(), "status" => 1));

                $colour_list = $this->product_model->get_existing_proplist("colour", array("prod_grp_cd" => $prod_obj->get_prod_grp_cd()));

                foreach ($this->input->post("joined_vlist") as $version) {
                    list($version_id, $version_name) = explode("::", $version);
                    foreach ($colour_list as $colour) {
                        list($colour_id, $colour_name) = explode("::", $colour);

                        $sku = str_pad($prod_grp_cd . "-" . $version_id . "-" . $colour_id, 11, "0", STR_PAD_LEFT);
                        $name = $data["product"]->get_name();
                        $narr = explode("(", $name);
                        if (count($narr) > 1) {
                            unset($narr[count($narr) - 1]);
                        }
                        $nname = implode("(", $narr) . (($sub_cat_obj->get_add_colour_name() && $colour_id != "NA") ? " ({$colour_name})" : "");
                        $data["product"]->set_sku($sku)->set_version_id($version_id)->set_prod_grp_cd($prod_grp_cd)->set_colour_id($colour_id)->set_proc_status('0')->set_name($nname);
                        if ($_POST["sub_sub_cat_id"] == "") {
                            $_POST["sub_sub_cat_id"] = 0;
                        }
                        $data["product"]->set_sub_sub_cat_id($_POST["sub_sub_cat_id"]);
                        $data["product"]->set_status(1);
                        $data["supp_prod"]->set_supplier_id(4);
                        $data["supp_prod"]->set_prod_sku($sku);
                        $data["supp_prod"]->set_cost($_POST["cost"]);
                        $data["supp_prod"]->set_currency_id("HKD");
                        $data["supp_prod"]->set_order_default("1");
                        $data["supp_prod"]->set_moq("1");
                        $data["supp_prod"]->set_supplier_status("A");

                        if ($cc_list = $this->product_model->get_custom_class_mapping_by_sub_cat_id($sub_cat_id)) {
                            foreach ($cc_list as $country_id => $cc_obj) {
                                if (empty($cc_obj)) {
                                    $_SESSION["NOTICE"] = "Custom Classification Code for Sub-Category '" . $sub_cat_obj->get_name() . "' is missing.";
                                    break 2;
                                }
                                $tmp = clone $prod_custom_class_vo;
                                $tmp->set_sku($sku);
                                $tmp->set_country_id($country_id);
                                $tmp->set_code($cc_obj->get_code());
                                $tmp->set_description($cc_obj->get_description());
                                $tmp->set_duty_pcent($cc_obj->get_duty_pcent());
                                $data["prod_custom_class"][$country_id] = $tmp;
                            }
                        }

                        if ($new_obj = $this->product_model->add("product", $data["product"])) {
                            if ($this->product_model->add_supplier_prod($data["supp_prod"])) {
                                if ($prod_cont) {
                                    foreach ($prod_cont as $pc_obj) {
                                        $pc_obj->set_prod_sku($sku);
                                        if (!$this->product_model->add_product_content($pc_obj)) {
                                            $_SESSION["NOTICE"] = __FILE__ . ":" . __LINE__ . ", " . $this->db->_error_message();
                                        }
                                    }
                                }

                                if ($prod_cont_ext) {
                                    foreach ($prod_cont_ext as $pcex_obj) {
                                        $pcex_obj->set_prod_sku($sku);
                                        if (!$this->product_model->add_product_content_extend($pcex_obj)) {
                                            $_SESSION["NOTICE"] = __FILE__ . ":" . __LINE__ . ", " . $this->db->_error_message();
                                        }
                                    }
                                }

                                if ($prod_type) {
                                    foreach ($prod_type as $pt_obj) {
                                        $pt_obj->set_sku($sku);
                                        if (!$this->product_model->add_product_type($pt_obj)) {
                                            $_SESSION["NOTICE"] = __FILE__ . ":" . __LINE__ . ", " . $this->db->_error_message();
                                        }
                                    }
                                }

                                if ($cat_map) {
                                    foreach ($cat_map as $cm_obj) {
                                        $cm_obj->set_id($sku);
                                        if (!$this->product_model->add_category_mapping($cm_obj)) {
                                            $_SESSION["NOTICE"] = __FILE__ . ":" . __LINE__ . ", " . $this->db->_error_message();
                                        }
                                    }
                                }

                                if ($data["prod_custom_class"]) {
                                    foreach ($data["prod_custom_class"] as $prod_custom_class_obj) {
                                        if (!($new_cc_obj = $this->product_model->add_product_custom_class($prod_custom_class_obj))) {
                                            $_SESSION["NOTICE"] = __FILE__ . ":" . __LINE__ . ", " . $this->db->_error_message();
                                            break;
                                        }
                                    }
                                }
                            }
                        } else {
                            $_SESSION["NOTICE"] = __FILE__ . ":" . __LINE__ . ", " . $this->db->_error_message();
                            break;
                        }
                    }
                }

                //$this->product_model->product_service->get_dao()->trans_complete();

                if (empty($_SESSION["NOTICE"])) {
                    unset($_SESSION["product_vo"]);
                    redirect(base_url() . "marketing/product/index/" . $prod_grp_cd);
                } else {
                    $data["product"]->set_sku("");
                    $data["product"]->set_prod_grp_cd("");
                    $data["product"]->set_colour_id("");
                    $data["product"]->set_proc_status('0');
                    $data["product"]->set_name($_POST["name"]);
                }
            }
        }

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;

        if (empty($data["product"])) {
            $where["prod_grp_cd"] = $prod_grp_cd;
            if ($colour_id) {
                $where["colour_id"] = $colour_id;
            }
            if (($data["product"] = $this->product_model->get("product", $where)) === FALSE) {
                $_SESSION["NOTICE"] = __FILE__ . ":" . __LINE__ . ", " . $this->db->_error_message();
            } else {
                $_SESSION["product_obj"] = serialize($data["product"]);
            }

            if (($data["product_vo"] = $this->product_model->get("product")) === FALSE) {
                $_SESSION["NOTICE"] = __FILE__ . ":" . __LINE__ . ", " . $this->db->_error_message();
            } else {
                $_SESSION["product_vo"] = serialize($data["product_vo"]);
            }
        }

        $data["brand_list"] = $this->product_model->get_list("brand", array(), array("orderby" => "brand_name ASC", "limit" => "-1"));

        $data["version_list"] = $this->product_model->get_remain_version_list($prod_grp_cd);

        $data["joined_vlist"] = array();

        if ($this->input->post("joined_vlist")) {
            $inc_list = $this->input->post("joined_vlist");
            $data["joined_vlist"] = get_inclusion($data["version_list"], $inc_list, array("id", "desc"));
            $data["version_list"] = get_exclusion($data["version_list"], $inc_list, array("id", "desc"));
        }

        $data["notice"] = notice($lang);
        $data["cmd"] = "add";
        $data["prod_grp_cd"] = $prod_grp_cd;
        $data["colour_id"] = $colour_id;
        $data["add_type"] = "version";
        $this->load->view('marketing/product/product_detail_v', $data);
    }

    public function empty_extra_info($sku = "", $lang_id = "en")
    {
        if ($sku) {
            $err = 0;
            $pc_list = $this->product_model->get_product_content_list(array("prod_sku" => $sku), array("limit" => -1));
            $this->product_model->product_service->get_dao()->trans_start();
            foreach ($pc_list as $pc) {
                $pc->set_extra_info("");

                if ($this->product_model->update_product_content($pc) === FALSE) {
                    $_SESSION["notice"] = "Error in Line " . __LINE__ . " : " . $this->db->_error_message();
                    $err++;
                    break;
                }
            }
            if ($err) {
                $this->product_model->product_service->get_dao()->trans_rollback();
            }
            $this->product_model->product_service->get_dao()->trans_complete();
            Redirect(base_url() . "marketing/product/view/" . $sku . "/" . $lang_id);
        }
    }

    public function translate_product_content($sku = "", $lang_id = "en")
    {
        $lang_id_list = explode(',', $lang_id);
        foreach ($lang_id_list as $lang_id) {
            $this->product_model->product_service->translate_product_content($sku, $lang_id, $translated_product_name);
            #SBF2701 get back the translation, then next update the googlebase product name
            if ($translated_product_name) {
                foreach ($this->google_feed_arr as $cid) {
                    $this->update_google_product_title($sku, $lang_id, $cid, $google_cat_id = null, $translated_product_name);
                }
            }
        }

        //copy the English product name to google product title.
        if ($pc_obj = $this->product_model->product_service->get_pc_dao()->get(array("prod_sku" => $sku, "lang_id" => "en"))) {
            $google_product_name = $pc_obj->get_prod_name();
            if ($google_product_name) {
                foreach ($this->google_feed_arr as $cid) {
                    $this->update_google_product_title($sku, 'en', $cid, $google_cat_id = null, $google_product_name);
                }
            }
        }

        Redirect(base_url() . "marketing/product/view/" . $sku . "/" . $lang_id);
    }

    public function translate_product_enhance_content($sku = "", $lang_id = "en")
    {
        $lang_id_list = explode(',', $lang_id);
        foreach ($lang_id_list as $lang_id) {
            $this->product_model->product_service->translate_product_enhance_content($sku, $lang_id, $translated_product_name);
        }

        Redirect(base_url() . "marketing/product/view/" . $sku . "/" . $lang_id);
    }

    public function view($sku = "", $lang_id = "en", $gen_keywords = array())
    {
        //  var_dump($_POST);die();
        if ($sku == "") {
            show_404();
        }


        $sub_app_id = $this->getAppId() . "02";

        $ar_feed = array("FROOGLE", "KELKOO", "PRICERUNNER", "PRICEGRABBER", "PRICEMINISTER");


        $data["google_feed_arr"] = $this->google_feed_arr;

        define('IMG_PH', $this->context_config_service->value_of("prod_img_path"));
        define('PROD_BANNER_PH', $this->context_config_service->value_of("prod_banner_path"));
        $product_content_dao = $this->product_service->get_pc_dao();

        $img_size = array("l", "m", "s");
        if ($this->input->post('posted')) {
            unset($_SESSION["NOTICE"]);
            if (isset($_SESSION["product_obj"][$sku])) {

                $this->product_model->include_vo("product");
                $this->product_model->product_service->get_sku_map_dao()->include_vo("sku_mapping");

                $data["product"] = unserialize($_SESSION["product_obj"][$sku]);
                $data["master_sku"] = unserialize($_SESSION["master_sku"][$sku]);
                if ($data["product"]->get_name() != $_POST["name"]) {
                    $proc = $this->product_model->get("product", array("name" => $name));
                    if (!empty($proc)) {
                        $_SESSION["NOTICE"] = "product_existed";
                    }
                }
                if ($_POST['master_sku']) {
                    $_POST['master_sku'] = strtoupper($_POST['master_sku']);
                    if ($sku_mapping_obj_list = $this->product_model->product_service->get_sku_map_dao()->get_list(array("sku" => $sku, "ext_sys" => "WMS"))) {
                        $insert_master_sku = 1;
                        foreach ($sku_mapping_obj_list as $map_obj) {
                            if ($map_obj->get_ext_sku() == $_POST["master_sku"]) {
                                if ($map_obj->get_status() == 0) {
                                    $map_obj->set_status(1);
                                    $this->product_model->product_service->get_sku_map_dao()->update($map_obj);
                                }
                                $insert_master_sku = 0;
                            } else {
                                $map_obj->set_status(0);
                                $this->product_model->product_service->get_sku_map_dao()->update($map_obj);
                                mail('chapman@eservicesgroup.com,christy.yeung@eservicesgroup.com,alice.fu@eservicesgroup.com,nicolove.ni@eservicesgroup.com', '[VB] SKU - ' . $sku . ' is mapped with another Master SKU.', 'Please noted,' . $sku . ' is mapped with another Master SKU.', "From: admin@valuebasket.com\r\n");
                            }

                        }
                        if ($insert_master_sku) {
                            $data["master_sku"]->set_ext_sku(trim($_POST["master_sku"]));
                            $data["master_sku"]->set_ext_sys("WMS");
                            $data["master_sku"]->set_sku($sku);
                            $data["master_sku"]->set_status(1);
                            $this->product_model->product_service->get_sku_map_dao()->insert($data["master_sku"]);
                            file_get_contents(base_url() . "cps/webapi.pullskuprice.php?sku=" . trim($_POST["master_sku"]));
                        }
                    }
                }

                $response_psd_list = $this->input->post('ps');

                $sub_cat_id = $this->input->post('sub_cat_id');
                $sku = $this->input->post('sku');
                $this->product_model->update_response_psd_list($sku, $sub_cat_id, $response_psd_list);

                if ($this->input->post('populate')) {
                    foreach ($this->input->post('populate') AS $ps_id => $lang_id) {
                        $this->product_model->populate_to_all_lang($ps_id, $sub_cat_id, $sku, $lang_id);
                    }
                }
                if (empty($_SESSION["NOTICE"])) {
                    if ($_POST["ex_demo"] == "") {
                        $_POST["ex_demo"] = 0;
                    }
                    if ($_POST["china_oem"] == "") {
                        $_POST["china_oem"] = 0;
                    }
                    if ($_POST["clearance"] == "") {
                        $_POST["clearance"] = 0;
                    }

                    if ($_POST['accelerator_salesrpt_bd'] == "") {
                        $_POST['accelerator_salesrpt_bd'] = 0;
                    }


                    $update_bundle = 0;

                    if ($_POST["website_status"] == "O" && $data["product"]->get_website_status() != "O") {
                        $update_bundle = 1;
                    }

                    if ($_POST['remove_flash']) {
                        $_POST['flash'] = '';
                    }

                    if ($_POST["cat_upselling"] == "") {
                        $_POST["cat_upselling"] = 0;
                    }

                    if (isset($_POST["lang_restricted"])) {
                        $lang_osd = 0;
                        foreach ($_POST["lang_restricted"] as $selectedLang) {
                            $lang_osd = $lang_osd | (1 << $selectedLang);
                        }
                        $_POST["lang_restricted"] = $lang_osd;
                    } else
                        $_POST["lang_restricted"] = 0;
                    $alert_website_status = array($_POST["before_update_website_status"], $_POST["website_status"]);
                    $alert_expected_delivery_date = array($_POST["before_expected_delivery_date"], $_POST["expected_delivery_date"]);
                    if ($_POST["before_update_website_status"] != $_POST["website_status"]) {
//send email
                        if ($_POST["before_update_website_status"] != "")
                            $this->product_model->product_service->alert_user(Product_service::PRODUCT_STATUS_CHANGED, $sku, $alert_website_status, $alert_expected_delivery_date);
                    }
                    if ($_POST["before_expected_delivery_date"] != $_POST["expected_delivery_date"]) {
                        if ($_POST["before_expected_delivery_date"] != "") {
                            if (($_POST["website_status"] == "P") || ($_POST["website_status"] == "A"))
                                $this->product_model->product_service->alert_user(Product_service::PRODUCT_EXPECT_DELIVERY_CHANGED, $sku, $alert_website_status, $alert_expected_delivery_date);
                        }
                    }
                    unset($_POST["before_update_website_status"]);
                    unset($_POST["before_expected_delivery_date"]);
                    set_value_vo($data["product"], $_POST);

                    $config['upload_path'] = IMG_PH;
                    $config['allowed_types'] = 'gif|jpg|jpeg|png';
                    $config['file_name'] = $sku;
                    $config['overwrite'] = TRUE;
                    $config['is_image'] = TRUE;
                    $this->load->library('upload', $config);

                    if (!empty($_FILES["image_file"]["name"])) {
                        @unlink(IMG_PH . $sku . "." . $data["product"]->get_image());
                        if ($this->upload->do_upload("image_file")) {
                            $res = $this->upload->data();
                            $ext = substr($res["file_ext"], 1);
                            $data["product"]->set_image($ext);
                            list($width, $height) = explode("x", $this->context_config_service->value_of("thumb_w_x_h"));
                            $outputfilename = IMG_PH . $sku . "." . $ext;
                            thumbnail(IMG_PH . $sku . "." . $ext, $width, $height, $outputfilename);
                            $url = $outputfilename;
                            if (!cdn_purge($url)) $a = 1; #$_SESSION["NOTICE"] = "cdn_purge failed1";

                            //watermark(IMG_PH.$sku.".".$ext, "images/watermark.png", "B", "R", "", "#000000");
                            foreach ($img_size as $size) {
                                list($width, $height) = explode("x", $this->context_config_service->value_of("thumb_{$size}_w_x_h"));
                                $outputfilename = IMG_PH . $sku . "_{$size}." . $ext;
                                thumbnail(IMG_PH . $sku . "." . $ext, $width, $height, $outputfilename);
                                $url = $outputfilename;
                                if (!cdn_purge($url)) $a = 1; #$_SESSION["NOTICE"] = "cdn_purge failed2";
                            }
                        } else {
                            $_SESSION["NOTICE"] = $this->upload->display_errors();;
                        }
                    }

                    if (!empty($_FILES["flash_file"]["name"])) {
                        $config['allowed_types'] = 'swf';
                        $config['is_image'] = FALSE;
                        $config['max_size'] = '1024';
                        $this->upload->initialize($config);
                        if ($this->upload->do_upload("flash_file")) {
                            $res = $this->upload->data();
                            $ext = substr($res["file_ext"], 1);
                            $data["product"]->set_flash($ext);
                        } else {
                            $_SESSION["NOTICE"] = $this->upload->display_errors();
                        }
                    }

                    //var_dump($data["product"]);exit;
                    if ($this->product_model->update("product", $data["product"])) {
                        // SBF 4402 warranty for different countries
                        // for receiving the changes of list

                        $warranty_country_counter = 0;
                        while ($this->input->post('warranty_country_' . $warranty_country_counter)) {
                            //var_dump($this->input->post('warranty_country_'.$warranty_country_counter));
                            $sku = $this->input->post('sku');

                            $warranty_platform_id = $this->input->post('warranty_country_' . $warranty_country_counter);
                            $warranty_in_month = $this->input->post('warranty_in_month_' . $warranty_country_counter);

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
                                    //var_dump($this->db->last_query());die();
                                }
                            }
                            $warranty_country_counter++;
                        }


                        //if ($warranty_country_counter) die();

                        //update HS code
                        $pccmap = array(
                            array('country' => 'AU', 'code' => $this->input->post('hscode_AU')),
                            array('country' => 'BE', 'code' => $this->input->post('hscode_BE')),
                            array('country' => 'CH', 'code' => $this->input->post('hscode_CH')),
                            array('country' => 'ES', 'code' => $this->input->post('hscode_ES')),
                            array('country' => 'FI', 'code' => $this->input->post('hscode_FI')),
                            array('country' => 'FR', 'code' => $this->input->post('hscode_FR')),
                            array('country' => 'GB', 'code' => $this->input->post('hscode_GB')),
                            array('country' => 'HK', 'code' => $this->input->post('hscode_HK')),
                            array('country' => 'ID', 'code' => $this->input->post('hscode_ID')),
                            array('country' => 'IE', 'code' => $this->input->post('hscode_IE')),
                            array('country' => 'IT', 'code' => $this->input->post('hscode_IT')),
                            array('country' => 'MT', 'code' => $this->input->post('hscode_MT')),
                            array('country' => 'MY', 'code' => $this->input->post('hscode_MY')),
                            array('country' => 'NZ', 'code' => $this->input->post('hscode_NZ')),
                            array('country' => 'PH', 'code' => $this->input->post('hscode_PH')),
                            array('country' => 'PL', 'code' => $this->input->post('hscode_PL')),
                            array('country' => 'PT', 'code' => $this->input->post('hscode_PT')),
                            array('country' => 'RU', 'code' => $this->input->post('hscode_RU')),
                            array('country' => 'SG', 'code' => $this->input->post('hscode_SG')),
                            array('country' => 'TH', 'code' => $this->input->post('hscode_TH')),
                            array('country' => 'US', 'code' => $this->input->post('hscode_US'))
                        );
                        $cccount = count($pccmap);

                        //var_dump($pccmap); die();

                        for ($i = 0; $i < $cccount; $i++) {
                            $cc_obj = $this->custom_class_model->get_cc(array('country_id' => $pccmap[$i]['country'], 'code' => $pccmap[$i]['code']));
                            //var_dump($this->db->last_query()); die();

                            if ($cc_obj) {
                                $pcc_obj = $this->custom_class_model->get_pcc(array('sku' => $sku, 'country_id' => $pccmap[$i]['country']));
                                //var_dump($pcc_obj); die();
                                $pcc_dao = $this->custom_class_model->product_custom_classification_service->get_dao();
                                $pcc_vo = $pcc_dao->get();
                                //if no record we add new record
                                if (!$pcc_obj) {
                                    $action = "add_pcc";
                                    $pcc_obj = clone($pcc_vo);
                                    $pcc_obj->set_sku($sku);
                                    $pcc_obj->set_country_id($pccmap[$i]['country']);
                                    $pcc_obj->set_code($pccmap[$i]['code']);
                                    $pcc_obj->set_description($cc_obj->get_description());
                                    $pcc_obj->set_duty_pcent($cc_obj->get_duty_pcent());
                                } else {
                                    // if record found then we update
                                    $action = "update_pcc";
                                    $pcc_obj->set_code($pccmap[$i]['code']);
                                    $pcc_obj->set_description($cc_obj->get_description());
                                    $pcc_obj->set_duty_pcent($cc_obj->get_duty_pcent());
                                }

                                if ($this->custom_class_model->$action($pcc_obj) === FALSE) {
                                    $error_message = __LINE__ . "category.php " . $action . " Error. " . $pcc_dao->db->_error_message();
                                    $_SESSION["NOTICE"] = $error_message;
                                }
                            }
                        }


                        if (isset($_SESSION["prod_cont_vo"][$sku])) {
                            $this->product_model->include_pc_vo();
                            $data["prod_cont"] = unserialize($_SESSION["prod_cont_vo"][$sku]);

                            set_value_vo($data["prod_cont"], $_POST);

                            $stop_sync_pc = $this->sc['ProductApi']->stopSyncArrToBit($_POST['stop_sync_pc']);
                            $data["prod_cont"]->set_stop_sync($stop_sync_pc);

                            $str = explode("\n", trim($this->input->post('keywords')));

                            if ($this->product_model->get_product_keyword(array("sku" => $sku, "lang_id" => $_POST['lang_id']))) {
                                $this->product_model->del_product_keyword(array("sku" => $sku, "lang_id" => $_POST['lang_id']));
                            }

                            foreach ($str as $k => $v) {
                                if ($v != "") {
                                    if (preg_match("/^-{1,}/", $v)) {
                                        $v = trim(preg_replace('/^-{1,}/', "\\1", trim($v)));
                                    }

                                    if ($v != "") {
                                        $prod_key_obj = $this->product_model->get_product_keyword();
                                        $prod_key_obj->set_sku($sku);
                                        $prod_key_obj->set_lang_id($_POST['lang_id']);
                                        $prod_key_obj->set_keyword(trim($v));
                                        $prod_key_obj->set_type(1);

                                        if (($this->product_model->add_product_keyword($prod_key_obj)) === FALSE) {
                                            $_SESSION["NOTICE"] = __FILE__ . ":" . __LINE__ . ", " . $this->db->_error_message();
                                        }
                                    }
                                }
                            }

                            $add_ret = $this->add_adgroup_keywords($sku, $this->input->post("cat_id"), $_POST['lang_id'], $str);

                            if ($add_ret["status"] === FALSE) {
                                $_SESSION["NOTICE"] = __FILE__ . ":" . __LINE__ . ", Error updating Google adwords - " . $add_ret["error_msg"];
                            }

                            if ($this->input->post("prod_type")) {
                                if ($this->product_model->get_product_type(array("sku" => $sku))) {
                                    $this->product_model->del_product_type(array("sku" => $sku));
                                }
                                foreach ($this->input->post("prod_type") as $prod_type) {
                                    $prod_type_obj = $this->product_model->get_product_type();
                                    $prod_type_obj->set_sku($sku);
                                    $prod_type_obj->set_type_id($prod_type);
                                    $prod_type_obj->set_status(1);
                                    if (!$this->product_model->add_product_type($prod_type_obj)) {
                                        $_SESSION["NOTICE"] = __FILE__ . ":" . __LINE__ . ", " . $this->db->_error_message();
                                    }
                                }
                            } else {
                                $this->product_model->del_product_type(array("sku" => $sku));
                            }

                            $data["prod_cont"]->set_prod_sku($sku);
                            $data["prod_cont"]->set_lang_id($_POST['lang_id']);

                            if ($this->product_model->get_product_content(array("prod_sku" => $sku, "lang_id" => $_POST['lang_id']))) {
                                $this->product_model->del_product_content(array("prod_sku" => $sku, "lang_id" => $_POST['lang_id']));
                            }
                            if (!$this->product_model->add_product_content($data["prod_cont"])) {
                                $_SESSION["NOTICE"] = __LINE__ . " " . $this->db->_error_message();
                            }
                        }

                        if (isset($_SESSION["prod_cont_ext_vo"][$sku])) {
                            $this->product_model->include_pcext_vo();
                            $data["prod_cont_ext"] = unserialize($_SESSION["prod_cont_ext_vo"][$sku]);

                            set_value_vo($data["prod_cont_ext"], $_POST);

                            $stop_sync_pce = $this->sc['ProductApi']->stopSyncArrToBit($_POST['stop_sync_pce']);
                            $data["prod_cont_ext"]->set_stop_sync($stop_sync_pce);

                            $data["prod_cont_ext"]->set_prod_sku($sku);
                            $data["prod_cont_ext"]->set_lang_id($_POST['lang_id']);

                            if ($this->product_model->get_product_content_extend(array("prod_sku" => $sku, "lang_id" => $_POST['lang_id']))) {
                                $this->product_model->del_product_content_extend(array("prod_sku" => $sku, "lang_id" => $_POST['lang_id']));
                            }
                            if (!$this->product_model->add_product_content_extend($data["prod_cont_ext"])) {
                                $_SESSION["NOTICE"] = __LINE__ . " " . $this->db->_error_message();
                            }
                        }

                        if ($update_bundle) {
                            foreach ($this->product_model->product_service->get_dao()->get_prod_by_component(array("component_sku" => $sku)) as $bundle_obj) {
                                $bundle_obj->set_website_status("O");
                                if (!$this->product_model->update("product", $bundle_obj)) {
                                    $_SESSION["NOTICE"] = __LINE__ . " " . $this->db->_error_message();
                                }
                            }
                        }

                        $prod_feed_vo = $this->product_feed_service->get();
                        $data["prod_feed"] = $_POST["prod_feed"];
                        $this->product_model->product_service->get_dao()->trans_start();
                        if ($this->product_feed_service->q_delete(array("sku" => $sku)) !== FALSE) {
                            for ($i = 0; $i < count($ar_feed); $i++) {
                                $feed = $ar_feed[$i];
                                $data["prod_feed"][$feed]["feeder"] = $feed;
                                $prod_feed_obj = clone $prod_feed_vo;
                                $data["prod_feed"][$feed]["sku"] = $sku;
                                set_value_vo($prod_feed_obj, $data["prod_feed"][$feed]);
                                $prod_feed_obj->set_status($_POST["prod_feed"][$feed]["status"] ? 1 : 0);
                                if (($prod_feed_obj->get_status() || ($prod_feed_obj->get_status() == 0 && $_POST["prod_feed"][$feed]["value_1"] != "")) && $this->product_feed_service->insert($prod_feed_obj) === FALSE) {
                                    $_SESSION["NOTICE"] = __LINE__ . " " . $this->db->_error_message();
                                    $this->product_model->product_service->get_dao()->trans_rollback();
                                    break;
                                }
                            }
                        }
                        $this->product_model->product_service->get_dao()->trans_complete();

                        // $this->product_update_followup_service->google_shopping_update($sku);
                        unset($_SESSION["product_obj"]);
                        unset($_SESSION["prod_cont_vo"]);
                        unset($_SESSION["prod_cont_ext_vo"]);
                    } else {
                        $_SESSION["NOTICE"] = __LINE__ . " " . $this->db->_error_message();
                    }

                    if ($this->input->post('series')) {
                        $update_series = $this->product_service->update_series($sku, $this->input->post('series'));

                        if (!($update_series === TRUE)) {
                            $_SESSION["NOTICE"] = $update_sreies; # error msg if update fail
                        }
                    }


                    // web image
                    $prod_image = $this->product_model->get_prod_image_list(array("sku" => $sku), array("orderby" => "status DESC, priority ASC, create_on ASC"));
                    $prod_image = (array)$prod_image;

                    for ($i = 0; $i < 5; $i++) {
                        if (!empty($_FILES["image_file" . $i]["name"])) {
                            // do upload for web image
                            $image_id = $prod_image[$i] ? $prod_image[$i]->get_id() : $this->product_model->pi_seq_next_val();

                            $config['upload_path'] = IMG_PH;
                            $config['allowed_types'] = 'gif|jpg|jpeg|png';
                            $config['file_name'] = $sku . "_" . $image_id;
                            $config['overwrite'] = TRUE;
                            $config['is_image'] = TRUE;
                            $this->upload->initialize($config);

                            if (!empty($_FILES["image_file" . $i])) {
                                $f_ext = substr($_FILES["image_file" . $i]["name"], strrpos($fileName, '.') + 1);
                                @unlink(IMG_PH . $sku . "_" . $image_id . "." . $f_ext);
                                if ($this->upload->do_upload("image_file" . $i)) {
                                    $res = $this->upload->data();
                                    $ext = substr($res["file_ext"], 1);

                                    // first row will update primary image
                                    // image with lowest priority will become first row
                                    if ($i == 0) {
                                        if (!copy(IMG_PH . $sku . "_" . $image_id . "." . $ext, IMG_PH . $sku . "." . $ext)) {
                                            $_SESSION["NOTICE"] = __LINE__ . "Error: fail to copy file\n";
                                        } else {
                                            list($width, $height) = explode("x", $this->context_config_service->value_of("thumb_w_x_h"));
                                            $outputfilename = IMG_PH . $sku . "." . $ext;
                                            thumbnail(IMG_PH . $sku . "." . $ext, $width, $height, $outputfilename);
                                            $url = $outputfilename;
                                            if (!cdn_purge($url)) $a = 1; #$_SESSION["NOTICE"] = "cdn_purge failed3";

                                            //watermark(IMG_PH.$sku.".".$ext, "images/watermark.png", "B", "R", "", "#000000");
                                            foreach ($img_size as $size) {
                                                list($width, $height) = explode("x", $this->context_config_service->value_of("thumb_{$size}_w_x_h"));
                                                $outputfilename = IMG_PH . $sku . "_{$size}." . $ext;
                                                thumbnail(IMG_PH . $sku . "." . $ext, $width, $height, $outputfilename);
                                                $url = $outputfilename;
                                                if (!cdn_purge($url)) $a = 1; #$_SESSION["NOTICE"] = "cdn_purge failed4";
                                            }

                                            $data["product"]->set_image($ext);
                                            $this->product_model->update("product", $data["product"]);
                                        }
                                    }

                                    if (!$_SESSION["NOTICE"]) {
                                        list($width, $height) = explode("x", $this->context_config_service->value_of("thumb_w_x_h"));
                                        $outputfilename = IMG_PH . $sku . "_" . $image_id . "." . $ext;
                                        thumbnail(IMG_PH . $sku . "_" . $image_id . "." . $ext, $width, $height, $outputfilename);
                                        //watermark(IMG_PH.$sku."_".$image_id.".".$ext, "images/watermark.png", "B", "R", "", "#000000");
                                        $url = $outputfilename;
                                        if (!cdn_purge($url)) $a = 1; #$_SESSION["NOTICE"] = "cdn_purge failed5";

                                        foreach ($img_size as $size) {
                                            list($width, $height) = explode("x", $this->context_config_service->value_of("thumb_{$size}_w_x_h"));
                                            $outputfilename = IMG_PH . $sku . "_" . $image_id . "_{$size}." . $ext;
                                            thumbnail(IMG_PH . $sku . "_" . $image_id . "." . $ext, $width, $height, $outputfilename);
                                            $url = $outputfilename;
                                            if (!cdn_purge($url)) $a = 1; #$_SESSION["NOTICE"] = "cdn_purge failed6";
                                        }
                                    }
                                } else {
                                    $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $this->upload->display_errors();
                                }
                            }
                        }

                        if (!$_SESSION["NOTICE"]) {
                            if (!empty($_FILES["image_file" . $i]["name"])) {
                                $this->product_model->product_service->get_pi_dao()->trans_start();
                                if (empty($prod_image[$i])) {
                                    $prod_image[$i] = $this->product_model->get_prod_image();

                                    $f_ext = strrchr($_FILES["image_file" . $i]["name"], '.');
                                    $prod_image[$i]->set_image(substr($f_ext, 1));
                                    $alt_txt = trim($this->input->post("image_alt_text_" . $i));
                                    if ($alt_txt == NULL) {
                                        $alt_txt = $sku . "_" . $image_id . "." . $prod_image[$i]->get_image();
                                    }
                                    $prod_image[$i]->set_alt_text($alt_txt);
                                    $prod_image[$i]->set_id($image_id);
                                    $prod_image[$i]->set_sku($sku);
                                    $prod_image[$i]->set_status("1");
                                    $p_list = $this->input->post("priority");
                                    $priority = $p_list[$i] ? $p_list[$i] : 9;
                                    $prod_image[$i]->set_priority($priority);

                                    $s_stop_sync = $this->input->post("im_stop_sync");
                                    $image_stop_sync = $s_stop_sync[$i] ? '1' : '0';
                                    $prod_image[$i]->set_stop_sync_image($image_stop_sync);

                                    if (!$this->product_model->add_product_image($prod_image[$i])) {
                                        $this->product_model->product_service->get_pi_dao()->trans_rollback();
                                        $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $this->product_model->product_service->get_pi_dao()->db->_error_message();
                                    } else {
                                        $this->product_model->product_service->get_pi_dao()->update_seq($image_id);
                                    }
                                } else {
                                    $f_ext = strrchr($_FILES["image_file" . $i]["name"], '.');
                                    $prod_image[$i]->set_image(substr($f_ext, 1));
                                    $alt_txt = trim($this->input->post("image_alt_text_" . $i));
                                    if ($alt_txt == NULL) {
                                        $alt_txt = $sku . "_" . $image_id . "." . substr($f_ext, 1);
                                    }
                                    $prod_image[$i]->set_alt_text($alt_txt);
                                    $prod_image[$i]->set_status("1");
                                    $p_list = $this->input->post("priority");
                                    $priority = $p_list[$i] ? $p_list[$i] : 9;
                                    $prod_image[$i]->set_priority($priority);

                                    $s_stop_sync = $this->input->post("im_stop_sync");
                                    $image_stop_sync = $s_stop_sync[$i] ? '1' : '0';
                                    $prod_image[$i]->set_stop_sync_image($image_stop_sync);

                                    if (!$this->product_model->update_product_image($prod_image[$i])) {
                                        $this->product_model->product_service->get_pi_dao()->trans_rollback();
                                        $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $this->db->_error_message();
                                    }
                                }
                                $this->product_model->product_service->get_pi_dao()->trans_complete();
                            } else {
                                if ($prod_image[$i] != NULL) {
                                    $s_list = $this->input->post("im_status");
                                    $image_status = $s_list[$i] ? '1' : '0';
                                    $prod_image[$i]->set_status($image_status);

                                    $s_stop_sync = $this->input->post("im_stop_sync");
                                    $image_stop_sync = $s_stop_sync[$i] ? '1' : '0';
                                    $prod_image[$i]->set_stop_sync_image($image_stop_sync);
                                    $a_list = $this->input->post("image_alt_text");
                                    $alt_txt = trim($a_list[$i]);
                                    $prod_image[$i]->set_alt_text($alt_txt);
                                    $p_list = $this->input->post("priority");
                                    $priority = $p_list[$i] ? $p_list[$i] : 9;
                                    $prod_image[$i]->set_priority($priority);

                                    // SBF #2527 check and see if primary image needs to be updated
                                    if ($_POST["update_image_file"] == 1) {
                                        $update_flag = TRUE;
                                    }

                                    if (!$this->product_model->update_product_image($prod_image[$i])) {
                                        $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $this->db->_error_message();
                                    }
                                }
                            }
                        }
                    }

                    if ($update_flag == TRUE) {
                        $update_image = $this->product_model->get_prod_image_list(array("sku" => $sku), array("limit" => 1, "orderby" => "status DESC, priority ASC, create_on ASC"));

                        if (!copy(IMG_PH . $sku . "_" . $update_image->get_id() . "." . $update_image->get_image(), IMG_PH . $sku . "." . $update_image->get_image())) {
                            $_SESSION["NOTICE"] = __LINE__ . "Error: fail to copy file\n";
                        } else {
                            list($width, $height) = explode("x", $this->context_config_service->value_of("thumb_w_x_h"));
                            $outputfilename = IMG_PH . $sku . "." . $update_image->get_image();
                            thumbnail(IMG_PH . $sku . "." . $update_image->get_image(), $width, $height, $outputfilename);
                            //watermark(IMG_PH.$sku.".".$update_image->get_image(), "images/watermark.png", "B", "R", "", "#000000");
                            $url = $outputfilename;
                            if (!cdn_purge($url)) $a = 1; #$_SESSION["NOTICE"] = "cdn_purge failed7";

                            foreach ($img_size as $size) {
                                list($width, $height) = explode("x", $this->context_config_service->value_of("thumb_{$size}_w_x_h"));
                                $outputfilename = IMG_PH . $sku . "_{$size}." . $update_image->get_image();
                                thumbnail(IMG_PH . $sku . "." . $update_image->get_image(), $width, $height, $outputfilename);
                                $url = $outputfilename;
                                if (!cdn_purge($url)) $a = 1; #$_SESSION["NOTICE"] = "cdn_purge failed8";
                            }
                            $data["product"]->set_image($update_image->get_image());
                            $this->product_model->update("product", $data["product"]);
                        }
                    }

                    // $this->update_banner_image($sku, $_POST);
                }

                $country_list = $this->product_model->get_list("country", array("status" => 1, "allow_sell" => 1), array("orderby" => "name ASC"));

                #SBF #2871 include product banner on mainproduct page
                foreach ($country_list as $country_obj) {
                    $country_id = ($country_obj->get_id()); // e.g. AU, US, UK
                    $prod_banner[$country_id] = $this->product_model->get_prod_banner(array("sku" => $sku, "country_id" => $country_id));

                    if (!empty($_FILES["prod_banner_$country_id"]["name"])) {
                        // do upload for product banner image
                        $banner_seq_no = $prod_banner[$country_id] ? $prod_banner[$country_id]->get_id() : $this->product_model->pb_seq_next_val();
                        $config['upload_path'] = PROD_BANNER_PH;
                        $config['allowed_types'] = 'gif|jpg|jpeg|png';
                        $config['file_name'] = $sku . "_" . $country_id;
                        $config['exact_width'] = 920;
                        $config['max_height'] = 156;
                        $config['overwrite'] = TRUE;
                        $config['is_image'] = TRUE;
                        $this->upload->initialize($config);

                        if (!empty($_FILES["prod_banner_$country_id"])) {
                            if ($this->upload->do_upload("prod_banner_$country_id")) {
                                $pb_res = $this->upload->data();
                                $pb_ext = substr($pb_res["file_ext"], 1);

                                if (!$_SESSION["NOTICE"]) {
                                    $pb_outputfilename = PROD_BANNER_PH . $sku . "_" . $country_id . "." . $pb_ext;
                                    $pb_url = $pb_outputfilename;
                                    if (!cdn_purge($pb_url)) $a = 1; #$_SESSION["NOTICE"] = "cdn_purge failed5";
                                }
                            } else {
                                $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $this->upload->display_errors();
                            }
                        }
                    }

                    if (!$_SESSION["NOTICE"]) {
                        if (!empty($_FILES["prod_banner_$country_id"]["name"])) {
                            $this->product_model->product_service->get_pb_dao()->trans_start();
                            if (empty($prod_banner[$country_id])) {
                                $prod_banner[$country_id] = $this->product_model->get_prod_banner();

                                $pb_file_ext = strrchr($_FILES["prod_banner_$country_id"]["name"], '.');
                                $prod_banner[$country_id]->set_image(substr($pb_file_ext, 1));
                                $alt_txt = trim($this->input->post("banner_alt_text_$country_id"));
                                if ($alt_txt == NULL) {
                                    $alt_txt = $sku . "_" . $country_id . "." . $prod_banner[$country_id]->get_image();
                                }
                                $prod_banner[$country_id]->set_alt_text($alt_txt);
                                $target_url = $this->input->post("banner_target_url_$country_id");
                                $prod_banner[$country_id]->set_target_url($target_url);
                                $target_type = $this->input->post("banner_target_type_$country_id");
                                if ($target_type == NULL) {
                                    $target_type = "E";
                                }
                                $prod_banner[$country_id]->set_target_type($target_type);
                                $prod_banner[$country_id]->set_sku($sku);
                                $status = $this->input->post("banner_status_$country_id");
                                $prod_banner[$country_id]->set_status($status);
                                $prod_banner[$country_id]->set_country_id($country_obj->get_id());

                                if (!$this->product_model->add_product_banner($prod_banner[$country_id])) {
                                    $this->product_model->product_service->get_pb_dao()->trans_rollback();
                                    $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $this->db->_error_message();
                                } else {
                                    $this->product_model->product_service->get_pb_dao()->update_seq($banner_seq_no);
                                }
                            } else {
                                $pb_file_ext = strrchr($_FILES["prod_banner_" . $country_id]["name"], '.');
                                $prod_banner[$country_id]->set_image(substr($pb_file_ext, 1));
                                $alt_txt = trim($this->input->post("banner_alt_text_" . $country_id));
                                if ($alt_txt == NULL) {
                                    $alt_txt = $sku . "_" . $banner_seq_no . "." . substr($pb_file_ext, 1);
                                }
                                $prod_banner[$country_id]->set_alt_text($alt_txt);
                                $target_url = $this->input->post("banner_target_url_$country_id");
                                $prod_banner[$country_id]->set_target_url($target_url);
                                $target_type = $this->input->post("banner_target_type_$country_id");
                                if ($target_type == NULL) {
                                    $target_type = "E";
                                }
                                $prod_banner[$country_id]->set_target_type($target_type);
                                $prod_banner[$country_id]->set_status(1);
                                $prod_banner[$country_id]->set_sku($sku);
                                $prod_banner[$country_id]->set_country_id($country_obj->get_id());

                                if (!$this->product_model->update_product_banner($prod_banner[$country_id])) {
                                    $this->product_model->product_service->get_pb_dao()->trans_rollback();
                                    $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $this->db->_error_message();
                                }
                            }
                            $this->product_model->product_service->get_pb_dao()->trans_complete();
                        } else {
                            # if no banner uploaded for the country, update other info
                            if (!empty($prod_banner[$country_id])) {
                                $prod_banner_status = $this->input->post("banner_status_$country_id");
                                $prod_banner[$country_id]->set_status($prod_banner_status);

                                $prod_banner_alt_text = $this->input->post("banner_alt_text_$country_id");
                                $prod_banner[$country_id]->set_alt_text($prod_banner_alt_text);

                                $target_url = $this->input->post("banner_target_url_$country_id");
                                $prod_banner[$country_id]->set_target_url($target_url);

                                $target_type = $this->input->post("banner_target_type_$country_id");
                                if ($target_type == NULL) {
                                    $target_type = "E";
                                }
                                $prod_banner[$country_id]->set_target_type($target_type);

                                if (!$this->product_model->update_product_banner($prod_banner[$country_id])) {
                                    $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $this->db->_error_message();
                                }
                            }
                        }
                    }

                }

                foreach ($this->google_feed_arr as $cid) {
                    {
                        $google_cat_id = null;
                        $platform_biz_var_obj = $this->sc['PlatformBizVar']->getDao('PlatformBizVar')->get(array("platform_country_id" => $cid));
                        if ($platform_biz_var_obj)
                        {
                            $lang_id_temp = $platform_biz_var_obj->getLanguageId();
                            $google_product_name = $this->input->post("google_product_name_{$cid}");
                        }
                    }
                }


                redirect(base_url() . "marketing/product/view/" . $sku . "/" . $lang_id);
            }
        }

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;

        $data["warranty_list"] = $this->product_model->get_valid_warrant_period_list();
        //$data["existing_product_warranty"] = $this->warranty_model->get_country_warranty_list();

        if (empty($data["product"])) {
            if (($data["product"] = $this->product_model->product_service->get_dao()->get_prod_wo_bundle(array("sku" => $sku), array("limit" => 1))) === FALSE) {
                $_SESSION["NOTICE"] = __FILE__ . ":" . __LINE__ . ", " . $this->db->_error_message();
            } elseif (!$data["product"]) {
                show_404();
            } else {
                unset($_SESSION["product_obj"]);
                $_SESSION["product_obj"][$sku] = serialize($data["product"]);
            }
        }
        $sub_cat_id = $data["product"]->get_sub_cat_id();
        if ($sub_cat_id) {
            $lang_list = $this->product_model->get_list("language", array("status" => 1), array("orderby" => "lang_name ASC"));
            foreach ($lang_list AS $lang_obj) {
                $temp_arr = $this->product_model->get_full_psd_w_lang($sub_cat_id, $sku, $lang_obj->get_lang_id());
                foreach ($temp_arr AS $obj) {
                    $psd_list[$lang_obj->get_lang_id()][$obj->get_psg_name()][$obj->get_ps_name()] = $obj;
                }
            }
            $data["psd_list"] = $psd_list;
            $data["sub_cat_id"] = $sub_cat_id;
        }

        if (empty($data["master_sku"])) {
            if (($data["master_sku"] = $this->product_model->get_prod_master_sku(array("sku" => $sku, "ext_sys" => "WMS", "status" => 1))) === FALSE) {
                $_SESSION["NOTICE"] = __FILE__ . ":" . __LINE__ . ", " . $this->db->_error_message();
            } else {
                if (empty($data["master_sku"])) {
                    $data["master_sku"] = $this->product_model->product_service->get_sku_map_dao()->get();
                } elseif (check_app_feature_access_right($this->getAppId(), 'MKT000302_edit_master_sku')) {
                    $data["allow_edit_master_sku"] = 1;
                    $data["lock_master_sku"] = 1;
                } else {
                    $data["allow_edit_master_sku"] = 0;
                    $data["lock_master_sku"] = 1;
                }
                unset($_SESSION["master_sku"]);
                $_SESSION["master_sku"][$sku] = serialize($data["master_sku"]);
            }
        }

        if (empty($data["prod_cont"])) {
            if (($data["prod_cont"] = $this->product_model->get_product_content(array("prod_sku" => $sku, "lang_id" => $lang_id))) === FALSE) {
                $_SESSION["NOTICE"] = __FILE__ . ":" . __LINE__ . ", " . $this->db->_error_message();
            } else {
                if (empty($data["prod_cont"])) {
                    $data["prod_cont"] = $this->product_model->get_product_content();
                }
                unset($_SESSION["prod_cont_vo"]);
                $_SESSION["prod_cont_vo"][$sku] = serialize($data["prod_cont"]);
            }
        }

        if (empty($data["prod_cont_ext"])) {
            if (($data["prod_cont_ext"] = $this->product_model->get_product_content_extend(array("prod_sku" => $sku, "lang_id" => $lang_id))) === FALSE) {
                $_SESSION["NOTICE"] = __FILE__ . ":" . __LINE__ . ", " . $this->db->_error_message();
            } else {
                if (empty($data["prod_cont_ext"])) {
                    $data["prod_cont_ext"] = $this->product_model->get_product_content_extend();
                }
                unset($_SESSION["prod_cont_ext_vo"]);
                $_SESSION["prod_cont_ext_vo"][$sku] = serialize($data["prod_cont_ext"]);
            }
        }

        $data["prod_image"] = $this->product_model->get_prod_image_list(array("sku" => $sku), array("orderby" => "status DESC, priority ASC, create_on ASC"));
        $data["prod_image"] = (array)$data["prod_image"];

        // $data["prod_type"] = $this->product_model->get_product_type_list(array("sku" => $sku));
        $keywords = $this->product_model->get_product_keyword_list(array("sku" => $sku, "lang_id" => $lang_id));
        if ($keywords) {
            foreach ($keywords as $k => $v) {
                $keyword_arr[] = $v->get_keyword();
            }
        }
        if ($keyword_arr) {
            $data['keywords'] = implode("\n", $keyword_arr);
        }

        $data["series"] = $data["prod_cont"]->get_series();
        $data["model_1"] = $data["prod_cont"]->get_model_1();
        $data["model_2"] = $data["prod_cont"]->get_model_2();
        $data["model_3"] = $data["prod_cont"]->get_model_3();
        $data["model_4"] = $data["prod_cont"]->get_model_4();
        $data["model_5"] = $data["prod_cont"]->get_model_5();

        if ($this->input->post('gen_post')) {
            # if user input some fields, we replace database values with new input
            # after redirecting them back from keywords generation
            $lang_id = ($this->input->post('lang_id'));
            $data["series"] = $this->input->post('gen_series');
            $data["model_1"] = $this->input->post('gen_model_1');
            $data["model_2"] = $this->input->post('gen_model_2');
            $data["model_3"] = $this->input->post('gen_model_3');
            $data["model_4"] = $this->input->post('gen_model_4');
            $data["model_5"] = $this->input->post('gen_model_5');

            $attributes_arr = array(
                "brand_id" => $this->input->post('gen_brand_id'),
                "colour_id" => $this->input->post('gen_colour_id'),
                "series" => $this->input->post('gen_series'),
                "model_1" => $this->input->post('gen_model_1'),
                "model_2" => $this->input->post('gen_model_2'),
                "model_3" => $this->input->post('gen_model_3'),
                "model_4" => $this->input->post('gen_model_4'),
                "model_5" => $this->input->post('gen_model_5')
            );

            $gen_ret = $this->product_service->generate_keywords($sku, $attributes_arr, $lang_id);
            if ($gen_ret["status"] === FALSE) {
                $_SESSION["NOTICE"] = $gen_ret["error_msg"];
            } else {
                # if user clicked "Generate EN keywords", it will
                # overwrite all previous keywords and replace with newly generated keywords
                $data["keywords"] = $gen_ret["keywords"];
            }
        }

        if (empty($data["prod_cont_ext"])) {
            if (($data["prod_cont_ext"] = $this->product_model->get_product_content_extend(array("prod_sku" => $sku, "lang_id" => $lang_id))) === FALSE) {
                $_SESSION["NOTICE"] = __FILE__ . ":" . __LINE__ . ", " . $this->db->_error_message();
            } else {
                if (empty($data["prod_cont_ext"])) {
                    $data["prod_cont_ext"] = $this->product_model->get_product_content_extend();
                }
                unset($_SESSION["prod_cont_ext_vo"]);
                $_SESSION["prod_cont_ext_vo"][$sku] = serialize($data["prod_cont_ext"]);
            }
        }

        if (empty($data["prod_feed"])) {
            $data["prod_feed"] = $this->product_feed_service->get_prod_feed_list_w_feeder_key(array("sku" => $sku));
        }

        foreach ($lang_list AS $lang_obj) {
            $temp_arr = $this->product_model->get_full_psd_w_lang($sub_cat_id, $sku, $lang_obj->get_lang_id());
            foreach ($temp_arr AS $obj) {
                $psd_list[$lang_obj->get_lang_id()][$obj->get_psg_name()][$obj->get_ps_name()] = $obj;
            }


            // ====================================== not working old codes =================================
            $data["product_banner"][$lang_obj->get_lang_id()] = $this->product_model->get_prod_banner(array("sku" => $sku, "status" => 1));
            if ($data["product_banner"][$lang_obj->get_lang_id()]) {
                $image_id = $data["product_banner"][$lang_obj->get_lang_id()]->get_image_id();
                $data["prod_banner_w_graphic"][$lang_obj->get_lang_id()] = $this->product_model->get_graphic(array("id" => $image_id));
            }
        }

        $data["sku"] = $sku;
        $data["brand_list"] = $this->product_model->get_list("brand", array(), array("orderby" => "brand_name ASC", "limit" => -1));
        $data["lang_list"] = $this->product_model->get_list("language", array("status" => 1), array("orderby" => "lang_name ASC"));
        #SBF2701
        $data["lang_list_str"] = '';
        foreach ($data["lang_list"] as $v) {
            if ($v->get_lang_id() != "en")
                $data["lang_list_str"] .= $v->get_lang_id() . ",";
        }

        $data["lang_list_str"] = rtrim($data["lang_list_str"], ',');

        $data["country_list"] = $this->product_model->get_list("country", array("status" => 1, "allow_sell" => 1), array("orderby" => "name ASC"));

        #4402
        $data['selling_platform_list'] = $this->selling_platform_service->get_platform_list_w_allow_sell_country("WEBSITE");

        foreach ($data["country_list"] as $country_obj) {
            $country_id = $country_obj->get_id();
            $data["prod_banner_obj"][$country_id] = $this->product_model->get_prod_banner(array("sku" => $sku, "country_id" => $country_id));
        }

        $data["type_list"] = $this->subject_domain_service->get_subj_list_w_subj_lang("MKT.PROD_TYPE.PROD_TYPE_ID", "en");
        $data["supp_prod"] = $this->product_model->get_supplier_prod(array("prod_sku" => $sku, "order_default" => 1));
        $data["default_curr"] = $this->context_config_service->value_of("website_default_curr");
        $data["bundle_list"] = $this->product_model->get_bundle_list(array("component_sku" => $sku, "component_order" => "0"));
        $data["inventory"] = $this->product_model->get_prod_inventory(array("prod_sku" => $sku));
        $data["website_link"] = $this->context_config_service->value_of("website_domain");
        $data["notice"] = notice($lang);
        $data["cmd"] = "edit";
        $data["prod_grp_cd"] = $data["product"]->get_prod_grp_cd();
        $data["colour_id"] = $data["product"]->get_colour_id();
        $data["version_id"] = $data["product"]->get_version_id();
        $data["ar_feed"] = $ar_feed;
        $data["language_id"] = $lang_id;
        $all = '1';
        $data['optionhs'] = $this->custom_class_model->get_cc_option($all);

        $data['phs'] = $this->custom_class_model->get_full_pcc_by_sku(array("sku" => $sku), array($option = ''));
        $udesc = array();
        for ($i = 0; $i < count($data['phs']); $i++) {
            $udesc[$i]['code'] = $data['phs'][$i]['code'];
            $udesc[$i]['description'] = $data['phs'][$i]['description'];
        }
        $data['ucode'] = $this->arrayUnique($udesc);

        for ($i = 0; $i < count($data['phs']); $i++) {
            $uarr .= '"' . $data['phs'][$i]['country_id'] . '"';
            $uarr .= ', ';
        }
        $data['psarr'] = $uarr;

        $data["google_cat_w_produc_name"] = $this->product_model->get_googlebase_cat_list_w_country($sku);

        $data["edit_enhance_js"] = <<<start
            jQuery(document).on('focusin', function(e) {
                                if (jQuery(event.target).closest(".mce-window").length) {
                                    e.stopImmediatePropagation();
                                }
                            });

            function edit_enhanced(event, sku)
            {
                jQuery(document).on('focusin', function(e) {
                    if (jQuery(e.target).closest(".mce-window").length) {
                        e.stopImmediatePropagation();
                    }
                });
                //var existing = jQuery('#enhance_layout p').size();
                var existing =  tinyMCE.editors.length;

                var scntDiv = jQuery('#enhance_layout');
                var orig = jQuery('#enhanced_listing_modal').val();
                if(orig !=''){
                var cnt = orig.match(/erow/g);
                var m = cnt.length;
                var suberow = orig.split('<div id="erow">');
                //var dtitle = "Edit Enhance Information " + sku;
                }
                jQuery("#edit_enhance_modal").dialog({
                    width: 900,
                    height: 600,
                    title: "Edit Enhance Information for SKU " + sku,
                    resizable: true,
                    modal: true,
                    position: {my: "center", at: "center"},
                    //open: function(event, ui) { jQuery(".ui-dialog-titlebar-close", ui.dialog || ui).hide()},
                    buttons: {
                        "Add New Frame": function() {
                            add_enhance();
                        },
                        "Compile Codes": function() {
                            compile_enhance();
                        },
                        "Close Editor": function() {
                            // var check =  tinyMCE.editors.length;
                            // alert(check);
                            jQuery(this).dialog("close");
                        }
                    }
                });
                //var b = jQuery("#price_reference");
                jQuery("#edit_enhance_modal").dialog("open");

                if(existing != m){
                    if(m > 0){
                        //jQuery('#p0').remove();
                        //tinymce.EditorManager.execCommand('mceRemoveEditor',true, 'e_row1');
                            tinyMCE.editors.remove();
                            jQuery('#enhance_layout p').remove();
                            for (i=1; i < 20; i++){
                            tinymce.EditorManager.execCommand('mceRemoveEditor',true, 'e_row'+i);
                            }

                        // for (i=0; i < tinyMCE.editors.length; i++){
                        //     tinyMCE.editors[i].remove;
                        // }

                        for (i=1; i < m+1; i++){
                            var context = suberow[i].replace('</div>', '');
                            jQuery('<p id="p'+i+'"><br><br><table width="100%" cellspacing="0" style="background-color: #BFECB8; border: solid 1px #D56868; padding-left: 20px;"><tr><td width="200px"><font><b>Sequence:</b></font> <input type="text" name="seq[]" id="seq" value="'+ i +'" style="width:30px"><br><br><font><b>Layout:</b></font><br><select name="pagemodel" style="width:130px" onchange="change_layout(this.value, \\'e_row'+i+'\\')"><option value="1FF">1 Col - Full Focus</option><option value="2LF">2 Col - Left Focus</option><option value="2RF">2 Col - Right Focus</option><option value="3CF">3 Col - Center Focus</option><option value="TPB">2 Row - Top Bottom</option><option value="3EF">3 Col - 3 Equal Column</option><option value="4EF">4 Col - 4 Equal Column</option><option value="CX1">Composite 1 - Compare</option></select></td><td><texteditor id="e_row' + i +'">'+ context +'</texteditor></td></tr></table><br><a id="remScnt" onclick="remove_enhance('+i+')">Remove</a></p>').appendTo(scntDiv);
                             }
                            tinymce.init({selector:'texteditor',
                            theme: "modern",
                            width: 700,
                            height: 200,
                            resize: "both",
                            plugins: [
                                 "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                                 "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                                 "save table contextmenu directionality emoticons template paste textcolor jbimages"
                                 ],
                            content_css: "css/content.css",
                            toolbar: "insertfile undo redo | styleselect fontselect fontsizeselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | forecolor backcolor | link image jbimages | print preview media fullpage",
                            relative_urls: false,
                            });
                            //return false;
                            tinymce.EditorManager.execCommand('mceAddEditor',true, 'e_row'+i);


                    }else{
                        tinymce.init({selector:'texteditor',
                        theme: "modern",
                        width: 700,
                        height: 200,
                        resize: "both",
                        plugins: [
                             "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                             "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                             "save table contextmenu directionality emoticons template paste textcolor jbimages"
                             ],
                        content_css: "css/content.css",
                        toolbar: "insertfile undo redo | styleselect fontselect fontsizeselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | forecolor backcolor | link image jbimages | print preview media fullpage",
                        relative_urls: false,
                        });

                    }
                }

            }

            function add_enhance(){
                jQuery(document).on('focusin', function(e) {
                                if (jQuery(event.target).closest(".mce-window").length) {
                                    e.stopImmediatePropagation();
                                }
                            });
                var scntDiv = jQuery('#enhance_layout');
                var i = jQuery('#enhance_layout p').size() + 1;
                var source = jQuery('#enhance_layout').html();
                while (source.search("e_row"+i) >= 0){
                    i++;
                }
                        var r = 'e_row'+i;
                        jQuery('<p id="p'+i+'"><br><br><table width="100%" cellspacing="0" style="background-color: #BFECB8; border: solid 1px #D56868; padding-left: 20px;"><tr><td width="200px"><font><b>Sequence:</b></font> <input type="text" name="seq[]" id="seq" value="'+ i +'" style="width:30px"><br><br><font><b>Layout:</b></font><br><select name="pagemodel" style="width:130px" onchange="change_layout(this.value, \\'e_row'+i+'\\')"><option value="1FF">1 Col - Full Focus</option><option value="2LF">2 Col - Left Focus</option><option value="2RF">2 Col - Right Focus</option><option value="3CF">3 Col - Center Focus</option><option value="TPB">2 Row - Top Bottom</option><option value="3EF">3 Col - 3 Equal Column</option><option value="4EF">4 Col - 4 Equal Column</option><option value="CX1">Composite 1 - Compare</option></select></td><td><texteditor id="e_row' + i +'">Your content here.</texteditor></td></tr></table><br><a id="remScnt" onclick="remove_enhance('+i+')">Remove</a></p>').appendTo(scntDiv);
                        //i++;

                        tinymce.init({selector:'texteditor',
                        theme: "modern",
                        width: 700,
                        height: 200,
                        resize: "both",
                        plugins: [
                             "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                             "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                             "save table contextmenu directionality emoticons template paste textcolor jbimages"
                             ],
                        content_css: "css/content.css",
                        toolbar: "insertfile undo redo | styleselect fontselect fontsizeselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | forecolor backcolor | link image jbimages | print preview media fullpage",
                        relative_urls: false,
                        });
                        return false;
                        tinymce.EditorManager.execCommand('mceAddEditor',true, 'e_row'+i);
            }

            function remove_enhance(i){
                    var scntDiv = jQuery('#enhance_layout');
                    if( i > 1 ) {
                                //jQuery(this).parents('p'+i).remove();
                                jQuery('#p'+i).remove();
                                tinymce.EditorManager.execCommand('mceRemoveEditor',true, 'e_row'+i);

                                //jQuery("#e_row1").focus();

                                //i--;
                        }
                        return false;
            }

            function change_layout(layout, erow){

                var ffc = '<table style="height:180px" align="center" width="630px" cellpadding="0" cellspacing="0"><tr><td>Full Content. Add your content here</td></tr></table>';
                var lftwo = '<table style="height:180px" align="center" width="630px" cellpadding="0" cellspacing="0"><tr><td width="65%">Left Content. Add your content here</td><td>Right Content. Add your content here</td></tr></table>';
                var rftwo = '<table style="height:180px" align="center" width="630px" cellpadding="0" cellspacing="0"><tr><td width="35%">Left Content. Add your content here</td><td>Right Content. Add your content here</td></tr></table>';
                var cfthree = '<table style="height:180px" align="center" width="630px" cellpadding="0" cellspacing="0"><tr><td width="15%">Left Content. Add your content here</td><td style="text-align:center">Centre Content. Add your content here</td><td width="15%">Right Content. Add your content here</td></tr></table>';
                var topbot = '<table style="height:180px" align="center" width="630px" cellpadding="0" cellspacing="0"><tr><td>Your Top Content</td></tr><tr><td>Your Bottom Content</td></tr></table>';
                var threecol = '<table style="height:180px" align="center" width="630px" cellpadding="0" cellspacing="0"><tr><td width="33.3%" style="text-align: center">1st Column</td><td width="33.3%" style="text-align: center">2nd Column</td><td width="33.3%" style="text-align: center">3rd Column</td><tr></table>';
                var fourcol = '<table style="height:180px" align="center" width="630px" cellpadding="0" cellspacing="0"><tr><td width="25%" style="text-align: center">1st Column</td><td width="25%" style="text-align: center">2nd Column</td><td width="25%" style="text-align: center">3rd Column</td><td width="25%" style="text-align: center">4th Column</td><tr></table>';
                var complex1 = '<table style="height:180px" align="center" width="630px" cellpadding="0" cellspacing="0"><tr><td><h2>Title of Product</h2><h3>General Feature:</h3><ul class="features"><li>Feature 1</li><li>Feature 2</li><li>Feature 3</li><li>Feature 4</li><li>Feature 5</li><li>Feature 6</li><li>Feature 7</li><li>Feature 8</li><li>Feature 9</li><li>Feature 10</li></ul><table><thead><tr><th>&nbsp;</th><th><div class="preview"><a href="" title=""><img src="" alt="Your Image" /></a></div><small><a href="" title="">Description of 1st Pic</a></small></th><th><div class="preview"><img src="" alt="Your Image" /></div><small><a href="" title="">Description of 2nd Pic</a></small></th><th><div class="preview"><img src="" alt="Your Image" /></div><small><a href="" title="">Description of 3rd Pic</a></small></th><th><div class="preview"><img src="" alt="Your Image" /></div><small><a href="" title="">Description of 4th Pic</a></small></th></tr></thead><tbody><tr><td>Compare 1</td><td><strong>detail</strong></td><td><strong>detail</strong></td><td><strong>detail</strong></td><td><strong>detail</strong></td></tr><tr><td>Compare 2</td><td><strong>detail</strong></td><td><strong>detail</strong></td><td><strong>detail</strong></td><td><strong>detail</strong></td></tr><tr><td>Compare 3</td><td><strong>detail</strong></td><td><strong>detail</strong></td><td><strong>detail</strong></td><td><strong>detail</strong></td></tr><tr><td>Compare 4</td><td><strong>detail</strong></td><td><strong>detail</strong></td><td><strong>detail</strong></td><td><strong>detail</strong></td></tr><tr><td>Compare 5</td><td><strong>detail</strong></td><td><strong>detail</strong></td><td><strong>detail</strong></td><td><strong>detail</strong></td></tr></tbody></table></td></tr></table>';

                if(layout == '1FF'){
                    tinyMCE.get(erow).setContent(ffc);
                }
                else if(layout == '2LF'){
                    tinyMCE.get(erow).setContent(lftwo);
                }else if(layout == '2RF'){
                    tinyMCE.get(erow).setContent(rftwo);
                }else if(layout == '3CF'){
                    tinyMCE.get(erow).setContent(cfthree);
                }else if(layout == 'TPB'){
                    tinyMCE.get(erow).setContent(topbot);
                }else if(layout == '3EF'){
                    tinyMCE.get(erow).setContent(threecol);
                }else if(layout == '4EF'){
                    tinyMCE.get(erow).setContent(fourcol);
                }else if(layout == 'CX1'){
                    tinyMCE.get(erow).setContent(complex1);
                }
            }

            function compile_enhance(){
                //var i = jQuery('#enhance_layout p').size();
                var compile ='';
                var content =[];

                var count = jQuery("input[name^='seq']").length;
                var x = jQuery("input[name^='seq']");
                var theResultsMulti = new Array();

                for (i=0; i < tinyMCE.editors.length; i++){
                var seqval = new Array;

                seqval[0] = x.eq(i).val();
                seqval[1] = '<div id="erow">' + tinyMCE.editors[i].getContent() + '</div>';
                theResultsMulti.push(seqval);
                }

                theResultsMulti = theResultsMulti.sort(function(a,b) {
                  return a[0] - b[0];
                });
                compile = compile  + '<html>';
                for (i=0; i < count; i++){
                    compile = compile + theResultsMulti[i][1];
                }
                compile = compile  + '</html>';

                jQuery('#enhanced_listing_modal').val(compile);
                jQuery('#edit_enhance_modal').dialog("close");

            }



start;

        $data["edit_enhance_content"] = <<<start
            <div><form action="">
                <div style="text-align:left">Enhance Listing Editor - Usage: Click Compile after you finish editing a new frame, else the new frame may be lost. To Save your changes, click Save in the product page after the pop up closes.</div>
                <div id="enhance_layout">
                    <p id="p0">
                    <br>
                    <table width="100%" cellspacing="0" style="background-color: #BFECB8; border: solid 1px #D56868; padding-left: 20px;">
                        <tr>
                            <td width="200px">
                            <font><b>Sequence:</b></font> <input type="text" name="seq[]" id="seq" value="1" style="width:30px">
                            <br><br>
                            <font><b>Layout:</b></font><br>
                            <select name="pagemodel" style="width:130px" onchange="change_layout(this.value, 'e_row1')">
                                <option value="1FF">1 Col - Full Focus</option>
                                <option value="2LF">2 Col - Left Focus</option>
                                <option value="2RF">2 Col - Right Focus</option>
                                <option value="3CF">3 Col - Center Focus</option>
                                <option value="TPB">2 Row - Top Bottom</option>
                                <option value="3EF">3 Col - 3 Equal Column</option>
                                <option value="4EF">4 Col - 4 Equal Column</option>
                                <option value="CX1">Composite 1 - Compare</option>
                            </select>
                            </td>
                            <td>
                            <texteditor id='e_row1'><table style="height:180px" align="center" width="630px" cellpadding="0" cellspacing="0"><tr><td>Full Content. Add your content here</td></tr></table></texteditor>
                            </td>
                        </tr>
                    </table>
                    </p>
                </div>
                <!--<br></br>
                <div>
                    <button type="button" id="addlayout" onclick="add_enhance()">Add New</button> <button type="button" onclick="compile_enhance()">Compile</button>
                </div>-->
                </form>
            </div>

start;


        $image_file = $data["product"]->get_sku() . "_m." . $data["product"]->get_image();#."?".$data["product"]->get_modify_on();
        $data["display_image"] = is_file(IMG_PH . $image_file) ? $image_file : "imageunavailable_m.jpg";
        $data["osd_lang_list"] = $this->product_service->get_lang_osd_list();
        $this->load->view('marketing/product/product_detail_v', $data);
    }

    private function add_adgroup_keywords($sku, $cat_id, $lang_id = 'en', $keywords_arr = array())
    {
        #SBF #3041
        #set $debug = TRUE if want to debug. It will die and echo success/errors
        $debug = FALSE;
        // $debug=true;

        if (!$sku) {
            $result["status"] = FALSE;
            $result["error_msg"] = "SKU cannot be empty.";
            return $result;
        }

        if ($keywords_arr) {
            # proceed if there are keywords input

            $ad_accountid_arr = array();

            if (!((strpos($_SERVER["SERVER_NAME"], "admindev")) === FALSE)) {
                # testing in dev will send info to Google API Test Account
                $ad_accountid_arr = array("DEV" => "493-907-8910");
            } else {
                switch ($lang_id) {
                    case 'en':
                        $ad_accountid_arr = array(
                            "WEBAU" => "212-603-9902"
                        , "WEBGB" => "220-522-9085"
                        , "WEBCH" => "556-933-8151"
                        , "WEBFI" => "960-837-9622"
                        , "WEBMT" => "933-307-6722"
                        , "WEBIE" => "766-479-7671"
                        , "WEBNZ" => "182-353-3787"
                        , "WEBMY" => "492-329-4157"
                        , "WEBPH" => "952-771-4151"
                        , "WEBSG" => "383-339-9953"
                        );
                        break;

                    case 'es':
                        $ad_accountid_arr = array(
                            "WEBES" => "361-241-0604"
                        , "WEBPT" => "229-179-7402"
                        );
                        break;

                    case 'fr':
                        $ad_accountid_arr = array(
                            "WEBFR" => "316-460-3467"
                        , "WEBBE" => "423-123-0557"
                        );
                        break;

                    case 'it':
                        $ad_accountid_arr = array(
                            "WEBIT" => "899-782-9704"
                        );
                        break;

                    case 'ru':
                        $ad_accountid_arr = array(
                            "WEBRU" => "339-560-2926"
                        );
                        break;

                    default:
                        $ad_accountid_arr = array();
                        break;
                }
            }

            //if ($ad_accountid_arr) {
            if (false) {
                $cat_name = $this->product_model->get("category", array("id" => $cat_id))->get_name();

                foreach ($ad_accountid_arr as $platform_id => $id) {
                    if ($platform_id !== "DEV") {
                        # update to google only if campaign and adgroup exists
                        # if campaign and adgroup has been created via pricing tool,
                        # SKU+platform_id will have an entry in db adwords_data
                        $adwords_data_obj = $this->adwords_service->get_adwords_data_dao()->get(array("sku" => $sku, "platform_id" => $platform_id));
                    }

                    if ($adwords_data_obj === FALSE) {
                        $subject = 'Database Error: Could not retrieve adwords_data info';
                        $ad_content['File'] = __FILE__;
                        $ad_content['Line'] = __LINE__;
                        $ad_content['error'] =
                            "\nad_accountId: $id / platform_id $platform_id" .
                            "\ncampaign name: $cat_name" .
                            "\nUnable to update Google keywords from product management - " . base_url() . "/marketing/product/view/$sku/$lang_id" .
                            "\nDatabase error_msg:" . $this->db->_error_message();

                        if ($debug) {
                            echo "<pre>";
                            var_dump($ad_content);
                        }
                        continue;
                    } elseif ($adwords_data_obj || $platform_id == "DEV") {
                        $ad_content = array();
                        if ($user = $this->adwords_service->init_account($id)) {
                            # get campainID
                            $result = $this->adwords_service->get_campaign_by_name_v2($user, $cat_name);
                            if (array_key_exists('error', $result)) {
                                //this error most probably due to wrong ad_accoundId
                                $subject = 'unknown Error: maybe wrong ad_accoundID';
                                $ad_content['File'] = __FILE__;
                                $ad_content['Line'] = __LINE__;
                                $ad_content['error'] = $result['error'] .
                                    "\nad_accountId: $id / platform_id $platform_id" .
                                    "\ncampaign name: $cat_name" .
                                    "\nUnable to update Google keywords from product management - " . base_url() . "/marketing/product/view/$sku/$lang_id";

                                if ($debug) {
                                    echo "<pre>";
                                    var_dump($ad_content);
                                }

                                $this->adwords_service->mail_adcontent($ad_content, $subject);
                                continue;
                            } elseif (array_key_exists('duplicate', $result)) {
                                //if found more than one campaign exists, then do not continue
                                $subject = 'Duplicate Error: multiple campaigns found';
                                $ad_content['File'] = __FILE__;
                                $ad_content['Line'] = __LINE__;
                                $ad_content['Duplicate_error'] = $result['duplicate'] .
                                    "\nad_accountId: $id / platform_id $platform_id" .
                                    "\ncampaign name: $cat_name" .
                                    "\nUnable to update Google keywords from product management - " . base_url() . "/marketing/product/view/$sku/$lang_id";

                                if ($debug) {
                                    echo "<pre>";
                                    var_dump($ad_content);
                                }

                                $this->adwords_service->mail_adcontent($ad_content, $subject);
                                continue;
                            } elseif (array_key_exists('empty', $result)) {
                                //need to create a new campaign with category name as campaign name
                                $subject = 'Empty Error: campaign does not exist';
                                $ad_content['File'] = __FILE__;
                                $ad_content['Line'] = __LINE__;
                                $ad_content['Empty_error'] = $result['empty'] .
                                    "\nad_accountId: $id / platform_id $platform_id" .
                                    "\ncampaign name: $cat_name" .
                                    "\nUnable to update Google keywords from product management - " . base_url() . "/marketing/product/view/$sku/$lang_id";

                                if ($debug) {
                                    echo "<pre>";
                                    var_dump($ad_content);
                                }

                                $this->adwords_service->mail_adcontent($ad_content, $subject);
                                continue;
                            } else {
                                //all this good here, then
                                //check if adGroup exists, if yes, get adGroupID
                                $campaignId = $result->id;
                                $adGroupName = $sku;

                                $result = $this->adwords_service->get_adGroups_by_name_v2($user, $campaignId, $adGroupName);

                                if (array_key_exists('error', $result)) {
                                    $subject = 'unknown Error: maybe wrong ad_accoundID';
                                    $ad_content['File'] = __FILE__;
                                    $ad_content['Line'] = __LINE__;
                                    $ad_content['error'] = $result['error'] .
                                        "\nad_accountId: $id / platform_id $platform_id" .
                                        "\nUnable to update Google keywords from product management - " . base_url() . "/marketing/product/view/$sku/$lang_id";

                                    if ($debug) {
                                        echo "<pre>";
                                        var_dump($ad_content);
                                    }

                                    $this->adwords_service->mail_adcontent($ad_content, $subject);
                                    continue;
                                } elseif (array_key_exists('duplicate', $result)) {
                                    //if found more than one adGroup exists, then do not continue
                                    $subject = 'Duplicate Error: multiple adGroups exist';
                                    $ad_content['File'] = __FILE__;
                                    $ad_content['Line'] = __LINE__;
                                    $ad_content['Duplicate_error'] = $result['duplicate'] .
                                        "\nad_accountId: $id / platform_id $platform_id" .
                                        "\nUnable to update Google keywords from product management - " . base_url() . "/marketing/product/view/$sku/$lang_id";

                                    if ($debug) {
                                        echo "<pre>";
                                        var_dump($ad_content);
                                    }

                                    $this->adwords_service->mail_adcontent($ad_content, $subject);
                                    continue;
                                } elseif (array_key_exists('empty', $result)) {
                                    if ($debug) {
                                        echo "<pre>";
                                        var_dump($ad_content);
                                    }

                                    // $this->adwords_service->mail_adcontent($ad_content,$subject);
                                    continue;
                                } else {
                                    # get the list of existing keywords under this adGroupId
                                    $adGroupId = $result->id;
                                    $result = $this->adwords_service->get_specific_keyword_v2($user, $adGroupId);

                                    if (array_key_exists('error', $result)) {
                                        $subject = 'Get Keywords Error';
                                        $ad_content['File'] = __FILE__;
                                        $ad_content['Line'] = __LINE__;
                                        $ad_content['error'] = $result['error'] .
                                            "\nad_accountId: $id / platform_id $platform_id" .
                                            "\nUnable to update Google keywords from product management - " . base_url() . "/marketing/product/view/$sku/$lang_id";

                                        if ($debug) {
                                            echo "<pre>";
                                            var_dump($ad_content);
                                        }

                                        $this->adwords_service->mail_adcontent($ad_content, $subject);
                                        continue;
                                    } else {
                                        # if reach here = success in getting impt info, continue to create keywords
                                        foreach ($keywords_arr as $k => $keyword) {
                                            if ($result) {
                                                /* if there are existing keywords in adGroup, loop through
                                                    existing keywords and sieve out new keywords to append */

                                                if ((in_array($keyword, $result)) === FALSE) {
                                                    $ad_content["keyword"][] = $keyword;
                                                }
                                            } else {
                                                /* if no existing keywords in adgroup */
                                                $ad_content["keyword"][] = $keyword;
                                            }
                                        }

                                        if ($ad_content["keyword"]) {
                                            # create keywords in the adgroup
                                            # all keywords added from product admin page defaulted to BROAD match type.
                                            $keyword_result = $this->adwords_service->add_keywords_v2($user, $adGroupId, $ad_content);

                                            if (array_key_exists('error', $keyword_result)) {
                                                $subject = 'Error: keyword create failed';
                                                $ad_content['error'] = $keyword_result['error'] .
                                                    "\nad_accountId: $id / platform_id $platform_id" .
                                                    "\nUnable to update Google keywords from product management - " . base_url() . "/marketing/product/view/$sku/$lang_id";

                                                if ($debug) {
                                                    echo "<pre>";
                                                    var_dump($ad_content);
                                                }

                                                $this->adwords_service->mail_adcontent($ad_content, $subject);
                                                continue;
                                            } else {
                                                $subject = "$sku - Adgroup keywords updated successfully";
                                                $ad_content['success'] = "ad_accountId: $id" . "\nGoogle adGroup keywords updated successfully from product management - " . base_url() . "/marketing/product/view/$sku/$lang_id";

                                                if ($debug) {
                                                    echo "SUCCESS! <pre>";
                                                    var_dump($ad_content);
                                                }

                                                $this->adwords_service->mail_adcontent($ad_content, $subject);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                if ($debug) die();
            }
        }

        $result["status"] = TRUE;
        return $result;
    }

    public function arrayUnique($array, $preserveKeys = false)
    {
        // Unique Array for return
        $arrayRewrite = array();
        // Array with the md5 hashes
        $arrayHashes = array();
        foreach ($array as $key => $item) {
            // Serialize the current element and create a md5 hash
            $hash = md5(serialize($item));
            // If the md5 didn't come up yet, add the element to
            // to arrayRewrite, otherwise drop it
            if (!isset($arrayHashes[$hash])) {
                // Save the current element hash
                $arrayHashes[$hash] = $hash;
                // Add element to the unique Array
                if ($preserveKeys) {
                    $arrayRewrite[$key] = $item;
                } else {
                    $arrayRewrite[] = $item;
                }
            }
        }
        return $arrayRewrite;
    }

    public function delete($id = "")
    {
        if (($product_vo = $this->product_model->get_product(array("id" => $id))) === FALSE) {
            $_SESSION["NOTICE"] = __FILE__ . ":" . __LINE__ . ", " . $this->db->_error_message();
        } else {
            if (empty($product_vo)) {
                $_SESSION["NOTICE"] = "product_not_found";
            } else {
                if (!$this->product_model->inactive_product($product_vo)) {
                    $_SESSION["NOTICE"] = __FILE__ . ":" . __LINE__ . ", " . $this->db->_error_message();
                }
            }
        }
        if (isset($_SESSION["LISTPAGE"])) {
            redirect($_SESSION["LISTPAGE"]);
        } else {
            redirect(current_url());
        }
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

    function get_primary_image($sku, $size)
    {
        $default_name = "imageunavailable";
        $default_ext = "jpg";

        $image_obj = $this->product_model->get_prod_image_list(array("sku" => $sku, "status" => "1"), array("limit" => 1, "orderby" => "priority ASC, create_on ASC"));

        if ($size) {
            $size = "_" . $size;
        }

        if ($image_list) {
            if (is_file(IMG_PH . $image_obj->get_sku() . "_" . $image_obj->get_id() . $size . "." . $image_obj->get_image())) {
                return $image_obj->get_sku() . "_" . $image_obj->get_id() . $size . "." . $image_obj->get_image();
            }

        }
        return $default_name . $size . "." . $default_ext;
    }

    public function upload_sku_product_name()
    {
        $_SESSION["LISTPAGE"] = base_url() . "marketing/product/index?" . $_SERVER['QUERY_STRING'];
        $new_file = $this->upload_product_name_file($_FILES["sku_product_name_file"]["tmp_name"]);

        $fail_str = "";
        $success_str = "";

        if (file_exists($new_file)) {
            require_once(BASEPATH . 'plugins/csv_parser_pi.php');
            $csvfile = new CSVFileLineIterator($new_file);

            $arr = csv_parse($csvfile);
            if (is_array($arr)) {
                unset($arr[0]);
                $n = 0;
                $fail_no = 0;
                foreach ($arr as $line) {
                    if (!$line[0]) {
                        continue;
                    }
                    $n++;
                    $sku = $line[0];
                    $language_id = $line[1];
                    $new_product_name = $line[2];

                    if ($prod_cont = $this->product_model->get_product_content(array("prod_sku" => $sku, "lang_id" => $language_id))) {
                        if ($new_product_name) {
                            $prod_cont->set_prod_name($new_product_name);
                            if ($prod_cont = $this->product_model->product_service->get_pc_dao()->update($prod_cont)) {
                                $success_str .= "<p>SUCCESS: Sku=>{$sku} | lang_id => {$language_id} | product_name=>{$new_product_name}</p>";
                            } else {
                                $fail_no++;
                                $fail_str .= "<p>FAIL: At Line =>  {$n} |sku = {$sku} | lang_id => {$language_id} | Reason: Product Name Update fail<p>";
                            }
                        } else {
                            $fail_no++;
                            $fail_str .= "<p>FAIL: At Line =>  $n |sku = $sku | lang_id => {$language_id} | Reason: Empty Product Name<p>";
                        }

                    } else {
                        $fail_no++;
                        $fail_str .= "<p>FAIL: At Line =>  $n |sku = $sku | lang_id => {$language_id }| Reason: record cannot be found<p>";
                    }
                }
            } else {
                $fail_str = "CSV file can not be parsed!";
            }
        } else {
            $fail_str = "CSV file can not be uploaded!";
        }

        $receipient = "nero@eservicesgroup.com, christy.yeung@eservicesgroup.com";

        $message = $success_str . "<hr>" . $fail_str;
        mail($receipient, "[VB] Product Name Update in Bulk", $message);

        if ((!$fail_no) && ($fail_str)) {
            $_SESSION["NOTICE"] = $fail_str;
        } else {
            $success_no = $n - $fail_no;
            $_SESSION["NOTICE"] = "Product Name Update Done\nSuccess: {$success_no}\nFail: $fail_no";
        }
        redirect($_SESSION["LISTPAGE"]);
    }

    private function upload_product_name_file($temp_name)
    {
        if ($_FILES["sku_product_name_file"]["error"] > 0) {
            return "Error:<br>Return Code: " . $_FILES["sku_product_name_file"]["error"] . "<br>";
        } else {
            $time_stamp = date("ymd_H_i_s");
            $new_filename = $time_stamp . "_" . $_FILES["sku_product_name_file"]["name"];

            $product_name_upload_path = "/var/data/valuebasket.com/product_name_upload/$new_filename";

            if (move_uploaded_file($temp_name, $product_name_upload_path)) {
                return $product_name_upload_path;
            } else {
                return false;
            }
        }
    }

    public function AutoCreateWarranty($sku = '')
    {
        $product_obj = $this->sc['Product']->getDao('Product')->get(array('sku' => $sku));
        if ($product_obj) {
            $this->sc['ProductWarranty']->autoCreateProductWarranty($product_obj);
        }
    }
}


