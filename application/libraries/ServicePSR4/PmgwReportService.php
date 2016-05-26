<?php
namespace ESG\Panther\Service;

abstract class PmgwReportService extends BaseService
{
    const WRONG_TRANSACTION_ID = "Wrong transaction id / so_no";
    const WRONG_CURRENCY_ID = "Wrong Currency ID";

    public function __construct()
    {
        $CI =& get_instance();
        $this->load = $CI->load;
        $this->load->helper(array('url', 'notice', 'image', 'object'));
        $this->contextConfigService = new ContextConfigService;
        $this->dataProcessService = new DataProcessService;
    }

    abstract public function isRiaIncludeSoFee();
    abstract public function isRefundIncludeSoFee();
    abstract protected function getPmgw();
    abstract public function isRiaRecord($dto_obj);
    abstract protected function insertInterfaceFlexRia($batch_id, $status, $dto_obj);
    abstract public function isRefundRecord($dto_obj);
    abstract protected function insertInterfaceFlexRefund($batch_id, $status, $dto_obj);
    abstract public function isSoFeeRecord($dto_obj);
    abstract protected function insertInterfaceFlexSoFee($batch_id, $status, $dto_obj);
    abstract public function isRollingReserveRecord($dto_obj);
    abstract protected function insertInterfaceFlexRollingReserve($batch_id, $status, $dto_obj);
    abstract public function isGatewayFeeRecord($dto_obj);
    abstract protected function insertInterfaceFlexGatewayFee($batch_id, $status, $dto_obj);
    abstract protected function afterInsertAllInterface($batch_id);
    abstract public function insertSoFeeFromRiaRecord($batch_id, $status, $dto_obj);
    abstract public function insertSoFeeFromRefundRecord($batch_id, $status, $dto_obj);
    abstract public function insertSoFeeFromRollingReserveRecord($batch_id, $status, $dto_obj);


    public function getSystemPlatform()
    {
        return "Panther";
    }

    public function processReport($filename)
    {
        $pmgw = $this->getPmgw();
        $batch_id = $this->insertBatch($filename);
        $output = $this->getFileData($filename);
        $batch_result = TRUE;
        $count_output = count((array)$output);
        if ($count_output > 0) {
            foreach ($output as $dto_obj) {
                $this->insertInterface($batch_id, $dto_obj);
            }
            $this->afterInsertAllInterface($batch_id);
            $batch_result = $this->insertMaster($batch_id);
            if ($batch_result) {
                $this->completeBatch($batch_id, "C");
            } else {
                $this->completeBatch($batch_id, "CE");
                $this->sendInvestigateReport($pmgw, $filename, $batch_id);
            }
        } else {
            $this->completeBatch($batch_id, "F");
            $this->sendInvestigateReport($pmgw, $filename, $batch_id);
        }
        $this->moveCompleteFile($filename);
        return array($batch_result, $batch_id);
    }

    public function insertBatch($filename)
    {
        set_time_limit(3000);
        if (is_file($this->getFolderPath() . $filename)) {
            $batch_dao = $this->getDao("FlexBatch");
            $batch_vo = $batch_dao->get();
            $batch_obj = $this->getDao("FlexBatch")->get(["filename" => $filename]);
            if (!$batch_obj) {
                $batch_obj = clone $batch_vo;
                $batch_obj->setGatewayId($this->getPmgw());
                $batch_obj->setFilename($filename);
                $batch_dao->insert($batch_obj);
                return $batch_obj->getId();
            } else {
                echo "file already in batch";
                exit;
            }
        } else {
            echo "file does not exists:<br />" . $this->getFolderPath() . $filename;
            exit;
        }
    }

    public function getFolderPath()
    {
        return $this->contextConfigService->valueOf("flex_pmgw_report_loaction") . $this->getPmgw() . "/";
    }

    public function getFileData($filename, $delimiter = ",")
    {
        $dex_srv = $this->dataProcessService;
        $result = $dex_srv->convert($this->getFolderPath() . $filename, APPPATH . $this->getDataExchangeFile(), TRUE, $delimiter, TRUE);
        return $result;
    }

    public function getDataExchangeFile()
    {
        return 'data/pmgw_report/pmgw_report_' . $this->getPmgw() . '.php';
    }

