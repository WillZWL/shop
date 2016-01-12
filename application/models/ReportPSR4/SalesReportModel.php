<?php
namespace ESG\Panther\Models\Report;
use ESG\Panther\Service\RptSalesService;

class SalesReportModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/rpt_sales_service');
    }

    public function get_csv($from_date, $to_date, $where = array(), $is_sales_rpt = false, $is_light_version_sales_rpt = false)
    {
        // To skip the fields for not display
        $skip_fields = array(19, 20, 21, 28);
        $skip_fields = array();
        $result = $this->get_report_service()->get_header($is_light_version_sales_rpt) . "\n";
        if ($is_sales_rpt) {
            set_time_limit(0);
            $arr = $this->get_report_service()->get_data($from_date, $to_date, $where, $is_light_version_sales_rpt);
        } else {
            # non sales reports come here (e.g. dispatch report)
            $arr = $this->get_report_service()->get_data($from_date, $to_date, $where);
        }
        if ($arr) {
            foreach ($arr as $row) {
                $num_of_fields = count($row);
                $orig_num_of_fields = $num_of_fields;
                if ($last_so_no == $row['so_no']) {
                    $skip_on = 1;
                } else {
                    $skip_on = 0;
                    $last_so_no = $row['so_no'];
                }

                foreach ($row as $key => $field) {
                    $row[$key] = '"' . $field . '"';
                }

                foreach ($row as $field) {
                    $num_of_fields--;

                    if (!($skip_on == 1 && in_array($orig_num_of_fields - $num_of_fields, $skip_fields))) {
                        $result .= $field;
                    }

                    if ($num_of_fields > 0) {
                        $result .= ',';
                    }
                }

                $result .= "\n";
            }
        }


        return $result;

    }

    public function get_report_service()
    {
        $this->load->library('service/rpt_sales_service');
        return $this->rpt_sales_service;
    }

    public function get_split_order_csv($from_date, $to_date, $where = array(), $option = array())
    {
        $ret = $this->get_report_service()->get_split_order_csv($from_date, $to_date, $where, $option);
        return $ret;
    }

}
