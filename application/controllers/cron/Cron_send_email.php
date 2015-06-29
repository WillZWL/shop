<?php
class Cron_send_email extends MY_Controller
{
    private $app_id="CRN0003";

    function __construct()
    {
        parent::__construct();
        $this->load->library('service/feedback_email_service');
        $this->load->library('service/rpt_wow_email_service');
    }

    function send_customer_review_email()
    {
        if($obj_list = $this->feedback_email_service->get_automated_feedback_email_content())
        {
            foreach($obj_list as $obj)
            {
                if($ret = $this->feedback_email_service->required_feedback_email($obj))
                {
                    $this->feedback_email_service->fire_feedback_email($obj);
                }
            }
        }
    }

    // SBF#1895
    function send_rma_email($past_day = 7)
    {
        if($obj_list = $this->feedback_email_service->get_rma_customer_email_address($past_day))
        {
            // var_dump("HELLO");
            include_once(BASEPATH . "plugins/phpmailer/phpmailer_pi.php");
            $phpmail = new phpmailer();
            $phpmail->IsSMTP();
            $phpmail->From = "Admin <admin@valuebasket.net>";
            $phpmail->Subject = "List of customers with RMA within the ".intval($past_day)." days";

            // public function AddStringAttachment($string, $filename, $encoding = 'base64', $type = 'application/octet-stream') {
            $csv = "";
            foreach ($obj_list as $email)
                $csv .= "$email\r\n";

            $phpmail->AddAddress("challis@eservicesgroup.net");
            $phpmail->AddAddress("ming@eservicesgroup.net");
            $phpmail->AddAddress("sang@eservicesgroup.net");
            // $phpmail->AddAddress("tslau@eservicesgroup.net");

            $phpmail->AddStringAttachment($csv, "rma_email_address.csv");
            $phpmail->IsHTML(false);
            $text = memory_get_usage();
            $phpmail->Body = $text;

            $result = $phpmail->Send();
            // if ($result) echo "OK"; else echo "FAIL";
            // mail("tslau@eservicesgroup.net", $subj, $msg, $headers);
            // mail("challis@eservicesgroup.net,ming@eservicesgroup.net,sang@eservicesgroup.net", $subj, $msg, $headers);
            // var_dump($obj_list);
        }
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }

    public function wow_email_list_data()
    {
        if($data = $this->rpt_wow_email_service->get_data())
        {
            mail('ray@eservicesgroup.net, logistics@valuebasket.com', "[VB] Wow Email list today - ".date("Y-m-d"), $data);
        }
    }
}

/* End of file cron_update_pending_list.php */
/* Location: ./app/controllers/cron_update_pending_list.php */