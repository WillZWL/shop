<?php
namespace ESG\Panther\Service;
use ESG\Panther\Service\ReportService;


class RptRefundReportService extends ReportService
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
            $refund_type = $obj->get_refund_type();
            switch ($refund_type) {
                case "R":
                    $obj->set_refund_type("Refund");
                    break;
                case "C":
                    $obj->set_refund_type("CashBack");
                    break;
                default:
            }

            $refund_status = $obj->get_refund_status();
            switch ($refund_status) {
                case "N":
                    $obj->set_refund_status("NEW");
                    break;
                case "CS":
                    break;
                case "LG":
                    $obj->set_refund_status("LOGISTICS");
                    break;
                case "AC":
                    $obj->set_refund_status("ACCOUNT");
                    break;
                case "D":
                    $obj->set_refund_status("DENIED");
                    break;
                case "C":
                    $obj->set_refund_status("COMPLETED");
                    break;
                default:
            }

            if ($obj->get_reason_cat() == "O") {
                if ($rh_obj = $this->get_history_dao()->get(array("refund_id" => $obj->get_refund_id(), "status" => "N"))) {
                    $obj->set_description("Others: " . $rh_obj->get_notes());
                }
            }

            $search = array(chr(10), chr(13));
            $replace = array(" ", " ");
            $refund_reason = str_replace($search, $replace, $obj->get_description());
            $refund_reason = trim($refund_reason);
            $obj->set_description($refund_reason);

            $refund_comment = str_replace($search, $replace, $obj->get_notes());
            $refund_comment = trim($refund_comment);
            $obj->set_notes($refund_comment);
        }
        return $this->convert($arr);
    }

    public function get_data($where)
    {
        set_time_limit(300);
        $res = $this->getDao('Refund')->getRefundReportContent($where, array("limit" => -1));

        return $res;
    }

    public function get_history_dao()
    {
        return $this->history_dao;
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
