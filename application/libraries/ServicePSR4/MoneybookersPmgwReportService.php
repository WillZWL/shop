<?php
namespace ESG\Panther\Service;
class MoneybookersPmgwReportService extends PmgwReportService
{
    private $_report_currency_id = "";

    public function __construct()
    {
        parent::__construct();
    }

    public function isRiaIncludeSoFee()
    {
        return false;
    }

    public function isRefundIncludeSoFee()
    {
        return false;
    }

    public function isRiaRecord($dto_obj)
    {
        if (($dto_obj->getType() == "Receive Money") && (substr($dto_obj->getTransactionDetail(), 0, 4) == "from")) {
            return true;
        }
        return false;
    }

    public function isSoFeeRecord($dto_obj)
    {
        if ((($dto_obj->getTransactionDetail() == "Per Transaction Fee") && ($dto_obj->getType() == "Receive Money"))) {
            return "M_PTF";
        } elseif (($dto_obj->getTransactionDetail() == "Fee") && ($dto_obj->getType() == "Receive Money")) {
            return "M_F";
        } elseif (($dto_obj->getTransactionDetail() == "Merchant Refund Fee") && ($dto_obj->getType() == "Withdraw")) {
            return "M_MRF_W";
        } elseif (($dto_obj->getTransactionDetail() == "Merchant Refund Fee") && ($dto_obj->getType() == "Upload")) {
            return "M_MRF_U";
        } elseif (($dto_obj->getTransactionDetail() == "Chargeback Fee") && ($dto_obj->getType() == "Withdraw")) {
            return "M_CBF";
        } elseif (($dto_obj->getType() == "Receive Money Cancellation")) {
            $transaction_details = $dto_obj->getTransactionDetail();
            if (substr($transaction_details, 0, 28) == "Return 'Per Transaction Fee'") {
                return "M_RMC_PTF";
            } elseif (substr($transaction_details, 0, 12) == "Return 'Fee'") {
                return "M_RMC_F";
            } else {
                return false;
            }
        }

        return false;
    }

    public function isRefundRecord($dto_obj)
    {
        if (($dto_obj->getTransactionDetail() == "Merchant Refund") && ($dto_obj->getType() == "Withdraw")) {
            return 'R';
        } elseif (($dto_obj->getType() == "Receive Money Cancellation")) {
            $transaction_details = $dto_obj->getTransactionDetail();
            if (preg_match('/Cancel \'from .*@.*\'/i', $transaction_details)) {
                return "R";
            }
            return false;
        } elseif (($dto_obj->getTransactionDetail() == "Merchant Refund") && ($dto_obj->getType() == "Upload")) {
            return 'R';
        } elseif (($dto_obj->getTransactionDetail() == "Chargeback") && ($dto_obj->getType() == "Withdraw")) {
            return 'CB';
        }
        return false;
    }

    public function isGatewayFeeRecord($dto_obj)
    {
        if (($dto_obj->getTransactionDetail() == "Fee") && ($dto_obj->getType() == "Withdraw")) {
            return 'BTU';
        }
        return false;
    }

    public function isRollingReserveRecord($dto_obj)
    {
        return false;
    }

    public function getFileData($filename, $delimiter = ",")
    {
        $currency = "";
        if ($fileHandle = fopen($this->getFolderPath() . $filename, 'r')) {
            $line = fgets($fileHandle);
            if ($line) {
                $headerArray = explode(",", $line);
                $currency = $headerArray[4];
                preg_match("/[A-Z]+/",$currency,$currency_arr);//get currency
                $this->_report_currency_id = $currency_arr[0];
            }
            fclose($fileHandle);
        }

        if ($this->_report_currency_id != "") {
            return parent::getFileData($filename, $delimiter);
        } else {
            $subject = "[" . $this->getSystemPlatform() . "] Flex Tools - " . $this->getPmgw() . " No correct currency!!";
            $message = $line . "\r\n";
            $message .= "File:" . $this->getFolderPath() . $filename . "\r\n";
            mail($this->getContactEmail(), $subject, $message, "From: website@chatandvision.com\r\n");
            return array();
        }
    }

    protected function getPmgw()
    {
        return "moneybookers";
    }

    public function getContactEmail()
    {
        return 'will.zhang@eservicesgroup.com';
    }

