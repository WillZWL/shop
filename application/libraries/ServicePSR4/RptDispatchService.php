<?php
namespace ESG\Panther\Service;

class RptDispatchService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
        $this->soService = new SoService;
        $this->refundService = new RefundService;
        $this->dbTextLookupService = new DbTextLookupService;
    }

    public function getCsv($where = [], $option = [])
    {
        $csv_header = $this->getHeader()."\n";
        $option['dispatch_report'] = 1;
        $list = $this->getDao('So')->getConfirmedSo($where, $option);
        $csv = '';
        if ($list) {
            $data = $this->processDataRow($list);
            foreach ($data as $row) {

                foreach ($row as $key => $field) {
                    $row[$key] = '"' . $field . '"';
                }
                foreach ($row as $field) {
                    $csv .= $field.",";
                }
                $csv .= "\n";
            }
        }
        return $csv_header. $csv;
    }

    public function processDataRow($list = [])
    {
        $new_list = [];
        foreach ($list as $row) {
            $amount = ($row['soid_amount'] + $row["soid_gst_total"]);
            if ($row['refund_status'] == 'C') {
                $row['actual_order_amount'] = $row['so_amount'] - $row['total_refund_amount'] * 1;
            } else {
                $row['actual_order_amount'] = $row['so_amount'];
            }

            $row['fee'] = $row['actual_order_amount'] * $row['payment_charge_percent'] / 100;
            $row['fee'] = number_format($row['fee'], 2, '.', '');

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

            if ($row["payment_status"] !== "") {
                $row['payment_status'] = $this->dbTextLookupService->getPaymentStatus($row['payment_status']);
            }

            $new_list[] = $row;
        }
        unset($list);

        return $new_list;
    }


    public function getHeader()
    {
        return 'Platform, Payment Gateway, Business Type, Special Order Creation Reason, Transaction ID, SO Number, '
            . 'Platform Order Number, Affiliate, Warehouse id, Category Name, Sub-category Name, Brand Name, '
            . 'Product Name, Master SKU, SKU, Quantity, Dispatch Date, '
            . 'Order Create Date, Currency, Amount, Fee, Receivable Amount, VAT, '
            . 'Profit, Margin, Unit Price, Cost, Country Code, Amount(USD), Profit(USD), Promotion Code, Delivery Type, Courier ID, Tracking No., Delivery Charge, SO mount, Rate, SO Item Amount,'
            . 'Payment Charge Percent, Customer Email, Packed Date, Actual Order Amount';
    }

}