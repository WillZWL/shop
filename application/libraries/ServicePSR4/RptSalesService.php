<?php
namespace ESG\Panther\Service;

class RptSalesService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
        $this->SoService = new SoService;
        $this->RefundService = new RefundService;
        $this->DbTextLookupService = new DbTextLookupService;
    }

    public function getCsv($from_date, $to_date, $where = array(), $is_sales_rpt = false, $is_light_version_sales_rpt = false)
    {
        $result = $this->getHeader($is_light_version_sales_rpt) . "\n";
        if ($is_sales_rpt) {
            set_time_limit(0);
            $arr = $this->getData($from_date, $to_date, $where, $is_light_version_sales_rpt);
        } else {
            $arr = $this->getData($from_date, $to_date, $where);
        }
        var_dump();
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

    public function getData($from_date = '', $to_date = '', $where = array(), $is_light_version = false)
    {
        $arr = $this->getDao('So')->getConfirmedSo($where, $option);
        echo $this->getDao('So')->db->last_query();die();
        $data = $this->processDataRow($arr, $is_light_version);
        return $data;
    }

    public function processDataRow($arr, $is_light_version = false)
    {
        $new_list = array();
        if ($arr) {
            $last_so_no = "";
            foreach ($arr as $row) {
                $amount = ($row['soid_amount'] + $row["soid_gst_total"]);
                if (1 == 1) {
                    // order amount
                    if ($row['refund_status'] == 'C') {
                        $row['actual_order_amount'] = $row['so_amount'] - $row['total_refund_amount'] * 1;
                    } else {
                        $row['actual_order_amount'] = $row['so_amount'];
                    }
                    // fee
                    $row['fee'] = $row['actual_order_amount'] * $row['payment_charge_percent'] / 100;
                    $row['fee'] = number_format($row['fee'], 2, '.', '');
                    // receivable
                    if ($row['refund_status'] == 'C') {
                        $row['receivable'] = 0;
                    } else {
                        $row['receivable'] = $row['actual_order_amount'] - $row['fee'];
                    }
                    $row['profit'] = $row["profit"] * $row["qty"];
                    $row['cost'] = $row["cost"] * $row["qty"];
                    $row['amount'] = $amount;
                    $row['profit_usd'] = $row['profit'] * $row['rate'];
                    $row['profit_usd'] = number_format($row['profit_usd'], 2, '.', '');
                    $row['amount_usd'] = $row['amount'] * $row['rate'];
                    $row['amount_usd'] = number_format($row['amount_usd'], 2, '.', '');
                }

                if ($row["payment_status"] !== "") {
                    $row['payment_status'] = $this->DbTextLookupService->getPaymentStatus($row['payment_status']);
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

    public function getHeader($is_light_version_sales_rpt = false)
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

    public function getSplitOrderCsv($from_date = "", $to_date = "", $where = array(), $option = array())
    {
        $from_date = $from_date . " 00:00:00";
        $to_date = $to_date . " 23:59:59";
        $data = $this->sc['SoService']->getSplitSoReport($where, $option, $from_date, $to_date);
        if ($data === FALSE) {
            $ret["status"] = false;
            $ret["message"] = "Cannot retrieve report. DB error: {$this->sc['SoService']->db->_error_message()}";
            return $ret;
        } else {
            if ($data) {
                foreach ($data as $key => $arr) {
                    if ($key == 0) {
                        $new_list[] = $this->getHeaderFromArray($arr);
                    }
                    $arr["order_status"] = $this->DbTextLookupService->getSoStatus($arr["order_status"]);
                    $arr["refund_status"] = $this->DbTextLookupService->getSoRefundStatus($arr["refund_status"]);
                    $arr["hold_status"] = $this->DbTextLookupService->getSoHoldStatus($arr["hold_status"]);
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

    private function getHeaderFromArray($array = array())
    {
        $ret = array();
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $ret[$key] = $key;
            }
        }
        return $ret;
    }
}


