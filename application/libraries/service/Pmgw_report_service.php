<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

abstract class Pmgw_report_service extends Base_service
{
    const WRONG_TRANSACTION_ID = "Wrong transaction id / so_no";
    const WRONG_CURRENCY_ID = "Wrong Currency ID";

    private $tlog_dao;
    private $batch_dao;
    private $ifr_dao;

    /*************************************
     *   Caveat (concept: txn_id, internal_txn_id and ref_txn_id)
     *   1.1 refund, rolling_reserve: HAVE internal_txn_id
     *   1.2 RIA, so_fee, gateway_fee: DO NOT HAVE internal_txn_id
     *   2.1 RIA, so_fee: use the set_txn_id(dto->get_txn_id())
     *   2.2 refund, rolling_reserve, gateway_fee: use the set_txn_id(dto->get_ref_txn_id())
     *   3.1 refund: set_amount(dto->get_commission())
     *   4.1 Other than refund: set_amount(dto->get_amount())
     **************************************/

    public function __construct()
    {
        $CI =& get_instance();
        $this->load = $CI->load;
        $this->load->helper(array('url', 'notice', 'image', 'object'));
        include_once(APPPATH . "libraries/dao/So_dao.php");
        $this->set_so_dao(new So_dao());
        include_once(APPPATH . "libraries/service/Data_exchange_service.php");
        $this->set_dex_srv(new Data_exchange_service());
        include_once(APPPATH . "libraries/service/Context_config_service.php");
        $this->set_config_srv(new Context_config_service());
        include_once(APPPATH . "libraries/dao/Interface_flex_ria_dao.php");
        $this->set_ifr_dao(new Interface_flex_ria_dao());
        include_once(APPPATH . "libraries/dao/Interface_flex_refund_dao.php");
        $this->set_ifrf_dao(new Interface_flex_refund_dao());
        include_once(APPPATH . "libraries/dao/Interface_flex_so_fee_dao.php");
        $this->set_ifsf_dao(new Interface_flex_so_fee_dao());
        include_once(APPPATH . "libraries/dao/Interface_flex_rolling_reserve_dao.php");
        $this->set_ifrr_dao(new Interface_flex_rolling_reserve_dao());
        include_once(APPPATH . "libraries/dao/Interface_flex_gateway_fee_dao.php");
        $this->set_ifgf_dao(new Interface_flex_gateway_fee_dao());
        include_once(APPPATH . "libraries/dao/Flex_batch_dao.php");
        $this->set_batch_dao(new Flex_batch_dao());
        include_once(APPPATH . "libraries/dao/Flex_ria_dao.php");
        $this->set_fr_dao(new Flex_ria_dao());
        include_once(APPPATH . "libraries/dao/Flex_refund_dao.php");
        $this->set_frf_dao(new Flex_refund_dao());
        include_once(APPPATH . "libraries/dao/Flex_so_fee_dao.php");
        $this->set_fsf_dao(new Flex_so_fee_dao());
        include_once(APPPATH . "libraries/dao/Flex_rolling_reserve_dao.php");
        $this->set_frr_dao(new Flex_rolling_reserve_dao());
        include_once(APPPATH . "libraries/dao/Flex_gateway_fee_dao.php");
        $this->set_fgf_dao(new Flex_gateway_fee_dao());
    }

    public function set_so_dao(Base_dao $dao)
    {
        $this->so_dao = $dao;
    }

    public function set_dex_srv($srv)
    {
        $this->dex_srv = $srv;
    }

    public function set_config_srv($srv)
    {
        $this->config_srv = $srv;
    }

    public function set_ifrf_dao(Base_dao $dao)
    {
        $this->ifrf_dao = $dao;
    }

    public function set_ifsf_dao(Base_dao $dao)
    {
        $this->ifsf_dao = $dao;
    }

    public function set_ifrr_dao(Base_dao $dao)
    {
        $this->ifrr_dao = $dao;
    }

    public function set_ifgf_dao(Base_dao $dao)
    {
        $this->ifgf_dao = $dao;
    }

    public function set_fr_dao(Base_dao $dao)
    {
        $this->fr_dao = $dao;
    }

    public function set_frf_dao(Base_dao $dao)
    {
        $this->frf_dao = $dao;
    }

    public function set_fsf_dao(Base_dao $dao)
    {
        $this->fsf_dao = $dao;
    }

    public function set_frr_dao(Base_dao $dao)
    {
        $this->frr_dao = $dao;
    }

    public function set_fgf_dao(Base_dao $dao)
    {
        $this->fgf_dao = $dao;
    }

    abstract public function is_ria_include_so_fee();

    abstract public function is_refund_include_so_fee();

    public function get_system_platform()
    {
        return "VB";
    }

    public function process_report($filename)
    {
        $pmgw = $this->get_pmgw();
        $batch_id = $this->insert_batch($filename);
        $output = $this->get_file_data($filename);

        $batch_result = TRUE;
        $count_output = count((array)$output);

        if ($count_output > 0) {
            foreach ($output as $dto_obj) {
                $this->insert_interface($batch_id, $dto_obj);
            }

            $this->after_insert_all_interface($batch_id);

            $batch_result = $this->insert_master($batch_id);
            if ($batch_result) {
                $this->complete_batch($batch_id, "C");
            } else {
                $this->complete_batch($batch_id, "CE");
                $this->send_investigate_report($pmgw, $filename, $batch_id);
            }
        } else {
            $this->complete_batch($batch_id, "F");
            $this->send_investigate_report($pmgw, $filename, $batch_id);
        }
        $this->move_complete_file($filename);

        return array($batch_result, $batch_id);
    }