    public function validTxnId($interface_obj)
    {
        $i_txn_id = $interface_obj->getTxnId();
        $i_so_no = $interface_obj->getSoNo();
        if ($this->getDao('So')->get(array("txn_id" => $i_txn_id))) {
            return true;
        } elseif ($this->getDao('So')->get(array("so_no" => $i_so_no))) {
            return true;
        } else {
            return false;
        }
    }

    public function afterInsertAllInterface($batch_id)
    {
        $where["flex_batch_id"] = $batch_id;
        $where["so_no"] = " ";
        if ($empty_so_no_obj_list = $this->getDao('InterfaceFlexRefund')->getList($where, array("limit" => -1))) {
            $related_txn_id = array("getInternalTxnId", "getTxnId");
            foreach ($empty_so_no_obj_list as $nut_obj) {
                foreach ($related_txn_id as $method) {
                    $txn_id = $nut_obj->$method();
                    if ($ria_obj = $this->getDao('InterfaceFlexRia')->get(array("txn_id" => $txn_id))) {
                        if ($so_no = $ria_obj->getSoNo()) {
                            $nut_obj->setSoNo($so_no);
                            $nut_obj->setBatchStatus("N");
                            $nut_obj->setFailedReason("");
                            $this->getDao('InterfaceFlexRefund')->update($nut_obj);
                            continue;
                        }
                    }
                }
            }
        }

        unset($where);
        $where["flex_batch_id"] = $batch_id;
        $where["so_no"] = " ";
        if ($empty_so_no_obj_list = $this->getDao('InterfaceFlexSoFee')->getList($where, array("limit" => -1))) {
            foreach ($empty_so_no_obj_list as $nut_obj) {
                $txn_id = $nut_obj->getTxnId();
                $search_fields = array("txn_id", "internal_txn_id");
                foreach ($search_fields as $field) {
                    if ($refund_obj = $this->getDao('InterfaceFlexRefund')->get(array($field => $txn_id, "gateway_id" => $this->getPmgw()))) {
                        if ($so_no = $refund_obj->getSoNo()) {
                            $nut_obj->setSoNo($so_no);
                            $nut_obj->setBatchStatus("N");
                            $nut_obj->setFailedReason("");
                            $this->getDao('InterfaceFlexSoFee')->update($nut_obj);
                            continue;
                        }
                    }
                }
            }
        }

        //move those NULL so no so_fee record to gateway_fee table
        unset($where);
        $where["flex_batch_id"] = $batch_id;
        $where["so_no"] = " ";
        $where["failed_reason"] = PmgwReportService::WRONG_TRANSACTION_ID;
        if ($empty_so_no_obj_list = $this->getDao('InterfaceFlexSoFee')->getList($where, array("limit" => -1))) {
            foreach ($empty_so_no_obj_list as $nut_obj) {
                $txn_id = $nut_obj->getTxnId();
                if ($so_obj = $this->getDao('InterfaceFlexRefund')->get(array("txn_id" => $txn_id, "gateway_id" => $this->getPmgw()))) {
                    $nut_obj->setSoNo($so_obj->getSoNo());
                    $nut_obj->setBatchStatus("N");
                    $nut_obj->setFailedReason("");
                    $this->getDao('InterfaceFlexSoFee')->update($nut_obj);
                    continue;
                }
                $ifgf_dao = $this->getDao('InterfaceFlexGatewayFee');
                $ifgf_obj = $ifgf_dao->get();
                $ifgf_obj->setFlexBatchId($batch_id);
                $ifgf_obj->setGatewayId($this->getPmgw());
                $ifgf_obj->setTxnId($nut_obj->getTxnId());
                $ifgf_obj->setTxnTime($nut_obj->getTxnTime());
                $ifgf_obj->setCurrencyId($nut_obj->getCurrencyId());
                $ifgf_obj->setAmount($nut_obj->getAmount());
                $ifgf_obj->setStatus($nut_obj->getStatus());
                $ifgf_obj->setBatchStatus("N");

                if ($ifgf_dao = $this->getDao('InterfaceFlexGatewayFee')->insert($ifgf_obj)) {
                    if ($ifsf_obj = $this->getDao('InterfaceFlexSoFee')->get(array("txn_id" => $nut_obj->getTxnId(), "gateway_id" => $this->getPmgw(), "status" => $nut_obj->getStatus()))) {
                        $ifsf_obj->setBatchStatus("S");
                        $ifsf_obj->setFailedReason("move from interface_so_fee to interface_gateway_fee");
                        $this->getDao('InterfaceFlexSoFee')->update($ifsf_obj);
                    }
                }
            }
        }
        return false;
    }

