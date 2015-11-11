<?php
include_once "Compensation_create.php";

class Compensation extends Compensation_create
{
    private $appId = 'CS0004';
    private $lang_id = 'en';

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $sub_app_id = $this->getAppId() . "00";
        //$this->sc['Authorization']->checkAccessRights($this->getAppId()."05");

        $langfile = $this->getAppId() . "00_" . $this->getLangId() . ".php";
        include_once APPPATH . "language/" . $langfile;
        $data["lang"] = $lang;
        $data["app_id"] = $this->getAppId();
        $this->load->view('cs/compensation/index', $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function manager_approval($offset = 0)
    {
        if (check_app_feature_access_right($this->getAppId(), "CS000400_man_approve_btn")) {
            $sub_app_id = $this->getAppId() . "05";
            //$this->sc['Authorization']->checkAccessRights($sub_app_id);

            if ($this->input->get('so') != "") {
                $where["so.so_no LIKE"] = "%" . $this->input->get('so') . "%";
            }

            if ($this->input->get('platform_id') != "") {
                $where["platform_id"] = $this->input->get('platform_id');
            }

            if ($this->input->get('sku') != "") {
                $where["p.sku LIKE"] = "%" . $this->input->get('sku') . "%";
            }

            if ($this->input->get('prod_name') != "") {
                $where["prod_name"] = $this->input->get('prod_name');
            }

            $sort = $this->input->get('sort');
            if ($sort == "") {
                $sort = "cp.so_no";
            }

            $order = $this->input->get('order');
            if (empty($order)) {
                $order = "asc";
            }

            $limit = 20;
            $option["limit"] = $limit;
            $option["offset"] = $offset;

            $_SESSION["LISTPAGE"] = base_url() . "cs/compensation/manager_approval?" . $_SERVER['QUERY_STRING'];
            $_SESSION["QUERY_STRING"] = $_SERVER['QUERY_STRING'];

            $option["orderby"] = $sort . " " . $order;

            $langfile = $this->getAppId() . "04_" . $this->getLangId() . ".php";
            include_once APPPATH . "language/" . $langfile;

            $data['list'] = $this->sc['So']->getDao('SoCompensation')->getCompensationSoList($where, $option);

            $data['total'] = $this->sc['So']->getDao('SoCompensation')->getCompensationSoList($where, array_merge($option, ['num_rows'=>1]));

            $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
            $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";

            $config['base_url'] = base_url('cs/compensation/manager_approval');
            $config['total_rows'] = $data["total"];
            $config['per_page'] = $limit;
            $this->pagination->initialize($config);
            $data['links'] = $this->pagination->create_links();


            $data["notice"] = notice($lang);
            $data["lang"] = $lang;
            $data["app_id"] = $this->getAppId();

            $this->load->view('cs/compensation/index_approval', $data);
        } else {
            show_error("Access Denied!");
        }
    }

    public function manager_approval_view($compensation_id = "", $orderid = "")
    {
        if (check_app_feature_access_right($this->getAppId(), "CS000400_man_approve_btn")) {
            if ($orderid == "") {
                Redirect(base_url() . "cs/compensation/manager_approval/");
                exit;
            }

            $sub_app_id = $this->getAppId() . "05";
            $this->sc['Authorization']->checkAccessRights($sub_app_id, "Approve");

            if ($this->input->post('posted')) {
                if ($this->input->post('action') == 'A') {
                    $so_obj = $this->sc['So']->getDao('So')->get(["so_no" => $orderid]);
                    $cp_obj = $this->sc['So']->getDao('SoCompensation')->get(["id" => $compensation_id]);
                    $cp_sku = $cp_obj->getItemSku();

                    $err = 0;

                    $pobj = $this->sc['Price']->getDao('Price')->getListWithBundleChecking($cp_sku, $so_obj->getPlatformId(), $so_obj->getLangId());
                    foreach ($pobj as $new_obj) {
                        $this->sc['Price']->calcLogisticCost($new_obj);
                        $this->sc['Price']->calculateProfit($new_obj);

                        // update_so_item_detail
                        $soi_num_rows = $this->sc['So']->getDao('SoItemDetail')->getNumRows(["so_no" => $orderid]);
                        $new_soid_obj = $this->sc['So']->getDao('SoItemDetail')->get();
                        $new_soid_obj->setSoNo($orderid);
                        $new_soid_obj->setLineNo($soi_num_rows + 1);
                        $new_soid_obj->setItemSku($new_obj->getSku());
                        $new_soid_obj->setQty(1);
                        $new_soid_obj->setOutstandingQty(1);
                        $new_soid_obj->setUnitPrice($new_obj->getPrice());
                        $new_soid_obj->setVatTotal($new_obj->getVat());
                        $new_soid_obj->setAmount(0);
                        $new_soid_obj->setCost($new_obj->getCost());
                        $new_soid_obj->setProfit(0 - $new_obj->getCost());
                        $new_soid_obj->setMargin(0); // margin is set to zero as requested in sbf #1381
                        $new_soid_obj->setStatus(0);
                        $new_soid_obj->setGstTotal(0);

                        $this->sc['So']->getDao('SoCompensation')->db->trans_start();

                        if ($this->sc['So']->getDao('SoItemDetail')->insert($new_soid_obj) === FALSE) {
                            $err++;
                        }

                        if (!$err) {
                            // update so
                            $new_so_cost = $so_obj->getCost() + $new_obj->getCost();
                            $so_obj->setCost($new_so_cost);
                            if ($so_obj->getHoldStatus() <> 0) {
                                $update_hold_status = true;
                                $holdStatus = 0;
                            }
                            $so_obj->setHoldStatus(0);
                            $ret = $this->sc['So']->getDao('So')->update($so_obj);
                            if ($ret === FALSE) {
                                $err++;
                            }
                            if ($update_hold_status) {
                                $this->sc['So']->updateIofHoldStatusBySo($so_no, $holdStatus);
                            }

                            if (!$err) {
                                $cp_obj = $this->sc['So']->getDao('SoCompensation')->get(["id" => $compensation_id]);
                                $cp_obj->setStatus(2);
                                if ($this->sc['So']->getDao('SoCompensation')->update($cp_obj) === FALSE) {
                                    $err++;
                                }
                                $cph_obj = $this->sc['So']->getDao('SoCompensationHistory')->get();
                                $cph_obj->setCompensationId($cp_obj->getId());
                                $cph_obj->setSoNo($orderid);
                                $cph_obj->setItemSku($cp_obj->getItemSku());
                                $cph_obj->setNote($this->input->post('cnotes'));
                                $cph_obj->setStatus(2);
                                if ($this->sc['So']->getDao('SoCompensationHistory')->insert($cph_obj) === FALSE) {
                                    $err++;
                                }

                                $note_obj = $this->sc['So']->getDao('OrderNotes')->get();
                                $note_obj->setSoNo($orderid);
                                $note_obj->setType("O");
                                $note_obj->setNote("Compensation Approved - Added " . $new_obj->getSku());
                                if ($this->sc['So']->getDao('OrderNotes')->insert($note_obj) === FALSE) {
                                    $err++;
                                }

                                $so_obj = $this->sc['So']->getDao('So')->get(["so_no" => $orderid]);
                                if ($so_obj->getHoldStatus() <> 0) {
                                    $update_hold_status = true;
                                    $holdStatus = 0;
                                }
                                $so_obj->setHoldStatus(0);
                                if ($this->sc['So']->getDao('So')->update($so_obj) === FALSE) {
                                    $err++;
                                }
                                if ($update_hold_status) {
                                    $this->sc['So']->updateIofHoldStatusBySo($so_no, $holdStatus);
                                }
                            }
                        }

                        if ($err) {
                            $this->sc['So']->getDao('SoCompensation')->db->trans_rollback();
                            $this->sc['So']->getDao('SoCompensation')->db->trans_complete();
                            $_SESSION["NOTICE"] = "update_fail";
                        } else {
                            $this->sc['So']->getDao('SoCompensation')->db->trans_complete();
                        }
                    }

                    Redirect(base_url() . "cs/compensation/manager_approval/");
                }

                if ($this->input->post('action') == 'D') {
                    $err = 0;

                    $cp_obj = $this->sc['So']->getDao('SoCompensation')->get(["id" => $compensation_id]);
                    $cp_obj->setStatus(0);
                    if (!$this->sc['So']->getDao('SoCompensation')->update($cp_obj)) {
                        $err++;
                    }
                    $cph_obj = $this->sc['So']->getDao('SoCompensationHistory')->get();
                    $cph_obj->setCompensationId($cp_obj->getId());
                    $cph_obj->setSoNo($orderid);
                    $cph_obj->setItemSku($cp_obj->getItemSku());
                    $cph_obj->setNote($this->input->post('cnotes'));
                    $cph_obj->setStatus(0);
                    if (!$this->sc['So']->getDao('SoCompensationHistory')->insert($cph_obj)) {
                        $err++;
                    }
                    if (!$err) {
                        $so_obj = $this->sc['So']->getDao('So')->get(["so_no" => $orderid]);
                        if ($so_obj->getHoldStatus() <> 0) {
                            $update_hold_status = true;
                            $holdStatus = 0;
                        }
                        $so_obj->setHoldStatus(0);
                        if (!$this->sc['So']->getDao('So')->update($so_obj)) {
                            $err++;
                        }
                        if ($update_hold_status) {
                            $this->sc['So']->updateIofHoldStatusBySo($so_no, $holdStatus);
                        }
                    }

                    if ($err) {
                        $_SESSION["NOTICE"] = "update_fail";
                    } else {
                        if ($reject_reason = $this->input->post('cnotes')) {
                            $note_obj = $this->sc['So']->getDao('OrderNotes')->get();
                            $note_obj->setSoNo($orderid);
                            $note_obj->setType("O");
                            $note_obj->setNote("Compensation Rejected - Reason: " . $reject_reason);
                            if ($this->sc['So']->getDao('OrderNotes')->insert($note_obj) === FALSE) {
                                $err++;
                            }

                            $notice_email = $this->sc['So']->getDao('SoCompensationHistory')->getNotificationEmail($compensation_id);
                            $subject = "[VB] Compensation Rejected - Order ID " . $orderid;
                            $message = "Reject Reason: " . $reject_reason;
                            mail($notice_email, $subject, $message, 'From: itsupport@eservicesgroup.net');
                        }
                    }
                    Redirect(base_url() . "cs/compensation/manager_approval/");
                }
            }

            $cp_obj = $this->sc['So']->getDao('SoCompensation')->get(["so_no" => $orderid, "status" => "1"]);

            if (empty($cp_obj) || $cp_obj->getSoNo() == "") {
                Redirect(base_url() . "cs/compensation/manager_approval/");
            }

            $history_list = $this->sc['So']->getDao('SoCompensationHistory')->getList(["so_no" => $cp_obj->getSoNo()],['limit'=>-1]);
            $data["history"] = $history_list;

            $order_item_list = $this->sc['So']->getDao('SoItemDetail')->getListWithProdname(["so_no" => $cp_obj->getSoNo()], ["sortby" => "line_no ASC"]);
            $data["order_item_list"] = $order_item_list;
            $data["orderobj"] = $so = $this->sc['So']->getDao('So')->get(["so_no" => $cp_obj->getSoNo()]);
            $data["compensate_obj"] = $this->sc['So']->getDao('SoCompensation')->getOrderCompensatedItem(["so.so_no" => $so->getSoNo(), "so.platform_id" => $so->getPlatformId()], ["limit" => 1]);

            $langfile = $this->getAppId() . "05_" . $this->getLangId() . ".php";
            include_once APPPATH . "language/" . $langfile;

            $data["notice"] = notice($lang);
            $data["lang"] = $lang;

            $this->load->view('cs/compensation/view_manager_approval', $data);
        } else {
            show_error("Access Denied!");
        }
    }
}