    abstract protected function get_pmgw();

    public function insert_batch($filename)
    {
        set_time_limit(3000);
        if (is_file($this->get_folder_path() . $filename)) {
            $batch_dao = $this->get_batch_dao();
            $batch_vo = $batch_dao->get();

            $batch_obj = $batch_dao->get(array("filename" => $filename));
            if (!$batch_obj) {
                $batch_obj = clone $batch_vo;

                $batch_obj->set_gateway_id($this->get_pmgw());
                $batch_obj->set_filename($filename);
                $batch_dao->insert($batch_obj);
                return $batch_obj->get_id();
            } else {
                echo "file already in batch";
                exit;
                //file already in batch
            }
        } else {
            echo "file does not exists:<br />" . $this->get_folder_path() . $filename;
            exit;
            //invalid file path
        }
    }

    public function get_folder_path()
    {
        return $this->get_config_srv()->value_of("flex_pmgw_report_loaction") . $this->get_pmgw() . "/";
    }

    public function get_config_srv()
    {
        return $this->config_srv;
    }

    public function get_batch_dao()
    {
        return $this->batch_dao;
    }

    public function set_batch_dao(Base_dao $dao)
    {
        $this->batch_dao = $dao;
    }

    public function get_file_data($filename, $delimiter = ",")
    {
        $dex_srv = $this->get_dex_srv();

        $obj_csv = new Csv_to_xml($this->get_folder_path() . $filename, APPPATH . $this->get_data_exchange_file(), TRUE, $delimiter, TRUE);
        $out_vo = new Xml_to_vo();

        $result = $dex_srv->convert($obj_csv, $out_vo);

        return $result;
    }

    public function get_dex_srv()
    {
        return $this->dex_srv;
    }

    public function get_data_exchange_file()
    {
        return 'data/pmgw_report_' . $this->get_pmgw() . '.txt';
    }

    protected function insert_interface($batch_id, $dto_obj)
    {
        if ($this->is_ria_record($dto_obj))
            $this->insert_interface_flex_ria($batch_id, 'RIA', $dto_obj);
        else if ($refund_status = $this->is_refund_record($dto_obj))
            $this->insert_interface_flex_refund($batch_id, $refund_status, $dto_obj);
        else if ($fee_status = $this->is_so_fee_record($dto_obj))
            $this->insert_interface_flex_so_fee($batch_id, $fee_status, $dto_obj);
        else if ($rolling_status = $this->is_rolling_reserve_record($dto_obj))
            $this->insert_interface_flex_rolling_reserve($batch_id, $rolling_status, $dto_obj);
        else if ($gateway_fee_status = $this->is_gateway_fee_record($dto_obj))
            $this->insert_interface_flex_gateway_fee($batch_id, $gateway_fee_status, $dto_obj);
    }

    abstract public function is_ria_record($dto_obj);

    /***********************************
     *   If Exchange Fee, return 'FX'
     *   If Payment Sent return 'PS'
     *   return False otherwises
     ***********************************/
    abstract protected function insert_interface_flex_ria($batch_id, $status, $dto_obj);

    /***********************************
     *   If Refund, return 'R'
     *   If Chargeback return 'CB'
     *   return False otherwises
     ***********************************/
    abstract public function is_refund_record($dto_obj);

    abstract protected function insert_interface_flex_refund($batch_id, $status, $dto_obj);

    abstract public function is_so_fee_record($dto_obj);

    abstract protected function insert_interface_flex_so_fee($batch_id, $status, $dto_obj);

    abstract public function is_rolling_reserve_record($dto_obj);

    abstract protected function insert_interface_flex_rolling_reserve($batch_id, $status, $dto_obj);

    abstract public function is_gateway_fee_record($dto_obj);

    abstract protected function insert_interface_flex_gateway_fee($batch_id, $status, $dto_obj);

    abstract protected function after_insert_all_interface($batch_id);

    public function insert_master($batch_id)
    {
        $return_result = TRUE;

        if ($this->insert_flex_ria($batch_id) === FALSE) {
            $return_result = FALSE;
        }
        if ($this->insert_flex_so_fee($batch_id) === FALSE) {
            $return_result = FALSE;
        }
        if ($this->insert_flex_refund($batch_id) === FALSE) {
            $return_result = FALSE;
        }
        if ($this->insert_flex_rolling_reserve($batch_id) === FALSE) {
            $return_result = FALSE;
        }
        if ($this->insert_flex_gateway_fee($batch_id) === FALSE) {
            $return_result = FALSE;
        }

        return $return_result;
    }

