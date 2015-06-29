<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Data_feed_service.php";

class Reevoo_customer_feed_service extends Data_feed_service
{
    protected $id = "Reevoo Customer Feed";

    public function __construct()
    {
        parent::Data_feed_service();
        $this->set_output_delimiter(chr(9));
    }

    public function gen_data_feed()
    {
        define('DATAPATH', $this->get_config_srv()->value_of("data_path"));

        $data_feed = $this->get_data_feed();

        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=reevoo_customer_feed.txt");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo $data_feed;

        // die();
        // when debugging, stop the output before the FTP which usually takes VERY long

        $filename = 'reevoo_customer_feed_' . date('Ymdhis');
        $fp = fopen(DATAPATH . 'feeds/reevoo/customer/' . $filename . '.txt', 'w');

        if (fwrite($fp, $data_feed)) {
            $this->ftp_feeds(DATAPATH . 'feeds/reevoo/customer/' . $filename . '.txt', "/partners/valuebasket/CustomerFeed.txt", $this->get_ftp_name());

        } else {
            $subject = "<DO NOT REPLY> Fails to create Reevoo Customer Feed File";
            $message = "FILE: " . __FILE__ . "<br>
                         LINE: " . __LINE__;
            $this->error_handler($subject, $message);
        }
    }

    protected function get_ftp_name()
    {
        return 'REEVOO';
    }

    public function process_data_row($data = NULL)
    {
        if (!is_object($data)) {
            return NULL;
        }

        // trim result
        $forename = $data->get_forename();
        $data->set_forename($this->format_result($forename));
        $surname = $data->get_surname();
        $data->set_surname($this->format_result($surname));
        $email = $data->get_email();
        $data->set_email($this->format_result($email));

        // format date
        $purchase_date = $data->get_purchase_date();
        $data->set_purchase_date(date("d/m/Y", strtotime($purchase_date)));

        $dispatch_date = $data->get_dispatch_date();
        if ($dispatch_date == null) $dispatch_date = "";
        if ($dispatch_date != "")
            $data->set_dispatch_date(date("d/m/Y", strtotime($dispatch_date)));

        return $data;
    }

    function format_result($str)
    {
        $search = array(chr(9), chr(10), chr(13));
        $replace = array(" ", " ", " ");
        $str = str_replace($search, $replace, $str);
        $str = trim($str);

        return $str;
    }

    public function get_contact_email()
    {
        return 'itsupport@eservicesgroup.net';
    }

    protected function get_data_list($where = array(), $option = array())
    {
        $last_access_time = $this->get_last_access_time();

        return $this->get_so_srv()->get_reevoo_customer_feed_dto($last_access_time);
    }

    protected function get_last_access_time()
    {
        $sj_obj = $this->get_sj_dao()->get(array("id" => $this->get_sj_id()));
        if ($sj_obj) {
            return $sj_obj->get_last_access_time();
        }
        return false;
    }

    protected function get_sj_id()
    {
        return "REEVOO_CUSTOMER_FEED";
    }

    protected function get_default_vo2xml_mapping()
    {
        return '';
    }

    protected function get_default_xml2csv_mapping()
    {
        return APPPATH . 'data/reevoo_customer_feed_vo2xml.txt';
    }

    protected function get_sj_name()
    {
        return "REEVOO Customer Feed Cron Time";
    }

}


