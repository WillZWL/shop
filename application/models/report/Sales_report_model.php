<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_report_model extends CI_Model
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
        // $arr = $this->get_report_service()->get_data($from_date, $to_date, $where);

        //var_dump($result);die();
        if ($is_sales_rpt) {
            # only sales report will go through this
            set_time_limit(0);
            $arr = $this->get_report_service()->get_data($from_date, $to_date, $where, $is_light_version_sales_rpt);

            /* ======================
                AS OF SBF #4530, WE USE so_payment_status.pay_date TO DETERMINE PAYMENT DATE.
                AS SUCH, WE WILL NOT HAVE PROBLEMS WITH DELAYED PAYMENT GATEWAYS & REFUND.
                COMMENTED LINES BELOW NO LONGER NEEDED.
                =====================
                sbf #3870 need date parameter to be applied to different fields based on different refund status
                - first we get not refunded orders with date parameter applied to so_payment_status.modify_on.
                - then we get refunded orders with date parameter applied to so.order_create_date.
            */

            // # $where["so.modify_on"] and $where["sps.modify_on"] will result in additional filters in query
            // # due to requirements in #3870, hence, always unset after get_data();
            // $where["so.modify_on"] = null;
            // $arr_refund_orders = $this->get_report_service()->get_data($from_date, $to_date, $where);
            // unset($where["so.modify_on"]);

            // $where["sps.modify_on"] = null;
            // $arr_not_refund_orders = $this->get_report_service()->get_data($from_date, $to_date, $where); # get not refunded orders
            // unset($where["sps.modify_on"]);

            // if(!empty($arr_not_refund_orders))
            // {
            //  $arr = array_merge($arr_not_refund_orders, (array)$arr_refund_orders);
            // }
            // else
            // {
            //  $arr = $arr_refund_orders;
            // }
        } else {
            # non sales reports come here (e.g. dispatch report)
            $arr = $this->get_report_service()->get_data($from_date, $to_date, $where);
        }

        // ======================

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

/* End of file sales_report_model.php */
/* Location: ./system/application/models/report/sales_report_model.php */