    public function insert_flex_ria($batch_id)
    {
        $ifr_list = $this->get_ifr_dao()->get_flex_ria_by_batch($batch_id);
        //var_dump($ifr_list);die();
        if ($ifr_list) {
            $return_result = TRUE;

            foreach ($ifr_list AS $ifr_obj) {
                if ($ifr_obj->get_batch_status() == 'N') {
                    $fr_dao = $this->get_fr_dao();
                    $ifr_dao = $this->get_ifr_dao();
                    $fr_vo = $fr_dao->get();

                    if ($fr_obj = $fr_dao->get(array("so_no" => $ifr_obj->get_so_no(), "gateway_id" => $ifr_obj->get_gateway_id(), "status" => $ifr_obj->get_status(), "txn_time" => $ifr_obj->get_txn_time()))) {
                        if (($fr_obj->get_amount() == $ifr_obj->get_amount())) {
                            $this->_update_interface_flex_ria_status_by_group($batch_id, $ifr_obj->get_so_no(), $ifr_obj->get_status(), "C", "duplicated record");
                        } else {
                            $fr_obj->set_flex_batch_id($ifr_obj->get_flex_batch_id());
                            $fr_obj->set_amount($ifr_obj->get_amount());
                            if ($fr_dao->update($fr_obj) !== FALSE) {
                                $this->_update_interface_flex_ria_status_by_group($batch_id, $ifr_obj->get_so_no(), $ifr_obj->get_status(), "I", "record updated on " . date("Y-m-d H:i:s"));
                                $return_result = FALSE;
                            } else {
                                $this->_update_interface_flex_ria_status_by_group($batch_id, $ifr_obj->get_so_no(), $ifr_obj->get_status(), "F", "update record error: " . $fr_dao->db->_error_message());
                                $return_result = FALSE;
                            }
                        }
                    } else {
                        $fr_obj = clone $fr_vo;
                        set_value($fr_obj, $ifr_obj);
                        if ($this->valid_txn_id($fr_obj)) {
                            if ($fr_dao->insert($fr_obj) !== FALSE) {
                                $this->_update_interface_flex_ria_status_by_group($batch_id, $ifr_obj->get_so_no(), $ifr_obj->get_status(), "S", "");
                            } else {   //var_dump($fr_dao->db->last_query());die();
                                if ($failed_reason = $this->valid_so_no($ifr_obj)) {
                                    $ifr_obj->set_failed_reason($failed_reason);
                                } else {
                                    $ifr_obj->set_failed_reason($fr_dao->db->_error_message());
                                }

                                $this->_update_interface_flex_ria_status_by_group($batch_id, $ifr_obj->get_so_no(), $ifr_obj->get_status(), "F", $ifr_obj->get_failed_reason());
                                $return_result = FALSE;
                            }
                        } else {
                            $this->_update_interface_flex_ria_status_by_group($batch_id, $ifr_obj->get_so_no(), $ifr_obj->get_status(), "F", "invalid txn_id");
                            $return_result = FALSE;
                        }
                    }
                } elseif ($ifr_obj->get_batch_status() == 'F') {
                    $return_result = FALSE;
                }
            }
            return $return_result;
        }
        return TRUE;
    }

    public function get_ifr_dao()
    {
        return $this->ifr_dao;
    }

    public function set_ifr_dao(Base_dao $dao)
    {
        $this->ifr_dao = $dao;
    }

    public function get_fr_dao()
    {
        return $this->fr_dao;
    }

    private function _update_interface_flex_ria_status_by_group($batch_id, $so_no, $status, $batch_status, $failed_reason)
    {
        $ifr_dao = $this->get_ifr_dao();
        $ifrObjs = $ifr_dao->get_list(array("flex_batch_id" => $batch_id,
            "gateway_id" => $this->get_pmgw(),
            "so_no" => $so_no,
            "status" => $status), array("limit" => -1));

        if ($ifrObjs) {
            foreach ($ifrObjs as $ifrObj) {
                $ifrObj->set_batch_status($batch_status);
                if ($failed_reason)
                    $ifrObj->set_failed_reason($failed_reason);
                $ifr_dao->update($ifrObj);
            }
        }
    }

    public function valid_txn_id($interface_obj)
    {
        return true;
    }

    public function valid_so_no($interface_obj)
    {
        return false;

        /*
        //by nero, if is rolling_reserve, then detect the internal_txn_id too.
        if(get_class($interface_obj) && in_array("get_internal_txn_id", get_class_methods($interface_obj)))
        {
            $internal_txn_id = $interface_obj->get_internal_txn_id();
        }
        else
        {
            $internal_txn_id = "";
        }

        if($fr_obj = $this->get_fr_dao()->get(array("so_no"=>$interface_obj->get_so_no(), "internal_txn_id = $internal_txn_id"=> NULL, "status"=>$interface_obj->get_status())))
        {
            $failed_reason = "gateway record already existed";
        }
        else
        {
            $i_so_no = $interface_obj->get_so_no();
            if(!$i_so_no)
            {
                $failed_reason = "empty so_no";
            }
            else
            {
                $failed_reason = "invalid so_no";
            }
        }
        return $failed_reason;
        */
    }