    protected function insertInterface($batch_id, $dto_obj)
    {
        if ($this->isRiaRecord($dto_obj)) {
            $this->insertInterfaceFlexRia($batch_id, 'RIA', $dto_obj);
        } else if ($refund_status = $this->isRefundRecord($dto_obj)) {
            $this->insertInterfaceFlexRefund($batch_id, $refund_status, $dto_obj);
        } else if ($fee_status = $this->isSoFeeRecord($dto_obj)) {
            $this->insertInterfaceFlexSoFee($batch_id, $fee_status, $dto_obj);
        } else if ($rolling_status = $this->isRollingReserveRecord($dto_obj)) {
            $this->insertInterfaceFlexRollingReserve($batch_id, $rolling_status, $dto_obj);
        } else if ($gateway_fee_status = $this->isGatewayFeeRecord($dto_obj)) {
            $this->insertInterfaceFlexGatewayFee($batch_id, $gateway_fee_status, $dto_obj);
        }
    }

    public function insertMaster($batch_id)
    {
        $return_result = TRUE;
        if ($this->insertFlexRia($batch_id) === FALSE) {
            $return_result = FALSE;
        }
        if ($this->insertFlexSoFee($batch_id) === FALSE) {
            $return_result = FALSE;
        }
        if ($this->insertFlexRefund($batch_id) === FALSE) {
            $return_result = FALSE;
        }
        if ($this->insertFlexRollingReserve($batch_id) === FALSE) {
            $return_result = FALSE;
        }
        if ($this->insertFlexGatewayFee($batch_id) === FALSE) {
            $return_result = FALSE;
        }
        return $return_result;
    }

    public function insertFlexRia($batch_id)
    {
        $ifr_list = $this->getDao('InterfaceFlexRia')->getFlexRiaByBatch($batch_id);
        if ($ifr_list) {
            $return_result = TRUE;
            foreach ($ifr_list AS $ifr_obj) {
                if ($ifr_obj->getBatchStatus() == 'N') {
                    $fr_dao = $this->getDao('FlexRia');
                    $ifr_dao = $this->getDao('InterfaceFlexRia');
                    $fr_vo = $fr_dao->get();

                    if ($fr_obj = $fr_dao->get(array("so_no" => $ifr_obj->getSoNo(), "gateway_id" => $ifr_obj->getGatewayId(), "status" => $ifr_obj->getStatus(), "txn_time" => $ifr_obj->getTxnTime()))) {
                        if (($fr_obj->getAmount() == $ifr_obj->getAmount())) {
                            $this->updateInterfaceFlexRiaStatusByGroup($batch_id, $ifr_obj->getSoNo(), $ifr_obj->getStatus(), "C", "duplicated record");
                        } else {
                            $fr_obj->setFlexBatchId($ifr_obj->getFlexBatchId());
                            $fr_obj->setAmount($ifr_obj->getAmount());
                            if ($fr_dao->update($fr_obj) !== FALSE) {
                                $this->updateInterfaceFlexRiaStatusByGroup($batch_id, $ifr_obj->getSoNo(), $ifr_obj->getStatus(), "I", "record updated on " . date("Y-m-d H:i:s"));
                                $return_result = FALSE;
                            } else {
                                $this->updateInterfaceFlexRiaStatusByGroup($batch_id, $ifr_obj->getSoNo(), $ifr_obj->getStatus(), "F", "update record error: " . $fr_dao->db->_error_message());
                                $return_result = FALSE;
                            }
                        }
                    } else {
                        $fr_obj = clone $fr_vo;
                        set_value($fr_obj, $ifr_obj);
                        if ($this->validTxnId($fr_obj)) {
                            if ($fr_dao->insert($fr_obj) !== FALSE) {
                                $this->updateInterfaceFlexRiaStatusByGroup($batch_id, $ifr_obj->getSoNo(), $ifr_obj->getStatus(), "S", "");
                            } else {
                                if ($failed_reason = $this->validSoNo($ifr_obj)) {
                                    $ifr_obj->setFailedReason($failed_reason);
                                } else {
                                    $ifr_obj->setFailedReason($fr_dao->db->_error_message());
                                }
                                $this->updateInterfaceFlexRiaStatusByGroup($batch_id, $ifr_obj->getSoNo(), $ifr_obj->getStatus(), "F", $ifr_obj->getFailedReason());
                                $return_result = FALSE;
                            }
                        } else {
                            $this->updateInterfaceFlexRiaStatusByGroup($batch_id, $ifr_obj->getSoNo(), $ifr_obj->getStatus(), "F", "invalid txn_id");
                            $return_result = FALSE;
                        }
                    }
                } elseif ($ifr_obj->getBatchStatus() == 'F') {
                    $return_result = FALSE;
                }
            }
            return $return_result;
        }
        return TRUE;
    }

