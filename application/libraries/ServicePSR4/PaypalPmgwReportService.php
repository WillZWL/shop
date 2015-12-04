<?php
namespace ESG\Panther\Service;
abstract class PaypalPmgwReportService extends PmgwReportService
{

    public $RiaRecordType = ["Mobile Express Checkout Payment Received",
            "PayPal Express Checkout Payment Received",
            "Mobile Payment Received",
            "Payment Received",
            "Express Checkout Payment Received"
        ];

    public function __construct()
    {
        parent::__construct();
    }

    public function isRiaIncludeSoFee()
    {
        return true;
    }

    public function isRefundIncludeSoFee()
    {
        return true;
    }

    public function isRiaRecord($dto_obj)
    {
        $type = $dto_obj->getType();
        $ria_record_type = $this->RiaRecordType;
        if (in_array($type, $ria_record_type)) {
            return true;
        }
        return false;
    }

    public function isSoFeeRecord($dto_obj)
    {
        return false;
    }

    public function isRefundRecord($dto_obj)
    {
        if (($dto_obj->getType() == "Refund") || ($dto_obj->getType() == "Reversal")) {
            return 'R';
        } else if ($dto_obj->getType() == "Chargeback Settlement") {
            return 'CB';
        }
        return false;
    }

    public function isRollingReserveRecord($dto_obj)
    {
        if ($dto_obj->getType() == "Reserve Hold") {
            return 'RRH';
        } else if ($dto_obj->getType() == "Reserve Release") {
            return 'RRR';
        } elseif ($dto_obj->getType() == "Temporary Hold") {
            return 'TH';
        } else if ($dto_obj->getType() == "Update to Reversal") {
            return 'UTR';
        }
        return false;
    }

    public function isGatewayFeeRecord($dto_obj)
    {
        if ($dto_obj->getType() == "Currency Conversion") {
            return 'FX';
        } else if ($dto_obj->getType() == "Payment Sent") {
            return 'PS';
        } elseif ($dto_obj->getType() == "Transfer") {
            return 'P_TF';
        }
        return false;
    }

    public function getContactEmail()
    {
        return 'willzhang@eservicesgroup.com';
    }

    public function afterInsertAllInterface($batch_id)
    {
        $where = array();
        $where["txn_id is null"] = null;
        $where["gateway_id"] = $this->getPmgw();
        if ($empty_txn_obj_list = $this->getDao('InterfaceFlexRollingReserve')->getList($where, ["limit" => -1])) {
            foreach ($empty_txn_obj_list as $empty_txn_obj) {
                if ($txn_id = $empty_txn_obj->getTxnId()) {
                    if (!$empty_txn_obj->getInternalTxnId()) {
                        $empty_txn_obj->setInternalTxnId($txn_id);
                    }
                } elseif ($internal_txn_id = $empty_txn_obj->getInternalTxnId()) {
                    if (!$empty_txn_obj->getTxnId()) {
                        $empty_txn_obj->setTxnId($internal_txn_id);
                    }
                }
            }
        }

        if ($empty_txn_obj_list = $this->getDao("InterfaceFlexRia")->getList($where, ["limit" => -1])) {
            foreach ($empty_txn_obj_list as $empty_txn_obj) {
                if ($txn_id = $empty_txn_obj->getTxnId()) {
                    if (!$empty_txn_obj->getInternalTxnId()) {
                        $empty_txn_obj->setInternalTxnId($txn_id);
                    }
                } elseif ($internal_txn_id = $empty_txn_obj->getInternalTxnId()) {
                    if (!$empty_txn_obj->getTxnId()) {
                        $empty_txn_obj->setTxnId($internal_txn_id);
                    }
                }
            }
        }

        unset($where);
        $where["so_no"] = " ";
        $where["gateway_id"] = $this->getPmgw();
        $where["failed_reason"] = PmgwReportService::WRONG_TRANSACTION_ID;

        if ($rolling_reserve_obj_list = $this->getDao('InterfaceFlexRollingReserve')->getList($where, ["limit" => -1])) {
            foreach ($rolling_reserve_obj_list as $nut_obj) {
                $txn_id = $nut_obj->getTxnId();
                if ($related_record = $this->getDao('InterfaceFlexRollingReserve')->getList(["internal_txn_id" => $txn_id], ["limit" => 1])) {
                    if ($so_no = $related_record->getSoNo()) {
                        $nut_obj->setSoNo($so_no);
                        $nut_obj->setTxnId($related_record->getTxnId());
                        $nut_obj->setBatchStatus("N");
                        $nut_obj->setFailedReason("");
                        $this->getDao('InterfaceFlexRollingReserve')->update($nut_obj);
                    }
                }
            }
        }
        if ($rolling_reserve_obj_list = $this->getDao('InterfaceFlexRollingReserve')->getList($where, ["limit" => -1])) {
            foreach ($rolling_reserve_obj_list as $nut_obj) {
                $this->RRToInterfaceFlexGatewayFee($batch_id, $nut_obj->getStatus(), $nut_obj);
            }
        }
    }

