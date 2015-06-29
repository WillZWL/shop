<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Report_service.php";

class Rpt_sales_comparison_by_period_service extends Report_service
{
    private $so_service;
    private $refund_service;

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/service/So_service.php");
        $this->set_so_service(new So_service());
        include_once(APPPATH . "libraries/service/Refund_service.php");
        $this->set_refund_service(new Refund_service());
        $this->set_output_delimiter(',');
        include_once(APPPATH . 'libraries/dto/lazy_dto.php');
    }

    public function get_csv($from_date1, $to_date1,
                            $from_date2, $to_date2)
    {
        $arr = $this->get_data($from_date1 . ' 00:00:00', $to_date1 . ' 23:59:59',
            $from_date2 . ' 00:00:00', $to_date2 . ' 23:59:59');
        return $this->convert($arr, array('period_start1' => $from_date1, 'period_end1' => $to_date1,
            'period_start2' => $from_date2, 'period_end2' => $to_date2));
    }

    public function get_data($from_date1 = '', $to_date1 = '',
                             $from_date2 = '', $to_date2 = '')
    {
        $prelim_data = (array)$this->get_so_service()->get_sales_comparison_data_by_period(
            array('period_start1' => $from_date1, 'period_end1' => $to_date1,
                'period_start2' => $from_date2, 'period_end2' => $to_date2));

        $data = array();

        foreach ($prelim_data as $row) {
            $row = (array)$row;

            if (!empty($row['item_sku1'])) {
                $temp_sku = $row['item_sku1'];
                $temp_cnty_id = $row['cnty_id1'];

                if (empty($data[$temp_sku][$temp_cnty_id])) {
                    $data[$temp_sku][$temp_cnty_id] = $row;
                }

                $data[$temp_sku][$temp_cnty_id]['total_sales_count1'] += $row['sales_count1'];
                $data[$temp_sku][$temp_cnty_id]['total_sales_count2'] += $row['sales_count2'];

                if ($row['promotion_code1'] !== null) {
                    $data[$temp_sku][$temp_cnty_id]['promotion_code_used1']++;
                }
                if ($row['promotion_code2'] !== null) {
                    $data[$temp_sku][$temp_cnty_id]['promotion_code_used2']++;
                }

            } else if (!empty($row['item_sku2'])) {
                $temp_sku = $row['item_sku2'];
                $temp_cnty_id = $row['cnty_id2'];

                if (empty($data[$temp_sku][$temp_cnty_id])) {
                    $row['item_sku1'] = $temp_sku;
                    $row['cnty_id1'] = $temp_cnty_id;
                    $row['cnty_name1'] = $row['cnty_name2'];
                    $row['prod_name1'] = $row['prod_name2'];
                    $data[$temp_sku][$temp_cnty_id] = $row;
                }

                $data[$temp_sku][$temp_cnty_id]['total_sales_count1'] += $row['sales_count1'];
                $data[$temp_sku][$temp_cnty_id]['total_sales_count2'] += $row['sales_count2'];

                if ($row['promotion_code1'] !== null) {
                    $data[$temp_sku][$temp_cnty_id]['promotion_code_used1']++;
                }
                if ($row['promotion_code2'] !== null) {
                    $data[$temp_sku][$temp_cnty_id]['promotion_code_used2']++;
                }
            }
        }

        $refund_data_period[1] = $this->get_refund_service()->get_refund_info_by_period(
            array('period_start' => $from_date1, 'period_end' => $to_date1));

        $refund_data_period[2] = $this->get_refund_service()->get_refund_info_by_period(
            array('period_start' => $from_date2, 'period_end' => $to_date2));

        for ($i = 1; $i <= 2; $i++) {
            if ($refund_data_period[$i]) {
                foreach ($refund_data_period[$i] as $row) {
                    $row = (array)$row;
                    $temp_sku = $row['item_sku'];
                    $temp_cnty_id = $row['cnty_id'];

                    $data[$temp_sku][$temp_cnty_id]["refund_qty$i"] += $row['refund_qty'];
                }
            };
        }

        ksort($data);

        $return_data = array();
        $overall_sales_count1 = 0;
        $overall_refund1 = 0;
        $overall_promtion_code_used1 = 0;
        $overall_sales_count2 = 0;
        $overall_refund2 = 0;
        $overall_promtion_code_used2 = 0;

        foreach ($data as $sku_key => $data_sku) {
            ksort($data_sku);

            foreach ($data_sku as $cnty_key => $data_cnty) {
                for ($i = 1; $i <= 2; $i++) {
                    $data_cnty["net_sales$i"] = $data_cnty["total_sales_count$i"]
                        - $data_cnty["refund_qty$i"];
                    if ($data_cnty["total_sales_count$i"] > 0) {
                        $data_cnty["refund_rate$i"] =
                            round(($data_cnty["refund_qty$i"] * 100 / $data_cnty["total_sales_count$i"]), 2)
                            . '%';
                    }


                }

                $data_cnty['sales_diff'] = (($data_cnty['net_sales1'] != 0) ?
                    (round(
                            ($data_cnty['net_sales2'] - $data_cnty['net_sales1'])
                            * 100 / $data_cnty['net_sales1'],
                            2) . '%') :
                    'No first period data for comparison');

                $data_cnty['spacing'] = null;
                $return_data[] = new Lazy_dto($data_cnty);

                //$data_sku[$cnty_key] = $data_cnty;
            }

//          ksort($data_sku);
//          $data[$sku_key] = $data_sku;
        }

        return $return_data;
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

    public function get_refund_service()
    {
        return $this->refund_service;
    }

    public function set_refund_service($value)
    {
        $this->refund_service = $value;
        return $this;
    }

    public function get_xls($from_date1, $to_date1,
                            $from_date2, $to_date2)
    {
        $arr = $this->get_data($from_date1 . ' 00:00:00', $to_date1 . ' 23:59:59',
            $from_date2 . ' 00:00:00', $to_date2 . ' 23:59:59');
        return $this->convert_xls($arr, array('period_start1' => $from_date1, 'period_end1' => $to_date1,
            'period_start2' => $from_date2, 'period_end2' => $to_date2));
    }

    public function convert_xls($data, $info_arr)
    {
        //$sheet_name["sheet_key"] = "bill_country_id";
        $header[] = new Lazy_dto(array('item_sku1' => 'Period 1', 'sales_count2' => 'Period 2'));
        $header[] = new Lazy_dto(array('item_sku1' => $info_arr['period_start1'] . ' to ' . $info_arr['period_end1'],
            'sales_count2' => $info_arr['period_start2'] . ' to ' . $info_arr['period_end2']));
        $header[] = new Lazy_dto($this->get_header());
        $process_data = array_merge($header, $data);

        $out_xml = new Vo_to_xml($process_data, $this->get_default_vo2xml_mapping());
        $out_xls = new Xml_to_xls('', APPPATH . 'data/sales_comparison_by_period_report_xml2xls.txt', TRUE, $sheet_name, FALSE);

        return $this->get_dex_service()->convert($out_xml, $out_xls);
    }

    public function get_header()
    {
        return array('item_sku1' => 'SKU', 'prod_name1' => 'Product Name', 'cnty_name1' => 'Country of Sales', 'sales_count1' => 'No. Of Sales',
            'refund_qty1' => 'No. of Refunds', 'net_sales1' => 'Net Sales (Sales - Refunds)', 'refund_rate1' => 'Refund Rate',
            'spacing' => '',
            'sales_count2' => 'No. Of Sales', 'refund_qty2' => 'No. of Refunds', 'net_sales2' => 'Net Sales (Sales - Refunds)',
            'refund_rate2' => 'Refund Rate', 'sales_diff' => 'Sales Difference (%)');
    }

    protected function get_default_vo2xml_mapping()
    {
        return APPPATH . 'data/sales_comparison_by_period_report_vo2xml.txt';
    }

    protected function get_default_xml2csv_mapping()
    {
        return '';
    }
}

/* End of file rpt_sales_comparison_by_period_service.php */
/* Location: ./system/application/libraries/service/Rpt_sales_comparison_by_period_service.php */