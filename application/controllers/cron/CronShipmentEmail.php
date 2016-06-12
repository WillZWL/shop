<?php
class CronShipmentEmail extends MY_Controller
{
    private $appId="CRN0034";

    public function __construct()
    {
        parent::__construct();
    }

    public function index($date = '')
    {
        if (empty($date)) {
            $date = date("Y-m-d", (time()-3600*24));
        }
        $where["so.dispatch_date >="] = $date." 00:00:00";
        $where["so.dispatch_date <="] = $date." 23:59:59";
        $this->sc['CronShipmentEmail']->sendEmail($where);
    }

    public function getAppId()
    {
        return $this->appId;
    }
}
