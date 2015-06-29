<?php
include_once "compensation_create.php";

class Compensation extends Compensation_create
{
    private $app_id = 'CS0004';
    private $lang_id = 'en';

    public function __construct()
    {
        parent::Compensation_create();
    }

    public function index()
    {
        $sub_app_id = $this->_get_app_id()."00";
        //$this->authorization_service->check_access_rights($this->_get_app_id()."05");

        $langfile = $this->_get_app_id()."00_".$this->_get_lang_id().".php";
        include_once APPPATH."language/".$langfile;
        $data["lang"] = $lang;
        $data["app_id"] = $this->_get_app_id();
        $this->load->view('cs/compensation/index', $data);
    }

    public function manager_approval()
    {
        if(check_app_feature_access_right($this->_get_app_id(), "CS000400_man_approve_btn"))
        {
            $sub_app_id = $this->_get_app_id()."05";
            //$this->authorization_service->check_access_rights($sub_app_id);

            if($this->input->get('so') != "")
            {
                $where["so.so_no LIKE"] = "%".$this->input->get('so')."%";
            }

            if($this->input->get('platform_id') != "")
            {
                $where["platform_id"] = $this->input->get('platform_id');
            }

            if($this->input->get('sku') != "")
            {
                $where["p.sku LIKE"] = "%".$this->input->get('sku')."%";
            }

            if($this->input->get('prod_name') != "")
            {
                $where["prod_name"] = $this->input->get('prod_name');
            }

            $sort = $this->input->get('sort');
            if($sort == "")
            {
                $sort = "cp.so_no";
            }

            $order = $this->input->get('order');
            if (empty($order))
            {
                $order = "asc";
            }

            $option["limit"] = $pconfig['per_page'] = 20;

            if ($option["limit"])
            {
                $option["offset"] = $this->input->get("per_page");
            }

            $_SESSION["LISTPAGE"] = base_url()."cs/compensation/manager_approval?".$_SERVER['QUERY_STRING'];
            $_SESSION["QUERY_STRING"] = $_SERVER['QUERY_STRING'];

            $option["orderby"] = $sort." ".$order;

            $langfile = $this->_get_app_id()."04_".$this->_get_lang_id().".php";
            include_once APPPATH."language/".$langfile;

            $data = $this->compensation_model->get_request_compensation_so($where, $option);
            $data["sortimg"][$sort] = "<img src='".base_url()."images/".$order.".gif'>";
            $data["xsort"][$sort] = $order=="asc"?"desc":"asc";
            $option["orderby"] = $sort." ".$order;

            $pconfig['base_url'] = $_SESSION["LISTPAGE"];
            $pconfig['total_rows'] = $data['total'];
            $this->pagination_service->set_show_count_tag(TRUE);
            $this->pagination_service->initialize($pconfig);

            $data["notice"] = notice($lang);
            $data["lang"] = $lang;
            $data["app_id"] = $this->_get_app_id();

            $this->load->view('cs/compensation/index_approval',$data);
        }
        else
        {
            show_error("Access Denied!");
        }
    }

