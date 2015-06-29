<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Order_email_service extends Base_service
{
    public $so_dao;

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/So_dao.php");
        $this->set_so_dao(new So_dao());
    }

    public function send_daily_order_by_supplier($supplier)
    {
        $orderList = $this->get_so_dao()->send_daily_order_by_supplier($supplier);
        $csv = $this->gen_csv($orderList);

        $title = 'Daily Order Report Sourced From ' . $supplier;
        $message = 'Daily Order Report Sourced From ' . $supplier;
        $filename = 'daily_order_sourced_from_' . $supplier . '_' . date('Ymd') . '.csv';
        $this->_email_report($csv, $title, $message, $filename, 'US-Sourcing@eservicesgroup.com');
    }

    private function _email_report($csv = "", $title = "", $message = "", $filename = "", $email1, $email2 = null)
    {
        include_once(BASEPATH . "plugins/phpmailer/phpmailer_pi.php");
        $phpmail = new PHPMailer();
        $phpmail->IsSMTP();
        $phpmail->From = "Admin <admin@valuebasket.com>";

        $phpmail->AddAddress($email1);

        if ($email2)
            $phpmail->AddAddress($email2);

        $phpmail->AddAddress('jerry.lim@eservicesgroup.com');

        $phpmail->Subject = $title;
        $phpmail->IsHTML(false);
        $phpmail->Body = $message;
        if($csv){
        $phpmail->AddStringAttachment($csv, $filename, 'base64', 'text/csv');
        }
        //var_dump($phpmail); die();
        $result = $phpmail->Send();
    }

    private function gen_csv($data=array())
    {
        $csv = "";
        if(!empty($data))
        {
            foreach ($data as $key => $value)
            {
                if($key==0)
                {
                    foreach ($value as $label => $v)
                    {
                        $csv .= '"' . "$label" . '",';
                    }

                    $csv .= "\n";
                }

                $csv .= '"' . implode('","', $value) . '"';
                $csv .= "\n";
            }
        }
        return $csv;
    }

    //dev test url http://admindev.valuebasket.com/cron/platform_integration/client_delivery_contact_by_platform/RAKUES/5
    public function gen_client_contact_delivery($platform_id = "ES", $day_diff=0)
    {
        $csv = "";
        $this->now_time = time();

        $data = $this->get_client_delivery_data($platform_id, $day_diff);

        if($data)
        {
            $csv = $this->gen_csv($data);
            //$this->gen_client_contact_feed($csv, $platform_id);
            // var_dump($csv); die();
            $message = "Attached file for ".$platform_id." Client details ".$day_diff." days after order ship generated at ". date("Y-m-d H:i:s", $this->now_time);

            $title = $platform_id." Client details ".$day_diff." days after order ship";
            $title = "VB ".$platform_id." Feedback Initiative Contact List";
            $email1 = 'marketplace-cses@eservicesgroup.com';
            $email2 = 'bd.platformteam@eservicesgroup.net';

            $this->title = $platform_id." Client details ".$day_diff." days after order ship";
            $filename = $this->filename = 'VB '.$platform_id.' Feedback Initiative Contact List'  . '_' . date('Ymdhis', $this->now_time) . '.csv';

            if(strpos($_SERVER['HTTP_HOST'], 'dev') === false)
                $this->_email_report($csv, $title, $message, $filename, $email1, $email2);

            // header("Content-type: text/csv");
            // header("Cache-Control: no-store, no-cache");
            // header("Content-Disposition: attachment; filename=\"$this->filename\"");
            // echo $csv;
        }
        else
        {
            $csv = '';
            $message = "No ".$platform_id." orders to notify today. generated at ". date("Y-m-d H:i:s", $this->now_time);

            $title = "VB ".$platform_id." Feedback Initiative Contact List";
            $email1 = 'marketplace-cses@eservicesgroup.com';
            $email2 = 'bd.platformteam@eservicesgroup.net';

            //$this->title = $platform_id." Client details ".$day_diff." days after order ship";
            //$this->filename = 'VB '.$platform_id.' Feedback Initiative Contact List'  . '_' . date('Ymdhis', $this->now_time) . '.csv';

            if(strpos($_SERVER['HTTP_HOST'], 'dev') === false)
                $this->_email_report($csv, $title, $message, $filename, $email1, $email2);
        }

    }

    private function get_client_delivery_data($platform_id = "all", $day_diff=0)
    {
        $data = $arr = array();
        // if(strtolower($platform_id) == "all")
        //  $platform_id = "";

        if($platform_id)
        {
            $where["so.platform_id ="] = $platform_id;
        }


        if($arr = $this->get_so_dao()->get_platform_client_delivery_orders($where, $day_diff))
        {
            //var_dump($this->db->last_query()); die();
            $data = $this->process_data_row($arr);
        }

        return $data;

    }

    private function process_data_row($arr = array())
    {
        $new_arr = array();
        if(!empty($arr))
        {
            $so_status      = array(
                                    0=>"inactive",
                                    1=>"new",
                                    2=>"paid",
                                    3=>"credit_checked",
                                    4=>"partial_allocated",
                                    5=>"full_allocated",
                                    6=>"shipped"
                                );
            $refund_status = array(
                                    0=>"no_refund",
                                    1=>"requested",
                                    2=>"logistic_approved",
                                    3=>"cs_approved",
                                    4=>"refunded"
                                );

            foreach ($arr as $key => $value)
            {
                if($value["status"] !== NULL)
                {
                    $value["status"] = $so_status[$value["status"]];    # e.g. $so_status[2] = "paid"
                }

                // if($value["refund_status"] !== NULL)
                // {
                //  $value["refund_status"] = $refund_status[$value["refund_status"]];
                // }

                $new_arr[] = $value;
            }
        }

        return $new_arr;
    }

    public function get_order_beforeship()
    {
        $email = array("gonzalo@eservicesgroup.com");
        $orderList = $this->get_so_dao()->get_order_beforeship($supplier);
        $csv = $this->gen_csv($orderList);
        $message = "VB Orders Before Ship. Report generated @ GMT+0 ".date("Y-m-d H:i:s");
        $filename = "VB_report_beforeship_".date("Ymd_His").".csv";
        $this->_email_report_v2($csv, "VB Orders Before Ship", $message, $filename, $email);

        if(isset($_GET["getfile"]))
        {
            header("Content-type: text/csv");
            header("Cache-Control: no-store, no-cache");
            header("Content-Disposition: attachment; filename=\"$filename\"");
            echo $csv;
        }
    }

    private function _email_report_v2($csv = "", $title = "", $message = "", $filename, $email=array())
    {
        include_once(BASEPATH . "plugins/phpmailer/phpmailer_pi.php");
        $phpmail = new PHPMailer();
        $phpmail->IsSMTP();
        $phpmail->From = "Admin <admin@valuebasket.com>";

        if($email)
        {
            foreach ($email as $add)
            {
                $phpmail->AddAddress($add);
            }
        }
        else
        {
            $phpmail->AddAddress("itsupport@eservicesgroup.net");
        }

        $phpmail->Subject = $title;
        $phpmail->IsHTML(false);
        $phpmail->Body = $message;
        $phpmail->AddStringAttachment($csv, $filename, 'base64', 'text/csv');

        if(strpos($_SERVER["HTTP_HOST"], "admindev") === FALSE)
            $result = $phpmail->Send();

        return;
    }

    public function get_so_dao()
    {
        return $this->so_dao;
    }

    public function set_so_dao($value)
    {
        $this->so_dao = $value;
    }
}

/* End of file order_email_service.php */
/* Location: ./system/application/libraries/service/Order_email_service.php */
