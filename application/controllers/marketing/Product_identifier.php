<?php

class Product_identifier extends MY_Controller
{
    public $default_platform_id;
    private $appId = 'MKT0065';

    private $lang_id = 'en';

    public function __construct()
    {
        parent::__construct();
        $this->default_platform_id = $this->sc['ContextConfig']->valueOf("default_platform_id");
    }

    public function index()
    {
        $data = [];
        include_once APPPATH . "language/" . $this->getAppId() . "00_" . $this->getLangId() . ".php";
        $data["lang"] = $lang;
        $this->load->view("marketing/product_identifier/product_identifier_index", $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function getLangId()
    {
        return $this->lang_id;
    }

    public function plist()
    {
        $where = [];
        $option = [];
        $sub_app_id = $this->getAppId() . "02";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;

        $sku = $this->input->get("sku");
        $prod_name = $this->input->get("name");
        $master_sku = $this->input->get("master_sku");
        $prod_grp_cd = $this->input->get("prod_grp_cd");
        $colour_id = $this->input->get("colour_id");

        if ($sku != "" || $prod_name != "" || $master_sku != "" || $prod_grp_cd != "" || $colour_id != "") {
            $data["search"] = 1;
            if ($sku != "") {
                $where["sku"] = $sku;
            }

            if ($master_sku != "") {
                $where['master_sku'] = $master_sku;
            }

            if ($prod_name != "") {
                $where["name"] = $prod_name;
            }

            if ($prod_grp_cd != "") {
                $where["prod_grp_cd"] = $prod_grp_cd;
            }

            if ($colour_id != "") {
                $where["colour_id"] = $colour_id;
            }

            $sort = $this->input->get("sort");
            $order = $this->input->get("order");

            $limit = '20';

            $pconfig['base_url'] = current_url() . "?" . $_SERVER['QUERY_STRING'];
            $option['limit'] = ($this->input->get('limit') != '') ? $this->input->get('limit') : '20';
            $option['offset'] = ($this->input->get('per_page') != '') ? $this->input->get('per_page') : '';


            if (empty($sort))
                $sort = "sku";

            if (empty($order))
                $order = "asc";

            $option["orderby"] = $sort . " " . $order;

            $option["exclude_bundle"] = 1;
            $data["objlist"] = $this->sc['Product']->getDao('Product')->getListWithName($where, $option);

            $option["num_rows"] = 1;
            $data["total"] = $this->sc['Product']->getDao('Product')->getListWithName($where, $option);

            $config['base_url'] = base_url('marketing/product_identifier/plist');
            $config['total_rows'] = $data["total"];
            $config['page_query_string'] = true;
            $config['reuse_query_string'] = true;
            $config['per_page'] = $option['limit'];
            $this->pagination->initialize($config);
            $data['links'] = $this->pagination->create_links();

            $data["notice"] = notice($lang);

            $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
            $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
        }

        $this->load->view('marketing/product_identifier/product_identifier_list', $data);
    }

    public function view($sku = "")
    {
        if ($sku == "") {
            exit;
        }

        $data = [];
        $data["valid_supplier"] = 1;
        $data["prompt_notice"] = 0;
        $data["website_link"] = $this->sc['ContextConfig']->valueOf("website_domain");
        define('IMG_PH', $this->sc['ContextConfig']->valueOf("prod_img_path"));
        if ($this->input->post('posted')) {
            $pcid = $this->input->post('country_id');
            $pean = $this->input->post('ean');
            $pmpn = $this->input->post('mpn');
            $pupc = $this->input->post('upc');
            $pstatus = $this->input->post('status');

            foreach ($pcid as $val) {
                $cur_ean = $pean[$val];
                $cur_mpn = $pmpn[$val];
                $cur_upc = $pupc[$val];
                $cur_status = $pstatus[$val] * 1;

                $sku = $this->input->post('sku');

                $prod_obj = $this->sc['Product']->getDao('Product')->get(['sku'=>$sku]);
                $prod_grp_cd = $prod_obj->getProdGrpCd();
                $version_id = $prod_obj->getVersionId();
                $colour_id = $prod_obj->getColourId();

                $product_identifier_obj = $this->sc['ProductIdentifier']->getDao('ProductIdentifier')->get(["prod_grp_cd" => $prod_grp_cd, "colour_id" => $colour_id, "country_id" => $val]);
                if (!empty($product_identifier_obj) || $cur_ean != "" || $cur_mpn != "" || $cur_upc != "") {
                    if (!$product_identifier_obj) {
                        $action = "insert";
                        $product_identifier_obj = $this->sc['ProductIdentifier']->getDao('ProductIdentifier')->get();
                        $product_identifier_obj->setProdGrpCd($prod_grp_cd);
                        $product_identifier_obj->setColourId($colour_id);
                        $product_identifier_obj->setCountryId($val);
                    } else {
                        $action = "update";
                    }

                    if ($product_identifier_obj->getEan() != $cur_ean ||
                        $product_identifier_obj->getMpn() != $cur_mpn ||
                        $product_identifier_obj->getUpc() != $cur_upc ||
                        $product_identifier_obj->getStatus() != $cur_status
                    ) {
                        $product_identifier_obj->setEan($cur_ean);
                        $product_identifier_obj->setMpn($cur_mpn);
                        $product_identifier_obj->setUpc($cur_upc);
                        $product_identifier_obj->setStatus($cur_status);

                        $ret = $this->sc['ProductIdentifier']->getDao('ProductIdentifier')->$action($product_identifier_obj);
                        if ($ret === FALSE) {
                            $_SESSION["NOTICE"] = "{$action}_failed " . $this->db->display_error();
                        } else {
                            unset($_SESSION["product_identifier_obj"][$val]);
                            if ($this->input->post('target') != "") {
                                $data["prompt_notice"] = 1;
                            }
                        }
                    }
                } else {
                    unset($_SESSION["product_identifier_obj"][$val]);
                }
            }
            Redirect(base_url() . "marketing/product_identifier/view/" . $sku);
        }

        $data["action"] = "update";

        include_once APPPATH . "language/" . $this->getAppId() . "01_" . $this->getLangId() . ".php";
        $data["lang"] = $lang;
        $data["canedit"] = 1;
        $data["value"] = $sku;
        $data["target"] = $this->input->get('target');
        $data["notice"] = notice($lang);

        $pdata = [];
        if ($sku != "") {
            //unset($_SESSION["product_identifier_obj"]);
            $prod_obj = $this->sc['Product']->getDao('Product')->get(['sku'=>$sku]);
            $prod_grp_cd = $prod_obj->getProdGrpCd();
            $version_id = $prod_obj->getVersionId();
            $colour_id = $prod_obj->getColourId();

            $data["country_list"] = $this->sc['Country']->getDao('Country')->getSellCountryList();
            if ($prod_identifer_list = $this->sc['ProductIdentifier']->getDao('ProductIdentifier')->getList(["prod_grp_cd" => $prod_grp_cd, "colour_id" => $colour_id], ['groupby'=>'country_id', 'limit'=>-1])) {
                foreach ($prod_identifer_list as $pi_obj) {
                    $objcount++;
                    $data["product_identifier_list"][$pi_obj->getCountryId()] = $pi_obj;
                    $_SESSION["product_identifier_obj"][$pi_obj->getCountryId()] = serialize($pi_obj);
                }
            }

            $data["pdata"] = $pdata;
            $data["objcount"] = $objcount;
            $data["value"] = $sku;
        }

        $data["prod_obj"] = $prod_obj;
        $mapping_obj = $this->sc['SkuMapping']->getDao('SkuMapping')->get(['sku' => $sku, 'ext_sys' => 'WMS', 'status' => 1]);
        if ($mapping_obj && trim($mapping_obj->getExtSku()) != "") {
            $data['master_sku'] = $mapping_obj->getExtSku();
        }
        $_SESSION["prod_obj"] = serialize($prod_obj);

        $this->load->view("marketing/product_identifier/product_identifier_view", $data);

    }
}

?>