<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron_surplus_report extends MY_Controller
{
    private $appId = "CRN0019";
    private $notification_email = "itsupport@eservicesgroup.net";

    function __construct()
    {
        parent::__construct();
        $this->load->model('report/surplus_report_model');
    }

    public function send_unmapped_report()
    {
        $content = $this->surplus_report_model->get_unmapped_surplus('csv', 0);
        $filepath = $content["filepath"];
        $timestamp = $content["timestamp"];
        $csv = $content["csv"];
        include_once(BASEPATH . "plugins/phpmailer/phpmailer_pi.php");
        $phpmail = new phpmailer();

        $country_id = $this->country_id;
        $phpmail->IsSMTP();
        $phpmail->From = "Admin <admin@valuebasket.net>";
        $phpmail->AddAddress("bd_product_team@eservicesgroup.com");
        $phpmail->AddAddress("celine@eservicesgroup.com");

        $phpmail->Subject = "VB Unmapped Surplus Report";
        if (file_exists($filepath)) {
            $phpmail->IsHTML(false);
            $phpmail->Body = "Attached: List of surplus not mapped on VB.";
            $phpmail->AddAttachment($filepath, "unmapped_surplus_report_{$timestamp}.csv");
            $result = $phpmail->Send();

            header("Cache-Control: no-store, no-cache");
            header("Content-Disposition: attachment; filename=\"unmapped_surplus_report_{$timestamp}.csv\"");
            echo $csv;
        } else {
            $phpmail->AddAddress("itsupport@eservicesgroup.net");
            $text = "Could not retrive file - $filepath\n" . __FILE__ . " LINE: " . __LINE__;
            $phpmail->Body = $text;
            $result = $phpmail->Send();
        }
    }

    public function send_unlisted_report()
    {
        $content = $this->surplus_report_model->get_unlisted_surplus('csv', 0);
        $filepath = $content["filepath"];
        $timestamp = $content["timestamp"];
        $csv = $content["csv"];
        include_once(BASEPATH . "plugins/phpmailer/phpmailer_pi.php");
        $phpmail = new phpmailer();

        $country_id = $this->country_id;
        $phpmail->IsSMTP();
        $phpmail->From = "Admin <admin@valuebasket.net>";
        $phpmail->AddAddress("bd@eservicesgroup.net");
        $phpmail->AddAddress("celine@eservicesgroup.com");

        $phpmail->Subject = "VB Unlisted Platforms Surplus Report";
        if (file_exists($filepath)) {
            $phpmail->IsHTML(false);
            $phpmail->Body = "Attached: Platforms which surplus SKUs are not listed on.";
            $phpmail->AddAttachment($filepath, "unlisted_surplus_report_{$timestamp}.csv");
            $result = $phpmail->Send();

            header("Cache-Control: no-store, no-cache");
            header("Content-Disposition: attachment; filename=\"unlisted_surplus_report_{$timestamp}.csv\"");
            echo $csv;
        } else {
            $text = "Could not retrive file - $filepath\r\nOccurred at " . __FILE__ . " LINE: " . __LINE__;
            $phpmail->AddAddress("itsupport@eservicesgroup.net");
            $phpmail->Body = $text;
            $result = $phpmail->Send();
        }
    }


    public function getAppId()
    {
        return $this->appId;
    }

}