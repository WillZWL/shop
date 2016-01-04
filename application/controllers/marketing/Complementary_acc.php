<?php

use ESG\Panther\Service\PaginationService;

class Complementary_acc extends MY_Controller
{

    private $appId = 'MKT0078';
    private $accessory_catid_arr;
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();

        $this->paginationService = new PaginationService;

        $this->set_accessory_catid_arr();
    }

    private function set_accessory_catid_arr()
    {
        # this will give all the current category IDs that are accessories
        $this->accessory_catid_arr = $this->sc['complementaryAccModel']->getAccessoryCatidArr();
    }

    public function index()
    {
        $data = array();
        include_once APPPATH . "language/" . $this->getAppId() . "00_" . $this->getLangId() . ".php";
        $data["lang"] = $lang;
        $this->load->view("/marketing/complementary_acc/complementary_acc_index", $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function plist()
    {
        $where = array();
        $option = array();
        $sub_app_id = $this->getAppId() . "02";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;

        if (($sku = $this->input->get("sku")) != "" || ($prod_name = $this->input->get("name")) != "") {
            $data["search"] = 1;
            if ($sku != "") {
                $where["sku"] = $sku;
            }

            if ($prod_name != "") {
                $where["name"] = $prod_name;
            }

            $sort = $this->input->get("sort");
            $order = $this->input->get("order");

            $limit = '20';

            $pconfig['base_url'] = current_url() . "?" . $_SERVER['QUERY_STRING'];
            $option["limit"] = $pconfig['per_page'] = $limit;

            if ($option["limit"]) {
                $option["offset"] = $this->input->get("per_page");
            }

            if (empty($sort))
                $sort = "sku";

            if (empty($order))
                $order = "asc";

            $option["orderby"] = $sort . " " . $order;
            $option["exclude_bundle"] = 1;
            $option["exclude_complementary_acc"] = 1;
            $data["objlist"] = $this->sc['Product']->getDao('Product')->getListWithName($where, $option);
            $data["total"] = $this->sc['Product']->getDao('Product')->getListWithName($where, array_merge(['num_rows'=>1], $option));
            $pconfig['total_rows'] = $data['total'];
            $this->paginationService->setShowCountTag(TRUE);
            $this->paginationService->msg_br = TRUE;
            $this->paginationService->initialize($pconfig);

            $data["notice"] = notice($lang);

            $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
            $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
        }

        $this->load->view('marketing/complementary_acc/complementary_acc_list', $data);
    }

    public function view($sku = "")
    {
        if ($sku == "") {
            exit;
        }

        $data = array();
        $data["prompt_notice"] = 0;
        $data["website_link"] = $this->sc['Product']->getDao('Config')->valueOf('website_domain');

        define('IMG_PH', $this->sc['Product']->getDao('Config')->valueOf("prod_img_path"));
        $country_list = $this->sc['Country']->getCountryLanguageList();

        if ($this->input->post('posted')) {
            $action = "";

            $action = $this->input->post("action");
            $info = $prod_arr = array();

            if ($action == "insert") {
                // POST data in array new_acc_sku[$ctry_id] format
                $info = $this->input->post("new_acc_sku");
                $prod_arr["ctry_id"] = $ctry_id = key($info);
                $prod_arr["ca_sku"] = $info[$ctry_id];
                $prod_arr["mainprod_sku"] = $sku;

                $ret = $this->insert_complementary_acc($prod_arr);
                if ($ret["status"] === false) {
                    $_SESSION["NOTICE"] = $ret["message"];
                    Redirect(base_url() . "marketing/complementary_acc/view/" . $sku);
                } else {
                    $_SESSION["NOTICE"] = "$action success.";
                }
            } elseif ($action == "update") {
                $success = true;
                $message = "";
                var_dump($this->input->post("info"));
                foreach ($this->input->post("info") as $ca_sku => $v) {
                    $prod_arr["mainprod_sku"] = $sku;
                    $prod_arr["ca_sku"] = $ca_sku;
                    $prod_arr["ctry_id"] = $v["ctry_id"];
                    $prod_arr["castatus"] = $v["status"];
                    $ret = $this->update_complementary_acc($prod_arr);

                    if ($ret["status"] === false) {
                        // compile all error messages
                        $success = false;
                        $message .= $ret["message"] . "\n";
                    }
                }

                if ($success === false) {
                    $_SESSION["NOTICE"] = $message;
                    Redirect(base_url() . "marketing/complementary_acc/view/" . $sku);
                } else {
                    $_SESSION["NOTICE"] = "$action success.";
                }
            } elseif ($action == "bulk_update") {
                $ret = $this->bulk_update_status($sku, $_POST);

                if ($ret["status"] === false) {
                    $_SESSION["NOTICE"] = $ret["message"];
                    Redirect(base_url() . "marketing/complementary_acc/view/" . $sku);
                } else {
                    # if any existing mapping has changed status, inform user
                    $reset_alert = $ret["message"];
                    $_SESSION["NOTICE"] = "$action success. \n$reset_alert";
                }
            } elseif ($action == "bulk_insert") {
                $ret = $this->bulk_insert_complementary_acc($sku, $_POST["new_acc_sku"]["bulk"], $country_list);
                if ($ret['status'] === false) {
                    $_SESSION["NOTICE"] = $ret["message"];
                    Redirect(base_url() . "marketing/complementary_acc/view/" . $sku);
                } else {
                    # if any existing mapping has changed status, inform user
                    $reset_alert = $ret["message"];

                    $_SESSION["NOTICE"] = "$action success. \n$reset_alert";
                }
            }
        }
        include_once APPPATH . "language/" . $this->getAppId() . "01_" . $this->getLangId() . ".php";
        $data["lang"] = $lang;
        $data["canedit"] = 1;
        $data["value"] = $sku;
        $data["target"] = $this->input->get('target');
        $data["notice"] = notice($lang);

        // main product data and its mapped accessory for each country
        $proddata = array();
        if ($sku != "") {
            $objcount = 0;
            $data["mainprod"] = $this->sc['Product']->getDao('Product')->get(["sku" => $sku]);
            $mapping_obj = $this->sc['Product']->getDao('SkuMapping')->get(['sku' => $sku, 'ext_sys' => 'WMS', 'status' => 1]);
            if ($mapping_obj && trim($mapping_obj->getExtSku()) != "") {
                $data['master_sku'] = $mapping_obj->getExtSku();
            }

            if ($country_list) {
                $ca_country_list = array();

                foreach ($country_list as $country_obj) {
                    $country_id = $country_obj->getCountryId();
                    $proddata[$country_id]["ctryobj"] = $country_obj;

                    // get mapped complementary accessory info
                    $where["dest_country_id"] = $country_id;
                    $where["mainprod_sku"] = $sku;

                    if ($map_acc_list = $this->sc['Product']->getDao('ProductComplementaryAcc')->getMappedAccListWithName($where, $option, false)) {
                        foreach ($map_acc_list as $ca_obj) {
                            # gather CA list by CA SKU for mass update by countries (list of unique accessory skus)
                            $ca_country_list[$ca_obj->getAccessorySku()]["ca_obj"] = $ca_obj;
                            $ca_country_list[$ca_obj->getAccessorySku()]["country_list"] .= "$country_id,";
                        }
                    }
                    $proddata[$country_id]["caprodlist"] = $map_acc_list;
                    $data["all_country_list"] .= "$country_id,";
                    $objcount++;
                }

                $data["ca_country_list"] = $ca_country_list;
            }
            $data["proddata"] = $proddata;
            $data["objcount"] = $objcount;
            $data["sku"] = $sku;
        }
        $this->load->view("marketing/complementary_acc/complementary_acc_view", $data);
    }

    private function insert_complementary_acc($prod_arr = array())
    {
        $ret = array();
        $ret["status"] = false;

        if ($prod_arr) {
            $pca_obj = new \ProductComplementaryAccVo();

            $ctry_id = $prod_arr["ctry_id"];
            $ca_sku = strtoupper($prod_arr["ca_sku"]);
            # check if it's an accessory
            $check_ca = $this->sc['complementaryAccModel']->checkCat($ca_sku, true);

            if ($check_ca["status"] === true) {
                $mainprod_sku = strtoupper($prod_arr["mainprod_sku"]);

                $pca_obj->setStatus(1);
                $pca_obj->setMainprodSku($mainprod_sku);
                $pca_obj->setAccessorySku($ca_sku);
                $pca_obj->setDestCountryId($ctry_id);

                if (($this->sc['Product']->getDao('ProductComplementaryAcc')->insert($pca_obj)) === false) {
                    $ret["message"] = "Line: " . __LINE__ . " Insert failed (mainprod:$mainprod_sku || ca:$ca_sku || ctry:$ctry_id)\n" . $this->db->_error_message();
                } else {
                    $ret["status"] = true;
                }
            } else
                $ret["message"] = $check_ca["message"];
        }

        return $ret;
    }

    private function update_complementary_acc($prod_arr = array())
    {
        $ret = array();
        $ret["status"] = false;
        if ($prod_arr) {
            $mainprod_sku = $prod_arr["mainprod_sku"];
            $ca_sku = $prod_arr["ca_sku"];
            $ctry_id = $prod_arr["ctry_id"];
            $castatus = $prod_arr["castatus"];

            if ($pca_obj = $this->sc['Product']->getDao('ProductComplementaryAcc')->get(["mainprod_sku" => $mainprod_sku, "accessory_sku" => $ca_sku, "dest_country_id" => $ctry_id])) {
                $pca_obj->setStatus($castatus);
                if (($this->sc['Product']->getDao('ProductComplementaryAcc')->update($pca_obj)) === false) {
                    $ret["message"] = "Line: " . __LINE__ . " Updated failed (mainprod:$mainprod_sku || ca:$ca_sku || ctry:$ctry_id)\n" . $this->db->_error_message();
                } else {
                    $ret["status"] = true;
                }
            }
        }

        return $ret;
    }

    private function bulk_update_status($mainprod_sku, $postarray = array())
    {
        $ret = array();
        $ret["status"] = true;

        if ($postarray) {
            foreach ($postarray['status'] as $selected_ca => $status) {
                if ($status != "") {
                    $ca_sku = strtoupper($selected_ca);
                    $mainprod_sku = strtoupper($mainprod_sku);
                    $country_arr = explode(',', trim($postarray["country_list"][$selected_ca], ','));
                    $action = $postarray["action"];
                    $posted = $postarray["posted"];
                    // $status      = $postarray["status"];
                    if ($status == "") {
                        $status = 1;
                    }
                    if (is_array($country_arr)) {
                        foreach ($country_arr as $ctry_id) {
                            # get existing mapping. if no mapping exists for the country, ignore
                            if ($pca_obj = $this->sc['Product']->getDao('ProductComplementaryAcc')->get(["mainprod_sku" => $mainprod_sku, "accessory_sku" => $ca_sku, "dest_country_id" => $ctry_id])) {
                                $old_status = $pca_obj->getStatus();
                                if ($old_status != $status) {
                                    $pca_obj->setStatus($status);
                                    if (($this->sc['Product']->getDao('ProductComplementaryAcc')->update($pca_obj)) === false) {
                                        $ret["message"] .= "Line: " . __LINE__ . " Update failed (mainprod:$mainprod_sku || ca:$ca_sku || ctry:$ctry_id)\n" . $this->db->_error_message();
                                        $ret["status"] = false;
                                    } else {
                                        # compile alert message if changed from Inactive to Active
                                        if ($old_status == 0 && $status == 1) {
                                            $ret["message"] .= "$ca_sku - $ctry_id reset to Active! \n";
                                        }

                                    }
                                }
                            } else {
                                $ret["message"] .= "Line: " . __LINE__ . " Bulk update failed. No mapping exists for (mainprod_sku:$mainprod_sku || ca:$ca_sku || ctry:$ctry_id)\n";
                            }
                        }
                    }
                }
            }
        } else {
            $ret["message"] = "Line: " . __LINE__ . " Bulk update failed. No post info";
        }

        return $ret;
    }

    private function bulk_insert_complementary_acc($mainprod_sku, $ca_sku, $country_list)
    {
        // echo "<pre>";var_dump($country_list);
        $ret = array();
        $ret["status"] = true;
        $mainprod_sku = strtoupper($mainprod_sku);
        $ca_sku = strtoupper($ca_sku);
        $pca_dao = $this->sc['Product']->getDao('ProductComplementaryAcc');

        # check if it's an accessory
        $check_ca = $this->sc['complementaryAccModel']->checkCat($ca_sku, true);

        if ($check_ca["status"] === true) {
            foreach ($country_list as $key => $ctryobj) {
                $ctry_id = $ctryobj->getCountryId();
                $pca_vo = $pca_dao->get();


                if (!$pca_obj = $pca_dao->get(array("mainprod_sku" => $mainprod_sku, "accessory_sku" => $ca_sku, "dest_country_id" => $ctry_id))) {
                    $pca_vo->setMainprodSku($mainprod_sku);
                    $pca_vo->setAccessorySku($ca_sku);
                    $pca_vo->setDestCountryId($ctry_id);
                    $pca_vo->setStatus(1);

                    if (($pca_dao->insert($pca_vo)) === false) {
                        $ret["message"] .= "Line: " . __LINE__ . " Bulk insert failed (mainprod:$mainprod_sku || ca:$ca_sku || ctry:$ctry_id)\n" . $pca_dao->db->_error_message();
                        $ret["status"] = false;
                    }
                } else {
                    $dest_country_id = $pca_obj->getDestCountryId();
                    $status = $pca_obj->getStatus();

                    if ($status == 0) {
                        # if current status is 0, set it back to 1 with alert
                        $pca_obj->setStatus(1);
                        if (($pca_dao->update($pca_obj)) === false) {
                            $ret["message"] .= "Line: " . __LINE__ . " Bulk update failed (mainprod:$mainprod_sku || ca:$ca_sku || ctry:$ctry_id)\n" . $this->db->_error_message();
                            $ret["status"] = false;
                        } else {
                            $ret["message"] .= "$dest_country_id reset to Active! \n";
                        }
                    }
                }
            }
        } else {
            $ret["status"] = false;
            $ret["message"] = $check_ca["message"];
        }

        return $ret;
    }

    public function map_cat($ctry_id = "")
    {
        //$ctry_id = "AU";
        $data = $where = $option = array();
        $data["prompt_notice"] = 0;
        $data["website_link"] = $this->sc['Product']->getDao('Config')->valueOf('website_domain');

        define('IMG_PH', $this->sc['Product']->getDao('Config')->valueOf("prod_img_path"));

        if ($ctry_id) {
            $data["country"] = $ctry_id;
        }

        if ($this->input->post('posted')) {
            if ($cat_id = $this->input->post('cat_id')) {
                $where["cat_id"] = $cat_id;
            }

            if ($sub_cat_id = $this->input->post("sub_cat_id")) {
                $where["sub_cat_id"] = $sub_cat_id;
            }
            if ($sub_sub_cat_id = $this->input->post("sub_sub_cat_id")) {
                $where["sub_sub_cat_id"] = $sub_sub_cat_id;
            }
            $option["limit"] = -1;

            $info = $this->input->post("new_acc_sku");
            $ca_sku = $info[$ctry_id];
            if ($objlist = $this->sc['Product']->getDao('Product')->getListWithName($where, $option)) {
                $ret = $this->add_ca_by_cat($objlist, $ca_sku, $ctry_id);

                if ($ret["status"] === false) {
                    $_SESSION["NOTICE"] = $ret["message"];
                    Redirect(base_url() . "marketing/complementary_acc/map_cat/" . $ctry_id);
                } else {
                    $_SESSION["NOTICE"] = "Insert success";
                }
            }
        }

        include_once APPPATH . "language/" . $this->getAppId() . "01_" . $this->getLangId() . ".php";
        $data["lang"] = $lang;
        $data["canedit"] = 1;
        $data["value"] = $sku;
        $data["target"] = $this->input->get('target');
        $data["notice"] = notice($lang);

        $data["country_list"] = $this->sc['Country']->getCountryLanguageList();
        $this->load->view("marketing/complementary_acc/complementary_acc_mapcat_view", $data);
    }

    private function add_ca_by_cat($mainprodlist = array(), $ca_sku = "", $dest_country_id = "")
    {
        # pass in the whole product list by cat/subcat/subsubcat to map new complementary accessory
        $map_acc_list = $where = $prod_arr = $ret = array();
        $success = true;
        $message = "";
        if ($mainprodlist && $ca_sku && $dest_country_id) {
            foreach ($mainprodlist as $prodobj) {
                $mainprod_sku = $prodobj->getSku();
                $where["pca.mainprod_sku"] = $prod_arr["mainprod_sku"] = $mainprod_sku;
                $where["pca.accessory_sku"] = $prod_arr["ca_sku"] = $ca_sku;
                $where["pca.dest_country_id"] = $prod_arr["ctry_id"] = $dest_country_id;
                $option["limit"] = 1;
                $map_acc = (array)$this->sc['Product']->getDao('ProductComplementaryAcc')->getMappedAccListWithName($where, $option);

                if (empty($map_acc)) {
                    # only insert if mapping of (mainprod_sku - accessory_sku - dest_country_id) doesn't exist
                    $ret = $this->insert_complementary_acc($prod_arr);

                    if ($ret["status"] === false) {
                        $success = false;
                        $message .= $ret["message"] . "\n";
                    }
                }
            }
        }

        return $ret;
    }

    public function showdata()
    {
        // search for complementary accessories by sku or name
        $datatable = "<ul style=\"list-style-type:none;padding:10px;\"><li style=\"color:white;\">No data suggestion</li>";
        if ($_GET) {
            if ($_GET["q"]) {
                $like["p.name"] = "%{$_GET["q"]}%";
                $like['p.sku'] = "%{$_GET["q"]}%";
                $option["limit"] = 50;
                $all_ca_list = $this->sc['Product']->getDao('ProductComplementaryAcc')->getAllAccessory($where, $option, $like);
                $arr = (array)$all_ca_list;
                if (!empty($arr)) {
                    $datatable = "<ul style=\"list-style-type:none;padding:10px;\">";
                    foreach ($all_ca_list as $key => $value) {
                        $datatable .= <<<HTML
                                <li>
                                    <a href="#" onclick="selected('{$_GET["ctry"]}', '{$value->getAccessorySku()}');return false;"
                                    style="color:white;"
                                    onmouseover="this.style.color='#F7E741';style.fontWeight='bold';"
                                    onmouseout="this.style.color='white';style.fontWeight='normal';"
                                    >
                                    {$value->getAccessorySku()} :: {$value->getName()}
                                    </a>
                                </li><hr></hr>
HTML;
                    }
                    $datatable .= "</u>";
                }
            }
        }

        echo $datatable;
    }

    public function complementary_acc_js()
    {
        // javascript to show suggestion list with complementary accessory
        // also echo out SKU in input field when user click on it
        $base_url = base_url();
        header("Content-type: text/javascript; charset: UTF-8");
        header("Cache-Control: must-revalidate");
        $js = <<<javascript
                var xmlhttp;
                function showData(eleid, str, ctryid)
                {
                    // get str passed in and search for complementary acc suggestion list
                    // pass in id of suggestion list's element
                    var ele = document.getElementById(eleid);
                    if(str=="")
                    {
                        ele.innerHTML="";
                        ele.style.display = "none";
                        return;
                    }

                    if (window.XMLHttpRequest)
                    {// code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp=new XMLHttpRequest();
                    }
                    else
                    {// code for IE6, IE5
                        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp.onreadystatechange=function()
                        {
                            if (xmlhttp.readyState==4 && xmlhttp.status==200)
                            {
                                var list = xmlhttp.responseText;
                                var listobj = ele.innerHTML = list;
                                ele.style.display = "block";
                            }
                        }

                    // get complementary acc list
                    xmlhttp.open("GET","{$base_url}marketing/complementary_acc/showdata?q="+str+"&ctry="+ctryid,true);
                    xmlhttp.send();
                }

                // clicking on suggestion value will echo SKU to element id new_acc_sku
                function selected(ctryid, sku)
                {
                    var newaccsku = document.getElementById('new_acc_sku['+ctryid+']');
                    var txthint = document.getElementById('txtHint['+ctryid+']');
                    newaccsku.value = sku;
                    txthint.style.display = "none";
                }
javascript;

        echo $js;

    }
}

?>