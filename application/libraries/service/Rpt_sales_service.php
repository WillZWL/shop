<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Report_service.php";

class Rpt_sales_service extends Report_service
{
    private $so_service;
    private $pricing_tool_model;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('marketing/pricing_tool_model');

        include_once(APPPATH . "libraries/service/So_service.php");
        $this->set_so_service(new So_service());
        include_once(APPPATH . "libraries/service/Refund_service.php");
        $this->set_refund_service(new Refund_service());
        include_once(APPPATH . "libraries/service/Db_text_lookup_service.php");
        $this->set_db_text_lookup_service(new Db_text_lookup_service());
        $this->set_output_delimiter(',');
    }

    public function set_refund_service($value)
    {
        $this->refund_service = $value;
        return $this;
    }

    public function set_db_text_lookup_service($value)
    {
        $this->db_text_lookup_service = $value;
        return $this;
    }

    public function get_refund_service()
    {
        return $this->refund_service;
    }

    public function get_csv($from_date, $to_date)
    {
        $arr = $this->get_data($from_date, $to_date);
        return $this->convert($arr);
    }

    public function get_data($from_date = '', $to_date = '', $where = array(), $is_light_version = false)
    {
        $arr = $this->get_so_service()->get_confirmed_so($where, $from_date, $to_date, $is_light_version);
//      error_log($this->get_so_service()->get_dao()->db->last_query());
        $data = $this->process_data_row($arr, $is_light_version);

        return $data;
    }

    public function get_so_service()
    {
        return $this->so_service;
    }

    public function set_so_service($value)
    {
        $this->so_service = $value;
        return $this;
    }

    public function process_data_row($arr, $is_light_version = false)
    {
        $new_list = array();
        if ($arr) {
            $last_so_no = "";
            foreach ($arr as $row) {
                if (!isset($this->pricing_tool_model[$row["type"]]))
                    $this->pricing_tool_model[$row["type"]] = new pricing_tool_model($row["type"]);

                $amount = ($row['soid_amount'] + $row["soid_gst_total"]);
                // $json = $this->pricing_tool_model[$row["type"]]->price_service->get_profit_margin_json($row["platform_id"], $row["sku"], $amount / $row["qty"]);
                // $jj = json_decode($json, true);

                if (1 == 1) {
                    // order amount
                    if ($row['refund_status'] == 'C')
                        $row['actual_order_amount'] = $row['so_amount'] - $row['total_refund_amount'] * 1;
                    else
                        $row['actual_order_amount'] = $row['so_amount'];

                    // fee
                    $row['fee'] = $row['actual_order_amount'] * $row['payment_charge_percent'] / 100;
                    $row['fee'] = number_format($row['fee'], 2, '.', '');

                    // receivable
                    if ($row['refund_status'] == 'C')
                        $row['receivable'] = 0;
                    else
                        $row['receivable'] = $row['actual_order_amount'] - $row['fee'];

                    $row['profit'] = $row["profit"] * $row["qty"];
                    $row['cost'] = $row["cost"] * $row["qty"];
                    $row['amount'] = $amount;

                    $row['profit_usd'] = $row['profit'] * $row['rate'];
                    $row['profit_usd'] = number_format($row['profit_usd'], 2, '.', '');

                    $row['amount_usd'] = $row['amount'] * $row['rate'];
                    $row['amount_usd'] = number_format($row['amount_usd'], 2, '.', '');
                }

                if ($row["payment_status"] !== "") {
                    # display payment status
                    switch (strtoupper($row["payment_status"])) {
                        case 'N':
                            $row["payment_status"] = 'New';
                            break;
                        case 'P':
                            $row["payment_status"] = 'Processing';
                            break;
                        case 'S':
                            $row["payment_status"] = 'Success';
                            break;
                        case 'C':
                            $row["payment_status"] = 'Cancelled';
                            break;
                        case 'F':
                            $row["payment_status"] = 'Failed';
                            break;
                        case 'B':
                            $row["payment_status"] = 'Chargeback';
                            break;
                        case 'CF':
                            $row["payment_status"] = 'Cancel Failed';
                            break;
                        default:
                            $row["payment_status"] = $row["payment_status"];
                            break;
                    }
                }

                $last_so_no = $row['so_no'];

                if ($is_light_version) {
                    $row = array_slice($row, 0, 26);
                }

                $new_list[] = $row;
            }

            unset($arr);
        }
        return $new_list;
    }

    public function get_header($is_light_version_sales_rpt = false)
    {
        if ($is_light_version_sales_rpt) {
            return "Platform,Payment Gateway,Payment Date,Payment Status,Business Type,"
            . "Special Order Creation Reason,Line No,Platform Order Number,Split Parent SO,Affiliate,"
            . "Category Name,Sub-category Name,Brand Name,Product Name,SKU,Quantity,"
            . "Order Create Date,Currency,Amount,Fee,Receivable Amount,VAT,Profit,Margin,Unit Price,Cost,"
            . "Promotion Code";
        } else {
            return 'Platform,Payment Gateway,Payment Date,Payment Status,Business Type,Special Order Creation Reason,Transaction ID,SO Number,Split Parent SO,Line No, '
            . 'Platform Order Number,Affiliate,Warehouse id,Category Name,Sub-category Name,Brand Name,'
            . 'Product Name,SKU,Master SKU,Current Supplier,Quantity,Dispatch Date,'
            . 'Order Create Date,Currency,Amount,Fee,Receivable Amount,VAT, '
            . 'Profit,Margin,Unit Price,Cost,Country Code,Amount(USD),Profit(USD),Promotion Code,Delivery Type,Courier ID,Tracking No.,'
            . 'Delivery Charge,Refund Type,Refund Status,Refund Quantity,Refund Amount,Cashback,rate_to_USD,so_amount,delivery_charge,refund_qty,'
            . 'payment_charge_percent,type,packed_date,payment_received_in_local_time (w_bank_transfer),Clearance,Customers Email,amount';
        }

    }

    public function get_split_order_csv($from_date = "", $to_date = "", $where = array(), $option = array())
    {
        if ($this->check_date($from_date) === false) {
            $ret["status"] = false;
            $ret["message"] = "Start date in wrong format. Must be YYYY-MM-DD.";
            return $ret;
        }

        if ($this->check_date($to_date) === false) {
            $ret["status"] = false;
            $ret["message"] = "End date in wrong format. Must be YYYY-MM-DD.";
            return $ret;
        }
        $from_date = $from_date . " 00:00:00";
        $to_date = $to_date . " 23:59:59";
        $data = $this->get_so_service()->get_split_so_report($where, $option, $from_date, $to_date);

        if ($data === FALSE) {
            $ret["status"] = false;
            $ret["message"] = "Cannot retrieve report. DB error: {$this->get_so_service()->db->_error_message()}";
            return $ret;
        } else {
            if ($data) {
                foreach ($data as $key => $arr) {
                    if ($key == 0) {
                        $new_list[] = $this->get_header_from_array($arr);
                    }
                    $arr["order_status"] = $this->get_db_text_lookup_service()->so_status($arr["order_status"]);
                    $arr["refund_status"] = $this->get_db_text_lookup_service()->so_refund_status($arr["refund_status"]);
                    $arr["hold_status"] = $this->get_db_text_lookup_service()->so_refund_status($arr["hold_status"]);

                    $new_list[] = $arr;
                }

                $ret["status"] = true;
                $ret["data"] = $new_list;
                return $ret;
            } else {
                $ret["status"] = false;
                $ret["message"] = "No data available.";
                return $ret;
            }
        }

    }

    private function check_date($date)
    {
        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date)) {
            return true;
        } else {
            return false;
        }
    }

    private function get_header_from_array($array = array())
    {
        $ret = array();
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $ret[$key] = $key;
            }
        }
        return $ret;
    }

    public function get_db_text_lookup_service()
    {
        return $this->db_text_lookup_service;
    }

    protected function get_default_vo2xml_mapping()
    {
        return '';
    }

    protected function get_default_xml2csv_mapping()
    {
        //return APPPATH . 'data/rpt_sales_xml2csv.txt';
        return '';
    }
}

/* End of file rpt_stock_valuation_service.php */
/* Location: ./system/application/libraries/service/Rpt_valuation_service.php */