    public function insert_flex_so_fee($batch_id)
    {
        $ifsf_list = $this->get_ifsf_dao()->get_so_fee_by_batch($batch_id);
        if ($ifsf_list) {
            $return_result = TRUE;
            foreach ($ifsf_list AS $ifsf_obj) {
                if ($ifsf_obj->get_batch_status() == 'N') {
                    $fsf_dao = $this->get_fsf_dao();
                    $ifsf_dao = $this->get_ifsf_dao();
                    $fsf_vo = $fsf_dao->get();

                    if (($fsf_obj = $fsf_dao->get_list(array("so_no" => $ifsf_obj->get_so_no(), "gateway_id" => $ifsf_obj->get_gateway_id(), "status" => $ifsf_obj->get_status(), "txn_id" => $ifsf_obj->get_txn_id(), "txn_time" => $ifsf_obj->get_txn_time())))
                        && ($fsf_obj instanceof flex_so_fee)
                    ) {
                        if (($fsf_obj->get_amount() == $ifsf_obj->get_amount())) {
                            $this->update_interface_so_fee_status_by_group($batch_id, $ifsf_obj->get_so_no(), $ifsf_obj->get_status(), "C", "duplicated record");
                        } else {
                            $fsf_obj->set_flex_batch_id($ifsf_obj->get_flex_batch_id());
                            $fsf_obj->set_amount($ifsf_obj->get_amount());

                            if ($fsf_dao->update($fsf_obj) !== FALSE) {
                                $this->update_interface_so_fee_status_by_group($batch_id, $ifsf_obj->get_so_no(), $ifsf_obj->get_status(), "I", "record updated on " . date("Y-m-d H:i:s"));
                            } else {
                                $this->update_interface_so_fee_status_by_group($batch_id, $ifsf_obj->get_so_no(), $ifsf_obj->get_status(), "F", "update record error: " . $fsf_dao->db->_error_message());
                                $return_result = FALSE;
                            }
                        }
                    } else {
                        $fsf_obj = clone $fsf_vo;
                        set_value($fsf_obj, $ifsf_obj);
                        if ($this->valid_txn_id($fsf_obj)) {
                            if ($fsf_dao->insert($fsf_obj) !== FALSE) {
                                $this->update_interface_so_fee_status_by_group($batch_id, $ifsf_obj->get_so_no(), $ifsf_obj->get_status(), "S", "");
                            } else {
                                if ($failed_reason = $this->valid_so_no($ifsf_obj)) {
                                    $errorMessage = $failed_reason;
                                } else {
                                    $errorMessage = $fsf_dao->db->_error_message();
                                }
                                $this->update_interface_so_fee_status_by_group($batch_id, $ifsf_obj->get_so_no(), $ifsf_obj->get_status(), "F", $errorMessage);
                                $return_result = FALSE;
                            }
                        } else {
                            $this->update_interface_so_fee_status_by_group($batch_id, $ifsf_obj->get_so_no(), $ifsf_obj->get_status(), "F", "invalid txn_id");
                            $return_result = FALSE;
                        }
                    }
                } elseif ($ifsf_obj->get_batch_status() == 'F') {
                    $return_result = FALSE;
                }
            }

            return $return_result;
        }

        return TRUE;
    }

    public function get_ifsf_dao()
    {
        return $this->ifsf_dao;
    }

    public function get_fsf_dao()
    {
        return $this->fsf_dao;
    }

    public function update_interface_so_fee_status_by_group($batch_id, $so_no, $status, $batch_status, $failed_reason)
    {
        //"so_no = $so_no" and "so_no" => $so_no is different
        $ifsf_dao = $this->get_ifsf_dao();
        $ifsfObjs = $ifsf_dao->get_list(array("flex_batch_id" => $batch_id,
            "gateway_id" => $this->get_pmgw(),
            "so_no" => $so_no,
            "status" => $status), array("limit" => -1));

        foreach ($ifsfObjs as $ifsfObj) {
            $ifsfObj->set_batch_status($batch_status);
            if ($failed_reason)
                $ifsfObj->set_failed_reason($failed_reason);
            $ifsf_dao->update($ifsfObj);
        }
    }

    public function insert_flex_refund($batch_id)
    {
        $ifrf_list = $this->get_ifrf_dao()->get_flex_refund_by_batch($batch_id);

        if ($ifrf_list) {
            $return_result = TRUE;
            foreach ($ifrf_list AS $ifrf_obj) {
                if ($ifrf_obj->get_batch_status() == 'N') {
                    $frf_dao = $this->get_frf_dao();
                    $ifrf_dao = $this->get_ifrf_dao();
                    $frf_vo = $frf_dao->get();

                    if ($frf_obj = $frf_dao->get(array("so_no" => $ifrf_obj->get_so_no(), "gateway_id" => $ifrf_obj->get_gateway_id(), "status" => $ifrf_obj->get_status(), "internal_txn_id" => $ifrf_obj->get_internal_txn_id(), "txn_time" => $ifrf_obj->get_txn_time()))) {
                        if (($frf_obj->get_amount() == $ifrf_obj->get_amount())) {
                            $this->_update_interface_flex_refund_status_by_group($batch_id, $ifrf_obj->get_so_no(), $ifrf_obj->get_status(), "C", "duplicated record");
                        } else {
                            $frf_obj->set_flex_batch_id($ifrf_obj->get_flex_batch_id());
                            $frf_obj->set_amount($ifrf_obj->get_amount());

                            if ($frf_dao->update($frf_obj) !== FALSE) {
                                $this->_update_interface_flex_refund_status_by_group($batch_id, $ifrf_obj->get_so_no(), $ifrf_obj->get_status(), "I", "record updated on " . date("Y-m-d H:i:s"));
                                $return_result = FALSE;
                            } else {
                                $this->_update_interface_flex_refund_status_by_group($batch_id, $ifrf_obj->get_so_no(), $ifrf_obj->get_status(), "F", "update record error: " . $frf_dao->db->_error_message());
                                $return_result = FALSE;
                            }
                        }
                    } else {
                        $frf_obj = clone $frf_vo;
                        set_value($frf_obj, $ifrf_obj);

                        if ($this->valid_txn_id($frf_obj)) {
                            //$frf_dao->insert($frf_obj);
                            //var_dump($frf_dao->db->last_query());die();
                            if ($frf_dao->insert($frf_obj) !== FALSE) {
                                $this->_update_interface_flex_refund_status_by_group($batch_id, $ifrf_obj->get_so_no(), $ifrf_obj->get_status(), "S", "");
                            } else {
                                if ($failed_reason = $this->valid_so_no($ifrf_obj)) {
                                    $ifrf_obj->set_failed_reason($failed_reason);
                                } else {
                                    $ifrf_obj->set_failed_reason($frf_dao->db->_error_message());
                                }
                                $this->_update_interface_flex_refund_status_by_group($batch_id, $ifrf_obj->get_so_no(), $ifrf_obj->get_status(), "F", $ifrf_obj->get_failed_reason());
                                $return_result = FALSE;
                            }
                        } else {
                            $this->_update_interface_flex_refund_status_by_group($batch_id, $ifrf_obj->get_so_no(), $ifrf_obj->get_status(), "F", "invalid txn_id");
                            $return_result = FALSE;
                        }
                    }
                } elseif ($ifrf_obj->get_batch_status() == 'F') {
                    $return_result = FALSE;
                }
            }

            return $return_result;
        }

        return TRUE;
    }