    private function updateInterfaceFlexRiaStatusByGroup($batch_id, $so_no, $status, $batch_status, $failed_reason)
    {
        $ifr_dao = $this->getDao('InterfaceFlexRia');
        $ifrObjs = $ifr_dao->getList(["flex_batch_id" => $batch_id,"gateway_id" => $this->getPmgw(),"so_no" => $so_no,"status" => $status], ["limit" => -1]);
        if ($ifrObjs) {
            foreach ($ifrObjs as $ifrObj) {
                $ifrObj->setBatchStatus($batch_status);
                if ($failed_reason) {
                    $ifrObj->setFailedReason($failed_reason);
                }
                $ifr_dao->update($ifrObj);
            }
        }
    }

    public function validTxnId($interface_obj)
    {
        return true;
    }

    public function validSoNo($interface_obj)
    {
        return false;
    }

    public function insertFlexSoFee($batch_id)
    {
        $ifsf_list = $this->getDao('InterfaceFlexSoFee')->getSoFeeByBatch($batch_id);
        if ($ifsf_list) {
            $return_result = TRUE;
            foreach ($ifsf_list AS $ifsf_obj) {
                if ($ifsf_obj->getBatchStatus() == 'N') {
                    $fsf_dao = $this->getDao('FlexSoFee');
                    $ifsf_dao = $this->getDao('InterfaceFlexSoFee');
                    $fsf_vo = $fsf_dao->get();
                    $where = ["so_no" => $ifsf_obj->getSoNo(), "gateway_id" => $ifsf_obj->getGatewayId(), "status" => $ifsf_obj->getStatus(), "txn_id" => $ifsf_obj->getTxnId(), "txn_time" => $ifsf_obj->getTxnTime()];
                    if (($fsf_obj = $fsf_dao->getList($where, array('limit'=>1)))) {
                        if (($fsf_obj->getAmount() == $ifsf_obj->getAmount())) {
                            $this->updateInterfaceSoFeeStatusByGroup($batch_id, $ifsf_obj->getSoNo(), $ifsf_obj->getStatus(), "C", "duplicated record");
                        } else {
                            $fsf_obj->setFlexBatchId($ifsf_obj->getFlexBatchId());
                            $fsf_obj->setAmount($ifsf_obj->getAmount());

                            if ($fsf_dao->update($fsf_obj) !== FALSE) {
                                $this->updateInterfaceSoFeeStatusByGroup($batch_id, $ifsf_obj->getSoNo(), $ifsf_obj->getStatus(), "I", "record updated on " . date("Y-m-d H:i:s"));
                            } else {
                                $this->updateInterfaceSoFeeStatusByGroup($batch_id, $ifsf_obj->getSoNo(), $ifsf_obj->getStatus(), "F", "update record error: " . $fsf_dao->db->_error_message());
                                $return_result = FALSE;
                            }
                        }
                    } else {
                        $fsf_obj = clone $fsf_vo;
                        set_value($fsf_obj, $ifsf_obj);
                        if ($this->validTxnId($fsf_obj)) {
                            if ($fsf_dao->insert($fsf_obj) !== FALSE) {
                                $this->updateInterfaceSoFeeStatusByGroup($batch_id, $ifsf_obj->getSoNo(), $ifsf_obj->getStatus(), "S", "");
                            } else {
                                if ($failed_reason = $this->validSoNo($ifsf_obj)) {
                                    $errorMessage = $failed_reason;
                                } else {
                                    $errorMessage = $fsf_dao->db->_error_message();
                                }
                                $this->updateInterfaceSoFeeStatusByGroup($batch_id, $ifsf_obj->getSoNo(), $ifsf_obj->getStatus(), "F", $errorMessage);
                                $return_result = FALSE;
                            }
                        } else {
                            $this->updateInterfaceSoFeeStatusByGroup($batch_id, $ifsf_obj->getSoNo(), $ifsf_obj->getStatus(), "F", "invalid txn_id");
                            $return_result = FALSE;
                        }
                    }
                } elseif ($ifsf_obj->getBatchStatus() == 'F') {
                    $return_result = FALSE;
                }
            }
            return $return_result;
        }
        return TRUE;
    }