    public function manager_approval_view($compensation_id = "", $orderid = "")
    {
        if(check_app_feature_access_right($this->_get_app_id(), "CS000400_man_approve_btn"))
        {
            if($orderid == "")
            {
                Redirect(base_url()."cs/compensation/manager_approval/");
                exit;
            }

            $sub_app_id = $this->_get_app_id()."05";
            $this->authorization_service->check_access_rights($sub_app_id, "Approve");

            if($this->input->post('posted'))
            {
                if($this->input->post('action') == 'A')
                {
                    $so_obj = $this->compensation_model->get_so(array("so_no"=>$orderid));
                    $cp_obj = $this->compensation_model->get_compensation(array("id"=>$compensation_id), array("limit"=>1));
                    $cp_sku = $cp_obj->get_item_sku();

                    $err = 0;

                    $pobj = $this->price_service->get_dao()->get_list_with_bundle_checking($cp_sku, $so_obj->get_platform_id(), "Product_cost_dto", 0, $so_obj->get_lang_id());
                    foreach($pobj as $new_obj)
                    {
                        $this->price_service->calc_logistic_cost($new_obj);
                        $this->price_service->calculate_profit($new_obj);

                        // update so_item
                        $soi_num_rows = $this->compensation_model->so_service->get_soi_dao()->get_num_rows(array("so_no"=>$orderid));
                        $new_soi_obj = $this->compensation_model->so_service->get_soi_dao()->get();
                        $new_soi_obj->set_so_no($orderid);
                        $new_soi_obj->set_line_no($soi_num_rows+1);
                        $new_soi_obj->set_prod_sku($new_obj->get_sku());
                        $new_soi_obj->set_prod_name($new_obj->get_prod_name());
                        $new_soi_obj->set_qty(1);
                        $new_soi_obj->set_unit_price($new_obj->get_price());
                        $new_soi_obj->set_vat_total($new_obj->get_vat());
                        $new_soi_obj->set_amount(0);
                        $new_soi_obj->set_gst_total(0);
//set warranty and website status
                        $prod_obj = $this->product_service->get(array("sku" => $new_obj->get_sku()));
                        $new_soi_obj->set_warranty_in_month($prod_obj->get_warranty_in_month());
                        $new_soi_obj->set_website_status($prod_obj->get_website_status());

                        // update_so_item_detail
                        $new_soid_obj = $this->compensation_model->so_service->get_soid_dao()->get();
                        $new_soid_obj->set_so_no($orderid);
                        $new_soid_obj->set_line_no($soi_num_rows+1);
                        $new_soid_obj->set_item_sku($new_obj->get_sku());
                        $new_soid_obj->set_qty(1);
                        $new_soid_obj->set_outstanding_qty(1);
                        $new_soid_obj->set_unit_price($new_obj->get_price());
                        $new_soid_obj->set_vat_total($new_obj->get_vat());
                        $new_soid_obj->set_amount(0);
                        $new_soid_obj->set_cost($new_obj->get_cost());
                        $new_soid_obj->set_profit(0-$new_obj->get_cost());
                        $new_soid_obj->set_margin(0); // margin is set to zero as requested in sbf #1381
                        $new_soid_obj->set_status(0);
                        $new_soid_obj->set_gst_total(0);

                        $this->compensation_model->_trans_start();
                        if($this->compensation_model->so_service->get_soi_dao()->insert($new_soi_obj) === FALSE)
                        {
                            $err++;
                        }
                        if($this->compensation_model->so_service->get_soid_dao()->insert($new_soid_obj) === FALSE)
                        {
                            $err++;
                        }

                        if(!$err)
                        {
                            // update so
                            $new_so_cost = $so_obj->get_cost() + $new_obj->get_cost();
                            $so_obj->set_cost($new_so_cost);
                            $so_obj->set_hold_status(0);
                            $ret = $this->compensation_model->update_so($so_obj);
                            if($ret === FALSE)
                            {
                                $err++;
                            }

                            if(!$err)
                            {
                                $cp_obj = $this->compensation_model->get_compensation(array("id"=>$compensation_id), array("limit"=>1));
                                $cp_obj->set_status(2);
                                if($this->compensation_model->update_compensation($cp_obj) === FALSE)
                                {
                                    $err++;
                                }
                                $cph_obj = $this->compensation_model->get_history();
                                $cph_obj->set_compensation_id($cp_obj->get_id());
                                $cph_obj->set_so_no($orderid);
                                $cph_obj->set_item_sku($cp_obj->get_item_sku());
                                $cph_obj->set_note($this->input->post('cnotes'));
                                $cph_obj->set_status(2);
                                if ($this->compensation_model->insert_history($cph_obj) === FALSE)
                                {
                                    $err++;
                                }

                                $note_obj = $this->order_notes_service->get();
                                $note_obj->set_so_no($orderid);
                                $note_obj->set_type("O");
                                $note_obj->set_note("Compensation Approved - Added " . $new_obj->get_sku());
                                if ($this->order_notes_service->insert($note_obj) === FALSE)
                                {
                                    $err++;
                                }

                                $so_obj = $this->compensation_model->get_so(array("so_no"=>$orderid));
                                $so_obj->set_hold_status(0);
                                if($this->compensation_model->update_so($so_obj) === FALSE)
                                {
                                    $err++;
                                }
                            }
                        }

                        if($err)
                        {
                            $this->compensation_model->so_compensation_service->get_dao()->trans_rollback();
                            $this->compensation_model->_trans_complete();
                            $_SESSION["NOTICE"] = "update_fail";
                        }
                        else
                        {
                            $this->compensation_model->_trans_complete();
                        }
                    }

                    Redirect(base_url()."cs/compensation/manager_approval/");
                }

                if($this->input->post('action') == 'D')
                {
                    $err = 0;

                    $cp_obj = $this->compensation_model->get_compensation(array("id"=>$compensation_id), array("limit"=>1));
                    $cp_obj->set_status(0);
                    if(!$this->compensation_model->update_compensation($cp_obj))
                    {
                        $err++;
                    }
                    $cph_obj = $this->compensation_model->get_history();
                    $cph_obj->set_compensation_id($cp_obj->get_id());
                    $cph_obj->set_so_no($orderid);
                    $cph_obj->set_item_sku($cp_obj->get_item_sku());
                    $cph_obj->set_note($this->input->post('cnotes'));
                    $cph_obj->set_status(0);
                    if (!$this->compensation_model->insert_history($cph_obj))
                    {
                        $err++;
                    }
                    if(!$err)
                    {
                        $so_obj = $this->compensation_model->get_so(array("so_no"=>$orderid));
                        $so_obj->set_hold_status(0);
                        if(!$this->compensation_model->update_so($so_obj))
                        {
                            $err++;
                        }
                    }

                    if($err)
                    {
                        $_SESSION["NOTICE"] = "update_fail";
                    }
                    else
                    {
                        if($reject_reason = $this->input->post('cnotes'))
                        {
                            $note_obj = $this->order_notes_service->get();
                            $note_obj->set_so_no($orderid);
                            $note_obj->set_type("O");
                            $note_obj->set_note("Compensation Rejected - Reason: " . $reject_reason);
                            if ($this->order_notes_service->insert($note_obj) === FALSE)
                            {
                                $err++;
                            }

                            $notice_email = $this->compensation_model->get_notification_email($compensation_id);
                            mail($notice_email, "[VB] Compensation Rejected - Order ID " . $orderid,
                                "Reject Reason: " . $reject_reason, 'From: itsupport@eservicesgroup.net');
                        }
                    }
                    Redirect(base_url()."cs/compensation/manager_approval/");
                }
            }

            $cp_obj = $this->compensation_model->get_compensation(array("so_no"=>$orderid,"status"=>"1"));

            if(empty($cp_obj) || $cp_obj->get_so_no() == "")
            {
                Redirect(base_url()."cs/compensation/manager_approval/");
            }

            $history_list = $this->compensation_model->get_history_list(array("so_no"=>$cp_obj->get_so_no()));
            $data["history"] = $history_list;

            $order_item_list = $this->compensation_model->get_item_list(array("so_no"=>$cp_obj->get_so_no()));
            $data["order_item_list"] = $order_item_list;
            $data["orderobj"] = $so = $this->compensation_model->get_so(array("so_no"=>$cp_obj->get_so_no()));
            $data["compensate_obj"] = $this->compensation_model->get_order_compensated_item(array("so.so_no"=>$so->get_so_no(), "so.platform_id"=>$so->get_platform_id()), array("limit"=>1));

            $langfile = $this->_get_app_id()."05_".$this->_get_lang_id().".php";
            include_once APPPATH."language/".$langfile;

            $data["notice"] = notice($lang);
            $data["lang"] = $lang;

            $this->load->view('cs/compensation/view_manager_approval',$data);
        }
        else
        {
            show_error("Access Denied!");
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
}

?>