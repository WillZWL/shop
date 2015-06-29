<?php

include_once "Base_service.php";

class Affiliate_service extends Base_service
{

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Affiliate_dao.php");
        $this->set_dao(new Affiliate_dao());
        include_once(APPPATH . 'libraries/dao/So_dao.php');
        $this->set_so_dao(New So_dao());
        include_once(APPPATH . "libraries/service/Context_config_service.php");
        $this->set_config_srv(new Context_config_service());
    }

    public function set_so_dao(Base_dao $dao)
    {
        $this->so_dao = $dao;
    }

    public function set_config_srv(Base_service $srv)
    {
        $this->config_srv = $srv;
    }

    public function add_af_cookie($src)
    {
        include_once(APPPATH . "helpers/string_helper.php");
        $domain = check_domain();

        $affiliate = NULL;

        if (is_array($src) && count($src) > 0) {
            if ($src['AF']) {
                $affiliate = $src['AF'];
                switch ($src['AF']) {
                    case 'LS':
                        $af_id = 'LS';
                    case 'LSAU':
                        $af_id = 'LSAU';
                        if ($src['siteID']) {
                            $site_id = mysql_real_escape_string($src['siteID']);
                            $this->_set_linkshare_tracking_cookie($site_id, $domain, $af_id);
                        }
                        break;
                    case 'LSNZ':
                        $af_id = 'LSNZ';
                        if ($src['siteID']) {
                            $site_id = mysql_real_escape_string($src['siteID']);
                            $this->_set_linkshare_tracking_cookie($site_id, $domain, $af_id);
                        }
                        break;
                    default:
                }
            }

        } else if (!empty($src)) {
            $affiliate = $src;
        } else {
            return;
        }

        if ($affiliate) {
            setcookie("af", $affiliate, time() + (60 * 60 * 24 * 30), "/", "." . $domain);
            $_COOKIE["af"] = $affiliate;
        }

    }

    public function _set_linkshare_tracking_cookie($site_id, $domain, $af_id)
    {
        $siteID = $_GET["siteID"];
        $expire_time = 60 * 60 * 24 * 365 * 2;
        $time_enter = gmdate('Y-m-d/H:i:s', gmmktime());
        $valid_id = "/^[-a-zA-Z0-9._\/*]{34}$/";
        if (preg_match($valid_id, $siteID)) {
            setcookie('af', $af_id, time() + $expire_time, "/", "." . $domain);
            setcookie('af_ref', $siteID, time() + $expire_time, "/", "." . $domain);
            setcookie('LS_siteID', $siteID, time() + $expire_time, "/", "." . $domain);
            setcookie('LS_timeEntered', $time_enter, time() + $expire_time, "/", "." . $domain);
        }
    }

    public function remove_af_record()
    {
        $domain = check_domain();
        setcookie("af", "", time() - 3600, "/", "." . $domain);
        setcookie('af_ref', "", time() - 3600, "/", "." . $domain);
    }

    public function get_af_record()
    {
        $data = array();
        if (isset($_COOKIE["af"]) && $this->get(array("id" => $_COOKIE["af"]))) {
            if (isset($_COOKIE["af_ref"])) {
                $af_ref = $_COOKIE["af_ref"];
            }
            return array("af" => $_COOKIE["af"], "af_ref" => $af_ref);
        }
        return $data;
    }

    //dev test url http://admindev.valuebasket.com/cron/cron_affiliate_order/affiliate_delay_report/KO/5

    public function set_feed_platform($affiliate_id, $platform_id = "")
    {
        return $this->get_dao()->set_feed_platform($affiliate_id, $platform_id);
    }

    public function get_feed_platform()
    {
        return $this->get_dao()->get_feed_platform();
    }

    public function gen_delay_orders_data($affiliate_prefix = "all", $day_diff = 0)
    {
        $csv = "";
        $this->now_time = time();
        //define('DATAPATH', $this->get_config_srv()->value_of("data_path"));

        $data = $this->get_delay_orders_data($affiliate_prefix, $day_diff);
        // var_dump($data); die();
        if ($data) {
            $csv = $this->gen_csv($data);
            //$this->gen_delay_order_feed($csv, $affiliate_prefix);
            // var_dump($csv); die();
            $message = "Attached file for Kelkoo Delay orders for " . $day_diff . " days generated at " . date("Y-m-d H:i:s", $this->now_time);

            // $country_id = strtoupper($country_id);

            $this->title = $day_diff . " Days Delay Orders Report for Kelkoo";
            $filename = 'affiliate_delay_orders_' . "$affiliate_prefix" . '_' . date('Ymdhis', $this->now_time) . '.csv';

            if (strpos($_SERVER['HTTP_HOST'], 'dev') === false)
                $this->email_report($affiliate_prefix, $csv, $message, $filename);

            // header("Content-type: text/csv");
            // header("Cache-Control: no-store, no-cache");
            // header("Content-Disposition: attachment; filename=\"$this->filename\"");
            //echo $csv;
        } else {
            $csv = "";
            //$this->gen_delay_order_feed($csv, $affiliate_prefix);
            // var_dump($csv); die();
            $message = "No Kelkoo Delay orders for " . $day_diff . " days generated at " . date("Y-m-d H:i:s", $this->now_time);

            // $country_id = strtoupper($country_id);

            $this->title = $day_diff . " Days Delay Orders Report for Kelkoo - No Records";
            $filename = "";

            if (strpos($_SERVER['HTTP_HOST'], 'dev') === false)
                $this->email_report($affiliate_prefix, $csv, $message, $filename);
        }
    }

    private function get_delay_orders_data($affiliate_prefix = "all", $day_diff = 0)
    {
        $data = $arr = array();
        if (strtolower($affiliate_prefix) == "all")
            $affiliate_prefix = "";

        if ($affiliate_prefix) {
            $where["soex.conv_site_id LIKE"] = $affiliate_prefix . "%";
        }


        if ($arr = $this->get_so_dao()->get_delay_affiliate_orders($where, $day_diff)) {
            //var_dump($this->db->last_query()); die();
            $data = $this->process_data_row($arr);
        }

        return $data;

    }

    public function get_so_dao()
    {
        return $this->so_dao;
    }

    private function process_data_row($arr = array())
    {
        $new_arr = array();
        if (!empty($arr)) {
            $so_status = array(
                0 => "inactive",
                1 => "new",
                2 => "paid",
                3 => "credit_checked",
                4 => "partial_allocated",
                5 => "full_allocated",
                6 => "shipped"
            );
            $refund_status = array(
                0 => "no_refund",
                1 => "requested",
                2 => "logistic_approved",
                3 => "cs_approved",
                4 => "refunded"
            );

            foreach ($arr as $key => $value) {
                if ($value["status"] !== NULL) {
                    $value["status"] = $so_status[$value["status"]];    # e.g. $so_status[2] = "paid"
                }

                if ($value["refund_status"] !== NULL) {
                    $value["refund_status"] = $refund_status[$value["refund_status"]];
                }

                $new_arr[] = $value;
            }
        }

        return $new_arr;
    }

    private function gen_csv($data = array())
    {
        $csv = "";
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                if ($key == 0) {
                    # form the header from the keys of array so don't have to manually add on
                    # name your keys at the query stage in get_paid_affiliate_orders() in so_dao.php
                    foreach ($value as $label => $v) {
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

    private function email_report($country_id = "", $csv = "", $message = "", $filename = "")
    {
        include_once(BASEPATH . "plugins/phpmailer/phpmailer_pi.php");
        $phpmail = new PHPMailer();
        $phpmail->IsSMTP();
        $phpmail->From = "Admin <admin@valuebasket.net>";

        $phpmail->AddAddress("vbteam@supportsave.com");
        $phpmail->AddAddress("csmanager@eservicesgroup.net");
        $phpmail->AddAddress("aymeric@eservicesgroup.com");
        $phpmail->AddAddress("jerry.lim@eservicesgroup.com");

        $phpmail->Subject = $this->title;
        $phpmail->IsHTML(false);
        $phpmail->Body = $message;
        if ($csv != "") {
            $phpmail->AddStringAttachment($csv, $filename, 'base64', 'text/csv');
        }
        // $phpmail->SMTPDebug  = 1;
        $result = $phpmail->Send();
    }

    protected function get_ftp_name()
    {
        return 'KO';
    }

    private function gen_delay_order_feed($data, $affiliate_prefix)
    {
        define('DATAPATH', $this->get_config_srv()->value_of("data_path"));

        //$data_feed = $this->get_data_feed(TRUE, $country, $explain_sku);
        if ($data) {
            $filename = 'vb_affiliate_delay_orders_' . "$affiliate_prefix" . '_' . date('Ymdhis') . '.txt';
            $remotefilename = strtolower('/vb_affiliate_delay_order_' . "$affiliate_prefix" . '.txt');
            $fp = fopen(DATAPATH . 'affiliate_delay_orders_records/' . $affiliate_prefix . '/' . $filename, 'w');

            if (fwrite($fp, $data)) {
                // if($country == "FR")
                // {
                //$this->ftp_feeds(DATAPATH . 'affiliate_delay_orders_records/' . $affiliate_prefix . '/' . $filename, $remotefilename, $this->get_ftp_name() . "_$affiliate_prefix");
                // }
                //$this->attach_file(DATAPATH . 'feeds/graysonline/' . $filename);

                if ($data != "") {
                    header("Content-type: text/csv");
                    header("Cache-Control: no-store, no-cache");
                    header("Content-Disposition: attachment; filename=\"$filename\"");
                    echo $data;
                }
            } else {
                $subject = "<DO NOT REPLY> Fails to create Kelkoo Product Feed File";
                $message = "FILE: " . __FILE__ . "<br>
                             LINE: " . __LINE__;
                $this->error_handler($subject, $message);
            }
        }
    }

    public function get_config_srv()
    {
        return $this->config_srv;
    }

}

/* End of file affiliate_dao.php */
/* Location: ./app/libraries/dao/Affiliate_dao.php */