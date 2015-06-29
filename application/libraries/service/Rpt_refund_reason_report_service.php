<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Report_service.php";


class Rpt_refund_reason_report_service extends Report_service
{
    private $refund_service;

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/service/Refund_service.php"); ### use refund service
        $this->set_refund_service(new Refund_service());
        $this->set_output_delimiter(',');
    }

    public function get_csv($where = array())
    {
        set_time_limit(300);
        $top5 = $this->get_refund_service()->get_dao()->get_refund_reason_top5($where);
        $num_rows = $this->get_refund_service()->get_dao()->get_refund_reason_num_rows($where);

        include_once(APPPATH . "libraries/dto/refund_reason_report_dto.php");
        //print_r($top5);
        //print_r($top5[1]->get_id());

        // calculate the percentage of the top 5 reasons with the total number of reasons
        for ($i = 0; $i < sizeof($top5); $i++) {
            $arr[$i] = new Refund_reason_report_dto();
            $arr[$i]->set_rank($i + 1);
            $arr[$i]->set_reason($top5[$i]->get_reason());
            $percentage = number_format(((floatval($top5[$i]->get_frequency()) / floatval($num_rows[0]->get_num_rows())) * 100), 2, '.', '') . '%';
            $arr[$i]->set_percentage($percentage);

            // if reason id = ...., also check top 5 products involved
            switch ($top5[$i]->get_id()) {
                case 9:
                case 10:
                case 11:
                case 12:
                case 17:
                case 37:
                case 43:
                case 46:
                    // edit where conditions
                    $where2 = array();
                    $where2["R1.create_on >="] = $where["R1.create_on >="];
                    $where2["R1.create_on <="] = $where["R1.create_on <="];
                    $where2["S.refund_status >="] = $where["S.refund_status >="];
                    $where2["R2.id"] = $top5[$i]->get_id();

                    $top5_products = $this->get_refund_service()->get_dao()->get_refund_reason_top5_products($where2);
                    $products = '';
                    for ($j = 0; $j < sizeof($top5_products); $j++) {
                        if ($j > 0)
                            $products .= ",\n";
                        $products .= $top5_products[$j]->get_item_name();
                    }

                    $arr[$i]->set_products($products);
                    break;
            }
        }

        //print $this->get_so_service()->get_dao()->db->last_query();
        //var_dump($arr);
        return $this->convert($arr);
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

    public function email_report($filename, $csv = "", $message = "")
    {
        include_once(BASEPATH . "plugins/phpmailer/phpmailer_pi.php");
        $phpmail = new PHPMailer();
        $phpmail->IsSMTP();
        $phpmail->From = "Admin <admin@valuebasket.net>";

        $phpmail->AddAddress("vb_refund_report@eservicesgroup.com");

        $phpmail->Subject = "[VB] Refund Reason Report (Weekly)";
        $phpmail->IsHTML(false);
        $phpmail->Body = $message;
        $phpmail->AddStringAttachment($csv, $filename, 'base64', 'text/csv');

        // $phpmail->SMTPDebug  = 1;
        $result = $phpmail->Send();
    }

    protected function get_default_vo2xml_mapping()
    {
        return '';
    }

    protected function get_default_xml2csv_mapping()
    {
        return APPPATH . 'data/refund_reason_report_xml2csv.txt';
    }
}