    public function updateInterfaceSoFeeStatusByGroup($batch_id, $so_no, $status, $batch_status, $failed_reason)
    {
        $ifsf_dao = $this->getDao('InterfaceFlexSoFee');
        $ifsfObjs = $ifsf_dao->getList(["flex_batch_id" => $batch_id,"gateway_id" => $this->getPmgw(),"so_no" => $so_no,"status" => $status], ["limit" => -1]);
        foreach ($ifsfObjs as $ifsfObj) {
            $ifsfObj->setBatchStatus($batch_status);
            if ($failed_reason) {
                $ifsfObj->setFailedReason($failed_reason);
            }
            $ifsf_dao->update($ifsfObj);
        }
    }

    public function insertFlexRefund($batch_id)
    {
        $ifrf_list = $this->getDao('InterfaceFlexRefund')->getFlexRefundByBatch($batch_id);
        if ($ifrf_list) {
            $return_result = TRUE;
            foreach ($ifrf_list AS $ifrf_obj) {
                if ($ifrf_obj->getBatchStatus() == 'N') {
                    $frf_dao = $this->getDao('FlexRefund');
                    $ifrf_dao = $this->getDao('InterfaceFlexRefund');
                    $frf_vo = $frf_dao->get();
                    if ($frf_obj = $frf_dao->get(["so_no" => $ifrf_obj->getSoNo(), "gateway_id" => $ifrf_obj->getGatewayId(), "status" => $ifrf_obj->getStatus(), "internal_txn_id" => $ifrf_obj->getInternalTxnId(), "txn_time" => $ifrf_obj->getTxnTime()])) {
                        if (($frf_obj->getAmount() == $ifrf_obj->getAmount())) {
                            $this->updateInterfaceFlexRefundStatusByGroup($batch_id, $ifrf_obj->getSoNo(), $ifrf_obj->getStatus(), "C", "duplicated record");
                        } else {
                            $frf_obj->setFlexBatchId($ifrf_obj->getFlexBatchId());
                            $frf_obj->setAmount($ifrf_obj->getAmount());
                            if ($frf_dao->update($frf_obj) !== FALSE) {
                                $this->updateInterfaceFlexRefundStatusByGroup($batch_id, $ifrf_obj->getSoNo(), $ifrf_obj->getStatus(), "I", "record updated on " . date("Y-m-d H:i:s"));
                                $return_result = FALSE;
                            } else {
                                $this->updateInterfaceFlexRefundStatusByGroup($batch_id, $ifrf_obj->getSoNo(), $ifrf_obj->getStatus(), "F", "update record error: " . $frf_dao->db->_error_message());
                                $return_result = FALSE;
                            }
                        }
                    } else {
                        $frf_obj = clone $frf_vo;
                        set_value($frf_obj, $ifrf_obj);
                        if ($this->validTxnId($frf_obj)) {
                            if ($frf_dao->insert($frf_obj) !== FALSE) {
                                $this->updateInterfaceFlexRefundStatusByGroup($batch_id, $ifrf_obj->getSoNo(), $ifrf_obj->getStatus(), "S", "");
                            } else {
                                if ($failed_reason = $this->validSoNo($ifrf_obj)) {
                                    $ifrf_obj->setFailedReason($failed_reason);
                                } else {
                                    $ifrf_obj->setFailedReason($frf_dao->db->_error_message());
                                }
                                $this->updateInterfaceFlexRefundStatusByGroup($batch_id, $ifrf_obj->getSoNo(), $ifrf_obj->getStatus(), "F", $ifrf_obj->getFailedReason());
                                $return_result = FALSE;
                            }
                        } else {
                            $this->updateInterfaceFlexRefundStatusByGroup($batch_id, $ifrf_obj->getSoNo(), $ifrf_obj->getStatus(), "F", "invalid txn_id");
                            $return_result = FALSE;
                        }
                    }
                } elseif ($ifrf_obj->getBatchStatus() == 'F') {
                    $return_result = FALSE;
                }
            }
            return $return_result;
        }
        return TRUE;
    }

    private function updateInterfaceFlexRefundStatusByGroup($batch_id, $so_no, $status, $batch_status, $failed_reason)
    {
        $ifrf_dao = $this->getDao('InterfaceFlexRefund');
        $ifrfObjs = $ifrf_dao->getList(["flex_batch_id" => $batch_id,"gateway_id" => $this->getPmgw(),"so_no" => $so_no,"status" => $status], ["limit" => -1]);
        if ($ifrfObjs) {
            foreach ($ifrfObjs as $ifrfObj) {
                $ifrfObj->setBatchStatus($batch_status);
                if ($failed_reason) {
                    $ifrfObj->setFailedReason($failed_reason);
                }
                $ifrf_dao->update($ifrfObj);
            }
        }
    }

