<?php

namespace ESG\Panther\Service;
use ESG\Panther\Service\SoFactoryService;

class RptDispatchWithHsCodeService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getCsv($where = array())
    {
        $arr = $this->getData($where);
        return $arr;
    }

    public function getData($where = array())
    {
        $arr = $this->getDao('So')->getDispatchData($where);
        $data = $this->processDataRow($arr);
        return $data;
    }

    public function processDataRow($arr)
    {
        $new_list = array();
        if ($arr) {
            $last_so_no = "";
            $declared_value = 0;
            $so_qty = array();
            foreach ($arr as $row) {
                $so_qty[$row['so_no']] += $row['qty'];
            }
            $SoFactoryService = New SoFactoryService();
            foreach ($arr as $row) {

                $row['prod_name'] = str_replace('"', '""', $row['prod_name']);
                $row['prod_name'] = '"' . $row['prod_name'] . '"';
                $row['amount'] = number_format($row['amount'], 2, '.', '');
                if ($row['so_no'] == $last_so_no) {
                    $row['order_create_date'] = '-----------------';
                    $row['pack_date'] = '-----------------';
                    $row['dispatch_date'] = '-----------------';
                    $row['tracking_no'] = '-----------------';
                    $row['courier_id'] = '-----------------';
                    $row['amount'] = '-----------------';
                }
                if ($row['so_no'] != '-----------------') {
                    $last_so_no = $row['so_no'];
                }
                $row['item_declared_value'] = number_format($SoFactoryService->_getDeclaredValue($row['delivery_country_id'], $row['item_amount']) * $row['rate_to_hkd'], 2, '.', '');
                if ($row['declared_value'] == '0.00') {
                    $row['total_declared_value'] = number_format($SoFactoryService->_getDeclaredValue($row['delivery_country_id'], $row['amount']) * $row['rate_to_hkd'], 2, '.', '');
                } else {
                    $row['total_declared_value'] = number_format($row['declared_value'] * $row['rate_to_hkd'], 2, '.', '');
                }
                $row['average_delivery_cost'] = $row['delivery_charge'] / $so_qty[$row['so_no']] * $row['rate_to_hkd'];
                $new_list[] = $row;
            }
            unset($arr);
        }
        return $new_list;
    }

    public function getHeader()
    {
        return "SO No,Warehouse ID,MasterSKU,Product Name,Quantity,HS Code,Description,Order Create Date,Pack Date,Dispatch Date,Currency,Amount,Origin Country ID, Destination Country ID, Courier ID, Tracking No, First leg tracking No, Average Delivery Cost(HKD) ,Declare value(HKD), Total Declare value(HKD)\r\n";
    }
}


