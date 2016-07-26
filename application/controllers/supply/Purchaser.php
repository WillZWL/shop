<?php

class Purchaser extends MY_Controller
{

    private $appId = "SUP0002";
    private $lang_id = "en";


    public function __construct()
    {
        parent::__construct();
        // $this->load->model('supply/purchaser_model');
        // $this->load->helper(array('url', 'notice', 'object', 'image'));
        // $this->load->library('service/pagination_service');
        // $this->load->library('service/context_config_service');
    }

    public function index($frame = "top")
    {
        $sub_app_id = $this->getAppId() . "00";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;

        if ($frame == "top") {
            $_SESSION["LISTPAGE"] = current_url() . "?" . $_SERVER['QUERY_STRING'];
            $this->load->view('supply/purchaser/purchaser_index_v', $data);
        } else {
            $where = [];
            $option = [];

            $sku = $this->input->get("sku");
            $prod_name = $this->input->get("name");
            $master_sku = $this->input->get("master_sku");

            if ($sku != "" || $prod_name != "" || $master_sku != "") {
                if ($sku != "") {
                    $where["sku"] = $sku;
                }

                if ($prod_name != "") {
                    $where["keywords"] = $prod_name;
                }

                if ($master_sku != "") {
                    $where['master_sku'] = $master_sku;
                }

                $sort = $this->input->get("sort");
                $order = $this->input->get("order");

                $option['limit'] = ($this->input->get('limit') != '') ? $this->input->get('limit') : 20;
                $option['offset'] = ($this->input->get('per_page') != '') ? $this->input->get('per_page') : '';

                if (empty($sort)) {
                    $sort = "name";
                }

                if (empty($order)) {
                    $order = "asc";
                }

                $option["orderby"] = $sort . " " . $order;

                $data["objlist"] = $this->sc['Product']->getDao('Product')->getListWithNameForPurchaserList($where, $option);
                $data["total"] = $this->sc['Product']->getDao('Product')->getListWithNameForPurchaserList($where, ["num_rows" => 1]);
                $pconfig['total_rows'] = $data['total'];

                $config['base_url'] = base_url('supply/purchaser/index/');
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

            $this->load->view('supply/purchaser/purchaser_left_v', $data);
        }
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function view($sku = "")
    {
        if ($sku) {
            global $data;
            $sub_app_id = $this->getAppId() . "01";

            if ($this->input->post("posted") && $this->input->post("cmd") == "edit") {
                if (isset($_SESSION["purchaser_obj"][$sku])) {

                    $src_supp_prod_vo = $data["supp_prod"] = unserialize($_SESSION["supp_prod"]);
                    $data["purchaser"] = unserialize($_SESSION["purchaser_obj"][$sku]);
                    // $data["prod"] = unserialize($_SESSION["prod_obj"][$sku]);
                    // $data["brand"] = unserialize($_SESSION["brand"][$sku]);
                    foreach ($data["purchaser"] as $obj) {
                        $supp_prod_vo = clone $src_supp_prod_vo;
                        $default_changed = $region_changed = $need_update = 0;
                        if ($_POST["check"][$obj->getSupplierId()]) {
                            $need_update = 1;
                        }
                        set_value($supp_prod_vo, $obj);

                        $supp_prod_vo->setPricehkd($obj->getPricehkd());


                        $old_moq = $supp_prod_vo->getMoq();
                        set_value($supp_prod_vo, $_POST["sp"][$obj->getSupplierId()]);
                        $new_moq = $supp_prod_vo->getMoq();

                        if ($this->input->post("sourcing_reg") != "") {
                            if ($this->input->post("sourcing_reg") == $supp_prod_vo->getRegionDefault()) {
                                $supp_prod_vo->setRegionDefault('');
                                $region_changed = 1;
                            }

                            if ($this->input->post("region_default") == $supp_prod_vo->getSupplierId()) {
                                $supp_prod_vo->setRegionDefault($this->input->post("sourcing_reg"));
                                $region_changed = ($region_changed) ? 0 : 1;
                            }

                            if ($region_changed) {
                                $need_update = 1;
                            }
                        } else {
                            if ($supp_prod_vo->getOrderDefault()) {
                                $supp_prod_vo->setOrderDefault(0);
                                $default_changed = 1;
                            }

                            if ($this->input->post("order_default") == $supp_prod_vo->getSupplierId()) {
                                $supp_prod_vo->setOrderDefault(1);
                                $default_changed = ($default_changed) ? 0 : 1;
                            }

                            if ($default_changed) {
                                $need_update = 1;
                            }
                        }

                        if ($need_update) {
                            if ($old_moq == $new_moq) {
                                if (!$this->sc['SupplierProd']->getDao('SupplierProd')->update($supp_prod_vo)) {
                                    $_SESSION["NOTICE"] = "ERROR " . __LINE__;
                                }
                            } else {
                                $d_where["supplier_id"] = $supp_prod_vo->getSupplierId();
                                $d_where["prod_sku"] = $supp_prod_vo->getProdSku();
                                $d_where["moq"] = $old_moq;
                                $this->sc['SupplierProd']->getDao('SupplierProd')->db->trans_start();
                                $this->sc['SupplierProd']->getDao('SupplierProd')->qDelete($d_where);
                                $this->sc['SupplierProd']->getDao('SupplierProd')->insert($supp_prod_vo);
                                $this->sc['SupplierProd']->getDao('SupplierProd')->db->trans_complete();
                            }
                        }
                    }

                    unset($_SESSION["purchaser_obj"]);
                    // unset($_SESSION["prod_obj"]);
                    unset($_SESSION["supp_prod"]);
                    // unset($_SESSION["brand"]);
                    $para = $this->input->post("sourcing_reg") ? "?sourcing_reg=" . $this->input->post("sourcing_reg") : "";

                    redirect(base_url() . "supply/purchaser/view/" . $sku . $para);
                }
            }

            include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
            $data["lang"] = $lang;

            $data["default_curr"] = $this->sc['ContextConfig']->valueOf("website_default_curr");

            if (empty($data["purchaser"])) {
                $curWhere["prod_sku"] = $sku;
                $curption = [
                    "to_currency" => $data["default_curr"],
                    "orderby" => "total_cost",
                ];

                if (($data["purchaser"] = $this->sc['SupplierProd']->getDao('SupplierProd')->getSupplierProdListWithName($curWhere, $curption)) === FALSE) {
                    $_SESSION["NOTICE"] = "ERROR " . __LINE__;
                } else {
                    $_SESSION["purchaser_obj"][$sku] = serialize($data["purchaser"]);
                }
            }

            if (empty($data["supp_prod"])) {
                if (($data["supp_prod"] = $this->sc['SupplierProd']->getDao('SupplierProd')->get()) === FALSE) {
                    $_SESSION["NOTICE"] = "ERROR " . __LINE__;
                } else {
                    $_SESSION["supp_prod"] = serialize($data["supp_prod"]);
                }
            }

            $data["prod"] = $this->sc['Purchaser']->getProdStProfit(["p.sku" => $sku, "pbv.selling_platform_id LIKE 'WEB%'" => NULL]);

            if ($data["prod"]["low_profit"]) {
                $data["brand"] = $this->sc['Brand']->getDao('Brand')->getBrandListWithSrcReg(["b.id" => $data["prod"]["low_profit"]->getBrandId()], array("limit" => 1));
            }

            $data["master_sku"] = $this->sc['SkuMapping']->getMasterSku(["sku" => $sku, "ext_sys" => "WMS", "status" => 1]);
            $data["note_objlist"] = $this->sc['Product']->getDao('ProductNote')->getList(['sku' => $sku, 'type' => 'S'], ['order_by' => 'modify_on DESC', 'limit' => 1]);
            $data["notice"] = notice($lang);
            $data["cmd"] = "edit";
            $data["sku"] = $sku;
            //$data["freight_region"] = $this->input->get("freight_region")?$this->input->get("freight_region"):$this->context_config_service->value_of("default_sourcing_region");
            $this->load->view('supply/purchaser/purchaser_detail_v', $data);
        }
    }
}


