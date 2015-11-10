<?php

include_once "Base_service.php";

class Webuploader_service extends Base_service
{

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Batch_dao.php");
        $this->set_batch_dao(new Batch_dao());
        include_once(APPPATH . "libraries/dao/Transmission_log_dao.php");
        $this->set_tlog_dao(new Transmission_log_dao());
        include_once(APPPATH . "libraries/service/Context_config_service.php");
        $this->set_config(new Context_config_service());
        include_once(APPPATH . "libraries/service/Validation_service.php");
        $this->set_valid(new Validation_service());
        include_once(APPPATH . "libraries/service/Event_service.php");
        $this->set_event(new Event_service());
        include_once(APPPATH . "libraries/service/Data_exchange_service.php");
        $this->set_dex(new Data_exchange_service());
        include_once(APPPATH . "libraries/service/So_service.php");
        $this->set_so_svc(new So_service());
    }

    public function set_batch_dao(Base_dao $dao)
    {
        $this->batch_dao = $dao;
    }

    public function set_tlog_dao(Base_dao $dao)
    {
        $this->tlog_dao = $dao;
    }

    public function set_config($value)
    {
        $this->config = $value;
    }

    public function set_valid($value)
    {
        $this->valid = $value;
    }

    public function set_event($value)
    {
        $this->event = $value;
    }

    public function set_dex($value)
    {
        $this->dex = $value;
    }

    public function set_so_svc($value)
    {
        $this->so_svc = $value;
    }

    public function dpd_checker($input = array())
    {
        /*  requirement:
            max_size: 5MB;
            file_type: .TXT (comma delimited)
        */
        $pass = 1;
        $reason = "";

        if ($input["size"] > 5 * 1024 * 1024) {
            $reason = "file_too_large";
            $pass = 0;
        } else {
            $filearr = explode(".", $input["name"]);
            if (!in_array(strtolower($filearr[count($filearr) - 1]), array("txt", "csv"))) {
                $reason = "invalid_file_format";
                $pass = 0;
            }
        }

        return array("status" => $pass, "reason" => $reason);
    }

    public function metapack_checker($input = array())
    {
        /*  requirement:
            max_size: 5MB;
            file_type: .TXT (comma delimited)
        */
        $pass = 1;
        $reason = "";

        if ($input["size"] > 5 * 1024 * 1024) {
            $reason = "file_too_large";
            $pass = 0;
        } else {
            $filearr = explode(".", $input["name"]);
            if (!in_array(strtolower($filearr[count($filearr) - 1]), array("txt", "csv"))) {
                $reason = "invalid_file_format";
                $pass = 0;
            }
        }

        return array("status" => $pass, "reason" => $reason);
    }

    public function dpd_processor($input = array())
    {

        $func = "dpd_process";

        $tlog_vo = $this->get_tlog_dao()->get();

        $tlog_dao = $this->get_tlog_dao();

        $valid = $this->get_valid();

        if ($fp = @fopen($input["tmp_name"], "r")) {
            $skip = 0;
            $rules = array();
            $rules[0] = array("not_empty");
            $rules[1] = array("not_empty");
            $rules[3] = array("not_empty");
            $rules[4] = array("not_empty");
            $rules[9] = array("not_empty");
            $valid->set_rules($rules);

            while (($line = fgetcsv($fp, 5000, ",")) && !$skip) {

                $valid->set_data($line);

                try {
                    $rs = $valid->run();
                } catch (Exception $e) {
                    $obj = clone $tlog_vo;
                    $obj->set_message($e->getMessage());
                    $tlog_dao->insert($tlog_obj);
                    $skip = 1;
                }
            }

            @fclose($fp);
            $filename = "dpdupload_" . date("YmdHis") . ".txt";
            $path = $this->get_config()->value_of("dpd_proc_path");

            if ($skip) {
                copy($input["tmp_name"], $path . "fail/" . $filename);
            } else {
                copy($input["tmp_name"], $path . "success/" . $filename);

                $batch_obj = $this->get_batch_dao()->get();
                $batch_obj->set_remark($filename);
                $batch_obj->set_func_name($func);
                $batch_obj->set_status("N");
                $batch_obj->set_listed(1);
                $this->get_batch_dao()->insert($batch_obj);
            }
        }
        $this->batch_dpdupload_handle("dpd");
    }

    public function get_tlog_dao()
    {
        return $this->tlog_dao;
    }

    public function get_valid()
    {
        return $this->valid;
    }

    public function get_config()
    {
        return $this->config;
    }

    public function get_batch_dao()
    {
        return $this->batch_dao;
    }

    public function batch_dpdupload_handle($type = "dpd")
    {
        $msvc_arr = array("dpd" => "DPD", "metapack" => "RM");
        $batch_list = $this->get_batch_dao()->get_list(array("status" => "N", "func_name" => $type . "_process"));
        $local_path = $this->get_config()->value_of($type . "_proc_path") . "/success/";

        include_once APPPATH . "libraries/dao/Interface_so_shipment_dao.php";
        $iss_dao = new Interface_so_shipment_dao();
        $iss_vo = $iss_dao->get();

        $dex = $this->get_dex();

        $valid = $this->get_valid();

        if (count((array)$batch_list)) {

            foreach ($batch_list as $obj) {
                $batch_id = $obj->get_id();
                $obj_csv = new Csv_to_xml($local_path . $obj->get_remark(), APPPATH . 'data/' . $type . '_upload_csv2xml.txt', ($type == "dpd" ? FALSE : TRUE), ',', TRUE);
                $obj_xml = new Xml_to_xml();

                $out = $dex->convert($obj_csv, $obj_xml);

                $obj_vo = new Xml_to_vo("", APPPATH . 'data/' . $type . '_upload_xml2vo.txt');

                $berr = 0;
                $output = $dex->convert($obj_csv, $obj_vo);

                if ($output) {
                    foreach ($output as $iss_obj) {
                        $tn = $iss_obj->get_tracking_no();
                        $iss_obj->set_tracking_no(ereg_replace("^=", "", str_replace(", ", "", $tn)));
                        $iss_obj->set_batch_id($obj->get_id());
                        if ($type == "dpd") {
                            $iss_obj->set_courier_id('DPD');
                        }
                        $iss_obj->set_status(1);
                        $iss_obj->set_batch_status('N');
                        $ret = $iss_dao->insert($iss_obj);
                        if ($ret === FALSE) {
                            $berr++;
                            break;
                        }
                    }
                }

                if ($berr) {
                    $obj->set_status("BE");
                    $this->get_batch_dao()->update($obj);
                    continue;
                }

                $sn_list = $this->get_so_svc()->get_ship_no_list("array", $msvc_arr[$type]);

                $obj->set_status("P");
                $this->get_batch_dao()->update($obj);
                $valid->set_exists_in(array("so_shipment" => $sn_list));

                $iss_list = $iss_dao->get_list(array("batch_id" => $batch_id));

                $success = 1;
                foreach ($iss_list as $issobj) {
                    $rules["sh_no"] = array("not_empty", "exists_in=so_shipment");
                    $rules["tracking_no"] = array("not_empty");
                    $valid->set_rules($rules);

                    $valid->set_data($issobj);

                    $rs = FALSE;
                    try {
                        $rs = $valid->run();
                    } catch (Exception $e) {
                        $issobj->set_failed_reason($e->getMessage());
                    }

                    if ($rs) {
                        $issobj->set_batch_status("R");
                        unset($e);
                    } else {
                        $issobj->set_batch_status("F");
                        $success = 0;
                    }
                    unset($e);
                    $iss_dao->update($issobj);
                }

                $iss_dao->update_dpd_trackno($batch_id);

                if (!$success) {
                    $obj->set_status("CE");
                    $obj->set_end_time(date("Y-m-d H:i:s"));
                    $this->get_batch_dao()->update($obj);
                }
            }
        }
    }

    public function get_dex()
    {
        return $this->dex;
    }

    public function get_so_svc()
    {
        return $this->so_svc;
    }

    public function metapack_processor($input = array())
    {

        $func = "metapack_process";

        $tlog_vo = $this->get_tlog_dao()->get();

        $tlog_dao = $this->get_tlog_dao();

        $valid = $this->get_valid();

        if ($fp = @fopen($input["tmp_name"], "r")) {
            $skip = 0;
            $rules = array();
            $rules[1] = array("not_empty");
            $rules[37] = array("not_empty");
            $rules[34] = array("not_empty");
            $valid->set_rules($rules);

            while (($line = fgetcsv($fp, 5000, ",")) && !$skip) {

                $valid->set_data($line);

                try {
                    $rs = $valid->run();
                } catch (Exception $e) {
                    $obj = clone $tlog_vo;
                    $obj->set_message($e->getMessage());
                    $tlog_dao->insert($tlog_obj);
                    $skip = 1;
                }
            }

            @fclose($fp);
            $filename = "mpupload_" . date("YmdHis") . ".txt";
            $path = $this->get_config()->value_of("metapack_proc_path");

            if ($skip) {
                copy($input["tmp_name"], $path . "fail/" . $filename);
            } else {
                copy($input["tmp_name"], $path . "success/" . $filename);

                $batch_obj = $this->get_batch_dao()->get();
                $batch_obj->set_remark($filename);
                $batch_obj->set_func_name($func);
                $batch_obj->set_status("N");
                $batch_obj->set_listed(1);
                $this->get_batch_dao()->insert($batch_obj);
            }
        }
        $this->batch_dpdupload_handle("metapack");
    }

    public function get_ip_dao()
    {
        return $this->ip_dao;
    }

    public function set_ip_dao(Base_dao $dao)
    {
        $this->ip_dao = $dao;
    }

    public function get_event()
    {
        return $this->event;
    }

}

?>