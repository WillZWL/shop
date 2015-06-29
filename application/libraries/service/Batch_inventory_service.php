<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_batch_service.php";

class Batch_inventory_service extends Base_batch_service
{

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
        include_once(APPPATH . "libraries/dao/Interface_inventory_dao.php");
        $this->set_iinv_dao(new Interface_inventory_dao());
    }

    public function set_tlog_dao(Base_dao $dao)
    {
        $this->tlog_dao = $dao;
    }

    public function set_valid($value)
    {
        $this->valid = $value;
    }

    public function set_dex($value)
    {
        $this->dex = $value;
    }

    public function set_iinv_dao(Base_dao $dao)
    {
        $this->iinv_dao = $dao;
    }

    public function cron_inventory()
    {
        $func = "inventory";
        include_once(APPPATH . "libraries/dao/Config_dao.php");
        $config_dao = new Config_dao();
        $config_obj = $config_dao->get(array("variable" => $func));
        $path = $config_obj->get_value();
        $no_file = 0;

        foreach (glob($path . '/' . "*.csv") as $filename) {
            $no_file = 1;
            $filename = basename($filename);
            $dao = $this->get_dao();
            $tlog_dao = $this->get_tlog_dao();
            $tlog_vo = $tlog_dao->get();
            $batch_vo = $dao->get();

            $valid = $this->get_valid();

            if (is_file($path . '/' . $filename)) {
                $tlog_obj = clone $tlog_vo;
                $batch_obj = $dao->get(array("remark" => $path . '/' . $filename));
                $success = 1;
                $arr = $this->get_valid()->check_field($path . '/' . $filename, $val = '4');
                if ($arr) {
                    $rules[0] = array("not_empty");//log_sku
                    $rules[1] = array("not_empty");//prod_name
                    $rules[2] = array("not_empty", "is_number");//inventory
                    $rules[3] = array("not_empty");//weight
                    $valid->set_rules($rules);
                    for ($i = 0; $i < count($arr); $i++) {
                        $arr[$i][] = explode(",", $arr[$i]);
                        $valid->set_data($arr[$i]);
                        $rs = FALSE;
                        try {
                            $rs = $valid->run();
                        } catch (Exception $e) {
                            $tlog_obj->set_func_name($func);
                            $tlog_obj->set_message($e->getMessage());
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
                        if (copy($path . '/' . $filename, $path . "/success/" . $filename)) {
                            unlink($path . '/' . $filename);
                        }
                    } else {
                        //echo $filename." uploaded fail<br>";
                        if (copy($path . '/' . $filename, $path . "/fail/" . $filename)) {
                            unlink($path . '/' . $filename);
                        }
                    }
                } else {
                    //echo $filename." uploaded fail<br>";
                    $tlog_obj->set_func_name($func);
                    $tlog_obj->set_message("number of field not match");
                    $tlog_dao->insert($tlog_obj);
                    if (copy($path . '/' . $filename, $path . "/fail/" . $filename)) {
                        unlink($path . '/' . $filename);
                    }
                }
            }
        }
        if ($no_file == 0) {
            //echo "No files in the folder";
        }
        return $this->batch_inventory();
    }

    public function get_tlog_dao()
    {
        return $this->tlog_dao;
    }

    public function get_valid()
    {
        return $this->valid;
    }

    public function batch_inventory()
    {
        $func = "inventory";
        $dao = $this->get_dao();
        $valid = $this->get_valid();
        $dex = $this->get_dex();

        include_once(APPPATH . "libraries/dao/Config_dao.php");
        $config_dao = new Config_dao();
        $config_obj = $config_dao->get(array("variable" => $func));
        $path = $config_obj->get_value();
        $local_path = $path . "/success";

        $iinv_dao = $this->get_iinv_dao();

        include_once(APPPATH . "libraries/dao/Inventory_dao.php");
        $inv_dao = new Inventory_dao();
        $objlist = $dao->get_list(array("func_name" => $func, "status" => "N"));
        if ($objlist) {
            foreach ($objlist as $obj) {
                $success = 1;
                $batch_id = $obj->get_id();
                $obj_csv = new Csv_to_xml($local_path . '/' . $obj->get_remark(), APPPATH . 'data/inventory.txt', TRUE, ",", FALSE);
                $out_vo = new Xml_to_vo();
                $output = $dex->convert($obj_csv, $out_vo);
                if ($output) {
                    foreach ($output as $iinv_obj) {
                        $iinv_obj->set_batch_id($batch_id);
                        $iinv_obj->set_batch_status("N");
                        $iinv_dao->insert($iinv_obj);
                    }
                    $inv_list = $inv_dao->get_batch_inventory_list(array("batch_id" => $batch_id, "warehouse_id" => "CW"));

                    $inv_data = array();
                    foreach ($inv_list as $list) {
                        $log_sku = $list->get_log_sku();
                        $inv_data["inventory"][$log_sku] = $list->get_inventory();
                        $inv_data["log_sku"][$log_sku] = 1;
                    }
                    $valid->set_exists_in(array("log_sku" => $inv_data["log_sku"]));
                    $obj->set_status("P");
                    $dao->update($obj);

                    foreach ($output as $iinv_obj) {
                        $rules["log_sku"] = array("exists_in=log_sku");
                        $rules["inventory"] = array("equal=" . $inv_data["inventory"][$iinv_obj->get_log_sku()] . ":" . $iinv_obj->get_log_sku());
                        $valid->set_rules($rules);
                        $valid->set_data($iinv_obj);
                        $rs = FALSE;
                        try {
                            $rs = $valid->run();
                        } catch (Exception $e) {
                            //echo "&nbsp;".$e->getMessage()."<br>";
                            $success = 0;
                            $iinv_obj->set_failed_reason($e->getMessage());
                            $iinv_obj->set_batch_status("F");
                            $iinv_dao->update($iinv_obj);
                        }
                        if ($rs) {
                            $iinv_obj->set_batch_status("S");
                            $iinv_dao->update($iinv_obj);
                            //echo "&nbsp;".$log_sku." is matched in field inventory<br>";
                        }
                    }
                }
                if (!$success) {
                    $obj->set_status("CE");
                    $obj->set_end_time(date("Y-m-d H:i:s"));
                    $dao->update($obj);
                } else {
                    $obj->set_status("C");
                    $obj->set_end_time(date("Y-m-d H:i:s"));
                    $dao->update($obj);
                }
            }
        }
        if ($iinv_obj != NULL) {
            return $iinv_obj->get_batch_id();
        } else {
            return NULL;
        }
    }

    public function get_dex()
    {
        return $this->dex;
    }

    public function get_iinv_dao()
    {
        return $this->iinv_dao;
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


/* End of file batch_inventory_service.php */
/* Location: ./system/application/libraries/service/Batch_inventory_service.php */