    public function insertSoFeeFromRiaRecord($batch_id, $status, $dto_obj)
    {
        return false;
    }

    public function insertSoFeeFromRollingReserveRecord($batch_id, $status, $dto_obj)
    {
        return false;
    }

    public function insertSoFeeFromRefundRecord($batch_id, $status, $dto_obj)
    {
        return false;
    }

    protected function insertInterfaceFlexRia($batch_id, $status, $dto_obj)
    {
        $dto_obj = $this->setFormatObject($dto_obj);
        $dto_obj->setAmount($dto_obj->getAmountCredit());
        $this->createInterfaceFlexRia($batch_id, $status, $dto_obj, false);
    }

    private function setFormatObject($dto_obj)
    {
        $date = date("Y-m-d H:i:s", strtotime($dto_obj->getTxnTime()));
        $dto_obj->setTxnTime($date);
        $dto_obj->setDate($date);
        $reference = $dto_obj->getReference();
        if (!empty($reference) && (strpos($reference, "-") !== FALSE) ) {
            $index = strpos($reference, "-") + 1;
            $dto_obj->setSoNo(substr($reference, $index, (strlen($reference) - $index)));
        }
        if ($dto_obj->getAmount()) {
            $dto_obj->setAmount(ereg_replace(",", "", $dto_obj->getAmount()));
        }

        $debit = 0 - $dto_obj->getAmountDebit();
        $dto_obj->setAmountDebit($debit);
        if (!trim($dto_obj->getSoNo())) {
            $txn_id_list = array("OriginalOrderTxnId", "TxnId");
            foreach ($txn_id_list as $a) {
                $method = "get" . $a;
                if ($txn_id = $dto_obj->$method()) {
                    if (($so_obj = $this->getDao('So')->get(array("txn_id" => $txn_id)))) {
                        $dto_obj->setRefTxnId($txn_id);
                        $dto_obj->setSoNo($so_obj->getSoNo());
                        break;
                    }
                }
            }
        }

        $internal_txn_id = $dto_obj->getTxnId();
        $dto_obj->setInternalTxnId($internal_txn_id);
        if (!$dto_obj->getRefTxnId()) {
            $dto_obj->setRefTxnId($internal_txn_id);
        }
        if (!$dto_obj->getCurrencyId()) {
            $dto_obj->setCurrencyId($this->_report_currency_id);
        }
        return $dto_obj;
    }

    protected function insertInterfaceFlexSoFee($batch_id, $status, $dto_obj)
    {
        $dto_obj = $this->setFormatObject($dto_obj);
        if ($dto_obj->getOriginalOrderTxnId() != "") {
            $dto_obj->setTxnId($dto_obj->getOriginalOrderTxnId());
        }

        $dto_obj->setCommission($dto_obj->getAmountDebit());
        if (!$dto_obj->getCommission()) {
            if ($amount_credit = $dto_obj->getAmountCredit()) {
                $dto_obj->setCommission($amount_credit);
            } else {
                $dto_obj->setCommission(0);
            }
        }
        $this->createInterfaceFlexSoFee($batch_id, $status, $dto_obj);
    }

    protected function insertInterfaceFlexRefund($batch_id, $status, $dto_obj)
    {
        $dto_obj = $this->setFormatObject($dto_obj);
        if ($dto_obj->getOriginalOrderTxnId() != "") {
            $dto_obj->setTxnId($dto_obj->getOriginalOrderTxnId());
            $dto_obj->setRefTxnId($dto_obj->getOriginalOrderTxnId());
        }
        $dto_obj->setAmount($dto_obj->getAmountDebit());
        if (!$dto_obj->getAmount()) {
            $dto_obj->setAmount($dto_obj->getAmountCredit());
        }
        $this->createInterfaceFlexRefund($batch_id, $status, $dto_obj, false);
    }

    protected function insertInterfaceFlexRollingReserve($batch_id, $status, $dto_obj)
    {
        return true;
    }

    protected function insertInterfaceFlexGatewayFee($batch_id, $status, $dto_obj)
    {
        $dto_obj = $this->setFormatObject($dto_obj);
        $dto_obj->setAmount($dto_obj->getAmountDebit());
        $dto_obj->setCurrencyId($this->_report_currency_id);
        return $this->createInterfaceFlexGatewayFee($batch_id, $status, $dto_obj);
    }
}