    public function insertFlexRollingReserve($batch_id)
    {
        $ifrr_list = $this->getDao('InterfaceFlexRollingReserve')->getRollingReserveByBatch($batch_id);
        if ($ifrr_list) {
            $return_result = TRUE;
            foreach ($ifrr_list AS $ifrr_obj) {
                if ($ifrr_obj->getBatchStatus() == 'N') {
                    $frr_dao = $this->getDao("FlexRollingReserve");
                    $ifrr_dao = $this->getDao("InterfaceFlexRollingReserve");
                    $frr_vo = $frr_dao->get();
                    if ($frr_obj = $frr_dao->get(array("so_no" => $ifrr_obj->getSoNo(), "gateway_id" => $ifrr_obj->getGatewayId(), "status" => $ifrr_obj->getStatus(), "internal_txn_id" => $ifrr_obj->getInternalTxnId(), "txn_time" => $ifrr_obj->getTxnTime()))) {
                        if (($frr_obj->getAmount() == $ifrr_obj->getAmount())) {
                            $ifrr_obj->setBatchStatus("C");
                            $ifrr_obj->setFailedReason("duplicated record");
                            $ifrr_dao->update($ifrr_obj);
                        } else {
                            $frr_obj->setFlexBatchId($ifrr_obj->getFlexBatchId());
                            $frr_obj->setAmount($ifrr_obj->getAmount());
                            if ($frr_dao->update($frr_obj) !== FALSE) {
                                $ifrr_obj->setBatchStatus("I");
                                $ifrr_obj->setFailedReason("record updated on " . date("Y-m-d H:i:s"));
                                $ifrr_dao->update($ifrr_obj);
                            } else {
                                $ifrr_obj->setBatchStatus("F");
                                $ifrr_obj->setFailedReason("update record error: " . $frr_dao->db->_error_message());
                                $ifrr_dao->update($ifrr_obj);
                                $return_result = FALSE;
                            }
                        }
                    } else {
                        $frr_obj = clone $frr_vo;
                        set_value($frr_obj, $ifrr_obj);
                        if ($this->validTxnId($frr_obj)) {
                            if ($frr_dao->insert($frr_obj) !== FALSE) {
                                $ifrr_obj->setBatchStatus("S");
                                $ifrr_dao->update($ifrr_obj);
                            } else {
                                if ($failed_reason = $this->validSoNo($ifrr_obj)) {
                                    $ifrr_obj->setFailedReason($failed_reason);
                                } else {
                                    $ifrr_obj->setFailedReason($frr_dao->db->_error_message());
                                }
                                $ifrr_obj->setBatchStatus("F");
                                $ifrr_dao->update($ifrr_obj);
                                $return_result = FALSE;
                            }
                        } else {
                            $ifrr_obj->setFailedReason("invalid txn_id");
                            $ifrr_obj->setBatchStatus("F");
                            $ifrr_dao->update($ifrr_obj);
                            $return_result = FALSE;
                        }
                    }
                } elseif ($ifrr_obj->getBatchStatus() == 'F') {
                    $return_result = FALSE;
                }
            }
            return $return_result;
        }

        return TRUE;
    }