    public function get_ifrf_dao()
    {
        return $this->ifrf_dao;
    }

    public function get_frf_dao()
    {
        return $this->frf_dao;
    }

    private function _update_interface_flex_refund_status_by_group($batch_id, $so_no, $status, $batch_status, $failed_reason)
    {
        $ifrf_dao = $this->get_ifrf_dao();
        $ifrfObjs = $ifrf_dao->get_list(array("flex_batch_id" => $batch_id,
            "gateway_id" => $this->get_pmgw(),
            "so_no" => $so_no,
            "status" => $status), array("limit" => -1));
        //var_dump($ifrf_dao->db->last_query());die();
        if ($ifrfObjs) {
            foreach ($ifrfObjs as $ifrfObj) {
                $ifrfObj->set_batch_status($batch_status);
                if ($failed_reason)
                    $ifrfObj->set_failed_reason($failed_reason);

                $ifrf_dao->update($ifrfObj);
            }
        }
    }

    public function insert_flex_rolling_reserve($batch_id)
    {
        $ifrr_list = $this->get_ifrr_dao()->get_rolling_reserve_by_batch($batch_id);
        if ($ifrr_list) {
            $return_result = TRUE;

            foreach ($ifrr_list AS $ifrr_obj) {
                if ($ifrr_obj->get_batch_status() == 'N') {
                    $frr_dao = $this->get_frr_dao();
                    $ifrr_dao = $this->get_ifrr_dao();
                    $frr_vo = $frr_dao->get();

                    if ($frr_obj = $frr_dao->get(array("so_no" => $ifrr_obj->get_so_no(), "gateway_id" => $ifrr_obj->get_gateway_id(), "status" => $ifrr_obj->get_status(), "internal_txn_id" => $ifrr_obj->get_internal_txn_id(), "txn_time" => $ifrr_obj->get_txn_time()))) {
                        if (($frr_obj->get_amount() == $ifrr_obj->get_amount())) {
                            $ifrr_obj->set_batch_status("C");
                            $ifrr_obj->set_failed_reason("duplicated record");
                            $ifrr_dao->update($ifrr_obj);
                        } else {
                            $frr_obj->set_flex_batch_id($ifrr_obj->get_flex_batch_id());
                            $frr_obj->set_amount($ifrr_obj->get_amount());

                            if ($frr_dao->update($frr_obj) !== FALSE) {
                                $ifrr_obj->set_batch_status("I");
                                $ifrr_obj->set_failed_reason("record updated on " . date("Y-m-d H:i:s"));
                                $ifrr_dao->update($ifrr_obj);
                            } else {
                                $ifrr_obj->set_batch_status("F");
                                $ifrr_obj->set_failed_reason("update record error: " . $frr_dao->db->_error_message());
                                $ifrr_dao->update($ifrr_obj);
                                $return_result = FALSE;
                            }
                        }
                    } else {
                        $frr_obj = clone $frr_vo;
                        set_value($frr_obj, $ifrr_obj);

                        if ($this->valid_txn_id($frr_obj)) {
                            if ($frr_dao->insert($frr_obj) !== FALSE) {
                                $ifrr_obj->set_batch_status("S");
                                $ifrr_dao->update($ifrr_obj);
                            } else {
                                if ($failed_reason = $this->valid_so_no($ifrr_obj)) {
                                    $ifrr_obj->set_failed_reason($failed_reason);
                                } else {
                                    $ifrr_obj->set_failed_reason($frr_dao->db->_error_message());
                                }
                                $ifrr_obj->set_batch_status("F");
                                $ifrr_dao->update($ifrr_obj);
                                $return_result = FALSE;
                            }
                        } else {
                            $ifrr_obj->set_failed_reason("invalid txn_id");
                            $ifrr_obj->set_batch_status("F");
                            $ifrr_dao->update($ifrr_obj);
                            $return_result = FALSE;
                        }
                    }
                } elseif ($ifrr_obj->get_batch_status() == 'F') {
                    $return_result = FALSE;
                }
            }
            return $return_result;
        }

        return TRUE;
    }

    public function get_ifrr_dao()
    {
        return $this->ifrr_dao;
    }

    public function get_frr_dao()
    {
        return $this->frr_dao;
    }

