<?php

class Purchaser extends MY_Controller
{

    private $app_id = "SUP0002";
    private $lang_id = "en";


    public function __construct()
    {
        parent::__construct();
        $this->load->model('supply/purchaser_model');
        $this->load->helper(array('url', 'notice', 'object', 'image'));
        $this->load->library('service/pagination_service');
        $this->load->library('service/context_config_service');
    }

    public function index($frame = "top")
    {
        $sub_app_id = $this->_get_app_id() . "00";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;

        if ($frame == "top") {
            $_SESSION["LISTPAGE"] = current_url() . "?" . $_SERVER['QUERY_STRING'];
            $this->load->view('supply/purchaser/purchaser_index_v', $data);
        } else {
            $where = array();
            $option = array();

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

                $limit = '-1';

                $pconfig['base_url'] = current_url() . "?" . $_SERVER['QUERY_STRING'];
                $option["limit"] = $pconfig['per_page'] = $limit;

                if ($option["limit"]) {
                    $option["offset"] = $this->input->get("per_page");
                }

                if (empty($sort))
                    $sort = "name";

                if (empty($order))
                    $order = "asc";

                $option["orderby"] = $sort . " " . $order;

                $data["objlist"] = $this->purchaser_model->get_product_list($where, $option);
                $data["total"] = $this->purchaser_model->get_product_list_total($where);
                $pconfig['total_rows'] = $data['total'];
                $this->pagination_service->set_show_count_tag(TRUE);
                $this->pagination_service->msg_br = TRUE;
                $this->pagination_service->initialize($pconfig);

                $data["notice"] = notice($lang);

                $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
                $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
            }
            $this->load->view('supply/purchaser/purchaser_left_v', $data);
        }
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function add()
    {
        global $data;
        $sub_app_id = $this->_get_app_id() . "01";

        if ($this->input->post("posted") && $this->input->post("cmd") == "add") {
            if (isset($_SESSION["supp_prod"])) {

                $sku = $this->input->post("prod_sku");
                $supplier_id = $this->input->post("supplier_id");
                $proc = $this->purchaser_model->get("supplier", "sp_dao", array("supplier_id" => $supplier_id, "prod_sku" => $sku, "moq" => $this->input->post("moq")));
                $this->purchaser_model->include_vo("supplier", "sp_dao");
                $data["supp_prod"] = unserialize($_SESSION["supp_prod"]);

                set_value($data["supp_prod"], $_POST);
                $data["supp_prod"]->set_order_default("0");

                if (!empty($proc)) {
                    $_SESSION["NOTICE"] = "supplier_existed";
                } else {
                    if ($new_obj = $this->purchaser_model->add("supplier", "sp_dao", $data["supp_prod"])) {
                        unset($_SESSION["supp_prod"]);
                        unset($data["supp_prod"]);
                        redirect(base_url() . 'supply/purchaser/view/' . $sku);
                    } else {
                        $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
                    }
                }
            }
            $this->view($sku);
        }
    }

