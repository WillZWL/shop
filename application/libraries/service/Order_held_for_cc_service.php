<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Order_held_for_cc_service extends Base_service
{
    const ORDER_TYPE_ALERT1 = "ALERT1";
    const ORDER_TYPE_ALERT2 = "ALERT2";

    public $so_dao;

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/So_dao.php");
        $this->set_so_dao(new So_dao());
    }

    public function send_report($duration)
    {
            $this->send_order_held_for_cc();
    }

    public function send_order_held_for_cc()
    {
        $where = array();
        $where["so.status"] = 2;
        $where["so.refund_status"] = 0;
        $where["shr.reason"] = 'cscc';
        $where["DATE(so.order_create_date) > '2014-06-01'"] = null;



        $option = array("group_by" => "so.so_no");

        $orderList = $this->get_so_dao()->order_held_for_cc_report($where, $option);
        //var_dump($this->get_so_dao()->db->last_query()); die();

        $csv = $this->gen_csv($orderList);

        $title = "Order Held for CC" ;
        $message = "Attached is your daily report for orders held for cc. Please follow up. Thanks";
        $filename = 'order_held_for_cc_' . date('Ymd') . '.csv';

        $this->_email_report($csv, $title, $message, $filename, "cs@eservicesgroup.net", "jerry.lim@eservicesgroup.com");
    }

    private function _email_report($csv = "", $title = "", $message = "", $filename, $email1, $email2 = null)
    {
        include_once(BASEPATH . "plugins/phpmailer/phpmailer_pi.php");
        $phpmail = new PHPMailer();
        $phpmail->IsSMTP();
        $phpmail->From = "Admin <admin@valuebasket.com>";

        $phpmail->AddAddress($email1);

        if ($email2)
            $phpmail->AddAddress($email2);

        $phpmail->Subject = $title;
        $phpmail->IsHTML(false);
        $phpmail->Body = $message;
        $phpmail->AddStringAttachment($csv, $filename, 'base64', 'text/csv');

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
                        $csv .= "$label,";
                    }

                    $csv .= "\n";
                }

                $csv .= implode(",", $value);
                $csv .= "\n";
            }
        }
        return $csv;
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

/* End of file Fireman_service.php */
/* Location: ./system/application/libraries/service/Fireman_service.php */