    private function RRToInterfaceFlexGatewayFee($batch_id, $status, $rr_obj)
    {
        $ifgf_dao = $this->getDao('InterfaceFlexGatewayFee');
        $ifgf_obj = $ifgf_dao->get();
        $ifgf_obj->setFlexBatchId($batch_id);
        $ifgf_obj->setGatewayId($this->getPmgw());
        $ifgf_obj->setTxnId($rr_obj->getTxnId());
        $ifgf_obj->setTxnTime($rr_obj->getTxnTime());
        $ifgf_obj->setCurrencyId($rr_obj->getCurrencyId());
        $ifgf_obj->setAmount($rr_obj->getAmount());
        $ifgf_obj->setStatus($status);
        $ifgf_obj->setBatchStatus("N");
        if ($ifgf_dao = $this->getDao('InterfaceFlexGatewayFee')->insert($ifgf_obj)) {
            $rr_obj->setBatchStatus("S");
            $rr_obj->setFailedReason("move to interface_gateway_fee");
            $this->getDao('InterfaceFlexRollingReserve')->update($rr_obj);
        }
    }

    public function validTxnId($interface_obj)
    {
        return true;
    }

    public function insertSoFeeFromRiaRecord($batch_id, $status, $dto_obj)
    {
        parent:: createInterfaceFlexSoFee($batch_id, $status, $dto_obj);
    }

    public function insertSoFeeFromRollingReserveRecord($batch_id, $status, $dto_obj)
    {
        parent:: createInterfaceFlexSoFee($batch_id, $status, $dto_obj);
    }