    public function insertFlexGatewayFee($batch_id)
    {
        $ifgf_list = $this->getDao("InterfaceFlexGatewayFee")->getList(["flex_batch_id" => $batch_id], ["limit" => "-1"]);
        if ($ifgf_list) {
            $return_result = TRUE;
            foreach ($ifgf_list AS $ifgf_obj) {
                if ($ifgf_obj->getBatchStatus() == 'N') {
                    $fgf_dao = $this->getDao("FlexGatewayFee");
                    $ifgf_dao = $this->getDao("InterfaceFlexGatewayFee");
                    $fgf_vo = $fgf_dao->get();
                    if ($fgf_obj = $fgf_dao->get(["txn_id" => $ifgf_obj->getTxnId(), "gateway_id" => $ifgf_obj->getGatewayId(), "status" => $ifgf_obj->getStatus(), "txn_time" => $ifgf_obj->getTxnTime()])) {
                        if (($fgf_obj->getAmount() == $ifgf_obj->getAmount())) {
                            $ifgf_obj->setBatchStatus("C");
                            $ifgf_obj->setFailedReason("duplicated record");
                            $ifgf_dao->update($ifgf_obj);
                        } else {
                            $fgf_obj->setFlexBatchId($ifgf_obj->getFlexBatchId());
                            $fgf_obj->setAmount($ifgf_obj->getAmount());
                            if ($fgf_dao->update($fgf_obj) !== FALSE) {
                                $ifgf_obj->setBatchStatus("I");
                                $ifgf_obj->setFailedReason("record updated on " . date("Y-m-d H:i:s"));
                                $ifgf_dao->update($ifgf_obj);
                            } else {
                                $ifgf_obj->setBatchStatus("F");
                                $ifgf_obj->setFailedReason("update record error: " . $fgf_dao->db->_error_message());
                                $ifgf_dao->update($ifgf_obj);
                                $return_result = FALSE;
                            }
                        }
                    } else {
                        $fgf_obj = clone $fgf_vo;
                        set_value($fgf_obj, $ifgf_obj);
                        if ($fgf_dao->insert($fgf_obj) !== FALSE) {
                            $ifgf_obj->setBatchStatus("S");
                            $ifgf_dao->update($ifgf_obj);
                        } else {
                            $ifgf_obj->setFailedReason($fgf_dao->db->_error_message());
                            $ifgf_obj->setBatchStatus("F");
                            $ifgf_dao->update($ifgf_obj);
                            $return_result = FALSE;
                        }
                    }
                } elseif ($ifgf_obj->getBatchStatus() == 'F') {
                    $return_result = FALSE;
                }
            }
            return $return_result;
        }
        return TRUE;
    }

    public function completeBatch($batch_id, $status)
    {
        $batch_obj = $this->getDao('FlexBatch')->get(["id" => $batch_id]);
        $batch_obj->setStatus($status);
        $this->getDao('FlexBatch')->update($batch_obj);
    }

    public function sendInvestigateReport($pmgw, $filename, $batch_id)
    {
        $total_err = 0;
        $message = "Payment Gateway: " . $pmgw . "\r\n";
        $message .= "File Name: " . $filename . "\r\n";
        $message .= "Batch ID: " . $batch_id . "\r\n\r\n";
        if ($ifr_list = $this->getDao('InterfaceFlexRia')->getList(["flex_batch_id" => $batch_id, "batch_status IN ('F', 'I')" => null], ["limit" => -1])) {
            if (count((array)$ifr_list) > 0) {
                $total_err += count((array)$ifr_list);
                $message .= "RIA:\r\n";
                $message .= "txn_id,so_no,failed_reason\r\n";
                foreach ($ifr_list as $ifr_obj) {
                    $message .= $ifr_obj->getTxnId() . "," . $ifr_obj->getSoNo() . "," . $ifr_obj->getFailedReason() . "\r\n";
                }
                $message .= "\r\n\r\n";
            }
        }
        if ($ifrf_list = $this->getDao("InterfaceFlexRefund")->getList(["flex_batch_id" => $batch_id, "batch_status IN ('F', 'I')" => null], ["limit" => -1])) {
            if (count((array)$ifrf_list) > 0) {
                $total_err += count((array)$ifrf_list);
                $message .= "Refund:\r\n";
                $message .= "txn_id,so_no,failed_reason\r\n";
                foreach ($ifrf_list as $ifrf_obj) {
                    $message .= $ifrf_obj->getTxnId() . "," . $ifrf_obj->getSoNo() . "," . $ifrf_obj->getFailedReason() . "\r\n";
                }
                $message .= "\r\n\r\n";
            }
        }
        if ($ifsf_list = $this->getDao('InterfaceFlexSoFee')->getList(["flex_batch_id" => $batch_id, "batch_status IN ('F', 'I')" => null], ["limit" => -1])) {
            if (count((array)$ifsf_list) > 0) {
                $total_err += count((array)$ifsf_list);

                $message .= "So Fee:\r\n";
                $message .= "txn_id,so_no,failed_reason\r\n";

                foreach ($ifsf_list as $ifsf_obj) {
                    $message .= $ifsf_obj->getTxnId() . "," . $ifsf_obj->getSoNo() . "," . $ifsf_obj->getFailedReason() . "\r\n";
                }
                $message .= "\r\n\r\n";
            }
        }

        if ($ifrr_list = $this->getDao('InterfaceFlexRollingReserve')->getList(["flex_batch_id" => $batch_id, "batch_status IN ('F', 'I')" => null], ["limit" => -1])) {
            if (count((array)$ifrr_list) > 0) {
                $total_err += count((array)$ifrr_list);

                $message .= "Rolling Reserve:\r\n";
                $message .= "txn_id,so_no,failed_reason\r\n";

                foreach ($ifrr_list as $ifrr_obj) {
                    $message .= $ifrr_obj->getTxnId() . "," . $ifrr_obj->getSoNo() . "," . $ifrr_obj->getFailedReason() . "\r\n";
                }
                $message .= "\r\n\r\n";
            }
        }

        if ($ifgf_list = $this->getDao("InterfaceFlexGatewayFee")->getList(["flex_batch_id" => $batch_id, "batch_status IN ('F', 'I')" => null], ["limit" => -1])) {
            if (count((array)$ifgf_list) > 0) {
                $total_err += count((array)$ifgf_list);
                $message .= "Gateway Fee:\r\n";
                $message .= "txn_id,failed_reason\r\n";

                foreach ($ifgf_list as $ifgf_obj) {
                    $message .= $ifgf_obj->getTxnId() . "," . $ifgf_obj->getFailedReason() . "\r\n";
                }
                $message .= "\r\n\r\n";
            }
        }

        if ($total_err > 0) {
            mail("flexadmin@valuebasket.com", "[Panther] Gateway Report Error", $message);
        }
    }

