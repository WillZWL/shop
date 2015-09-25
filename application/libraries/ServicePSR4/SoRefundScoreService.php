<?php
namespace ESG\Panther\Service;

class SoRefundScoreService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function insert_initial_refund_score($orderid)
    {
        $payment_gateway_id = strtolower($this->getSoPaymentGatewayId($orderid));
        $payment_gateway_list = ["paypal", "w_bank_transfer"];

        $so_obj = $this->getDao('So')->get(["so_no" => $orderid]);
        $delivery_status = $so_obj->getStatus();
        $platform_id = $so_obj->getPlatformId();
        $platform_id_list = array("QOO10SG", "TMNZ");

        if (in_array($payment_gateway_id, $payment_gateway_list) || in_array($platform_id, $platform_id_list) || $delivery_status == '6') {
            $refund_score = 2;
        } else {
            $refund_score = 0;
        }
        if (!$this->getRefundScoreVo($orderid)) {
            $this->insertRefundScore($orderid, $refund_score);
            $this->insertRefundScoreHistory($orderid, $refund_score);
        }
    }

    public function getSoPaymentGatewayId($so_no)
    {
        if ($sops_vo = $this->getDao('SoPaymentStatus')->get(["so_no" => $so_no])) {
            return $sops_vo->getPaymentGatewayId();
        } else {
            return FALSE;
        }
    }

    public function getRefundScoreVo($so_no)
    {
        return $this->getDao('SoRefundScore')->get(["so_no" => $so_no]);
    }

    public function insertRefundScore($so_no, $new_score)
    {
        $new_sorf_vo = $this->getDao('SoRefundScore')->get();
        $new_sorf_vo->setSoNo($so_no);
        $new_sorf_vo->setScore($new_score);
        return $this->getDao('SoRefundScore')->insert($new_sorf_vo);

    }

    public function insertRefundScoreHistory($so_no, $new_score)
    {
        $new_sorf_history_vo = $this->getDao('SoRefundScoreHistory')->get();
        $new_sorf_history_vo->setSoNo($so_no);
        $new_sorf_history_vo->setScore($new_score);
        return $this->getDao('SoRefundScoreHistory')->insert($new_sorf_history_vo);
    }

    public function updateRefundScore($so_no, $new_score)
    {
        $sorf_vo = $this->getDao('SoRefundScore')->get(['so_no' => $so_no]);
        $sorf_vo->setScore($new_score);
        return $this->getDao('SoRefundScore')->update($sorf_vo);
    }

    public function getRefundScoreHistoryList($where = [], $option = [])
    {
        return $this->getDao('SoRefundScoreHistory')->get_list($where, $option);
    }
}


