<?php

class CronAftership extends MY_Controller
{
    private $appId = "CRN0025";

    public function __construct()
    {
        parent::__construct();
    }

    public function aftershipReport($Destination = "")
    {
        if (!$Destination) {
            $Destination = "ALL";
        } else {
            $where["cy.id_3_digit"] = $Destination;
        }

        DEFINE('AFTERSHIP_REPORT', $this->sc['ContextConfig']->valueOf("aftership_report"));
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

        // $where["so.dispatch_date >="] = $start_date . ' 00:00:00';
        // $where["so.dispatch_date <= "] = $end_date . ' 23:59:59';

        ## SBF 9986 change save dispatch_date, this date maybe not is today
        $where["osh.create_on >"] = $from_date.' 00:00:00';
        $where["osh.create_on < "] = $to_date.' 23:59:59';
        $where["so.dispatch_date is not null"] = null;

        $where["so.status"] = 6;
        $where["sosh.courier_id is not null"] = null;
        $where["sosh.tracking_no is not null"] = null;
        $data = $this->sc['RptAftership']->getAftershipReportForFtp($where);
        $file_name = "aftership_report_{$Destination}" . '.csv';
        $history_file_name = $history_name . "_{$Destination}_" . time() . ".csv";
        if (file_exists(AFTERSHIP_REPORT)) {
            if (!file_put_contents(AFTERSHIP_REPORT . "/" . $file_name, $data)) {
                mail("will.zhang@eservicesgroup.com", "aftership_report", "failed to upload $file_name to" . AFTERSHIP_REPORT);
            } else {
                file_put_contents(AFTERSHIP_REPORT_HISTORY . "/" . $history_file_name, $data);
            }
        } else {
            mail("will.zhang@eservicesgroup.com", "aftership_report", "Path No Exists: " . AFTERSHIP_REPORT);
        }
    }

    public function getAppId()
    {
        return $this->appId;
    }
}



