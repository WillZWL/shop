<?php

class Cron_aftership extends MY_Controller
{
    private $app_id = "CRN0025";

    function __construct()
    {
        parent::__construct();
        $this->load->model('report/aftership_report_model');
        $this->load->library('service/context_config_service');
    }

    public function aftership_report($Destination = "")
    {
        if (!$Destination) {
            $where["cy.id_3_digit not in ('ITA', 'FRA', 'ESP', 'POL')"] = null;
            $Destination = "REST";
        } else {
            $where["cy.id_3_digit"] = $Destination;
        }

        $where['sosh.tracking_no is not null'] = null;

        DEFINE('AFTERSHIP_REPORT', $this->context_config_service->value_of("aftership_report"));
        DEFINE('AFTERSHIP_REPORT_HISTORY', AFTERSHIP_REPORT . "/history");

        $history_name = "";

        if ($_GET['date_from'] && $_GET['date_to']) {
            $start_date = date("Y-m-d", strtotime($_GET['date_from']));
            $end_date = date("Y-m-d", strtotime($_GET['date_to']));
            $history_name = $start_date . "--" . $end_date;
        } else {
            $start_date = date("Y-m-d");
            $end_date = date("Y-m-d");

            $history_name = date("Y-m-d");
        }


        $file_name = "aftership_report_{$Destination}" . '.csv';
        $history_file_name = $history_name . "_{$Destination}_" . time() . ".csv";


        $data = $this->aftership_report_model->get_aftership_report_for_ftp($start_date, $end_date, $where);

        //echo 'hell9o'; var_dump($this->aftership_report_model->db->last_query());die();


        if (file_exists(AFTERSHIP_REPORT)) {
            if (!file_put_contents(AFTERSHIP_REPORT . "/" . $file_name, $data)) {
                mail("jerry.lim@eservicesgroup.com", "aftership_report", "failed to upload $file_name to" . AFTERSHIP_REPORT);
            } else {
                file_put_contents(AFTERSHIP_REPORT_HISTORY . "/" . $history_file_name, $data);
            }
        } else {
            mail("jerry.lim@eservicesgroup.com", "aftership_report", "Path No Exists: " . AFTERSHIP_REPORT);
        }

        /*
        if(file_exists(AFTERSHIP_REPORT."/".$file_name))
        {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.$file_name);
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: '.filesize(AFTERSHIP_REPORT."/".$file_name));
            ob_clean();
            flush();
            readfile(AFTERSHIP_REPORT."/".$file_name);
        }
        */
    }


    public function _get_app_id()
    {
        return $this->app_id;
    }
}

/* End of file cron_aftership.php */
/* Location: ./app/controllers/cron_aftership.php */
