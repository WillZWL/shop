<?php
namespace ESG\Panther\Service;
use ESG\Panther\Service\ReportService;


class RefundReportService extends ReportService
{
    private $so_service;

    public function __construct()
    {
        parent::__construct();
        $this->setOutputDelimiter(',');
    }

    public function get_csv($where)
    {
        $arr = $this->get_data($where);
        foreach ($arr as $obj) {
            $refund_type = $obj->getRefundType();
            switch ($refund_type) {
                case "R":
                    $obj->setRefundType("Refund");
                    break;
                case "C":
                    $obj->setRefundType("CashBack");
                    break;
                default:
            }

            $refund_status = $obj->getRefundStatus();
            switch ($refund_status) {
                case "N":
                    $obj->setRefundStatus("NEW");
                    break;
                case "CS":
                    break;
                case "LG":
                    $obj->setRefundStatus("LOGISTICS");
                    break;
                case "AC":
                    $obj->setRefundStatus("ACCOUNT");
                    break;
                case "D":
                    $obj->setRefundStatus("DENIED");
                    break;
                case "C":
                    $obj->setRefundStatus("COMPLETED");
                    break;
                default:
            }

            if ($obj->getReasonCat() == "O") {
                if ($rh_obj = $this->getDao('RefundHistory')->get(array("refund_id" => $obj->getRefundId(), "status" => "N"))) {
                    $obj->setReasonCat("Others: " . $rh_obj->getNotes());
                }
            }

            $search = array(chr(10), chr(13));
            $replace = array(" ", " ");
            $refund_reason = str_replace($search, $replace, $obj->getDescription());
            $refund_reason = trim($refund_reason);
            $obj->setDescription($refund_reason);

            $refund_comment = str_replace($search, $replace, $obj->getNotes());
            $refund_comment = trim($refund_comment);
            $obj->setNotes($refund_comment);
        }
        return $this->convert($arr);
    }

    public function get_data($where)
    {
        set_time_limit(300);
        $res = $this->getDao('Refund')->getRefundReportContent($where, array("limit" => -1));
        $modify_on = [$where["rh.modify_on >="],$where["rh.modify_on <="]];
        $history = $this->getDao('RefundHistory')->getHistoryByModifyOn($modify_on);

        foreach ($res as $val){
            if($history[$val->getRefundId()]){
                $hObj = $history[$val->getRefundId()];
                $val->setCsApprovalDate($hObj->getModifyOn());
                $val->setCsApprovedBy($hObj->getCreateBy());
            }
        }

        return $res;
    }

    protected function get_default_vo2xml_mapping()
    {
        return '';
    }

    protected function get_default_xml2csv_mapping()
    {
        return APPPATH . 'data/refund_report_xml2csv.txt';
    }
}
