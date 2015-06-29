<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Split_order_email_service extends Base_service
{
    public $so_dao;

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/So_dao.php");
        $this->set_so_dao(new So_dao());
    }

    public function send_order_for_split_email()
    {
        $where = $option = array();
        $orderlist = $this->get_so_dao()->get_orders_for_split($where, $option);

        $csv = $this->gen_csv($orderlist);

        $title = '[VB] Orders Available for Split';
        $message = 'Orders Available for Split. Generated @ GMT+0 '.date("Y-m-d H:i:s");
        $filename = 'orders_for_split_' . date('Ymd_His') . '.csv';

        $email = array("ordermanagement@valuebasket.com","purchase@aln.hk");
        $this->_email_report($csv, $title, $message, $filename, $email);

        if(isset($_GET["getfile"]))
        {
            header("Content-type: text/csv");
            header("Cache-Control: no-store, no-cache");
            header("Content-Disposition: attachment; filename=\"$filename\"");
            echo $csv;
        }

    }

    private function _email_report($csv = "", $title = "", $message = "", $filename, $email=array())
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

    private function gen_csv($data=array())
    {
        $csv = "";
        if(!empty($data))
        {
            $data = $this->process_data_row($data);
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

    private function process_data_row($arr = array())
    {
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
            $refund_status  = array(
                                    0=>"no_refund",
                                    1=>"requested",
                                    2=>"logistic_approved",
                                    3=>"cs_approved",
                                    4=>"refunded"
                                );
            $sourcing_status = array(
                                    'A' => 'Readily Available',
                                    'O' => 'Temp of Out Stock ',
                                    'C' => 'Limited Stock',
                                    'L' => 'Last Lot',
                                    'D' => 'Discontinued',
                                );

            foreach ($arr as $key => $value)
            {
                // if($value["status"] !== NULL)
                // {
                //  $value["status"] = $so_status[$value["status"]];    # e.g. $so_status[2] = "paid"
                // }

                // if($value["refund_status"] !== NULL)
                // {
                //  $value["refund_status"] = $refund_status[$value["refund_status"]];
                // }
                if($value["sourcing_status"] !== NULL)
                {
                    $value["sourcing_status"] = $sourcing_status[$value["sourcing_status"]];
                }

                $new_arr[] = $value;
            }
        }

        return $new_arr;
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

/* End of file split_order_email_service.php */
/* Location: ./system/application/libraries/service/Split_order_email_service.php */