    public function moveCompleteFile($filename)
    {
        if (copy($this->getFolderPath() . $filename, $this->getFolderPath() . "complete/" . $filename)) {
            unlink($this->getFolderPath() . $filename);
        }
    }

    public function reprocessRecord($interface_obj)
    {
        $this->updateRecord($interface_obj);
    }

    public function getPmgwFailedRecord($where = [], $option = [])
    {
        return $this->get_ifpt_dao()->getPmgw_failed_record($where, $option);
    }

    public function getBatchFuncName()
    {
        return "upload_pmgw_report_" . $this->getPmgw();
    }

    protected function createInterfaceFlexRia($batch_id, $status, $dto_obj, $include_fsf = true)
    {
        $ifr_dao = $this->getDao("InterfaceFlexRia");
        $ifr_obj = $ifr_dao->get();
        if (!$dto_obj->getSoNo() && $dto_obj->getTxnId()) {
            if ($so_obj = $this->getSoObj(["txn_id" => $dto_obj->getTxnId()])) {
                $dto_obj->setSoNo($so_obj->getSoNo());
            }
        }
        $ifr_obj->setSoNo($dto_obj->getSoNo());
        $ifr_obj->setFlexBatchId($batch_id);
        $ifr_obj->setGatewayId($this->getPmgw());
        $ifr_obj->setTxnId($dto_obj->getTxnId());
        $ifr_obj->setTxnTime($dto_obj->getDate());
        $ifr_obj->setCurrencyId($dto_obj->getCurrencyId());
        $ifr_obj->setAmount($dto_obj->getAmount());
        $ifr_obj->setStatus($status);
        $ifr_obj->setBatchStatus("N");
        if (!$ifr_obj->getSoNo()) {
            $ifr_obj->setSoNo(" ");
            $ifr_obj->setBatchStatus("F");
            $ifr_obj->setFailedReason(PmgwReportService::WRONG_TRANSACTION_ID);
        }
        if ($ifr_dao->insert($ifr_obj) && $ifr_obj->getBatchStatus() != "F") {
            if ($include_fsf) {
                $this->insertSoFeeFromRiaRecord($batch_id, $status, $dto_obj);
            }
        }
        return $ifr_obj;
    }

    protected function createInterfaceFlexSoFee($batch_id, $status, $dto_obj)
    {
        $ifsf_dao = $this->getDao('InterfaceFlexSoFee');
        $ifsf_obj = $ifsf_dao->get();
        $ifsf_obj->setSoNo($dto_obj->getSoNo());
        $ifsf_obj->setFlexBatchId($batch_id);
        $ifsf_obj->setGatewayId($this->getPmgw());
        $ifsf_obj->setTxnId($dto_obj->getTxnId());
        $ifsf_obj->setTxnTime($dto_obj->getDate());
        $ifsf_obj->setCurrencyId($dto_obj->getCurrencyId());
        $ifsf_obj->setAmount(ereg_replace(",", "", $dto_obj->getCommission()));
        $ifsf_obj->setStatus($status);
        $ifsf_obj->setBatchStatus("N");
        if (!$ifsf_obj->getSoNo()) {
            $ifsf_obj->setSoNo(" ");
            $ifsf_obj->setBatchStatus("F");
            $ifsf_obj->setFailedReason(PmgwReportService::WRONG_TRANSACTION_ID);
        }
        $ifsf_dao->insert($ifsf_obj);
    }