    public function insertSoFeeFromRefundRecord($batch_id, $status, $dto_obj)
    {
        if (!((float)$dto_obj->getCommission())) {
            return true;
        }
        $ifsf_dao = $this->getDao("InterfaceFlexSoFee");
        $ifsf_obj = $ifsf_dao->get();
        $ifsf_obj->setSoNo($dto_obj->getSoNo());
        $ifsf_obj->setFlexBatchId($batch_id);
        $ifsf_obj->setGatewayId($this->getPmgw());
        $ifsf_obj->setTxnId($dto_obj->getRefTxnId());
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

    protected function insertInterfaceFlexRia($batch_id, $status, $dto_obj)
    {
        $this->reformData($dto_obj);
        $this->getOrderFromTxnId($dto_obj, $type = "ria");
        if ($dto_obj->getAmount()) {
            $dto_obj->setAmount(ereg_replace(",", "", $dto_obj->getAmount()));
        }
        $ifr_obj = $this->createInterfaceFlexRia($batch_id, $status, $dto_obj);
        if ($ifr_obj && trim($ifr_obj->getSoNo()) == "") {
            if ($dto_obj->getType() == "Payment Received") {
                $this->PRToInterfaceFlexGatewayFee($batch_id, $ifr_obj->getStatus(), "PR", $dto_obj);
                if ($dto_obj->getCommission() != "" && $dto_obj->getCommission() != "..." && abs((float)$dto_obj->getCommission()) > 0) {
                    $dto_obj->setAmount($dto_obj->getCommission());
                    $dto_obj->setTxnId($dto_obj->getTxnId());
                    $new_status = "RIA";
                    if ($dto_obj->getStatus() == "Completed") {
                        $new_status = "PR_C";
                    } elseif ($dto_obj->getStatus() == "Refunded") {
                        $new_status = "PR_R";
                    }
                    $this->PRToInterfaceFlexGatewayFee($batch_id, $ifr_obj->getStatus(), $new_status, $dto_obj);
                }
            }
        }
    }

    public function reformData($dto_obj)
    {
        $date = date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $dto_obj->getDate()) . " " . $dto_obj->getTime()));
        $dto_obj->setDate($date);
        $dto_obj->setAmount(ereg_replace(",", "", $dto_obj->getAmount()));
        $dto_obj->setNet(ereg_replace(",", "", $dto_obj->getNet()));
        $txn_time_min = date("Y-m-d H:i:s", strtotime($date . " -1 month"));
        $txn_time_max = date("Y-m-d H:i:s", strtotime($date . " +1 month"));
        $where_ria["txn_id"] = "Transaction ID: " . $dto_obj->getTxnId();
        $where_ria["order_create_date between '" . $txn_time_min . "'and'" . $txn_time_max . "'"] = null;
        $where_refund["txn_id"] = "Transaction ID: " . $dto_obj->getRefTxnId();
        $where_refund["order_create_date between '" . $txn_time_min . "'and'" . $txn_time_max . "'"] = null;
        if (!$dto_obj->getSoNo()) {
            if ($so_obj = $this->getSoObj(array("txn_id" => $dto_obj->getTxnId()))) {
                $dto_obj->setSoNo($so_obj->getSoNo());
            }
        }
    }

    private function getOrderFromTxnId($dto_obj, $type = "ria")
    {
        if ((!preg_match("/^\d{6}$/", $dto_obj->getSoNo()))) {
            $date = $dto_obj->getDate();
            $txn_time_min = date("Y-m-d H:i:s", strtotime($date . " -1 month"));
            $txn_time_max = date("Y-m-d H:i:s", strtotime($date . " +1 month"));
            $where["order_create_date between '" . $txn_time_min . "'and'" . $txn_time_max . "'"] = null;
            if ($type == "ria") {
                $txn_id = $dto_obj->getTxnId();
            } else {
                $txn_id = $dto_obj->getRefTxnId();
            }
            $where_refund["txn_id"] = "Transaction ID: " . $txn_id;
            $search_mode = array(array("txn_id" => $txn_id), array("txn_id like '%" . $txn_id . "%'" => null));
            foreach ($search_mode as $where) {
                if ($so_obj = $this->getSoObj($where)) {
                    $dto_obj->setSoNo($so_obj->getSoNo());
                    break;
                }
            }
        }
    }

    private function PRToInterfaceFlexGatewayFee($batch_id, $original_status, $destination_status, $dto_obj)
    {
        $ifgf_dao = $this->getDao('InterfaceFlexGatewayFee');
        $ifgf_obj = $ifgf_dao->get();
        $ifgf_obj->setFlexBatchId($batch_id);
        $ifgf_obj->setGatewayId($this->getPmgw());
        $ifgf_obj->setTxnId($dto_obj->getTxnId());
        $ifgf_obj->setTxnTime($dto_obj->getDate());
        $ifgf_obj->setCurrencyId($dto_obj->getCurrencyId());
        $ifgf_obj->setAmount($dto_obj->getAmount());
        $ifgf_obj->setStatus($destination_status);
        $ifgf_obj->setBatchStatus("N");
        if ($ifgf_dao = $this->getDao("InterfaceFlexGatewayFee")->insert($ifgf_obj)) {
            if ($ifr_obj = $this->getDao("InterfaceFlexRia")->get(["txn_id" => $dto_obj->getTxnId(), "status" => $original_status, "batch_status != 'S'" => NULL])) {
                $ifr_obj->setBatchStatus("S");
                $ifr_obj->setFailedReason("move to interface_gateway_fee");
                $this->getDao("InterfaceFlexRia")->update($ifr_obj);
            }
        }
    }

    protected function insertInterfaceFlexRefund($batch_id, $status, $dto_obj)
    {
        $this->reformData($dto_obj);
        $this->getOrderFromTxnId($dto_obj, $type = "refund");
        $dto_obj->setInternalTxnId($dto_obj->getTxnId());
        if (!$dto_obj->getRefTxnId()) {
            $dto_obj->setRefTxnId($dto_obj->getTxnId());
        }
        $this->createInterfaceFlexRefund($batch_id, $status, $dto_obj, true);
    }

    protected function insertInterfaceFlexSoFee($batch_id, $status, $dto_obj)
    {
        return false;
    }

    protected function insertInterfaceFlexRollingReserve($batch_id, $status, $dto_obj)
    {
        $this->reformData($dto_obj);
        $dto_obj->setInternalTxnId($dto_obj->getTxnId());
        if (!$dto_obj->getRefTxnId()) {
            $dto_obj->setRefTxnId($dto_obj->getTxnId());
        }
        $dto_obj->setAmount($dto_obj->getNet());
        $include_fsf = false;
        $ifrr_obj = $this->createInterfaceFlexRollingReserve($batch_id, $status, $dto_obj, $include_fsf);
    }

    protected function insertInterfaceFlexGatewayFee($batch_id, $status, $dto_obj)
    {
        $this->reformData($dto_obj);
        if (($status == "PS") || ($status == "BTU")) {
            $dto_obj->setTxnId($dto_obj->getRefTxnId());
        }
        if ($status == 'FX') {
            if ($dto_obj->getAmount() > 0) {
                $status = "FXI";
            } else {
                $status = "FXO";
            }
        }
        if (!$dto_obj->getTxnId()) {
            $dto_obj->setTxnId(" ");
        }
        if (!$dto_obj->getRefTxnId()) {
            $dto_obj->setRefTxnId($dto_obj->getTxnId());
        }
        return $this->createInterfaceFlexGatewayFee($batch_id, $status, $dto_obj);
    }
}