    public function view($sku = "")
    {
        if ($sku) {
            global $data;
            $sub_app_id = $this->_get_app_id() . "01";
            define('IMG_PH', $this->context_config_service->value_of("prod_img_path"));

            if ($this->input->post("posted") && $this->input->post("cmd") == "edit") {
                if (isset($_SESSION["purchaser_obj"][$sku])) {
                    $this->purchaser_model->include_vo("supplier", "sp_dao");
                    $this->purchaser_model->include_vo("product", "dao");
                    $this->purchaser_model->include_dto("supplier", "sp_dao", "Supplier_prod_w_name_dto");
                    $src_supp_prod_vo = $data["supp_prod"] = unserialize($_SESSION["supp_prod"]);
                    $data["purchaser"] = unserialize($_SESSION["purchaser_obj"][$sku]);
                    $data["prod"] = unserialize($_SESSION["prod_obj"][$sku]);
                    $data["brand"] = unserialize($_SESSION["brand"][$sku]);
                    foreach ($data["purchaser"] as $obj) {
                        $supp_prod_vo = clone $src_supp_prod_vo;
                        $default_changed = $region_changed = $need_update = 0;
                        if ($_POST["check"][$obj->get_supplier_id()]) {
                            $need_update = 1;
                        }

                        set_value($supp_prod_vo, $obj);
                        $old_moq = $supp_prod_vo->get_moq();
                        set_value($supp_prod_vo, $_POST["sp"][$obj->get_supplier_id()]);
                        $new_moq = $supp_prod_vo->get_moq();

                        if ($this->input->post("sourcing_reg") != "") {
                            if ($this->input->post("sourcing_reg") == $supp_prod_vo->get_region_default()) {
                                $supp_prod_vo->set_region_default('');
                                $region_changed = 1;
                            }

                            if ($this->input->post("region_default") == $supp_prod_vo->get_supplier_id()) {
                                $supp_prod_vo->set_region_default($this->input->post("sourcing_reg"));
                                $region_changed = ($region_changed) ? 0 : 1;
                            }

                            if ($region_changed) {
                                $need_update = 1;
                            }
                        } else {
                            if ($supp_prod_vo->get_order_default()) {
                                $supp_prod_vo->set_order_default(0);
                                $default_changed = 1;
                            }

                            if ($this->input->post("order_default") == $supp_prod_vo->get_supplier_id()) {
                                $supp_prod_vo->set_order_default(1);
                                $default_changed = ($default_changed) ? 0 : 1;
                            }

                            if ($default_changed) {
                                $need_update = 1;
                            }
                        }

                        if ($need_update) {
                            if ($old_moq == $new_moq) {
                                if (!$this->purchaser_model->update("supplier", "sp_dao", $supp_prod_vo)) {
                                    $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
                                }
                            } else {
                                $d_where["supplier_id"] = $supp_prod_vo->get_supplier_id();
                                $d_where["prod_sku"] = $supp_prod_vo->get_prod_sku();
                                $d_where["moq"] = $old_moq;
                                if (!($this->supplier_service->get_sp_dao()->q_delete($d_where) && $this->purchaser_model->add("supplier", "sp_dao", $supp_prod_vo))) {
                                    $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
                                }
                            }
                        }
                    }
                    unset($_SESSION["purchaser_obj"]);
                    unset($_SESSION["prod_obj"]);
                    unset($_SESSION["supp_prod"]);
                    unset($_SESSION["brand"]);
                    $para = $this->input->post("sourcing_reg") ? "?sourcing_reg=" . $this->input->post("sourcing_reg") : "";
                    redirect(base_url() . "supply/purchaser/view/" . $sku . $para);
                }
            }

            include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
            $data["lang"] = $lang;

            $data["default_curr"] = $this->context_config_service->value_of("website_default_curr");
            $data["default_region"] = $this->context_config_service->value_of("default_region");

            if (empty($data["purchaser"])) {
                $cur_where["prod_sku"] = $sku;

                if (($data["purchaser"] = $this->purchaser_model->get_supplier_prod_list_w_name($cur_where, array("to_currency" => $data["default_curr"], "orderby" => "total_cost"))) === FALSE) {
                    $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
                } else {
                    $_SESSION["purchaser_obj"][$sku] = serialize($data["purchaser"]);
                }
            }

            if (empty($data["supp_prod"])) {
                if (($data["supp_prod"] = $this->purchaser_model->get("supplier", "sp_dao")) === FALSE) {
                    $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
                } else {
                    $_SESSION["supp_prod"] = serialize($data["supp_prod"]);
                }
            }

            if (empty($data["prod"])) {
                if ($data["prod"] = $this->purchaser_model->get_prod_st_profit($sku)) {
                    $_SESSION["prod_obj"][$sku] = serialize($data["prod"]);
                }
            }

            if ($data["prod"]["low_profit"]) {
                if (empty($data["brand"])) {
                    if (($data["brand"] = $this->purchaser_model->brand_service->get_dao()->get_brand_list_w_src_reg(array("b.id" => $data["prod"]["low_profit"]->get_brand_id()), array("limit" => 1))) === FALSE) {
                        $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
                    } else {
                        $_SESSION["brand"][$sku] = serialize($data["brand"]);
                    }
                }
            }
            $data["master_sku"] = $this->purchaser_model->get_master_sku(array("sku" => $sku, "ext_sys" => "WMS", "status" => 1));
            $data["note_objlist"] = $this->purchaser_model->get_note($sku, "S");
            $data["inventory"] = $this->purchaser_model->get_prod_inventory(array("prod_sku" => $sku));
            $data["notice"] = notice($lang);
            $data["cmd"] = "edit";
            $data["sku"] = $sku;
            //$data["freight_region"] = $this->input->get("freight_region")?$this->input->get("freight_region"):$this->context_config_service->value_of("default_sourcing_region");
            $this->load->view('supply/purchaser/purchaser_detail_v', $data);
        }
    }

    public function delete($id = "")
    {
        if ($this->input->post("posted") && ($sku = $this->input->post("sku"))) {
            foreach ($_POST["check"] as $supplier_id) {
                if (($product_vo = $this->purchaser_model->delete("supplier", "sp_dao", array("supplier_id" => $supplier_id, "prod_sku" => $sku))) === FALSE) {
                    $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
                }
            }
            redirect(base_url() . "supply/purchaser/view/" . $sku);
        }
    }

    public function update_status($sku = "")
    {
        if ($sku) {
            $sub_app_id = $this->_get_app_id() . "02";

            if ($this->input->post("posted")) {
                if ($prod_obj = $this->purchaser_model->get("product", "dao", array("sku" => $sku))) {
                    set_value($prod_obj, $_POST);
                    if ($this->purchaser_model->update("product", "dao", $prod_obj)) {
                        redirect(base_url() . 'supply/purchaser/view/' . $sku);
                    }
                }
                $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
                $this->view($sku);
            }
        }
    }

    public function add_note($sku)
    {
        $sub_app_id = $this->_get_app_id() . "02";
        if ($this->input->post("posted")) {
            $note_obj = $this->purchaser_model->get("product_note", "dao");
            $note_obj->set_sku($sku);
            $note_obj->set_type('S');
            $note_obj->set_note($this->input->post('note'));
            if ($ret = $this->purchaser_model->add("product_note", "dao", $note_obj)) {
                redirect(base_url() . 'supply/purchaser/view/' . $sku);
            }
            $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
            $this->view($sku);
        }
    }
}


