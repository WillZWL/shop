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
        $this->sc['CronShipmentEmail']->sendEmail($date);
    }

    public function getAppId()
    {
        return $this->appId;
    }
}
