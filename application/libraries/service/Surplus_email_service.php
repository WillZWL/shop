<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Surplus_email_service extends Base_service
{
    public $so_dao;

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/So_dao.php");
        $this->set_so_dao(new So_dao());
    }

    public function send_surplus_oos_email()
    {
        $where = $option = array();
        $list = $this->get_so_dao()->get_surplus_oos($where, $option);
        $csv = $this->gen_csv($list);
        $title = '[VB] Surplus but OOS on Website Alert';
        $message = 'Surplus but OOS on Website Alert. Generated @ GMT+0 ' . date("Y-m-d H:i:s");
        $filename = 'surplus_oos_' . date('Ymd_His') . '.csv';
        /*
        header("Content-type: text/csv");
        header("Cache-Control: no-store, no-cache");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        echo $csv;
        die();
        */
        $email = array("bd@eservicesgroup.net");
        $this->_email_report($csv, $title, $message, $filename, $email);
        if (isset($_GET["getfile"])) {
            header("Content-type: text/csv");
            header("Cache-Control: no-store, no-cache");
            header("Content-Disposition: attachment; filename=\"$filename\"");
            echo $csv;
        }
    }

    public function get_so_dao()
    {
        return $this->so_dao;
    }

    public function set_so_dao($value)
    {
        $this->so_dao = $value;
    }

    private function gen_csv($data = array())
    {
        $csv = "sku,name,surplus quantity\n";
        if (!empty($data)) {
            $data = $this->process_data_row($data);
            foreach ($data as $key => $value) {
                $csv .= $value['sku'] . "," . $value['name'] . "," . $value['surplus_quantity'] . "\n";
            }
        }
        return $csv;
    }

    private function process_data_row($arr = array())
    {
        if (!empty($arr)) {
            foreach ($arr as $key => $value) {
                $new_arr['sku'] = $value->sku;
                $new_arr['name'] = $value->name;
                $new_arr['surplus_quantity'] = $value->surplus_quantity;
                $n_arr[] = $new_arr;
            }
        }
        return $n_arr;
    }

    private function _email_report($csv = "", $title = "", $message = "", $filename, $email = array())
    {
        include_once(BASEPATH . "plugins/phpmailer/phpmailer_pi.php");
        $phpmail = new PHPMailer();
        $phpmail->IsSMTP();
        $phpmail->From = "Admin <admin@valuebasket.com>";

        if ($email) {
            foreach ($email as $add) {
                $phpmail->AddAddress($add);
            }
        } else {
            $phpmail->AddAddress("itsupport@eservicesgroup.net");
        }

        $phpmail->Subject = $title;
        $phpmail->IsHTML(false);
        $phpmail->Body = $message;
        $phpmail->AddStringAttachment($csv, $filename, 'base64', 'text/csv');

        if (strpos($_SERVER["HTTP_HOST"], "admindev") === FALSE)
            $result = $phpmail->Send();

        return;
    }
}



