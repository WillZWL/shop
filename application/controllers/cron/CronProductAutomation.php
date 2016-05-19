<?php
class CronProductAutomation extends MY_Controller
{
    private $appId="CRN0033";

    public function __construct()
    {
        parent::__construct();
    }

    public function updateProductQty()
    {
        $this->sc['ProductAutomation']->updateProductQty();
    }

    public function sendAutoChangeEmail($batch_id = '')
    {
        $this->sc['ProductAutomation']->sendAutoChangeEmail(array('batch_id' => $batch_id));
    }

    public function getAppId()
    {
        return $this->appId;
    }
}
