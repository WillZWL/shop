<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_batch_service.php";

class Batch_tracking_info_service extends Base_batch_service
{

    private $itinfo_dao;
    private $tlog_dao;
    private $batch_dao;

    function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Batch_dao.php");
        $this->set_dao(new Batch_dao());
        include_once(APPPATH . "libraries/dao/Transmission_log_dao.php");
        $this->set_tlog_dao(new Transmission_log_dao());
        include_once(APPPATH . "libraries/service/Validation_service.php");
        $this->set_valid(new Validation_service());
        include_once(APPPATH . "libraries/service/Data_exchange_service.php");
        $this->set_dex(new Data_exchange_service());
        include_once(APPPATH . "libraries/dao/Interface_tracking_info_dao.php");
        $this->set_itinfo_dao(new Interface_tracking_info_dao());
        include_once(APPPATH . "libraries/service/So_service.php");
        $this->set_so_service(new So_service());
    }

    public function set_valid($value)
    {
        $this->valid = $value;
    }

    public function set_dex($value)
    {
        $this->dex = $value;
    }

    public function set_so_service($serv)
    {
        $this->so_service = $serv;
    }

    public function cron_tracking_info($wh)
    {
        $func = "tracking_info_" . $wh;

        $path = rtrim($this->get_config()->value_of($func . "_path"), '/') . '/';

        $no_file = 0;
        if ($file_list = glob($path . "*")) {
            foreach ($file_list as $filename) {
                if (!is_file($filename)) {
                    continue;
                }
                $filename = basename($filename);
                $_SESSION["result"] .= "<br>checking {$wh} trackingfile: {$filename}<br>";
                $dao = $this->get_dao();
                $tlog_dao = $this->get_tlog_dao();
                $tlog_vo = $tlog_dao->get();
                $batch_vo = $dao->get();

                $valid = $this->get_valid();
                $tlog_obj = clone $tlog_vo;
                $batch_obj = $dao->get(array("remark" => $filename));
                $success = 1;
                if (!empty($batch_obj)) {
                    $tlog_obj->set_func_name($func);
                    $tlog_obj->set_message($filename . " already_in_batch");
                    $tlog_dao->insert($tlog_obj);
                    $_SESSION["result"] .= "<font color='red'> -> {$filename}: already_in_batch</font><br>";
                    $result[$no_file] = $filename;
                    if (copy($path . $filename, $path . "fail/" . $filename)) {
                        $result[$no_file] = array("filename" => $filename, "reason" => "already in batch", "success" => "0");
                        unlink($path . $filename);
                    }
                } else {
                    switch ($wh) {
                        case "ams":
                            $arr = $this->get_valid()->check_field($path . $filename, 1, 18);
                            break;
                        default:
                            $arr = $this->get_valid()->check_field($path . $filename, 1);
                            break;
                    }
                    if ($arr) {
                        $rules[0] = array("not_empty");//shipment_number
                        $rules[1] = array("not_empty");//so_number
                        //$rules[2]=array("not_empty");//order_number
                        $rules[2] = array("not_empty");//status
                        //                  $rules[3]=array("not_empty");//tracking_number
                        //                  $rules[4]=array("not_empty");//ship_method
                        //                  $rules[5]=array("not_empty");//courier_id
                        //                  $rules[6]=array("not_empty");//dispatch_date
                        //                  $rules[7]=array("not_empty", "is_number");//weight
                        //                  $rules[8]=array("not_empty");//consignee
                        //                  $rules[9]=array();//postcode
                        //                  $rules[10]=array("not_empty");//country
                        //                  $rules[11]=array("not_empty", "is_number","min=0");//amount
                        //                  $rules[12]=array("not_empty");//currency
                        //                  $rules[13]=array("not_empty");//charge_out
                        //                  $rules[14]=array("not_empty");//qty
                        $valid->set_rules($rules);

                        for ($i = 0; $i < count($arr); $i++) {
                            //$arr[$i][]= explode(",", $arr[$i]);
                            $valid->set_data($arr[$i]);
                            $rs = FALSE;
                            try {
                                $rs = $valid->run();
                            } catch (Exception $e) {
                                $tlog_obj->set_func_name($func);
                                $tlog_obj->set_message($e->getMessage());
                                $_SESSION["result"] .= "<font color='red'> -> {$filename}: " . $tlog_obj->get_message() . "</font><br>";
                                $tlog_dao->insert($tlog_obj);
                            }
                            if (!$rs) {
                                $success = 0;
                            }
                        }
                        if ($success) {
                            $batch_obj = clone $batch_vo;
                            $batch_obj->set_func_name($func);
                            $batch_obj->set_status("N");
                            $batch_obj->set_listed(1);
                            $batch_obj->set_remark($filename);
                            $dao->insert($batch_obj);
                            //echo $filename." uploaded success<br>";
                            //if (copy($path.$filename, $path."/success/".$filename))
                            //{
                            $result[$no_file] = array("filename" => $filename, "reason" => "upload success", "success" => "1");
                            $_SESSION["result"] .= " -> {$filename}: checking done<br>";
                            //  unlink($path.$filename);
                            //}
                        } else {
                            //echo $filename." uploaded fail<br>";
                            if (copy($path . $filename, $path . "fail/" . $filename)) {
                                $result[$no_file] = array("filename" => $filename, "reason" => "file format not match the requirement", "success" => "0");
                                $_SESSION["result"] .= "<font color='red'>  -> {$filename}: file format not match the requirement</font><br>";
                                unlink($path . $filename);
                            }
                        }
                    } else {
                        //echo $filename." uploaded fail<br>";
                        $tlog_obj->set_func_name($func);
                        $tlog_obj->set_message("Number of field not match");
                        $tlog_dao->insert($tlog_obj);
                        $_SESSION["result"] .= "<font color='red'>  -> {$filename}: Number of field not match</font><br>";
                        if (copy($path . $filename, $path . "fail/" . $filename)) {
                            $result[$no_file] = array("filename" => $filename, "reason" => "number of field not match", "success" => "0");
                            unlink($path . $filename);
                        }
                    }
                }
                $no_file++;
            }
            if (!$no_file) {
                $_SESSION["result"] .= "<br>No {$wh} trackingfile found!<br>";
            }
            $this->batch_tracking_info($wh);
        } else {
            $_SESSION["result"] .= "<br>No {$wh} trackingfile found!<br>";
        }
    }

    public function get_tlog_dao()
    {
        return $this->tlog_dao;
    }

    public function set_tlog_dao(Base_dao $dao)
    {
        $this->tlog_dao = $dao;
    }

    public function get_valid()
    {
        return $this->valid;
    }

    public function batch_tracking_info($wh)
    {
        $func = "tracking_info_" . $wh;

        $local_path = rtrim($this->get_config()->value_of($func . "_path"), '/') . '/';

        $dao = $this->get_dao();
        $valid = $this->get_valid();
        $dex = $this->get_dex();

        $CI =& get_instance();
        $CI->load->helper('url');

        $itinfo_dao = $this->get_itinfo_dao();

        include_once(APPPATH . "libraries/dao/So_shipment_dao.php");
        $sosh_dao = new So_shipment_dao();
        include_once(APPPATH . "libraries/dao/So_allocate_dao.php");
        $soal_dao = new So_allocate_dao();
        include_once(APPPATH . "libraries/dao/So_dao.php");
        $so_dao = new So_dao();
        $objlist = $dao->get_list(array("func_name" => $func, "status" => "N"));
        if ($objlist) {
            foreach ($objlist as $obj) {
                $filename = $obj->get_remark();
                $_SESSION["result"] .= "<br>importing {$wh} trackingfile: {$filename}<br>";
                $success = 1;
                $batch_id = $obj->get_id();
                switch ($wh) {
                    default:
                        $obj_csv = new Csv_to_xml($local_path . $obj->get_remark(), APPPATH . 'data/tracking_info.txt', TRUE, ",", TRUE);
                        break;
                }
                $out_vo = new Xml_to_vo();
                $output = $dex->convert($obj_csv, $out_vo);
                if ($output) {
                    foreach ($output as $itinfo_obj) {
                        $itinfo_obj->set_batch_id($batch_id);
                        $itinfo_obj->set_batch_status("N");
                        if ($itinfo_dao->insert($itinfo_obj) !== FALSE) {
                            $intinfo_objlist[] = $itinfo_obj;
                        }
                    }
                    $sosh_list = $sosh_dao->get_tracking_info_list(array("batch_id" => $batch_id));
                    $sosh_data = array();
                    foreach ($sosh_list as $list) {
                        $sh_no = $list->get_sh_no();
                        $sosh_data["sh_no"][$sh_no] = 1;
                        $sosh_data["tracking_no"][$sh_no] = $list->get_tracking_no();
                        $sosh_data["courier_id"][$sh_no] = $list->get_courier_id();
                    }
                    $valid->set_exists_in(array("sh_no" => $sosh_data["sh_no"]));
                    $obj->set_status("P");
                    $dao->update($obj);
                    foreach ($intinfo_objlist as $itinfo_obj) {
                        $rules["sh_no"] = array("exists_in=sh_no");
                        $valid->set_rules($rules);
                        $valid->set_data($itinfo_obj);
                        $rs = FALSE;
                        try {
                            $rs = $valid->run();
                        } catch (Exception $e) {
                            //echo "&nbsp;".$e->getMessage()."<br>";
                            $itinfo_obj->set_failed_reason($e->getMessage());
                            $_SESSION["result"] .= "<font color='red'>  -> {$filename} import error: " . $itinfo_obj->get_failed_reason() . "</font><br>";
                        }
                        $sosh_datalist = $sosh_dao->get(array("sh_no" => $itinfo_obj->get_sh_no()));

                        if ($rs) {
                            $sosh_datalist->set_courier_id($itinfo_obj->get_courier_id());
                            $sosh_datalist->set_tracking_no($itinfo_obj->get_tracking_no());
                            //echo "&nbsp;".$sosh_datalist->get_sh_no()." updated success<br>";
                            $sosh_datalist->set_status("2");
                            $sosh_dao->update($sosh_datalist);
                            $so_list = $so_dao->get(array("so_no" => $itinfo_obj->get_so_no()));

                            $need_update = 0;

                            if ($so_list->get_dispatch_date() == NULL) {
                                $so_list->set_dispatch_date(date("Y-m-d H:i:s"));
                                $need_update = 1;
                            }

                            $need_to_send_dispatch = FALSE;
                            if ($so_list->get_status() != 6) {
                                $so_list->set_status("6");
                                $need_update = 1;
                                $need_to_send_dispatch = TRUE;
                            }

                            if ($need_update) {
                                $so_dao->update($so_list);
                                if ($need_to_send_dispatch)
                                    $this->get_so_service()->fire_dispatch($so_list, $itinfo_obj->get_sh_no());
                            }

                            $soal_list = $soal_dao->get(array("sh_no" => $itinfo_obj->get_sh_no()));
                            $soal_list->set_status("3");
                            $soal_dao->update($soal_list);
                            $itinfo_obj->set_batch_status("S");

                            if ($so_list->get_biz_type() == 'SPECIAL') {
                                $special_orders[] = $so_list->get_so_no();
                            }

                        } else {
                            $itinfo_obj->set_batch_status("F");
                            $success = 0;
                        }
                        $itinfo_dao->update($itinfo_obj);
                    }
                    if ($success) {
                        if ($special_orders) {
                            foreach ($special_orders as $key => $so_no) {
                                $so_w_reason = $this->so_service->get_dao()->get_so_w_reason(array('so.so_no' => $so_no), array('limit' => 1));

                                if ($so_w_reason->get_reason_id() == '34') {
                                    $aps_direct_order[] = $so_w_reason->get_so_no();
                                }

                            }

                            $aps_direct_orders = implode(',', $aps_direct_order);
                            $where = "where so.so_no in (" . $aps_direct_orders . ")";
                            $content = $this->so_service->get_dao()->get_aps_direct_order_csv($where);

                            file_put_contents('/tmp/debug_email', $content);

                            include_once(BASEPATH . "plugins/phpmailer/phpmailer_pi.php");
                            $phpmail = new phpmailer();

                            $phpmail->IsSMTP();
                            $phpmail->From = "VB APS ORDER ALERT <do_not_reply@valuebasket.com>";
                            $phpmail->AddAddress("bd.platformteam@eservicesgroup.net");
                            //$phpmail->AddAddress("nicolove.ni@eservicesgroup.com");

                            $phpmail->Subject = " DIRECT APS ORDERS";
                            $phpmail->IsHTML(false);
                            $phpmail->Body = "Attached: DIRECT APS ORDERS.";
                            $phpmail->AddStringAttachment($content, "direct_aps_info.csv");
                            $result = $phpmail->Send();
                        }

                        if (copy($local_path . $obj->get_remark(), $local_path . "success/" . $obj->get_remark())) {
                            unlink($local_path . $obj->get_remark());
                        }
                        $obj->set_status("C");
                        $obj->set_end_time(date("Y-m-d H:i:s"));
                        $_SESSION["result"] .= "<font color='green'>  -> {$filename} import completed, click <a href='" . base_url() . "integration/integration/view/tracking_info/" . $obj->get_id() . "' target='integration'>here</a> for detail</font><br>";
                    } else {
                        if (copy($local_path . $obj->get_remark(), $local_path . "complete_with_error/" . $obj->get_remark())) {
                            unlink($local_path . $obj->get_remark());
                        }
                        $obj->set_status("CE");
                        $obj->set_end_time(date("Y-m-d H:i:s"));
                        $_SESSION["result"] .= "<font color='blue'>  -> {$filename} import completed with error, click <a href='" . base_url() . "integration/integration/view/tracking_info/" . $obj->get_id() . "' target='integration'>here</a> for detail</font><br>";
                    }
                    $dao->update($obj);
                } else {
                    $_SESSION["result"] .= "<font color='red'>  -> {$filename} import error: " . __LINE__ . "</font><br>";
                }
            }
        }
    }

    public function get_dex()
    {
        return $this->dex;
    }

    public function get_itinfo_dao()
    {
        return $this->itinfo_dao;
    }

    public function set_itinfo_dao(Base_dao $dao)
    {
        $this->itinfo_dao = $dao;
    }

    public function get_so_service()
    {
        return $this->so_service;
    }

    public function get_batch_dao()
    {
        return $this->batch_dao;
    }

    public function set_batch_dao(Base_dao $dao)
    {
        $this->batch_dao = $dao;
    }

}


/* End of file batch_tracking_info_service.php */
/* Location: ./system/application/libraries/service/Batch_tracking_info_service.php */