    protected function createInterfaceFlexRefund($batch_id, $status, $dto_obj, $include_fsf = false)
    {
        $ifrf_dao = $this->getDao('InterfaceFlexRefund');
        $ifrf_obj = $ifrf_dao->get();
        if (!$dto_obj->getSoNo() && $dto_obj->getRefTxnId()) {
            if ($so_obj = $this->getDao('So')->get(array("txn_id" => $dto_obj->getRefTxnId()))) {
                $dto_obj->setSoNo($so_obj->getSoNo());
            }
        }
        $ifrf_obj->setSoNo($dto_obj->getSoNo());
        $ifrf_obj->setFlexBatchId($batch_id);
        $ifrf_obj->setGatewayId($this->getPmgw());
        $ifrf_obj->setInternalTxnId($dto_obj->getInternalTxnId());
        $ifrf_obj->setTxnId($dto_obj->getRefTxnId());
        $ifrf_obj->setTxnTime($dto_obj->getDate());
        $ifrf_obj->setCurrencyId($dto_obj->getCurrencyId());
        $ifrf_obj->setAmount(ereg_replace(",", "", $dto_obj->getAmount()));
        $ifrf_obj->setStatus($status);
        $ifrf_obj->setBatchStatus("N");
        if (!$ifrf_obj->getSoNo()) {
            $ifrf_obj->setSoNo(" ");
            $ifrf_obj->setBatchStatus("F");
            $ifrf_obj->setFailedReason(PmgwReportService::WRONG_TRANSACTION_ID);
        }
        if ($ifrf_dao->insert($ifrf_obj) && $ifrf_obj->getBatchStatus() != "F") {
            if ($include_fsf) {
                $this->insertSoFeeFromRefundRecord($batch_id, $status, $dto_obj);
            }
        }
    }

    protected function createInterfaceFlexRollingReserve($batch_id, $status, $dto_obj, $include_fsf = false)
    {
        $ifrr_dao = $this->getDao('InterfaceFlexRollingReserve');
        $ifrr_obj = $ifrr_dao->get();
        if (!$dto_obj->getSoNo() && $dto_obj->getRefTxnId()) {
            if ($so_obj = $this->getSoObj(["txn_id" => $dto_obj->getRefTxnId()])) {
                $dto_obj->setSoNo($so_obj->getSoNo());
            }
        }
        $ifrr_obj->setSoNo($dto_obj->getSoNo());
        $ifrr_obj->setFlexBatchId($batch_id);
        $ifrr_obj->setGatewayId($this->getPmgw());
        $ifrr_obj->setTxnId($dto_obj->getRefTxnId());
        $ifrr_obj->setInternalTxnId($dto_obj->getTxnId());
        $ifrr_obj->setTxnTime($dto_obj->getDate());
        $ifrr_obj->setCurrencyId($dto_obj->getCurrencyId());
        $ifrr_obj->setAmount(ereg_replace(",", "", $dto_obj->getAmount()));
        $ifrr_obj->setStatus($status);
        $ifrr_obj->setBatchStatus("N");
        if (!$ifrr_obj->getSoNo()) {
            $ifrr_obj->setSoNo(" ");
            $ifrr_obj->setBatchStatus("F");
            $ifrr_obj->setFailedReason(PmgwReportService::WRONG_TRANSACTION_ID);
        }
        if ($ifrr_dao->insert($ifrr_obj) && $ifrr_obj->getBatchStatus() != "F") {
            if ($include_fsf) {
                $this->insertSoFeeFromRollingReserveRecord($batch_id, $status, $dto_obj);
            }
        }
        return $ifrr_obj;
    }

    protected function createInterfaceFlexGatewayFee($batch_id, $status, $dto_obj)
    {
        $ifgf_dao = $this->getDao("InterfaceFlexGatewayFee");
        $ifgf_obj = $ifgf_dao->get();
        $ifgf_obj->setFlexBatchId($batch_id);
        $ifgf_obj->setGatewayId($this->getPmgw());
        $ifgf_obj->setTxnId($dto_obj->getRefTxnId());
        $ifgf_obj->setTxnTime($dto_obj->getDate());
        $ifgf_obj->setCurrencyId($dto_obj->getCurrencyId());
        $ifgf_obj->setAmount(ereg_replace(",", "", $dto_obj->getAmount()));
        $ifgf_obj->setStatus($status);
        $ifgf_obj->setBatchStatus("N");
        $result = $ifgf_dao->insert($ifgf_obj);
        return $result;
    }
}