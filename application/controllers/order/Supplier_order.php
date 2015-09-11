<?php

class Supplier_order extends MY_Controller
{

    private $appId = "ORD0001";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('order/supplier_order_model');
        $this->load->helper(array('url', 'notice'));
        $this->load->library('input');
        $this->load->library('service/event_service');
        $this->load->library('service/pagination_service');
    }

    public function index()
    {
        if ($this->input->post('add_message')) {
            $pm_number = $this->input->post('pm_number');
            $message = $this->input->post('message');


            $obj = $this->supplier_order_model->get_pm();
            $obj->set_po_number($pm_number);
            $obj->set_message($message);

            $ret = $this->supplier_order_model->insert_pm($obj);
            if ($ret === FALSE) {
                $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
            }

            Redirect(base_url() . "order/supplier_order/");
        }

        $sub_app_id = $this->getAppId() . "01";

        $_SESSION["SOLISTPAGE"] = base_url() . "order/supplier_order/?" . $_SERVER['QUERY_STRING'];


        $where = array();
        $option = array();

        $where["po_number"] = $this->input->get("po_number");
        $where["supplier"] = $this->input->get("name");
        $where["supplier_invoice_number"] = $this->input->get("supplier_invoice_number");
        $where["delivery_mode"] = $this->input->get("delivery_mode");
        $where["eta"] = $this->input->get("eta");
        if ($this->input->get("status") != "") {
            $where["status"] = $this->input->get("status");
        } else {
            $where["status <>"] = "CL";
        }

        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        $limit = $this->pagination_service->get_num_records_per_page();

        $pconfig['base_url'] = base_url() . "order/supplier_order/?" . $_SERVER['QUERY_STRING'];
        $option["limit"] = $pconfig['per_page'] = $limit;
        if ($option["limit"]) {
            $option["offset"] = $this->input->get("per_page");
        }

        if (empty($sort))
            $sort = "po_number";

        if (empty($order))
            $order = "desc";

        $option["orderby"] = ($sort == "name" ? "s." . $sort : "po." . $sort) . " " . $order;

        $data = $this->supplier_order_model->get_supplier_order_list_index($where, $option);

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;

        $pconfig['total_rows'] = $data['total'];
        $this->pagination_service->initialize($pconfig);

        $data["notice"] = notice($lang);

        $data["syslang"] = $this->_get_lang_id();
        $data["refresh"] = $this->input->get("refresh");
        $data["added"] = $this->input->get("added");
        $data["updated"] = $this->input->get("updated");

        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
//      $data["searchdisplay"] = ($where["po_number"]=="" && $where["supplier"]=="" && $where["supplier_invoice_number"]=="" &&  $where["delivery_mode"]=="" && $where["status"]=="")?'style="display:none"':"";
        $data["searchdisplay"] = "";
        $this->load->view('order/supplier_order/index', $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function add()
    {
        if ($this->input->post('posted')) {
            $qty = $this->input->post('qty');
            if (!is_array($qty)) {
                $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . "should_have_item";
                $this->load->view('order/supplier_order/index', $data);
                return;
            }

            $err = 0;
            $po_obj = $this->supplier_order_model->get_po();
            $po_obj->set_supplier_id($this->input->post("supplier"));
            $po_obj->set_supplier_invoice_number($this->input->post("supplier_invoice_number"));
            $po_obj->set_delivery_mode($this->input->post("delivery_mode"));
            $po_obj->set_status("N");
            $po_obj->set_currency($this->input->post("currency"));
            $po_obj->set_amount($this->input->post("amount"));
            list($d, $m, $y) = explode("/", $this->input->post('eta'));
            $po_obj->set_eta($y . "-" . $m . "-" . $d);
            $seq = $this->supplier_order_model->seq_next_val();
            $po_number = "PO" . sprintf("%06d", $this->supplier_order_model->seq_next_val());

            $po_obj->set_po_number($po_number);


            $this->supplier_order_model->start_transaction();

            $result = $this->supplier_order_model->insert_po($po_obj);

            if ($result === FALSE) {
                $err++;
                $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
            } else {
                $skui = $this->input->post("sku");
                $qtyi = $this->input->post("qty");
                $pricei = $this->input->post("price");

                foreach ($skui as $key => $value) {
                    $po_item_obj = $this->supplier_order_model->get_po_item();
                    $po_item_obj->set_po_number($po_number);
                    $po_item_obj->set_sku($value);
                    $po_item_obj->set_status("A");
                    $po_item_obj->set_shipped_qty(0);
                    $po_item_obj->set_order_qty($qtyi[$key]);
                    $po_item_obj->set_unit_price($pricei[$key]);
                    $po_item_obj->set_line_number($key + 1);

                    $ret = $this->supplier_order_model->insert_po_item($po_item_obj);
                    if ($ret === FALSE) {
                        $err++;
                        $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
                    }
                }
            }
            $this->supplier_order_model->update_seq($seq);
            $this->supplier_order_model->end_transaction();
            if (!$err) {
                //$this->event_service->fire_event($event, $obj);
                unset($_SESSION["NOTICE"]);
                Redirect(base_url() . "order/supplier_order/index/");
            }
            Redirect(base_url() . "order/supplier_order/add/");
        }

        $this->load->view('order/supplier_order/index_add', $data);
    }

    public function add_left()
    {
        $where = array();
        $option = array();
        $sub_app_id = $this->getAppId() . "03";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
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
            $option["purchaser"] = 1;

            $data["objlist"] = $this->supplier_order_model->get_product_list($where, $option);
            $data["total"] = $this->supplier_order_model->get_product_list_total($where, $option);
            $pconfig['total_rows'] = $data['total'];
            $this->pagination_service->set_show_count_tag(TRUE);
            $this->pagination_service->msg_br = TRUE;
            $this->pagination_service->initialize($pconfig);

//          $data["notice"] = notice($lang);

            $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
            $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
        }

        $this->load->view('order/supplier_order/v_prodlist', $data);
    }

    public function add_right()
    {
        $sub_app_id = $this->getAppId() . "02";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;
        $data["notice"] = notice($lang);

        # we are asked to pull from CPS
        if (isset($_GET['pull_from_cps'])) {
            # pull the XML from CPS and formulate the java insertion code
            // $url = "http://cps.eservicesgroup.net/xml.autoallocate.php?warehouseplan&name=VB&id=6&wid={$_GET["pull_from_cps"]}";
            $url = "http://cps.eservicesgroup.net/xml.autoallocate.php?warehouseplan&name=VB&id=6&wid={$_GET["pull_from_cps"]}";
            $use_curl = true;
            if ($use_curl) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                $xmlstring = curl_exec($ch);
                curl_close($ch);
            } else
                $xmlstring = file_get_contents($url);
            $xmlobject = simplexml_load_string($xmlstring, 'SimpleXMLElement', LIBXML_NOCDATA);
            $javacode = "";
            foreach ($xmlobject->transfer as $transfer) {
                $javacode .= "addRow('order_form', '{$transfer->retailer_sku}', '" . str_replace(array("\\", "'"), array("\\\\", "\'"), $transfer->name) . "', '{$transfer->qty}', '{$transfer->pricehkd}');";
                #$javacode .= "addRow('order_form', '{$transfer->sku}', '{$transfer->name}', '{$transfer->qty}', '{$transfer->pricehkd}');";
            }

            $data['skulist'] = "<script language=\"javascript\">$javacode</script>";
        }
        $this->load->view('order/supplier_order/v_add.php', $data);
    }

    public function view_ship($po_number)
    {
        if ($this->input->post('posted')) {
            $skulist = $this->input->post('sku');
            $qtylist = $this->input->post('qty');
            $sqtylist = $this->input->post('shipped_qty');
            $price = $this->input->post('price');
            $line_number = $this->input->post('line_number');
            $delete = $this->input->post('delete');
            $batch = $this->input->post('batch');
            $update_on = $this->input->post('modify_on');
            $create_on = $this->input->post('create_on');
            $create_at = $this->input->post('create_at');
            $create_by = $this->input->post('create_by');
            $wh_list = $this->supplier_order_model->get_warehouse_list();
            $current_maxline = $this->supplier_order_model->get_max_line_number($po_number);
            $sourcing_region = $this->input->post('sourcing_region');
            $where = array();
            $this->supplier_order_model->start_transaction();
            $pc = 0;
            $err = 0;
            $ln_cnt = 1;
            $shipped = 0;

            $shipqty = array();

            foreach ($wh_list as $whobj) {
                $ud = 0;
                $wh = strtolower($whobj->get_id());
                $tmp = $this->input->post("$wh");
                $shipment_count = $this->supplier_order_model->get_shipment_count($po_number);
                $shipment_id = $po_number . "-" . sprintf("%02d", ($shipment_count + 1));

                //$sid = $this->supplier_order_model->ss_seq_next_val();
                //$shipment_id = "ST".sprintf("%08d",$sid);
                for ($i = 0; $i < count($skulist); $i++) {
                    if ($delete[$i] || $tmp[$i] == 0) {
                        continue;
                    } else {
                        $pois_obj = $this->supplier_order_model->get_purchase_order_item_shipment();
                        $pois_obj->set_sid($shipment_id);
                        $pois_obj->set_po_number($po_number);
                        $pois_obj->set_line_number($line_number[$i]);
                        $pois_obj->set_qty($tmp[$i]);
                        $pois_obj->set_received_qty(0);
                        $pois_obj->set_to_location($whobj->get_id());


                        $ret = $this->supplier_order_model->insert_shipment_item($pois_obj);
                        if ($ret === FALSE) {
                            $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
                            $err++;
                            break;
                        } else {
                            $shipqty[$i] += $tmp[$i];
                            $inv_move_obj = $this->supplier_order_model->get_inv_movement_obj();
                            $inv_move_obj->set_ship_ref($shipment_id);
                            $inv_move_obj->set_sku($skulist[$i]);
                            $inv_move_obj->set_qty($tmp[$i]);
                            $inv_move_obj->set_type('S');
                            $inv_move_obj->set_from_location('SPL');
                            $inv_move_obj->set_to_location(strtoupper($wh));
                            $inv_move_obj->set_status('IT');
                            $ret2 = $this->supplier_order_model->insert_inv_movement($inv_move_obj);
                            if ($ret2 == FALSE) {
                                $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
                                $err++;
                                break;
                            } else {
                                $ret->set_invm_trans_id($ret2->get_trans_id());
                                $r = $this->supplier_order_model->update_pois($ret, array());
                                if (!$r) {
                                    $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
                                    $err++;
                                    break;
                                } else {
                                    $ud++;
                                }
                            }
                        }
                    }
                }
                if ($ud) {
                    $shipment_obj = $this->supplier_order_model->get_supplier_shipment_obj();
                    $shipment_obj->set_shipment_id($shipment_id);
                    $shipment_obj->set_status("IT");
                    $shipment_obj->set_tracking_no($this->input->post("tracking_no"));
                    $shipment_obj->set_courier($this->input->post("courier"));
                    $ret = $this->supplier_order_model->insert_shipment($shipment_obj);

                    if ($ret === FALSE) {
                        $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
                        $err++;
                    }
                }
                if ($err) {
                    break;
                }
            }

            for ($i = 0; $i < count($skulist); $i++) {
                $po_item_obj = $this->supplier_order_model->get_po_item();
                $po_item_obj->set_sku($skulist[$i]);
                $po_item_obj->set_order_qty($qtylist[$i]);
                $po_item_obj->set_unit_price($price[$i]);
                $po_item_obj->set_po_number($po_number);
                if ($delete[$i]) {
                    $status = "D";
                } else {
                    $status = "A";
                    $pc++;
                }
                $insert = 0;
                $ln = $line_number[$i];
                if ($line_number[$i] == "") {
                    $ln = $current_maxline + $ln_cnt++;
                    $insert = 1;
                }
                $po_item_obj->set_line_number($ln);
                $po_item_obj->set_status($status);
                $po_item_obj->set_shipped_qty($sqtylist[$i] + $shipqty[$i]);

                if (!$insert) {
                    $po_item_obj->set_create_on($create_on[$i]);
                    $po_item_obj->set_create_at($create_at[$i]);
                    $po_item_obj->set_create_by($create_by[$i]);
                    $result = $this->supplier_order_model->update_po_item($po_item_obj, array("po_number" => $po_number, "line_number" => $line_number[$i], "modify_on <=" => $update_on[$i]));
                    if ($result == "0") {
                        $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . "updated_by_other";
                        $err++;
                        break;
                    }
                } else {
                    $result = $this->supplier_order_model->insert_po_item($po_item_obj);
                }

                if ($result === FALSE) {
                    $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
                    $err++;
                    break;
                }
            }

            if (!$err) {
                $this->supplier_order_model->__autoload_po_vo();
                $po_obj = unserialize($_SESSION["po_obj"]);
                $po_obj->set_supplier_id($this->input->post("supplier"));
                $po_obj->set_supplier_invoice_number($this->input->post("supplier_invoice_number"));
                $po_obj->set_delivery_mode($this->input->post("delivery_mode"));
                list($d, $m, $y) = explode("/", $this->input->post('eta'));
                $po_obj->set_eta($y . "-" . $m . "-" . $d);

                //check status and update it if necessary
                $status = $this->supplier_order_model->check_shipment_status($po_number);

                if ($status["in_progress"] == 0) {
                    if ($status["completed"] == 0) {
                        $po_status = "N";
                    } else if ($status["completed"] < $status["total"] && $status["completed"] > 0) {
                        $po_status = "PS";
                    } else {
                        $po_status = $this->supplier_order_model->update_po_status(array("po_number" => $po_number), 0);
                        if ($po_status === TRUE && $po_obj->get_status() == 'C') {
                            $po_status = "C";
                        } else {
                            $po_status = "FS";
                        }
                    }
                } else if ($status["in_progress"] > 0) {
                    $po_status = "PS";
                }
                $po_obj->set_status($po_status);

                if ($pc == 0) {
                    $po_obj->set_status("CL");
                }
                $po_obj->set_currency($this->input->post("currency"));
                $po_obj->set_amount($this->input->post("amount"));

                $result = $this->supplier_order_model->update_po($po_obj, array("modify_on <= " => $po_obj->get_modify_on()));
                if ($result === FALSE) {
                    $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
                    $err++;
                }

                if (!$err) {
                    unset($_SESSION["NOTICE"]);
                    unset($_SESSION["po_obj"]);
                    unset($_SESSION["po_item_obj"]);
                }
            }
            $this->supplier_order_model->end_transaction();

            if ($pc == 0) {
                Redirect(base_url() . "order/supplier_order/index");
            }
            Redirect(base_url() . "order/supplier_order/view_ship/" . $po_number);
        }
        $sub_app_id = $this->getAppId() . "05";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["po_number"] = $po_number;
        $data["lang"] = $lang;
//      $data["notice"] = notice($lang);
        $this->load->view('order/supplier_order/index_add_ship', $data);
    }

    public function add_ship_right($po_number)
    {
        $po_obj = $this->supplier_order_model->get_po($po_number);
        if (empty($po_obj)) {
            Redirect(base_url() . "order/supplier_order/index");
        } else {
            $item_list = $this->supplier_order_model->get_po_item_list($po_number);
            $shipment_list = $this->supplier_order_model->get_supplier_shipment_record($po_number);
            $data["shipment_info"] = $this->supplier_order_model->get_shipment_info($po_number);

            $_SESSION["shipment_list"] = serialize($shipment_list);
            $sub_app_id = $this->getAppId() . "06";
            include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
            $data["po_obj"] = $po_obj;
            $_SESSION["po_obj"] = serialize($po_obj);
            $data["wh_list"] = $this->supplier_order_model->get_warehouse_list();
            $data["shipment_list"] = $shipment_list;
            $display_list = $this->supplier_order_model->get_order_item($po_number);


            $data["po_item_list"] = $display_list;
            $data["lang"] = $lang;
            $data["notice"] = notice($lang);
            $this->load->view('order/supplier_order/add_ship_right', $data);
        }
    }

    public function view_right($po_number)
    {
        $po_obj = $this->supplier_order_model->get_po($po_number);
        if (empty($po_obj)) {
            Redirect(base_url() . "order/supplier_order/index");
        } else {
            $data["po_obj"] = $po_obj;
            $_SESSION["po_obj"] = serialize($po_obj);
            $where = array();
            $where["po_number"] = $po_number;
            $option = array("limit" => "1");

            $item_list = $this->supplier_order_model->get_po_item_list($po_number);

            $_SESSION["item_list"] = serialize($item_list);
            $display_list = $this->supplier_order_model->get_order_item($po_number);

            $data["po_item_list"] = $display_list;
            $sub_app_id = $this->getAppId() . "04";
            include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
            $data["lang"] = $lang;
            $data["notice"] = notice($lang);

            $this->load->view('order/supplier_order/v_view', $data);
        }
    }

    public function view($po_number)
    {
        if ($this->input->post('posted')) {
            $skulist = $this->input->post('sku');
            $qtylist = $this->input->post('qty');
            $price = $this->input->post('price');
            $line_number = $this->input->post('line_number');
            $delete = $this->input->post('delete');
            $update_on = $this->input->post('modify_on');
            $create_on = $this->input->post('create_on');
            $create_at = $this->input->post('create_at');
            $create_by = $this->input->post('create_by');
            $current_maxline = $this->supplier_order_model->get_max_line_number($po_number);
            $sourcing_region = $this->input->post('sourcing_region');
            $where = array();
            $this->supplier_order_model->start_transaction();
            $pc = 0;
            $err = 0;
            $ln_cnt = 1;

            for ($i = 0; $i < count($skulist); $i++) {
                $po_item_obj = $this->supplier_order_model->get_po_item();
                $po_item_obj->set_sku($skulist[$i]);
                $po_item_obj->set_order_qty($qtylist[$i]);
                $po_item_obj->set_unit_price($price[$i]);
                $po_item_obj->set_po_number($po_number);
                if ($delete[$i]) {
                    $status = "D";
                } else {
                    $status = "A";
                }
                $insert = 0;
                $ln = $line_number[$i];
                if ($line_number[$i] == "") {
                    $ln = $current_maxline + $ln_cnt++;
                    $insert = 1;
                }
                $po_item_obj->set_line_number($ln);

                $po_item_obj->set_status($status);

                $po_item_obj->set_shipped_qty(0);

                if (!$insert) {
                    $po_item_obj->set_create_on($create_on[$i]);
                    $po_item_obj->set_create_at($create_at[$i]);
                    $po_item_obj->set_create_by($create_by[$i]);
                    $result = $this->supplier_order_model->update_po_item($po_item_obj, array("po_number" => $po_number, "line_number" => $line_number[$i], "modify_on <=" => $update_on[$i]));
                    if ($result == "0") {
                        $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . "updated_by_other";
                        $err++;
                        $this->supplier_order_model->trans_rollback();
                        break;
                    }
                } else {
                    $result = $this->supplier_order_model->insert_po_item($po_item_obj);
                }
                if ($result === FALSE) {
                    $_SESSION["NOTICE"] = $this->supplier_order_model->get_error_message();
                    $err++;
                    break;
                } else {
                    if ($status == "A") {
                        $pc++;
                    }
                }

            }

            if (!$err) {
                $this->supplier_order_model->__autoload_po_vo();
                $po_obj = unserialize($_SESSION["po_obj"]);
                $po_obj->set_supplier_id($this->input->post("supplier"));
                $po_obj->set_supplier_invoice_number($this->input->post("supplier_invoice_number"));
                $po_obj->set_delivery_mode($this->input->post("delivery_mode"));
                $po_obj->set_status($this->input->post("status"));
                list($d, $m, $y) = explode("/", $this->input->post('eta'));
                $po_obj->set_eta($y . "-" . $m . "-" . $d);
                if ($pc == 0) {
                    $po_obj->set_status("CL");
                }
                $po_obj->set_currency($this->input->post("currency"));
                $po_obj->set_amount($this->input->post("amount"));

                $result = $this->supplier_order_model->update_po($po_obj, array("modify_on <=" => $po_obj->get_modify_on()));
                if ($result === FALSE) {
                    $_SESSION["NOTICE"] = $this->supplier_order_model->get_error_message();
                    $err++;
                } else if ($result == "0") {
                    $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . "update_by_other";
                    $this->supplier_order_model->trans_rollback();
                    $err++;
                }
                if (!$err) {
                    unset($_SESSION["po_obj"]);
                    unset($_SESSION["po_item_obj"]);
                }
            }
            $this->supplier_order_model->end_transaction();
            if ($pc == 0) {
                Redirect(base_url() . "order/supplier_order/index");
            }
            Redirect(base_url() . "order/supplier_order/view/" . $po_number);
        }
        if ($po_number == "") {
            Redirect(base_url() . "order/supplier_order/index");
        } else {
            $data["po_number"] = $po_number;
            $this->load->view('order/supplier_order/index_view', $data);
        }
    }

    public function supplier_outstanding()
    {
        $this->load->view("order/supplier_order/search_by_supplier", $data);
    }

    public function confirm_shipment($wh = "CW")
    {
        $sub_app_id = $this->getAppId() . "07";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        if ($this->input->post('posted')) {

            $shipment_id = $this->input->post('shipment_id');
            $sq = $this->input->post('sq');
            $rqty = $this->input->post('rqty');
            $this->supplier_order_model->__autoload_imvo();
            $this->supplier_order_model->__autoload_ssvo();
            $this->supplier_order_model->__autoload_pois_vo();
            $imvolist = unserialize($_SESSION["imvolist"]);
            $ssvolist = unserialize($_SESSION["ssvolist"]);
            $poislist = unserialize($_SESSION["poislist"]);


            $sn = $this->input->post("shipment_id");
            $ln = $this->input->post("linen");
            $trans = $this->input->post("trans");
            $rqty = $this->input->post("rqty");
            $remarks = $this->input->post("remarks");
            $pon = $this->input->post("pon");
            $reason = $this->input->post("reason");
            $check = $this->input->post("checked");
            $serr = 0;
            $dis_msg = array();

            foreach ($sn as $key => $value) {
                if ($check[$key]) {
                    $data = array();
                    $this->supplier_order_model->start_transaction();
                    $updateStatus = array();
                    //update inv movement
                    //update po_item_shipment
                    foreach ($rqty[$key] as $key2 => $value2) {
                        $sqty = $sq[$key][$key2];
                        $poisobj = $poislist[$value][$pon[$key][$key2]][$ln[$key][$key2]];
                        $imobj = $imvolist[$trans[$key][$key2]];
                        $poisobj->set_received_qty($value2);
                        $poisobj->set_reason_code($reason[$key][$key2]);

                        $imobj->set_qty($value2);
                        $imobj->set_status('IN');

                        $ret = $this->supplier_order_model->update_pois($poisobj, array("modify_on <= " => $poisobj->get_modify_on()));
                        $ret2 = $this->supplier_order_model->update_im($imobj, array("modify_on <= " => $imobj->get_modify_on()));

                        if ($ret === FALSE) {
                            $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
                            $serr++;
                            $this->supplier_order_model->trans_rollback();
                            break;
                        }
                        if ($ret2 === FALSE) {
                            $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
                            $serr++;
                            $this->supplier_order_model->trans_rollback();
                            break;
                        }
                        if ($sqty != $value2) {
                            //modify po items and po_shipment when != original shipped qty
                            //change shipped qty
                            //checked for overings / underings via Validation AJAX before passing into this one
                            $diff = $value2 - $sqty;
                            $ret = $this->supplier_order_model->update_po_item_qty($diff, array("po_number" => $pon[$key][$key2], "line_number" => $ln[$key][$key2]));

                            if ($ret === FALSE) {
                                $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
                                $this->supplier_order_model->trans_rollback();
                                $serr++;
                                break;
                            } else {
                                $dis_msg[] = array("im" => $imobj, "pois" => $poisobj);
                            }

                        }
                        $data[] = array("where" => array("po_number" => $pon[$key][$key2], "line_number" => $ln[$key][$key2]), "sqty" => $value2);
                    }
                    if ($serr) {
//                      echo "err4 ";
                        break;
                    }
                    $ssobj = $ssvolist[$value];
                    $ssobj->set_remark($remarks[$key]);
                    $ssobj->set_status('C');

                    $ret3 = $this->supplier_order_model->update_ss($ssobj, array("modify_on <= " => $ssobj->get_modify_on()));
                    if ($ret3 === FALSE) {
//                      echo "err5 ";
                        $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
                        break;
                    } else {
                        if ($ret3 === 0) {
//                          echo "err6 ";
                            $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
                            $this->supplier_order_model->trans_rollback();
                            break;
                        }
                    }

                    foreach ($data as $value) {
                        //$ret = $this->supplier_order_model->update_po_status(array("po_number"=>$pon[$key][$key2],"line_number"=>$ln[$key][$key2]),$value2);
                        $ret = $this->supplier_order_model->update_po_status($value["where"], 1);
                        if ($ret === FALSE) {
//                          echo "err7 ";
                            $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
                            $this->supplier_order_model->trans_rollback();
                            break;
                        }
                    }
                    $this->supplier_order_model->end_transaction();
                }
                unset($data);
            }
            if (count($dis_msg)) {
                $this->purchase_order_service->send_notice($dis_msg);
            }
            Redirect(base_url() . "order/supplier_order/confirm_shipment/" . $wh);
        }

        $_SESSION["LISTPAGE"] = base_url() . "order/supplier_order/confirm_shipment/?" . $_SERVER['QUERY_STRING'];

        $where = array();
        $option = array();

        $s_r = $this->input->get("shipment_id");
        if (!empty($s_r)) {
            $where["ss.shipment_id LIKE "] = '%' . $s_r . '%';
        }

        $p_n = $this->input->get("prod_name");
        if (!empty($p_n)) {
            $where["p.name LIKE "] = '%' . $p_n . '%';
        }

        $s_n = $this->input->get("supplier_name");
        if (!empty($s_n)) {
            $where["s.name LIKE "] = '%' . $s_n . '%';
        }

        $sku = $this->input->get("sku");
        if (!empty($sku)) {
            $where["im.sku LIKE "] = '%' . $sku . '%';
        }

        $tracking_no = $this->input->get("tracking_no");
        if (!empty($tracking_no)) {
            $where["ss.tracking_no LIKE "] = '%' . $tracking_no . '%';
        }


        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        $limit = -1;

        $pconfig['base_url'] = base_url() . "order/supplier_order/confirm_shipment/";
        $option["limit"] = $pconfig['per_page'] = $limit;
        if ($option["limit"]) {
            $option["offset"] = $this->input->get("per_page");
        }

        if (empty($sort))
            $sort = "shipment_id";

        if (empty($order))
            $order = "desc";

        $option["orderby"] = $sort . " " . $order;

        $data = $this->supplier_order_model->get_confirm_list2($wh, $where, $option, "IT");
        if ($data === FALSE && $wh != "") {
            $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
        }


        $_SESSION["ssvolist"] = serialize($data["ssvolist"]);
        $_SESSION["imvolist"] = serialize($data["volist"]);
        $_SESSION["poislist"] = serialize($data["poislist"]);


        $data["lang"] = $lang;

        //$pconfig['total_rows'] = $data['total'];
        //$this->pagination_service->initialize($pconfig);

        $data["wh"] = $wh;
        $data["showcontent"] = $wh == "" ? "0" : "1";
        $data["notice"] = notice($lang);
        $data["syslang"] = $this->_get_lang_id();
        $data["refresh"] = $this->input->get("refresh");
        $data["added"] = $this->input->get("added");
        $data["updated"] = $this->input->get("updated");

        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
//      $data["searchdisplay"] = ($where["po_number"]=="" && $where["supplier"]=="" && $where["supplier_invoice_number"]=="" &&  $where["delivery_mode"]=="" && $where["status"]=="")?'style="display:none"':"";
        $data["searchdisplay"] = "";


        $this->load->view("order/supplier_order/confirm_shipment", $data);

    }

    public function check_outstanding()
    {
        $input = $this->input->get('input');
        $pon = $this->input->get('po_number');
        $ln = $this->input->get('line_number');
        $data["result"] = $this->supplier_order_model->check_overing($input, $pon, $ln);
        $this->load->view("order/supplier_order/check_overing", $data);
    }

    public function gen_shipment_csv($shipment_id)
    {
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"{$shipment_id}.csv\"");
        $this->supplier_order_model->supplier_shipment_service->gen_shipment_csv($shipment_id);
    }

    public function download_csv($wh)
    {
        if ($this->input->get('searchsubmitted')) {
            $where = array();
            $option = array();

            $s_r = $this->input->get("shipment_id");
            if (!empty($s_r)) {
                $where["ss.shipment_id LIKE "] = '%' . $s_r . '%';
            }

            $p_n = $this->input->get("prod_name");
            if (!empty($p_n)) {
                $where["p.name LIKE "] = '%' . $p_n . '%';
            }

            $s_n = $this->input->get("supplier_name");
            if (!empty($s_n)) {
                $where["s.name LIKE "] = '%' . $s_n . '%';
            }

            $sku = $this->input->get("sku");
            if (!empty($sku)) {
                $where["im.sku LIKE "] = '%' . $sku . '%';
            }

            $tracking_no = $this->input->get("tracking_no");
            if (!empty($tracking_no)) {
                $where["ss.tracking_no LIKE "] = '%' . $tracking_no . '%';
            }

            $sort = $this->input->get("sort");
            $order = $this->input->get("order");

            $limit = -1;

            $pconfig['base_url'] = base_url() . "order/supplier_order/confirm_shipment/";
            $option["limit"] = $pconfig['per_page'] = $limit;
            if ($option["limit"]) {
                $option["offset"] = $this->input->get("per_page");
            }

            if (empty($sort))
                $sort = "shipment_id";

            if (empty($order))
                $order = "desc";

            $option["orderby"] = $sort . " " . $order;
            $data = $this->supplier_order_model->get_sh_list($wh, $where, $option, "IT");
        }
        return $this->print_csv($data);
    }

    public function print_csv($data)
    {
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"supplier_shipment_confirmation_list_.csv\"");
        $this->supplier_order_model->supplier_shipment_service->get_csv($data);
    }

}

?>