    public function insert_flex_gateway_fee($batch_id)
    {

        $ifgf_list = $this->get_ifgf_dao()->get_list(array("flex_batch_id" => $batch_id), array("limit" => "-1"));

        if ($ifgf_list) {
            $return_result = TRUE;

            foreach ($ifgf_list AS $ifgf_obj) {
                if ($ifgf_obj->get_batch_status() == 'N') {
                    $fgf_dao = $this->get_fgf_dao();
                    $ifgf_dao = $this->get_ifgf_dao();
                    $fgf_vo = $fgf_dao->get();

                    if ($fgf_obj = $fgf_dao->get(array("txn_id" => $ifgf_obj->get_txn_id(), "gateway_id" => $ifgf_obj->get_gateway_id(), "status" => $ifgf_obj->get_status(), "txn_time" => $ifgf_obj->get_txn_time()))) {
                        if (($fgf_obj->get_amount() == $ifgf_obj->get_amount())) {
                            $ifgf_obj->set_batch_status("C");
                            $ifgf_obj->set_failed_reason("duplicated record");
                            $ifgf_dao->update($ifgf_obj);
                        } else {
                            $fgf_obj->set_flex_batch_id($ifgf_obj->get_flex_batch_id());
                            $fgf_obj->set_amount($ifgf_obj->get_amount());

                            if ($fgf_dao->update($fgf_obj) !== FALSE) {
                                $ifgf_obj->set_batch_status("I");
                                $ifgf_obj->set_failed_reason("record updated on " . date("Y-m-d H:i:s"));
                                $ifgf_dao->update($ifgf_obj);
                            } else {
                                $ifgf_obj->set_batch_status("F");
                                $ifgf_obj->set_failed_reason("update record error: " . $fgf_dao->db->_error_message());
                                $ifgf_dao->update($ifgf_obj);
                                $return_result = FALSE;
                            }
                        }
                    } else {
                        $fgf_obj = clone $fgf_vo;
                        set_value($fgf_obj, $ifgf_obj);

                        if ($fgf_dao->insert($fgf_obj) !== FALSE) {
                            $ifgf_obj->set_batch_status("S");
                            $ifgf_dao->update($ifgf_obj);
                        } else {
                            $ifgf_obj->set_failed_reason($fgf_dao->db->_error_message());
                            $ifgf_obj->set_batch_status("F");
                            $ifgf_dao->update($ifgf_obj);
                            $return_result = FALSE;
                        }
                    }
                } elseif ($ifgf_obj->get_batch_status() == 'F') {
                    $return_result = FALSE;
                }
            }

            return $return_result;
        }
        return TRUE;
    }

    public function get_ifgf_dao()
    {
        return $this->ifgf_dao;
    }

    public function get_fgf_dao()
    {
        return $this->fgf_dao;
    }

    public function complete_batch($batch_id, $status)
    {
        $batch_dao = $this->get_batch_dao();

        $batch_obj = $batch_dao->get(array("id" => $batch_id));
        $batch_obj->set_status($status);

        $batch_dao->update($batch_obj);
    }

    public function send_investigate_report($pmgw, $filename, $batch_id)
    {
        $total_err = 0;

        $message = "Payment Gateway: " . $pmgw . "\r\n";
        $message .= "File Name: " . $filename . "\r\n";
        $message .= "Batch ID: " . $batch_id . "\r\n\r\n";

        if ($ifr_list = $this->get_ifr_dao()->get_list(array("flex_batch_id" => $batch_id, "batch_status IN ('F', 'I')" => null), array("limit" => -1))) {
            if (count((array)$ifr_list) > 0) {
                $total_err += count((array)$ifr_list);

                $message .= "RIA:\r\n";
                $message .= "txn_id,so_no,failed_reason\r\n";

                foreach ($ifr_list as $ifr_obj) {
                    $message .= $ifr_obj->get_txn_id() . "," . $ifr_obj->get_so_no() . "," . $ifr_obj->get_failed_reason() . "\r\n";
                }
                $message .= "\r\n\r\n";
            }
        }

        if ($ifrf_list = $this->get_ifrf_dao()->get_list(array("flex_batch_id" => $batch_id, "batch_status IN ('F', 'I')" => null), array("limit" => -1))) {
            if (count((array)$ifrf_list) > 0) {
                $total_err += count((array)$ifrf_list);

                $message .= "Refund:\r\n";
                $message .= "txn_id,so_no,failed_reason\r\n";

                foreach ($ifrf_list as $ifrf_obj) {
                    $message .= $ifrf_obj->get_txn_id() . "," . $ifrf_obj->get_so_no() . "," . $ifrf_obj->get_failed_reason() . "\r\n";
                }
                $message .= "\r\n\r\n";
            }
        }

        if ($ifsf_list = $this->get_ifsf_dao()->get_list(array("flex_batch_id" => $batch_id, "batch_status IN ('F', 'I')" => null), array("limit" => -1))) {
            if (count((array)$ifsf_list) > 0) {
                $total_err += count((array)$ifsf_list);

                $message .= "So Fee:\r\n";
                $message .= "txn_id,so_no,failed_reason\r\n";

                foreach ($ifsf_list as $ifsf_obj) {
                    $message .= $ifsf_obj->get_txn_id() . "," . $ifsf_obj->get_so_no() . "," . $ifsf_obj->get_failed_reason() . "\r\n";
                }
                $message .= "\r\n\r\n";
            }
        }

        if ($ifrr_list = $this->get_ifrr_dao()->get_list(array("flex_batch_id" => $batch_id, "batch_status IN ('F', 'I')" => null), array("limit" => -1))) {
            if (count((array)$ifrr_list) > 0) {
                $total_err += count((array)$ifrr_list);

                $message .= "Rolling Reserve:\r\n";
                $message .= "txn_id,so_no,failed_reason\r\n";

                foreach ($ifrr_list as $ifrr_obj) {
                    $message .= $ifrr_obj->get_txn_id() . "," . $ifrr_obj->get_so_no() . "," . $ifrr_obj->get_failed_reason() . "\r\n";
                }
                $message .= "\r\n\r\n";
            }
        }

        if ($ifgf_list = $this->get_ifgf_dao()->get_list(array("flex_batch_id" => $batch_id, "batch_status IN ('F', 'I')" => null), array("limit" => -1))) {
            if (count((array)$ifgf_list) > 0) {
                $total_err += count((array)$ifgf_list);

                $message .= "Gateway Fee:\r\n";
                $message .= "txn_id,failed_reason\r\n";

                foreach ($ifgf_list as $ifgf_obj) {
                    $message .= $ifgf_obj->get_txn_id() . "," . $ifgf_obj->get_failed_reason() . "\r\n";
                }
                $message .= "\r\n\r\n";
            }
        }

        if ($total_err > 0) {
            mail("flexadmin@valuebasket.com", "[VB] Gateway Report Error", $message);
        }
    }

