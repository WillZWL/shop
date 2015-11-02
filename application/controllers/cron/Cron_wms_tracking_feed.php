<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cron_wms_tracking_feed extends MY_Controller
{
    private $app_id="CRN0042";

    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->sc['ClwmsTrackingFeed']->processTrackingFeed();
    }

    public function getAppId()
    {
        return $this->app_id;
    }

}