    public function move_complete_file($filename)
    {
        if (copy($this->get_folder_path() . $filename, $this->get_folder_path() . "complete/" . $filename)) {
            unlink($this->get_folder_path() . $filename);
        }
    }

    public function reprocess_record($interface_obj)
    {
        $this->update_record($interface_obj);
    }

    public function get_pmgw_failed_record($where = array(), $option = array())
    {
        return $this->get_ifpt_dao()->get_pmgw_failed_record($where, $option);
    }

    public function get_ifpt_dao()
    {
        return $this->ifpt_dao;
    }

    public function get_num_pmgw_failed_record($where = array())
    {
        return $this->get_ifpt_dao()->get_num_pmgw_failed_record($where);
    }

    public function get_ifpt($where)
    {
        return $this->get_ifpt_dao()->get($where);
    }

    public function update_ifpt($obj)
    {
        return $this->get_ifpt_dao()->update($obj);
    }

    public function get_batch_func_name()
    {
        return "upload_pmgw_report_" . $this->get_pmgw();
    }

    public function set_ifpt_dao(Base_dao $dao)
    {
        $this->ifpt_dao = $dao;
    }

    public function get_fpt_dao()
    {
        return $this->fpt_dao;
    }

    public function set_fpt_dao(Base_dao $dao)
    {
        $this->fpt_dao = $dao;
    }

    /**********************************
     *   create_interface_flex_ria is a helper function for child class
     *   to insert interface RIA record
     *   include_fsf, if RIA record include So Fee, insert the data directly
     **********************************/
    protected function create_interface_flex_ria($batch_id, $status, $dto_obj, $include_fsf = true)
    {
        $ifr_dao = $this->get_ifr_dao();
        $ifr_obj = $ifr_dao->get();

        if (!$dto_obj->get_so_no() && $dto_obj->get_txn_id()) {
            if ($so_obj = $this->get_so_obj(array("txn_id" => $dto_obj->get_txn_id()))) {
                $dto_obj->set_so_no($so_obj->get_so_no());
            }
        }
        $ifr_obj->set_so_no($dto_obj->get_so_no());
        $ifr_obj->set_flex_batch_id($batch_id);
        $ifr_obj->set_gateway_id($this->get_pmgw());
        $ifr_obj->set_txn_id($dto_obj->get_txn_id());
        $ifr_obj->set_txn_time($dto_obj->get_date());
        $ifr_obj->set_currency_id($dto_obj->get_currency_id());
        $ifr_obj->set_amount($dto_obj->get_amount());
        $ifr_obj->set_status($status);
        $ifr_obj->set_batch_status("N");

        if (!$ifr_obj->get_so_no()) {
            $ifr_obj->set_so_no(" ");
            $ifr_obj->set_batch_status("F");
            $ifr_obj->set_failed_reason(Pmgw_report_service::WRONG_TRANSACTION_ID);
        }

        // if success insert interface_flex_so_fee
        if ($ifr_dao->insert($ifr_obj) && $ifr_obj->get_batch_status() != "F") {
            if ($include_fsf) {
                $this->insert_so_fee_from_ria_record($batch_id, $status, $dto_obj);
            }
        }

        return $ifr_obj;
    }

    public function get_so_obj($where = array())
    {
        return $this->get_so_dao()->get($where);
    }

    public function get_so_dao()
    {
        return $this->so_dao;
    }

    abstract function insert_so_fee_from_ria_record($batch_id, $status, $dto_obj);

    /**********************************
     *   create_interface_flex_so_fee is a helper function for child class
     *   to insert interface so_fee record, if it is not inserted through ria
     *   this function should be used
     **********************************/
    protected function create_interface_flex_so_fee($batch_id, $status, $dto_obj)
    {
        $ifsf_dao = $this->get_ifsf_dao();
        $ifsf_obj = $ifsf_dao->get();
        $ifsf_obj->set_so_no($dto_obj->get_so_no());
        $ifsf_obj->set_flex_batch_id($batch_id);
        $ifsf_obj->set_gateway_id($this->get_pmgw());
        $ifsf_obj->set_txn_id($dto_obj->get_txn_id());
        $ifsf_obj->set_txn_time($dto_obj->get_date());
        $ifsf_obj->set_currency_id($dto_obj->get_currency_id());
        //by nero, remove the abs
        $ifsf_obj->set_amount(ereg_replace(",", "", $dto_obj->get_commission()));

        $ifsf_obj->set_status($status);
        $ifsf_obj->set_batch_status("N");

        if (!$ifsf_obj->get_so_no()) {
            $ifsf_obj->set_so_no(" ");
            $ifsf_obj->set_batch_status("F");
            $ifsf_obj->set_failed_reason(Pmgw_report_service::WRONG_TRANSACTION_ID);
        }

        $ifsf_dao->insert($ifsf_obj);
        //var_dump($this->get_ifsf_dao()->db->last_query());die();
    }

    /**********************************
     *   create_interface_flex_refund is a helper function for child class
     *   to insert interface refund record + interface so fee record
     **********************************/
    protected function create_interface_flex_refund($batch_id, $status, $dto_obj, $include_fsf = false)
    {
        $ifrf_dao = $this->get_ifrf_dao();
        $ifrf_obj = $ifrf_dao->get();

        if (!$dto_obj->get_so_no() && $dto_obj->get_ref_txn_id()) {
            if ($so_obj = $this->get_so_obj(array("txn_id" => $dto_obj->get_ref_txn_id()))) {
                $dto_obj->set_so_no($so_obj->get_so_no());
            }
        }

        //var_dump($dto_obj);die();

        $ifrf_obj->set_so_no($dto_obj->get_so_no());
        $ifrf_obj->set_flex_batch_id($batch_id);
        $ifrf_obj->set_gateway_id($this->get_pmgw());
        $ifrf_obj->set_internal_txn_id($dto_obj->get_internal_txn_id());
        $ifrf_obj->set_txn_id($dto_obj->get_ref_txn_id());
        $ifrf_obj->set_txn_time($dto_obj->get_date());
        $ifrf_obj->set_currency_id($dto_obj->get_currency_id());
        //by nero
        $ifrf_obj->set_amount(ereg_replace(",", "", $dto_obj->get_amount()));
        $ifrf_obj->set_status($status);
        $ifrf_obj->set_batch_status("N");

        if (!$ifrf_obj->get_so_no()) {
            $ifrf_obj->set_so_no(" ");
            $ifrf_obj->set_batch_status("F");
            $ifrf_obj->set_failed_reason(Pmgw_report_service::WRONG_TRANSACTION_ID);
        }
//insert into so_fee
//$ifrf_dao->insert($ifrf_obj);
//var_dump($ifrf_dao->db->last_query());die();
        if ($ifrf_dao->insert($ifrf_obj) && $ifrf_obj->get_batch_status() != "F") {
            if ($include_fsf) {
                $this->insert_so_fee_from_refund_record($batch_id, $status, $dto_obj);
            }
        }
    }

    /************************************
     *   For Paypay, The refund record need to
     *   1. create the interface_flex_refund
     *   2. create the interface_flex_so_fee
     *************************************/
    abstract function insert_so_fee_from_refund_record($batch_id, $status, $dto_obj);

    /**********************************
     *   create_interface_flex_rolling_reserve is a helper function for child class
     *   to insert interface rolling reserve record
     **********************************/
    protected function create_interface_flex_rolling_reserve($batch_id, $status, $dto_obj, $include_fsf = false)
    {
        $ifrr_dao = $this->get_ifrr_dao();
        $ifrr_obj = $ifrr_dao->get();

        if (!$dto_obj->get_so_no() && $dto_obj->get_ref_txn_id()) {
            if ($so_obj = $this->get_so_obj(array("txn_id" => $dto_obj->get_ref_txn_id()))) {
                $dto_obj->set_so_no($so_obj->get_so_no());
            }
        }

        $ifrr_obj->set_so_no($dto_obj->get_so_no());
        $ifrr_obj->set_flex_batch_id($batch_id);
        $ifrr_obj->set_gateway_id($this->get_pmgw());
        $ifrr_obj->set_txn_id($dto_obj->get_ref_txn_id());

        $ifrr_obj->set_internal_txn_id($dto_obj->get_txn_id());

        $ifrr_obj->set_txn_time($dto_obj->get_date());
        $ifrr_obj->set_currency_id($dto_obj->get_currency_id());

        //by nero, remove the abs and replace the get_net to get_amount
        //$ifrr_obj->set_amount(ereg_replace(",", "", $dto_obj->get_net()));
        $ifrr_obj->set_amount(ereg_replace(",", "", $dto_obj->get_amount()));

        $ifrr_obj->set_status($status);
        $ifrr_obj->set_batch_status("N");

        if (!$ifrr_obj->get_so_no()) {
            $ifrr_obj->set_so_no(" ");
            $ifrr_obj->set_batch_status("F");
            $ifrr_obj->set_failed_reason(Pmgw_report_service::WRONG_TRANSACTION_ID);
        }

        if ($ifrr_dao->insert($ifrr_obj) && $ifrr_obj->get_batch_status() != "F") {
            if ($include_fsf) {
                $this->insert_so_fee_from_rolling_reserve_record($batch_id, $status, $dto_obj);
            }
        }

        return $ifrr_obj;
        //return $ifrr_dao->insert($ifrr_obj);
    }

    abstract function insert_so_fee_from_rolling_reserve_record($batch_id, $status, $dto_obj);

    /**********************************
     *   create_interface_flex_gateway_fee is a helper function for child class
     *   to insert interface gateway fee record
     **********************************/
    protected function create_interface_flex_gateway_fee($batch_id, $status, $dto_obj)
    {
        $ifgf_dao = $this->get_ifgf_dao();
        $ifgf_obj = $ifgf_dao->get();

        $ifgf_obj->set_flex_batch_id($batch_id);
        $ifgf_obj->set_gateway_id($this->get_pmgw());
        $ifgf_obj->set_txn_id($dto_obj->get_ref_txn_id());
        $ifgf_obj->set_txn_time($dto_obj->get_date());
        $ifgf_obj->set_currency_id($dto_obj->get_currency_id());
        $ifgf_obj->set_amount(ereg_replace(",", "", $dto_obj->get_amount()));
        $ifgf_obj->set_status($status);
        $ifgf_obj->set_batch_status("N");
        $result = $ifgf_dao->insert($ifgf_obj);

        //var_dump($ifgf_dao->db->last_query());die();
        return $result;
    }
}

/* End of file pmgw_report_service.php */
/* Location: ./system/application/libraries/service/Pmgw_report_service.php */